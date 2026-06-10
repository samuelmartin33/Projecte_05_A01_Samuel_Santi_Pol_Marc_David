<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Mail\BonoAgotado;
use App\Models\BonoCompra;
use App\Models\BonoConsumo;
use App\Models\ProductoBarra;
use App\Models\TipoBebida;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * CanjeBonoController — Permite al camarero canjear bonos de bebidas escaneando el QR.
 *
 * Accesible tanto a empresas como a porteros/camareros (sin middleware no-portero).
 * El flujo es: el camarero elige tipo de bebida y cantidad ANTES de escanear el QR,
 * luego el sistema valida el saldo y registra el consumo.
 */
class CanjeBonoController extends Controller
{
    /**
     * Devuelve la empresa del usuario autenticado, sea empresa directa u organizador/portero.
     * Igual al patrón de ValidacionQRController.
     */
    private function empresa()
    {
        $user = Auth::user();
        if (!$user) abort(403);

        if ($user->isEmpresa()) {
            $empresa = $user->empresa;
        } elseif ($user->isOrganizador()) {
            $empresa = $user->organizador?->empresa ?? null;
        } else {
            abort(403, 'Acceso restringido a empresas y camareros.');
        }

        if (!$empresa) {
            abort(403, 'Tu cuenta no tiene un perfil de empresa configurado.');
        }

        return $empresa;
    }

    /**
     * GET /empresa/bonos/canjear
     * Vista del escáner de bonos para el camarero.
     * Pasa los tipos de bebida activos para renderizarlos dinámicamente.
     */
    public function index()
    {
        $empresa = $this->empresa();
        $tipos   = TipoBebida::activos()->orderBy('nombre')->get();
        return view('empresa.bonos.canjear', compact('empresa', 'tipos'));
    }

    /**
     * POST /empresa/bonos/validar-canje  (AJAX)
     *
     * Valida el QR del bono, descuenta bebidas y registra el consumo.
     * También descuenta una unidad del stock físico de la empresa.
     * Si el bono se agota (bebidas_restantes = 0), envía email al cliente.
     */
    public function validar(Request $request): JsonResponse
    {
        // Tipos válidos: cargados dinámicamente desde tipos_bebida (no hardcoded)
        $tiposValidos = TipoBebida::activos()->pluck('nombre')->toArray();

        $request->validate([
            'codigo_qr'    => ['required', 'string', 'max:255'],
            'cantidad'     => ['required', 'integer', 'min:1', 'max:10'],
            'tipo_producto'=> ['required', 'string', 'in:' . implode(',', $tiposValidos)],
        ]);

        $bono = BonoCompra::where('codigo_qr', trim($request->codigo_qr))
            ->with(['usuario', 'tipo', 'evento.organizador'])
            ->first();

        // QR no encontrado
        if (!$bono) {
            return response()->json([
                'ok'    => false,
                'tipo'  => 'no_encontrado',
                'error' => 'QR no reconocido. El código no existe en el sistema.',
            ], 404);
        }

        // empresa_id del evento: se obtiene por evento→organizador→empresa_id
        // (organizador_id en eventos apunta al creador del evento)
        $empresaIdDelEvento = $bono->evento?->organizador?->empresa_id;

        if (!$empresaIdDelEvento) {
            return response()->json([
                'ok'    => false,
                'tipo'  => 'no_autorizado',
                'error' => 'No se pudo determinar la empresa del evento.',
            ], 403);
        }

        // Query directa a organizadores: hasOne solo devuelve el primer registro y puede
        // no coincidir si el usuario tiene varios roles/empresas asociadas.
        $esCamareroDeEmpresa = \App\Models\Organizador::where('usuario_id', Auth::id())
            ->where('empresa_id', $empresaIdDelEvento)
            ->where('estado', 1)
            ->exists();

        if (!$esCamareroDeEmpresa) {
            return response()->json([
                'ok'    => false,
                'tipo'  => 'no_autorizado',
                'error' => 'Este bono no pertenece a ninguno de tus eventos.',
            ], 403);
        }

        // Verificar saldo disponible
        if ($bono->bebidas_restantes <= 0) {
            return response()->json([
                'ok'      => false,
                'tipo'    => 'sin_saldo',
                'error'   => 'Este bono no tiene bebidas disponibles.',
                'nombre'  => $bono->usuario?->nombre . ' ' . $bono->usuario?->apellido1,
            ]);
        }

        $cantidad = (int) $request->cantidad;
        if ($bono->bebidas_restantes < $cantidad) {
            return response()->json([
                'ok'               => false,
                'tipo'             => 'saldo_insuficiente',
                'error'            => 'El bono solo tiene ' . $bono->bebidas_restantes . ' bebidas disponibles.',
                'bebidas_restantes' => $bono->bebidas_restantes,
                'nombre'           => $bono->usuario?->nombre . ' ' . $bono->usuario?->apellido1,
            ]);
        }

        // Obtener el ID del camarero (organizador autenticado) si existe
        $camareroId = Auth::user()->organizador?->id;

        try {
            $bonoActualizado = DB::transaction(function () use ($bono, $cantidad, $request, $camareroId, $empresaIdDelEvento) {
                // Crear un BonoConsumo por cada bebida canjeable
                for ($i = 0; $i < $cantidad; $i++) {
                    BonoConsumo::create([
                        'bono_compra_id' => $bono->id,
                        'tipo_producto'  => $request->tipo_producto,
                        'camarero_id'    => $camareroId,
                    ]);
                }

                // Descontar del saldo restante del bono
                $bono->decrement('bebidas_restantes', $cantidad);

                // ── Descontar stock físico de la empresa ────────────────
                // Recogemos TODOS los empresa_id del camarero (no solo el primero que devuelve hasOne)
                // y también añadimos el empresa_id del evento como fallback de seguridad.
                // unique() evita duplicados si el camarero tiene varios registros en la misma empresa.
                $empresaIdsCamarero = \App\Models\Organizador::where('usuario_id', Auth::user()->id)
                    ->where('estado', 1)
                    ->pluck('empresa_id')
                    ->push($empresaIdDelEvento)   // añade empresa del evento como fallback
                    ->unique()
                    ->values();

                // Elegimos el producto del tipo correcto con MÁS stock (orderByDesc)
                // para minimizar el riesgo de dejar otro producto a 0 antes de tiempo.
                $productoStock = ProductoBarra::whereIn('empresa_id', $empresaIdsCamarero)
                    ->where('tipo_producto', $request->tipo_producto)
                    ->orderByDesc('stock')
                    ->first();

                if ($productoStock) {
                    // GREATEST(0, ...) evita que el stock quede negativo en la BD
                    DB::table('productos_barra')
                        ->where('id', $productoStock->id)
                        ->update([
                            'stock' => DB::raw('GREATEST(0, stock - ' . (int) $cantidad . ')'),
                        ]);
                } else {
                    // El tipo de bebida no tiene producto registrado en el stock: solo se registra
                    Log::info('Canje sin producto de stock: empresas=[' . $empresaIdsCamarero->implode(',') . '], tipo=' . $request->tipo_producto);
                }

                $bono->refresh();
                return $bono;
            });

            // Email de aviso al cliente si el bono llega a 0 bebidas
            if ($bonoActualizado->bebidas_restantes === 0) {
                try {
                    Mail::to($bono->usuario?->email)->send(new BonoAgotado($bonoActualizado));
                } catch (\Throwable $e) {
                    Log::error('Email bono agotado: ' . $e->getMessage());
                }
            }

            $nombre = $bono->usuario?->nombre . ' ' . $bono->usuario?->apellido1;

            return response()->json([
                'ok'               => true,
                'tipo'             => 'ok',
                'mensaje'          => $cantidad . 'x ' . $request->tipo_producto . ' canjeado(s) correctamente.',
                'nombre'           => $nombre,
                'bebidas_restantes' => $bonoActualizado->bebidas_restantes,
                'tipo_bono'        => $bono->tipo?->cantidad_bebidas . ' bebidas',
                'agotado'          => $bonoActualizado->bebidas_restantes === 0,
            ]);
        } catch (\Throwable $e) {
            Log::error('Error canjear bono: ' . $e->getMessage());
            return response()->json([
                'ok'    => false,
                'tipo'  => 'error',
                'error' => 'Error interno al procesar el canje.',
            ], 500);
        }
    }
}

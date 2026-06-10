<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Mail\BonoAgotado;
use App\Models\BonoCompra;
use App\Models\BonoConsumo;
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
     */
    public function index()
    {
        $empresa = $this->empresa();
        return view('empresa.bonos.canjear', compact('empresa'));
    }

    /**
     * POST /empresa/bonos/validar-canje  (AJAX)
     *
     * Valida el QR del bono, descuenta bebidas y registra el consumo.
     * Si el bono se agota (bebidas_restantes = 0), envía email al cliente.
     */
    public function validar(Request $request): JsonResponse
    {
        $empresa = $this->empresa();

        $request->validate([
            'codigo_qr'    => ['required', 'string', 'max:255'],
            'cantidad'     => ['required', 'integer', 'min:1', 'max:10'],
            'tipo_producto'=> ['required', 'string', 'in:Cocktail,Destilado,Sin alcohol'],
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

        // Verificar que el bono pertenece a un evento de esta empresa
        if ($bono->evento?->organizador?->empresa_id !== $empresa->id) {
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
            $bonoActualizado = DB::transaction(function () use ($bono, $cantidad, $request, $camareroId) {
                // Crear un BonoConsumo por cada bebida canjeable
                for ($i = 0; $i < $cantidad; $i++) {
                    BonoConsumo::create([
                        'bono_compra_id' => $bono->id,
                        'tipo_producto'  => $request->tipo_producto,
                        'camarero_id'    => $camareroId,
                    ]);
                }

                // Descontar del saldo restante
                $bono->decrement('bebidas_restantes', $cantidad);
                $bono->refresh();
                return $bono;
            });

            // Enviar email si el bono se ha agotado
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

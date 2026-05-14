<?php

namespace App\Http\Controllers;

use App\Models\Cupon;
use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * CuponController — Controlador público de cupones de descuento.
 *
 * Responsabilidades:
 *   - Mostrar la página pública con todos los cupones disponibles.
 *   - Validar un código de cupón vía AJAX para un evento concreto.
 */
class CuponController extends Controller
{
    /**
     * Página pública de cupones.
     * Muestra todos los cupones activos con sus eventos asociados.
     */
    public function index()
    {
        // Cupones vigentes (activos y dentro del rango de fechas)
        $cuponesActivos = Cupon::with(['eventos.categoria', 'eventos.portada'])
            ->vigentes()
            ->orderBy('fecha_fin', 'asc')
            ->get();

        // Todos los cupones (para la vista completa)
        $cuponesExpirados = Cupon::with(['eventos.categoria'])
            ->where('estado', 1)
            ->where('fecha_fin', '<', now())
            ->orderBy('fecha_fin', 'desc')
            ->take(6)
            ->get();

        return view('cupones.index', compact('cuponesActivos', 'cuponesExpirados'));
    }

    /**
     * Valida un código de cupón para un evento específico.
     *
     * Recibe: { codigo: 'VIBEZ10', evento_id: 5 }
     * Devuelve JSON con los datos del cupón o un error descriptivo.
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function validar(Request $request): JsonResponse
    {
        $request->validate([
            'codigo'    => ['required', 'string', 'max:50'],
            'evento_id' => ['required', 'integer', 'exists:eventos,id'],
        ]);

        $codigo   = strtoupper(trim($request->codigo));
        $eventoId = (int) $request->evento_id;

        // Buscar el cupón por código
        $cupon = Cupon::with('eventos')
            ->where('codigo', $codigo)
            ->first();

        if (!$cupon) {
            return response()->json([
                'valid'   => false,
                'message' => 'El código de cupón no existe.',
            ], 404);
        }

        // Verificar que está activo
        if ($cupon->estado !== 1) {
            return response()->json([
                'valid'   => false,
                'message' => 'Este cupón está desactivado.',
            ], 422);
        }

        // Verificar rango de fechas
        $ahora = now();
        if ($ahora->lt($cupon->fecha_inicio)) {
            return response()->json([
                'valid'   => false,
                'message' => 'Este cupón aún no está activo. Empieza el ' . $cupon->fecha_inicio->format('d/m/Y') . '.',
            ], 422);
        }

        if ($ahora->gt($cupon->fecha_fin)) {
            return response()->json([
                'valid'   => false,
                'message' => 'Este cupón ha expirado el ' . $cupon->fecha_fin->format('d/m/Y') . '.',
            ], 422);
        }

        // Verificar límite total de usos
        if ($cupon->limite_usos_total !== null && $cupon->usos_actuales >= $cupon->limite_usos_total) {
            return response()->json([
                'valid'   => false,
                'message' => 'Este cupón ya ha alcanzado el límite máximo de usos.',
            ], 422);
        }

        // Verificar que el cupón aplica al evento solicitado
        if (!$cupon->aplicaAEvento($eventoId)) {
            return response()->json([
                'valid'   => false,
                'message' => 'Este cupón no es válido para este evento.',
            ], 422);
        }

        // Verificar límite por usuario (solo si está autenticado)
        if (Auth::check() && $cupon->limite_usos_por_usuario !== null) {
            $usosDelUsuario = $cupon->usosDeUsuario(Auth::id());
            if ($usosDelUsuario >= $cupon->limite_usos_por_usuario) {
                return response()->json([
                    'valid'   => false,
                    'message' => 'Ya has usado este cupón el máximo de veces permitido.',
                ], 422);
            }
        }

        // ¡Cupón válido! Devolvemos los datos necesarios para el frontend
        return response()->json([
            'valid'            => true,
            'cupon_id'         => $cupon->id,
            'codigo'           => $cupon->codigo,
            'descripcion'      => $cupon->descripcion,
            'valor_descuento'  => $cupon->valor_descuento, // porcentaje
            'usos_restantes'   => $cupon->usos_restantes,
            'message'          => '¡Cupón aplicado! ' . $cupon->valor_descuento . '% de descuento.',
        ]);
    }
}

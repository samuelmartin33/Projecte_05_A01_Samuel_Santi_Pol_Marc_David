<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use App\Models\Evento;
use App\Models\Pedido;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EntradaController extends Controller
{
    public function comprar(Request $request): JsonResponse
    {
        $request->validate([
            'evento_id' => ['required', 'integer', 'exists:eventos,id'],
            'cantidad'  => ['required', 'integer', 'min:1', 'max:10'],
        ], [
            'evento_id.exists' => 'El evento no existe.',
            'cantidad.min'     => 'Debes seleccionar al menos 1 entrada.',
            'cantidad.max'     => 'No puedes comprar más de 10 entradas a la vez.',
        ]);

        $eventoId = (int) $request->evento_id;
        $cantidad = (int) $request->cantidad;

        $evento = Evento::where('estado', 1)->findOrFail($eventoId);

        if ($evento->aforo_maximo !== null && ($evento->aforo_maximo - $evento->aforo_actual) < $cantidad) {
            return response()->json([
                'success' => false,
                'message' => 'No hay suficientes entradas disponibles.',
            ], 422);
        }

        try {
            $pedido = DB::transaction(function () use ($evento, $cantidad) {
                $ahora = now();
                $total = round($evento->precio_base * $cantidad, 2);

                $pedido = Pedido::create([
                    'usuario_id'          => Auth::id(),
                    'total'               => $total,
                    'total_descuento'     => 0.00,
                    'total_final'         => $total,
                    'estado'              => 1,
                    'fecha_creacion'      => $ahora,
                    'fecha_actualizacion' => $ahora,
                ]);

                for ($i = 0; $i < $cantidad; $i++) {
                    Entrada::create([
                        'pedido_id'           => $pedido->id,
                        'evento_id'           => $evento->id,
                        'estado_entrada'      => 1,
                        'codigo_qr'           => Str::uuid()->toString(),
                        'precio_unitario'     => $evento->precio_base,
                        'precio_pagado'       => $evento->precio_base,
                        'estado'              => 1,
                        'fecha_creacion'      => $ahora,
                        'fecha_actualizacion' => $ahora,
                    ]);
                }

                $evento->increment('aforo_actual', $cantidad);

                return $pedido;
            });

            return response()->json([
                'success'   => true,
                'pedido_id' => $pedido->id,
                'redirect'  => route('entradas.confirmacion', $pedido->id),
            ]);
        } catch (\Throwable) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la compra. Inténtalo de nuevo.',
            ], 500);
        }
    }

    public function misEntradas(): View
    {
        $pedidos = Pedido::where('usuario_id', Auth::id())
            ->with(['entradas.evento'])
            ->orderByDesc('fecha_creacion')
            ->get();

        return view('entradas.mis-entradas', compact('pedidos'));
    }

    public function confirmacion(Pedido $pedido): View
    {
        if ($pedido->usuario_id !== Auth::id()) {
            abort(403);
        }

        $pedido->load(['entradas.evento']);

        return view('entradas.confirmacion', compact('pedido'));
    }
}

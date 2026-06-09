<?php

namespace App\Http\Controllers\Camarero;

use App\Http\Controllers\Controller;
use App\Mail\BonoAgotado;
use App\Models\BonoCompra;
use App\Models\BonoConsumo;
use App\Models\Evento;
use App\Models\Notificacion;
use App\Models\Organizador;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CanjeBonoController extends Controller
{
    public function index()
    {
        $organizador = Organizador::where('usuario_id', Auth::id())->firstOrFail();
        $eventos = Evento::where('camarero_id', $organizador->id)->with('categoria')->get();

        return view('camarero.canje-bono.index', compact('eventos', 'organizador'));
    }

    public function canjear(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'codigo_qr'     => ['required', 'string'],
            'tipo_producto' => ['required', 'in:cocktail,destilado,sin_alcohol'],
            'evento_id'     => ['required', 'integer'],
        ]);

        $organizador = Organizador::where('usuario_id', Auth::id())->firstOrFail();
        
        $evento = Evento::findOrFail($datos['evento_id']);
        if ($organizador->id !== $evento->camarero_id) {
            abort(403, 'No tienes permiso para gestionar bonos en este evento.');
        }

        $bono = BonoCompra::where('codigo_qr', $datos['codigo_qr'])
            ->where('evento_id', $datos['evento_id'])
            ->with(['usuario', 'tipo'])
            ->first();

        if (!$bono) {
            return response()->json([
                'ok'      => false,
                'mensaje' => 'Bono no encontrado para este evento.',
            ], 404);
        }

        if (!$bono->tieneSaldo()) {
            return response()->json([
                'ok'      => false,
                'mensaje' => 'Este bono no tiene bebidas disponibles.',
            ], 422);
        }

        DB::transaction(function () use ($bono, $datos, $organizador) {
            $bono->decrement('bebidas_restantes');
            
            BonoConsumo::create([
                'bono_compra_id' => $bono->id,
                'tipo_producto'  => $datos['tipo_producto'],
                'camarero_id'    => $organizador->id,
            ]);
        });

        $bono->refresh();

        if ($bono->bebidas_restantes === 0) {
            Notificacion::crear(
                $bono->usuario_id,
                Notificacion::GENERAL,
                '🍹 Tu bono se ha agotado',
                'Tu bono de ' . $bono->tipo->cantidad_bebidas . ' bebidas para ' . $evento->titulo . ' se ha agotado.',
                null
            );

            Mail::to($bono->usuario->email)->send(new BonoAgotado($bono->load(['usuario', 'tipo', 'evento'])));
        }

        return response()->json([
            'ok'                => true,
            'bebidas_restantes' => $bono->bebidas_restantes,
            'cliente'           => $bono->usuario->nombre . ' ' . $bono->usuario->apellido1,
            'tipo_producto'     => $datos['tipo_producto'],
            'mensaje'           => 'Bebida canjeada correctamente.',
        ]);
    }
}

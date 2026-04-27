<?php

namespace App\Http\Controllers;

use App\Models\EventoFavorito;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoritoController extends Controller
{
    /**
     * Activa o desactiva un evento como favorito para el usuario autenticado.
     */
    public function toggle(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'evento_id' => ['required', 'integer', 'exists:eventos,id'],
        ]);

        $usuario = $request->user();
        $ahora = now();

        $favorito = EventoFavorito::where('usuario_id', $usuario->id)
            ->where('evento_id', $validated['evento_id'])
            ->first();

        if ($favorito && (int) $favorito->estado === 1) {
            $favorito->update([
                'estado' => 0,
                'fecha_actualizacion' => $ahora,
            ]);

            return response()->json([
                'success' => true,
                'favorito' => false,
                'message' => 'Evento eliminado de favoritos.',
            ]);
        }

        if ($favorito) {
            $favorito->update([
                'estado' => 1,
                'fecha_actualizacion' => $ahora,
            ]);
        } else {
            EventoFavorito::create([
                'usuario_id' => $usuario->id,
                'evento_id' => $validated['evento_id'],
                'estado' => 1,
                'fecha_creacion' => $ahora,
                'fecha_actualizacion' => $ahora,
            ]);
        }

        return response()->json([
            'success' => true,
            'favorito' => true,
            'message' => 'Evento guardado en favoritos.',
        ]);
    }
}

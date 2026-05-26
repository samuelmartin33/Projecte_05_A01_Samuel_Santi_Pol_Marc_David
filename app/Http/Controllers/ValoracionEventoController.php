<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use App\Models\Evento;
use App\Models\ValoracionEvento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValoracionEventoController extends Controller
{
    /**
     * Guarda una valoración de evento.
     * Reglas:
     *   - Solo usuarios con entrada válida o usada pueden valorar.
     *   - Un usuario solo puede valorar cada evento una vez.
     *   - Admins y cuentas de empresa no pueden valorar.
     */
    public function store(Request $request, int $eventoId): JsonResponse
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        // Bloquear admins y cuentas de empresa
        if ($usuario->isAdmin() || $usuario->isEmpresa()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para valorar eventos.',
            ], 403);
        }

        // Verificar que el evento existe y está activo
        $evento = Evento::where('estado', 1)->findOrFail($eventoId);

        // Verificar que el usuario tiene entrada válida (1) o usada (2) para el evento
        $tieneEntrada = Entrada::whereHas('pedido', function ($q) use ($usuario) {
            $q->where('usuario_id', $usuario->id)->where('estado', 1);
        })->where('evento_id', $eventoId)
          ->whereIn('estado_entrada', [1, 2])
          ->exists();

        if (!$tieneEntrada) {
            return response()->json([
                'success' => false,
                'message' => 'Solo puedes valorar eventos para los que hayas comprado una entrada.',
            ], 403);
        }

        // Verificar que no ha valorado ya este evento
        $yaValorado = ValoracionEvento::where('usuario_id', $usuario->id)
                                      ->where('evento_id', $eventoId)
                                      ->exists();

        if ($yaValorado) {
            return response()->json([
                'success' => false,
                'message' => 'Ya has valorado este evento.',
            ], 422);
        }

        // Validar los datos del formulario
        $validated = $request->validate([
            'puntuacion' => ['required', 'integer', 'min:1', 'max:5'],
            'comentario' => ['nullable', 'string', 'max:1000'],
        ]);

        // Crear la valoración
        $valoracion = ValoracionEvento::create([
            'usuario_id'          => $usuario->id,
            'evento_id'           => $eventoId,
            'puntuacion'          => $validated['puntuacion'],
            'comentario'          => $validated['comentario'] ?? null,
            'estado'              => 1,
            'fecha_creacion'      => now(),
            'fecha_actualizacion' => now(),
        ]);

        // Calcular la nueva media y total para actualizar la UI sin recargar
        $media = ValoracionEvento::where('evento_id', $eventoId)
                                 ->where('estado', 1)
                                 ->avg('puntuacion');
        $total = ValoracionEvento::where('evento_id', $eventoId)
                                 ->where('estado', 1)
                                 ->count();

        return response()->json([
            'success'    => true,
            'message'    => '¡Gracias por tu valoración!',
            'media'      => round($media, 1),
            'total'      => $total,
            'valoracion' => [
                'puntuacion' => $valoracion->puntuacion,
                'comentario' => $valoracion->comentario,
                'autor'      => trim($usuario->nombre . ' ' . ($usuario->apellido1 ?? '')),
                'foto'       => $usuario->foto_url,
                'fecha'      => $valoracion->fecha_creacion->format('d/m/Y'),
            ],
        ]);
    }
}

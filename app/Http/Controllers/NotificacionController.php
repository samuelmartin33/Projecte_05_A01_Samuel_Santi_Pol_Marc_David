<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * Gestiona las notificaciones internas del usuario autenticado.
 *
 * Todos los métodos responden JSON porque son llamados desde JS (fetch).
 */
class NotificacionController extends Controller
{
    /**
     * Devuelve las últimas 8 notificaciones del usuario y el total de no leídas.
     * GET /api/notificaciones
     */
    public function index(): JsonResponse
    {
        $userId = Auth::id();

        $notificaciones = Notificacion::where('usuario_id', $userId)
            ->where('estado', 1)
            ->orderBy('fecha_creacion', 'desc')
            ->limit(8)
            ->get()
            ->map(fn ($n) => [
                'id'      => $n->id,
                'tipo'    => $n->tipo_notificacion,
                'icono'   => $n->icono(),
                'titulo'  => $n->titulo,
                'mensaje' => $n->mensaje,
                'url'     => $n->url_accion,
                'leida'   => (bool) $n->leida,
                'fecha'   => $n->fecha_creacion
                    ? \Carbon\Carbon::parse($n->fecha_creacion)->diffForHumans()
                    : '',
            ]);

        $sinLeer = Notificacion::where('usuario_id', $userId)
            ->where('estado', 1)
            ->where('leida', 0)
            ->count();

        return response()->json([
            'success'       => true,
            'notificaciones' => $notificaciones,
            'sin_leer'       => $sinLeer,
        ]);
    }

    /**
     * Marca todas las notificaciones del usuario como leídas.
     * POST /api/notificaciones/leer-todas
     */
    public function leerTodas(): JsonResponse
    {
        Notificacion::where('usuario_id', Auth::id())
            ->where('leida', 0)
            ->update([
                'leida'               => 1,
                'fecha_actualizacion' => now()->toDateTimeString(),
            ]);

        return response()->json(['success' => true]);
    }

    /**
     * Marca una notificación específica como leída.
     * POST /api/notificaciones/{id}/leer
     */
    public function leer(int $id): JsonResponse
    {
        Notificacion::where('id', $id)
            ->where('usuario_id', Auth::id())
            ->update([
                'leida'               => 1,
                'fecha_actualizacion' => now()->toDateTimeString(),
            ]);

        return response()->json(['success' => true]);
    }
}

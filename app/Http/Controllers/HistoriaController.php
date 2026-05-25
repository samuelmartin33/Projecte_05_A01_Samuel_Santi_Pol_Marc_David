<?php

namespace App\Http\Controllers;

use App\Models\Amigo;
use App\Models\Entrada;
use App\Models\Evento;
use App\Models\Historia;
use App\Models\HistoriaVista;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HistoriaController extends Controller
{
    /* ============================================================
       FEED DE HISTORIAS — agrupado por usuario
       ============================================================ */

    public function feed(): JsonResponse
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        // IDs de amigos aceptados
        $amigoIds = Amigo::where('estado', 1)
            ->where(fn ($q) => $q
                ->where('solicitante_id', $usuario->id)
                ->orWhere('receptor_id', $usuario->id))
            ->get()
            ->map(fn ($a) => $a->solicitante_id === $usuario->id
                ? $a->receptor_id
                : $a->solicitante_id)
            ->values()
            ->toArray();

        // Historias activas propias + de amigos
        $historias = Historia::activas()
            ->whereIn('usuario_id', array_merge([$usuario->id], $amigoIds))
            ->with([
                'usuario:id,nombre,apellido1,foto_url',
                'evento:id,titulo',
            ])
            ->orderBy('usuario_id')
            ->orderBy('fecha_creacion')
            ->get();

        // IDs de historias ya vistas por el usuario autenticado
        $vistasIds = HistoriaVista::where('usuario_id', $usuario->id)
            ->whereIn('historia_id', $historias->pluck('id'))
            ->pluck('historia_id')
            ->flip();

        // Agrupar por usuario
        $grupos = $historias->groupBy('usuario_id')->map(function ($items, $uId) use ($usuario, $vistasIds) {
            $u = $items->first()->usuario;
            return [
                'usuario'  => [
                    'id'        => $u->id,
                    'nombre'    => $u->nombre,
                    'apellido1' => $u->apellido1,
                    'foto_url'  => $u->foto_url,
                ],
                'es_mio'   => (int) $uId === $usuario->id,
                'historias' => $items->map(fn ($h) => [
                    'id'             => $h->id,
                    'media_url'      => $h->media_url,
                    'texto'          => $h->texto,
                    'evento'         => $h->evento
                        ? ['id' => $h->evento->id, 'titulo' => $h->evento->titulo]
                        : null,
                    'expira_en'      => $h->expira_en,
                    'fecha_creacion' => $h->fecha_creacion,
                    'ha_visto'       => isset($vistasIds[$h->id]),
                ])->values(),
            ];
        })->values();

        // El grupo propio siempre va primero
        $sorted = $grupos->sortByDesc('es_mio')->values();

        return response()->json(['exito' => true, 'datos' => $sorted]);
    }

    /* ============================================================
       CREAR HISTORIA
       ============================================================ */

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'foto'      => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:20480'],
            'texto'     => ['nullable', 'string', 'max:200'],
            'evento_id' => ['nullable', 'integer', 'exists:eventos,id'],
        ]);

        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        // Verificar asistencia si se etiqueta un evento
        if ($request->filled('evento_id')) {
            $asistio = Entrada::where('evento_id', (int) $request->evento_id)
                ->whereIn('estado_entrada', [1, 2])
                ->whereHas('pedido', fn ($q) => $q->where('usuario_id', $usuario->id))
                ->exists();

            if (!$asistio) {
                return response()->json([
                    'exito'   => false,
                    'mensaje' => 'Solo puedes etiquetar eventos a los que hayas asistido.',
                ], 403);
            }
        }

        $path  = $request->file('foto')->store('historias', 'public');
        $ahora = now();

        Historia::create([
            'usuario_id'          => $usuario->id,
            'media_url'           => '/storage/' . $path,
            'texto'               => $request->texto ? trim($request->texto) : null,
            'evento_id'           => $request->evento_id ?: null,
            'expira_en'           => $ahora->copy()->addHours(24),
            'vistas'              => 0,
            'estado'              => 1,
            'fecha_creacion'      => $ahora,
            'fecha_actualizacion' => $ahora,
        ]);

        return response()->json(['exito' => true, 'mensaje' => 'Historia publicada']);
    }

    /* ============================================================
       REGISTRAR VISTA
       ============================================================ */

    public function vista(int $id): JsonResponse
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        $historia = Historia::activas()->findOrFail($id);

        $vista = HistoriaVista::where('historia_id', $historia->id)
            ->where('usuario_id', $usuario->id)
            ->first();

        if (!$vista) {
            HistoriaVista::create([
                'historia_id' => $historia->id,
                'usuario_id'  => $usuario->id,
                'fecha_vista' => now(),
            ]);
            // Incrementar contador de vistas de forma atómica
            DB::table('historias')->where('id', $historia->id)->increment('vistas');
        }

        return response()->json(['exito' => true]);
    }

    /* ============================================================
       MIS HISTORIAS (propias, incluidas expiradas últimas 48h)
       ============================================================ */

    public function misHistorias(): JsonResponse
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        $historias = Historia::where('usuario_id', $usuario->id)
            ->where('estado', 1)
            ->where('fecha_creacion', '>=', now()->subHours(48))
            ->with('evento:id,titulo')
            ->orderByDesc('fecha_creacion')
            ->get()
            ->map(fn ($h) => [
                'id'             => $h->id,
                'media_url'      => $h->media_url,
                'texto'          => $h->texto,
                'evento'         => $h->evento
                    ? ['id' => $h->evento->id, 'titulo' => $h->evento->titulo]
                    : null,
                'vistas'         => $h->vistas,
                'activa'         => $h->expira_en->gt(now()),
                'expira_en'      => $h->expira_en,
                'fecha_creacion' => $h->fecha_creacion,
            ]);

        return response()->json(['exito' => true, 'datos' => $historias]);
    }
}

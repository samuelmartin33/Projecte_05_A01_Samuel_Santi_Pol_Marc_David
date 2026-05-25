<?php

namespace App\Http\Controllers;

use App\Models\Amigo;
use App\Models\Entrada;
use App\Models\Evento;
use App\Models\EventoImagen;
use App\Models\EventoPost;
use App\Models\EventoPostComentario;
use App\Models\EventoPostImagen;
use App\Models\EventoPostLike;
use App\Models\Historia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventoPostController extends Controller
{
    /* ============================================================
       FEED DE PUBLICACIONES
       ============================================================ */

    public function feed(Request $request): JsonResponse
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        /* IDs de amigos aceptados para filtrar posts de "solo amigos" */
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

        $pagina    = max(1, (int) $request->get('pagina', 1));
        $porPagina = 15;

        $query = EventoPost::with([
                'usuario:id,nombre,apellido1,foto_url',
                'evento:id,titulo',
                'imagenes',
                'comentarios' => fn ($q) => $q->where('estado', 1)->limit(3),
                'comentarios.usuario:id,nombre,apellido1,foto_url',
            ])
            ->where('estado', 1)
            ->where(function ($q) use ($usuario, $amigoIds) {
                /* Posts públicos visibles para todos; posts de solo amigos solo si eres el autor o amigo */
                $q->where('visibilidad', 1)
                  ->orWhere(fn ($q2) => $q2
                      ->where('visibilidad', 2)
                      ->where(fn ($q3) => $q3
                          ->where('usuario_id', $usuario->id)
                          ->orWhereIn('usuario_id', $amigoIds)));
            })
            ->when($request->filled('evento_id'), fn ($q) => $q->where('evento_id', (int) $request->evento_id))
            ->orderByDesc('fecha_creacion');

        $total = (clone $query)->count();
        $posts = $query->skip(($pagina - 1) * $porPagina)->take($porPagina)->get();

        return response()->json([
            'exito' => true,
            'datos' => $posts->map(fn ($p) => $this->formatearPost($p, $usuario->id)),
            'meta'  => [
                'pagina'  => $pagina,
                'total'   => $total,
                'hay_mas' => ($pagina * $porPagina) < $total,
            ],
        ]);
    }

    /* ============================================================
       CREAR PUBLICACIÓN
       ============================================================ */

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'evento_id'   => ['nullable', 'integer', 'exists:eventos,id'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'visibilidad' => ['nullable', 'integer', 'in:1,2'],
            'imagenes'    => ['required', 'array', 'min:1', 'max:10'],
            'imagenes.*'  => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:20480'],
        ]);

        /** @var \App\Models\Usuario $usuario */
        $usuario  = Auth::user();
        $eventoId = $request->filled('evento_id') ? (int) $request->evento_id : null;

        // Verificar asistencia solo si se etiqueta un evento
        if ($eventoId !== null) {
            $asistio = Entrada::where('evento_id', $eventoId)
                ->whereIn('estado_entrada', [1, 2])
                ->whereHas('pedido', fn ($q) => $q->where('usuario_id', $usuario->id))
                ->exists();

            if (!$asistio) {
                return response()->json([
                    'exito'   => false,
                    'mensaje' => 'Solo puedes publicar sobre eventos a los que hayas asistido.',
                ], 403);
            }
        }

        $post = DB::transaction(function () use ($request, $usuario, $eventoId) {
            $ahora = now();

            $post = EventoPost::create([
                'usuario_id'          => $usuario->id,
                'evento_id'           => $eventoId,
                'descripcion'         => $request->descripcion ? trim($request->descripcion) : null,
                'estado'              => 1,
                'visibilidad'         => (int) ($request->visibilidad ?? 1),
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => $ahora,
            ]);

            foreach ($request->file('imagenes') as $orden => $archivo) {
                $path = $archivo->store('posts', 'public');
                EventoPostImagen::create([
                    'evento_post_id'      => $post->id,
                    'imagen_url'          => '/storage/' . $path,
                    'orden'               => $orden,
                    'estado'              => 1,
                    'fecha_creacion'      => $ahora,
                    'fecha_actualizacion' => $ahora,
                ]);
            }

            return $post;
        });

        $post->load([
            'usuario:id,nombre,apellido1,foto_url',
            'evento:id,titulo',
            'imagenes',
        ]);

        return response()->json([
            'exito' => true,
            'datos' => $this->formatearPost($post, $usuario->id),
        ], 201);
    }

    /* ============================================================
       COMENTARIOS
       ============================================================ */

    public function comentar(Request $request, int $postId): JsonResponse
    {
        $request->validate([
            'contenido' => ['required', 'string', 'max:500'],
            'padre_id'  => ['nullable', 'integer', 'exists:evento_post_comentarios,id'],
        ]);

        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        $post = EventoPost::where('id', $postId)->where('estado', 1)->firstOrFail();

        $ahora = now();
        $comentario = EventoPostComentario::create([
            'evento_post_id'      => $post->id,
            'padre_id'            => $request->padre_id ?: null,
            'usuario_id'          => $usuario->id,
            'contenido'           => trim($request->contenido),
            'estado'              => 1,
            'fecha_creacion'      => $ahora,
            'fecha_actualizacion' => $ahora,
        ]);

        return response()->json([
            'exito' => true,
            'datos' => [
                'id'        => $comentario->id,
                'contenido' => $comentario->contenido,
                'fecha'     => $comentario->fecha_creacion,
                'es_mio'    => true,
                'padre_id'  => $comentario->padre_id,
                'autor'     => [
                    'nombre'    => $usuario->nombre,
                    'apellido1' => $usuario->apellido1,
                    'foto_url'  => $usuario->foto_url,
                ],
            ],
        ], 201);
    }

    public function comentariosPaginados(Request $request, int $postId): JsonResponse
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();
        $desdeId = (int) $request->get('desde', 0);

        $comentarios = EventoPostComentario::with('usuario:id,nombre,apellido1,foto_url')
            ->where('evento_post_id', $postId)
            ->where('estado', 1)
            ->whereNull('padre_id')                          // solo comentarios raíz
            ->when($desdeId > 0, fn ($q) => $q->where('id', '>', $desdeId))
            ->orderBy('fecha_creacion')
            ->limit(20)
            ->get()
            ->map(function ($c) use ($usuario) {
                $respuestas = $c->respuestas()->with('usuario:id,nombre,apellido1,foto_url')->get();
                return [
                    'id'        => $c->id,
                    'contenido' => $c->contenido,
                    'fecha'     => $c->fecha_creacion,
                    'es_mio'    => $c->usuario_id === $usuario->id,
                    'padre_id'  => $c->padre_id,
                    'autor'     => [
                        'nombre'    => $c->usuario->nombre,
                        'apellido1' => $c->usuario->apellido1,
                        'foto_url'  => $c->usuario->foto_url,
                    ],
                    'respuestas' => $respuestas->map(fn ($r) => [
                        'id'        => $r->id,
                        'contenido' => $r->contenido,
                        'fecha'     => $r->fecha_creacion,
                        'es_mio'    => $r->usuario_id === $usuario->id,
                        'padre_id'  => $r->padre_id,
                        'autor'     => [
                            'nombre'    => $r->usuario->nombre,
                            'apellido1' => $r->usuario->apellido1,
                            'foto_url'  => $r->usuario->foto_url,
                        ],
                    ])->values(),
                ];
            });

        return response()->json(['exito' => true, 'datos' => $comentarios]);
    }

    /* ============================================================
       EVENTOS ASISTIDOS (para el select del modal)
       ============================================================ */

    public function misEventosAsistidos(): JsonResponse
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        $eventos = Entrada::whereIn('estado_entrada', [1, 2])
            ->whereHas('pedido', fn ($q) => $q->where('usuario_id', $usuario->id))
            ->with('evento:id,titulo')
            ->get()
            ->pluck('evento')
            ->unique('id')
            ->filter()
            ->map(fn ($e) => ['id' => $e->id, 'titulo' => $e->titulo])
            ->values();

        return response()->json(['exito' => true, 'datos' => $eventos]);
    }

    /* ============================================================
       LIKES
       ============================================================ */

    public function toggleLike(Request $request, int $postId): JsonResponse
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        $post = EventoPost::where('id', $postId)->where('estado', 1)->firstOrFail();

        $ahora = now();

        $like = EventoPostLike::where('evento_post_id', $postId)
            ->where('usuario_id', $usuario->id)
            ->first();

        if ($like && (int) $like->estado === 1) {
            $like->update(['estado' => 0, 'fecha_actualizacion' => $ahora]);
            $liked = false;
        } elseif ($like) {
            $like->update(['estado' => 1, 'fecha_actualizacion' => $ahora]);
            $liked = true;
        } else {
            EventoPostLike::create([
                'evento_post_id'      => $postId,
                'usuario_id'          => $usuario->id,
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => $ahora,
            ]);
            $liked = true;
        }

        $totalLikes = EventoPostLike::where('evento_post_id', $postId)->where('estado', 1)->count();

        return response()->json([
            'exito'       => true,
            'liked'       => $liked,
            'total_likes' => $totalLikes,
        ]);
    }

    /* ============================================================
       FEED FILTRADO POR EVENTO
       ============================================================ */

    public function feedPorEvento(int $eventoId): JsonResponse
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        $evento = Evento::with(['imagenes' => fn ($q) => $q->where('es_portada', 1)->where('estado', 1)->limit(1)])
            ->find($eventoId);

        if (!$evento) {
            return response()->json(['exito' => false, 'mensaje' => 'Evento no encontrado'], 404);
        }

        $posts = EventoPost::with([
                'usuario:id,nombre,apellido1,foto_url',
                'evento:id,titulo',
                'imagenes',
                'comentarios' => fn ($q) => $q->where('estado', 1)->limit(3),
                'comentarios.usuario:id,nombre,apellido1,foto_url',
            ])
            ->where('evento_id', $eventoId)
            ->where('estado', 1)
            ->orderByDesc('fecha_creacion')
            ->limit(50)
            ->get()
            ->map(fn ($p) => $this->formatearPost($p, $usuario->id));

        // Historias activas del evento
        $historias = Historia::activas()
            ->where('evento_id', $eventoId)
            ->with('usuario:id,nombre,apellido1,foto_url')
            ->orderBy('fecha_creacion')
            ->get()
            ->map(fn ($h) => [
                'id'             => $h->id,
                'media_url'      => $h->media_url,
                'texto'          => $h->texto,
                'usuario'        => [
                    'id'        => $h->usuario->id,
                    'nombre'    => $h->usuario->nombre,
                    'apellido1' => $h->usuario->apellido1,
                    'foto_url'  => $h->usuario->foto_url,
                ],
                'fecha_creacion' => $h->fecha_creacion,
            ]);

        return response()->json([
            'exito'    => true,
            'evento'   => [
                'id'          => $evento->id,
                'titulo'      => $evento->titulo,
                'portada_url' => $evento->imagenes->first() ? $evento->imagenes->first()->imagen_url : null,
            ],
            'posts'    => $posts,
            'historias' => $historias,
        ]);
    }

    /* ============================================================
       EVENTOS CON CONTENIDO (para el panel de filtro)
       ============================================================ */

    public function eventosConContenido(): JsonResponse
    {
        // Todos los eventos que tengan publicaciones activas o historias activas (sin filtro de asistencia)
        $idsConPosts = EventoPost::where('estado', 1)
            ->whereNotNull('evento_id')
            ->distinct()
            ->pluck('evento_id');

        $idsConHistorias = Historia::activas()
            ->whereNotNull('evento_id')
            ->distinct()
            ->pluck('evento_id');

        $eventoIds = $idsConPosts->merge($idsConHistorias)->unique()->values();

        if ($eventoIds->isEmpty()) {
            return response()->json(['exito' => true, 'datos' => []]);
        }

        $resultado = Evento::whereIn('id', $eventoIds)
            ->with(['imagenes' => fn ($q) => $q->where('es_portada', 1)->where('estado', 1)->limit(1)])
            ->get(['id', 'titulo'])
            ->map(function ($e) {
                return [
                    'id'              => $e->id,
                    'titulo'          => $e->titulo,
                    'portada_url'     => $e->imagenes->first() ? $e->imagenes->first()->imagen_url : null,
                    'total_posts'     => EventoPost::where('evento_id', $e->id)->where('estado', 1)->count(),
                    'total_historias' => Historia::activas()->where('evento_id', $e->id)->count(),
                ];
            })
            ->values();

        return response()->json(['exito' => true, 'datos' => $resultado]);
    }

    /* ============================================================
       HELPER PRIVADO
       ============================================================ */

    private function formatearPost(EventoPost $post, int $miId): array
    {
        $comentariosPreview = $post->relationLoaded('comentarios')
            ? $post->comentarios
                ->filter(fn ($c) => $c->estado == 1)
                ->map(fn ($c) => [
                    'id'        => $c->id,
                    'contenido' => $c->contenido,
                    'fecha'     => $c->fecha_creacion,
                    'es_mio'    => $c->usuario_id === $miId,
                    'autor'     => [
                        'nombre'    => $c->usuario->nombre,
                        'apellido1' => $c->usuario->apellido1,
                        'foto_url'  => $c->usuario->foto_url,
                    ],
                ])->values()
            : collect();

        return [
            'id'                  => $post->id,
            'descripcion'         => $post->descripcion,
            'visibilidad'         => (int) $post->visibilidad,
            'fecha'               => $post->fecha_creacion,
            'evento'              => $post->evento
                ? ['id' => $post->evento->id, 'titulo' => $post->evento->titulo]
                : null,
            'autor'               => [
                'id'        => $post->usuario->id,
                'nombre'    => $post->usuario->nombre,
                'apellido1' => $post->usuario->apellido1,
                'foto_url'  => $post->usuario->foto_url,
            ],
            'imagenes'            => $post->imagenes->map(fn ($img) => [
                'id'  => $img->id,
                'url' => $img->imagen_url,
            ])->values(),
            'comentarios_preview' => $comentariosPreview,
            'total_comentarios'   => $post->comentarios()->where('estado', 1)->count(),
            'es_mio'              => $post->usuario_id === $miId,
            'total_likes'         => $post->likes()->where('estado', 1)->count(),
            'yo_di_like'          => $post->likes()->where('usuario_id', $miId)->where('estado', 1)->exists(),
        ];
    }
}

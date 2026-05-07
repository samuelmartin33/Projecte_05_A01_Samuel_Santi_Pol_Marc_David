<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use App\Models\EventoPost;
use App\Models\EventoPostComentario;
use App\Models\EventoPostImagen;
use App\Models\EventoPostLike;
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

        $eventoIds = Entrada::where('estado_entrada', 2)
            ->whereHas('pedido', fn ($q) => $q->where('usuario_id', $usuario->id))
            ->pluck('evento_id')
            ->unique()
            ->values();

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
            ->whereIn('evento_id', $eventoIds)
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
            'evento_id'   => ['required', 'integer', 'exists:eventos,id'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'imagenes'    => ['required', 'array', 'min:1', 'max:10'],
            'imagenes.*'  => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        /** @var \App\Models\Usuario $usuario */
        $usuario  = Auth::user();
        $eventoId = (int) $request->evento_id;

        $asistio = Entrada::where('evento_id', $eventoId)
            ->where('estado_entrada', 2)
            ->whereHas('pedido', fn ($q) => $q->where('usuario_id', $usuario->id))
            ->exists();

        if (!$asistio) {
            return response()->json([
                'exito'   => false,
                'mensaje' => 'Solo puedes publicar sobre eventos a los que hayas asistido.',
            ], 403);
        }

        $post = DB::transaction(function () use ($request, $usuario, $eventoId) {
            $ahora = now();

            $post = EventoPost::create([
                'usuario_id'          => $usuario->id,
                'evento_id'           => $eventoId,
                'descripcion'         => $request->descripcion ? trim($request->descripcion) : null,
                'estado'              => 1,
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
        $request->validate(['contenido' => ['required', 'string', 'max:500']]);

        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        $post = EventoPost::where('id', $postId)->where('estado', 1)->firstOrFail();

        $ahora = now();
        $comentario = EventoPostComentario::create([
            'evento_post_id'      => $post->id,
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
            ->when($desdeId > 0, fn ($q) => $q->where('id', '>', $desdeId))
            ->orderBy('fecha_creacion')
            ->limit(20)
            ->get()
            ->map(fn ($c) => [
                'id'        => $c->id,
                'contenido' => $c->contenido,
                'fecha'     => $c->fecha_creacion,
                'es_mio'    => $c->usuario_id === $usuario->id,
                'autor'     => [
                    'nombre'    => $c->usuario->nombre,
                    'apellido1' => $c->usuario->apellido1,
                    'foto_url'  => $c->usuario->foto_url,
                ],
            ]);

        return response()->json(['exito' => true, 'datos' => $comentarios]);
    }

    /* ============================================================
       EVENTOS ASISTIDOS (para el select del modal)
       ============================================================ */

    public function misEventosAsistidos(): JsonResponse
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        $eventos = Entrada::where('estado_entrada', 2)
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
            'fecha'               => $post->fecha_creacion,
            'evento'              => [
                'id'     => $post->evento->id,
                'titulo' => $post->evento->titulo,
            ],
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

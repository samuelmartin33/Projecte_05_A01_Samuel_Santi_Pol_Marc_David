<?php

namespace App\Http\Controllers\Moderador;

use App\Http\Controllers\Controller;
use App\Models\EventoPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Lista todas las publicaciones del social para moderación.
     */
    public function index(): View
    {
        $posts = EventoPost::with('usuario:id,nombre,apellido1')
            ->orderByDesc('fecha_creacion')
            ->paginate(20);

        return view('moderador.publicaciones.index', compact('posts'));
    }

    /**
     * Elimina (desactiva) una publicación cambiando su estado a 0.
     */
    public function destroy(EventoPost $post): RedirectResponse
    {
        $post->update([
            'estado'              => 0,
            'fecha_actualizacion' => now(),
        ]);

        return redirect()
            ->route('moderador.posts.index')
            ->with('success', 'Publicación eliminada correctamente.');
    }
}

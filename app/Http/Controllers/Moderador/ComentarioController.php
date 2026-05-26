<?php

namespace App\Http\Controllers\Moderador;

use App\Http\Controllers\Controller;
use App\Models\EventoPostComentario;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ComentarioController extends Controller
{
    /**
     * Lista todos los comentarios del social para moderación.
     */
    public function index(): View
    {
        $comentarios = EventoPostComentario::with([
                'usuario:id,nombre,apellido1',
                'post:id,descripcion',
            ])
            ->orderByDesc('fecha_creacion')
            ->paginate(20);

        return view('moderador.comentarios.index', compact('comentarios'));
    }

    /**
     * Elimina (desactiva) un comentario cambiando su estado a 0.
     */
    public function destroy(EventoPostComentario $comentario): RedirectResponse
    {
        $comentario->update([
            'estado'              => 0,
            'fecha_actualizacion' => now(),
        ]);

        return redirect()
            ->route('moderador.comentarios.index')
            ->with('success', 'Comentario eliminado correctamente.');
    }
}

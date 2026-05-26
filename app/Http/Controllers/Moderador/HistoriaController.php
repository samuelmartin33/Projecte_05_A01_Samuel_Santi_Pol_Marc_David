<?php

namespace App\Http\Controllers\Moderador;

use App\Http\Controllers\Controller;
use App\Models\Historia;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HistoriaController extends Controller
{
    /**
     * Lista todas las historias del social para moderación.
     * Muestra también las expiradas que aún tengan estado activo.
     */
    public function index(): View
    {
        $historias = Historia::with('usuario:id,nombre,apellido1')
            ->orderByDesc('fecha_creacion')
            ->paginate(20);

        return view('moderador.historias.index', compact('historias'));
    }

    /**
     * Elimina (desactiva) una historia cambiando su estado a 0.
     */
    public function destroy(Historia $historia): RedirectResponse
    {
        $historia->update([
            'estado'              => 0,
            'fecha_actualizacion' => now(),
        ]);

        return redirect()
            ->route('moderador.historias.index')
            ->with('success', 'Historia eliminada correctamente.');
    }
}

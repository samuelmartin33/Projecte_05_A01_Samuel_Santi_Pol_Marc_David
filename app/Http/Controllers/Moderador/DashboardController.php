<?php

namespace App\Http\Controllers\Moderador;

use App\Http\Controllers\Controller;
use App\Models\EventoPost;
use App\Models\EventoPostComentario;
use App\Models\Historia;
use App\Models\Usuario;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Muestra el panel principal del moderador con estadísticas de contenido activo.
     */
    public function index(): View
    {
        $totalPosts       = EventoPost::where('estado', 1)->count();
        $totalHistorias   = Historia::where('estado', 1)->count();
        $totalComentarios = EventoPostComentario::where('estado', 1)->count();
        $totalBaneados    = Usuario::where('estado', 0)
                                ->where('es_admin', 0)
                                ->count();

        return view('moderador.dashboard', compact(
            'totalPosts',
            'totalHistorias',
            'totalComentarios',
            'totalBaneados'
        ));
    }
}

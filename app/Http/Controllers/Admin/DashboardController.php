<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\Usuario;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'totalEventos'      => Evento::count(),
            'eventosActivos'    => Evento::where('estado', 1)->count(),
            'empresasPendientes' => Usuario::where('tipo_cuenta', 'empresa')
                ->where('estado_registro', 'pendiente')
                ->count(),
        ]);
    }
}

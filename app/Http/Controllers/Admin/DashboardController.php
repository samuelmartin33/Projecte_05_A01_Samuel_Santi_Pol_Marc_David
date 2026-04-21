<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
<<<<<<< HEAD
use App\Models\Usuario;
use Illuminate\View\View;

/**
 * Dashboard del Administrador.
 * Solo accesible con middleware 'role:admin' (es_admin = 1).
 */
class DashboardController extends Controller
{
    /**
     * Muestra el panel de control del administrador.
     * Pasa estadísticas básicas a la vista.
     */
    public function index(): View
    {
        $stats = [
            'total_usuarios'   => Usuario::where('es_admin', 0)->count(),
            'admins'           => Usuario::where('es_admin', 1)->count(),
            'usuarios_activos' => Usuario::where('estado', 1)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
=======
use App\Models\Evento;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'totalEventos' => Evento::count(),
            'eventosActivos' => Evento::where('estado', 1)->count(),
        ]);
>>>>>>> 842cf758743629209c59f3b6b6ec472ffcd429bf
    }
}

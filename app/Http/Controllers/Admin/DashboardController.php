<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
    }
}

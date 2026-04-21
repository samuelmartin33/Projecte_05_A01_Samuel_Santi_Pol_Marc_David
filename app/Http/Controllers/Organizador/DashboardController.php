<?php

namespace App\Http\Controllers\Organizador;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Dashboard del Organizador.
 * Solo accesible con middleware 'role:organizador'.
 */
class DashboardController extends Controller
{
    /**
     * Muestra el panel de control del organizador.
     * Carga la relación empresa del organizador para mostrar datos.
     */
    public function index(): View
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        // Cargar la empresa a la que pertenece este organizador
        $usuario->load('organizador.empresa');

        return view('organizador.dashboard', compact('usuario'));
    }
}

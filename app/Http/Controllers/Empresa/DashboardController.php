<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Dashboard de Empresa / Entidad Colaboradora.
 * Solo accesible con middleware 'role:empresa'.
 */
class DashboardController extends Controller
{
    /**
     * Muestra el panel de control de la empresa.
     * Carga los datos de la empresa del usuario autenticado.
     */
    public function index(): View
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        // Cargar la empresa de este usuario propietario
        $usuario->load('empresa');

        return view('empresa.dashboard', compact('usuario'));
    }
}

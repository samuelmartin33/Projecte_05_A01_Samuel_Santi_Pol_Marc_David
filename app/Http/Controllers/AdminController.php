<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $pendientes = Usuario::where('tipo_cuenta', 'empresa')
            ->where('estado_registro', 'pendiente')
            ->orderBy('fecha_creacion', 'asc')
            ->get();

        $verificados = Usuario::where('email_verificado', 1)
            ->where('es_admin', 0)
            ->orderBy('fecha_actualizacion', 'desc')
            ->limit(50)
            ->get();

        return view('admin.usuarios', compact('pendientes', 'verificados'));
    }

    public function verificar(int $id): RedirectResponse
    {
        $usuario = Usuario::findOrFail($id);

        if ($usuario->email_verificado) {
            return redirect()->route('admin.usuarios')->with('admin_error', 'Este usuario ya está verificado.');
        }

        $usuario->update([
            'email_verificado'    => 1,
            'estado_registro'     => 'aprobado',
            'fecha_actualizacion' => now(),
        ]);

        return redirect()->route('admin.usuarios')
            ->with('admin_success', "✓ {$usuario->nombre} (empresa) verificada correctamente.");
    }

    public function rechazar(int $id): RedirectResponse
    {
        $usuario = Usuario::findOrFail($id);

        if ($usuario->email_verificado) {
            return redirect()->route('admin.usuarios')
                ->with('admin_error', 'No se puede rechazar un usuario ya verificado.');
        }

        $usuario->update([
            'estado_registro'     => 'rechazado',
            'fecha_actualizacion' => now(),
        ]);

        return redirect()->route('admin.usuarios')
            ->with('admin_success', "✗ Solicitud de {$usuario->nombre} rechazada y notificada.");
    }
}

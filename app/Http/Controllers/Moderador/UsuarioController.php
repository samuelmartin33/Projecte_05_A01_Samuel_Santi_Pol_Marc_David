<?php

namespace App\Http\Controllers\Moderador;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UsuarioController extends Controller
{
    /**
     * Lista todos los usuarios no-admin para gestionar baneos.
     */
    public function index(): View
    {
        $usuarios = Usuario::where('es_admin', 0)
            ->orderByDesc('fecha_creacion')
            ->paginate(25);

        return view('moderador.usuarios.index', compact('usuarios'));
    }

    /**
     * Banea un usuario desactivando su cuenta (estado = 0).
     * Restricciones: no se puede banear a uno mismo, admins, moderadores ni empresas.
     */
    public function banear(Usuario $usuario): RedirectResponse
    {
        abort_if($usuario->id === Auth::id(), 403, 'No puedes banearte a ti mismo.');
        abort_if((bool) $usuario->es_admin, 403, 'No puedes banear a un administrador.');
        abort_if((bool) $usuario->es_moderador, 403, 'No puedes banear a otro moderador.');
        abort_if(($usuario->tipo_cuenta ?? '') === 'empresa', 403, 'No puedes banear a una cuenta de empresa.');

        if ((int) $usuario->estado === 0) {
            return redirect()
                ->route('moderador.usuarios.index')
                ->with('error', 'El usuario ya estaba baneado.');
        }

        $usuario->update([
            'estado'              => 0,
            'fecha_actualizacion' => now(),
        ]);

        return redirect()
            ->route('moderador.usuarios.index')
            ->with('success', "Usuario {$usuario->nombre} baneado correctamente.");
    }

    /**
     * Desbanea un usuario reactivando su cuenta (estado = 1).
     */
    public function desbanear(Usuario $usuario): RedirectResponse
    {
        $usuario->update([
            'estado'              => 1,
            'fecha_actualizacion' => now(),
        ]);

        return redirect()
            ->route('moderador.usuarios.index')
            ->with('success', "Usuario {$usuario->nombre} desbaneado correctamente.");
    }
}

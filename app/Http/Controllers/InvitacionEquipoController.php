<?php

namespace App\Http\Controllers;

use App\Models\InvitacionEquipo;
use App\Models\Organizador;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Gestiona la aceptación de invitaciones para unirse al equipo de una empresa.
 * Accesible para cualquier usuario (autenticado o no).
 */
class InvitacionEquipoController extends Controller
{
    /**
     * Muestra la página de aceptación o procesa la invitación directamente si el usuario está autenticado.
     * GET /equipo/aceptar/{token}
     */
    public function aceptar(string $token)
    {
        $invitacion = InvitacionEquipo::with(['empresa', 'candidatura.oferta'])
            ->where('token', $token)
            ->first();

        // Token inexistente
        if (!$invitacion) {
            return view('equipo.invitacion-invalida');
        }

        // Token caducado o ya usado
        if (!$invitacion->estaVigente()) {
            return view('equipo.invitacion-caducada', compact('invitacion'));
        }

        // Si no está autenticado, redirigir a login con ?redirect= para que login.js devuelva aquí al usuario
        if (!Auth::check()) {
            $urlRetorno = route('equipo.aceptar', ['token' => $token]);
            return redirect(route('login') . '?redirect=' . urlencode($urlRetorno));
        }

        $usuario = Auth::user();

        // Comprobar si ya es miembro activo de esta empresa
        $yaEsMiembro = DB::table('organizadores')
            ->where('empresa_id', $invitacion->empresa_id)
            ->where('usuario_id', $usuario->id)
            ->where('estado', 1)
            ->exists();

        if (!$yaEsMiembro) {
            // Añadir al usuario como miembro del equipo de la empresa
            Organizador::create([
                'usuario_id'          => $usuario->id,
                'empresa_id'          => $invitacion->empresa_id,
                'rol'                 => $invitacion->rol,
                'estado'              => 1,
                'fecha_creacion'      => now(),
                'fecha_actualizacion' => now(),
            ]);
        }

        // Marcar la invitación como usada
        $invitacion->update(['usado_en' => now()]);

        return view('equipo.invitacion-aceptada', compact('invitacion'));
    }
}
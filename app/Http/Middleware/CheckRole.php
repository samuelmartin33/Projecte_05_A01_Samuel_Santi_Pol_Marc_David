<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware CheckRole
 *
 * Protege rutas según el rol del usuario autenticado.
 *
 * Uso en rutas:
 *   ->middleware('role:admin')
 *   ->middleware('role:admin,empresa')   ← acepta cualquiera de los dos roles
 *
 * Lógica de roles (sin columna extra en BD):
 *   - admin      → es_admin = 1
 *   - organizador → tiene fila en tabla organizadores (estado activo)
 *   - empresa    → tiene fila en tabla empresas como propietario (usuario_id)
 *   - usuario    → ninguno de los anteriores
 */
class CheckRole
{
    /**
     * @param  string  ...$roles  Roles permitidos separados por coma en la definición
     *                             de la ruta: ->middleware('role:admin,empresa')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. Usuario no autenticado → redirigir al login con mensaje informativo
        if (! Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para acceder a esta sección.');
        }

        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        // 2. Cuenta desactivada → cerrar sesión y redirigir al login
        if ((int) $usuario->estado !== 1) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'Tu cuenta ha sido desactivada. Contacta con el administrador.');
        }

        // 3. Verificar si el usuario tiene alguno de los roles permitidos
        //    (admite múltiples: role:admin,empresa → acceso si tiene cualquiera)
        foreach ($roles as $role) {
            if ($usuario->hasRole(trim($role))) {
                return $next($request);
            }
        }

        // 4. Sin permiso → 403 Forbidden
        abort(403, 'No tienes permiso para acceder a esta sección.');
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsModerador
{
    /**
     * Verifica que el usuario autenticado sea moderador o administrador.
     * Los admins tienen acceso completo, incluido el panel de moderación.
     * Si no tiene permisos, devuelve un error 403 (Forbidden).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $usuario = $request->user();

        if (! $usuario?->es_moderador && ! $usuario?->es_admin) {
            abort(403, 'No tienes permisos de moderación.');
        }

        return $next($request);
    }
}

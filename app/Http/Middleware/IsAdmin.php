<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Verifica si el usuario autenticado es administrador.
     * Si no es admin, devuelve un error 403 (Forbidden).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->es_admin) {
            abort(403, 'No tienes permisos de administrador');
        }

        return $next($request);
    }
}

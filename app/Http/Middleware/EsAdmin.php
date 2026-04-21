<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->es_admin) {
            abort(403, 'Acceso restringido al panel de administración.');
        }

        return $next($request);
    }
}

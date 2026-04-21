<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EstaVerificado
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()->email_verificado) {
            return redirect()->route('pendiente-verificacion');
        }

        return $next($request);
    }
}

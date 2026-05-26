<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\RedirectIfPortero::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'stripe/webhook',
        ]);
        $middleware->alias([
            'admin'      => \App\Http\Middleware\IsAdmin::class,
            'moderador'  => \App\Http\Middleware\IsModerador::class,
            'verificado' => \App\Http\Middleware\EstaVerificado::class,
            'no-portero' => \App\Http\Middleware\RedirectIfPortero::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

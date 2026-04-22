<?php

/**
 * VIBEZ — routes/api.php
 *
 * Solo contiene los endpoints AJAX que necesitan respuesta dinámica sin recargar página.
 * Cargado desde web.php con el prefijo /api, hereda el middleware 'web' (sesión + CSRF).
 *
 * URLs resultantes:
 *   POST /api/login              → AuthController@login
 *   POST /api/register           → AuthController@register
 *   POST /api/logout             → AuthController@logout
 *   GET  /api/amigos/buscar      → PerfilController@buscarUsuarios  (buscador dinámico)
 *   POST /api/amigos/solicitud   → PerfilController@enviarSolicitud (botón en resultados)
 */

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PerfilController;
use Illuminate\Support\Facades\Route;

// Login — throttle: máx 5 intentos por minuto por IP
Route::post('/login', [AuthController::class, 'login'])
     ->middleware('throttle:5,1')
     ->name('api.login');

// Register — mismo límite de rate
Route::post('/register', [AuthController::class, 'register'])
     ->middleware('throttle:5,1')
     ->name('api.register');

// Logout — solo accesible si hay sesión activa
Route::post('/logout', [AuthController::class, 'logout'])
     ->middleware('auth')
     ->name('api.logout');

// Búsqueda dinámica de amigos y envío de solicitud
// Usan AJAX porque los resultados se muestran sin recargar la página
Route::middleware('auth')->group(function () {
    Route::get('/amigos/buscar',    [PerfilController::class, 'buscarUsuarios'])->name('api.amigos.buscar');
    Route::post('/amigos/solicitud', [PerfilController::class, 'enviarSolicitud'])->name('api.amigos.solicitud');
});

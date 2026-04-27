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
use App\Http\Controllers\FavoritoController;
use App\Http\Controllers\EntradaController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\SocialController;
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
    Route::post('/favoritos/toggle', [FavoritoController::class, 'toggle'])->name('api.favoritos.toggle');

    // Compra de entradas
    Route::post('/entradas/comprar', [EntradaController::class, 'comprar'])->name('api.entradas.comprar');
});

// ── Sección Social ──────────────────────────────────────────
// Todos los endpoints requieren sesión activa
Route::middleware('auth')->prefix('social')->group(function () {

    // Amigos
    Route::get('/amigos',                        [SocialController::class, 'misAmigos']);
    Route::get('/solicitudes',                   [SocialController::class, 'solicitudesPendientes']);
    Route::post('/solicitudes/{id}/aceptar',     [SocialController::class, 'aceptarSolicitud']);
    Route::post('/solicitudes/{id}/rechazar',    [SocialController::class, 'rechazarSolicitud']);
    Route::get('/usuarios/buscar',               [SocialController::class, 'buscarUsuarios']);
    Route::post('/solicitud',                    [SocialController::class, 'enviarSolicitud']);

    // Chats y mensajes
    Route::get('/chats',                         [SocialController::class, 'misChats']);
    Route::post('/chats/abrir',                  [SocialController::class, 'abrirChat']);
    Route::get('/chats/{id}/mensajes',           [SocialController::class, 'mensajesChat']);
    Route::post('/chats/{id}/mensajes',          [SocialController::class, 'enviarMensaje']);
    Route::get('/chats/{id}/nuevos',             [SocialController::class, 'mensajesNuevos']);

    // Badge del navbar
    Route::get('/contador',                      [SocialController::class, 'contadorNoLeidos']);
});

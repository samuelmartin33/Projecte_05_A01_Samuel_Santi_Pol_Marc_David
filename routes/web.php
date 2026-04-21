<?php

/**
 * VIBEZ — routes/web.php
 *
 * Rutas web (middleware 'web': sesión, CSRF, cookies, etc.)
 *
 * Estructura:
 *   GET  /               → welcome (landing pública)
 *   GET  /login          → vista login
 *   GET  /register       → vista register
 *   GET  /home           → home de usuario (protegido por auth)
 *   GET  /empresa/home   → home de empresa (protegido por auth)
 *
 *   POST /api/login    → AJAX (incluido desde api.php)
 *   POST /api/register → AJAX (incluido desde api.php)
 *   POST /api/logout   → AJAX (incluido desde api.php)
 *
 * NOTA: Las rutas /api/* se cargan desde routes/api.php con el prefijo
 * 'api' dentro del grupo web. Así tienen acceso completo a la sesión
 * sin necesitar Sanctum ni ningún paquete externo.
 */

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PerfilController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventoController;

/*
|--------------------------------------------------------------------------
| Rutas Web de VIBEZ
|--------------------------------------------------------------------------
| Aquí se definen todas las rutas de la aplicación.
|
*/

// --- Landing de bienvenida: primera página que ve el usuario ---
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// --- Detalle de un evento específico ---
Route::get('/eventos/{id}', [EventoController::class, 'detalle'])
    ->where('id', '[0-9]+')
    ->name('eventos.detalle');

// --- Detalle de una oferta de trabajo ---
Route::get('/trabajos/{id}', [EventoController::class, 'detalleOferta'])
    ->where('id', '[0-9]+')
    ->name('trabajos.detalle');

// --- API AJAX: filtrar eventos y ofertas (responde JSON) ---
Route::get('/api/filtrar', [EventoController::class, 'filtrar'])
    ->name('api.filtrar');

// --- Página completa de Bolsa de Trabajo ---
Route::get('/bolsa-de-trabajo', [EventoController::class, 'bolsaTrabajo'])
    ->name('trabajos.index');

// --- API AJAX: filtrar solo ofertas de trabajo ---
Route::get('/api/filtrar-trabajos', [EventoController::class, 'filtrarTrabajos'])
    ->name('api.filtrar-trabajos');

/* — Vistas de autenticación — */
Route::get('/login',    [AuthController::class, 'showLogin'])
     ->name('login');

Route::get('/register', [AuthController::class, 'showRegister'])
     ->name('register');

/* — Home de usuario: protegido por middleware auth — */
Route::get('/home', [AuthController::class, 'showHome'])
     ->middleware('auth')
     ->name('home');

/* — Home de empresa: protegido por middleware auth — */
Route::get('/empresa/home', [AuthController::class, 'showEmpresaHome'])
     ->middleware('auth')
     ->name('empresa.home');

/* — Perfil de usuario — */
Route::middleware('auth')->group(function () {

    // Vista principal del perfil
    Route::get('/perfil', [PerfilController::class, 'show'])->name('perfil');

    // Formulario de datos personales (POST simple, sin AJAX)
    Route::post('/perfil', [PerfilController::class, 'actualizar'])->name('perfil.actualizar');

    // Formulario de foto (POST con archivo, sin AJAX)
    Route::post('/perfil/foto', [PerfilController::class, 'actualizarFoto'])->name('perfil.foto');

    // Formulario de mood/estado de ánimo (POST simple)
    Route::post('/perfil/mood', [PerfilController::class, 'actualizarMood'])->name('perfil.mood');

    // Aceptar / rechazar solicitudes de amistad (botones de formulario)
    Route::post('/amigos/{id}/aceptar',  [PerfilController::class, 'aceptarSolicitud'])->name('amigos.aceptar');
    Route::post('/amigos/{id}/rechazar', [PerfilController::class, 'rechazarSolicitud'])->name('amigos.rechazar');
});

/* — Endpoints AJAX: cargados desde api.php con prefijo /api —
     Heredan el middleware 'web' al estar dentro de web.php */
Route::prefix('api')->group(base_path('routes/api.php'));

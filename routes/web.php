<?php

/**
 * VIBEZ — routes/web.php
 *
 * Rutas web (middleware 'web': sesión, CSRF, cookies, etc.)
 *
 * Estructura:
 *   GET  /          → redirige a /login
 *   GET  /login     → vista login
 *   GET  /register  → vista register
 *   GET  /index     → dashboard (protegido por auth)
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
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventoController;

/*
|--------------------------------------------------------------------------
| Rutas Web de VIBEZ
|--------------------------------------------------------------------------
| Aquí se definen todas las rutas de la aplicación.
|
*/

// --- Página de inicio: grid de eventos con filtros ---
Route::get('/', [EventoController::class, 'index'])->name('home');

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

// --- Landing de bienvenida (página estática de marketing) ---
Route::get('/bienvenida', function () {
    return view('welcome');
})->name('bienvenida');

/* — Vistas de autenticación — */
Route::get('/login',    [AuthController::class, 'showLogin'])
     ->name('login');

Route::get('/register', [AuthController::class, 'showRegister'])
     ->name('register');

/* — Dashboard: protegido por middleware auth —
     Si no hay sesión, Laravel redirige automáticamente a la ruta 'login' */
Route::get('/index',    [AuthController::class, 'showIndex'])
     ->middleware('auth')
     ->name('index');

/* — Endpoints AJAX: cargados desde api.php con prefijo /api —
     Heredan el middleware 'web' al estar dentro de web.php */
Route::prefix('api')->group(base_path('routes/api.php'));

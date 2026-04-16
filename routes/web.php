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

/* — Raíz: redirige al login — */
Route::get('/', fn () => redirect()->route('login'));

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

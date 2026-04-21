<?php

/**
 * VIBEZ — routes/web.php
 *
 * Estructura de rutas organizada por rol:
 *
 * Estructura:
 *   GET  /login     → vista login
 *   GET  /register  → vista register
 *   GET  /index     → dashboard (protegido por auth)
 *
 *  AJAX (heredan middleware web desde este archivo):
 *    POST /api/login     → AuthController@login     (form POST, devuelve redirect)
 *    POST /api/register  → AuthController@register  (form POST, devuelve redirect)
 *    POST /api/logout    → AuthController@logout    (AJAX, devuelve JSON)
 *
 *  Protegidas por autenticación:
 *    GET  /index                  → dashboard usuario  (auth)
 *    GET  /admin/dashboard        → dashboard admin    (auth + role:admin)
 *    GET  /organizador/dashboard  → dashboard org.     (auth + role:organizador)
 *    GET  /empresa/dashboard      → dashboard empresa  (auth + role:empresa)
 */

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Empresa\DashboardController as EmpresaDashboard;
use App\Http\Controllers\Organizador\DashboardController as OrganizadorDashboard;
use Illuminate\Support\Facades\Route;

/* ——————————————————————————————————————————
   RUTAS PÚBLICAS
   —————————————————————————————————————————— */

Route::get('/', fn () => view('welcome'))->name('home');

Route::get('/login',    [AuthController::class, 'showLogin'])
     ->name('login');

Route::get('/register', [AuthController::class, 'showRegister'])
     ->name('register');

/* — Dashboard: protegido por auth + verificado (email_verificado = true) — */
Route::get('/index', [AuthController::class, 'showIndex'])
     ->middleware(['auth', 'verificado'])
     ->name('index');

/* — Página informativa para usuarios pendientes de verificación — */
Route::get('/pendiente-verificacion', fn () => view('pendiente-verificacion'))
     ->name('pendiente-verificacion');

/* — Endpoints AJAX: cargados desde api.php con prefijo /api —
     Heredan el middleware 'web' al estar dentro de web.php */
Route::prefix('api')->group(base_path('routes/api.php'));

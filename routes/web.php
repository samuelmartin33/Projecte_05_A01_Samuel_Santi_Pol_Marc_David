<?php

/**
 * VIBEZ — routes/web.php
 *
 * Estructura de rutas organizada por rol:
 *
 *  Públicas:
 *    GET  /              → welcome
 *    GET  /login         → vista login
 *    GET  /register      → vista registro
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

/* ——————————————————————————————————————————
   ENDPOINTS AJAX — heredan middleware 'web' (sesión + CSRF)
   —————————————————————————————————————————— */
Route::prefix('api')->group(base_path('routes/api.php'));

/* ——————————————————————————————————————————
   DASHBOARD USUARIO — cualquier usuario autenticado
   —————————————————————————————————————————— */
Route::get('/index', [AuthController::class, 'showIndex'])
     ->middleware('auth')
     ->name('index');

/* ——————————————————————————————————————————
   ÁREA ADMIN — solo administradores (es_admin = 1)
   —————————————————————————————————————————— */
Route::middleware(['auth', 'role:admin'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function () {
         Route::get('/dashboard', [AdminDashboard::class, 'index'])
              ->name('dashboard');

         // Aquí añadir más rutas de admin:
         // Route::resource('usuarios', AdminUsuariosController::class);
         // Route::resource('eventos',  AdminEventosController::class);
     });

/* ——————————————————————————————————————————
   ÁREA ORGANIZADOR — solo organizadores
   —————————————————————————————————————————— */
Route::middleware(['auth', 'role:organizador'])
     ->prefix('organizador')
     ->name('organizador.')
     ->group(function () {
         Route::get('/dashboard', [OrganizadorDashboard::class, 'index'])
              ->name('dashboard');

         // Aquí añadir más rutas de organizador:
         // Route::resource('eventos', OrganizadorEventosController::class);
     });

/* ——————————————————————————————————————————
   ÁREA EMPRESA — solo empresas / entidades colaboradoras
   —————————————————————————————————————————— */
Route::middleware(['auth', 'role:empresa'])
     ->prefix('empresa')
     ->name('empresa.')
     ->group(function () {
         Route::get('/dashboard', [EmpresaDashboard::class, 'index'])
              ->name('dashboard');

         // Aquí añadir más rutas de empresa:
         // Route::resource('cupones',  EmpresaCuponesController::class);
         // Route::resource('ofertas',  EmpresaOfertasController::class);
     });

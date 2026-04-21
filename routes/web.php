<?php

/**
 * VIBEZ — routes/web.php
 *
 * Rutas web (middleware 'web': sesión, CSRF, cookies, etc.)
 *
 * Estructura:
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
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventoController;
use Illuminate\Support\Facades\Route;

/* — Raíz: muestra la landing (welcome) — */
Route::get('/', fn () => view('welcome'))->name('home');

/* — Vistas de autenticación — */
Route::get('/login',    [AuthController::class, 'showLogin'])
     ->name('login');

Route::get('/register', [AuthController::class, 'showRegister'])
     ->name('register');

/* — Dashboard: protegido por auth + verificado (email_verificado = true) — */
Route::get('/index', [AuthController::class, 'showIndex'])
     ->middleware(['auth', 'verificado'])
     ->name('index');

/* — Dashboard de Admin: protegido por middlewares auth e IsAdmin — */
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'index'])
         ->name('admin.dashboard');
    
    /* Rutas de eventos */
    Route::get('/admin/eventos', [EventoController::class, 'index'])
         ->name('admin.eventos.index');
    Route::get('/admin/eventos/crear', [EventoController::class, 'create'])
         ->name('admin.eventos.create');
    Route::post('/admin/eventos', [EventoController::class, 'store'])
         ->name('admin.eventos.store');
    Route::get('/admin/eventos/{evento}/editar', [EventoController::class, 'edit'])
         ->name('admin.eventos.edit');
    Route::put('/admin/eventos/{evento}', [EventoController::class, 'update'])
         ->name('admin.eventos.update');
    Route::delete('/admin/eventos/{evento}', [EventoController::class, 'destroy'])
         ->name('admin.eventos.destroy');
});

/* — Endpoints AJAX: cargados desde api.php con prefijo /api —
     Heredan el middleware 'web' al estar dentro de web.php */
Route::prefix('api')->group(base_path('routes/api.php'));

<?php

/**
 * VIBEZ — routes/api.php
 *
 * Endpoints AJAX de autenticación.
 * Este archivo es cargado desde web.php con el prefijo /api,
 * por lo que heredan el middleware 'web' completo (sesión + CSRF).
 *
 * URLs resultantes:
 *   POST /api/login    → AuthController@login
 *   POST /api/register → AuthController@register
 *   POST /api/logout   → AuthController@logout
 */

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Login — throttle: máx 5 intentos por minuto por IP
Route::post('/login',    [AuthController::class, 'login'])
     ->middleware('throttle:5,1')
     ->name('api.login');

// Register — mismo límite de rate
Route::post('/register', [AuthController::class, 'register'])
     ->middleware('throttle:5,1')
     ->name('api.register');

// Logout — solo accesible si hay sesión activa
Route::post('/logout',   [AuthController::class, 'logout'])
     ->middleware('auth')
     ->name('api.logout');

// Google Identity Services — verifica el credential JWT y crea/loguea al usuario
Route::post('/auth/google', [AuthController::class, 'googleAuth'])
     ->middleware('throttle:10,1')
     ->name('api.auth.google');

<?php

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

// --- Landing de bienvenida (página estática de marketing) ---
Route::get('/bienvenida', function () {
    return view('welcome');
})->name('bienvenida');

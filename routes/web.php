<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('eventos', EventoController::class)->except(['show']);
});

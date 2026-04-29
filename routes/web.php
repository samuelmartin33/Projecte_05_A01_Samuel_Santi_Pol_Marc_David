<?php

/**
 * VIBEZ — routes/web.php
 *
 * Estructura de rutas organizada por rol:
 *
 * Estructura:
 *   GET  /               → welcome (landing pública)
 *   GET  /login          → vista login
 *   GET  /register       → vista register
 *   GET  /home           → home de usuario (protegido por auth)
 *   GET  /empresa/home   → home de empresa (protegido por auth)
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

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventoController as AdminEventoController;
use App\Http\Controllers\Admin\EmpresaController as AdminEmpresaController;
use App\Http\Controllers\Admin\PedidoController as AdminPedidoController;
use App\Http\Controllers\Admin\PagoController as AdminPagoController;
use App\Http\Controllers\Admin\UsuarioController as AdminUsuarioController;
use App\Http\Controllers\EventoController as PublicEventoController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\SocialController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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
Route::get('/eventos/{id}', [PublicEventoController::class, 'detalle'])
    ->where('id', '[0-9]+')
    ->name('eventos.detalle');

// --- Detalle de una oferta de trabajo ---
Route::get('/trabajos/{id}', [PublicEventoController::class, 'detalleOferta'])
    ->where('id', '[0-9]+')
    ->name('trabajos.detalle');

// --- API AJAX: filtrar eventos y ofertas (responde JSON) ---
Route::get('/api/filtrar', [PublicEventoController::class, 'filtrar'])
    ->name('api.filtrar');

// --- Página completa de Bolsa de Trabajo ---
Route::get('/bolsa-de-trabajo', [PublicEventoController::class, 'bolsaTrabajo'])
    ->name('trabajos.index');

// --- API AJAX: filtrar solo ofertas de trabajo ---
Route::get('/api/filtrar-trabajos', [PublicEventoController::class, 'filtrarTrabajos'])
    ->name('api.filtrar-trabajos');

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

    // Mis entradas (wallet de QRs)
    Route::get('/mis-entradas', [\App\Http\Controllers\EntradaController::class, 'misEntradas'])
         ->name('entradas.mis-entradas');

    // Confirmación de compra de entradas
    Route::get('/entradas/confirmacion/{pedido}', [\App\Http\Controllers\EntradaController::class, 'confirmacion'])
         ->name('entradas.confirmacion');

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

/* — Página Social: protegida por auth — */
Route::middleware('auth')->get('/social', [SocialController::class, 'index'])
    ->name('social');

/* — Dashboard de Admin: protegido por middlewares auth e IsAdmin — */
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'index'])
         ->name('admin.dashboard');
    
    /* Rutas de eventos */
    Route::get('/admin/eventos', [AdminEventoController::class, 'index'])
         ->name('admin.eventos.index');
    Route::get('/admin/eventos/crear', [AdminEventoController::class, 'create'])
         ->name('admin.eventos.create');
    Route::post('/admin/eventos', [AdminEventoController::class, 'store'])
         ->name('admin.eventos.store');
    Route::get('/admin/eventos/{evento}/editar', [AdminEventoController::class, 'edit'])
         ->name('admin.eventos.edit');
    Route::put('/admin/eventos/{evento}', [AdminEventoController::class, 'update'])
         ->name('admin.eventos.update');
    Route::delete('/admin/eventos/{evento}', [AdminEventoController::class, 'destroy'])
         ->name('admin.eventos.destroy');

    /* Rutas de gestión de empresas */
    Route::get('/admin/empresas', [AdminEmpresaController::class, 'index'])
         ->name('admin.empresas.index');
    Route::post('/admin/empresas/{id}/aprobar', [AdminEmpresaController::class, 'aprobar'])
         ->name('admin.empresas.aprobar');
    Route::post('/admin/empresas/{id}/rechazar', [AdminEmpresaController::class, 'rechazar'])
         ->name('admin.empresas.rechazar');

    /* Rutas de gestión de usuarios */
    Route::get('/admin/usuarios', [AdminUsuarioController::class, 'index'])
         ->name('admin.usuarios.index');
    Route::get('/admin/usuarios/crear', [AdminUsuarioController::class, 'create'])
         ->name('admin.usuarios.create');
    Route::post('/admin/usuarios', [AdminUsuarioController::class, 'store'])
         ->name('admin.usuarios.store');
    Route::get('/admin/usuarios/{usuario}/editar', [AdminUsuarioController::class, 'edit'])
         ->name('admin.usuarios.edit');
    Route::put('/admin/usuarios/{usuario}', [AdminUsuarioController::class, 'update'])
         ->name('admin.usuarios.update');
    Route::delete('/admin/usuarios/{usuario}', [AdminUsuarioController::class, 'destroy'])
         ->name('admin.usuarios.destroy');

    /* Rutas de gestión de pedidos */
    Route::get('/admin/pedidos', [AdminPedidoController::class, 'index'])
         ->name('admin.pedidos.index');
    Route::get('/admin/pedidos/crear', [AdminPedidoController::class, 'create'])
         ->name('admin.pedidos.create');
    Route::post('/admin/pedidos', [AdminPedidoController::class, 'store'])
         ->name('admin.pedidos.store');
    Route::get('/admin/pedidos/{pedido}/editar', [AdminPedidoController::class, 'edit'])
         ->name('admin.pedidos.edit');
    Route::put('/admin/pedidos/{pedido}', [AdminPedidoController::class, 'update'])
         ->name('admin.pedidos.update');
    Route::delete('/admin/pedidos/{pedido}', [AdminPedidoController::class, 'destroy'])
         ->name('admin.pedidos.destroy');

    /* Rutas de gestión de pagos */
    Route::get('/admin/pagos', [AdminPagoController::class, 'index'])
         ->name('admin.pagos.index');
    Route::get('/admin/pagos/crear', [AdminPagoController::class, 'create'])
         ->name('admin.pagos.create');
    Route::post('/admin/pagos', [AdminPagoController::class, 'store'])
         ->name('admin.pagos.store');
    Route::get('/admin/pagos/{pago}/editar', [AdminPagoController::class, 'edit'])
         ->name('admin.pagos.edit');
    Route::put('/admin/pagos/{pago}', [AdminPagoController::class, 'update'])
         ->name('admin.pagos.update');
    Route::delete('/admin/pagos/{pago}', [AdminPagoController::class, 'destroy'])
         ->name('admin.pagos.destroy');
});

/* — Endpoints AJAX: cargados desde api.php con prefijo /api —
     Heredan el middleware 'web' al estar dentro de web.php */
Route::prefix('api')->group(base_path('routes/api.php'));

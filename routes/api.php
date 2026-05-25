<?php

/**
 * VIBEZ — routes/api.php
 *
 * Solo contiene los endpoints AJAX que necesitan respuesta dinámica sin recargar página.
 * Cargado desde web.php con el prefijo /api, hereda el middleware 'web' (sesión + CSRF).
 *
 * URLs resultantes:
 *   POST /api/login              → AuthController@login
 *   POST /api/register           → AuthController@register
 *   POST /api/logout             → AuthController@logout
 *   GET  /api/amigos/buscar      → PerfilController@buscarUsuarios  (buscador dinámico)
 *   POST /api/amigos/solicitud   → PerfilController@enviarSolicitud (botón en resultados)
 */

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventoPostController;
use App\Http\Controllers\FavoritoController;
use App\Http\Controllers\EntradaController;
use App\Http\Controllers\CuponController;
use App\Http\Controllers\HistoriaController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\SeguimientoController;
use App\Http\Controllers\SocialController;
use Illuminate\Support\Facades\Route;

// Login — throttle: máx 5 intentos por minuto por IP
Route::post('/login', [AuthController::class, 'login'])
     ->middleware('throttle:5,1')
     ->name('api.login');

// Autenticación social — Google y Apple
// throttle:10,1 → máx 10 intentos por minuto (más permisivo que login clásico
// porque el token ya fue validado por el proveedor externo)
Route::post('/google-auth', [AuthController::class, 'googleAuth'])
     ->middleware('throttle:10,1')
     ->name('api.google-auth');

Route::post('/apple-auth', [AuthController::class, 'appleAuth'])
     ->middleware('throttle:10,1')
     ->name('api.apple-auth');

// Register — mismo límite de rate
Route::post('/register', [AuthController::class, 'register'])
     ->middleware('throttle:5,1')
     ->name('api.register');

// Logout — solo accesible si hay sesión activa
Route::post('/logout', [AuthController::class, 'logout'])
     ->middleware('auth')
     ->name('api.logout');

// Búsqueda dinámica de amigos y envío de solicitud
// Usan AJAX porque los resultados se muestran sin recargar la página
Route::middleware('auth')->group(function () {
    // Notificaciones internas — campanita del nav
    Route::get('/notificaciones',              [NotificacionController::class, 'index'])->name('api.notificaciones');
    Route::post('/notificaciones/leer-todas',  [NotificacionController::class, 'leerTodas'])->name('api.notificaciones.leer-todas');
    Route::post('/notificaciones/{id}/leer',   [NotificacionController::class, 'leer'])->name('api.notificaciones.leer');

    Route::get('/amigos/buscar',    [PerfilController::class, 'buscarUsuarios'])->name('api.amigos.buscar');
    Route::post('/amigos/solicitud', [PerfilController::class, 'enviarSolicitud'])->name('api.amigos.solicitud');
    Route::post('/favoritos/toggle', [FavoritoController::class, 'toggle'])->name('api.favoritos.toggle');

    // Seguimiento de promotoras
    Route::get('/seguimientos/ids',                    [SeguimientoController::class, 'misSeguidosIds'])->name('api.seguimientos.ids');
    Route::get('/seguimientos/promotoras',             [SeguimientoController::class, 'misPromotoras'])->name('api.seguimientos.promotoras');
    Route::post('/seguimientos/{empresa}/toggle',      [SeguimientoController::class, 'toggle'])->name('api.seguimientos.toggle');

    // Compra de entradas
    Route::post('/entradas/comprar', [EntradaController::class, 'comprar'])->name('api.entradas.comprar');

    // Stripe: crear PaymentIntent (eventos de pago con Stripe Connect)
    Route::post('/stripe/crear-payment-intent', [EntradaController::class, 'crearPaymentIntent'])->name('api.stripe.crear-payment-intent');

    // Stripe: confirmar pedido tras pago exitoso
    Route::post('/entradas/confirmar-stripe', [EntradaController::class, 'confirmarStripe'])->name('api.entradas.confirmar-stripe');

    // Validar código de cupón (requiere sesión para verificar límites por usuario)
    Route::post('/cupones/validar', [CuponController::class, 'validar'])->name('api.cupones.validar');
});

// ── Sección Social ──────────────────────────────────────────
// Todos los endpoints requieren sesión activa
Route::middleware('auth')->prefix('social')->group(function () {

    // Amigos
    Route::get('/amigos',                        [SocialController::class, 'misAmigos']);
    Route::get('/solicitudes',                   [SocialController::class, 'solicitudesPendientes']);
    Route::post('/solicitudes/{id}/aceptar',     [SocialController::class, 'aceptarSolicitud']);
    Route::post('/solicitudes/{id}/rechazar',    [SocialController::class, 'rechazarSolicitud']);
    Route::get('/usuarios/buscar',               [SocialController::class, 'buscarUsuarios']);
    Route::post('/solicitud',                    [SocialController::class, 'enviarSolicitud']);

    // Chats y mensajes
    Route::get('/chats',                         [SocialController::class, 'misChats']);
    Route::post('/chats/abrir',                  [SocialController::class, 'abrirChat']);
    Route::post('/chats/grupo',                  [SocialController::class, 'crearGrupo']);
    Route::get('/chats/{id}/mensajes',           [SocialController::class, 'mensajesChat']);
    Route::post('/chats/{id}/mensajes',          [SocialController::class, 'enviarMensaje']);
    Route::get('/chats/{id}/nuevos',             [SocialController::class, 'mensajesNuevos']);

    // Badge del navbar
    Route::get('/contador',                      [SocialController::class, 'contadorNoLeidos']);

    // Publicaciones de eventos
    Route::get('/posts',                         [EventoPostController::class, 'feed']);
    Route::post('/posts',                        [EventoPostController::class, 'store']);
    Route::get('/posts/{id}/comentarios',        [EventoPostController::class, 'comentariosPaginados'])->where('id', '[0-9]+');
    Route::post('/posts/{id}/comentarios',       [EventoPostController::class, 'comentar'])->where('id', '[0-9]+');
    Route::post('/posts/{id}/like',              [EventoPostController::class, 'toggleLike'])->where('id', '[0-9]+');
    Route::get('/mis-eventos-asistidos',         [EventoPostController::class, 'misEventosAsistidos']);
<<<<<<< HEAD

    // Historias
    Route::get('/historias',                     [HistoriaController::class, 'feed']);
    Route::post('/historias',                    [HistoriaController::class, 'store']);
    Route::post('/historias/{id}/vista',         [HistoriaController::class, 'vista'])->where('id', '[0-9]+');
    Route::get('/mis-historias',                 [HistoriaController::class, 'misHistorias']);

    // Filtro por evento
    Route::get('/eventos-con-contenido',         [EventoPostController::class, 'eventosConContenido']);
    Route::get('/evento/{eventoId}',             [EventoPostController::class, 'feedPorEvento'])->where('eventoId', '[0-9]+');
});
=======
});
>>>>>>> f1367d008a757bba14d54f01a53fcb743cdefeb9

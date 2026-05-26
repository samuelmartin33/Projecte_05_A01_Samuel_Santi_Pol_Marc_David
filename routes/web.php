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
use App\Http\Controllers\Admin\CategoriaEventoController as AdminCategoriaController;
use App\Http\Controllers\Admin\PedidoController as AdminPedidoController;
use App\Http\Controllers\Admin\PagoController as AdminPagoController;
use App\Http\Controllers\Admin\UsuarioController as AdminUsuarioController;
use App\Http\Controllers\Admin\FacturacionEventoController;
use App\Http\Controllers\Admin\CuponController as AdminCuponController;
use App\Http\Controllers\Admin\TrabajosController as AdminTrabajosController;
use App\Http\Controllers\CuponController;
use App\Http\Controllers\EventoController as PublicEventoController;
use App\Http\Controllers\Empresa\CandidaturasController;
use App\Http\Controllers\Empresa\ValidacionQRController;
use App\Http\Controllers\Empresa\FacturacionController;
use App\Http\Controllers\Empresa\EventosController as EmpresaEventosController;
use App\Http\Controllers\Empresa\OfertasController as EmpresaOfertasController;
use App\Http\Controllers\Empresa\EquipoController;
use App\Http\Controllers\Empresa\PerfilFiscalController;
use App\Http\Controllers\Empresa\PerfilEmpresaController;
use App\Http\Controllers\Empresa\StripeOnboardingController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\Empresa\CuponController as EmpresaCuponController;
use App\Http\Controllers\InvitacionEquipoController;
use App\Http\Controllers\HorasController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\PremiumController;
use App\Http\Controllers\SocialController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Models\Evento;
use App\Models\CategoriaEvento;
use App\Models\Usuario;
use App\Models\Empresa;

/*
|--------------------------------------------------------------------------
| Rutas Web de VIBEZ
|--------------------------------------------------------------------------
| Aquí se definen todas las rutas de la aplicación.
|
*/

// --- Landing de bienvenida: primera página que ve el usuario ---
Route::get('/', function () {
    $eventos = Evento::with(['categoria', 'portada'])
        ->where('estado', 1)
        ->orderBy('fecha_inicio', 'asc')
        ->get();

    $categorias = CategoriaEvento::where('estado', 1)
        ->orderBy('nombre')
        ->get();

    /* Eventos con coordenadas para el mapa Leaflet */
    $eventosMapa = $eventos
        ->filter(fn ($e) => $e->latitud && $e->longitud)
        ->map(fn ($e) => [
            'id'        => $e->id,
            'titulo'    => $e->titulo,
            'lat'       => $e->latitud,
            'lng'       => $e->longitud,
            'portada'   => $e->url_portada,
            'categoria' => $e->categoria?->nombre ?? 'Evento',
            'precio'    => $e->precio_formateado,
            'fecha'     => $e->fecha_inicio->locale('es')->isoFormat('D MMM'),
            'url'       => route('eventos.detalle', $e->id),
        ])
        ->values();

    /* Estadísticas reales para la sección proof */
    $statRavers     = Usuario::where('es_admin', 0)->where('estado', 1)->count();
    $statEventos    = Evento::where('estado', 1)->count();
    $statPromotores = Empresa::where('estado', 1)->count();
    $statSatisf     = 98;

    return view('welcome', compact('eventos', 'categorias', 'eventosMapa', 'statRavers', 'statEventos', 'statPromotores', 'statSatisf'));
})->name('welcome');

// --- Página pública de Eventos: lista completa con grid, filtros y mapa ---
Route::get('/eventos', [PublicEventoController::class, 'eventos'])
    ->name('eventos.index');

// --- Detalle de un evento específico ---
Route::get('/eventos/{id}', [PublicEventoController::class, 'detalle'])
    ->where('id', '[0-9]+')
    ->name('eventos.detalle');

// --- Página de compra de entradas (requiere login) ---
Route::get('/eventos/{id}/comprar', [PublicEventoController::class, 'compra'])
    ->where('id', '[0-9]+')
    ->middleware('auth')
    ->name('eventos.comprar');

// --- Detalle de una oferta de trabajo ---
Route::get('/trabajos/{id}', [PublicEventoController::class, 'detalleOferta'])
    ->where('id', '[0-9]+')
    ->name('trabajos.detalle');

// --- Postulación: formulario CV ---
Route::post('/trabajos/{id}/postular', [PublicEventoController::class, 'postular'])
    ->where('id', '[0-9]+')
    ->name('trabajos.postular');

// --- Postulación: subir archivo CV ---
Route::post('/trabajos/{id}/postular-archivo', [PublicEventoController::class, 'postularArchivo'])
    ->where('id', '[0-9]+')
    ->name('trabajos.postular-archivo');

// --- API AJAX: filtrar eventos y ofertas (responde JSON) ---
Route::get('/api/filtrar', [PublicEventoController::class, 'filtrar'])
    ->name('api.filtrar');

// --- Mapa de eventos a pantalla completa ---
Route::get('/mapa', [PublicEventoController::class, 'mapa'])
    ->name('mapa');

// --- Calendario mensual de eventos ---
Route::get('/calendario', [PublicEventoController::class, 'calendario'])
    ->name('calendario');

// --- Páginas estáticas del footer ---
Route::view('/quienes-somos',  'static.quienes-somos')->name('quienes-somos');
Route::view('/manifiesto',     'static.manifiesto')->name('manifiesto');
Route::view('/prensa',         'static.prensa')->name('prensa');
Route::view('/contacto',       'static.contacto')->name('contacto');
Route::view('/privacidad',     'static.privacidad')->name('privacidad');
Route::view('/cookies',        'static.cookies')->name('cookies');
Route::view('/terminos',       'static.terminos')->name('terminos');
Route::view('/devoluciones',   'static.devoluciones')->name('devoluciones');

// --- Página pública de cupones ---
Route::get('/cupones', [CuponController::class, 'index'])
    ->name('cupones.index');

// --- Aceptar invitación al equipo de empresa (enlace del correo de selección) ---
Route::get('/equipo/aceptar/{token}', [InvitacionEquipoController::class, 'aceptar'])
    ->name('equipo.aceptar');

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

/* — Google OAuth (Socialite — flujo server-side redirect) — */
Route::get('/auth/google',          [\App\Http\Controllers\GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [\App\Http\Controllers\GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

/* — Recuperación de contraseña (solo invitados) — */
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password',         [\App\Http\Controllers\PasswordResetController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password',        [\App\Http\Controllers\PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}',  [\App\Http\Controllers\PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password',         [\App\Http\Controllers\PasswordResetController::class, 'resetPassword'])->name('password.update');
});

/* — Home de usuario: protegido por middleware auth — */
Route::get('/home', [AuthController::class, 'showHome'])
     ->middleware('auth')
     ->name('home');

/* — Home de empresa: protegido por middleware auth — */
Route::get('/empresa/home', [AuthController::class, 'showEmpresaHome'])
     ->middleware('auth')
     ->name('empresa.home');

/* — Eventos de empresa: crear y eliminar eventos (auth requerido, bloqueado a porteros) — */
Route::middleware(['auth','no-portero'])->prefix('empresa/eventos')->name('empresa.eventos.')->group(function () {
    Route::get('/crear', [EmpresaEventosController::class, 'create'])->name('create');
    Route::post('/',     [EmpresaEventosController::class, 'store'])->name('store');
    Route::get('/{id}/editar', [EmpresaEventosController::class, 'edit'])->where('id', '[0-9]+')->name('edit');
    Route::put('/{id}',        [EmpresaEventosController::class, 'update'])->where('id', '[0-9]+')->name('update');
    Route::delete('/{id}', [EmpresaEventosController::class, 'destroy'])->where('id', '[0-9]+')->name('destroy');
});

/* — Ofertas de trabajo (empresa) — */
Route::middleware(['auth','no-portero'])->prefix('empresa/ofertas')->name('empresa.ofertas.')->group(function () {
    Route::get('/crear', [EmpresaOfertasController::class, 'create'])->name('create');
    Route::post('/',     [EmpresaOfertasController::class, 'store'])->name('store');
});

/* — Candidaturas (empresa) — */
Route::middleware(['auth','no-portero'])->prefix('empresa/candidaturas')->name('empresa.candidaturas.')->group(function () {
    Route::get('/',                              [CandidaturasController::class, 'ofertas'])->name('ofertas');
    Route::get('/{ofertaId}',                    [CandidaturasController::class, 'candidaturas'])->where('ofertaId', '[0-9]+')->name('detalle');
    Route::patch('/{candidaturaId}/estado',          [CandidaturasController::class, 'actualizarEstado'])->where('candidaturaId', '[0-9]+')->name('estado');
    Route::get('/{candidaturaId}/descargar',         [CandidaturasController::class, 'descargarCv'])->where('candidaturaId', '[0-9]+')->name('descargar');
    Route::post('/{candidaturaId}/enviar-seleccion', [CandidaturasController::class, 'enviarEmailSeleccion'])->where('candidaturaId', '[0-9]+')->name('enviar-seleccion');
    Route::patch('/oferta/{ofertaId}/cerrar',        [CandidaturasController::class, 'cerrarOferta'])->where('ofertaId', '[0-9]+')->name('cerrar-oferta');
    // Ver horas registradas por un candidato (desde la empresa)
    Route::get('/{candidaturaId}/horas-trabajador',  [HorasController::class, 'verHorasTrabajador'])->where('candidaturaId', '[0-9]+')->name('horas-trabajador');
});

/* — Validación QR (accesible también a porteros) — */
Route::middleware('auth')->prefix('empresa/validacion')->name('empresa.validacion.')->group(function () {
    Route::get('/',         [ValidacionQRController::class, 'index'])->name('index');
    Route::post('/validar', [ValidacionQRController::class, 'validar'])->name('validar');
});

/* — Facturación de empresa — */
Route::middleware(['auth','no-portero'])->prefix('empresa/facturacion')->name('empresa.facturacion.')->group(function () {
    Route::get('/',                            [FacturacionController::class, 'index'])     ->name('index');
    Route::get('/{factura}/descargar',         [FacturacionController::class, 'descargar']) ->name('descargar');
    Route::get('/evento/{evento}/generar-pdf', [FacturacionController::class, 'generarPdf'])->name('generar-pdf');
});

/* — Cupones de empresa: cada empresa gestiona sus propios cupones — */
Route::middleware(['auth','no-portero'])->prefix('empresa/cupones')->name('empresa.cupones.')->group(function () {
    Route::get('/',           [EmpresaCuponController::class, 'index']) ->name('index');
    Route::get('/crear',      [EmpresaCuponController::class, 'create'])->name('create');
    Route::post('/',          [EmpresaCuponController::class, 'store']) ->name('store');
    Route::get('/{id}/editar',[EmpresaCuponController::class, 'edit'])  ->name('edit');
    Route::put('/{id}',       [EmpresaCuponController::class, 'update'])->name('update');
    Route::delete('/{id}',    [EmpresaCuponController::class, 'destroy'])->name('destroy');
});

/* — Perfil fiscal de empresa: fase 2 del onboarding (datos legales, bancarios y Stripe) — */
Route::middleware(['auth','no-portero'])->prefix('empresa')->name('empresa.')->group(function () {
    Route::get('/perfil-fiscal',  [PerfilFiscalController::class, 'show'])  ->name('perfil-fiscal');
    Route::post('/perfil-fiscal', [PerfilFiscalController::class, 'update'])->name('perfil-fiscal.guardar');
    Route::get('/perfil',         [PerfilEmpresaController::class, 'show'])  ->name('perfil');
    Route::post('/perfil',        [PerfilEmpresaController::class, 'update'])->name('perfil.guardar');
});

/* — Stripe Connect: onboarding de cuentas Express para empresas — */
Route::middleware(['auth','no-portero'])->prefix('empresa/stripe')->name('empresa.stripe.')->group(function () {
    Route::get('/conectar',  [StripeOnboardingController::class, 'iniciar'])  ->name('conectar');
    Route::get('/retorno',   [StripeOnboardingController::class, 'retorno'])  ->name('retorno');
    Route::get('/refrescar', [StripeOnboardingController::class, 'refrescar'])->name('refrescar');
});

/* — Stripe Webhook: recibe eventos de Stripe (sin CSRF, sin auth) — */
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');

/* — Equipo de empresa: gestión de usuarios y roles — */
Route::middleware(['auth','no-portero'])->prefix('empresa/equipo')->name('empresa.equipo.')->group(function () {
    Route::get('/',              [EquipoController::class, 'index'])->name('index');
    Route::post('/',             [EquipoController::class, 'store'])->name('store');
    Route::patch('/{organizador}/rol', [EquipoController::class, 'cambiarRol'])->name('rol');
    Route::delete('/{organizador}',    [EquipoController::class, 'destroy'])->name('destroy');
    // Ver horas de un miembro del equipo
    Route::get('/{usuarioId}/horas', [HorasController::class, 'verHorasEquipo'])
         ->where('usuarioId', '[0-9]+')->name('horas');
});

/* — Perfil de usuario — */
Route::middleware('auth')->group(function () {

    // Vista principal del perfil
    Route::get('/perfil', [PerfilController::class, 'show'])->name('perfil');

    // Mis entradas (wallet de QRs)
    Route::get('/mis-entradas', [\App\Http\Controllers\EntradaController::class, 'misEntradas'])
         ->name('entradas.mis-entradas');

    // Registro de horas diarias (para organizadores y porteros)
    Route::get('/mis-horas',  [HorasController::class, 'index'])->name('horas.index');
    Route::post('/mis-horas', [HorasController::class, 'store'])->name('horas.store');

    // Confirmación de compra de entradas
    Route::get('/entradas/confirmacion/{pedido}', [\App\Http\Controllers\EntradaController::class, 'confirmacion'])
         ->name('entradas.confirmacion');

    // Formulario de datos personales (POST simple, sin AJAX)
    Route::post('/perfil', [PerfilController::class, 'actualizar'])->name('perfil.actualizar');

    // Formulario de foto (POST con archivo, sin AJAX)
    Route::post('/perfil/foto', [PerfilController::class, 'actualizarFoto'])->name('perfil.foto');

    // Formulario de mood/estado de ánimo (POST simple)
    Route::post('/perfil/mood', [PerfilController::class, 'actualizarMood'])->name('perfil.mood');

     // Página de favoritos: muestra solo los eventos marcados como favoritos
     Route::get('/perfil/favoritos', [PerfilController::class, 'favoritos'])->name('perfil.favoritos');

    // Aceptar / rechazar solicitudes de amistad (botones de formulario)
    Route::post('/amigos/{id}/aceptar',  [PerfilController::class, 'aceptarSolicitud'])->name('amigos.aceptar');
    Route::post('/amigos/{id}/rechazar', [PerfilController::class, 'rechazarSolicitud'])->name('amigos.rechazar');
});

/* — Premium: oferta, checkout con Stripe y páginas de retorno — */
Route::middleware('auth')->group(function () {
    // Página de oferta: muestra los beneficios Premium y el botón de pago.
    Route::get('/premium',           [PremiumController::class, 'mostrar'])->name('premium');
    // Inicia el checkout de Stripe (POST para prevenir accesos directos desde URL).
    Route::post('/premium/checkout', [PremiumController::class, 'iniciarCheckout'])->name('premium.checkout');
    // Stripe redirige aquí tras pago exitoso.
    Route::get('/premium/exito',     [PremiumController::class, 'exito'])->name('premium.exito');
    // Stripe redirige aquí si el usuario cancela.
    Route::get('/premium/cancelado', [PremiumController::class, 'cancelado'])->name('premium.cancelado');
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
    Route::get('/admin/empresas/{id}', [AdminEmpresaController::class, 'show'])
         ->where('id', '[0-9]+')
         ->name('admin.empresas.show');
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
    Route::patch('/admin/usuarios/{usuario}/activar', [AdminUsuarioController::class, 'activar'])
         ->name('admin.usuarios.activar');

    /* Rutas de gestión de categorías de eventos */
    Route::get('/admin/categorias', [AdminCategoriaController::class, 'index'])
         ->name('admin.categorias.index');
    Route::get('/admin/categorias/crear', [AdminCategoriaController::class, 'create'])
         ->name('admin.categorias.create');
    Route::post('/admin/categorias', [AdminCategoriaController::class, 'store'])
         ->name('admin.categorias.store');
    Route::get('/admin/categorias/{categoria}/editar', [AdminCategoriaController::class, 'edit'])
         ->name('admin.categorias.edit');
    Route::put('/admin/categorias/{categoria}', [AdminCategoriaController::class, 'update'])
         ->name('admin.categorias.update');
    Route::delete('/admin/categorias/{categoria}', [AdminCategoriaController::class, 'destroy'])
         ->name('admin.categorias.destroy');

    /* Rutas de gestión de tipos de trabajo */
    Route::get('/admin/trabajos', [AdminTrabajosController::class, 'index'])
         ->name('admin.trabajos.index');
    Route::post('/admin/trabajos', [AdminTrabajosController::class, 'store'])
         ->name('admin.trabajos.store');
    Route::patch('/admin/trabajos/{categoria}/estado', [AdminTrabajosController::class, 'toggleEstado'])
         ->name('admin.trabajos.estado');
    Route::patch('/admin/trabajos/{categoria}', [AdminTrabajosController::class, 'update'])
         ->name('admin.trabajos.update');
    Route::delete('/admin/trabajos/{categoria}', [AdminTrabajosController::class, 'destroy'])
         ->name('admin.trabajos.destroy');

    /* Rutas de pedidos (solo lectura) */
    Route::get('/admin/pedidos', [AdminPedidoController::class, 'index'])
         ->name('admin.pedidos.index');

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

    /* Cupones (solo lectura para el admin) */
    Route::get('/admin/cupones', [AdminCuponController::class, 'index'])
         ->name('admin.cupones.index');

    /* Facturación por evento */
    Route::prefix('admin/facturacion')->name('admin.facturacion.')->group(function () {
        Route::get('/',                          [FacturacionEventoController::class, 'index'])    ->name('index');
        Route::get('/{evento}/empezar',          [FacturacionEventoController::class, 'empezar'])  ->name('empezar');
        Route::post('/{evento}/confirmar',       [FacturacionEventoController::class, 'confirmar'])->name('confirmar');
        Route::get('/factura/{factura}/descargar',[FacturacionEventoController::class, 'descargar'])->name('descargar');
        Route::patch('/factura/{factura}/anular', [FacturacionEventoController::class, 'anular'])   ->name('anular');
    });
});

/* — Migración remota: permite ejecutar migraciones desde el servidor sin SSH — */
Route::get('/migrate', function (\Illuminate\Http\Request $request) {
    if ($request->query('secret') !== 'vibez_migrate_2026') {
        abort(403);
    }
    Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);
    return '<pre>' . Artisan::output() . '</pre>';
});

/* — Endpoints AJAX: cargados desde api.php con prefijo /api —
     Heredan el middleware 'web' al estar dentro de web.php */
Route::prefix('api')->group(base_path('routes/api.php'));

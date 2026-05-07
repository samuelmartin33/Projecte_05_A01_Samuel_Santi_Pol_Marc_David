<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Admin Dashboard'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?php echo e(asset('css/app-static.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/admin-eventos.css')); ?>">
</head>
<body>


<header class="nav-vibez sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">

        <?php $esEmpresa = Auth::check() && Auth::user()->isEmpresa(); ?>

        
        <a href="<?php echo e($esEmpresa ? route('empresa.home') : route('home')); ?>"
           class="nav-logo-link group">
            <img src="<?php echo e(asset('images/logo_vibez_white.png')); ?>"
                 alt="VIBEZ"
                 class="nav-logo-img">
        </a>

        
        <nav class="hidden md:flex items-center gap-8">
            <?php if($esEmpresa): ?>
                <a href="<?php echo e(route('empresa.home')); ?>"
                   class="nav-link <?php echo e(request()->routeIs('empresa.home') ? 'nav-link-activo' : ''); ?>">
                    Panel
                </a>
                <a href="<?php echo e(route('empresa.candidaturas.ofertas')); ?>"
                   class="nav-link <?php echo e(request()->routeIs('empresa.candidaturas.*') ? 'nav-link-activo' : ''); ?>">
                    Candidaturas
                </a>
            <?php elseif(Auth::check() && Auth::user()->es_admin): ?>
                <a href="<?php echo e(route('admin.dashboard')); ?>"
                   class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'nav-link-activo' : ''); ?>">
                    Inicio
                </a>
                <a href="<?php echo e(route('admin.eventos.index')); ?>"
                   class="nav-link <?php echo e(request()->routeIs('admin.eventos.*') ? 'nav-link-activo' : ''); ?>">
                    Eventos
                </a>
                <a href="<?php echo e(route('admin.empresas.index')); ?>"
                   class="nav-link <?php echo e(request()->routeIs('admin.empresas.*') ? 'nav-link-activo' : ''); ?>">
                    Empresas
                </a>
                <a href="<?php echo e(route('admin.usuarios.index')); ?>"
                   class="nav-link <?php echo e(request()->routeIs('admin.usuarios.*') ? 'nav-link-activo' : ''); ?>">
                    Usuarios
                </a>
                <a href="<?php echo e(route('admin.categorias.index')); ?>"
                   class="nav-link <?php echo e(request()->routeIs('admin.categorias.*') ? 'nav-link-activo' : ''); ?>">
                    Categorías
                </a>
                <a href="<?php echo e(route('admin.pedidos.index')); ?>"
                   class="nav-link <?php echo e(request()->routeIs('admin.pedidos.*') ? 'nav-link-activo' : ''); ?>">
                    Pedidos
                </a>
                <a href="<?php echo e(route('admin.pagos.index')); ?>"
                   class="nav-link <?php echo e(request()->routeIs('admin.pagos.*') ? 'nav-link-activo' : ''); ?>">
                    Pagos
                </a>
            <?php else: ?>
                <a href="<?php echo e(route('home')); ?>"
                   class="nav-link <?php echo e(request()->routeIs('home') ? 'nav-link-activo' : ''); ?>">
                    Explorar
                </a>
                <a href="<?php echo e(route('trabajos.index')); ?>"
                   class="nav-link <?php echo e(request()->routeIs('trabajos.index') ? 'nav-link-activo' : ''); ?>">
                    Trabajo
                </a>
                <?php if(auth()->guard()->check()): ?>
                <a href="<?php echo e(route('social')); ?>"
                   class="nav-link relative <?php echo e(request()->routeIs('social') ? 'nav-link-activo' : ''); ?>">
                    Social
                    <span class="nav-badge-social" id="nav-badge-social" style="display:none">0</span>
                </a>
                <?php endif; ?>
            <?php endif; ?>
        </nav>

        
        <div class="flex items-center gap-3">
            <?php if(auth()->guard()->guest()): ?>
                <a href="<?php echo e(route('login')); ?>"
                   class="hidden sm:block btn-nav-ghost">
                    Entrar
                </a>
                <a href="<?php echo e(route('register')); ?>"
                   class="btn-nav-solido">
                    <span>Registro &nbsp;→</span>
                </a>
            <?php else: ?>
                
                <div class="nav-avatar-wrapper" id="navAvatarWrapper">

                    
                    
                    <div style="position:relative;display:inline-block">
                        <button class="nav-avatar" id="navAvatarBtn"
                                onclick="toggleNavDropdown()"
                                aria-haspopup="true" aria-expanded="false">
                            <?php if(Auth::user()->foto_url): ?>
                                <img src="<?php echo e(Auth::user()->foto_url); ?>"
                                     alt="<?php echo e(Auth::user()->nombre); ?>"
                                     class="nav-avatar-img">
                            <?php else: ?>
                                <span class="nav-avatar-iniciales">
                                    <?php echo e(strtoupper(substr(Auth::user()->nombre, 0, 1))); ?><?php echo e(strtoupper(substr(Auth::user()->apellido1 ?? '', 0, 1))); ?>

                                </span>
                            <?php endif; ?>
                        </button>

                        
                        <?php if(Auth::user()->mood): ?>
                            <span class="nav-mood-badge" title="<?php echo e(Auth::user()->mood); ?>">
                                
                                <?php echo e(explode(' ', Auth::user()->mood, 2)[0]); ?>

                            </span>
                        <?php endif; ?>
                    </div>

                    
                    <div class="nav-dropdown" id="navDropdown" style="display:none">

                        
                        <div class="nav-dropdown-header">
                            <p class="nav-dropdown-nombre"><?php echo e(Auth::user()->nombre); ?> <?php echo e(Auth::user()->apellido1); ?></p>
                            <p class="nav-dropdown-email"><?php echo e(Auth::user()->email); ?></p>
                            <?php if(Auth::user()->mood): ?>
                                <p class="nav-dropdown-mood"><?php echo e(Auth::user()->mood); ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="nav-dropdown-divider"></div>

                        
                        <a href="<?php echo e(route('perfil')); ?>" class="nav-dropdown-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Mi perfil
                        </a>

                        <?php if(Auth::check() && Auth::user()->es_admin): ?>
                            <a href="<?php echo e(route('home')); ?>" class="nav-dropdown-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3 12l2-3m0 0l7-4 7 4M5 9v7a1 1 0 001 1h12a1 1 0 001-1V9m-9 13l-4-4m0 0l-2-2m2 2l2-2m-2 2l4 4m0 0l2 2m-2-2l-2 2"/>
                                </svg>
                                Volver al inicio
                            </a>
                        <?php elseif($esEmpresa): ?>
                            
                            <a href="<?php echo e(route('empresa.home')); ?>" class="nav-dropdown-item" style="color:#7c3aed;font-weight:700">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                Panel Empresa
                            </a>
                            <a href="<?php echo e(route('empresa.candidaturas.ofertas')); ?>" class="nav-dropdown-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Revisar Currículums
                            </a>
                        <?php else: ?>
                            
                            <?php if(!Auth::user()->isAdmin()): ?>
                            <a href="<?php echo e(route('entradas.mis-entradas')); ?>" class="nav-dropdown-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                </svg>
                                Mis entradas
                            </a>
                            <?php endif; ?>

                            
                            <a href="<?php echo e(route('perfil')); ?>#amigos" class="nav-dropdown-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Amigos
                            </a>
                        <?php endif; ?>

                        <div class="nav-dropdown-divider"></div>

                        
                        <button class="nav-dropdown-item nav-dropdown-logout"
                                onclick="cerrarSesion()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Cerrar sesión
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>

<div class="dashboard-wrap">
    <div class="dashboard-main">
        <main class="admin-shell">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?php echo e(asset('js/app-nav.js')); ?>"></script>
<script src="<?php echo e(asset('js/admin-eventos.js')); ?>"></script>


</body>
</html>
<?php /**PATH C:\wamp64\www\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/admin/layouts/dashboard.blade.php ENDPATH**/ ?>
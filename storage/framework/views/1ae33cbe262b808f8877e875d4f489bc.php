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

        
        <a href="<?php echo e(route('home')); ?>" class="nav-logo-link group">
            <img src="<?php echo e(asset('images/logo_vibez_white.png')); ?>"
                 alt="VIBEZ"
                 class="nav-logo-img">
        </a>

        
        <nav class="hidden md:flex items-center gap-6">
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
            <a href="<?php echo e(route('admin.pedidos.index')); ?>"
               class="nav-link <?php echo e(request()->routeIs('admin.pedidos.*') ? 'nav-link-activo' : ''); ?>">
                Pedidos
            </a>
            <a href="<?php echo e(route('admin.pagos.index')); ?>"
               class="nav-link <?php echo e(request()->routeIs('admin.pagos.*') ? 'nav-link-activo' : ''); ?>">
                Pagos
            </a>
        </nav>

        
        <div class="flex items-center gap-3">
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

                    <a href="<?php echo e(route('home')); ?>" class="nav-dropdown-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 12l2-3m0 0l7-4 7 4M5 9v7a1 1 0 001 1h12a1 1 0 001-1V9m-9 13l-4-4m0 0l-2-2m2 2l2-2m-2 2l4 4m0 0l2 2m-2-2l-2 2"/>
                        </svg>
                        Volver al inicio
                    </a>

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
<?php /**PATH C:\wamp64\www\DAW2\0616\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/admin/layouts/dashboard.blade.php ENDPATH**/ ?>
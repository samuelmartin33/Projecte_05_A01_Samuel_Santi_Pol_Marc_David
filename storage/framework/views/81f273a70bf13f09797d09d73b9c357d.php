<!DOCTYPE html>
<html lang="es" class="<?php echo $__env->yieldContent('html-class', ''); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php if (! empty(trim($__env->yieldContent('title')))): ?><?php echo $__env->yieldContent('title'); ?><?php else: ?> <?php echo $__env->yieldContent('titulo', 'VIBEZ'); ?> — Descubre tu próximo evento <?php endif; ?></title>

    <link rel="icon" type="image/png" href="<?php echo e(asset('images/logo_vibez.png')); ?>">

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Bebas+Neue&family=Archivo:wght@400;500;600;700;800;900&family=Archivo+Narrow:wght@400;500;600;700&family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,700;0,9..40,900;1,9..40,700&display=swap" rel="stylesheet">

    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @theme {
            --color-paper:  #f5f1ea;
            --color-ink:    #0f172a;
            --color-lilac:  #7c3aed;
            --color-plum:   #4e3a96;
            --color-dusk:   #1e1035;
            --font-display: 'DM Sans', ui-sans-serif, system-ui, sans-serif;
            --font-sans:    'Syne', ui-sans-serif, system-ui, sans-serif;
            --tracking-brutal:   0.04em;
            --tracking-tightest: -0.02em;
        }
    </style>

    <link rel="stylesheet" href="<?php echo e(asset('css/app-static.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/vibez.css')); ?>">

    <?php if(request()->routeIs('login') || request()->routeIs('register')): ?>
        <link rel="stylesheet" href="<?php echo e(asset('css/auth-vibez.css')); ?>">
    <?php endif; ?>

    
    <?php echo $__env->yieldPushContent('estilos'); ?>
    <?php echo $__env->yieldContent('extra-css'); ?>
</head>
<body class="min-h-screen flex flex-col <?php echo $__env->yieldContent('body-class', ''); ?>">

    
    <?php if(!View::hasSection('content')): ?>
    <header class="nav-vibez sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 sm:px-10 flex items-center justify-between h-14">

            <?php $esEmpresa = Auth::check() && Auth::user()->isEmpresa(); ?>

            
            <a href="<?php echo e($esEmpresa ? route('empresa.home') : route('home')); ?>"
               class="flex items-center flex-shrink-0 select-none"
               style="opacity:1;transition:opacity 0.15s">
                <img src="<?php echo e(asset('images/logo_vibez_white.png')); ?>" alt="VIBEZ"
                     style="height:38px;width:auto;display:block;filter:drop-shadow(0 0 8px rgba(255,255,255,0.25));transition:filter 0.2s,transform 0.2s"
                     onmouseover="this.style.filter='drop-shadow(0 0 14px rgba(255,255,255,0.55))';this.style.transform='scale(1.04)'"
                     onmouseout="this.style.filter='drop-shadow(0 0 8px rgba(255,255,255,0.25))';this.style.transform='scale(1)'">
            </a>

            
            <nav class="hidden md:flex items-center gap-8">
                <?php if($esEmpresa): ?>
                    <a href="<?php echo e(route('empresa.home')); ?>"
                       class="font-mono text-xs uppercase tracking-widest transition-colors duration-100
                              <?php echo e(request()->routeIs('empresa.home') ? 'text-white' : 'text-white/60 hover:text-white'); ?>">
                        Panel
                    </a>
                    <a href="<?php echo e(route('empresa.candidaturas.ofertas')); ?>"
                       class="font-mono text-xs uppercase tracking-widest transition-colors duration-100
                              <?php echo e(request()->routeIs('empresa.candidaturas.*') ? 'text-white' : 'text-white/60 hover:text-white'); ?>">
                        Candidaturas
                    </a>
                    <a href="<?php echo e(route('empresa.facturacion.index')); ?>"
                       class="font-mono text-xs uppercase tracking-widest transition-colors duration-100
                              <?php echo e(request()->routeIs('empresa.facturacion.*') ? 'text-white' : 'text-white/60 hover:text-white'); ?>">
                        Administración
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('home')); ?>"
                       class="font-mono text-xs uppercase tracking-widest transition-colors duration-100
                              <?php echo e(request()->routeIs('home') ? 'text-white' : 'text-white/60 hover:text-white'); ?>">
                        Explorar
                    </a>
                    <a href="<?php echo e(route('cupones.index')); ?>"
                       class="font-mono text-xs uppercase tracking-widest transition-colors duration-100
                              <?php echo e(request()->routeIs('cupones.index') ? 'text-white' : 'text-white/60 hover:text-white'); ?>">
                        Cupones
                    </a>
                    <a href="<?php echo e(route('trabajos.index')); ?>"
                       class="font-mono text-xs uppercase tracking-widest transition-colors duration-100
                              <?php echo e(request()->routeIs('trabajos.index') ? 'text-white' : 'text-white/60 hover:text-white'); ?>">
                        Trabajo
                    </a>
                    <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('social')); ?>"
                       class="font-mono text-xs uppercase tracking-widest transition-colors duration-100 relative
                              <?php echo e(request()->routeIs('social') ? 'text-white' : 'text-white/60 hover:text-white'); ?>">
                        Social
                        <span class="nav-badge-social" id="nav-badge-social" style="display:none">0</span>
                    </a>
                    <?php endif; ?>
                <?php endif; ?>
            </nav>

            
            <div class="flex items-center gap-3">
                <?php if(auth()->guard()->guest()): ?>
                    <a href="<?php echo e(route('cupones.index')); ?>"
                       class="font-mono text-xs uppercase tracking-widest
                              text-white/65 hover:text-white transition-colors duration-100">
                        Cupones
                    </a>
                    <a href="<?php echo e(route('login')); ?>"
                       class="hidden sm:block font-mono text-xs uppercase tracking-widest
                              text-white/65 hover:text-white transition-colors duration-100">
                        Entrar
                    </a>
                    <a href="<?php echo e(route('register')); ?>"
                       class="font-mono text-xs uppercase tracking-widest px-5 py-2.5"
                       style="background:rgba(255,255,255,0.15);border:1.5px solid rgba(255,255,255,0.4);color:white;border-radius:999px;transition:background 0.15s,border-color 0.15s"
                       onmouseover="this.style.background='rgba(255,255,255,0.25)';this.style.borderColor='rgba(255,255,255,0.65)'"
                       onmouseout="this.style.background='rgba(255,255,255,0.15)';this.style.borderColor='rgba(255,255,255,0.4)'">
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
                                    <?php echo e(mb_substr(Auth::user()->mood, 0, 1)); ?>

                                </span>
                            <?php endif; ?>
                        </div>

                        
                        <div class="nav-dropdown" id="navDropdown" style="display:none">

                            
                            <div class="nav-dropdown-header">
                                <p class="nav-dropdown-nombre"><?php echo e(Auth::user()->nombre); ?> <?php echo e(Auth::user()->apellido1); ?></p>
                                <p class="nav-dropdown-email"><?php echo e(Auth::user()->email); ?></p>
                                <?php if(Auth::user()->mood): ?>
                                    <p class="nav-dropdown-mood"><?php echo e(explode(' ', Auth::user()->mood, 2)[0]); ?></p>
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

                            <?php if($esEmpresa): ?>
                                
                                <a href="<?php echo e(route('empresa.home')); ?>" class="nav-dropdown-item" style="color:#c084fc;font-weight:700">
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
                                <a href="<?php echo e(route('empresa.facturacion.index')); ?>" class="nav-dropdown-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    Facturación
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

                            
                            <?php if(Auth::user()->es_admin): ?>
                                <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-dropdown-item" style="color:#c084fc;font-weight:700">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Panel Admin
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

                
                <button class="nav-hamburger" id="navHamburger"
                        onclick="toggleMenuMovil()"
                        aria-label="Abrir menú" aria-expanded="false">
                    <svg class="icono-ham" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg class="icono-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display:none">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

        </div>
    </header>

    

    
    <div class="nav-movil-overlay" id="navMovilOverlay" onclick="cerrarMenuMovil()"></div>

    
    <nav class="nav-movil-panel" id="navMovilPanel" aria-label="Menú de navegación móvil">

        
        <div class="nav-movil-cabecera">
            <img src="<?php echo e(asset('images/logo_vibez_white.png')); ?>" alt="VIBEZ" class="nav-movil-logo">
            <button class="nav-movil-cerrar" onclick="cerrarMenuMovil()" aria-label="Cerrar menú">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        
        <?php if(auth()->guard()->check()): ?>
        <div class="nav-movil-usuario">
            <div class="nav-movil-avatar-sm">
                <?php if(Auth::user()->foto_url): ?>
                    <img src="<?php echo e(Auth::user()->foto_url); ?>" alt="<?php echo e(Auth::user()->nombre); ?>">
                <?php else: ?>
                    <?php echo e(strtoupper(substr(Auth::user()->nombre, 0, 1))); ?><?php echo e(strtoupper(substr(Auth::user()->apellido1 ?? '', 0, 1))); ?>

                <?php endif; ?>
            </div>
            <div>
                <p class="nav-movil-usuario-nombre"><?php echo e(Auth::user()->nombre); ?> <?php echo e(Auth::user()->apellido1); ?></p>
                <p class="nav-movil-usuario-email"><?php echo e(Auth::user()->email); ?></p>
            </div>
        </div>
        <?php endif; ?>

        <div class="nav-movil-divisor"></div>

        <?php if($esEmpresa): ?>
            
            <a href="<?php echo e(route('empresa.home')); ?>"
               class="nav-movil-link <?php echo e(request()->routeIs('empresa.home') ? 'nav-movil-activo' : ''); ?>"
               onclick="cerrarMenuMovil()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Panel
            </a>
            <a href="<?php echo e(route('empresa.candidaturas.ofertas')); ?>"
               class="nav-movil-link <?php echo e(request()->routeIs('empresa.candidaturas.*') ? 'nav-movil-activo' : ''); ?>"
               onclick="cerrarMenuMovil()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Candidaturas
            </a>
            <a href="<?php echo e(route('empresa.facturacion.index')); ?>"
               class="nav-movil-link <?php echo e(request()->routeIs('empresa.facturacion.*') ? 'nav-movil-activo' : ''); ?>"
               onclick="cerrarMenuMovil()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Facturación
            </a>
        <?php else: ?>
            
            <a href="<?php echo e(route('home')); ?>"
               class="nav-movil-link <?php echo e(request()->routeIs('home') ? 'nav-movil-activo' : ''); ?>"
               onclick="cerrarMenuMovil()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Explorar
            </a>
            <a href="<?php echo e(route('trabajos.index')); ?>"
               class="nav-movil-link <?php echo e(request()->routeIs('trabajos.index') ? 'nav-movil-activo' : ''); ?>"
               onclick="cerrarMenuMovil()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Bolsa de Trabajo
            </a>
            <?php if(auth()->guard()->check()): ?>
            <a href="<?php echo e(route('social')); ?>"
               class="nav-movil-link <?php echo e(request()->routeIs('social') ? 'nav-movil-activo' : ''); ?>"
               onclick="cerrarMenuMovil()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                Social
            </a>
            <?php endif; ?>
        <?php endif; ?>

        <div class="nav-movil-divisor"></div>

        <?php if(auth()->guard()->check()): ?>
        
        <a href="<?php echo e(route('perfil')); ?>" class="nav-movil-link" onclick="cerrarMenuMovil()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Mi perfil
        </a>

        <button class="nav-movil-link nav-movil-logout" onclick="cerrarSesion()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Cerrar sesión
        </button>
        <?php endif; ?>

        
        <?php if(auth()->guard()->guest()): ?>
        <div class="nav-movil-divisor"></div>
        <a href="<?php echo e(route('login')); ?>" class="nav-movil-link" onclick="cerrarMenuMovil()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            Entrar
        </a>
        <a href="<?php echo e(route('register')); ?>" class="nav-movil-link" onclick="cerrarMenuMovil()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Registro
        </a>
        <?php endif; ?>

    </nav>

    <?php endif; ?>

    
    <main class="flex-1">
        <?php echo $__env->yieldContent('contenido'); ?>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    
    <?php if(!View::hasSection('content')): ?>
    <footer class="bg-ink text-paper border-t border-ink">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 py-8 border-b border-paper/10">
                <div>
                    <span class="font-display font-black text-2xl tracking-brutal select-none">VIBEZ</span>
                    <p class="font-mono text-xs uppercase tracking-widest text-paper/35 mt-1">La plataforma de tu escena</p>
                </div>
                <nav class="flex flex-wrap gap-6 sm:gap-8 font-mono text-xs uppercase tracking-widest text-paper/35">
                    <a href="<?php echo e(route('home')); ?>" class="hover:text-paper transition-colors duration-100">Explorar</a>
                    <a href="<?php echo e(route('trabajos.index')); ?>" class="hover:text-paper transition-colors duration-100">Trabajo</a>
                    <a href="#" class="hover:text-paper transition-colors duration-100">Privacidad</a>
                    <a href="#" class="hover:text-paper transition-colors duration-100">Contacto</a>
                </nav>
            </div>
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 py-5">
                <p class="font-mono text-xs text-paper/25">&copy; <?php echo e(date('Y')); ?> VIBEZ — Todos los derechos reservados.</p>
                <p class="font-mono text-xs text-paper/20"><?php echo e(now()->format('d.m.y — H:i')); ?></p>
            </div>
        </div>
    </footer>
    <?php endif; ?>

    
    <?php echo $__env->yieldPushContent('scripts'); ?>
    <?php echo $__env->yieldContent('scripts'); ?>

    
    <script src="<?php echo e(asset('js/app-nav.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\wamp64\www\DAW2\proyectos\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/layouts/app.blade.php ENDPATH**/ ?>
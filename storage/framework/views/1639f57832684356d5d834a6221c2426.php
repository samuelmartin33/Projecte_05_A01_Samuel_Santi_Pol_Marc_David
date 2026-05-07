<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<<<<<<< HEAD
    <title>VIBEZ — Descubre tu próximo evento</title>
=======
    <title>VIBEZ — Bienvenido</title>
>>>>>>> feature/middleware
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/logo_vibez.png')); ?>">

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<<<<<<< HEAD
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Bebas+Neue&family=Archivo:wght@400;500;600;700;800;900&family=Archivo+Narrow:wght@400;500;600;700&display=swap" rel="stylesheet">

    
    <script src="https://cdn.tailwindcss.com"></script>

    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">

    
    <link rel="stylesheet" href="<?php echo e(asset('css/app-static.css')); ?>">

    
    <link rel="stylesheet" href="<?php echo e(asset('css/home-vibez.css')); ?>">

    <style>
        /* ════════════════════════════════════════════════════
           Estilos específicos de la landing pública VIBEZ
           ════════════════════════════════════════════════════ */

        /* ── Navbar fija sobre el hero ── */
        .welcome-nav {
            position: fixed;
            top: 0; left: 0; right: 0; z-index: 200;
            padding: 18px clamp(1.5rem, 4vw, 3.5rem);
            display: flex; align-items: center; justify-content: space-between;
            background: linear-gradient(180deg, rgba(7,6,12,0.88) 0%, transparent 100%);
            backdrop-filter: blur(4px);
        }
        .welcome-nav-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .welcome-nav-logo img { height: 34px; width: auto; }
        .welcome-nav-actions { display: flex; align-items: center; gap: 10px; }
        .welcome-btn-ghost {
            font-family: 'Archivo Narrow', sans-serif;
            font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.12em;
            color: rgba(245,241,234,0.65); text-decoration: none;
            padding: 8px 18px; border: 1px solid rgba(245,241,234,0.22);
            border-radius: 999px; transition: all 0.2s;
        }
        .welcome-btn-ghost:hover { border-color: rgba(245,241,234,0.55); color: #f5f1ea; }
        .welcome-btn-solid {
            font-family: 'Archivo Narrow', sans-serif;
            font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.12em;
            color: #f5f1ea; text-decoration: none;
            padding: 8px 20px;
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            border: 1px solid transparent; border-radius: 999px;
            transition: all 0.2s;
            box-shadow: 0 4px 18px rgba(168,85,247,0.45);
        }
        .welcome-btn-solid:hover { box-shadow: 0 6px 28px rgba(168,85,247,0.65); transform: translateY(-1px); }

        /* ── Chip CTA dentro del hero ── */
        .hero-cta-link {
            display: inline-flex; align-items: center; gap: 8px;
            font-family: 'Archivo Narrow', sans-serif;
            font-size: 12px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.14em;
            color: #f5f1ea; text-decoration: none;
            padding: 10px 24px;
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            border-radius: 999px;
            box-shadow: 0 8px 28px rgba(168,85,247,0.5);
            transition: all 0.2s;
        }
        .hero-cta-link:hover { box-shadow: 0 12px 36px rgba(168,85,247,0.7); transform: translateY(-2px); }

        /* ── Sección wrapper dark ── */
        .landing-seccion {
            background: #07060c;
            padding: clamp(3rem, 6vw, 5rem) 0;
        }
        .landing-seccion-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 clamp(1.5rem, 4vw, 2rem);
        }
        .landing-seccion-header {
            margin-bottom: 36px;
        }
        .landing-kicker {
            font-family: 'Archivo Narrow', sans-serif;
            font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.18em;
            color: #a855f7;
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 10px;
        }
        .landing-kicker::before {
            content: ''; width: 28px; height: 1px; background: #a855f7;
        }
        .landing-titulo {
            font-family: 'Anton', sans-serif;
            font-size: clamp(28px, 4vw, 52px);
            text-transform: uppercase; letter-spacing: 0.01em;
            color: #f5f1ea; margin: 0 0 6px;
        }
        .landing-subtitulo {
            font-family: 'Archivo Narrow', sans-serif;
            font-size: 14px; color: rgba(245,241,234,0.4);
            text-transform: uppercase; letter-spacing: 0.1em;
        }

        /* ── Carousel de eventos portrait ── */
        .carousel-vibez {
            overflow-x: auto; overflow-y: visible;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            cursor: grab;
        }
        .carousel-vibez:active { cursor: grabbing; }
        .carousel-vibez::-webkit-scrollbar { display: none; }
        .carousel-track {
            display: flex; gap: 18px;
            padding: 0 clamp(1.5rem, 4vw, 2rem) 12px;
            width: max-content;
        }

        /* Tarjeta portrait del carousel */
        .carousel-card {
            width: 260px; flex-shrink: 0;
            position: relative; cursor: pointer;
            background: #0d0a18;
            border: 1px solid rgba(245,241,234,0.08);
            transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
        }
        .carousel-card:hover {
            transform: translateY(-6px);
            border-color: rgba(168,85,247,0.35);
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        }
        .carousel-card-img-wrap {
            position: relative; height: 360px; overflow: hidden;
        }
        .carousel-card-img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }
        .carousel-card:hover .carousel-card-img { transform: scale(1.05); }
        .carousel-card-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(180deg, transparent 40%, rgba(7,6,12,0.96) 100%);
        }
        .carousel-card-num {
            position: absolute; top: 12px; left: 14px;
            font-family: 'Anton', sans-serif;
            font-size: 64px; line-height: 1;
            color: transparent;
            -webkit-text-stroke: 1.5px rgba(245,241,234,0.15);
            pointer-events: none;
        }
        .carousel-card-precio-badge {
            position: absolute; top: 12px; right: 12px;
            font-family: 'Archivo Narrow', sans-serif;
            font-size: 10px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.1em;
            padding: 4px 10px; border-radius: 999px;
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            color: white;
        }
        .carousel-card-precio-badge.gratis {
            background: linear-gradient(135deg, #059669, #10b981);
        }
        .carousel-card-info {
            position: absolute; bottom: 0; left: 0; right: 0;
            padding: 18px 14px 14px;
        }
        .carousel-card-cat {
            font-family: 'Archivo Narrow', sans-serif;
            font-size: 10px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.12em;
            color: #a855f7; margin-bottom: 5px;
        }
        .carousel-card-titulo {
            font-family: 'Anton', sans-serif;
            font-size: 18px; text-transform: uppercase;
            color: #f5f1ea; margin: 0 0 8px; line-height: 1.1;
        }
        .carousel-card-meta {
            font-family: 'Archivo Narrow', sans-serif;
            font-size: 11px; font-weight: 500;
            color: rgba(245,241,234,0.45);
            text-transform: uppercase; letter-spacing: 0.08em;
            display: flex; align-items: center; gap: 6px;
        }

        /* ── Sección mapa ── */
        .map-section {
            background: #07060c;
            padding: clamp(3rem, 6vw, 5rem) 0 0;
        }
        .map-section-header {
            padding: 0 clamp(1.5rem, 4vw, 2rem);
            max-width: 1280px; margin: 0 auto 28px;
        }
        #mapa-eventos {
            height: 520px; width: 100%;
            background: #0d0a18;
        }

        /* ── CTA final ── */
        .landing-cta {
            background: #07060c;
            padding: clamp(4rem, 8vw, 7rem) clamp(1.5rem, 4vw, 2rem);
            text-align: center;
            border-top: 1px solid rgba(245,241,234,0.06);
        }
        .landing-cta-titulo {
            font-family: 'Anton', sans-serif;
            font-size: clamp(40px, 7vw, 96px);
            text-transform: uppercase; line-height: 0.9;
            color: #f5f1ea; margin: 0 0 28px;
        }
        .landing-cta-titulo em {
            font-style: italic;
            color: #a855f7; font-family: 'Bebas Neue', sans-serif;
        }
        .landing-cta-sub {
            font-family: 'Archivo Narrow', sans-serif;
            font-size: 16px; color: rgba(245,241,234,0.5);
            max-width: 480px; margin: 0 auto 36px; line-height: 1.6;
        }
        .landing-cta-btns { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }
        .landing-cta-btn-primary {
            font-family: 'Anton', sans-serif;
            font-size: 17px; text-transform: uppercase; letter-spacing: 0.04em;
            color: #f5f1ea; text-decoration: none;
            padding: 16px 36px; border-radius: 999px;
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            box-shadow: 0 8px 32px rgba(168,85,247,0.5);
            transition: all 0.2s;
        }
        .landing-cta-btn-primary:hover { box-shadow: 0 14px 44px rgba(168,85,247,0.7); transform: translateY(-2px); }
        .landing-cta-btn-ghost {
            font-family: 'Archivo Narrow', sans-serif;
            font-size: 12px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.12em;
            color: rgba(245,241,234,0.7); text-decoration: none;
            padding: 16px 28px; border-radius: 999px;
            border: 1px solid rgba(245,241,234,0.2);
            transition: all 0.2s;
        }
        .landing-cta-btn-ghost:hover { border-color: rgba(245,241,234,0.5); color: #f5f1ea; }

        /* ── Footer ── */
        .landing-footer {
            background: #07060c;
            border-top: 1px solid rgba(245,241,234,0.06);
            padding: 20px clamp(1.5rem, 4vw, 2rem);
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 12px;
        }
        .landing-footer p {
            font-family: 'Archivo Narrow', sans-serif;
            font-size: 11px; color: rgba(245,241,234,0.3);
            text-transform: uppercase; letter-spacing: 0.1em;
        }

        /* ── Leaflet dark overrides ── */
        .leaflet-popup-content-wrapper {
            background: #0d0a18;
            border: 1px solid rgba(168,85,247,0.35);
            color: #f5f1ea;
            border-radius: 0;
            box-shadow: 0 8px 32px rgba(0,0,0,0.7);
        }
        .leaflet-popup-tip { background: #0d0a18; }
        .leaflet-popup-close-button { color: rgba(245,241,234,0.5) !important; }
        .leaflet-popup-close-button:hover { color: #f5f1ea !important; }
        .leaflet-container { background: #0d0a18; }
        .leaflet-control-attribution { background: rgba(13,10,24,0.8) !important; color: rgba(245,241,234,0.35) !important; }
        .leaflet-control-attribution a { color: rgba(168,85,247,0.7) !important; }
        .leaflet-control-zoom a {
            background: #0d0a18 !important; color: #f5f1ea !important;
            border-color: rgba(245,241,234,0.15) !important;
        }
        .leaflet-control-zoom a:hover { background: rgba(168,85,247,0.2) !important; }

        /* Marcador personalizado para el mapa */
        .mapa-marker {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg);
            border: 2px solid rgba(245,241,234,0.8);
            box-shadow: 0 4px 16px rgba(168,85,247,0.6);
        }
        .mapa-marker-inner {
            width: 100%; height: 100%;
            transform: rotate(45deg);
            display: flex; align-items: center; justify-content: center;
        }
    </style>
</head>
<body style="background:#07060c;color:#f5f1ea;margin:0;overflow-x:hidden;">


<nav class="welcome-nav" aria-label="Navegación principal">
    <a href="<?php echo e(route('welcome')); ?>" class="welcome-nav-logo">
        <img src="<?php echo e(asset('images/logo_vibez_white.png')); ?>" alt="VIBEZ">
    </a>
    <div class="welcome-nav-actions">
        <?php if(auth()->guard()->check()): ?>
            <a href="<?php echo e(Auth::user()->isEmpresa() ? route('empresa.home') : route('home')); ?>" class="welcome-btn-solid">
                Ir a mi cuenta
            </a>
        <?php else: ?>
            <a href="<?php echo e(route('login')); ?>" class="welcome-btn-ghost">Entrar</a>
            <a href="<?php echo e(route('register')); ?>" class="welcome-btn-solid">Regístrate gratis</a>
        <?php endif; ?>
    </div>
</nav>


<div class="hero-poster" style="min-height:100vh;">

    
    <?php if($eventos->isNotEmpty() && $eventos->first()->url_portada): ?>
        <img src="<?php echo e($eventos->first()->url_portada); ?>" alt="" class="hero-poster-img" aria-hidden="true">
    <?php else: ?>
        <img src="https://picsum.photos/seed/vibez-welcome/1600/900" alt="" class="hero-poster-img" aria-hidden="true">
    <?php endif; ?>
    <div class="hero-poster-overlay"></div>

    
    <div class="hero-orb hero-orb-1" aria-hidden="true"></div>
    <div class="hero-orb hero-orb-2" aria-hidden="true"></div>

    
    <div class="hero-poster-numbers" aria-hidden="true">
        <div class="hero-poster-numbers-inner"><?php echo e(now()->format('d')); ?><br><?php echo e(now()->format('m')); ?></div>
    </div>

    
    <div class="hero-poster-content">
        <p class="hero-kicker">
            <span class="hero-kicker-line"></span>
            La plataforma de la escena joven
        </p>

        <h1 class="hero-titulo-vibez">
            Tu próxima
            <span class="acento">aventura empieza aquí</span>
        </h1>

        <p class="hero-subtitulo-vibez">
            Eventos, conciertos, festivales y trabajo —<br>
            todo lo que vive tu escena, en un solo lugar.
        </p>

        
        <div class="hero-stats" style="flex-wrap:wrap;gap:12px;margin-bottom:28px;">
            <span class="hero-stat-pill">
                <span class="hero-stat-dot"></span>
                <?php echo e($eventos->count()); ?> eventos activos
            </span>
            <?php if($categorias->count()): ?>
                <span class="hero-stat-pill">
                    <span class="hero-stat-dot"></span>
                    <?php echo e($categorias->count()); ?> categorías
                </span>
            <?php endif; ?>
        </div>

        <a href="<?php echo e(route('register')); ?>" class="hero-cta-link">
            Únete gratis
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M5 12h14M12 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

</div>


<div class="marquee-vibez" aria-hidden="true">
    <div class="marquee-track">
        <span class="marquee-item"> Música</span><span class="marquee-dot"></span>
        <span class="marquee-item"> Cultura</span><span class="marquee-dot"></span>
        <span class="marquee-item"> Techno</span><span class="marquee-dot"></span>
        <span class="marquee-item"> Deporte</span><span class="marquee-dot"></span>
        <span class="marquee-item"> Gastronomía</span><span class="marquee-dot"></span>
        <span class="marquee-item"> Networking</span><span class="marquee-dot"></span>
        <span class="marquee-item"> Moda</span><span class="marquee-dot"></span>
        <span class="marquee-item"> Tecnología</span><span class="marquee-dot"></span>
        <span class="marquee-item"> Festivales</span><span class="marquee-dot"></span>
        <span class="marquee-item"> Arte</span><span class="marquee-dot"></span>
        
        <span class="marquee-item"> Música</span><span class="marquee-dot"></span>
        <span class="marquee-item"> Cultura</span><span class="marquee-dot"></span>
        <span class="marquee-item"> Techno</span><span class="marquee-dot"></span>
        <span class="marquee-item"> Deporte</span><span class="marquee-dot"></span>
        <span class="marquee-item"> Gastronomía</span><span class="marquee-dot"></span>
        <span class="marquee-item"> Networking</span><span class="marquee-dot"></span>
        <span class="marquee-item"> Moda</span><span class="marquee-dot"></span>
        <span class="marquee-item"> Tecnología</span><span class="marquee-dot"></span>
        <span class="marquee-item"> Festivales</span><span class="marquee-dot"></span>
        <span class="marquee-item"> Arte</span><span class="marquee-dot"></span>
    </div>
</div>


<?php if($eventos->isNotEmpty()): ?>
<section class="landing-seccion">
    <div class="landing-seccion-header landing-seccion-inner">
        <p class="landing-kicker">Próximos eventos</p>
        <h2 class="landing-titulo">Esto es lo que se mueve</h2>
        <p class="landing-subtitulo">
            <?php echo e($eventos->count()); ?> evento<?php echo e($eventos->count() !== 1 ? 's' : ''); ?> disponible<?php echo e($eventos->count() !== 1 ? 's' : ''); ?>

        </p>
    </div>

    <div class="carousel-vibez" id="carousel-eventos">
        <div class="carousel-track">
            <?php $__currentLoopData = $eventos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $evento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <article
                    class="carousel-card"
                    onclick="window.location.href='<?php echo e(route('eventos.detalle', $evento->id)); ?>'"
                    title="<?php echo e($evento->titulo); ?>"
                >
                    <div class="carousel-card-img-wrap">
                        <img
                            src="<?php echo e($evento->url_portada); ?>"
                            alt="<?php echo e($evento->titulo); ?>"
                            class="carousel-card-img"
                            onerror="this.src='https://picsum.photos/seed/ev-<?php echo e($evento->id); ?>/400/600'"
                        >
                        <div class="carousel-card-overlay"></div>

                        
                        <span class="carousel-card-num"><?php echo e(str_pad($index + 1, 2, '0', STR_PAD_LEFT)); ?></span>

                        
                        <span class="carousel-card-precio-badge <?php echo e($evento->es_gratuito ? 'gratis' : ''); ?>">
                            <?php echo e($evento->precio_formateado); ?>

                        </span>

                        
                        <div class="carousel-card-info">
                            <p class="carousel-card-cat"><?php echo e($evento->categoria?->nombre ?? 'Evento'); ?></p>
                            <h3 class="carousel-card-titulo"><?php echo e($evento->titulo); ?></h3>
                            <div class="carousel-card-meta">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <?php echo e($evento->fecha_inicio->locale('es')->isoFormat('D MMM YYYY')); ?>

                                <?php if($evento->ubicacion_nombre): ?>
                                    &nbsp;·&nbsp;
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    <?php echo e($evento->ubicacion_nombre); ?>

                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>

=======
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Bebas+Neue&family=Archivo:wght@400;500;600;700;800;900&family=Archivo+Narrow:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    
    <link rel="stylesheet" href="<?php echo e(asset('css/vibez-welcome.css')); ?>">
</head>
<body>


<div class="amb amb-1" aria-hidden="true"></div>
<div class="amb amb-2" aria-hidden="true"></div>
<div class="amb amb-3" aria-hidden="true"></div>


<header class="nav">
    <a href="<?php echo e(route('welcome')); ?>" class="logo" aria-label="VIBEZ — Inicio">
        <img src="<?php echo e(asset('images/logo_vibez_white.png')); ?>" alt="VIBEZ">
        <span>VIBEZ</span>
    </a>

    <nav class="nav-links" aria-label="Navegación principal">
        <a href="#features">Cómo funciona</a>
        <a href="#showcase">Eventos</a>
        <a href="<?php echo e(route('trabajos.index')); ?>">Bolsa de trabajo</a>
    </nav>

    <div class="nav-cta">
        <?php if(auth()->guard()->check()): ?>
            <a href="<?php echo e(Auth::user()->isEmpresa() ? route('empresa.home') : route('home')); ?>"
               class="btn-pri">Mi cuenta</a>
        <?php else: ?>
            <a href="<?php echo e(route('login')); ?>" class="btn-ghost">Entrar</a>
            <a href="<?php echo e(route('register')); ?>" class="btn-pri">Crear cuenta</a>
        <?php endif; ?>
        <button class="burger"
                onclick="document.getElementById('menu-movil').classList.add('open')"
                aria-label="Abrir menú" aria-expanded="false">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="6" x2="21" y2="6"/>
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>
    </div>
</header>


<div class="mobile-menu" id="menu-movil" role="dialog" aria-modal="true" aria-label="Menú de navegación">
    <button class="close"
            onclick="document.getElementById('menu-movil').classList.remove('open')"
            aria-label="Cerrar menú">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"/>
            <line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
    </button>
    <a href="#features" onclick="document.getElementById('menu-movil').classList.remove('open')">Cómo funciona</a>
    <a href="#showcase" onclick="document.getElementById('menu-movil').classList.remove('open')">Eventos</a>
    <a href="<?php echo e(route('trabajos.index')); ?>" onclick="document.getElementById('menu-movil').classList.remove('open')">Bolsa de trabajo</a>
    <div class="actions">
        <?php if(auth()->guard()->check()): ?>
            <a href="<?php echo e(Auth::user()->isEmpresa() ? route('empresa.home') : route('home')); ?>"
               class="btn-pri">Mi cuenta</a>
        <?php else: ?>
            <a href="<?php echo e(route('login')); ?>" class="btn-ghost">Entrar</a>
            <a href="<?php echo e(route('register')); ?>" class="btn-pri">Crear cuenta</a>
        <?php endif; ?>
    </div>
</div>


<section class="hero">

    
    <div>
        <div class="mono hero-kicker">▸ Edición #428 · <?php echo e(now()->locale('es')->isoFormat('MMMM YYYY')); ?></div>
        <h1>Esta noche<br><em>se rompe</em>.</h1>
        <p class="hero-sub">
            Eventos, fiestas, conciertos y festivales — todo lo que vive tu escena en un solo sitio.
            Compra entradas, descubre tu próxima noche y entra a la lista VIP.
        </p>
        <div class="hero-actions">
            <a href="<?php echo e(route('register')); ?>" class="btn-pri big">
                Crear cuenta
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <line x1="5" y1="12" x2="19" y2="12"/>
                    <polyline points="12 5 19 12 12 19"/>
                </svg>
            </a>
            <a href="<?php echo e(route('login')); ?>" class="btn-ghost">Ya tengo cuenta</a>
            <div class="hero-stat">
                <div class="num"><?php echo e($eventos->count() > 0 ? $eventos->count() : '12k'); ?>+</div>
                <div>eventos<br>disponibles</div>
            </div>
        </div>
    </div>

    
    <div class="hero-vis" aria-hidden="true">
        <div class="hero-vis-sticker">★ NEW · <?php echo e(date('Y')); ?></div>

        
        <div class="hero-card hero-card-1">
            <?php if($eventos->count() > 0): ?>
                <img src="<?php echo e($eventos[0]->url_portada ?? 'https://picsum.photos/seed/welcome-1/600/760'); ?>"
                     alt="<?php echo e($eventos[0]->titulo); ?>"
                     onerror="this.src='https://picsum.photos/seed/welcome-1/600/760'">
                <div class="hero-card-overlay"><span><?php echo e($eventos[0]->titulo); ?></span></div>
            <?php else: ?>
                <img src="https://picsum.photos/seed/welcome-1/600/760" alt="">
                <div class="hero-card-overlay"><span>Charlotte de Witte</span></div>
            <?php endif; ?>
        </div>

        
        <div class="hero-card hero-card-2">
            <?php if($eventos->count() > 1): ?>
                <img src="<?php echo e($eventos[1]->url_portada ?? 'https://picsum.photos/seed/welcome-2/500/620'); ?>"
                     alt="<?php echo e($eventos[1]->titulo); ?>"
                     onerror="this.src='https://picsum.photos/seed/welcome-2/500/620'">
                <div class="hero-card-overlay"><span><?php echo e($eventos[1]->titulo); ?></span></div>
            <?php else: ?>
                <img src="https://picsum.photos/seed/welcome-2/500/620" alt="">
                <div class="hero-card-overlay"><span>Primavera Sound</span></div>
            <?php endif; ?>
        </div>

        
        <div class="hero-card hero-card-3">
            <?php if($eventos->count() > 2): ?>
                <img src="<?php echo e($eventos[2]->url_portada ?? 'https://picsum.photos/seed/welcome-3/360/440'); ?>"
                     alt="<?php echo e($eventos[2]->titulo); ?>"
                     onerror="this.src='https://picsum.photos/seed/welcome-3/360/440'">
                <div class="hero-card-overlay"><span><?php echo e($eventos[2]->titulo); ?></span></div>
            <?php else: ?>
                <img src="https://picsum.photos/seed/welcome-3/360/440" alt="">
                <div class="hero-card-overlay"><span>Pacha · BCN</span></div>
            <?php endif; ?>
        </div>
    </div>

</section>


<div class="marquee" aria-hidden="true">
    <div class="marquee-track">
        <span>Esta noche se rompe<span class="star">✦</span></span>
        <span class="alt">BCN never sleeps<span class="star">✦</span></span>
        <span>Sound system on<span class="star">✦</span></span>
        <span class="alt">All night long<span class="star">✦</span></span>
        <span>Esta noche se rompe<span class="star">✦</span></span>
        <span class="alt">BCN never sleeps<span class="star">✦</span></span>
        <span>Sound system on<span class="star">✦</span></span>
        <span class="alt">All night long<span class="star">✦</span></span>
    </div>
</div>


<section class="features" id="features">
    <div class="section-head">
        <div class="mono">▸ Cómo funciona</div>
        <h2>Tu pase a la <em>noche</em></h2>
    </div>
    <div class="features-grid">
        <div class="feat-card">
            <div class="feat-num">01</div>
            <h3>Descubre</h3>
            <p>Más de 1.200 eventos por mes en BCN, Madrid, Valencia, Lisboa e Ibiza. Filtra por mood, género y aforo en tiempo real.</p>
        </div>
        <div class="feat-card">
            <div class="feat-num">02</div>
            <h3>Compra rápido</h3>
            <p>QR digital al instante. Sin colas, sin imprimir. Pago con tarjeta, Apple Pay y Bizum. Devolución hasta 24h antes.</p>
        </div>
        <div class="feat-card">
            <div class="feat-num">03</div>
            <h3>Pre-venta VIP</h3>
            <p>48h de acceso anticipado a sold-outs. Cupones exclusivos. Eventos solo para miembros y after-parties privadas.</p>
        </div>
    </div>
</section>


<section class="showcase" id="showcase">
    <div class="section-head">
        <div class="mono">▸ Esta semana</div>
        <h2>Lo que <em>todos</em> escuchan</h2>
    </div>
    <div class="show-grid">
        <?php $__empty_1 = true; $__currentLoopData = $eventos->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <a href="<?php echo e(route('eventos.detalle', $evento->id)); ?>" class="show-card">
                <img
                    src="<?php echo e($evento->url_portada); ?>"
                    alt="<?php echo e($evento->titulo); ?>"
                    onerror="this.src='https://picsum.photos/seed/show-<?php echo e($evento->id); ?>/400/520'"
                >
                <div class="show-info">
                    <div class="city">
                        <?php echo e($evento->ubicacion_nombre ?? 'BCN'); ?> · <?php echo e($evento->fecha_inicio->locale('es')->isoFormat('D MMM')); ?>

                    </div>
                    <div class="name"><?php echo e($evento->titulo); ?></div>
                </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            
            <a href="<?php echo e(route('register')); ?>" class="show-card">
                <img src="https://picsum.photos/seed/show-1/400/520" alt="">
                <div class="show-info"><div class="city">BCN · 09 May</div><div class="name">Charlotte de Witte</div></div>
            </a>
            <a href="<?php echo e(route('register')); ?>" class="show-card">
                <img src="https://picsum.photos/seed/show-2/400/520" alt="">
                <div class="show-info"><div class="city">BCN · 28 May</div><div class="name">Primavera Sound</div></div>
            </a>
            <a href="<?php echo e(route('register')); ?>" class="show-card">
                <img src="https://picsum.photos/seed/show-3/400/520" alt="">
                <div class="show-info"><div class="city">IBZ · 15 Jun</div><div class="name">Bad Bunny</div></div>
            </a>
            <a href="<?php echo e(route('register')); ?>" class="show-card">
                <img src="https://picsum.photos/seed/show-4/400/520" alt="">
                <div class="show-info"><div class="city">BCN · 22 May</div><div class="name">Dixon · Apolo</div></div>
            </a>
        <?php endif; ?>
    </div>
</section>


<section class="proof">
    <div class="proof-text">
        <div class="mono" style="color:var(--morado-3);font-size:11px;margin-bottom:14px;display:inline-block">▸ Únete a la lista</div>
        <h2>La <em>escena</em> ya está dentro.</h2>
        <p>Más de 12.000 ravers, 42 promotores y 1.200 eventos al mes. VIBEZ es la plataforma que mueve la noche de Iberia.</p>
        <a href="<?php echo e(route('register')); ?>" class="btn-pri big">
            Quiero entrar
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <line x1="5" y1="12" x2="19" y2="12"/>
                <polyline points="12 5 19 12 12 19"/>
            </svg>
        </a>
    </div>
    <div class="proof-stats">
        <div class="stat-card">
            <div class="stat-num">12k+</div>
            <div class="stat-lbl">Ravers activos</div>
        </div>
        <div class="stat-card">
            <div class="stat-num">1.2k</div>
            <div class="stat-lbl">Eventos / mes</div>
        </div>
        <div class="stat-card">
            <div class="stat-num">42</div>
            <div class="stat-lbl">Promotores</div>
        </div>
        <div class="stat-card">
            <div class="stat-num">98%</div>
            <div class="stat-lbl">Devolución 24h</div>
        </div>
    </div>
</section>


<section class="final">
    <h2>La noche<br>te <em>espera</em>.</h2>
    <p>Crea tu pase VIBEZ en 30 segundos. Sin spam, sin compromisos. Solo música.</p>
    <div class="final-actions">
        <a href="<?php echo e(route('register')); ?>" class="btn-pri big">
            Crear mi pase
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <line x1="5" y1="12" x2="19" y2="12"/>
                <polyline points="12 5 19 12 12 19"/>
            </svg>
        </a>
        <a href="<?php echo e(route('login')); ?>" class="btn-ghost">Ya tengo cuenta</a>
    </div>
</section>


<footer>
    <div class="foot-row">
        <div>
            <a href="<?php echo e(route('welcome')); ?>" class="logo" style="display:inline-flex;margin-bottom:14px;">
                <img src="<?php echo e(asset('images/logo_vibez_white.png')); ?>" alt="VIBEZ">
                <span>VIBEZ</span>
            </a>
            <p style="color:var(--ink-dim);font-size:13px;line-height:1.5;max-width:320px;">
                La plataforma de la escena. Eventos, conciertos, festivales y bolsa de trabajo en BCN, Madrid, Valencia, Ibiza y Lisboa.
            </p>
        </div>
        <div>
            <h4>Plataforma</h4>
            <a href="<?php echo e(route('home')); ?>">Explorar</a>
            <a href="<?php echo e(route('trabajos.index')); ?>">Bolsa de trabajo</a>
            <a href="<?php echo e(route('register')); ?>">Crear cuenta</a>
            <a href="<?php echo e(route('login')); ?>">Entrar</a>
        </div>
        <div>
            <h4>Empresa</h4>
            <a href="#">Sobre VIBEZ</a>
            <a href="#">Prensa</a>
            <a href="#">Contacto</a>
        </div>
        <div>
            <h4>Legal</h4>
            <a href="#">Términos</a>
            <a href="#">Privacidad</a>
            <a href="#">Cookies</a>
            <a href="#">Devoluciones</a>
        </div>
    </div>
    <div class="foot-bottom">
        <span>© <?php echo e(date('Y')); ?> VIBEZ · BCN</span>
        <span>Edición 428 · Made for ravers</span>
    </div>
</footer>
>>>>>>> feature/middleware

<section class="landing-cta">
    <h2 class="landing-cta-titulo">
        ¿A qué<br><em>esperas?</em>
    </h2>
    <p class="landing-cta-sub">
        Únete a miles de jóvenes que ya viven su escena con VIBEZ.
        Registro gratuito en menos de 2 minutos.
    </p>
    <div class="landing-cta-btns">
        <a href="<?php echo e(route('register')); ?>" class="landing-cta-btn-primary">Crear cuenta gratis</a>
        <a href="<?php echo e(route('login')); ?>" class="landing-cta-btn-ghost">Ya tengo cuenta</a>
    </div>
</section>


<footer class="landing-footer">
    <img src="<?php echo e(asset('images/logo_vibez_white.png')); ?>" alt="VIBEZ" style="height:28px;opacity:0.6;">
    <p>&copy; <?php echo e(date('Y')); ?> VIBEZ — Plataforma de eventos para jóvenes</p>
    <div style="display:flex;gap:20px;">
        <a href="#" style="font-family:'Archivo Narrow',sans-serif;font-size:11px;color:rgba(245,241,234,0.3);text-decoration:none;text-transform:uppercase;letter-spacing:0.1em;">Privacidad</a>
        <a href="#" style="font-family:'Archivo Narrow',sans-serif;font-size:11px;color:rgba(245,241,234,0.3);text-decoration:none;text-transform:uppercase;letter-spacing:0.1em;">Contacto</a>
    </div>
</footer>
</body>
</html>
<?php /**PATH C:\wamp64\www\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/welcome.blade.php ENDPATH**/ ?>
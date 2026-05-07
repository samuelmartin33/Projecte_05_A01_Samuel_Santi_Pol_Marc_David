<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VIBEZ — Descubre tu próximo evento</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_vibez.png') }}">

    {{-- Fuentes editoriales VIBEZ --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Bebas+Neue&family=Archivo:wght@400;500;600;700;800;900&family=Archivo+Narrow:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind (mismo que el resto del proyecto) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">

    {{-- Estilos compartidos del proyecto --}}
    <link rel="stylesheet" href="{{ asset('css/app-static.css') }}">

    {{-- Estilos del tema oscuro (home-vibez.css) --}}
    <link rel="stylesheet" href="{{ asset('css/home-vibez.css') }}">

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

{{-- ════════════════════════════════════════════════════
     NAVBAR — fija, transparente
════════════════════════════════════════════════════ --}}
<nav class="welcome-nav" aria-label="Navegación principal">
    <a href="{{ route('welcome') }}" class="welcome-nav-logo">
        <img src="{{ asset('images/logo_vibez_white.png') }}" alt="VIBEZ">
    </a>
    <div class="welcome-nav-actions">
        @auth
            <a href="{{ Auth::user()->isEmpresa() ? route('empresa.home') : route('home') }}" class="welcome-btn-solid">
                Ir a mi cuenta
            </a>
        @else
            <a href="{{ route('login') }}" class="welcome-btn-ghost">Entrar</a>
            <a href="{{ route('register') }}" class="welcome-btn-solid">Regístrate gratis</a>
        @endauth
    </div>
</nav>

{{-- ════════════════════════════════════════════════════
     HERO POSTER — imagen editorial full-bleed
════════════════════════════════════════════════════ --}}
<div class="hero-poster" style="min-height:100vh;">

    {{-- Imagen del primer evento como portada --}}
    @if ($eventos->isNotEmpty() && $eventos->first()->url_portada)
        <img src="{{ $eventos->first()->url_portada }}" alt="" class="hero-poster-img" aria-hidden="true">
    @else
        <img src="https://picsum.photos/seed/vibez-welcome/1600/900" alt="" class="hero-poster-img" aria-hidden="true">
    @endif
    <div class="hero-poster-overlay"></div>

    {{-- Orbs animados --}}
    <div class="hero-orb hero-orb-1" aria-hidden="true"></div>
    <div class="hero-orb hero-orb-2" aria-hidden="true"></div>

    {{-- Números editoriales --}}
    <div class="hero-poster-numbers" aria-hidden="true">
        <div class="hero-poster-numbers-inner">{{ now()->format('d') }}<br>{{ now()->format('m') }}</div>
    </div>

    {{-- Contenido editorial --}}
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

        {{-- Stats + CTA --}}
        <div class="hero-stats" style="flex-wrap:wrap;gap:12px;margin-bottom:28px;">
            <span class="hero-stat-pill">
                <span class="hero-stat-dot"></span>
                {{ $eventos->count() }} eventos activos
            </span>
            @if ($categorias->count())
                <span class="hero-stat-pill">
                    <span class="hero-stat-dot"></span>
                    {{ $categorias->count() }} categorías
                </span>
            @endif
        </div>

        <a href="{{ route('register') }}" class="hero-cta-link">
            Únete gratis
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M5 12h14M12 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

</div>

{{-- ════════════════════════════════════════════════════
     MARQUEE — banda animada de categorías
════════════════════════════════════════════════════ --}}
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
        {{-- Segunda copia para el loop infinito --}}
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

{{-- ════════════════════════════════════════════════════
     CAROUSEL — eventos en tarjetas portrait con números
════════════════════════════════════════════════════ --}}
@if ($eventos->isNotEmpty())
<section class="landing-seccion">
    <div class="landing-seccion-header landing-seccion-inner">
        <p class="landing-kicker">Próximos eventos</p>
        <h2 class="landing-titulo">Esto es lo que se mueve</h2>
        <p class="landing-subtitulo">
            {{ $eventos->count() }} evento{{ $eventos->count() !== 1 ? 's' : '' }} disponible{{ $eventos->count() !== 1 ? 's' : '' }}
        </p>
    </div>

    <div class="carousel-vibez" id="carousel-eventos">
        <div class="carousel-track">
            @foreach ($eventos as $index => $evento)
                <article
                    class="carousel-card"
                    onclick="window.location.href='{{ route('eventos.detalle', $evento->id) }}'"
                    title="{{ $evento->titulo }}"
                >
                    <div class="carousel-card-img-wrap">
                        <img
                            src="{{ $evento->url_portada }}"
                            alt="{{ $evento->titulo }}"
                            class="carousel-card-img"
                            onerror="this.src='https://picsum.photos/seed/ev-{{ $evento->id }}/400/600'"
                        >
                        <div class="carousel-card-overlay"></div>

                        {{-- Número editorial del carousel --}}
                        <span class="carousel-card-num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>

                        {{-- Badge de precio --}}
                        <span class="carousel-card-precio-badge {{ $evento->es_gratuito ? 'gratis' : '' }}">
                            {{ $evento->precio_formateado }}
                        </span>

                        {{-- Info sobre la imagen --}}
                        <div class="carousel-card-info">
                            <p class="carousel-card-cat">{{ $evento->categoria?->nombre ?? 'Evento' }}</p>
                            <h3 class="carousel-card-titulo">{{ $evento->titulo }}</h3>
                            <div class="carousel-card-meta">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $evento->fecha_inicio->locale('es')->isoFormat('D MMM YYYY') }}
                                @if ($evento->ubicacion_nombre)
                                    &nbsp;·&nbsp;
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    {{ $evento->ubicacion_nombre }}
                                @endif
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ════════════════════════════════════════════════════
     CTA FINAL — llamada a la acción
════════════════════════════════════════════════════ --}}
<section class="landing-cta">
    <h2 class="landing-cta-titulo">
        ¿A qué<br><em>esperas?</em>
    </h2>
    <p class="landing-cta-sub">
        Únete a miles de jóvenes que ya viven su escena con VIBEZ.
        Registro gratuito en menos de 2 minutos.
    </p>
    <div class="landing-cta-btns">
        <a href="{{ route('register') }}" class="landing-cta-btn-primary">Crear cuenta gratis</a>
        <a href="{{ route('login') }}" class="landing-cta-btn-ghost">Ya tengo cuenta</a>
    </div>
</section>

{{-- ════════════════════════════════════════════════════
     FOOTER
════════════════════════════════════════════════════ --}}
<footer class="landing-footer">
    <img src="{{ asset('images/logo_vibez_white.png') }}" alt="VIBEZ" style="height:28px;opacity:0.6;">
    <p>&copy; {{ date('Y') }} VIBEZ — Plataforma de eventos para jóvenes</p>
    <div style="display:flex;gap:20px;">
        <a href="#" style="font-family:'Archivo Narrow',sans-serif;font-size:11px;color:rgba(245,241,234,0.3);text-decoration:none;text-transform:uppercase;letter-spacing:0.1em;">Privacidad</a>
        <a href="#" style="font-family:'Archivo Narrow',sans-serif;font-size:11px;color:rgba(245,241,234,0.3);text-decoration:none;text-transform:uppercase;letter-spacing:0.1em;">Contacto</a>
    </div>
</footer>

{{-- Leaflet JS --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV/XN/WLs=" crossorigin=""></script>

<script>
/* ════════════════════════════════════════════════════
   MAPA LEAFLET — Inicialización con dark tiles
   ════════════════════════════════════════════════════ */
(function inicializarMapa() {
    var contenedor = document.getElementById('mapa-eventos');
    if (!contenedor) return;

    var eventosMapa = @json($eventosMapa);
    if (!eventosMapa.length) return;

    /* Calcular centro del mapa usando el promedio de coordenadas */
    var sumLat = 0, sumLng = 0;
    for (var i = 0; i < eventosMapa.length; i++) {
        sumLat += eventosMapa[i].lat;
        sumLng += eventosMapa[i].lng;
    }
    var centroLat = sumLat / eventosMapa.length;
    var centroLng = sumLng / eventosMapa.length;

    /* Crear mapa con tiles oscuros de CartoDB */
    var mapa = L.map('mapa-eventos', {
        center: [centroLat, centroLng],
        zoom: 6,
        zoomControl: true,
        attributionControl: true,
    });

    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> · <a href="https://carto.com">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 19,
    }).addTo(mapa);

    /* Icono personalizado con el color de VIBEZ */
    var iconoVibez = L.divIcon({
        className: '',
        html: '<div style="width:14px;height:14px;background:linear-gradient(135deg,#7c3aed,#a855f7);border-radius:50%;border:2px solid rgba(245,241,234,0.9);box-shadow:0 0 16px rgba(168,85,247,0.8),0 0 4px rgba(168,85,247,0.4);"></div>',
        iconSize:   [14, 14],
        iconAnchor: [7, 7],
        popupAnchor: [0, -12],
    });

    /* Añadir un marcador por cada evento */
    for (var j = 0; j < eventosMapa.length; j++) {
        var ev = eventosMapa[j];
        var popupHtml =
            '<div style="font-family:\'Archivo\',sans-serif;">' +
            '<p style="font-family:\'Archivo Narrow\',sans-serif;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.12em;color:#a855f7;margin:0 0 4px;">' + ev.categoria + '</p>' +
            '<p style="font-size:14px;font-weight:700;color:#f5f1ea;margin:0 0 4px;">' + ev.titulo + '</p>' +
            '<p style="font-size:11px;color:rgba(245,241,234,0.5);margin:0 0 10px;">' + ev.fecha + (ev.precio ? ' · ' + ev.precio : '') + '</p>' +
            '<a href="' + ev.url + '" style="font-family:\'Archivo Narrow\',sans-serif;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:#a855f7;text-decoration:none;">Ver evento →</a>' +
            '</div>';

        L.marker([ev.lat, ev.lng], { icon: iconoVibez })
            .addTo(mapa)
            .bindPopup(popupHtml, { maxWidth: 240, minWidth: 180 });
    }

    /* Ajustar zoom para que quepan todos los marcadores */
    if (eventosMapa.length > 1) {
        var bounds = L.latLngBounds(eventosMapa.map(function(e) { return [e.lat, e.lng]; }));
        mapa.fitBounds(bounds, { padding: [48, 48] });
    }
})();

/* ════════════════════════════════════════════════════
   CAROUSEL — drag to scroll (sin EventListeners globales)
   ════════════════════════════════════════════════════ */
(function initCarousel() {
    var carousel = document.getElementById('carousel-eventos');
    if (!carousel) return;

    var arrastrando = false;
    var inicioX = 0;
    var scrollX = 0;

    carousel.onmousedown = function(e) {
        arrastrando = true;
        inicioX = e.pageX - carousel.offsetLeft;
        scrollX = carousel.scrollLeft;
        carousel.style.cursor = 'grabbing';
    };
    carousel.onmouseleave = function() {
        arrastrando = false;
        carousel.style.cursor = 'grab';
    };
    carousel.onmouseup = function() {
        arrastrando = false;
        carousel.style.cursor = 'grab';
    };
    carousel.onmousemove = function(e) {
        if (!arrastrando) return;
        e.preventDefault();
        var x = e.pageX - carousel.offsetLeft;
        carousel.scrollLeft = scrollX - (x - inicioX);
    };
})();
</script>

</body>
</html>

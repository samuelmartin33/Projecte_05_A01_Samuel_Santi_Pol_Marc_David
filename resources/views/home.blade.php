@extends('layouts.app')

@section('titulo', 'Explorar Eventos')

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/home-vibez.css') }}">
@endpush

@section('contenido')

{{-- ════════════════════════════════════════════════════
     HERO POSTER — imagen de portada editorial + branding
════════════════════════════════════════════════════ --}}
<div class="hero-poster">

    {{-- Imagen de portada del primer evento (con fallback) --}}
    @if ($eventos->isNotEmpty() && $eventos->first()->url_portada)
        <img
            src="{{ $eventos->first()->url_portada }}"
            alt=""
            class="hero-poster-img"
            aria-hidden="true"
        >
    @else
        <img
            src="https://picsum.photos/seed/vibez-hero/1600/900"
            alt=""
            class="hero-poster-img"
            aria-hidden="true"
        >
    @endif

    <div class="hero-poster-overlay"></div>

    {{-- Orbs de luz animados sobre la imagen --}}
    <div class="hero-orb hero-orb-1" aria-hidden="true"></div>
    <div class="hero-orb hero-orb-2" aria-hidden="true"></div>

    {{-- Números editoriales decorativos --}}
    <div class="hero-poster-numbers" aria-hidden="true">
        <div class="hero-poster-numbers-inner">
            {{ now()->format('d') }}<br>{{ now()->format('m') }}
        </div>
    </div>

    {{-- Contenido del hero --}}
    <div class="hero-poster-content">

        <p class="hero-kicker">
            <span class="hero-kicker-line"></span>
            La plataforma de la escena joven
        </p>

        {{-- Título con tipografía Bebas Neue --}}
        <h1 class="hero-titulo-vibez">
            Tu próxima
            <span class="acento">aventura empieza aquí</span>
        </h1>

        <p class="hero-subtitulo-vibez">
            Eventos, conciertos, festivales y trabajo —
            todo lo que vive tu escena, en un solo lugar.
        </p>

        {{-- Stats rápidas --}}
        <div class="hero-stats">
            <span class="hero-stat-pill">
                <span class="hero-stat-dot"></span>
                {{ $eventos->count() }} eventos disponibles
            </span>
            @if($ubicaciones->count() > 0)
                <span class="hero-stat-pill">
                    <span class="hero-stat-dot"></span>
                    {{ $ubicaciones->count() }} ciudades
                </span>
            @endif
        </div>

    </div>
</div>

{{-- ════════════════════════════════════════════════════
     MARQUEE — Banda animada con categorías y vibes
     CSS-only, sin JavaScript
════════════════════════════════════════════════════ --}}
<div class="marquee-vibez" aria-hidden="true">
    {{-- Duplicamos la pista para que el bucle sea infinito sin salto --}}
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
     BARRA DE FILTROS — sticky bajo el nav
     Mantiene las clases CSS/JS funcionales
════════════════════════════════════════════════════ --}}
<section class="barra-filtros sticky top-14 z-40">

    <div id="overlay-dropdowns"
         style="display:none;position:fixed;inset:0;z-index:200;"
         onclick="cerrarTodosDropdowns()"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex flex-wrap items-end gap-3">

        <p class="text-sm font-semibold mr-auto self-center"
           style="color:rgba(245,241,234,0.45)">
            <span id="contador-resultados">{{ $eventos->count() }}</span>
            <span style="color:rgba(168,85,247,0.85)"> resultados</span>
        </p>

        <div class="filtro-grupo" style="position:relative;z-index:250;">
            <label class="filtro-label">Categoría</label>
            <div class="custom-select-wrapper" id="wrapper-categoria" onclick="toggleDropdown('categoria')">
                <span id="categoria-display" class="custom-select-display">Todas</span>
                <svg class="custom-select-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M6 9l6 6 6-6"/>
                </svg>
                <div id="categoria-dropdown" class="custom-select-dropdown" style="display:none">
                    <div class="custom-select-option seleccionado" onclick="seleccionarFiltro('categoria','','Todas',event)">Todas</div>
                    @foreach ($categorias as $categoria)
                        <div class="custom-select-option" onclick="seleccionarFiltro('categoria','{{ $categoria->id }}','{{ $categoria->nombre }}',event)">
                            {{ $categoria->nombre }}
                        </div>
                    @endforeach
                </div>
            </div>
            <input type="hidden" id="filtro-categoria" value="">
        </div>

        <div class="filtro-grupo" style="position:relative;z-index:240;">
            <label class="filtro-label">Ubicación</label>
            <div class="custom-select-wrapper" id="wrapper-ubicacion" onclick="toggleDropdown('ubicacion')">
                <span id="ubicacion-display" class="custom-select-display">Todas las ciudades</span>
                <svg class="custom-select-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M6 9l6 6 6-6"/>
                </svg>
                <div id="ubicacion-dropdown" class="custom-select-dropdown" style="display:none">
                    <div class="custom-select-option seleccionado" onclick="seleccionarFiltro('ubicacion','','Todas las ciudades',event)">Todas las ciudades</div>
                    @foreach ($ubicaciones as $ubicacion)
                        <div class="custom-select-option" onclick="seleccionarFiltro('ubicacion','{{ $ubicacion }}','{{ $ubicacion }}',event)">
                            {{ $ubicacion }}
                        </div>
                    @endforeach
                </div>
            </div>
            <input type="hidden" id="filtro-ubicacion" value="">
        </div>

        @auth
        <div class="filtro-grupo">
            <label class="filtro-label">Favoritos</label>
            <button type="button" id="btn-solo-favoritos" class="btn-favoritos-filtro" onclick="toggleSoloFavoritos()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
                <span id="texto-solo-favoritos">Solo favoritos</span>
            </button>
            <input type="hidden" id="filtro-favoritos" value="0">
        </div>
        @endauth

        {{-- Botón limpiar --}}
        <div class="filtro-grupo">
            <span class="filtro-label" style="visibility:hidden">–</span>
            <button class="btn-limpiar" onclick="limpiarFiltros()">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
                
            </button>
        </div>

    </div>
</section>

{{-- ════════════════════════════════════════════════════
     CONTENIDO PRINCIPAL — dark section wrapper
════════════════════════════════════════════════════ --}}
<section class="home-seccion-dark">
    <div class="home-seccion-dark-inner">

        {{-- Spinner de carga --}}
        <div id="cargando" class="hidden flex justify-center py-16">
            <div class="spinner"></div>
        </div>

        {{-- Mensaje sin resultados --}}
        <div id="sin-resultados" class="hidden text-center py-20">
            <span class="flex justify-center mb-4" aria-hidden="true">
                <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="#a855f7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.5;">
                    <circle cx="10.5" cy="10.5" r="6.75"/>
                    <path d="M15.75 15.75L21 21"/>
                </svg>
            </span>
            <p class="font-bold text-lg" style="color:#f5f1ea">Sin resultados para estos filtros</p>
            <p class="text-sm mt-1 mb-6" style="color:rgba(245,241,234,0.4)">Prueba a cambiar la categoría o la ciudad</p>
            <button class="btn-morado" onclick="limpiarFiltros()">Ver todo</button>
        </div>

        {{-- ── Sección EVENTOS ── --}}
        <div id="seccion-eventos">
            <p class="home-seccion-kicker">Eventos</p>
            <div class="seccion-vibez-titulo">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M16.5 6v.75a3.75 3.75 0 0 1-7.5 0V6m-4.5 3h16.5m-16.5 0a2.25 2.25 0 0 0-2.25 2.25v8.25A2.25 2.25 0 0 0 4.5 21.75h15a2.25 2.25 0 0 0 2.25-2.25V11.25A2.25 2.25 0 0 0 19.5 9H4.5z"/>
                </svg>
                Próximos eventos
            </div>
            <p class="seccion-vibez-sub">
                {{ $eventos->count() }} evento{{ $eventos->count() !== 1 ? 's' : '' }} disponible{{ $eventos->count() !== 1 ? 's' : '' }}
            </p>

            <div id="grid-eventos"
                 class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

                @foreach ($eventos as $evento)
                    <article class="card-evento"
                             onclick="irADetalle('evento', {{ $evento->id }})">

                        <div class="card-imagen-wrap">
                            <button type="button"
                                    class="btn-favorito-card {{ in_array((int) $evento->id, $favoritosIds ?? [], true) ? 'activo' : '' }}"
                                    data-evento-id="{{ $evento->id }}"
                                    data-favorito="{{ in_array((int) $evento->id, $favoritosIds ?? [], true) ? '1' : '0' }}"
                                    aria-label="Marcar favorito"
                                    aria-pressed="{{ in_array((int) $evento->id, $favoritosIds ?? [], true) ? 'true' : 'false' }}"
                                    onclick="toggleFavorito(event, this)">
                                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                            </button>

                            <img src="{{ $evento->url_portada }}"
                                 alt="{{ $evento->titulo }}"
                                 class="card-imagen"
                                 onerror="this.src='https://picsum.photos/seed/fallback-{{ $evento->id }}/600/400'">

                            {{-- Badge con data-cat para el color CSS --}}
                            <span class="badge-categoria"
                                  data-cat="{{ $evento->categoria?->nombre ?? 'Evento' }}">
                                {{ $evento->categoria?->nombre ?? 'Evento' }}
                            </span>

                            <span class="badge-precio {{ $evento->es_gratuito ? 'badge-gratis' : '' }}">
                                {{ $evento->precio_formateado }}
                            </span>
                        </div>

                        <div class="card-cuerpo">
                            <h3 class="card-titulo">{{ $evento->titulo }}</h3>
                            <p class="card-meta">
                                <svg class="icono-meta" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ \Carbon\Carbon::parse($evento->fecha_inicio)->locale('es')->isoFormat('D MMM YYYY') }}
                            </p>
                            @if ($evento->ubicacion_nombre)
                                <p class="card-meta">
                                    <svg class="icono-meta" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $evento->ubicacion_nombre }}
                                </p>
                            @endif
                            @if ($evento->organizador?->empresa)
                                <p class="card-organizador">
                                    {{ $evento->organizador->empresa->nombre_empresa }}
                                </p>
                            @endif
                        </div>
                    </article>
                @endforeach

            </div>{{-- fin #grid-eventos --}}
        </div>

        {{-- Grid unificado para el AJAX (target del filtrado) --}}
        <div id="grid-resultados" class="hidden">
            <div id="grid-resultados-inner"
                 class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            </div>
        </div>

    </div>
</section>

@endsection

@push('scripts')
<script>
window.vibezFavoritosConfig = {
    userAuthenticated: @json(Auth::check()),
    loginUrl: @json(route('login'))
};
window.vibezHomeConfig = {
    totalEventos: {{ $eventos->count() }}
};

/* Sincroniza los mood chips con el selector de categoría existente */
function seleccionarMood(chip, categoriaId, categoriaNombre) {
    /* Desactivamos todos los chips y activamos el pulsado */
    document.querySelectorAll('.mood-chip').forEach(function(c) {
        c.classList.remove('activo');
    });
    chip.classList.add('activo');

    /* Delegamos al selector de categoría del filtro existente */
    seleccionarFiltro('categoria', categoriaId, categoriaNombre || 'Todas', new Event('click'));
}
</script>
<script src="{{ asset('js/favoritos.js') }}"></script>
<script src="{{ asset('js/home.js') }}"></script>
@endpush

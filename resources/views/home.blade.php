@extends('layouts.app')

@section('titulo', 'Explorar Eventos')

@push('estilos')
<style>
    .btn-favorito-card {
        position: absolute;
        bottom: 0.65rem;
        right: 0.65rem;
        z-index: 12;
        width: 2.2rem;
        height: 2.2rem;
        border-radius: 999px;
        border: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        background: rgba(15, 23, 42, 0.48);
        backdrop-filter: blur(5px);
        transition: transform 0.2s ease, background 0.2s ease;
    }

    .btn-favorito-card:hover {
        transform: scale(1.06);
        background: rgba(15, 23, 42, 0.62);
    }

    .btn-favorito-card svg {
        width: 1.05rem;
        height: 1.05rem;
        fill: currentColor;
        opacity: 0.78;
    }

    .btn-favorito-card.activo {
        background: rgba(244, 63, 94, 0.92);
    }

    .btn-favorito-card.activo svg {
        opacity: 1;
    }

    .btn-favorito-card.cargando {
        opacity: 0.7;
        pointer-events: none;
    }

    .btn-favoritos-filtro {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        border: 1px solid rgba(124, 58, 237, 0.25);
        background: #ffffff;
        color: #6d28d9;
        font-weight: 700;
        font-size: 0.82rem;
        padding: 0.58rem 0.78rem;
        border-radius: 0.8rem;
        transition: all 0.2s ease;
    }

    .btn-favoritos-filtro:hover {
        border-color: rgba(124, 58, 237, 0.45);
        background: #f8f5ff;
    }

    .btn-favoritos-filtro.activo {
        background: linear-gradient(135deg, #7c3aed, #a855f7);
        color: #ffffff;
        border-color: transparent;
    }
</style>
@endpush

@section('contenido')

{{-- ════════════════════════════════════════════════════
     BANNER HERO — Fondo oscuro con orbs neon animados
════════════════════════════════════════════════════ --}}
<section class="hero-home">

    {{-- Partículas decorativas (estrellitas de luz) --}}
    <div class="hero-particula hero-particula-1"></div>
    <div class="hero-particula hero-particula-2"></div>
    <div class="hero-particula hero-particula-3"></div>
    <div class="hero-particula hero-particula-4"></div>
    <div class="hero-particula hero-particula-5"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center relative z-10">

        {{-- Badge de bienvenida --}}
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold mb-5"
             style="background:rgba(168,85,247,0.15);border:1px solid rgba(168,85,247,0.3);color:#c084fc;letter-spacing:0.06em;text-transform:uppercase;">
            🔥 La plataforma de la escena joven
        </div>

        <h1 class="text-4xl sm:text-5xl font-black text-white tracking-tight leading-tight">
            Tu próxima<br>
            <span class="text-gradient-claro">aventura empieza aquí</span>
        </h1>

        <p class="mt-5 text-white/60 text-base max-w-lg mx-auto leading-relaxed">
            Eventos, conciertos, festivales y trabajo — todo lo que vive tu escena, en un solo lugar.
        </p>

        {{-- Pills de categorías interactivas --}}
        <div class="flex flex-wrap justify-center gap-2 mt-7">
            <span class="pill text-xs font-semibold px-3.5 py-1.5 rounded-full cursor-pointer"
                  onclick="seleccionarFiltro('categoria', '1', '🎵 Música', {stopPropagation:function(){}})">🎵 Música</span>
            <span class="pill text-xs font-semibold px-3.5 py-1.5 rounded-full cursor-pointer"
                  onclick="seleccionarFiltro('categoria', '3', '⚽ Deporte', {stopPropagation:function(){}})">⚽ Deporte</span>
            <span class="pill text-xs font-semibold px-3.5 py-1.5 rounded-full cursor-pointer"
                  onclick="seleccionarFiltro('categoria', '2', '🎭 Cultura', {stopPropagation:function(){}})">🎭 Cultura</span>
            <span class="pill text-xs font-semibold px-3.5 py-1.5 rounded-full cursor-pointer"
                  onclick="seleccionarFiltro('categoria', '4', '🍕 Gastro', {stopPropagation:function(){}})">🍕 Gastro</span>
            <span class="pill text-xs font-semibold px-3.5 py-1.5 rounded-full cursor-pointer"
                  onclick="seleccionarFiltro('categoria', 'trabajo', '💼 Trabajo', {stopPropagation:function(){}})">💼 Trabajo</span>
        </div>

    </div>
</section>

{{-- ════════════════════════════════════════════════════
     BARRA DE FILTROS — sticky bajo el nav
     Usa custom selects (sin look nativo del navegador)
════════════════════════════════════════════════════ --}}
<section class="barra-filtros sticky top-0 z-40">

    {{-- Overlay transparente para cerrar dropdowns al pulsar fuera --}}
    <div id="overlay-dropdowns"
         style="display:none;position:fixed;inset:0;z-index:200;"
         onclick="cerrarTodosDropdowns()"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex flex-wrap items-end gap-3">

        {{-- Contador de resultados --}}
        <p class="text-sm font-semibold mr-auto self-center"
           style="color:rgba(15,23,42,0.5)">
            <span id="contador-resultados">{{ $eventos->count() + $ofertas->count() }}</span>
            <span style="color:var(--morado)"> resultados</span>
        </p>

        {{-- ── Selector de CATEGORÍA personalizado ── --}}
        <div class="filtro-grupo" style="position:relative;z-index:250;">
            <label class="filtro-label">Categoría</label>
            <div class="custom-select-wrapper"
                 id="wrapper-categoria"
                 onclick="toggleDropdown('categoria')">
                <span id="categoria-display" class="custom-select-display">Todas</span>
                <svg class="custom-select-arrow" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5">
                    <path d="M6 9l6 6 6-6"/>
                </svg>
                <div id="categoria-dropdown" class="custom-select-dropdown" style="display:none">
                    <div class="custom-select-option seleccionado"
                         onclick="seleccionarFiltro('categoria','','Todas',event)">Todas</div>
                    @foreach ($categorias as $categoria)
                        <div class="custom-select-option"
                             onclick="seleccionarFiltro('categoria','{{ $categoria->id }}','{{ $categoria->nombre }}',event)">
                            {{ $categoria->nombre }}
                        </div>
                    @endforeach
                    <div class="custom-select-option"
                         onclick="seleccionarFiltro('categoria','trabajo','💼 Bolsa de Trabajo',event)">
                        💼 Bolsa de Trabajo
                    </div>
                </div>
            </div>
            <input type="hidden" id="filtro-categoria" value="">
        </div>

        {{-- ── Selector de UBICACIÓN personalizado ── --}}
        <div class="filtro-grupo" style="position:relative;z-index:240;">
            <label class="filtro-label">Ubicación</label>
            <div class="custom-select-wrapper"
                 id="wrapper-ubicacion"
                 onclick="toggleDropdown('ubicacion')">
                <span id="ubicacion-display" class="custom-select-display">Todas las ciudades</span>
                <svg class="custom-select-arrow" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5">
                    <path d="M6 9l6 6 6-6"/>
                </svg>
                <div id="ubicacion-dropdown" class="custom-select-dropdown" style="display:none">
                    <div class="custom-select-option seleccionado"
                         onclick="seleccionarFiltro('ubicacion','','Todas las ciudades',event)">
                        Todas las ciudades
                    </div>
                    @foreach ($ubicaciones as $ubicacion)
                        <div class="custom-select-option"
                             onclick="seleccionarFiltro('ubicacion','{{ $ubicacion }}','{{ $ubicacion }}',event)">
                            {{ $ubicacion }}
                        </div>
                    @endforeach
                </div>
            </div>
            <input type="hidden" id="filtro-ubicacion" value="">
        </div>

        {{-- ── Toggle SOLO FAVORITOS ── --}}
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

        {{-- Botón limpiar — wrapped en filtro-grupo con label invisible para alinearlo ── --}}
        <div class="filtro-grupo">
            <span class="filtro-label" style="visibility:hidden">–</span>
            <button class="btn-limpiar" onclick="limpiarFiltros()">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
                Limpiar
            </button>
        </div>

    </div>
</section>

{{-- ════════════════════════════════════════════════════
     GRID DE TARJETAS (eventos + ofertas mezclados)
     id="grid-resultados" es el target del AJAX
════════════════════════════════════════════════════ --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Spinner de carga --}}
    <div id="cargando" class="hidden flex justify-center py-16">
        <div class="spinner"></div>
    </div>

    {{-- Mensaje sin resultados --}}
    <div id="sin-resultados" class="hidden text-center py-20">
        <p class="text-5xl mb-3">🔍</p>
        <p class="font-bold text-lg" style="color:var(--navy)">Sin resultados para estos filtros</p>
        <p class="text-sm mt-1 mb-5" style="color:rgba(15,23,42,0.45)">Prueba a cambiar la categoría o la ciudad</p>
        <button class="btn-morado" onclick="limpiarFiltros()">Ver todo</button>
    </div>

    {{-- ── Sección EVENTOS ── --}}
    <div id="seccion-eventos">
        <div class="seccion-vibez-titulo">
            <span>🎉</span> Eventos
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

    {{-- ── Separador y sección BOLSA DE TRABAJO ── --}}
    @if ($ofertas->count() > 0)
        <hr class="linea-divisora">

        <div id="seccion-trabajos">
            <div class="seccion-vibez-titulo">
                <span>💼</span> Bolsa de Trabajo
            </div>
            <p class="seccion-vibez-sub">
                {{ $ofertas->count() }} oferta{{ $ofertas->count() !== 1 ? 's' : '' }} de empleo en la escena de eventos
            </p>

            <div id="grid-trabajos"
                 class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

                @foreach ($ofertas as $oferta)
                    <article class="card-trabajo"
                             onclick="irADetalle('oferta', {{ $oferta->id }})">

                        <div class="card-trabajo-header">
                            <svg class="icono-trabajo" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="badge-trabajo">Trabajo</span>
                        </div>

                        <div class="card-cuerpo">
                            <h3 class="card-titulo">{{ $oferta->titulo }}</h3>
                            @if ($oferta->organizador?->empresa)
                                <p class="card-meta font-semibold" style="color:var(--morado)">
                                    {{ $oferta->organizador->empresa->nombre_empresa }}
                                </p>
                            @endif
                            @if ($oferta->ubicacion)
                                <p class="card-meta">
                                    <svg class="icono-meta" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    {{ $oferta->ubicacion }}
                                </p>
                            @endif
                            <p class="card-salario">{{ $oferta->salario_formateado }}</p>
                            <p class="card-meta" style="font-size:0.75rem">
                                {{ $oferta->vacantes }} vacante{{ $oferta->vacantes !== 1 ? 's' : '' }}
                            </p>
                        </div>
                    </article>
                @endforeach

            </div>{{-- fin #grid-trabajos --}}
        </div>
    @endif

    {{-- Grid unificado para el AJAX (target del filtrado) --}}
    <div id="grid-resultados" class="hidden">
        <div id="grid-resultados-inner"
             class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        </div>
    </div>

</section>

@endsection

{{-- ════════════════════════════════════════════════════
     SCRIPTS — Filtrado AJAX + Custom Selects
════════════════════════════════════════════════════ --}}
@push('scripts')
<script>
window.vibezFavoritosConfig = {
    userAuthenticated: @json(Auth::check()),
    loginUrl: @json(route('login'))
};

window.vibezHomeConfig = {
    totalEventos: {{ $eventos->count() }},
    totalOfertas: {{ $ofertas->count() }}
};
</script>
<script src="{{ asset('js/favoritos.js') }}"></script>
<script>
var HOME_CFG = window.vibezHomeConfig || {};

/**
 * Abre o cierra el dropdown personalizado de un selector.
 * Usa un overlay invisible para detectar clics fuera del selector.
 */
function toggleDropdown(id) {
    var dropdown = document.getElementById(id + '-dropdown');
    var wrapper  = document.getElementById('wrapper-' + id);
    var overlay  = document.getElementById('overlay-dropdowns');
    var estaAbierto = dropdown.style.display === 'block';

    cerrarTodosDropdowns();

    if (!estaAbierto) {
        dropdown.style.display = 'block';
        wrapper.classList.add('abierto');
        overlay.style.display = 'block';
    }
}

/**
 * Cierra todos los custom selects y oculta el overlay.
 */
function cerrarTodosDropdowns() {
    ['categoria', 'ubicacion'].forEach(function(id) {
        var d = document.getElementById(id + '-dropdown');
        var w = document.getElementById('wrapper-' + id);
        if (d) d.style.display = 'none';
        if (w) w.classList.remove('abierto');
    });
    var overlay = document.getElementById('overlay-dropdowns');
    if (overlay) overlay.style.display = 'none';
}

/**
 * Selecciona una opción del custom select, actualiza el input hidden
 * y dispara el filtrado AJAX.
 * @param {string} filtroId  - 'categoria' o 'ubicacion'
 * @param {string} valor     - Valor a guardar en el input hidden
 * @param {string} texto     - Texto visible en el selector
 * @param {Event}  event     - Evento del click (para stopPropagation)
 */
function seleccionarFiltro(filtroId, valor, texto, event) {
    event.stopPropagation();

    // Actualizar input hidden y texto visible
    var inputHidden = document.getElementById('filtro-' + filtroId);
    var display = document.getElementById(filtroId + '-display');
    if (inputHidden) inputHidden.value = valor;
    if (display) display.textContent = texto;

    // Marcar la opción como seleccionada visualmente
    var dropdown = document.getElementById(filtroId + '-dropdown');
    if (dropdown) {
        dropdown.querySelectorAll('.custom-select-option').forEach(function(op) {
            op.classList.remove('seleccionado');
        });
        if (event.target && event.target.classList) {
            event.target.classList.add('seleccionado');
        }
    }

    cerrarTodosDropdowns();
    aplicarFiltros();
}

/**
 * Lee los filtros activos y hace fetch AJAX al endpoint /api/filtrar.
 * Muestra el grid unificado de AJAX ocultando las secciones estáticas.
 */
function aplicarFiltros() {
    var categoria = document.getElementById('filtro-categoria').value;
    var ubicacion = document.getElementById('filtro-ubicacion').value;
    var favoritos = document.getElementById('filtro-favoritos').value;

    // Mostrar spinner, ocultar secciones
    document.getElementById('cargando').classList.remove('hidden');
    document.getElementById('grid-resultados').classList.add('hidden');
    document.getElementById('sin-resultados').classList.add('hidden');

    var seccionEventos  = document.getElementById('seccion-eventos');
    var seccionTrabajos = document.getElementById('seccion-trabajos');
    if (seccionEventos)  seccionEventos.style.display  = 'none';
    if (seccionTrabajos) seccionTrabajos.style.display = 'none';

    fetch('/api/filtrar?categoria=' + encodeURIComponent(categoria) + '&ubicacion=' + encodeURIComponent(ubicacion) + '&favoritos=' + encodeURIComponent(favoritos))
        .then(function(respuesta) { return respuesta.json(); })
        .then(function(datos) {

            document.getElementById('contador-resultados').textContent = datos.total;
            document.getElementById('cargando').classList.add('hidden');

            if (datos.total === 0) {
                document.getElementById('sin-resultados').classList.remove('hidden');
                return;
            }

            // Construir HTML del grid combinado
            var htmlGrid = '';
            datos.eventos.forEach(function(evento) {
                htmlGrid += crearTarjetaEvento(evento);
            });
            datos.ofertas.forEach(function(oferta) {
                htmlGrid += crearTarjetaOferta(oferta);
            });

            document.getElementById('grid-resultados-inner').innerHTML = htmlGrid;
            document.getElementById('grid-resultados').classList.remove('hidden');
        })
        .catch(function(error) {
            console.error('Error al filtrar:', error);
            document.getElementById('cargando').classList.add('hidden');
            // Mostrar de nuevo las secciones estáticas
            if (seccionEventos)  seccionEventos.style.display  = '';
            if (seccionTrabajos) seccionTrabajos.style.display = '';
        });
}

/**
 * Resetea todos los filtros y vuelve a mostrar las secciones estáticas.
 */
function limpiarFiltros() {
    // Resetear inputs hidden
    document.getElementById('filtro-categoria').value = '';
    document.getElementById('filtro-ubicacion').value = '';
    document.getElementById('filtro-favoritos').value = '0';
    document.getElementById('btn-solo-favoritos').classList.remove('activo');

    // Resetear textos del custom select
    document.getElementById('categoria-display').textContent = 'Todas';
    document.getElementById('ubicacion-display').textContent = 'Todas las ciudades';

    // Resetear marcas de seleccionado
    document.querySelectorAll('.custom-select-dropdown .custom-select-option').forEach(function(op) {
        op.classList.remove('seleccionado');
    });
    // Marcar primera opción de cada dropdown como seleccionada
    ['categoria-dropdown', 'ubicacion-dropdown'].forEach(function(id) {
        var primera = document.querySelector('#' + id + ' .custom-select-option');
        if (primera) primera.classList.add('seleccionado');
    });

    // Ocultar el grid AJAX y mostrar las secciones estáticas
    document.getElementById('grid-resultados').classList.add('hidden');
    document.getElementById('sin-resultados').classList.add('hidden');

    var seccionEventos  = document.getElementById('seccion-eventos');
    var seccionTrabajos = document.getElementById('seccion-trabajos');
    if (seccionEventos)  seccionEventos.style.display  = '';
    if (seccionTrabajos) seccionTrabajos.style.display = '';

    // Actualizar contador con total real
    var totalEventos  = Number(HOME_CFG.totalEventos || 0);
    var totalOfertas  = Number(HOME_CFG.totalOfertas || 0);
    document.getElementById('contador-resultados').textContent = totalEventos + totalOfertas;
}

/**
 * Alterna el estado del filtro de solo favoritos.
 */
function toggleSoloFavoritos() {
    if (!window.vibezFavoritosConfig.userAuthenticated) {
        window.location.href = window.vibezFavoritosConfig.loginUrl;
        return;
    }
    
    var btn = document.getElementById('btn-solo-favoritos');
    var input = document.getElementById('filtro-favoritos');
    
    if (input.value === '1') {
        input.value = '0';
        btn.classList.remove('activo');
    } else {
        input.value = '1';
        btn.classList.add('activo');
    }
    
    aplicarFiltros();
}

/**
 * Navega al detalle del evento o la oferta al hacer clic en una tarjeta.
 */
function irADetalle(tipo, id) {
    window.location.href = tipo === 'evento' ? '/eventos/' + id : '/trabajos/' + id;
}

/**
 * Genera el HTML de una tarjeta de evento para el grid AJAX.
 */
function crearTarjetaEvento(evento) {
    var fecha = new Date(evento.fecha_inicio).toLocaleDateString('es-ES', {
        day: 'numeric', month: 'short', year: 'numeric'
    });
    var imagen = evento.portada || ('https://picsum.photos/seed/evento-' + evento.id + '/600/400');
    var ubicacionHtml = evento.ubicacion_nombre
        ? '<p class="card-meta"><svg class="icono-meta" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>' + evento.ubicacion_nombre + '</p>'
        : '';

    return '<article class="card-evento" onclick="irADetalle(\'evento\',' + evento.id + ')">'
        + '<div class="card-imagen-wrap">'
        + crearBotonFavorito(evento.id, Boolean(evento.is_favorito))
        + '<img src="' + imagen + '" alt="' + evento.titulo + '" class="card-imagen" onerror="this.src=\'https://picsum.photos/seed/fallback-' + evento.id + '/600/400\'">'
        + '<span class="badge-categoria" data-cat="' + evento.categoria + '">' + evento.categoria + '</span>'
        + '<span class="badge-precio ' + (evento.es_gratuito ? 'badge-gratis' : '') + '">' + evento.precio_formateado + '</span>'
        + '</div>'
        + '<div class="card-cuerpo">'
        + '<h3 class="card-titulo">' + evento.titulo + '</h3>'
        + '<p class="card-meta"><svg class="icono-meta" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>' + fecha + '</p>'
        + ubicacionHtml
        + '<p class="card-organizador">' + evento.organizador + '</p>'
        + '</div></article>';
}

/**
 * Genera el HTML de una tarjeta de oferta de trabajo para el grid AJAX.
 */
function crearTarjetaOferta(oferta) {
    var ubicacionHtml = oferta.ubicacion_nombre
        ? '<p class="card-meta"><svg class="icono-meta" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>' + oferta.ubicacion_nombre + '</p>'
        : '';

    return '<article class="card-trabajo" onclick="irADetalle(\'oferta\',' + oferta.id + ')">'
        + '<div class="card-trabajo-header">'
        + '<svg class="icono-trabajo" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>'
        + '<span class="badge-trabajo">Trabajo</span>'
        + '</div>'
        + '<div class="card-cuerpo">'
        + '<h3 class="card-titulo">' + oferta.titulo + '</h3>'
        + '<p class="card-meta" style="font-weight:600;color:var(--morado)">' + oferta.organizador + '</p>'
        + ubicacionHtml
        + '<p class="card-salario">' + oferta.salario_formateado + '</p>'
        + '<p class="card-meta" style="font-size:0.75rem">' + oferta.vacantes + ' vacante' + (oferta.vacantes !== 1 ? 's' : '') + '</p>'
        + '</div></article>';
}
</script>
@endpush

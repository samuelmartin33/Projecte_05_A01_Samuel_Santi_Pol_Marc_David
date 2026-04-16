@extends('layouts.app')

@section('titulo', 'Explorar Eventos')

@section('contenido')

{{-- ════════════════════════════════════════════════════
     BANNER HERO — Título y subtítulo de la sección
════════════════════════════════════════════════════ --}}
<section class="hero-home">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 text-center">
        <h1 class="text-4xl sm:text-5xl font-black text-white tracking-tight leading-tight">
            Descubre tu próximo<br>
            <span class="text-gradient-claro">evento</span>
        </h1>
        <p class="mt-4 text-white/70 text-lg max-w-xl mx-auto">
            Música, cultura, deporte y oportunidades de trabajo — todo en un solo lugar.
        </p>
    </div>
</section>

{{-- ════════════════════════════════════════════════════
     BARRA DE FILTROS — sticky bajo el nav
     onchange llama a aplicarFiltros() que hace fetch AJAX
════════════════════════════════════════════════════ --}}
<section class="barra-filtros sticky top-16 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-wrap items-center gap-4">

        {{-- Indicador de resultados --}}
        <p class="text-sm font-medium text-navy/70 mr-auto">
            <span id="contador-resultados">
                {{ $eventos->count() + $ofertas->count() }}
            </span> resultados
        </p>

        {{-- Selector de categoría --}}
        <div class="filtro-grupo">
            <label class="filtro-label" for="filtro-categoria">Categoría</label>
            <select id="filtro-categoria"
                    class="filtro-select"
                    onchange="aplicarFiltros()">
                <option value="">Todas</option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                @endforeach
                {{-- Opción especial para mostrar solo ofertas de trabajo --}}
                <option value="trabajo">💼 Bolsa de Trabajo</option>
            </select>
        </div>

        {{-- Selector de ubicación --}}
        <div class="filtro-grupo">
            <label class="filtro-label" for="filtro-ubicacion">Ubicación</label>
            <select id="filtro-ubicacion"
                    class="filtro-select"
                    onchange="aplicarFiltros()">
                <option value="">Todas las ciudades</option>
                @foreach ($ubicaciones as $ubicacion)
                    <option value="{{ $ubicacion }}">{{ $ubicacion }}</option>
                @endforeach
            </select>
        </div>

        {{-- Botón para limpiar filtros --}}
        <button class="btn-limpiar"
                onclick="limpiarFiltros()">
            Limpiar
        </button>

    </div>
</section>

{{-- ════════════════════════════════════════════════════
     GRID DE TARJETAS
     id="grid-resultados" es el target del AJAX
════════════════════════════════════════════════════ --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Spinner de carga — oculto por defecto, se muestra durante el fetch --}}
    <div id="cargando" class="hidden flex justify-center py-16">
        <div class="spinner"></div>
    </div>

    {{-- Mensaje cuando no hay resultados --}}
    <div id="sin-resultados" class="hidden text-center py-20">
        <p class="text-4xl mb-3">🔍</p>
        <p class="text-navy/60 text-lg font-medium">No hay resultados para estos filtros.</p>
        <button class="btn-morado mt-4" onclick="limpiarFiltros()">Ver todo</button>
    </div>

    {{-- Grid de tarjetas — se reemplaza en cada filtrado --}}
    <div id="grid-resultados"
         class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

        {{-- ── Tarjetas de EVENTOS ── --}}
        @foreach ($eventos as $evento)
            <article class="card-evento"
                     onclick="irADetalle('evento', {{ $evento->id }})">

                {{-- Imagen de portada con badges superpuestos --}}
                <div class="card-imagen-wrap">
                    <img src="{{ $evento->url_portada }}"
                         alt="{{ $evento->titulo }}"
                         class="card-imagen"
                         onerror="this.src='https://picsum.photos/seed/fallback-{{ $evento->id }}/600/400'">

                    {{-- Badge de categoría (esquina superior izquierda) --}}
                    <span class="badge-categoria">{{ $evento->categoria?->nombre ?? 'Evento' }}</span>

                    {{-- Badge de precio (esquina superior derecha) --}}
                    <span class="badge-precio {{ $evento->es_gratuito ? 'badge-gratis' : '' }}">
                        {{ $evento->precio_formateado }}
                    </span>
                </div>

                {{-- Información del evento --}}
                <div class="card-cuerpo">
                    <h3 class="card-titulo">{{ $evento->titulo }}</h3>

                    {{-- Fecha de inicio formateada --}}
                    <p class="card-meta">
                        <svg class="icono-meta" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ \Carbon\Carbon::parse($evento->fecha_inicio)->locale('es')->isoFormat('D MMM YYYY') }}
                    </p>

                    {{-- Ubicación (si existe) --}}
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

                    {{-- Nombre del organizador --}}
                    @if ($evento->organizador?->empresa)
                        <p class="card-organizador">
                            {{ $evento->organizador->empresa->nombre_empresa }}
                        </p>
                    @endif
                </div>

            </article>
        @endforeach

        {{-- ── Tarjetas de BOLSA DE TRABAJO ── --}}
        @foreach ($ofertas as $oferta)
            <article class="card-trabajo"
                     onclick="irADetalle('oferta', {{ $oferta->id }})">

                {{-- Cabecera con degradado en lugar de imagen --}}
                <div class="card-trabajo-header">
                    <svg class="icono-trabajo" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span class="badge-trabajo">Trabajo</span>
                </div>

                {{-- Información de la oferta --}}
                <div class="card-cuerpo">
                    <h3 class="card-titulo">{{ $oferta->titulo }}</h3>

                    {{-- Empresa --}}
                    @if ($oferta->organizador?->empresa)
                        <p class="card-meta font-semibold text-morado-vibez">
                            {{ $oferta->organizador->empresa->nombre_empresa }}
                        </p>
                    @endif

                    {{-- Ubicación --}}
                    @if ($oferta->ubicacion)
                        <p class="card-meta">
                            <svg class="icono-meta" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            {{ $oferta->ubicacion }}
                        </p>
                    @endif

                    {{-- Salario --}}
                    <p class="card-salario">{{ $oferta->salario_formateado }}</p>

                    {{-- Número de vacantes --}}
                    <p class="card-meta text-xs">
                        {{ $oferta->vacantes }} vacante{{ $oferta->vacantes !== 1 ? 's' : '' }}
                    </p>
                </div>

            </article>
        @endforeach

    </div>{{-- fin #grid-resultados --}}

</section>

@endsection

{{-- ════════════════════════════════════════════════════
     SCRIPTS DE LA HOME — filtrado AJAX con fetch()
════════════════════════════════════════════════════ --}}
@push('scripts')
<script>
/**
 * Redirige al detalle del evento o la oferta de trabajo al hacer clic en una tarjeta.
 * @param {string} tipo   - 'evento' o 'oferta'
 * @param {number} id     - ID del registro en la BD
 */
function irADetalle(tipo, id) {
    if (tipo === 'evento') {
        window.location.href = '/eventos/' + id;
    } else {
        window.location.href = '/trabajos/' + id;
    }
}

/**
 * Lee los valores actuales de los dos selectores de filtro
 * y hace una petición AJAX al endpoint /api/filtrar.
 * Al recibir la respuesta, actualiza el grid sin recargar la página.
 */
function aplicarFiltros() {
    const categoria = document.getElementById('filtro-categoria').value;
    const ubicacion = document.getElementById('filtro-ubicacion').value;

    // Mostrar spinner y ocultar grid mientras carga
    document.getElementById('cargando').classList.remove('hidden');
    document.getElementById('grid-resultados').classList.add('hidden');
    document.getElementById('sin-resultados').classList.add('hidden');

    // Llamada AJAX al backend
    fetch(`/api/filtrar?categoria=${encodeURIComponent(categoria)}&ubicacion=${encodeURIComponent(ubicacion)}`)
        .then(function(respuesta) {
            // Convertir la respuesta a JSON
            return respuesta.json();
        })
        .then(function(datos) {
            // Actualizar el contador de resultados
            document.getElementById('contador-resultados').textContent = datos.total;

            // Ocultar spinner
            document.getElementById('cargando').classList.add('hidden');

            // Si no hay resultados, mostrar mensaje vacío
            if (datos.total === 0) {
                document.getElementById('sin-resultados').classList.remove('hidden');
                return;
            }

            // Construir el HTML de todas las tarjetas
            let htmlGrid = '';

            // Renderizar tarjetas de eventos
            datos.eventos.forEach(function(evento) {
                htmlGrid += crearTarjetaEvento(evento);
            });

            // Renderizar tarjetas de ofertas de trabajo
            datos.ofertas.forEach(function(oferta) {
                htmlGrid += crearTarjetaOferta(oferta);
            });

            // Inyectar el HTML en el grid y mostrarlo
            const grid = document.getElementById('grid-resultados');
            grid.innerHTML = htmlGrid;
            grid.classList.remove('hidden');
        })
        .catch(function(error) {
            // En caso de error de red, mostrar el grid como estaba
            console.error('Error al filtrar:', error);
            document.getElementById('cargando').classList.add('hidden');
            document.getElementById('grid-resultados').classList.remove('hidden');
        });
}

/**
 * Resetea los dos selectores a su valor por defecto
 * y vuelve a cargar todos los eventos.
 */
function limpiarFiltros() {
    document.getElementById('filtro-categoria').value = '';
    document.getElementById('filtro-ubicacion').value = '';
    aplicarFiltros();
}

/**
 * Genera el HTML de una tarjeta de evento a partir de los datos JSON.
 * @param {Object} evento - Objeto con los datos del evento devueltos por la API
 * @returns {string} HTML de la tarjeta lista para insertar en el DOM
 */
function crearTarjetaEvento(evento) {
    // Formatear la fecha a formato español (ej: "20 jul. 2026")
    const fecha = new Date(evento.fecha_inicio).toLocaleDateString('es-ES', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });

    // Si no viene imagen, usar placeholder de picsum con ID como semilla
    const imagen = evento.portada || ('https://picsum.photos/seed/evento-' + evento.id + '/600/400');

    // Construir la línea de ubicación solo si existe
    const ubicacionHtml = evento.ubicacion_nombre
        ? `<p class="card-meta">
               <svg class="icono-meta" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
               </svg>
               ${evento.ubicacion_nombre}
           </p>`
        : '';

    return `
        <article class="card-evento" onclick="irADetalle('evento', ${evento.id})">
            <div class="card-imagen-wrap">
                <img src="${imagen}"
                     alt="${evento.titulo}"
                     class="card-imagen"
                     onerror="this.src='https://picsum.photos/seed/fallback-${evento.id}/600/400'">
                <span class="badge-categoria">${evento.categoria}</span>
                <span class="badge-precio ${evento.es_gratuito ? 'badge-gratis' : ''}">${evento.precio_formateado}</span>
            </div>
            <div class="card-cuerpo">
                <h3 class="card-titulo">${evento.titulo}</h3>
                <p class="card-meta">
                    <svg class="icono-meta" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    ${fecha}
                </p>
                ${ubicacionHtml}
                <p class="card-organizador">${evento.organizador}</p>
            </div>
        </article>
    `;
}

/**
 * Genera el HTML de una tarjeta de oferta de trabajo.
 * @param {Object} oferta - Datos de la oferta devueltos por la API
 * @returns {string} HTML de la tarjeta lista para insertar en el DOM
 */
function crearTarjetaOferta(oferta) {
    const ubicacionHtml = oferta.ubicacion_nombre
        ? `<p class="card-meta">
               <svg class="icono-meta" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
               </svg>
               ${oferta.ubicacion_nombre}
           </p>`
        : '';

    return `
        <article class="card-trabajo" onclick="irADetalle('oferta', ${oferta.id})">
            <div class="card-trabajo-header">
                <svg class="icono-trabajo" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span class="badge-trabajo">Trabajo</span>
            </div>
            <div class="card-cuerpo">
                <h3 class="card-titulo">${oferta.titulo}</h3>
                <p class="card-meta font-semibold text-morado-vibez">${oferta.organizador}</p>
                ${ubicacionHtml}
                <p class="card-salario">${oferta.salario_formateado}</p>
                <p class="card-meta text-xs">${oferta.vacantes} vacante${oferta.vacantes !== 1 ? 's' : ''}</p>
            </div>
        </article>
    `;
}
</script>
@endpush

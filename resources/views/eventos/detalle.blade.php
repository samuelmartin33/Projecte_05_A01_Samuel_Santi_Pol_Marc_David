@extends('layouts.app')

@section('titulo', $evento->titulo)

{{-- Cargar Leaflet CSS solo en esta página --}}
@push('estilos')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        /* Altura fija para el mapa de Leaflet */
        #mapa-evento {
            height: 320px;
            border-radius: 16px;
            z-index: 1;
        }

        .btn-favorito-detalle {
            width: 100%;
            margin-top: 0.75rem;
            border-radius: 999px;
            border: 1px solid rgba(124, 58, 237, 0.2);
            background: #ffffff;
            color: #4c1d95;
            padding: 0.75rem 1rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-favorito-detalle:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(124, 58, 237, 0.12);
        }

        .btn-favorito-detalle svg {
            width: 1rem;
            height: 1rem;
            fill: currentColor;
            opacity: 0.82;
        }

        .btn-favorito-detalle.activo {
            background: #f43f5e;
            border-color: #f43f5e;
            color: #ffffff;
        }

        .btn-favorito-detalle.activo svg {
            opacity: 1;
        }

        .btn-favorito-detalle.cargando {
            opacity: 0.7;
            pointer-events: none;
        }
    </style>
@endpush

@section('contenido')

{{-- ════════════════════════════════════════════════════
     HERO DEL EVENTO — imagen de portada con overlay
════════════════════════════════════════════════════ --}}
<div class="hero-detalle" style="background-image: url('{{ $evento->url_portada }}')">
    {{-- Overlay oscuro con degradado para legibilidad del texto --}}
    <div class="hero-detalle-overlay">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

            {{-- Botón volver --}}
            <a href="{{ route('home') }}" class="btn-volver">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver
            </a>

            {{-- Badge de categoría --}}
            <span class="badge-categoria-hero mt-6 inline-block">
                {{ $evento->categoria?->nombre ?? 'Evento' }}
            </span>

            {{-- Título del evento --}}
            <h1 class="text-3xl sm:text-5xl font-black text-white mt-3 leading-tight max-w-3xl">
                {{ $evento->titulo }}
            </h1>

            {{-- Datos clave: fecha, ubicación, precio --}}
            <div class="flex flex-wrap gap-6 mt-6">

                {{-- Fecha --}}
                <div class="dato-hero">
                    <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>
                        {{ \Carbon\Carbon::parse($evento->fecha_inicio)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                        · {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('H:i') }}h
                    </span>
                </div>

                {{-- Ubicación --}}
                @if ($evento->ubicacion_nombre)
                    <div class="dato-hero">
                        <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                        <span>{{ $evento->ubicacion_nombre }}</span>
                    </div>
                @endif

                {{-- Precio --}}
                <div class="dato-hero">
                    <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 9a2 2 0 10-4 0v5a2 2 0 01-2 2h6m-6-4h4m8 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-bold text-lg">{{ $evento->precio_formateado }}</span>
                </div>

            </div>

        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════
     CUERPO DEL DETALLE — layout de 2 columnas
     Izquierda (2/3): descripción, organizador, galería
     Derecha (1/3): ficha de compra + mapa
════════════════════════════════════════════════════ --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        {{-- ─── COLUMNA IZQUIERDA ─── --}}
        <div class="lg:col-span-2 space-y-10">

            {{-- Descripción completa del evento --}}
            <section class="seccion-detalle">
                <h2 class="seccion-titulo">Sobre el evento</h2>
                <p class="text-navy/80 leading-relaxed text-base">
                    {{ $evento->descripcion ?? 'No hay descripción disponible.' }}
                </p>
            </section>

            {{-- Datos adicionales: aforo, edad mínima, tipo --}}
            <section class="seccion-detalle">
                <h2 class="seccion-titulo">Información adicional</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">

                    {{-- Aforo máximo --}}
                    @if ($evento->aforo_maximo)
                        <div class="ficha-dato">
                            <span class="ficha-dato-label">Aforo máximo</span>
                            <span class="ficha-dato-valor">{{ number_format($evento->aforo_maximo) }} personas</span>
                        </div>
                    @endif

                    {{-- Entradas disponibles --}}
                    @if ($evento->aforo_maximo)
                        @php
                            $disponibles = $evento->aforo_maximo - $evento->aforo_actual;
                        @endphp
                        <div class="ficha-dato">
                            <span class="ficha-dato-label">Disponibles</span>
                            <span class="ficha-dato-valor {{ $disponibles < 50 ? 'text-red-600' : 'text-green-600' }}">
                                {{ number_format($disponibles) }}
                            </span>
                        </div>
                    @endif

                    {{-- Edad mínima --}}
                    @if ($evento->edad_minima)
                        <div class="ficha-dato">
                            <span class="ficha-dato-label">Edad mínima</span>
                            <span class="ficha-dato-valor">+{{ $evento->edad_minima }}</span>
                        </div>
                    @endif

                    {{-- Fecha de fin --}}
                    @if ($evento->fecha_fin)
                        <div class="ficha-dato">
                            <span class="ficha-dato-label">Finaliza</span>
                            <span class="ficha-dato-valor">
                                {{ \Carbon\Carbon::parse($evento->fecha_fin)->format('H:i') }}h
                            </span>
                        </div>
                    @endif

                </div>
            </section>

            {{-- Organizador del evento --}}
            @if ($evento->organizador?->empresa)
                <section class="seccion-detalle">
                    <h2 class="seccion-titulo">Organiza</h2>
                    <div class="card-organizador-detalle">
                        {{-- Logo de la empresa o inicial del nombre --}}
                        <div class="logo-empresa">
                            @if ($evento->organizador->empresa->logo_url)
                                <img src="{{ $evento->organizador->empresa->logo_url }}"
                                     alt="{{ $evento->organizador->empresa->nombre_empresa }}"
                                     class="w-full h-full object-cover">
                            @else
                                <span class="text-white font-black text-xl">
                                    {{ strtoupper(substr($evento->organizador->empresa->nombre_empresa, 0, 1)) }}
                                </span>
                            @endif
                        </div>
                        <div>
                            <p class="font-bold text-navy text-lg">
                                {{ $evento->organizador->empresa->nombre_empresa }}
                            </p>
                            @if ($evento->organizador->empresa->descripcion)
                                <p class="text-navy/60 text-sm mt-1 line-clamp-2">
                                    {{ $evento->organizador->empresa->descripcion }}
                                </p>
                            @endif
                            @if ($evento->organizador->empresa->sitio_web)
                                <a href="{{ $evento->organizador->empresa->sitio_web }}"
                                   target="_blank"
                                   class="texto-enlace text-sm mt-2 inline-block">
                                    Visitar web →
                                </a>
                            @endif
                        </div>
                    </div>
                </section>
            @endif

            {{-- Galería de imágenes adicionales --}}
            @if ($evento->imagenes->where('es_portada', 0)->count() > 0)
                <section class="seccion-detalle">
                    <h2 class="seccion-titulo">Galería</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach ($evento->imagenes->where('es_portada', 0) as $imagen)
                            <div class="overflow-hidden rounded-xl aspect-video">
                                <img src="{{ $imagen->imagen_url }}"
                                     alt="{{ $imagen->descripcion ?? $evento->titulo }}"
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                     onerror="this.parentElement.remove()">
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

        </div>{{-- fin columna izquierda --}}

        {{-- ─── COLUMNA DERECHA (sticky) ─── --}}
        <div class="lg:col-span-1">
            <div class="lg:sticky lg:top-24 space-y-6">

                {{-- Tarjeta de compra / acción principal --}}
                <div class="ficha-compra">

                    {{-- Precio destacado --}}
                    <div class="text-center mb-6">
                        <p class="text-navy/50 text-sm uppercase tracking-widest font-semibold mb-1">Precio</p>
                        <p class="text-4xl font-black text-gradient">
                            {{ $evento->precio_formateado }}
                        </p>
                        @if (!$evento->es_gratuito)
                            <p class="text-navy/40 text-xs mt-1">por persona · IVA incluido</p>
                        @endif
                    </div>

                    {{-- Barra de ocupación del aforo --}}
                    @if ($evento->aforo_maximo)
                        @php
                            $porcentajeOcupacion = ($evento->aforo_actual / $evento->aforo_maximo) * 100;
                        @endphp
                        <div class="mb-6">
                            <div class="flex justify-between text-xs text-navy/50 mb-1">
                                <span>{{ number_format($evento->aforo_maximo - $evento->aforo_actual) }} entradas disponibles</span>
                                <span>{{ round($porcentajeOcupacion) }}% ocupado</span>
                            </div>
                            <div class="barra-aforo-fondo">
                                <div class="barra-aforo-relleno {{ $porcentajeOcupacion > 80 ? 'barra-aforo-critico' : '' }}"
                                     style="width: {{ min($porcentajeOcupacion, 100) }}%"></div>
                            </div>
                        </div>
                    @endif

                    {{-- Botón principal de acción (oculto para admin) --}}
                    @if(!Auth::check() || !Auth::user()->isAdmin())
                    <button class="btn-comprar w-full"
                            onclick="abrirCompra()">
                        {{ $evento->es_gratuito ? 'Reservar entrada gratuita' : 'Comprar entrada' }}
                    </button>
                    @endif

                    @auth
                        <button type="button"
                                id="btn-favorito-detalle"
                                class="btn-favorito-detalle {{ $esFavorito ? 'activo' : '' }}"
                                data-evento-id="{{ $evento->id }}"
                                data-favorito="{{ $esFavorito ? '1' : '0' }}"
                                aria-pressed="{{ $esFavorito ? 'true' : 'false' }}"
                                onclick="toggleFavoritoDetalle(event.currentTarget)">
                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                            <span id="btn-favorito-detalle-texto">{{ $esFavorito ? 'En favoritos' : 'Guardar en favoritos' }}</span>
                        </button>
                    @endauth

                    {{-- Enlace externo si lo hay --}}
                    @if ($evento->url_externa)
                        <a href="{{ $evento->url_externa }}"
                           target="_blank"
                           class="btn-secundario w-full mt-3 block text-center">
                            Ver en web oficial
                        </a>
                    @endif

                    {{-- Información de garantía --}}
                    <p class="text-center text-navy/40 text-xs mt-4">
                        🔒 Compra segura · Entrada con código QR
                    </p>

                </div>

                {{-- ── MAPA DE LEAFLET ── --}}
                @if ($evento->latitud && $evento->longitud)
                    <div class="ficha-mapa">
                        <h3 class="font-bold text-navy mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-morado-vibez" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            Ubicación
                        </h3>

                        {{-- Dirección textual --}}
                        @if ($evento->ubicacion_direccion)
                            <p class="text-navy/60 text-sm mb-3">{{ $evento->ubicacion_direccion }}</p>
                        @endif

                        {{-- Contenedor del mapa — Leaflet se inicializa en el script --}}
                        <div id="mapa-evento"></div>

                        {{-- Enlace a Google Maps --}}
                        <a href="https://www.google.com/maps?q={{ $evento->latitud }},{{ $evento->longitud }}"
                           target="_blank"
                           class="texto-enlace text-sm mt-3 inline-block">
                            Abrir en Google Maps →
                        </a>
                    </div>
                @else
                    {{-- Mensaje si el evento no tiene coordenadas --}}
                    <div class="ficha-mapa text-center py-6">
                        <p class="text-navy/40 text-sm">📍 Ubicación no disponible</p>
                    </div>
                @endif

            </div>
        </div>{{-- fin columna derecha --}}

    </div>
</div>

{{-- ════════════════════════════════════════════════════
     MODAL DE COMPRA DE ENTRADAS
════════════════════════════════════════════════════ --}}
@auth
@if(!Auth::user()->isAdmin())
<div id="modal-compra"
     style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(15,23,42,0.65);backdrop-filter:blur(4px);"
     onclick="if(event.target===this)cerrarModalCompra()">
    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
                background:#fff;border-radius:24px;padding:2rem;width:calc(100% - 2rem);max-width:440px;
                box-shadow:0 25px 60px rgba(124,58,237,0.25);">

        {{-- Cabecera --}}
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
            <h2 style="font-weight:900;font-size:1.25rem;color:var(--navy,#0f172a);margin:0">
                Comprar entradas
            </h2>
            <button onclick="cerrarModalCompra()"
                    style="background:none;border:none;cursor:pointer;font-size:1.75rem;color:#94a3b8;line-height:1">×</button>
        </div>

        {{-- Resumen del evento --}}
        <div style="background:#f0ecff;border-radius:12px;padding:1rem;margin-bottom:1.5rem">
            <p style="font-weight:700;color:var(--navy,#0f172a);margin:0;font-size:0.95rem">{{ $evento->titulo }}</p>
            <p style="color:#7c3aed;font-size:0.85rem;margin:4px 0 0">
                {{ \Carbon\Carbon::parse($evento->fecha_inicio)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
            </p>
        </div>

        {{-- Selector de cantidad --}}
        <div style="margin-bottom:1.5rem">
            <label style="font-weight:600;font-size:0.875rem;color:var(--navy,#0f172a);display:block;margin-bottom:10px">
                Cantidad de entradas
            </label>
            <div style="display:flex;align-items:center;gap:16px">
                <button type="button" onclick="cambiarCantidad(-1)"
                        style="width:40px;height:40px;border-radius:50%;border:2px solid #7c3aed;
                               background:#fff;color:#7c3aed;font-size:1.25rem;cursor:pointer;
                               font-weight:700;display:flex;align-items:center;justify-content:center;
                               flex-shrink:0">−</button>
                <span id="modal-cantidad"
                      style="font-size:1.5rem;font-weight:900;color:var(--navy,#0f172a);
                             min-width:40px;text-align:center">1</span>
                <button type="button" onclick="cambiarCantidad(1)"
                        style="width:40px;height:40px;border-radius:50%;border:2px solid #7c3aed;
                               background:#fff;color:#7c3aed;font-size:1.25rem;cursor:pointer;
                               font-weight:700;display:flex;align-items:center;justify-content:center;
                               flex-shrink:0">+</button>
            </div>
        </div>

        {{-- Total --}}
        <div style="display:flex;justify-content:space-between;align-items:center;
                    border-top:1px solid #ede9fe;padding-top:1rem;margin-bottom:1.5rem">
            <span style="font-weight:600;color:#64748b;font-size:0.9rem">Total</span>
            <span id="modal-total" class="text-gradient"
                  style="font-size:1.75rem;font-weight:900">
                @if($evento->es_gratuito) Gratis
                @else {{ number_format($evento->precio_base, 2) }} €
                @endif
            </span>
        </div>

        {{-- Zona de error --}}
        <div id="modal-error"
             style="display:none;background:#fef2f2;border:1px solid #fca5a5;color:#dc2626;
                    border-radius:8px;padding:10px 14px;font-size:0.875rem;margin-bottom:1rem"></div>

        {{-- Botón confirmar --}}
        <button id="modal-btn-comprar"
                onclick="confirmarCompra()"
                class="btn-comprar w-full">
            {{ $evento->es_gratuito ? 'Reservar gratis' : 'Confirmar compra' }}
        </button>

        <p style="text-align:center;font-size:0.75rem;color:#94a3b8;margin-top:12px;margin-bottom:0">
            🔒 Transacción segura · Recibirás tu QR al instante
        </p>
    </div>
</div>
@endif
@endauth

@endsection

{{-- ════════════════════════════════════════════════════
     SCRIPTS DEL DETALLE — Leaflet y acción de compra
════════════════════════════════════════════════════ --}}
@push('scripts')
{{-- Favoritos: necesario para el botón "Guardar en favoritos" --}}
<script src="{{ asset('js/favoritos.js') }}"></script>

{{-- Leaflet: librería de mapas interactivos, gratuita y sin API key --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
/* ============================================================
   DATOS DEL EVENTO — puente PHP → JavaScript
   Se definen aquí porque PHP (Blade) es el único que conoce
   estos valores en tiempo de servidor.
   ============================================================ */
/* ============================================================
   MAPA LEAFLET
   ============================================================ */

/**
 * Inicializa el mapa de Leaflet centrado en la ubicación del evento.
 * Añade un marcador con el icono de VIBEZ y un popup con el nombre del lugar.
 *
 * @param {number} latitud         - Latitud del evento (de la base de datos)
 * @param {number} longitud        - Longitud del evento (de la base de datos)
 * @param {string} nombreUbicacion - Nombre del lugar que aparece en el popup
 */
function inicializarMapa(latitud, longitud, nombreUbicacion) {
    /* Zoom 15 corresponde al nivel de calle */
    var mapa = L.map('mapa-evento').setView([latitud, longitud], 15);

    /* Capa de tiles: OpenStreetMap — gratuito, sin necesidad de API key */
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom:     19,
        attribution: '© <a href="https://openstreetmap.org">OpenStreetMap</a>'
    }).addTo(mapa);

    /* Icono personalizado con degradado morado de VIBEZ */
    var iconoVibez = L.divIcon({
        html:        '<div style="background:linear-gradient(135deg,#7c3aed,#a855f7);width:32px;height:32px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.3)"></div>',
        iconSize:    [32, 32],
        iconAnchor:  [16, 32],
        popupAnchor: [0, -35],
        className:   ''
    });

    /* Marcador en la ubicación + popup con nombre del sitio */
    L.marker([latitud, longitud], { icon: iconoVibez })
        .addTo(mapa)
        .bindPopup('<strong>' + nombreUbicacion + '</strong>')
        .openPopup();
}

/* Llamada directa al cargar el script — el DOM ya está listo porque el script va al final del body */
@if ($evento->latitud && $evento->longitud)
inicializarMapa(
    {{ $evento->latitud }},
    {{ $evento->longitud }},
    '{{ addslashes($evento->ubicacion_nombre ?? 'Ubicación del evento') }}'
);
@endif

// Datos del evento pasados desde PHP
const EVENTO_ID   = {{ $evento->id }};
const PRECIO_BASE = {{ $evento->precio_base ?? 0 }};
const ES_GRATUITO = {{ $evento->es_gratuito ? 'true' : 'false' }};
const AFORO_LIBRE = {{ $evento->aforo_maximo ? $evento->aforo_maximo - $evento->aforo_actual : 9999 }};

let cantidadModal = 1;

/**
 * Abre el modal de compra de entradas.
 * Si el usuario no está autenticado, lo redirige al login.
 */
function abrirCompra() {
    @guest
    window.location.href = '{{ route('login') }}';
    return;
    @endguest

    /* Reiniciar estado del modal antes de mostrarlo */
    cantidadModal = 1;
    actualizarModalTotal();
    document.getElementById('modal-error').style.display        = 'none';
    document.getElementById('modal-btn-comprar').disabled       = false;
    document.getElementById('modal-btn-comprar').textContent    = ES_GRATUITO ? 'Reservar gratis' : 'Confirmar compra';
    document.getElementById('modal-compra').style.display       = 'block';
    document.body.style.overflow = 'hidden';
}

/**
 * Cierra el modal de compra y restaura el scroll de la página.
 */
function cerrarModalCompra() {
    document.getElementById('modal-compra').style.display = 'none';
    document.body.style.overflow = '';
}

/**
 * Aumenta o reduce la cantidad de entradas.
 * Límite: entre 1 y 10, sin superar el aforo disponible.
 *
 * @param {number} cambio - +1 para aumentar, -1 para reducir
 */
function cambiarCantidad(cambio) {
    var nuevaCantidad = cantidadModal + cambio;
    if (nuevaCantidad < 1 || nuevaCantidad > 10 || nuevaCantidad > AFORO_LIBRE) return;
    cantidadModal = nuevaCantidad;
    actualizarModalTotal();
}

/**
 * Actualiza el número de entradas y el precio total en el modal.
 */
function actualizarModalTotal() {
    document.getElementById('modal-cantidad').textContent = cantidadModal;
    if (!ES_GRATUITO) {
        var total = (PRECIO_BASE * cantidadModal).toFixed(2).replace('.', ',');
        document.getElementById('modal-total').textContent = total + ' €';
    }
}

/**
 * Envía la petición de compra al servidor mediante fetch y gestiona la respuesta.
 * En caso de éxito redirige a la confirmación; si falla muestra el error en el modal.
 */
function confirmarCompra() {
    var botonComprar = document.getElementById('modal-btn-comprar');
    var zonaError    = document.getElementById('modal-error');
    var csrf         = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    botonComprar.disabled    = true;
    botonComprar.textContent = 'Procesando...';
    zonaError.style.display  = 'none';

    fetch('/api/entradas/comprar', {
        method:  'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept':       'application/json',
            'X-CSRF-TOKEN': csrf,
        },
        body: JSON.stringify({ evento_id: EVENTO_ID, cantidad: cantidadModal }),
    })
    .then(function (respuesta) { return respuesta.json(); })
    .then(function (datos) {
        if (datos.success) {
            botonComprar.textContent       = '¡Redirigiendo...';
            document.body.style.transition = 'opacity 0.3s';
            document.body.style.opacity    = '0';
            setTimeout(function () { window.location.href = datos.redirect; }, 320);
        } else {
            zonaError.textContent    = datos.message || 'Error al procesar la compra.';
            zonaError.style.display  = 'block';
            botonComprar.disabled    = false;
            botonComprar.textContent = ES_GRATUITO ? 'Reservar gratis' : 'Confirmar compra';
        }
    })
    .catch(function () {
        zonaError.textContent    = 'Error de conexión. Inténtalo de nuevo.';
        zonaError.style.display  = 'block';
        botonComprar.disabled    = false;
        botonComprar.textContent = ES_GRATUITO ? 'Reservar gratis' : 'Confirmar compra';
    });
}
</script>
@endpush

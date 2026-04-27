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

                    {{-- Botón principal de acción --}}
                    <button class="btn-comprar w-full"
                            onclick="abrirCompra({{ $evento->id }})">
                        {{ $evento->es_gratuito ? 'Reservar entrada gratuita' : 'Comprar entrada' }}
                    </button>

                    @auth
                        <button type="button"
                                id="btn-favorito-detalle"
                                class="btn-favorito-detalle {{ $esFavorito ? 'activo' : '' }}"
                                data-evento-id="{{ $evento->id }}"
                                data-favorito="{{ $esFavorito ? '1' : '0' }}"
                                aria-pressed="{{ $esFavorito ? 'true' : 'false' }}"
                                onclick="toggleFavoritoDetalle(event.currentTarget)">
                            <svg viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                                <path d="M8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-1.066 2.08-.163 4.905 2.54 7.568L8 15l4.06-4.379c2.703-2.663 3.606-5.488 2.54-7.568-1.114-2.175-4.2-2.772-5.883-1.042L8 2.748z"/>
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
@endauth

@endsection

{{-- ════════════════════════════════════════════════════
     SCRIPTS DEL DETALLE — Leaflet y acción de compra
════════════════════════════════════════════════════ --}}
@push('scripts')
{{-- Cargar el archivo de favoritos para que toggleFavoritoDetalle esté disponible --}}
<script src="{{ asset('js/favoritos.js') }}"></script>

{{-- Librería de mapas Leaflet (gratuita, sin API key) --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
/**
 * Inicializar el mapa de Leaflet con la ubicación del evento.
 * Se ejecuta directamente (sin event listeners) cuando el script carga.
 *
 * @param {number} latitud         - Latitud del evento en BD
 * @param {number} longitud        - Longitud del evento en BD
 * @param {string} nombreUbicacion - Nombre del lugar para el popup
 */
function inicializarMapa(latitud, longitud, nombreUbicacion) {
    // Crear el mapa centrado en las coordenadas del evento, zoom 15 = nivel calle
    var mapa = L.map('mapa-evento').setView([latitud, longitud], 15);

    // Añadir capa de tiles de OpenStreetMap (gratuita, sin necesidad de API key)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© <a href="https://openstreetmap.org">OpenStreetMap</a>'
    }).addTo(mapa);

    // Icono personalizado con los colores de VIBEZ
    var iconoVibez = L.divIcon({
        html: '<div style="background:linear-gradient(135deg,#7c3aed,#a855f7);width:32px;height:32px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.3)"></div>',
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -35],
        className: ''
    });

    // Añadir marcador en la ubicación y abrir el popup con el nombre del lugar
    L.marker([latitud, longitud], { icon: iconoVibez })
        .addTo(mapa)
        .bindPopup('<strong>' + nombreUbicacion + '</strong>')
        .openPopup();
}

// Llamar a la función directamente — los datos vienen del blade (PHP → JS)
@if ($evento->latitud && $evento->longitud)
    inicializarMapa(
        {{ $evento->latitud }},
        {{ $evento->longitud }},
        '{{ addslashes($evento->ubicacion_nombre ?? 'Ubicación del evento') }}'
    );
@endif

/**
 * Gestiona el clic en el botón "Comprar entrada".
 * Por ahora muestra un SweetAlert — aquí se integrará el flujo de pago.
 * @param {number} eventoId - ID del evento seleccionado
 */
function abrirCompra(eventoId) {
    // TODO: Integrar con el sistema de pedidos y pagos de VIBEZ
    alert('Próximamente: flujo de compra para el evento #' + eventoId);
// Datos del evento pasados desde PHP
const EVENTO_ID   = {{ $evento->id }};
const PRECIO_BASE = {{ $evento->precio_base ?? 0 }};
const ES_GRATUITO = {{ $evento->es_gratuito ? 'true' : 'false' }};
const AFORO_LIBRE = {{ $evento->aforo_maximo ? $evento->aforo_maximo - $evento->aforo_actual : 9999 }};

let modalCantidad = 1;

function abrirCompra() {
    @guest
    window.location.href = '{{ route('login') }}';
    return;
    @endguest

    modalCantidad = 1;
    actualizarModalTotal();
    document.getElementById('modal-error').style.display = 'none';
    document.getElementById('modal-btn-comprar').disabled = false;
    document.getElementById('modal-btn-comprar').textContent = ES_GRATUITO ? 'Reservar gratis' : 'Confirmar compra';
    document.getElementById('modal-compra').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function cerrarModalCompra() {
    document.getElementById('modal-compra').style.display = 'none';
    document.body.style.overflow = '';
}

function cambiarCantidad(delta) {
    const nuevo = modalCantidad + delta;
    if (nuevo < 1 || nuevo > 10 || nuevo > AFORO_LIBRE) return;
    modalCantidad = nuevo;
    actualizarModalTotal();
}

function actualizarModalTotal() {
    document.getElementById('modal-cantidad').textContent = modalCantidad;
    if (!ES_GRATUITO) {
        const total = (PRECIO_BASE * modalCantidad).toFixed(2).replace('.', ',');
        document.getElementById('modal-total').textContent = total + ' €';
    }
}

async function confirmarCompra() {
    const btn   = document.getElementById('modal-btn-comprar');
    const error = document.getElementById('modal-error');
    const csrf  = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    btn.disabled    = true;
    btn.textContent = 'Procesando...';
    error.style.display = 'none';

    try {
        const res = await fetch('/api/entradas/comprar', {
            method:  'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept':       'application/json',
                'X-CSRF-TOKEN': csrf,
            },
            body: JSON.stringify({ evento_id: EVENTO_ID, cantidad: modalCantidad }),
        });

        const data = await res.json();

        if (data.success) {
            btn.textContent = '¡Redirigiendo...';
            document.body.style.transition = 'opacity 0.3s';
            document.body.style.opacity    = '0';
            setTimeout(() => { window.location.href = data.redirect; }, 320);
        } else {
            error.textContent   = data.message || 'Error al procesar la compra.';
            error.style.display = 'block';
            btn.disabled        = false;
            btn.textContent     = ES_GRATUITO ? 'Reservar gratis' : 'Confirmar compra';
        }
    } catch (e) {
        error.textContent   = 'Error de conexión. Inténtalo de nuevo.';
        error.style.display = 'block';
        btn.disabled        = false;
        btn.textContent     = ES_GRATUITO ? 'Reservar gratis' : 'Confirmar compra';
    }
}
</script>
@endpush

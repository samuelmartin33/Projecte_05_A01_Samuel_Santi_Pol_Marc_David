@extends('layouts.app')

@section('titulo', $evento->titulo)

@push('estilos')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/eventos-detalle.css') }}">
@endpush

@section('contenido')

{{-- ════════════════════════════════════════════════════
     HERO EDITORIAL — imagen de fondo + overlay ink
════════════════════════════════════════════════════ --}}
<div class="relative overflow-hidden bg-ink" style="min-height:420px;">

    {{-- Imagen de fondo con overlay --}}
    <div style="position:absolute;inset:0;background-image:url('{{ $evento->url_portada }}');background-size:cover;background-position:center;opacity:0.25;"></div>
    <div style="position:absolute;inset:0;background:linear-gradient(to bottom,rgba(27,20,48,0.6) 0%,rgba(27,20,48,0.95) 100%);"></div>

    {{-- Dot grid --}}
    <div style="position:absolute;inset:0;background-image:radial-gradient(circle,rgba(139,120,204,0.1) 1.5px,transparent 1.5px);background-size:28px 28px;pointer-events:none;"></div>

    <div class="max-w-7xl mx-auto px-6 sm:px-10 py-14 relative" style="z-index:1">

        {{-- Volver --}}
        <a href="{{ route('home') }}"
           class="inline-flex items-center gap-2 font-mono text-xs uppercase tracking-widest text-paper/50 hover:text-paper transition-colors duration-100 mb-8">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver
        </a>

        {{-- Categorías --}}
        @if($evento->categorias->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach($evento->categorias as $cat)
                    <span class="inline-block font-mono text-xs uppercase tracking-widest text-lilac border border-lilac/40 px-3 py-1">
                        {{ $cat->nombre }}
                    </span>
                @endforeach
            </div>
        @endif

        {{-- Título --}}
        <h1 class="font-display font-black uppercase text-paper tracking-tightest leading-[0.88] max-w-4xl"
            style="font-size:clamp(2rem,6vw,5rem)">
            {{ $evento->titulo }}
        </h1>

        {{-- Metadatos --}}
        <div class="flex flex-wrap items-center gap-6 mt-6">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-paper/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="font-mono text-xs uppercase tracking-widest text-paper/60">
                    {{ \Carbon\Carbon::parse($evento->fecha_inicio)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                    · {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('H:i') }}h
                </span>
            </div>
            @if($evento->ubicacion_nombre)
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-paper/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    </svg>
                    <span class="font-mono text-xs uppercase tracking-widest text-paper/60">{{ $evento->ubicacion_nombre }}</span>
                </div>
            @endif
            <div class="flex items-center gap-2">
                <span class="font-mono text-xs uppercase tracking-widest text-lilac font-bold">
                    {{ $evento->precio_formateado }}
                </span>
            </div>
        </div>

        {{-- Promotora en el hero --}}
        @php $empresaHero = $evento->organizador?->empresa; @endphp
        @if($empresaHero)
            <div class="flex items-center gap-3 mt-5">
                {{-- Logo / inicial --}}
                <div class="w-9 h-9 rounded-full flex-shrink-0 flex items-center justify-center overflow-hidden"
                     style="background:linear-gradient(135deg,#4e3a96,#7c3aed)">
                    @if($empresaHero->logo_url)
                        <img src="{{ $empresaHero->logo_url }}" alt="{{ $empresaHero->nombre_empresa }}"
                             class="w-full h-full object-cover">
                    @else
                        <span class="font-bold text-white text-sm">{{ strtoupper(substr($empresaHero->nombre_empresa,0,1)) }}</span>
                    @endif
                </div>
                <span class="font-mono text-xs uppercase tracking-widest text-paper/70">{{ $empresaHero->nombre_empresa }}</span>

                @auth
                    @if(!Auth::user()->isAdmin() && !Auth::user()->isEmpresa())
                        <button class="btn-seguir-promotora {{ $siguePromotor ? 'siguiendo' : '' }}"
                                data-empresa-id="{{ $empresaHero->id }}"
                                onclick="toggleSeguirPromotora(this)"
                                style="margin-left:0.25rem">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <line x1="19" y1="8" x2="19" y2="14"/>
                                <line x1="22" y1="11" x2="16" y2="11"/>
                            </svg>
                            <span class="btn-seguir-texto">{{ $siguePromotor ? 'Siguiendo' : 'Seguir' }}</span>
                        </button>
                    @endif
                @endauth
            </div>
        @endif

    </div>
</div>

{{-- ════════════════════════════════════════════════════
     CUERPO DEL DETALLE — 2 columnas
════════════════════════════════════════════════════ --}}
<div class="eventos-detalle-wrap">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        {{-- ─── COLUMNA IZQUIERDA ─── --}}
        <div class="lg:col-span-2 space-y-10">

            <section class="seccion-detalle">
                <h2 class="seccion-titulo">Sobre el evento</h2>
                <p class="text-navy/80 leading-relaxed text-base">
                    {{ $evento->descripcion ?? 'No hay descripción disponible.' }}
                </p>
            </section>

            <section class="seccion-detalle">
                <h2 class="seccion-titulo">Información adicional</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @if($evento->aforo_maximo)
                        <div class="ficha-dato">
                            <span class="ficha-dato-label">Aforo máximo</span>
                            <span class="ficha-dato-valor">{{ number_format($evento->aforo_maximo) }} personas</span>
                        </div>
                    @endif
                    @if($evento->aforo_maximo)
                        @php $disponibles = $evento->aforo_maximo - $evento->aforo_actual; @endphp
                        <div class="ficha-dato">
                            <span class="ficha-dato-label">Disponibles</span>
                            <span class="ficha-dato-valor {{ $disponibles < 50 ? 'text-red-600' : 'text-green-600' }}">
                                {{ number_format($disponibles) }}
                            </span>
                        </div>
                    @endif
                    @if($evento->edad_minima)
                        <div class="ficha-dato">
                            <span class="ficha-dato-label">Edad mínima</span>
                            <span class="ficha-dato-valor">+{{ $evento->edad_minima }}</span>
                        </div>
                    @endif
                    @if($evento->fecha_fin)
                        <div class="ficha-dato">
                            <span class="ficha-dato-label">Finaliza</span>
                            <span class="ficha-dato-valor">{{ \Carbon\Carbon::parse($evento->fecha_fin)->format('H:i') }}h</span>
                        </div>
                    @endif
                </div>
            </section>

            @if($evento->organizador?->empresa)
                <section class="seccion-detalle">
                    <h2 class="seccion-titulo">Organiza</h2>
                    <div class="card-organizador-detalle">
                        <div class="logo-empresa">
                            @if($evento->organizador->empresa->logo_url)
                                <img src="{{ $evento->organizador->empresa->logo_url }}" alt="{{ $evento->organizador->empresa->nombre_empresa }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-white font-black text-xl">{{ strtoupper(substr($evento->organizador->empresa->nombre_empresa, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-navy text-lg">{{ $evento->organizador->empresa->nombre_empresa }}</p>
                            @if($evento->organizador->empresa->descripcion)
                                <p class="text-navy/60 text-sm mt-1 line-clamp-2">{{ $evento->organizador->empresa->descripcion }}</p>
                            @endif
                            @if($evento->organizador->empresa->sitio_web)
                                <a href="{{ $evento->organizador->empresa->sitio_web }}" target="_blank" class="texto-enlace text-sm mt-2 inline-block">Visitar web →</a>
                            @endif
                        </div>
                        @auth
                        @if(!Auth::user()->isAdmin() && !Auth::user()->isEmpresa())
                        <button id="btn-seguir-promotora"
                                class="btn-seguir-promotora {{ $siguePromotor ? 'siguiendo' : '' }}"
                                data-empresa-id="{{ $evento->organizador->empresa->id }}"
                                onclick="toggleSeguirPromotora(this)">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                            <span class="btn-seguir-texto">{{ $siguePromotor ? 'Siguiendo' : 'Seguir' }}</span>
                        </button>
                        @endif
                        @endauth
                    </div>
                </section>
            @endif

            @if($evento->imagenes->where('es_portada', 0)->count() > 0)
                <section class="seccion-detalle">
                    <h2 class="seccion-titulo">Galería</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($evento->imagenes->where('es_portada', 0) as $imagen)
                            <div class="overflow-hidden aspect-video border border-ink/10">
                                <img src="{{ $imagen->imagen_url }}"
                                     alt="{{ $imagen->descripcion ?? $evento->titulo }}"
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                     onerror="this.parentElement.remove()">
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

        {{-- ════════════════════════
             CUPONES DE LA EMPRESA
        ════════════════════════ --}}
        @if($cupones->isNotEmpty())
            <section class="seccion-detalle">
                <h2 class="seccion-titulo">Cupones de descuento</h2>
                <p class="text-navy/60 text-sm mb-4">
                    Usa uno de estos códigos al comprar tu entrada para obtener descuento.
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($cupones as $cupon)
                        <div class="rounded-xl p-4" style="background:rgba(124,58,237,0.08);border:1px solid rgba(124,58,237,0.25);">
                            {{-- Código del cupón --}}
                            <div class="flex items-center justify-between mb-2">
                                <code id="codigo-cupon-{{ $cupon->id }}"
                                      class="font-mono font-black text-lg tracking-widest"
                                      style="color:#c084fc;">
                                    {{ $cupon->codigo }}
                                </code>
                                <button onclick="copiarCodigoCupon('{{ $cupon->codigo }}', {{ $cupon->id }})"
                                        id="btn-copiar-{{ $cupon->id }}"
                                        class="text-xs font-bold px-3 py-1 rounded-lg transition-colors"
                                        style="background:rgba(168,85,247,0.2);color:#c084fc;border:1px solid rgba(168,85,247,0.3);">
                                    Copiar
                                </button>
                            </div>
                            {{-- Descuento --}}
                            <p class="font-black text-2xl mb-1" style="color:#a855f7;">
                                {{ number_format($cupon->valor_descuento, 0) }}% de descuento
                            </p>
                            {{-- Descripción --}}
                            @if($cupon->descripcion)
                                <p class="text-sm text-navy/60">{{ $cupon->descripcion }}</p>
                            @endif
                            {{-- Validez --}}
                            <p class="text-xs text-navy/40 mt-2">
                                Válido hasta {{ $cupon->fecha_fin->format('d/m/Y') }}
                                @if($cupon->usos_restantes !== -1)
                                    · {{ $cupon->usos_restantes }} usos restantes
                                @endif
                            </p>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        </div>

        {{-- ─── COLUMNA DERECHA ─── --}}
        <div class="lg:col-span-1">
            <div class="lg:sticky lg:top-24 space-y-6">

                <div class="ficha-compra">
                    <div class="text-center mb-6">
                        <p class="text-navy/50 text-sm uppercase tracking-widest font-semibold mb-1">Precio</p>
                        <p class="text-4xl font-black text-gradient">{{ $evento->precio_formateado }}</p>
                        @if(!$evento->es_gratuito)
                            <p class="text-navy/40 text-xs mt-1">por persona · IVA incluido</p>
                        @endif
                    </div>

                    @if($evento->aforo_maximo)
                        @php $porcentajeOcupacion = ($evento->aforo_actual / $evento->aforo_maximo) * 100; @endphp
                        <div class="mb-6">
                            <div class="flex justify-between text-xs text-navy/50 mb-1">
                                <span>{{ number_format($evento->aforo_maximo - $evento->aforo_actual) }} entradas disponibles</span>
                                <span>{{ round($porcentajeOcupacion) }}% ocupado</span>
                            </div>
                            <div class="barra-aforo-fondo">
                                <div class="barra-aforo-relleno {{ $porcentajeOcupacion > 80 ? 'barra-aforo-critico' : '' }}"
                                     style="width:{{ min($porcentajeOcupacion, 100) }}%"></div>
                            </div>
                        </div>
                    @endif

                    @if(!Auth::check() || !Auth::user()->isAdmin())
                    <button class="btn-comprar w-full" onclick="abrirCompra()">
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

                    @if($evento->url_externa)
                        <a href="{{ $evento->url_externa }}" target="_blank" class="btn-secundario w-full mt-3 block text-center">Ver en web oficial</a>
                    @endif

                    @auth
                        @if(!Auth::user()->isAdmin() && !Auth::user()->isEmpresa())
                            @php $empresaSidebar = $evento->organizador?->empresa; @endphp
                            @if($empresaSidebar)
                                <button class="btn-seguir-promotora w-full mt-3 {{ $siguePromotor ? 'siguiendo' : '' }}"
                                        data-empresa-id="{{ $empresaSidebar->id }}"
                                        onclick="toggleSeguirPromotora(this)">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                    </svg>
                                    <span class="btn-seguir-texto">{{ $siguePromotor ? 'Siguiendo a ' : 'Seguir a ' }}{{ $empresaSidebar->nombre_empresa }}</span>
                                </button>
                            @endif
                        @endif
                    @endauth

                    <p class="text-center text-navy/40 text-xs mt-4"> Compra segura · Entrada con código QR</p>
                </div>

                @if($evento->latitud && $evento->longitud)
                    <div class="ficha-mapa">
                        <h3 class="font-bold text-navy mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-morado-vibez" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            Ubicación
                        </h3>
                        @if($evento->ubicacion_direccion)
                            <p class="text-navy/60 text-sm mb-3">{{ $evento->ubicacion_direccion }}</p>
                        @endif
                        <div id="mapa-evento"></div>
                        <a href="https://www.google.com/maps?q={{ $evento->latitud }},{{ $evento->longitud }}"
                           target="_blank" class="texto-enlace text-sm mt-3 inline-block">
                            Abrir en Google Maps →
                        </a>
                    </div>
                @else
                    <div class="ficha-mapa text-center py-6">
                        <p class="text-navy/40 text-sm"> Ubicación no disponible</p>
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>
</div>

{{-- ════════════════════════════════════════════════════
     MODAL DE COMPRA (estructura funcional intacta)
════════════════════════════════════════════════════ --}}
@auth
@if(!Auth::user()->isAdmin())
<div id="modal-compra"
     style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(7,6,12,0.80);backdrop-filter:blur(4px);"
     onclick="if(event.target===this)cerrarModalCompra()">
    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
                background:#0d0a18;padding:2rem;width:calc(100% - 2rem);max-width:440px;
                box-shadow:0 25px 60px rgba(0,0,0,0.55),0 0 0 1px rgba(124,58,237,0.2);
                border:1px solid rgba(245,241,234,0.10);">

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
            <h2 style="font-family:'DM Sans',sans-serif;font-weight:900;font-size:1.25rem;color:#f5f1ea;margin:0;text-transform:uppercase;letter-spacing:-0.02em;">
                Comprar entradas
            </h2>
            <button onclick="cerrarModalCompra()"
                    style="background:none;border:none;cursor:pointer;font-size:1.75rem;color:rgba(245,241,234,0.4);line-height:1">×</button>
        </div>

        <div style="background:rgba(124,58,237,0.15);padding:1rem;margin-bottom:1.5rem;border-left:3px solid #a855f7;">
            <p style="font-family:'DM Sans',sans-serif;font-weight:700;color:#f5f1ea;margin:0;font-size:0.95rem">{{ $evento->titulo }}</p>
            <p style="font-family:'Syne',sans-serif;color:#c084fc;font-size:0.8rem;margin:4px 0 0;text-transform:uppercase;letter-spacing:0.05em;">
                {{ \Carbon\Carbon::parse($evento->fecha_inicio)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
            </p>
        </div>

        <div style="margin-bottom:1.5rem">
            <label style="font-family:'Syne',sans-serif;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.1em;color:rgba(245,241,234,0.55);display:block;margin-bottom:10px">
                Cantidad de entradas
            </label>
            <div style="display:flex;align-items:center;gap:16px">
                <button type="button" onclick="cambiarCantidad(-1)"
                        style="width:40px;height:40px;border:2px solid rgba(124,58,237,0.5);background:rgba(124,58,237,0.15);color:#c084fc;font-size:1.25rem;cursor:pointer;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0">−</button>
                <span id="modal-cantidad" style="font-family:'DM Sans',sans-serif;font-size:1.5rem;font-weight:900;color:#f5f1ea;min-width:40px;text-align:center">1</span>
                <button type="button" onclick="cambiarCantidad(1)"
                        style="width:40px;height:40px;border:2px solid rgba(124,58,237,0.5);background:rgba(124,58,237,0.15);color:#c084fc;font-size:1.25rem;cursor:pointer;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0">+</button>
            </div>
        </div>

        <div style="display:flex;justify-content:space-between;align-items:center;border-top:1px solid rgba(255,255,255,0.08);padding-top:1rem;margin-bottom:1.5rem">
            <span style="font-family:'Syne',sans-serif;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.1em;color:rgba(245,241,234,0.4);">Total</span>
            <span id="modal-total" class="text-gradient" style="font-family:'DM Sans',sans-serif;font-size:1.75rem;font-weight:900">
                @if($evento->es_gratuito) Gratis
                @else {{ number_format($evento->precio_base, 2) }} €
                @endif
            </span>
        </div>

        <div id="modal-error" style="display:none;background:rgba(220,38,38,0.12);border:1px solid rgba(220,38,38,0.35);color:#f87171;padding:10px 14px;font-family:'Syne',sans-serif;font-size:0.875rem;margin-bottom:1rem"></div>

        <button id="modal-btn-comprar" onclick="confirmarCompra()" class="btn-comprar w-full">
            {{ $evento->es_gratuito ? 'Reservar gratis' : 'Confirmar compra' }}
        </button>

        <p style="text-align:center;font-family:'Syne',sans-serif;font-size:0.7rem;color:rgba(245,241,234,0.3);margin-top:12px;margin-bottom:0;text-transform:uppercase;letter-spacing:0.05em;">
             Transacción segura · Recibirás tu QR al instante
        </p>
    </div>
</div>
@endif
@endauth

@endsection

@push('estilos')
<style>
.btn-seguir-promotora {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border: 1.5px solid rgba(168,85,247,0.6);
    background: transparent;
    color: #a855f7;
    font-family: 'Syne', sans-serif;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    cursor: pointer;
    transition: background 0.18s, color 0.18s, border-color 0.18s;
    flex-shrink: 0;
    white-space: nowrap;
}
.btn-seguir-promotora:hover {
    background: rgba(168,85,247,0.15);
}
.btn-seguir-promotora.siguiendo {
    background: rgba(168,85,247,0.2);
    border-color: #a855f7;
    color: #e9d5ff;
}
.btn-seguir-promotora.cargando {
    opacity: 0.6;
    pointer-events: none;
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/favoritos.js') }}"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
window.eventoData = {
    id:              {{ $evento->id }},
    precioBase:      {{ $evento->precio_base ?? 0 }},
    esGratuito:      {{ $evento->es_gratuito ? 'true' : 'false' }},
    aforoLibre:      {{ $evento->aforo_maximo ? $evento->aforo_maximo - $evento->aforo_actual : 9999 }},
    latitud:         {{ $evento->latitud ?? 'null' }},
    longitud:        {{ $evento->longitud ?? 'null' }},
    nombreUbicacion: '{{ addslashes($evento->ubicacion_nombre ?? 'Ubicación del evento') }}',
    loginUrl:        '{{ route('login') }}',
    guestRedirect:   {{ Auth::check() ? 'false' : 'true' }}
};
</script>
<script src="{{ asset('js/eventos-detalle.js') }}"></script>
<script>
async function toggleSeguirPromotora(btn) {
    const empresaId = btn.dataset.empresaId;
    btn.classList.add('cargando');
    try {
        const res = await fetch(`/api/seguimientos/${empresaId}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        });
        const data = await res.json();
        if (data.success) {
            const texto = btn.querySelector('.btn-seguir-texto');
            if (data.siguiendo) {
                btn.classList.add('siguiendo');
                texto.textContent = 'Siguiendo';
            } else {
                btn.classList.remove('siguiendo');
                texto.textContent = 'Seguir';
            }
        }
    } catch (e) {
        console.error('Error al seguir promotora', e);
    } finally {
        btn.classList.remove('cargando');
    }
}

// Copia el código del cupón al portapapeles y cambia el texto del botón
function copiarCodigoCupon(codigo, id) {
    navigator.clipboard.writeText(codigo).then(function () {
        var btn = document.getElementById('btn-copiar-' + id);
        var textoOriginal = btn.textContent;
        btn.textContent = '¡Copiado!';
        btn.style.background = 'rgba(74,222,128,0.2)';
        btn.style.color = '#4ade80';
        // Volver al estado original después de 2 segundos
        setTimeout(function () {
            btn.textContent = textoOriginal;
            btn.style.background = 'rgba(168,85,247,0.2)';
            btn.style.color = '#c084fc';
        }, 2000);
    });
}
</script>
@endpush

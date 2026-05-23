@extends('layouts.app')

@section('titulo', $evento->titulo)

@push('estilos')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/vibez-home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/eventos-detalle.css') }}">
@endpush

@section('content')

{{-- ── Mismo nav que el home ── --}}
@include('partials.home.nav')

{{-- ════════════════════════════════════════════════════
     HERO — imagen de fondo + overlay
════════════════════════════════════════════════════ --}}
<div style="position:relative;overflow:hidden;min-height:420px;background:#07060c;">

    {{-- Imagen de fondo --}}
    <div style="position:absolute;inset:0;background-image:url('{{ $evento->url_portada }}');background-size:cover;background-position:center;opacity:0.22;"></div>
    <div style="position:absolute;inset:0;background:linear-gradient(to bottom,rgba(7,6,12,0.5) 0%,rgba(7,6,12,0.96) 100%);"></div>
    <div style="position:absolute;inset:0;background-image:radial-gradient(circle,rgba(168,85,247,0.08) 1.5px,transparent 1.5px);background-size:28px 28px;pointer-events:none;"></div>

    <div style="max-width:1480px;margin:0 auto;padding:3.5rem 2rem 3rem;position:relative;z-index:1;">

        {{-- Volver --}}
        <a href="{{ route('home') }}"
           class="mono"
           style="display:inline-flex;align-items:center;gap:8px;font-size:10px;color:rgba(245,241,234,0.4);text-decoration:none;margin-bottom:2rem;transition:color 0.15s;"
           onmouseover="this.style.color='rgba(245,241,234,0.8)'"
           onmouseout="this.style.color='rgba(245,241,234,0.4)'">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver
        </a>

        {{-- Categoría --}}
        @if($evento->categoria)
            <div class="mono" style="font-size:10px;color:var(--magenta);margin-bottom:1rem;display:flex;align-items:center;gap:10px;">
                <span style="width:28px;height:1px;background:var(--magenta);display:inline-block;"></span>
                {{ $evento->categoria->nombre }}
            </div>
        @endif

        {{-- Título --}}
        <h1 class="display" style="font-size:clamp(2.5rem,7vw,5.5rem);color:var(--ink);margin:0 0 1.5rem;max-width:900px;">
            {{ $evento->titulo }}
        </h1>

        {{-- Metadatos --}}
        <div style="display:flex;flex-wrap:wrap;align-items:center;gap:1.5rem;">
            <div style="display:flex;align-items:center;gap:8px;">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:rgba(245,241,234,0.35);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="mono" style="font-size:10px;color:rgba(245,241,234,0.6);">
                    {{ \Carbon\Carbon::parse($evento->fecha_inicio)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                    · {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('H:i') }}h
                </span>
            </div>
            @if($evento->ubicacion_nombre)
                <div style="display:flex;align-items:center;gap:8px;">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:rgba(245,241,234,0.35);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    </svg>
                    <span class="mono" style="font-size:10px;color:rgba(245,241,234,0.6);">{{ $evento->ubicacion_nombre }}</span>
                </div>
            @endif
            <span class="display" style="font-size:1.5rem;color:var(--magenta);">{{ $evento->precio_formateado }}</span>
        </div>

    </div>
</div>

{{-- ════════════════════════════════════════════════════
     CUERPO — 2 columnas
════════════════════════════════════════════════════ --}}
<div style="background:radial-gradient(circle,rgba(124,58,237,0.08) 1.5px,transparent 1.5px),linear-gradient(160deg,#0d0820 0%,#130228 45%,#0d0820 100%);background-size:28px 28px,100% 100%;padding:4rem 0;">
<div style="max-width:1480px;margin:0 auto;padding:0 2rem;">
    <div style="display:grid;grid-template-columns:1fr 400px;gap:2.5rem;align-items:start;">

        {{-- ─── COLUMNA IZQUIERDA ─── --}}
        <div style="display:flex;flex-direction:column;gap:2rem;">

            {{-- Sobre el evento --}}
            <div class="vibe-card" style="padding:2rem;">
                <div class="mono" style="font-size:10px;color:var(--magenta);margin-bottom:1rem;padding-bottom:0.75rem;border-bottom:1px solid var(--line);">
                    <span style="width:20px;height:1px;background:var(--magenta);display:inline-block;margin-right:8px;vertical-align:middle;"></span>
                    Sobre el evento
                </div>
                <p style="font-family:'Archivo',sans-serif;font-size:15px;color:var(--ink-dim);line-height:1.7;">
                    {{ $evento->descripcion ?? 'No hay descripción disponible.' }}
                </p>
            </div>

            {{-- Información adicional --}}
            @if($evento->aforo_maximo || $evento->edad_minima || $evento->fecha_fin)
            <div class="vibe-card" style="padding:2rem;">
                <div class="mono" style="font-size:10px;color:var(--magenta);margin-bottom:1rem;padding-bottom:0.75rem;border-bottom:1px solid var(--line);">
                    <span style="width:20px;height:1px;background:var(--magenta);display:inline-block;margin-right:8px;vertical-align:middle;"></span>
                    Información adicional
                </div>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:1rem;">
                    @if($evento->aforo_maximo)
                        <div style="background:rgba(255,255,255,0.03);border:1px solid var(--line);padding:1rem;border-radius:8px;">
                            <div class="mono" style="font-size:9px;color:rgba(245,241,234,0.35);margin-bottom:4px;">Aforo máximo</div>
                            <div class="display" style="font-size:1.2rem;color:var(--ink);">{{ number_format($evento->aforo_maximo) }}</div>
                        </div>
                        @php $disponibles = $evento->aforo_maximo - $evento->aforo_actual; @endphp
                        <div style="background:rgba(255,255,255,0.03);border:1px solid var(--line);padding:1rem;border-radius:8px;">
                            <div class="mono" style="font-size:9px;color:rgba(245,241,234,0.35);margin-bottom:4px;">Disponibles</div>
                            <div class="display" style="font-size:1.2rem;color:{{ $disponibles < 50 ? '#ef4444' : '#22c55e' }};">{{ number_format($disponibles) }}</div>
                        </div>
                    @endif
                    @if($evento->edad_minima)
                        <div style="background:rgba(255,255,255,0.03);border:1px solid var(--line);padding:1rem;border-radius:8px;">
                            <div class="mono" style="font-size:9px;color:rgba(245,241,234,0.35);margin-bottom:4px;">Edad mínima</div>
                            <div class="display" style="font-size:1.2rem;color:var(--ink);">+{{ $evento->edad_minima }}</div>
                        </div>
                    @endif
                    @if($evento->fecha_fin)
                        <div style="background:rgba(255,255,255,0.03);border:1px solid var(--line);padding:1rem;border-radius:8px;">
                            <div class="mono" style="font-size:9px;color:rgba(245,241,234,0.35);margin-bottom:4px;">Finaliza</div>
                            <div class="display" style="font-size:1.2rem;color:var(--ink);">{{ \Carbon\Carbon::parse($evento->fecha_fin)->format('H:i') }}h</div>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Organizador --}}
            @if($evento->organizador?->empresa)
                <div class="vibe-card" style="padding:2rem;">
                    <div class="mono" style="font-size:10px;color:var(--magenta);margin-bottom:1rem;padding-bottom:0.75rem;border-bottom:1px solid var(--line);">
                        <span style="width:20px;height:1px;background:var(--magenta);display:inline-block;margin-right:8px;vertical-align:middle;"></span>
                        Organiza
                    </div>
                    <div style="display:flex;align-items:flex-start;gap:1rem;">
                        <div style="width:52px;height:52px;border-radius:10px;overflow:hidden;background:linear-gradient(135deg,#4e3a96,#7c3aed);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            @if($evento->organizador->empresa->logo_url)
                                <img src="{{ $evento->organizador->empresa->logo_url }}" alt="{{ $evento->organizador->empresa->nombre_empresa }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <span style="color:#fff;font-family:'Anton',sans-serif;font-size:22px;">{{ strtoupper(substr($evento->organizador->empresa->nombre_empresa, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div>
                            <p style="font-family:'Anton',sans-serif;font-size:1.1rem;color:var(--ink);text-transform:uppercase;margin:0 0 4px;">{{ $evento->organizador->empresa->nombre_empresa }}</p>
                            @if($evento->organizador->empresa->descripcion)
                                <p style="font-family:'Archivo',sans-serif;font-size:13px;color:var(--ink-dim);margin:0 0 8px;line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">{{ $evento->organizador->empresa->descripcion }}</p>
                            @endif
                            @if($evento->organizador->empresa->sitio_web)
                                <a href="{{ $evento->organizador->empresa->sitio_web }}" target="_blank"
                                   class="mono" style="font-size:10px;color:var(--magenta);text-decoration:none;">
                                    Visitar web →
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Galería --}}
            @if($evento->imagenes->where('es_portada', 0)->count() > 0)
                <div class="vibe-card" style="padding:2rem;">
                    <div class="mono" style="font-size:10px;color:var(--magenta);margin-bottom:1rem;padding-bottom:0.75rem;border-bottom:1px solid var(--line);">
                        <span style="width:20px;height:1px;background:var(--magenta);display:inline-block;margin-right:8px;vertical-align:middle;"></span>
                        Galería
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:10px;">
                        @foreach($evento->imagenes->where('es_portada', 0) as $imagen)
                            <div style="overflow:hidden;aspect-ratio:16/9;border:1px solid var(--line);border-radius:6px;">
                                <img src="{{ $imagen->imagen_url }}"
                                     alt="{{ $imagen->descripcion ?? $evento->titulo }}"
                                     style="width:100%;height:100%;object-fit:cover;transition:transform 0.3s;"
                                     onmouseover="this.style.transform='scale(1.05)'"
                                     onmouseout="this.style.transform='scale(1)'"
                                     onerror="this.parentElement.remove()">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        {{-- ─── COLUMNA DERECHA (sticky) ─── --}}
        <div style="position:sticky;top:90px;display:flex;flex-direction:column;gap:1.5rem;">

            {{-- Panel de compra --}}
            <div class="vibe-card" style="padding:2rem;">

                {{-- Precio --}}
                <div style="text-align:center;margin-bottom:1.5rem;padding-bottom:1.5rem;border-bottom:1px solid var(--line);">
                    <div class="mono" style="font-size:9px;color:rgba(245,241,234,0.4);margin-bottom:8px;">Precio por persona</div>
                    <div class="display" style="font-size:3rem;color:var(--magenta);">{{ $evento->precio_formateado }}</div>
                    @if(!$evento->es_gratuito)
                        <div class="mono" style="font-size:9px;color:rgba(245,241,234,0.3);margin-top:4px;">IVA incluido</div>
                    @endif
                </div>

                {{-- Barra de aforo --}}
                @if($evento->aforo_maximo)
                    @php $porcentajeOcupacion = ($evento->aforo_actual / $evento->aforo_maximo) * 100; @endphp
                    <div style="margin-bottom:1.5rem;">
                        <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                            <span class="mono" style="font-size:9px;color:rgba(245,241,234,0.45);">{{ number_format($evento->aforo_maximo - $evento->aforo_actual) }} entradas disponibles</span>
                            <span class="mono" style="font-size:9px;color:rgba(245,241,234,0.45);">{{ round($porcentajeOcupacion) }}% ocupado</span>
                        </div>
                        <div style="background:rgba(255,255,255,0.1);border-radius:999px;height:4px;overflow:hidden;">
                            <div style="height:100%;border-radius:999px;width:{{ min($porcentajeOcupacion, 100) }}%;background:{{ $porcentajeOcupacion > 80 ? 'linear-gradient(90deg,#ef4444,#f97316)' : 'linear-gradient(90deg,#7c3aed,#a855f7)' }};"></div>
                        </div>
                    </div>
                @endif

                {{-- Botón comprar --}}
                @if(!Auth::check())
                    <a href="{{ route('eventos.comprar', $evento->id) }}"
                       class="btn-primary"
                       style="display:block;width:100%;padding:14px;text-align:center;border-radius:999px;font-size:14px;text-decoration:none;margin-bottom:10px;">
                        {{ $evento->es_gratuito ? 'Reservar entrada gratuita' : 'Comprar entrada →' }}
                    </a>
                @elseif(!Auth::user()->isAdmin())
                    <a href="{{ route('eventos.comprar', $evento->id) }}"
                       class="btn-primary"
                       style="display:block;width:100%;padding:14px;text-align:center;border-radius:999px;font-size:14px;text-decoration:none;margin-bottom:10px;">
                        {{ $evento->es_gratuito ? 'Reservar entrada gratuita' : 'Comprar entrada →' }}
                    </a>
                @endif

                {{-- Favorito --}}
                @auth
                    <button type="button"
                            id="btn-favorito-detalle"
                            class="{{ $esFavorito ? 'activo' : '' }}"
                            data-evento-id="{{ $evento->id }}"
                            data-favorito="{{ $esFavorito ? '1' : '0' }}"
                            aria-pressed="{{ $esFavorito ? 'true' : 'false' }}"
                            onclick="toggleFavoritoDetalle(event.currentTarget)"
                            style="width:100%;display:flex;align-items:center;justify-content:center;gap:8px;padding:12px;border-radius:999px;border:1px solid rgba(245,241,234,0.15);background:rgba(255,255,255,0.04);color:rgba(192,132,252,0.9);font-family:'Archivo Narrow',sans-serif;font-size:11px;text-transform:uppercase;letter-spacing:0.1em;cursor:pointer;transition:all 0.2s;margin-bottom:10px;"
                            onmouseover="if(!this.classList.contains('activo')){this.style.borderColor='rgba(168,85,247,0.5)';}"
                            onmouseout="if(!this.classList.contains('activo')){this.style.borderColor='rgba(245,241,234,0.15)';}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                        <span id="btn-favorito-detalle-texto">{{ $esFavorito ? 'En favoritos' : 'Guardar en favoritos' }}</span>
                    </button>
                @endauth

                @if($evento->url_externa)
                    <a href="{{ $evento->url_externa }}" target="_blank"
                       style="display:block;width:100%;padding:11px;text-align:center;border-radius:999px;border:1px solid rgba(245,241,234,0.12);background:transparent;color:rgba(245,241,234,0.5);font-family:'Archivo Narrow',sans-serif;font-size:11px;text-transform:uppercase;letter-spacing:0.1em;text-decoration:none;transition:all 0.2s;"
                       onmouseover="this.style.borderColor='rgba(245,241,234,0.3)';this.style.color='rgba(245,241,234,0.8)'"
                       onmouseout="this.style.borderColor='rgba(245,241,234,0.12)';this.style.color='rgba(245,241,234,0.5)'">
                        Ver en web oficial
                    </a>
                @endif

                <p class="mono" style="text-align:center;font-size:9px;color:rgba(245,241,234,0.2);margin-top:1rem;">Compra segura · Entrada con código QR</p>
            </div>

            {{-- Mapa --}}
            @if($evento->latitud && $evento->longitud)
                <div class="vibe-card" style="padding:1.5rem;">
                    <div class="mono" style="font-size:10px;color:var(--magenta);margin-bottom:0.75rem;display:flex;align-items:center;gap:8px;">
                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                        Ubicación
                    </div>
                    @if($evento->ubicacion_direccion)
                        <p style="font-family:'Archivo',sans-serif;font-size:12px;color:var(--ink-dim);margin-bottom:0.75rem;">{{ $evento->ubicacion_direccion }}</p>
                    @endif
                    <div id="mapa-evento" style="height:220px;border-radius:8px;z-index:1;"></div>
                    <a href="https://www.google.com/maps?q={{ $evento->latitud }},{{ $evento->longitud }}"
                       target="_blank"
                       class="mono" style="font-size:9px;color:var(--magenta);text-decoration:none;display:inline-block;margin-top:10px;">
                        Abrir en Google Maps →
                    </a>
                </div>
            @else
                <div class="vibe-card" style="padding:1.5rem;text-align:center;">
                    <p class="mono" style="font-size:10px;color:rgba(245,241,234,0.3);">Ubicación no disponible</p>
                </div>
            @endif

        </div>

    </div>
</div>
</div>

{{-- ── Mismo footer que el home ── --}}
@include('partials.home.footer')

@endsection

@push('scripts')
<script src="{{ asset('js/favoritos.js') }}"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
window.eventoData = {
    latitud:         {{ $evento->latitud ?? 'null' }},
    longitud:        {{ $evento->longitud ?? 'null' }},
    nombreUbicacion: '{{ addslashes($evento->ubicacion_nombre ?? 'Ubicación del evento') }}',
};
</script>
<script src="{{ asset('js/eventos-detalle.js') }}"></script>
@endpush

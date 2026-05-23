@extends('layouts.app')

@section('titulo', 'Comprar — ' . $evento->titulo)

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/vibez-home.css') }}">
@endpush

@section('content')

{{-- Mismo nav que el home --}}
@include('partials.home.nav')

{{-- ── Hero mínimo ── --}}
<div style="background:rgba(7,6,12,0.95);border-bottom:1px solid var(--line);padding:2rem 2rem 1.5rem;">
    <div style="max-width:1100px;margin:0 auto;">
        <a href="{{ route('eventos.detalle', $evento->id) }}"
           class="mono"
           style="display:inline-flex;align-items:center;gap:8px;font-size:10px;color:rgba(245,241,234,0.4);text-decoration:none;margin-bottom:1rem;transition:color 0.15s;"
           onmouseover="this.style.color='rgba(245,241,234,0.8)'"
           onmouseout="this.style.color='rgba(245,241,234,0.4)'">
            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver al evento
        </a>
        <h1 class="display" style="font-size:1.6rem;color:var(--ink);margin:0;">Comprar entradas</h1>
    </div>
</div>

{{-- ── Grid principal ── --}}
<div style="background:radial-gradient(circle,rgba(124,58,237,0.08) 1.5px,transparent 1.5px),linear-gradient(160deg,#0d0820 0%,#130228 45%,#0d0820 100%);background-size:28px 28px,100% 100%;min-height:calc(100vh - 160px);padding:3rem 2rem;">
<div style="max-width:1100px;margin:0 auto;display:grid;grid-template-columns:1fr 460px;gap:2.5rem;align-items:start;">

    {{-- ── Columna izquierda: resumen del evento ── --}}
    <div class="vibe-card" style="padding:0;overflow:hidden;">

        {{-- Portada --}}
        @if($evento->url_portada)
            <div style="position:relative;aspect-ratio:16/7;overflow:hidden;">
                <img src="{{ $evento->url_portada }}" alt="{{ $evento->titulo }}"
                     style="width:100%;height:100%;object-fit:cover;filter:brightness(0.85) saturate(1.1);">
                <div style="position:absolute;inset:0;background:linear-gradient(180deg,transparent 40%,rgba(7,6,12,0.85));"></div>
            </div>
        @endif

        <div style="padding:2rem;">

            {{-- Categoría --}}
            @if($evento->categoria)
                <div class="mono" style="font-size:9px;color:var(--magenta);margin-bottom:0.75rem;display:flex;align-items:center;gap:8px;">
                    <span style="width:18px;height:1px;background:var(--magenta);display:inline-block;"></span>
                    {{ $evento->categoria->nombre }}
                </div>
            @endif

            {{-- Título --}}
            <h2 class="display" style="font-size:clamp(1.6rem,3vw,2.5rem);color:var(--ink);margin:0 0 1.5rem;line-height:1;">
                {{ $evento->titulo }}
            </h2>

            {{-- Metadatos --}}
            <div style="display:flex;flex-direction:column;gap:10px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:var(--magenta);flex-shrink:0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="mono" style="font-size:10px;color:var(--ink-dim);">
                        {{ \Carbon\Carbon::parse($evento->fecha_inicio)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                        · {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('H:i') }}h
                    </span>
                </div>

                @if($evento->ubicacion_nombre)
                <div style="display:flex;align-items:center;gap:10px;">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:var(--magenta);flex-shrink:0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    </svg>
                    <span class="mono" style="font-size:10px;color:var(--ink-dim);">
                        {{ $evento->ubicacion_nombre }}
                        @if($evento->ubicacion_direccion) — {{ $evento->ubicacion_direccion }} @endif
                    </span>
                </div>
                @endif

                @if($evento->organizador?->empresa)
                <div style="display:flex;align-items:center;gap:10px;">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:var(--magenta);flex-shrink:0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="mono" style="font-size:10px;color:var(--ink-dim);">Organiza: {{ $evento->organizador->empresa->nombre_empresa }}</span>
                </div>
                @endif

                <div style="display:flex;align-items:center;gap:10px;margin-top:4px;">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:var(--magenta);flex-shrink:0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                    <span class="display" style="font-size:1.4rem;color:var(--magenta);">
                        {{ $evento->precio_formateado }} por persona
                        @if(!$evento->es_gratuito)
                            <span style="font-family:'Archivo Narrow',sans-serif;font-size:11px;font-weight:400;color:rgba(245,241,234,0.3);"> · IVA incluido</span>
                        @endif
                    </span>
                </div>
            </div>

        </div>
    </div>

    {{-- ── Columna derecha: panel de pago ── --}}
    <div class="vibe-card" style="padding:2rem;position:sticky;top:90px;">

        <div class="mono" style="font-size:10px;color:var(--magenta);margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid var(--line);display:flex;align-items:center;gap:8px;">
            <span style="width:18px;height:1px;background:var(--magenta);display:inline-block;"></span>
            {{ $evento->es_gratuito ? 'Reservar entrada gratuita' : 'Datos de pago' }}
        </div>

        {{-- Cantidad --}}
        <div class="mono" style="font-size:9px;color:rgba(245,241,234,0.4);margin-bottom:10px;">Cantidad de entradas</div>
        <div style="display:flex;align-items:center;gap:16px;margin-bottom:1.5rem;">
            <button type="button" onclick="cambiarCantidad(-1)"
                    style="width:40px;height:40px;border:1.5px solid rgba(168,85,247,0.4);background:rgba(168,85,247,0.08);color:var(--magenta-2);font-size:1.3rem;font-weight:700;cursor:pointer;border-radius:8px;display:flex;align-items:center;justify-content:center;transition:all 0.15s;line-height:1;"
                    onmouseover="this.style.background='rgba(168,85,247,0.2)'"
                    onmouseout="this.style.background='rgba(168,85,247,0.08)'">−</button>
            <span id="checkout-cantidad" class="display" style="font-size:2.2rem;color:var(--ink);min-width:2rem;text-align:center;">1</span>
            <button type="button" onclick="cambiarCantidad(1)"
                    style="width:40px;height:40px;border:1.5px solid rgba(168,85,247,0.4);background:rgba(168,85,247,0.08);color:var(--magenta-2);font-size:1.3rem;font-weight:700;cursor:pointer;border-radius:8px;display:flex;align-items:center;justify-content:center;transition:all 0.15s;line-height:1;"
                    onmouseover="this.style.background='rgba(168,85,247,0.2)'"
                    onmouseout="this.style.background='rgba(168,85,247,0.08)'">+</button>
            @if($aforoLibre < 9999)
                <span class="mono" style="font-size:9px;color:rgba(245,241,234,0.3);">Máx. {{ min(10, $aforoLibre) }}</span>
            @endif
        </div>

        {{-- Total --}}
        <div style="display:flex;justify-content:space-between;align-items:center;border-top:1px solid var(--line);border-bottom:1px solid var(--line);padding:1rem 0;margin-bottom:1.5rem;">
            <span class="mono" style="font-size:9px;color:rgba(245,241,234,0.4);">Total</span>
            <span id="checkout-total" class="display" style="font-size:2.2rem;color:var(--magenta);">
                @if($evento->es_gratuito) Gratis
                @else {{ number_format($evento->precio_base, 2, ',', '.') }} €
                @endif
            </span>
        </div>

        {{-- Formulario Stripe --}}
        @if(!$evento->es_gratuito && $stripeActivo)
        <div style="margin-bottom:1.5rem;">
            <div class="mono" style="font-size:9px;color:rgba(245,241,234,0.4);margin-bottom:10px;">Datos de tarjeta</div>
            <div id="stripe-card-element"
                 style="background:rgba(255,255,255,0.04);border:1px solid rgba(168,85,247,0.35);padding:14px 16px;border-radius:8px;transition:border-color 0.2s;"
                 onfocus="this.style.borderColor='rgba(168,85,247,0.8)'"
                 onblur="this.style.borderColor='rgba(168,85,247,0.35)'"></div>
            <div id="stripe-card-error" style="display:none;color:#f87171;font-family:'Archivo Narrow',sans-serif;font-size:12px;margin-top:8px;"></div>
        </div>
        @elseif(!$evento->es_gratuito && !$stripeActivo)
        <div style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);color:#f87171;padding:12px 16px;font-family:'Archivo Narrow',sans-serif;font-size:13px;border-radius:8px;margin-bottom:1.5rem;">
            Los pagos con tarjeta no están disponibles para este evento. Contacta con el organizador.
        </div>
        @endif

        {{-- Error general --}}
        <div id="checkout-error" style="display:none;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);color:#f87171;padding:10px 14px;font-family:'Archivo Narrow',sans-serif;font-size:13px;border-radius:8px;margin-bottom:1rem;"></div>

        {{-- Botón de acción --}}
        @if($evento->es_gratuito || $stripeActivo)
        <button id="checkout-btn" type="button" onclick="procesarPago()" class="btn-primary"
                style="width:100%;padding:15px;border-radius:999px;font-size:14px;cursor:pointer;border:none;">
            @if($evento->es_gratuito)
                Reservar gratis
            @else
                Pagar {{ number_format($evento->precio_base, 2, ',', '.') }} €
            @endif
        </button>
        @endif

        <p class="mono" style="text-align:center;font-size:9px;color:rgba(245,241,234,0.2);margin-top:1rem;">
            Compra segura · Recibirás tu QR por email al instante
        </p>

        @if(!$evento->es_gratuito && $stripeActivo && app()->environment('local'))
        <div class="mono" style="background:rgba(168,85,247,0.06);border:1px solid rgba(168,85,247,0.15);padding:10px 14px;border-radius:8px;margin-top:1rem;font-size:9px;color:rgba(245,241,234,0.35);">
            Tarjeta de prueba: 4242 4242 4242 4242 · Cualquier fecha · Cualquier CVC
        </div>
        @endif

    </div>

</div>
</div>

{{-- Mismo footer que el home --}}
@include('partials.home.footer')

@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
window.comprarData = {
    eventoId:     {{ $evento->id }},
    precioBase:   {{ $evento->precio_base ?? 0 }},
    esGratuito:   {{ $evento->es_gratuito ? 'true' : 'false' }},
    aforoLibre:   {{ $aforoLibre }},
    stripeKey:    '{{ config('services.stripe.key') }}',
    stripeActivo: {{ $stripeActivo ? 'true' : 'false' }},
};
</script>
<script src="{{ asset('js/eventos-comprar.js') }}"></script>
@endpush

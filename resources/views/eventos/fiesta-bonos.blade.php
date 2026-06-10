@extends('layouts.app')

@section('titulo', 'Bonos de bebidas — ' . $evento->titulo . ' — VIBEZ')

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/vibez-home.css') }}">
<link rel="stylesheet" href="{{ asset('css/fiesta-bonos.css') }}">
@endpush

@section('content')

@include('partials.home.nav')

{{-- Hero --}}
<div style="background:rgba(7,6,12,0.95);border-bottom:1px solid rgba(245,241,234,0.06);padding:2rem 2rem 1.5rem;">
    <div style="max-width:1200px;margin:0 auto;">
        <a href="{{ route('entradas.mis-entradas') }}"
           style="display:inline-flex;align-items:center;gap:8px;font-size:10px;color:rgba(245,241,234,0.4);text-decoration:none;margin-bottom:1rem;font-family:'Archivo Narrow',sans-serif;text-transform:uppercase;letter-spacing:0.1em;transition:color 0.15s;"
           onmouseover="this.style.color='rgba(245,241,234,0.8)'"
           onmouseout="this.style.color='rgba(245,241,234,0.4)'">
            ← Mis entradas
        </a>
        <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
            @if($evento->url_portada)
                <img src="{{ $evento->url_portada }}" alt="{{ $evento->titulo }}"
                     style="width:56px;height:56px;object-fit:cover;border-radius:8px;border:1px solid rgba(124,58,237,0.3);flex-shrink:0;">
            @endif
            <div>
                <div style="font-family:'Archivo Narrow',sans-serif;font-size:10px;text-transform:uppercase;letter-spacing:0.12em;color:#a855f7;margin-bottom:4px;">🍹 Bonos de bebidas</div>
                <h1 style="font-family:'Anton',sans-serif;font-size:clamp(1.4rem,3vw,2rem);color:#f5f1ea;margin:0;line-height:1.1;">
                    {{ $evento->titulo }}
                </h1>
                <p style="font-family:'Archivo Narrow',sans-serif;font-size:12px;color:rgba(245,241,234,0.4);margin:4px 0 0;">
                    {{ $evento->fecha_inicio->format('d/m/Y H:i') }}
                    @if($evento->ubicacion_nombre) · {{ $evento->ubicacion_nombre }} @endif
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Nota empresa --}}
<div style="background:rgba(74,222,128,0.06);border-bottom:1px solid rgba(74,222,128,0.15);padding:10px 2rem;">
    <div style="max-width:1200px;margin:0 auto;font-family:'Archivo Narrow',sans-serif;font-size:12px;color:rgba(74,222,128,0.75);display:flex;align-items:center;gap:8px;">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Tu bono es válido en cualquier evento Fiesta de esta empresa, no solo en esta noche.
    </div>
</div>

<div style="background:radial-gradient(circle,rgba(124,58,237,0.07) 1.5px,transparent 1.5px),linear-gradient(160deg,#0d0820 0%,#130228 45%,#0d0820 100%);background-size:28px 28px,100% 100%;min-height:calc(100vh - 200px);padding:2rem;">
<div style="max-width:1200px;margin:0 auto;">

    {{-- Bonos activos del usuario --}}
    @if($bonosActivos->isNotEmpty())
    <div style="margin-bottom:2.5rem;">
        <h2 style="font-family:'Anton',sans-serif;font-size:1.1rem;color:#f5f1ea;margin:0 0 1rem;text-transform:uppercase;">Tus bonos activos</h2>
        <div style="display:flex;flex-direction:column;gap:10px;">
            @foreach($bonosActivos as $bono)
            <div class="saldo-card">
                <div>
                    <div style="font-family:'Anton',sans-serif;font-size:1.05rem;color:#f5f1ea;">
                        Bono {{ $bono->tipo?->cantidad_bebidas }} bebidas
                    </div>
                    <div style="font-family:'Archivo Narrow',sans-serif;font-size:11px;color:rgba(245,241,234,0.45);margin-top:2px;">
                        Comprado en: {{ $bono->evento?->titulo ?? '—' }} · {{ $bono->created_at->format('d/m/Y') }}
                    </div>
                </div>
                <div style="text-align:right;">
                    <div style="font-family:'Anton',sans-serif;font-size:2rem;color:#4ade80;line-height:1;">
                        {{ $bono->bebidas_restantes }}
                    </div>
                    <div style="font-family:'Archivo Narrow',sans-serif;font-size:10px;color:rgba(74,222,128,0.6);text-transform:uppercase;letter-spacing:0.08em;">bebidas restantes</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Tipos de bono disponibles --}}
    <div style="margin-bottom:1.5rem;">
        <h2 style="font-family:'Anton',sans-serif;font-size:1.1rem;color:#f5f1ea;margin:0 0 4px;text-transform:uppercase;">Comprar bono</h2>
        <p style="font-family:'Archivo Narrow',sans-serif;font-size:12px;color:rgba(245,241,234,0.4);margin:0 0 1.25rem;">
            Evita colas en la barra — paga ahora y canjea cuando quieras.
        </p>
    </div>

    @if($tiposBono->isEmpty())
        <div style="background:#0d0a18;border:1px solid rgba(245,241,234,0.08);padding:60px;text-align:center;border-radius:12px;">
            <p style="font-family:'Archivo Narrow',sans-serif;font-size:0.875rem;text-transform:uppercase;letter-spacing:0.1em;color:rgba(245,241,234,0.25);">
                No hay bonos disponibles para este evento todavía.
            </p>
        </div>
    @else
        <div class="bonos-grid">
            @foreach($tiposBono as $tipo)
            <div class="bono-card" id="bono-card-{{ $tipo->id }}">
                <div class="bono-cantidad">{{ $tipo->cantidad_bebidas }}</div>
                <div style="font-family:'Archivo Narrow',sans-serif;font-size:11px;text-transform:uppercase;letter-spacing:0.14em;color:rgba(245,241,234,0.4);margin-bottom:4px;">bebidas</div>

                @if($tipo->descripcion)
                <p style="font-family:'Archivo Narrow',sans-serif;font-size:12px;color:rgba(245,241,234,0.5);margin:8px 0 4px;line-height:1.4;">
                    {{ $tipo->descripcion }}
                </p>
                @endif

                <div class="bono-precio">{{ number_format($tipo->precio, 2) }} €</div>

                <div style="font-family:'Archivo Narrow',sans-serif;font-size:11px;color:rgba(245,241,234,0.3);margin-bottom:16px;">
                    {{ number_format($tipo->precio / $tipo->cantidad_bebidas, 2) }} € / bebida
                </div>

                <button class="btn-comprar-bono"
                        id="btn-bono-{{ $tipo->id }}"
                        onclick="abrirModalBono({{ $tipo->id }}, {{ $tipo->cantidad_bebidas }}, {{ $tipo->precio }})">
                    Comprar bono
                </button>
            </div>
            @endforeach
        </div>

        @if(app()->environment('local'))
        <div style="margin-top:1.5rem;padding:10px 14px;background:rgba(168,85,247,0.06);border:1px solid rgba(168,85,247,0.15);border-radius:8px;font-family:'Archivo Narrow',sans-serif;font-size:11px;color:rgba(245,241,234,0.35);">
            Prueba: 4242 4242 4242 4242 · Cualquier fecha · Cualquier CVC
        </div>
        @endif
    @endif

</div>
</div>

{{-- Modal de pago --}}
<div id="modal-bono-overlay" onclick="cerrarModalBonoSiOverlay(event)">
    <div id="modal-bono" role="dialog" aria-modal="true">

        <button onclick="cerrarModalBono()"
                style="position:absolute;top:14px;right:16px;background:none;border:none;color:rgba(245,241,234,0.4);font-size:1.3rem;cursor:pointer;padding:4px;">✕</button>

        <div style="text-align:center;margin-bottom:1.5rem;">
            <div style="font-size:2.5rem;margin-bottom:8px;">🍹</div>
            <div id="modal-bono-titulo" style="font-family:'Anton',sans-serif;font-size:1.6rem;color:#c084fc;"></div>
            <div style="font-family:'Archivo Narrow',sans-serif;font-size:12px;color:rgba(245,241,234,0.4);margin-top:4px;">bebidas · válido en todos los eventos Fiesta de esta empresa</div>
        </div>

        <div style="display:flex;justify-content:space-between;align-items:center;border-top:1px solid rgba(245,241,234,0.06);border-bottom:1px solid rgba(245,241,234,0.06);padding:12px 0;margin-bottom:1.25rem;">
            <span style="font-family:'Archivo Narrow',sans-serif;font-size:12px;color:rgba(245,241,234,0.4);">Importe</span>
            <span id="modal-bono-precio" style="font-family:'Anton',sans-serif;font-size:1.6rem;color:#f5f1ea;"></span>
        </div>

        <div id="stripe-bono-card-el"></div>
        <div id="stripe-bono-error" style="display:none;color:#f87171;font-family:'Archivo Narrow',sans-serif;font-size:12px;margin-bottom:10px;"></div>

        <button id="btn-pagar-bono" type="button" onclick="procesarPagoBono()"
                style="width:100%;padding:12px;border:none;border-radius:999px;background:linear-gradient(135deg,#7c3aed,#a855f7);color:#fff;font-family:'Archivo Narrow',sans-serif;font-size:14px;font-weight:700;cursor:pointer;transition:opacity 0.15s;"
                onmouseover="this.style.opacity='0.88'" onmouseout="this.style.opacity='1'">
            Pagar y activar bono
        </button>

    </div>
</div>

@include('partials.home.footer')
@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
window.bonoData = {
    eventoId:  {{ $evento->id }},
    stripeKey: '{{ config('services.stripe.key') }}',
};
</script>
<script src="{{ asset('js/fiesta-bonos.js') }}"></script>
@endpush

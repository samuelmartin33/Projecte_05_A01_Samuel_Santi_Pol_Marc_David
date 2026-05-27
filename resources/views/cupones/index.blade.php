@extends('layouts.app')

@section('title', 'Cupones de Descuento — VIBEZ')
@section('html-class', 'dark')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/cupones.css') }}">
@endsection

@section('content')

<div class="cup-page">
    @include('partials.home.nav')

    <section class="cup-hero">
        <p class="cup-hero-kicker">✦ Descuentos exclusivos VIBEZ</p>
        <h1 class="cup-hero-title">CUPONES</h1>
        <p class="cup-hero-sub">
            Copia el código, pégalo al comprar tu entrada y ahorra al instante.
        </p>
    </section>

    <main class="cup-main">

        @if($cuponesActivos->isEmpty())
            <div class="cup-empty">
                <div class="cup-empty-icon">🎟️</div>
                <h3>No hay cupones activos ahora mismo</h3>
                <p>Vuelve pronto — publicamos nuevas ofertas regularmente.</p>
            </div>
        @else
        <div class="cup-grid">
            @foreach($cuponesActivos as $cupon)
            @php
                $isFree  = $cupon->valor_descuento == 0;
                $eventos = $cupon->eventos;
            @endphp
            <article class="cup-card {{ $isFree ? 'free-entry' : '' }}" data-cupon-id="{{ $cupon->id }}">

                {{-- Empresa que ofrece el cupón --}}
                @if($cupon->empresa)
                <div class="cup-card-empresa">
                    @if($cupon->empresa->logo_url)
                        <img src="{{ $cupon->empresa->logo_url }}"
                             alt="{{ $cupon->empresa->nombre_empresa }}"
                             class="cup-card-empresa-logo">
                    @else
                        <div class="cup-card-empresa-avatar">{{ mb_substr($cupon->empresa->nombre_empresa, 0, 1) }}</div>
                    @endif
                    <span class="cup-card-empresa-nombre">{{ $cupon->empresa->nombre_empresa }}</span>
                </div>
                @endif

                {{-- Porcentaje / badge --}}
                <div class="cup-card-top">
                    <div class="cup-card-discount-wrap">
                        <span class="cup-card-discount">
                            {{ $isFree ? 'GRATIS' : number_format($cupon->valor_descuento, 0) . '%' }}
                        </span>
                        <span class="cup-card-discount-label">
                            {{ $isFree ? 'Entrada gratuita' : 'de descuento' }}
                        </span>
                    </div>
                    <span class="cup-card-badge active">
                        <span class="badge-dot"></span> Activo
                    </span>
                </div>

                @if($cupon->descripcion)
                    <p class="cup-card-desc">{{ $cupon->descripcion }}</p>
                @endif

                <div class="cup-card-divider"><hr class="cup-card-divider-line"></div>

                {{-- Código con botón copiar --}}
                <div class="cup-card-code-section">
                    <span class="cup-card-code-label">Código</span>
                    <div class="cup-card-code-inner">
                        <span class="cup-card-code">{{ $cupon->codigo }}</span>
                        <button class="cup-card-copy-btn"
                                id="btn-copy-{{ $cupon->id }}"
                                onclick="copiarCodigo('{{ $cupon->codigo }}', {{ $cupon->id }})">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <rect x="9" y="9" width="13" height="13" rx="2"/>
                                <path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/>
                            </svg>
                            Copiar
                        </button>
                    </div>
                </div>

                {{-- Eventos donde aplica --}}
                <div class="cup-card-events" style="padding-top:16px;padding-bottom:8px;">
                    <p class="cup-card-events-label">Válido en</p>
                    @if($eventos->isEmpty())
                        <span class="cup-card-global-note">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/>
                            </svg>
                            Todos los eventos VIBEZ
                        </span>
                    @else
                        @foreach($eventos->take(3) as $ev)
                        <a href="{{ route('eventos.detalle', $ev->id) }}" class="cup-card-event-item">
                            <img src="{{ $ev->url_portada }}" alt="{{ $ev->titulo }}"
                                 class="cup-card-event-img" loading="lazy">
                            <div style="min-width:0;flex:1;">
                                <span class="cup-card-event-name">{{ $ev->titulo }}</span>
                                <span class="cup-card-event-date">
                                    {{ $ev->fecha_inicio->locale('es')->isoFormat('D MMM · HH:mm') }}
                                </span>
                            </div>
                        </a>
                        @endforeach
                        @if($eventos->count() > 3)
                            <span class="cup-card-global-note" style="margin-top:6px;">
                                +{{ $eventos->count() - 3 }} eventos más
                            </span>
                        @endif
                    @endif
                </div>

                {{-- Footer: fecha fin + usos --}}
                <div class="cup-card-foot">
                    <div class="cup-card-meta">
                        <div>Hasta {{ $cupon->fecha_fin->locale('es')->isoFormat('D MMM YYYY') }}</div>
                        <div>
                            {{ $cupon->usos_restantes >= 0 ? $cupon->usos_restantes . ' usos restantes' : 'Usos ilimitados' }}
                        </div>
                    </div>
                </div>

            </article>
            @endforeach
        </div>
        @endif

    </main>

    @include('partials.home.footer')
</div>

<div class="cup-toast" id="cup-toast"></div>

@push('scripts')
<script src="{{ asset('js/cupones.js') }}"></script>
@endpush

@endsection

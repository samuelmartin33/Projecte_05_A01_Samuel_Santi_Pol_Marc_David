@extends('layouts.app')

@section('title', 'Cupones de Descuento — VIBEZ')
@section('html-class', 'dark')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/cupones.css') }}">
@endsection

@section('content')

@php
    $totalActivos  = $cuponesActivos->count();
    $totalGlobal   = $totalActivos + $cuponesExpirados->count();
    $maxDescuento  = $cuponesActivos->max('valor_descuento') ?? 0;
@endphp

<div class="cup-page">
    @include('partials.home.nav')

    <section class="cup-hero">
        <p class="cup-hero-kicker">✦ Descuentos exclusivos VIBEZ</p>
        <h1 class="cup-hero-title">CUPONES</h1>
        <p class="cup-hero-sub">
            Copia el código, pégalo al comprar tu entrada y ahorra al instante.
        </p>
        <div class="hero-stats-simple">
            <div class="stat-item">
                <div class="stat-value">{{ $totalActivos }}</div>
                <div class="stat-text">Activos ahora</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $maxDescuento > 0 ? $maxDescuento . '%' : '—' }}</div>
                <div class="stat-text">Descuento máximo</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $totalGlobal }}</div>
                <div class="stat-text">Total cupones</div>
            </div>
        </div>
    </section>

    <div class="cup-filters">
        <div class="cup-filters-inner">
            <button class="cup-filter-chip active" data-filter="all"    onclick="cupSetFilter(this,'all')">Todos</button>
            <button class="cup-filter-chip"         data-filter="active" onclick="cupSetFilter(this,'active')">Activos</button>
            <button class="cup-filter-chip"         data-filter="free"   onclick="cupSetFilter(this,'free')">Gratuitos</button>
        </div>
    </div>

    <main class="cup-main">
        <p class="cup-section-title">Cupones disponibles</p>

        @if($cuponesActivos->isEmpty())
            <div class="cup-empty">
                <div class="cup-empty-icon">🎟️</div>
                <h3>No hay cupones activos ahora mismo</h3>
                <p>Vuelve pronto — publicamos nuevas ofertas regularmente.</p>
            </div>
        @else
        <div class="cup-grid" id="cup-grid-activos">
            @foreach($cuponesActivos as $cupon)
            @php
                $isFree = $cupon->valor_descuento == 0;
                $eventos = $cupon->eventos;
            @endphp
            <article class="cup-card {{ $isFree ? 'free-entry' : '' }}"
                     data-type="{{ $isFree ? 'free' : 'active' }}"
                     data-cupon-id="{{ $cupon->id }}">

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

                <div class="cup-card-divider">
                    <hr class="cup-card-divider-line">
                </div>

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

                <!-- Eventos aplicables: máximo 3 mostrados con opción de ver más -->
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

        @if($cuponesExpirados->isNotEmpty())
        <div id="cup-sec-expired" style="margin-top:64px;">
            <p class="cup-section-title">Cupones expirados</p>
            <div class="cup-grid">
                @foreach($cuponesExpirados as $cupon)
                <article class="cup-card expired" data-type="expired" data-cupon-id="{{ $cupon->id }}">
                    <div class="cup-card-top">
                        <div class="cup-card-discount-wrap">
                            <span class="cup-card-discount">
                                {{ $cupon->valor_descuento == 0 ? 'GRATIS' : number_format($cupon->valor_descuento, 0) . '%' }}
                            </span>
                            <span class="cup-card-discount-label">expirado</span>
                        </div>
                        <span class="cup-card-badge expired">Expirado</span>
                    </div>

                    @if($cupon->descripcion)
                        <p class="cup-card-desc">{{ $cupon->descripcion }}</p>
                    @endif

                    <div class="cup-card-divider"><hr class="cup-card-divider-line"></div>

                    <div class="cup-card-code-section">
                        <span class="cup-card-code-label">Código</span>
                        <div class="cup-card-code-inner">
                            <span class="cup-card-code">{{ $cupon->codigo }}</span>
                            <button class="cup-card-copy-btn" disabled>Expirado</button>
                        </div>
                    </div>

                    <div class="cup-card-foot">
                        <div class="cup-card-meta">
                            Venció el {{ $cupon->fecha_fin->locale('es')->isoFormat('D MMM YYYY') }}
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
        @endif
    </main>

    <!-- Sección informativa: pasos para usar cupones -->
    <section class="cup-how">
        <div class="cup-how-inner">
            <h2 class="cup-how-title">¿Cómo funciona?</h2>
            <div class="cup-how-grid">
                <div class="cup-how-step">
                    <div class="cup-how-step-num">01</div>
                    <div class="cup-how-step-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3>Copia el código</h3>
                    <p>Haz clic en "Copiar" y el código queda listo en tu portapapeles.</p>
                </div>

                <div class="cup-how-step">
                    <div class="cup-how-step-num">02</div>
                    <div class="cup-how-step-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h3>Encuentra tu evento</h3>
                    <p>Explora los eventos donde aplica y haz clic en "Comprar entrada".</p>
                </div>

                <div class="cup-how-step">
                    <div class="cup-how-step-num">03</div>
                    <div class="cup-how-step-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                    </div>
                    <h3>Aplica y ahorra</h3>
                    <p>Pega el código en el modal de compra. El descuento se aplica al instante.</p>
                </div>
            </div>
        </div>
    </section>

    @include('partials.home.footer')

</div>

<div class="cup-toast" id="cup-toast"></div>

@push('scripts')
<script src="{{ asset('js/cupones.js') }}"></script>
@endpush

@endsection

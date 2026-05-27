@extends('layouts.app')
@section('titulo', 'Mis entradas — VIBEZ')

{{-- @push('estilos')
<link rel="stylesheet" href="{{ asset('css/mis-entradas.css') }}">
{{-- CSS extraido a public/css/mis-entradas.css --}}
@endpush

@section('content') suprime el nav/footer del layout, igual que la home --}}
@section('content')

{{-- ── Estilos ── --}}
<link rel="stylesheet" href="{{ asset('css/vibez-home.css') }}">


{{-- ────────────────── NAV ────────────────── --}}
@include('partials.home.nav')

{{-- ────────────────── HERO + COUNTDOWN ────────────────── --}}
@php
  /* Encontramos el próximo evento futuro con entrada activa */
  $proximoEvento = null;
  foreach($pedidos as $pedido) {
    foreach($pedido->entradas as $entrada) {
      if ($entrada->estado_entrada == 1 && $entrada->evento &&
          \Carbon\Carbon::parse($entrada->evento->fecha_inicio)->isFuture()) {
        if (!$proximoEvento ||
            \Carbon\Carbon::parse($entrada->evento->fecha_inicio)
              ->lt(\Carbon\Carbon::parse($proximoEvento->fecha_inicio))) {
          $proximoEvento = $entrada->evento;
        }
      }
    }
  }
@endphp

<section style="background:linear-gradient(160deg,#07060c 0%,#130228 55%,#0d0820 100%);
                padding:2.5rem 0 2rem;border-bottom:1px solid rgba(124,58,237,0.15);">
  <div style="max-width:720px;margin:0 auto;padding:0 1.5rem;">

    {{-- Botón volver --}}
    <a href="{{ route('home') }}"
       style="display:inline-flex;align-items:center;gap:8px;text-decoration:none;
              color:rgba(245,241,234,0.4);font-family:'Archivo Narrow',sans-serif;
              font-size:0.72rem;text-transform:uppercase;letter-spacing:0.1em;
              margin-bottom:1.5rem;transition:color 0.15s;"
       onmouseover="this.style.color='#c084fc'"
       onmouseout="this.style.color='rgba(245,241,234,0.4)'">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
      </svg>
      Volver
    </a>

    <h1 style="font-family:'Anton',sans-serif;font-size:2.4rem;color:#fff;
               margin:0 0 0.3rem;line-height:1;text-transform:uppercase;letter-spacing:0.02em;">
      MIS ENTRADAS
    </h1>
    <p style="font-family:'Archivo Narrow',sans-serif;color:rgba(245,241,234,0.38);
              font-size:0.8rem;text-transform:uppercase;letter-spacing:0.1em;margin:0;">
      {{ $pedidos->sum(fn($p) => $p->entradas->count()) }}
      {{ $pedidos->sum(fn($p) => $p->entradas->count()) === 1 ? 'entrada' : 'entradas' }} en total
    </p>

    {{-- Countdown para el próximo evento activo --}}
    @if($proximoEvento)
    <div class="me-cnt-row"
         data-fecha="{{ \Carbon\Carbon::parse($proximoEvento->fecha_inicio)->toISOString() }}"
         style="margin-top:2rem;background:rgba(124,58,237,0.1);
                border:1px solid rgba(124,58,237,0.32);border-radius:18px;
                padding:20px 24px;display:flex;align-items:center;
                justify-content:space-between;gap:20px;backdrop-filter:blur(12px);">

      <div>
        <p style="font-family:'Archivo Narrow',sans-serif;font-size:0.6rem;
                  text-transform:uppercase;letter-spacing:0.14em;
                  color:#a855f7;margin:0 0 5px;">
          Próximo evento
        </p>
        <p style="font-family:'Anton',sans-serif;font-size:1rem;color:#fff;
                  margin:0 0 4px;text-transform:uppercase;letter-spacing:0.02em;">
          {{ $proximoEvento->titulo }}
        </p>
        <p style="font-family:'Archivo Narrow',sans-serif;font-size:0.7rem;
                  color:rgba(245,241,234,0.35);margin:0;letter-spacing:0.06em;">
          {{ \Carbon\Carbon::parse($proximoEvento->fecha_inicio)->locale('es')->isoFormat('ddd D MMM YYYY · HH:mm') }}
        </p>
      </div>

      <div class="me-cnt-timer" style="display:flex;align-items:center;gap:6px;flex-shrink:0;">
        <div class="me-cnt-unit">
          <span class="me-cnt-num" id="cnt-dias"
                style="font-family:'Anton',sans-serif;font-size:1.4rem;color:#fff;line-height:1;">--</span>
          <span style="font-family:'Archivo Narrow',sans-serif;font-size:0.5rem;
                       text-transform:uppercase;letter-spacing:0.1em;
                       color:rgba(245,241,234,0.35);margin-top:3px;">días</span>
        </div>
        <span style="font-family:'Anton',sans-serif;font-size:1.2rem;
                     color:rgba(168,85,247,0.5);padding-bottom:14px;line-height:1;">:</span>
        <div class="me-cnt-unit">
          <span class="me-cnt-num" id="cnt-horas"
                style="font-family:'Anton',sans-serif;font-size:1.4rem;color:#fff;line-height:1;">--</span>
          <span style="font-family:'Archivo Narrow',sans-serif;font-size:0.5rem;
                       text-transform:uppercase;letter-spacing:0.1em;
                       color:rgba(245,241,234,0.35);margin-top:3px;">horas</span>
        </div>
        <span style="font-family:'Anton',sans-serif;font-size:1.2rem;
                     color:rgba(168,85,247,0.5);padding-bottom:14px;line-height:1;">:</span>
        <div class="me-cnt-unit">
          <span class="me-cnt-num" id="cnt-min"
                style="font-family:'Anton',sans-serif;font-size:1.4rem;color:#fff;line-height:1;">--</span>
          <span style="font-family:'Archivo Narrow',sans-serif;font-size:0.5rem;
                       text-transform:uppercase;letter-spacing:0.1em;
                       color:rgba(245,241,234,0.35);margin-top:3px;">min</span>
        </div>
        <span style="font-family:'Anton',sans-serif;font-size:1.2rem;
                     color:rgba(168,85,247,0.5);padding-bottom:14px;line-height:1;">:</span>
        <div class="me-cnt-unit">
          <span class="me-cnt-num" id="cnt-seg"
                style="font-family:'Anton',sans-serif;font-size:1.4rem;color:#fff;line-height:1;">--</span>
          <span style="font-family:'Archivo Narrow',sans-serif;font-size:0.5rem;
                       text-transform:uppercase;letter-spacing:0.1em;
                       color:rgba(245,241,234,0.35);margin-top:3px;">seg</span>
        </div>
      </div>
    </div>
    @endif

  </div>
</section>

{{-- ────────────────── CONTENIDO ────────────────── --}}
<div style="background:radial-gradient(circle,rgba(124,58,237,0.09) 1.5px,transparent 1.5px),
                        linear-gradient(160deg,#07060c 0%,#0d0820 45%,#0e0722 75%,#07060c 100%);
            background-size:28px 28px,100% 100%;min-height:60vh;padding:2.5rem 0 5rem;">
<div style="max-width:720px;margin:0 auto;padding:0 1.5rem;">

  @if($pedidos->isEmpty())

    {{-- Estado vacío --}}
    <div style="text-align:center;padding:4rem 1.5rem;">
      <div style="width:64px;height:64px;background:rgba(124,58,237,0.12);
                  border:2px solid rgba(124,58,237,0.28);border-radius:50%;
                  display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
        <svg width="32" height="32" fill="none" viewBox="0 0 24 24"
             stroke="#7c3aed" stroke-width="1.5">
          <path stroke-linecap="round" stroke-linejoin="round"
                d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
        </svg>
      </div>
      <p style="font-family:'Anton',sans-serif;font-size:1.4rem;color:#f5f1ea;
                margin:0 0 0.5rem;text-transform:uppercase;letter-spacing:0.02em;">
        Aún no tienes entradas
      </p>
      <p style="font-family:'Archivo',sans-serif;color:rgba(245,241,234,0.35);
                font-size:0.9rem;margin:0 0 2rem;">
        Explora los eventos disponibles y compra tu primera entrada.
      </p>
      <a href="{{ route('home') }}"
         style="display:inline-block;background:linear-gradient(135deg,#7c3aed,#a855f7);
                color:#fff;padding:12px 28px;border-radius:999px;
                font-family:'Archivo Narrow',sans-serif;font-size:0.85rem;
                font-weight:700;text-transform:uppercase;letter-spacing:0.1em;
                text-decoration:none;box-shadow:0 4px 20px rgba(124,58,237,0.4);">
        Explorar eventos
      </a>
    </div>

  @else

    {{-- ── Filtros ── --}}
    <div class="me-filtros" style="display:flex;gap:8px;margin-bottom:2rem;flex-wrap:wrap;">
      <button class="me-filtro-btn activo" onclick="filtrarEntradas('todas', this)">Todas</button>
      <button class="me-filtro-btn" onclick="filtrarEntradas('activas', this)">Activas</button>
      <button class="me-filtro-btn" onclick="filtrarEntradas('usadas', this)">Usadas</button>
      <button class="me-filtro-btn" onclick="filtrarEntradas('caducadas', this)">Caducadas</button>
    </div>

    {{-- ── Tarjetas de pedidos ── --}}
    @foreach($pedidos as $pedido)
      @php
        $evento           = $pedido->entradas->first()?->evento;
        $tieneActivas     = $pedido->entradas->contains('estado_entrada', 1);
        $eventoPasado     = $evento && \Carbon\Carbon::parse($evento->fecha_inicio)->isPast();
        $tieneEscaneadas  = $pedido->entradas->contains('estado_entrada', 2);

        if ($eventoPasado) {
            $estadoCard = 'caducadas';
        } elseif ($tieneActivas) {
            $estadoCard = 'activas';
        } else {
            $estadoCard = 'usadas';
        }

        // Botón de reembolso: evento futuro, al menos una activa, ninguna escaneada
        $puedeReembolsar = $estadoCard === 'activas' && !$tieneEscaneadas;
      @endphp

      <div class="me-card {{ $estadoCard === 'usadas' ? 'usada' : ($estadoCard === 'caducadas' ? 'caducada' : '') }}"
           id="pedido-card-{{ $pedido->id }}"
           data-estado="{{ $estadoCard }}"
           onclick="toggleTicketQr({{ $pedido->id }})">

        {{-- Cabecera de la tarjeta --}}
        <div class="me-card-header"
             style="padding:20px 24px;background:linear-gradient(135deg,#060011,#1a0f35);
                    display:flex;justify-content:space-between;align-items:flex-start;gap:16px;">

          <div style="flex:1;min-width:0;">
            @if($estadoCard === 'activas')
              <span class="me-badge-activa">Activa</span>
            @elseif($estadoCard === 'caducadas')
              <span class="me-badge-caducada">Caducada</span>
            @else
              <span class="me-badge-usada">Usada</span>
            @endif

            <h3 style="font-family:'Anton',sans-serif;font-size:1.1rem;color:#fff;
                       margin:0 0 8px;line-height:1.15;text-transform:uppercase;
                       letter-spacing:0.02em;white-space:nowrap;overflow:hidden;
                       text-overflow:ellipsis;">
              {{ $evento?->titulo ?? 'Evento eliminado' }}
            </h3>

            @if($evento)
              <p style="display:flex;align-items:center;gap:6px;font-family:'Archivo Narrow',sans-serif;
                        font-size:0.72rem;color:rgba(245,241,234,0.4);margin:0 0 3px;
                        text-transform:uppercase;letter-spacing:0.06em;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" style="flex-shrink:0">
                  <rect x="3" y="4" width="18" height="18" rx="2"/>
                  <line x1="16" y1="2" x2="16" y2="6"/>
                  <line x1="8" y1="2" x2="8" y2="6"/>
                  <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                {{ \Carbon\Carbon::parse($evento->fecha_inicio)->locale('es')->isoFormat('ddd D MMM YYYY · HH:mm') }}
              </p>
              @if($evento->ubicacion_nombre)
              <p style="display:flex;align-items:center;gap:6px;font-family:'Archivo Narrow',sans-serif;
                        font-size:0.72rem;color:rgba(245,241,234,0.4);margin:0;
                        text-transform:uppercase;letter-spacing:0.06em;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" style="flex-shrink:0">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                  <circle cx="12" cy="10" r="3"/>
                </svg>
                {{ $evento->ubicacion_nombre }}
              </p>
              @endif
            @endif

            @if($puedeReembolsar)
              <button class="me-btn-reembolso"
                      onclick="solicitarReembolso(event, {{ $pedido->id }}, '{{ route('entradas.reembolsar', $pedido) }}')">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 10h10a8 8 0 018 8v2M3 10l4-4M3 10l4 4"/>
                </svg>
                Pedir reembolso
              </button>
            @endif
          </div>

          <div style="display:flex;flex-direction:column;align-items:flex-end;
                      gap:4px;flex-shrink:0;">
            <span style="font-family:'Anton',sans-serif;font-size:1.8rem;
                         color:rgba(168,85,247,0.6);line-height:1;">
              {{ $pedido->entradas->count() }}×
            </span>
            <p style="margin:0;font-family:'Archivo',sans-serif;font-weight:800;font-size:0.9rem;">
              @if($pedido->total_final == 0)
                <span style="color:#34d399;">Gratis</span>
              @else
                <span style="background:linear-gradient(135deg,#7c3aed,#a855f7);
                             -webkit-background-clip:text;-webkit-text-fill-color:transparent;
                             background-clip:text;">
                  {{ number_format($pedido->total_final, 2) }}€
                </span>
              @endif
            </p>
            <span class="me-chevron" id="chevron-{{ $pedido->id }}"
                  style="color:rgba(245,241,234,0.28);margin-top:4px;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                   stroke="currentColor" stroke-width="2.5">
                <path d="M6 9l6 6 6-6"/>
              </svg>
            </span>
          </div>
        </div>

        {{-- Separador talonario --}}
        <div class="me-sep">
          <div class="me-circ" style="left:-11px;"></div>
          <div class="me-circ" style="right:-11px;"></div>
        </div>

        {{-- Panel QR (oculto por defecto, se abre al hacer clic) --}}
        <div id="qr-panel-{{ $pedido->id }}" class="me-qr-panel" style="display:none">

          @if($pedido->entradas->count() > 1)
            <p style="font-family:'Archivo Narrow',sans-serif;font-size:0.68rem;
                      text-transform:uppercase;letter-spacing:0.1em;
                      color:rgba(245,241,234,0.3);margin:0 0 18px;text-align:center;">
              {{ $pedido->entradas->count() }} entradas · un QR por persona
            </p>
          @endif

          <div style="display:flex;gap:18px;justify-content:center;flex-wrap:wrap;">
            @foreach($pedido->entradas as $i => $entrada)
              <div style="display:flex;flex-direction:column;align-items:center;gap:8px;">
                <div class="me-qr-marco">
                  {{-- data-codigo es leído por JS para generar el QR --}}
                  <div id="qr-canvas-{{ $entrada->id }}"
                       class="qr-container"
                       data-codigo="{{ $entrada->codigo_qr }}"
                       style="width:180px;height:180px;display:block;"></div>
                </div>
                <p style="font-family:'Archivo Narrow',sans-serif;font-size:0.75rem;
                          font-weight:700;color:rgba(245,241,234,0.65);margin:0;
                          text-transform:uppercase;letter-spacing:0.08em;">
                  Entrada #{{ $i + 1 }}
                </p>
                <p style="font-family:monospace;font-size:0.55rem;
                          color:rgba(245,241,234,0.22);margin:0;">
                  {{ substr($entrada->codigo_qr, 0, 20) }}…
                </p>
              </div>
            @endforeach
          </div>

          <p style="text-align:center;font-family:'Archivo Narrow',sans-serif;font-size:0.7rem;
                    color:rgba(245,241,234,0.28);margin:18px 0 0;
                    text-transform:uppercase;letter-spacing:0.08em;">
            Presenta este QR en la entrada del evento
          </p>
        </div>

      </div>
    @endforeach

    {{-- Mensaje cuando no hay resultados para el filtro --}}
    <div id="me-no-resultados" style="display:none;text-align:center;padding:3rem 1.5rem;">
      <p style="font-family:'Anton',sans-serif;font-size:1.2rem;color:rgba(245,241,234,0.4);
                text-transform:uppercase;letter-spacing:0.02em;margin:0;">
        Sin entradas en esta categoría
      </p>
    </div>

  @endif

</div>
</div>

{{-- SweetAlert2 para confirmaciones --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- QRCode.js debe cargarse ANTES que nuestro script --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="{{ asset('js/entradas-mis-entradas.js') }}"></script>

@endsection

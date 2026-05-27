@extends('layouts.app')

@section('titulo', 'VIBEZ — Todos los Eventos')

{{-- @push('estilos')
<link rel="stylesheet" href="{{ asset('css/eventos.css') }}">
{{-- CSS extraido a public/css/eventos.css --}}
@endpush

@section('content') suprime el nav/footer del layout --}}
@section('content')

<link rel="stylesheet" href="{{ asset('css/vibez-home.css') }}">
<link rel="stylesheet" href="{{ asset('css/vibez-forms.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4/dist/flatpickr.min.css">


{{-- Variables JS mínimas para funciones de favoritos --}}
<script>
  window.FAVORITOS_IDS  = @json($favoritosIds ?? []);
  window.SEGUIMIENTOS_IDS = @json($seguimientosIds ?? []);
  window.USER_AUTH      = @json(Auth::check());
  window.LOGIN_URL      = @json(route('login'));
  window.PUEDE_SEGUIR   = @json(Auth::check() && Auth::user()->tipo_cuenta === 'cliente');
</script>

{{-- ════════════════ NAV ════════════════ --}}
@include('partials.home.nav')

{{-- ════════════════ CABECERA ════════════════ --}}
<section class="ev-cabecera" style="max-width:1480px;margin:0 auto;">
  <div class="mono" style="font-family:'Archivo Narrow',sans-serif;font-size:11px;color:var(--magenta);margin-bottom:16px;display:flex;align-items:center;gap:10px;text-transform:uppercase;letter-spacing:0.18em;">
    <span style="width:28px;height:1px;background:var(--magenta);display:inline-block;"></span>
    Todos los eventos · curados por VIBEZ
  </div>
  <h1 style="font-family:'Anton',sans-serif;font-size:clamp(56px,8vw,120px);margin:0;line-height:0.9;color:var(--ink);text-transform:uppercase;">
    Eventos
  </h1>
  <p style="font-family:'Archivo Narrow',sans-serif;font-size:14px;color:var(--ink-dim);margin:16px 0 0;text-transform:uppercase;letter-spacing:0.1em;">
    Descubre lo que está pasando cerca de ti
  </p>
</section>

{{-- ════════════════ BARRA DE FILTROS ════════════════ --}}
<div class="ev-filtros-bar">
  <form id="ev-form-filtros" method="GET" action="{{ route('eventos.index') }}" class="ev-filtros-inner" onsubmit="_evFormSubmit(event)">

    {{-- Búsqueda --}}
    <div class="ev-search-wrap">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
      <input type="text" name="buscar" class="ev-search-input"
        placeholder="Buscar evento, promotora..."
        value="{{ $buscar }}" autocomplete="off"
        oninput="buscarEventos(this.value)">
    </div>

    <div class="ev-sep"></div>

    {{-- Selector orden --}}
    <input type="hidden" id="ev-orden-val" name="orden" value="{{ $orden }}">
    <div class="ev-csel" id="ev-csel-orden">
      <div class="ev-csel-trigger" onclick="toggleEvCsel('ev-csel-orden')">
        <span id="ev-orden-label">{{ $ordenLabel }}</span>
        <span class="ev-csel-arrow">▾</span>
      </div>
      <ul class="ev-csel-menu">
        <li class="ev-csel-opt {{ $orden === 'nuevo' ? 'selected' : '' }}"
            onclick="pickEvOrden('nuevo', 'Más reciente')">Más reciente</li>
        <li class="ev-csel-opt {{ $orden === 'antiguo' ? 'selected' : '' }}"
            onclick="pickEvOrden('antiguo', 'Más antiguo')">Más antiguo</li>
      </ul>
    </div>

    <div class="ev-sep"></div>

    {{-- Fecha desde --}}
    <input type="text" id="ev-fecha-desde" name="fecha_desde"
      class="ev-date-input" placeholder="Desde"
      value="{{ $fechaDesde }}" autocomplete="off" readonly>

    {{-- Fecha hasta --}}
    <input type="text" id="ev-fecha-hasta" name="fecha_hasta"
      class="ev-date-input" placeholder="Hasta"
      value="{{ $fechaHasta }}" autocomplete="off" readonly>

    {{-- Buscar --}}
    <button type="button" class="ev-btn-buscar" onclick="_evFetch()">Buscar</button>

    {{-- Limpiar (JS controla display; siempre presente en DOM) --}}
    <a href="{{ route('eventos.index') }}" class="ev-limpiar" id="ev-limpiar"
       style="{{ ($buscar || $categoriaId || $fechaDesde || $fechaHasta || $orden !== 'nuevo') ? '' : 'display:none' }}">
      ✕ Limpiar
    </a>

    {{-- Contador --}}
    <span class="ev-count">
      {{ $eventos->total() }} {{ $eventos->total() === 1 ? 'evento' : 'eventos' }}
    </span>

  </form>
</div>

{{-- ════════════════ GRID + PAGINACIÓN (reemplazado vía AJAX) ════════════════ --}}
<div id="ev-resultado">
  @include('partials.eventos.resultado', [
      'eventos'        => $eventos,
      'favoritosIds'   => $favoritosIds,
      'seguimientosIds' => $seguimientosIds,
  ])
</div>

{{-- ════════════════ FOOTER ════════════════ --}}
@include('partials.home.footer')

{{-- Toast (favoritos) --}}
<div id="vibez-toast" class="toast" style="display:none;"></div>

{{-- ════════════════ SCRIPTS ════════════════ --}}
<script src="{{ asset('js/vibez-home.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4/dist/flatpickr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4/dist/l10n/es.js"></script>
<script src="{{ asset('js/eventos-filtros.js') }}"></script>
{{-- flatpickr init incluido en public/js/eventos-filtros.js --}}

@endsection

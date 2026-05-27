@extends('layouts.app')

@section('titulo', 'Mapa de eventos — VIBEZ')

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/mapa.css') }}">
{{-- CSS extraido a public/css/mapa.css --}}
@endpush

@section('content')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<link rel="stylesheet" href="{{ asset('css/vibez-home.css') }}">

<script>
  window.EVENTOS_DATA  = @json($eventosParaJs ?? []);
  window.FAVORITOS_IDS = @json($favoritosIds ?? []);
  window.USER_AUTH     = @json(Auth::check());
  window.LOGIN_URL     = @json(route('login'));
</script>

{{-- Nav --}}
@include('partials.home.nav')

{{-- Overlay que oscurece el mapa cuando el panel está abierto en móvil --}}
<div id="mapa-panel-overlay" onclick="vibezMapCerrarPanel()"></div>

{{-- Mapa pantalla completa --}}
<div style="position:relative;height:calc(100vh - 80px);display:flex;">

  {{-- Panel lateral de detalle del evento --}}
  <div id="mapa-panel"
       style="display:none;width:300px;flex-shrink:0;background:var(--bg);border-right:1px solid var(--line);overflow-y:auto;z-index:10;position:relative;">
  </div>

  {{-- Mapa --}}
  <div style="flex:1;position:relative;">
    <div id="vibez-map-full" style="width:100%;height:100%;"></div>

    {{-- Filtros sobre el mapa --}}
    <div id="mapa-filtros-wrap"
         style="position:absolute;top:16px;left:50%;transform:translateX(-50%);z-index:400;background:rgba(7,6,12,0.9);backdrop-filter:blur(14px);border:1px solid var(--line);padding:12px 20px;display:flex;align-items:center;gap:12px;flex-wrap:wrap;">

      {{-- Botón toggle — solo visible en móvil --}}
      <button id="mapa-filtros-btn"
              onclick="vibezToggleFiltrosMapa()"
              style="display:none;align-items:center;gap:6px;background:linear-gradient(135deg,#7c3aed,#a855f7);border:none;color:#fff;padding:6px 14px;border-radius:999px;font-size:11px;font-family:'Archivo Narrow',sans-serif;font-weight:600;letter-spacing:0.05em;cursor:pointer;">
        Filtrar <span id="mapa-filtros-arrow">▾</span>
      </button>

      {{-- Panel de chips --}}
      <div id="mapa-filtros-panel" style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
        <span class="mono" style="font-size:10px;color:var(--ink-dim);">
          {{ count($eventosParaJs ?? []) }} eventos en el mapa
        </span>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
          @foreach($categorias as $cat)
            <button class="chip vibez-cat-chip" data-cat="{{ $cat->nombre }}"
                    onclick="vibezFiltrarMapa('{{ $cat->nombre }}')"
                    style="font-size:10px;padding:4px 10px;">
              {{ $cat->nombre }}
            </button>
          @endforeach
          <button class="chip active vibez-cat-chip" data-cat="Todo"
                  onclick="vibezFiltrarMapa('Todo')"
                  style="font-size:10px;padding:4px 10px;">
            Todo
          </button>
        </div>
      </div>
    </div>

    {{-- Leyenda --}}
    <div style="position:absolute;bottom:20px;left:20px;z-index:400;background:rgba(7,6,12,0.9);backdrop-filter:blur(10px);padding:14px;border:1px solid var(--line);">
      <div class="mono" style="font-size:9px;color:var(--ink-dim);margin-bottom:8px;">LEYENDA</div>
      <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
        <span style="width:12px;height:12px;border-radius:50%;background:var(--magenta);display:inline-block;"></span>
        <span style="font-size:11px;">Próximo</span>
      </div>
      <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
        <span style="width:16px;height:16px;border-radius:50%;background:var(--magenta);box-shadow:0 0 12px var(--magenta);display:inline-block;"></span>
        <span style="font-size:11px;">Featured / destacado</span>
      </div>
      <div style="display:flex;align-items:center;gap:8px;">
        <span style="width:16px;height:16px;border-radius:50%;background:#00ff88;box-shadow:0 0 12px #00ff88;display:inline-block;"></span>
        <span style="font-size:11px;">En curso ahora</span>
      </div>
    </div>

    {{-- Volver --}}
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('home') }}"
       style="position:absolute;top:16px;right:16px;z-index:400;background:rgba(7,6,12,0.85);border:1px solid var(--line);color:var(--ink);padding:8px 16px;text-decoration:none;font-family:'Archivo Narrow',sans-serif;font-size:12px;text-transform:uppercase;letter-spacing:0.08em;backdrop-filter:blur(10px);">
      ← Volver
    </a>
  </div>
</div>

{{-- Modal detalle (para comprar desde el mapa) --}}
@include('partials.home.detail-modal')
<div id="vibez-toast" class="toast" style="display:none;"></div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('js/vibez-home.js') }}"></script>
<script src="{{ asset('js/mapa-filtros.js') }}"></script>
{{-- JS en public/js/mapa-filtros.js --}}

@endsection

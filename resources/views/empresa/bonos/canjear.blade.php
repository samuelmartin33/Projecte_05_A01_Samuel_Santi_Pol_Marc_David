@extends('layouts.app')

@section('titulo', 'Canjear bonos — ' . $empresa->nombre_empresa . ' — VIBEZ')

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/canje-bono.css') }}">
@endpush

@section('content')

@include('partials.home.nav')

<div class="canje-wrapper">

    {{-- Hero --}}
    <div class="canje-hero">
        <div style="font-size:2.5rem;margin-bottom:12px;position:relative;">🍹</div>
        <h1>Canjear Bono</h1>
        <p>{{ $empresa->nombre_empresa }} · Escanea el QR del cliente</p>
    </div>

    {{-- Paso 1: Elegir tipo y cantidad --}}
    <div class="canje-card" id="panel-seleccion">
        <div class="canje-card__titulo">① Elige la bebida</div>

        {{-- Tipos de bebida: cargados dinámicamente desde la BD --}}
        <div class="tipo-grid">
            @foreach($tipos as $tipo)
            <button class="tipo-btn" onclick="seleccionarTipo('{{ addslashes($tipo->nombre) }}', this)">
                {{ $tipo->icono }}<br>{{ $tipo->nombre }}
            </button>
            @endforeach
        </div>

        {{-- Cantidad --}}
        <div class="canje-card__titulo" style="margin-top:1rem;">Cantidad</div>
        <div class="cantidad-ctrl">
            <button class="cantidad-btn" onclick="cambiarCantidad(-1)">−</button>
            <span class="cantidad-valor" id="cantidad-valor">1</span>
            <button class="cantidad-btn" onclick="cambiarCantidad(1)">+</button>
        </div>
        <p style="text-align:center;font-family:'Archivo Narrow',sans-serif;font-size:0.6875rem;color:rgba(245,241,234,0.3);text-transform:uppercase;letter-spacing:0.1em;margin:8px 0 0;">
            bebidas a canjear
        </p>
    </div>

    {{-- Panel resultado --}}
    <div id="resultado-panel" class="resultado-panel">
        <div class="resultado-titulo" id="resultado-titulo"></div>
        <p class="resultado-detalle" id="resultado-detalle"></p>
        <div class="resultado-saldo" id="resultado-saldo" style="display:none;"></div>
    </div>

    {{-- Paso 2: Escanear QR --}}
    <div class="canje-card">
        <div class="canje-card__titulo">② Escanea el QR del bono</div>

        {{-- Pestañas --}}
        <div class="tab-bar">
            <button class="tab-btn activo" id="tab-camara" onclick="mostrarTab('camara')">
                📷 Cámara
            </button>
            <button class="tab-btn" id="tab-manual" onclick="mostrarTab('manual')">
                ⌨️ Manual
            </button>
        </div>

        {{-- Vista cámara --}}
        <div id="panel-camara">
            <div id="qr-reader"></div>
            <button onclick="toggleEscaner()"
                    id="btn-escaner"
                    style="width:100%;margin-top:10px;padding:13px;background:rgba(74,222,128,0.10);border:1.5px solid rgba(74,222,128,0.35);color:#4ade80;font-family:'Archivo Narrow',sans-serif;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.12em;cursor:pointer;transition:all 0.15s;">
                Iniciar cámara
            </button>
        </div>

        {{-- Vista manual --}}
        <div id="panel-manual" style="display:none;">
            <input type="text" id="input-manual" class="input-manual"
                   placeholder="Pega o escribe el código QR aquí…"
                   autocomplete="off" autocorrect="off" spellcheck="false"
                   onkeydown="if(event.key==='Enter') procesarManual()">
            <button class="btn-canjear" style="margin-top:10px;" onclick="procesarManual()">
                Validar código
            </button>
        </div>
    </div>

    {{-- Historial de la sesión --}}
    <div class="canje-card" id="panel-historial" style="display:none;">
        <div class="canje-card__titulo">Últimos canjes de esta sesión</div>
        <div id="historial-lista"></div>
    </div>

</div>

@include('partials.home.footer')

@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
window.canjeData = {
    urlValidar: '{{ route('camarero.bonos.validar-canje') }}',
    csrf: '{{ csrf_token() }}',
};
</script>
<script src="{{ asset('js/canje-bono.js') }}"></script>
@endpush

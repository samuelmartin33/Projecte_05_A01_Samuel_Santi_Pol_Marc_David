@extends('layouts.app')

@section('titulo', 'Validar entradas QR — ' . $empresa->nombre_empresa)

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/empresa-validacion.css') }}">
{{-- CSS extraído a public/css/empresa-validacion.css --}}
@endpush

@section('content')

@include('partials.home.nav')

<div class="validacion-wrapper">

    {{-- Hero --}}
    <div class="validacion-hero">
        <div style="display:inline-flex;align-items:center;justify-content:center;width:52px;height:52px;background:rgba(124,58,237,0.2);border-radius:50%;margin-bottom:0.875rem;">
            <svg style="width:28px;height:28px;color:#a78bfa" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
            </svg>
        </div>
        <h1>Validar entradas QR</h1>
        <p>{{ $empresa->nombre_empresa }} · Escanea o introduce el código para validar el acceso</p>
    </div>

    {{-- Selector de evento (opcional, solo para referencia visual) --}}
    @if($eventos->count() > 1)
    <div class="evento-selector">
        <label>Evento a validar</label>
        <input type="hidden" id="filtro-evento" value="">
        <div class="ev-csel" id="ev-csel-main" onmouseleave="document.getElementById('ev-csel-main').classList.remove('open')">
            <div class="ev-csel-trigger" onclick="toggleEvCsel()">
                <span id="ev-csel-label">— Todos los eventos activos —</span>
                <svg class="ev-csel-arrow" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
            </div>
            <div class="ev-csel-menu">
                <div class="ev-csel-opt sel" data-val="" onclick="seleccionarEvento(this, '')">— Todos los eventos activos —</div>
                @foreach($eventos as $ev)
                    <div class="ev-csel-opt" data-val="{{ $ev->id }}" onclick="seleccionarEvento(this, '{{ $ev->id }}')">{{ $ev->titulo }}</div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Pestañas de modo --}}
    <div class="modo-tabs">
        <button class="modo-tab activo" id="tab-camara" onclick="cambiarModo('camara')">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Cámara
        </button>
        <button class="modo-tab" id="tab-manual" onclick="cambiarModo('manual')">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Manual
        </button>
    </div>

    {{-- Panel cámara --}}
    <div class="panel-camara" id="panel-camara">
        <div id="qr-reader"></div>
    </div>

    {{-- Panel manual --}}
    <div class="panel-manual" id="panel-manual">
        <div class="manual-form">
            <label for="input-qr">Apunta aquí con el lector o escribe el código</label>
            <div class="manual-input-row">
                <input type="text"
                       id="input-qr"
                       placeholder="Escanea con el lector o introduce el UUID…"
                       autocomplete="off"
                       autocorrect="off"
                       spellcheck="false"
                       onkeydown="if(event.key==='Enter') validarManual()">
                <button class="btn-validar-manual" id="btn-manual" onclick="validarManual()">
                    Validar
                </button>
            </div>
        </div>
    </div>

    {{-- Resultado --}}
    <div class="resultado-card" id="resultado-card">
        <div class="resultado-icono" id="resultado-icono"></div>
        <p class="resultado-titulo" id="resultado-titulo"></p>
        <div class="resultado-body" id="resultado-body"></div>
        <p class="resultado-error-msg" id="resultado-msg"></p>
    </div>

    {{-- Historial de escaneos de esta sesión --}}
    <p class="historial-titulo" id="historial-label" style="display:none">Escaneos de esta sesión</p>
    <div class="historial-lista" id="historial-lista"></div>

</div>

@endsection

@push('scripts')
<script>window.VALIDACION_URL = '{{ route("empresa.validacion.validar") }}';</script>
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script src="{{ asset('js/empresa-validacion.js') }}"></script>
{{-- JS en public/js/empresa-validacion.js --}}
@endpush

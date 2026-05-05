@extends('layouts.app')
@section('titulo', 'Confirmación de compra — VIBEZ')

{{-- Carga el CSS específico de esta página antes del contenido --}}
@push('estilos')
<link rel="stylesheet" href="{{ asset('css/entradas-confirmacion.css') }}">
@endpush

@section('contenido')

{{-- ════════ HERO DE ÉXITO ════════ --}}
{{-- Cabecera verde que confirma visualmente que la compra fue correcta --}}
<div class="confirmacion-hero">
    {{-- Círculo semitransparente con icono de check --}}
    <div class="confirmacion-hero-icono">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
    </div>
    <h1 class="confirmacion-hero-titulo">
        ¡Compra realizada!
    </h1>
    <p class="confirmacion-hero-subtitulo">
        Pedido #{{ $pedido->id }} · {{ \Carbon\Carbon::parse($pedido->fecha_creacion)->locale('es')->isoFormat('D [de] MMMM [de] YYYY, HH:mm') }}
    </p>
</div>

{{-- ════════ CONTENIDO PRINCIPAL ════════ --}}
<div class="max-w-2xl mx-auto px-4 py-8">

    {{-- Resumen del pedido: evento + importe total --}}
    <div class="ficha-evento" style="margin-bottom:1.5rem">
        <h2 class="seccion-titulo" style="margin-bottom:1rem">Resumen del pedido</h2>

        {{-- Obtenemos el primer evento asociado al pedido para mostrar sus datos --}}
        @php $primerEvento = $pedido->entradas->first()?->evento; @endphp
        @if ($primerEvento)
            {{-- Fila con icono del evento y sus datos principales --}}
            <div class="confirmacion-evento-fila">
                <div class="confirmacion-evento-icono">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <div>
                    <p class="confirmacion-evento-titulo">{{ $primerEvento->titulo }}</p>
                    <p class="confirmacion-evento-fecha">
                        {{ \Carbon\Carbon::parse($primerEvento->fecha_inicio)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
                    </p>
                    @if($primerEvento->ubicacion_nombre)
                        <p class="confirmacion-evento-lugar">📍 {{ $primerEvento->ubicacion_nombre }}</p>
                    @endif
                </div>
            </div>
        @endif

        {{-- Fila de totales: cantidad de entradas (izquierda) + importe total (derecha) --}}
        <div class="confirmacion-resumen-footer">
            <div>
                <p class="confirmacion-entrada-count">{{ $pedido->entradas->count() }} entrada(s)</p>
            </div>
            <div>
                <p class="confirmacion-total-label">Total</p>
                <p class="text-gradient" style="margin:0;font-weight:900;font-size:1.5rem">
                    @if($pedido->total_final == 0)
                        Gratis
                    @else
                        {{ number_format($pedido->total_final, 2) }} €
                    @endif
                </p>
            </div>
        </div>
    </div>

    {{-- Lista de entradas individuales con su código QR --}}
    <h2 class="seccion-titulo" style="margin-bottom:1rem">
        Tus entradas ({{ $pedido->entradas->count() }})
    </h2>

    @foreach ($pedido->entradas as $i => $entrada)
        <div class="ficha-evento" style="margin-bottom:1.25rem;overflow:hidden">

            {{-- Cabecera: número de entrada y precio --}}
            <div class="confirmacion-entrada-cabecera">
                <div>
                    <p class="confirmacion-entrada-num">
                        Entrada #{{ $i + 1 }}
                    </p>
                    {{-- UUID del QR en tipografía monoespaciada para facilitar la lectura --}}
                    <p class="confirmacion-entrada-codigo">{{ $entrada->codigo_qr }}</p>
                </div>
                <div class="confirmacion-entrada-precio-col">
                    <p class="text-gradient" style="margin:0;font-weight:800;font-size:1.1rem">
                        @if($entrada->precio_pagado == 0) Gratis
                        @else {{ number_format($entrada->precio_pagado, 2) }} €
                        @endif
                    </p>
                </div>
            </div>

            {{-- Separador con estilo talonario (línea discontinua de borde a borde) --}}
            <div class="confirmacion-entrada-divisor"></div>

            {{-- Código QR generado por entradas-confirmacion.js al cargar la página --}}
            <div class="confirmacion-qr-centrado">
                <div class="confirmacion-qr-marco">
                    {{-- data-codigo es leído por el JS para generar el QR dentro de este div --}}
                    <div id="qr-{{ $i }}" data-codigo="{{ $entrada->codigo_qr }}"
                         class="confirmacion-qr-canvas"></div>
                </div>
                <p class="confirmacion-qr-texto">
                    Presenta este QR en la entrada del evento
                </p>
            </div>
        </div>
    @endforeach

    {{-- Acciones al finalizar: explorar más eventos o ver perfil --}}
    <div class="confirmacion-acciones">
        <a href="{{ route('home') }}" class="btn-comprar">
            Explorar más eventos
        </a>
        <a href="{{ route('perfil') }}" style="color:#7c3aed;font-size:0.875rem;text-decoration:none">
            Ver mi perfil
        </a>
    </div>

</div>

@endsection

{{-- Carga la librería QRCode.js ANTES de nuestro script,
     porque entradas-confirmacion.js depende de que QRCode exista globalmente --}}
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="{{ asset('js/entradas-confirmacion.js') }}"></script>
@endpush

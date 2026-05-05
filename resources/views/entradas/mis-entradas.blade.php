@extends('layouts.app')
@section('titulo', 'Mis entradas — VIBEZ')

{{-- Carga el CSS específico de esta página antes del contenido --}}
@push('estilos')
<link rel="stylesheet" href="{{ asset('css/entradas-mis-entradas.css') }}">
@endpush

@section('contenido')

{{-- ════════ CABECERA ════════ --}}
<section class="perfil-hero">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <a href="{{ route('home') }}" class="btn-volver" style="display:inline-flex;margin-bottom:1.5rem">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver
        </a>

        <h1 class="text-2xl sm:text-3xl font-black text-white">
            Mis entradas
        </h1>
        <p class="text-white/50 text-sm mt-1">
            {{ $pedidos->sum(fn($p) => $p->entradas->count()) }}
            {{ $pedidos->sum(fn($p) => $p->entradas->count()) === 1 ? 'entrada' : 'entradas' }} en total
        </p>

    </div>
</section>

{{-- ════════ CONTENIDO PRINCIPAL ════════ --}}
<div class="max-w-3xl mx-auto px-4 py-8">

    @if($pedidos->isEmpty())

        {{-- Estado vacío: el usuario no ha comprado ninguna entrada todavía --}}
        <div class="seccion-detalle mis-entradas-vacio">
            <div class="mis-entradas-vacio-icono">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
            </div>
            <p class="mis-entradas-vacio-titulo">
                Aún no tienes entradas
            </p>
            <p class="mis-entradas-vacio-texto">
                Explora los eventos disponibles y compra tu primera entrada.
            </p>
            <a href="{{ route('home') }}" class="btn-comprar mis-entradas-vacio-btn">
                Explorar eventos
            </a>
        </div>

    @else

        {{-- Iteramos los pedidos del usuario (ordenados del más reciente al más antiguo) --}}
        @foreach($pedidos as $pedido)
            {{-- Obtenemos el evento del primer ticket para mostrar el nombre y fecha --}}
            @php $evento = $pedido->entradas->first()?->evento; @endphp

            {{-- Tarjeta estilo talonario para este pedido --}}
            <div class="seccion-detalle mis-entradas-pedido">

                {{-- Cabecera oscura con datos del evento y precio total --}}
                <div class="mis-entradas-pedido-header">
                    <div class="mis-entradas-pedido-info">
                        <p class="mis-entradas-pedido-titulo">
                            {{ $evento?->titulo ?? 'Evento eliminado' }}
                        </p>
                        @if($evento)
                            <p class="mis-entradas-pedido-fecha">
                                {{ \Carbon\Carbon::parse($evento->fecha_inicio)->locale('es')->isoFormat('D MMM YYYY') }}
                                @if($evento->ubicacion_nombre)
                                    &nbsp;·&nbsp;{{ $evento->ubicacion_nombre }}
                                @endif
                            </p>
                        @endif
                        <p class="mis-entradas-pedido-meta">
                            Pedido #{{ $pedido->id }}
                            · {{ \Carbon\Carbon::parse($pedido->fecha_creacion)->locale('es')->isoFormat('D MMM YYYY, HH:mm') }}
                        </p>
                    </div>
                    <div class="mis-entradas-pedido-precio-col">
                        {{-- Badge con la cantidad de entradas --}}
                        <span class="mis-entradas-badge-entradas">
                            {{ $pedido->entradas->count() }}
                            {{ $pedido->entradas->count() === 1 ? 'entrada' : 'entradas' }}
                        </span>
                        <p class="mis-entradas-precio-total">
                            @if($pedido->total_final == 0)
                                <span class="precio-gratis">Gratis</span>
                            @else
                                <span class="text-gradient">{{ number_format($pedido->total_final, 2) }} €</span>
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Lista de entradas individuales --}}
                <div class="mis-entradas-entradas-lista">
                    @foreach($pedido->entradas as $i => $entrada)

                        {{-- Separador estilo talonario: línea discontinua con círculos en los extremos --}}
                        <div class="mis-entradas-talonario-sep">
                            <div class="mis-entradas-talonario-circulo-izq"></div>
                            <div class="mis-entradas-talonario-circulo-der"></div>
                        </div>

                        <div class="mis-entradas-entrada-fila">
                            {{-- Cabecera: número de entrada + botón para mostrar/ocultar QR --}}
                            <div class="mis-entradas-entrada-header">
                                <div class="mis-entradas-entrada-info">
                                    <p class="mis-entradas-entrada-num">
                                        Entrada #{{ $i + 1 }}
                                    </p>
                                    {{-- UUID del código QR (truncado si es muy largo) --}}
                                    <p class="mis-entradas-entrada-codigo">{{ $entrada->codigo_qr }}</p>
                                </div>
                                {{-- toggleQr() en mis-entradas.js alterna display:none/block --}}
                                <button id="btn-{{ $entrada->id }}"
                                        onclick="toggleQr('qr-{{ $entrada->id }}','btn-{{ $entrada->id }}')"
                                        class="btn-secundario mis-entradas-btn-qr">
                                    Ver QR
                                </button>
                            </div>

                            {{-- Panel QR: oculto por defecto.
                                 style="display:none" es necesario aquí para que el JS pueda
                                 detectar el estado con panel.style.display !== 'none' --}}
                            <div id="qr-{{ $entrada->id }}" style="display:none"
                                 class="mis-entradas-qr-panel">
                                <div class="mis-entradas-qr-marco">
                                    {{-- data-codigo es leído por mis-entradas.js para generar el QR --}}
                                    <div id="qr-canvas-{{ $entrada->id }}"
                                         data-codigo="{{ $entrada->codigo_qr }}"
                                         class="mis-entradas-qr-canvas"></div>
                                </div>
                                <p class="mis-entradas-qr-texto">
                                    Presenta este QR en la entrada del evento
                                </p>
                            </div>
                        </div>

                    @endforeach
                    <div style="padding-bottom:4px"></div>
                </div>

            </div>
        @endforeach

    @endif

</div>

@endsection

{{-- Carga la librería QRCode.js ANTES de nuestro script,
     porque entradas-mis-entradas.js depende de que QRCode exista globalmente --}}
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="{{ asset('js/entradas-mis-entradas.js') }}"></script>
@endpush

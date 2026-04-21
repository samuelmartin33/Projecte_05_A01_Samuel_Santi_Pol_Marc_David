@extends('layouts.app')

@section('titulo', $oferta->titulo)

@section('contenido')

{{-- ════════════════════════════════════════════════════
     HERO DE LA OFERTA — sin imagen, fondo navy
════════════════════════════════════════════════════ --}}
<div class="hero-trabajo">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

        {{-- Botón volver --}}
        <a href="{{ route('home') }}?categoria=trabajo" class="btn-volver">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver a Bolsa de Trabajo
        </a>

        {{-- Badge de tipo --}}
        <span class="badge-trabajo inline-block mt-6">Oferta de trabajo</span>

        {{-- Título de la oferta --}}
        <h1 class="text-3xl sm:text-5xl font-black text-white mt-3 leading-tight max-w-3xl">
            {{ $oferta->titulo }}
        </h1>

        {{-- Datos clave: empresa, ubicación, salario --}}
        <div class="flex flex-wrap gap-6 mt-6">

            {{-- Empresa --}}
            @if ($oferta->organizador?->empresa)
                <div class="dato-hero">
                    <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="font-semibold">{{ $oferta->organizador->empresa->nombre_empresa }}</span>
                </div>
            @endif

            {{-- Ubicación --}}
            @if ($oferta->ubicacion)
                <div class="dato-hero">
                    <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    </svg>
                    <span>{{ $oferta->ubicacion }}</span>
                </div>
            @endif

            {{-- Salario --}}
            <div class="dato-hero">
                <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-bold text-lg text-green-300">{{ $oferta->salario_formateado }}</span>
            </div>

        </div>

    </div>
</div>

{{-- ════════════════════════════════════════════════════
     CUERPO DEL DETALLE
════════════════════════════════════════════════════ --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        {{-- ─── Columna izquierda: descripción y requisitos ─── --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- Descripción de la oferta --}}
            @if ($oferta->descripcion)
                <section class="seccion-detalle">
                    <h2 class="seccion-titulo">Descripción del puesto</h2>
                    <p class="text-navy/80 leading-relaxed">{{ $oferta->descripcion }}</p>
                </section>
            @endif

            {{-- Requisitos --}}
            @if ($oferta->requisitos)
                <section class="seccion-detalle">
                    <h2 class="seccion-titulo">Requisitos</h2>
                    <p class="text-navy/80 leading-relaxed">{{ $oferta->requisitos }}</p>
                </section>
            @endif

            {{-- Datos adicionales --}}
            <section class="seccion-detalle">
                <h2 class="seccion-titulo">Detalles</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">

                    <div class="ficha-dato">
                        <span class="ficha-dato-label">Vacantes</span>
                        <span class="ficha-dato-valor">{{ $oferta->vacantes }}</span>
                    </div>

                    @if ($oferta->fecha_inicio_trabajo)
                        <div class="ficha-dato">
                            <span class="ficha-dato-label">Inicio</span>
                            <span class="ficha-dato-valor">
                                {{ \Carbon\Carbon::parse($oferta->fecha_inicio_trabajo)->format('d/m/Y') }}
                            </span>
                        </div>
                    @endif

                    @if ($oferta->fecha_fin_trabajo)
                        <div class="ficha-dato">
                            <span class="ficha-dato-label">Fin contrato</span>
                            <span class="ficha-dato-valor">
                                {{ \Carbon\Carbon::parse($oferta->fecha_fin_trabajo)->format('d/m/Y') }}
                            </span>
                        </div>
                    @endif

                </div>
            </section>

        </div>

        {{-- ─── Columna derecha: botón de postulación ─── --}}
        <div class="lg:col-span-1">
            <div class="lg:sticky lg:top-24">
                <div class="ficha-compra">

                    {{-- Salario destacado --}}
                    <div class="text-center mb-6">
                        <p class="text-navy/50 text-sm uppercase tracking-widest font-semibold mb-1">Salario</p>
                        <p class="text-2xl font-black text-green-600">{{ $oferta->salario_formateado }}</p>
                    </div>

                    {{-- Vacantes disponibles --}}
                    <div class="bg-purple-50 rounded-xl p-4 text-center mb-6">
                        <p class="text-3xl font-black text-navy">{{ $oferta->vacantes }}</p>
                        <p class="text-navy/50 text-sm">vacante{{ $oferta->vacantes !== 1 ? 's' : '' }} disponible{{ $oferta->vacantes !== 1 ? 's' : '' }}</p>
                    </div>

                    {{-- Botón de postulación --}}
                    <button class="btn-comprar w-full"
                            onclick="abrirPostulacion({{ $oferta->id }})">
                        Postularme ahora
                    </button>

                    <p class="text-center text-navy/40 text-xs mt-4">
                        Tu candidatura se enviará al organizador
                    </p>

                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
/**
 * Abre el flujo de postulación a la oferta de trabajo.
 * @param {number} ofertaId - ID de la oferta en la BD
 */
function abrirPostulacion(ofertaId) {
    // TODO: Integrar con el sistema de candidaturas de VIBEZ
    alert('Próximamente: formulario de candidatura para la oferta #' + ofertaId);
}
</script>
@endpush

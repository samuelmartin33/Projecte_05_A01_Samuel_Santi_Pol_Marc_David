@extends('layouts.app')

@section('titulo', 'Candidatos — ' . $oferta->titulo)

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/empresa-candidaturas-detalle.css') }}">
@endpush

@section('contenido')

{{-- ══ Hero ══ --}}
<section class="cand-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Breadcrumb --}}
        <a href="{{ route('empresa.candidaturas.ofertas') }}"
           class="inline-flex items-center gap-1.5 text-slate-400 hover:text-white text-sm font-medium transition-colors mb-5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Mis ofertas
        </a>

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold mb-3"
                     style="background:rgba(139,92,246,0.15);border:1px solid rgba(139,92,246,0.3);color:#c084fc;letter-spacing:.06em;text-transform:uppercase">
                    {{ $oferta->categoria?->nombre ?? 'Oferta de trabajo' }}
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white leading-tight">
                    {{ $oferta->titulo }}
                </h1>
                <div class="flex flex-wrap gap-4 mt-2 text-sm text-slate-400">
                    @if($oferta->ubicacion)
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            {{ $oferta->ubicacion }}
                        </span>
                    @endif
                    <span class="text-green-400 font-semibold">{{ $oferta->salario_formateado }}</span>
                    <span id="oferta-estado-badge" class="{{ $oferta->estado ? 'text-green-400' : 'text-slate-500' }} font-semibold">
                        {{ $oferta->estado ? '● Activa' : '○ Cerrada' }}
                    </span>
                </div>
            </div>

            {{-- Acciones oferta --}}
            <div class="flex flex-col items-end gap-3">
                {{-- Contador total --}}
                <div style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);border-radius:.875rem;padding:1rem 1.5rem;text-align:center;min-width:130px">
                    <p class="text-3xl font-black text-purple-300">{{ $candidaturas->total() }}</p>
                    <p class="text-slate-400 text-xs mt-0.5">candidatura{{ $candidaturas->total() !== 1 ? 's' : '' }}</p>
                </div>
                {{-- Botón cerrar / reabrir --}}
                <button id="btn-cerrar-oferta"
                        onclick="toggleOferta()"
                        data-estado="{{ $oferta->estado }}"
                        data-url="{{ route('empresa.candidaturas.cerrar-oferta', $oferta->id) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold transition-all
                               {{ $oferta->estado
                                    ? 'bg-red-500/20 text-red-300 border border-red-500/30 hover:bg-red-500/30'
                                    : 'bg-green-500/20 text-green-300 border border-green-500/30 hover:bg-green-500/30' }}">
                    @if($oferta->estado)
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cerrar oferta
                    @else
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M5 13l4 4L19 7"/>
                        </svg>
                        Reabrir oferta
                    @endif
                </button>
            </div>
        </div>
    </div>
</section>

{{-- ══ Filtros de estado ══ --}}
@php
    $baseUrl   = route('empresa.candidaturas.detalle', $oferta->id);
    $ordenAct  = request('orden', 'reciente');
    $estadoAct = request('estado', '');
    $estados   = [
        ''  => ['label' => 'Todos',           'color' => ''],
        '1' => ['label' => 'Nuevos',          'color' => 'text-blue-600'],
        '2' => ['label' => 'Revisados',       'color' => 'text-amber-600'],
        '3' => ['label' => 'Preseleccionados','color' => 'text-green-600'],
        '4' => ['label' => 'Rechazados',      'color' => 'text-red-500'],
    ];
@endphp
<div class="sticky top-16 z-30 bg-white border-b border-navy/8 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <div class="flex flex-wrap items-center gap-2">

            @foreach($estados as $val => $info)
                <button type="button"
                        data-estado="{{ $val }}"
                        onclick="cargarCandidaturas('{{ $val }}', _ordenActual)"
                        class="tab-estado {{ $estadoAct == $val ? 'activo' : '' }}">
                    {{ $info['label'] }}
                </button>
            @endforeach

            <div class="ml-auto flex items-center gap-2">
                <label class="text-navy/40 text-xs font-semibold uppercase tracking-wider">Ordenar:</label>
                <select class="filtro-select text-xs border border-navy/10 rounded-lg px-2 py-1.5 outline-none"
                        onchange="cargarCandidaturas(_estadoActual, this.value)">
                    <option value="reciente" {{ $ordenAct === 'reciente' ? 'selected':'' }}>Más reciente</option>
                    <option value="nombre"   {{ $ordenAct === 'nombre'   ? 'selected':'' }}>Nombre A–Z</option>
                    <option value="estado"   {{ $ordenAct === 'estado'   ? 'selected':'' }}>Por estado</option>
                </select>
            </div>

        </div>
    </div>
</div>

{{-- ══ Lista de candidaturas ══ --}}
@php
$candidaturasJson = $candidaturas->map(function($c) {
    return [
        'id'            => $c->id,
        'nombre'        => $c->nombreCompleto(),
        'email'         => $c->email_candidato ?? '',
        'telefono'      => $c->telefono_candidato ?? '',
        'ciudad'        => $c->ciudad_candidato ?? '',
        'linkedin'      => $c->linkedin_candidato ?? '',
        'perfil'        => $c->perfil_profesional ?? '',
        'habilidades'   => $c->habilidades ?? '',
        'idiomas'       => $c->idiomas ?? '',
        'carta'         => $c->carta_presentacion ?? '',
        'tiene_archivo' => $c->tieneArchivo(),
        'descargar_url' => route('empresa.candidaturas.descargar', $c->id),
        'fecha'         => \Carbon\Carbon::parse($c->fecha_creacion)->format('d/m/Y H:i'),
    ];
});
@endphp
<main id="candidaturas-lista" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
<script type="application/json" id="candidaturas-json">@json($candidaturasJson)</script>

    @if($candidaturas->isEmpty())
        <div class="text-center py-20">
            <div class="w-16 h-16 bg-navy/5 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-navy/25" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h3 class="text-navy/50 font-bold text-lg">Sin candidaturas
                @if(request('estado')) en este estado @endif
            </h3>
            <p class="text-navy/30 text-sm mt-1">Cuando alguien se postule aparecerá aquí.</p>
            @if(request('estado'))
                <a href="{{ route('empresa.candidaturas.detalle', $oferta->id) }}"
                   class="mt-4 inline-block text-purple-600 text-sm font-semibold hover:underline">
                    Ver todas las candidaturas
                </a>
            @endif
        </div>
    @else

        <div class="bg-white rounded-2xl border border-navy/8 overflow-hidden shadow-sm">

            {{-- Cabecera de tabla --}}
            <div class="grid grid-cols-[2.5rem_1fr_auto] gap-4 px-5 py-3 bg-navy/3 border-b border-navy/8">
                <div></div>
                <p class="text-xs font-700 text-navy/40 uppercase tracking-wider font-bold">Candidato</p>
                <p class="text-xs font-700 text-navy/40 uppercase tracking-wider font-bold">Acciones</p>
            </div>

            @foreach($candidaturas as $cand)
            <div class="cand-row" id="row-{{ $cand->id }}">

                {{-- Avatar --}}
                <div class="cand-avatar">{{ $cand->iniciales() }}</div>

                {{-- Info candidato --}}
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-0.5">
                        <span class="font-bold text-navy text-sm">{{ $cand->nombreCompleto() }}</span>

                        {{-- Estado badge (se actualiza via JS) --}}
                        <span id="badge-{{ $cand->id }}" class="estado-badge {{ $cand->estadoClases() }}">
                            {{ $cand->estadoLabel() }}
                        </span>

                        @if($cand->tieneArchivo())
                            <span class="inline-flex items-center gap-1 text-xs text-green-600 font-semibold">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                                PDF adjunto
                            </span>
                        @endif
                    </div>

                    <div class="flex flex-wrap gap-x-3 gap-y-0.5 text-xs text-navy/45">
                        @if($cand->email_candidato)
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ $cand->email_candidato }}
                            </span>
                        @endif
                        @if($cand->telefono_candidato)
                            <span>{{ $cand->telefono_candidato }}</span>
                        @endif
                        @if($cand->ciudad_candidato)
                            <span>{{ $cand->ciudad_candidato }}</span>
                        @endif
                        <span class="text-navy/30">
                            {{ \Carbon\Carbon::parse($cand->fecha_creacion)->diffForHumans() }}
                        </span>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="flex items-center gap-2 flex-shrink-0">

                    {{-- Cambiar estado --}}
                    <select class="estado-select estado-{{ $cand->estado_candidatura }}"
                            id="sel-{{ $cand->id }}"
                            onchange="cambiarEstado({{ $cand->id }}, this.value, this)"
                            title="Cambiar estado">
                        <option value="1" {{ $cand->estado_candidatura == 1 ? 'selected':'' }}>Nuevo</option>
                        <option value="2" {{ $cand->estado_candidatura == 2 ? 'selected':'' }}>Revisado</option>
                        <option value="3" {{ $cand->estado_candidatura == 3 ? 'selected':'' }}>Preseleccionado</option>
                        <option value="4" {{ $cand->estado_candidatura == 4 ? 'selected':'' }}>Rechazado</option>
                    </select>

                    {{-- Ver CV --}}
                    <button onclick="verCv({{ $cand->id }})"
                            title="Ver CV completo"
                            class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 hover:bg-purple-100 flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>

                    {{-- Descargar CV (si tiene archivo) --}}
                    @if($cand->tieneArchivo())
                        <a href="{{ route('empresa.candidaturas.descargar', $cand->id) }}"
                           title="Descargar CV"
                           class="w-8 h-8 rounded-lg bg-green-50 text-green-600 hover:bg-green-100 flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Paginación --}}
        @if($candidaturas->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $candidaturas->links() }}
            </div>
        @endif

    @endif
</main>

{{-- ══ Modal de CV completo ══ --}}
<div id="cv-overlay" class="cv-modal-overlay" onclick="cerrarCvModal(event)">
    <div class="cv-modal" id="cv-modal-box">
        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-navy/8 flex-shrink-0">
            <div>
                <h3 class="font-black text-navy text-lg" id="cv-modal-nombre">CV Candidato</h3>
                <p class="text-navy/40 text-xs" id="cv-modal-sub"></p>
            </div>
            <button onclick="cerrarCvModalBtn()" class="text-navy/30 hover:text-navy transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Spinner mientras carga --}}
        <div id="cv-spinner" class="cv-modal-body flex items-center justify-center py-16">
            <div class="w-8 h-8 border-4 border-purple-200 border-t-purple-600 rounded-full animate-spin"></div>
        </div>

        {{-- Contenido --}}
        <div id="cv-content" class="cv-modal-body hidden"></div>

        {{-- Footer --}}
        <div class="flex gap-2 px-5 py-3 border-t border-navy/8 flex-shrink-0">
            <a id="cv-download-btn" href="#" class="hidden btn-comprar text-sm px-4 py-2 rounded-lg font-semibold">
                Descargar PDF
            </a>
            <button onclick="cerrarCvModalBtn()" class="ml-auto text-sm px-4 py-2 text-navy/40 hover:text-navy transition-colors font-medium">
                Cerrar
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
window.candidaturasPageData = {
    estadoAct: '{{ $estadoAct }}',
    ordenAct:  '{{ $ordenAct }}',
    baseUrl:   '{{ $baseUrl }}',
    estadoUrl: '{{ rtrim(route('empresa.candidaturas.ofertas'), '/') }}'
};
</script>
<script src="{{ asset('js/empresa-candidaturas-detalle.js') }}"></script>
@endpush
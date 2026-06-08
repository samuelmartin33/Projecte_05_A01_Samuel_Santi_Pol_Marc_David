@extends('layouts.app')

@section('titulo', 'Candidatos — ' . $oferta->titulo)

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/empresa-candidaturas-detalle.css') }}">
@endpush

@section('content')

@include('partials.home.nav')

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
        '1' => ['label' => 'Nuevos',          'color' => 'text-blue-400'],
        '2' => ['label' => 'Revisados',       'color' => 'text-amber-400'],
        '3' => ['label' => 'Preseleccionados','color' => 'text-green-400'],
        '4' => ['label' => 'Rechazados',      'color' => 'text-red-400'],
        '5' => ['label' => 'Seleccionados',   'color' => 'text-orange-400'],
    ];
@endphp
<div class="sticky top-16 z-30" style="background:rgba(13,8,32,0.92);border-bottom:1px solid rgba(245,241,234,0.10);backdrop-filter:blur(20px);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <div class="flex flex-wrap items-center gap-2">

            @foreach($estados as $val => $info)
                <button type="button"
                        data-estado="{{ $val }}"
                        onclick="cargarCandidaturas('{{ $val }}', ordenActual)"
                        class="tab-estado {{ $estadoAct == $val ? 'activo' : '' }}">
                    {{ $info['label'] }}
                </button>
            @endforeach

            <div class="ml-auto flex items-center gap-2">
                <label class="text-navy/40 text-xs font-semibold uppercase tracking-wider">Ordenar:</label>
                @php $ordenCandLabels = ['reciente'=>'Más reciente','nombre'=>'Nombre A–Z','estado'=>'Por estado']; @endphp
                <input type="hidden" id="inp-orden-cand" value="{{ $ordenAct }}">
                <div class="ev-csel" id="ev-orden-cand" style="min-width:140px;">
                    <div class="ev-csel-trigger" onclick="cselToggle('ev-orden-cand')"
                         style="padding:8px 12px;font-family:'Archivo Narrow',sans-serif;font-size:0.6875rem;font-weight:600;text-transform:uppercase;letter-spacing:0.12em;border-color:rgba(245,241,234,0.10);">
                        <span id="ev-orden-cand-label">{{ $ordenCandLabels[$ordenAct] ?? 'Más reciente' }}</span>
                        <span class="ev-csel-arrow">▾</span>
                    </div>
                    <ul class="ev-csel-menu">
                        <li class="ev-csel-opt {{ $ordenAct === 'reciente' ? 'selected' : '' }}"
                            onclick="cselPick('ev-orden-cand','inp-orden-cand','reciente','Más reciente',this);cargarCandidaturas(estadoActual,'reciente')">Más reciente</li>
                        <li class="ev-csel-opt {{ $ordenAct === 'nombre' ? 'selected' : '' }}"
                            onclick="cselPick('ev-orden-cand','inp-orden-cand','nombre','Nombre A–Z',this);cargarCandidaturas(estadoActual,'nombre')">Nombre A–Z</li>
                        <li class="ev-csel-opt {{ $ordenAct === 'estado' ? 'selected' : '' }}"
                            onclick="cselPick('ev-orden-cand','inp-orden-cand','estado','Por estado',this);cargarCandidaturas(estadoActual,'estado')">Por estado</li>
                    </ul>
                </div>
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
        'trabajo'       => $c->trabajo?->nombre ?? null,
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
            <div class="w-16 h-16 flex items-center justify-center mx-auto mb-4" style="background:rgba(245,241,234,0.05);">
                <svg class="w-8 h-8" style="color:rgba(245,241,234,0.20);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h3 class="font-bold text-lg" style="color:rgba(245,241,234,0.50);">Sin candidaturas
                @if(request('estado')) en este estado @endif
            </h3>
            <p class="text-sm mt-1" style="color:rgba(245,241,234,0.30);">Cuando alguien se postule aparecerá aquí.</p>
            @if(request('estado'))
                <a href="{{ route('empresa.candidaturas.detalle', $oferta->id) }}"
                   class="mt-4 inline-block text-sm font-semibold hover:underline" style="color:#c084fc;">
                    Ver todas las candidaturas
                </a>
            @endif
        </div>
    @else

        <div style="background:#0d0a18;border:1px solid rgba(245,241,234,0.10);overflow:visible;">

            {{-- Cabecera de tabla --}}
            <div class="grid grid-cols-[2.5rem_1fr_auto] gap-4 px-5 py-3" style="background:rgba(168,85,247,0.06);border-bottom:1px solid rgba(245,241,234,0.10);">
                <div></div>
                <p class="text-xs uppercase tracking-wider font-bold" style="color:rgba(245,241,234,0.40);font-family:'Archivo Narrow',sans-serif;letter-spacing:0.16em;">Candidato</p>
                <p class="text-xs uppercase tracking-wider font-bold" style="color:rgba(245,241,234,0.40);font-family:'Archivo Narrow',sans-serif;letter-spacing:0.16em;">Acciones</p>
            </div>

            @php $estLabels = [1=>'Nuevo', 2=>'Revisado', 3=>'Preseleccionado', 4=>'Rechazado', 5=>'Seleccionado']; @endphp
            @foreach($candidaturas as $cand)
            <div class="cand-row" id="row-{{ $cand->id }}">

                {{-- Avatar --}}
                <div class="cand-avatar">{{ $cand->iniciales() }}</div>

                {{-- Info candidato --}}
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-0.5">
                        <span class="font-bold text-sm" style="color:#f5f1ea;">{{ $cand->nombreCompleto() }}</span>

                        {{-- Rol / tipo de trabajo al que se postula --}}
                        @if($cand->trabajo)
                            <span style="background:rgba(168,85,247,0.18);border:1px solid rgba(168,85,247,0.35);color:#c084fc;font-size:10px;font-family:'Archivo Narrow',sans-serif;font-weight:600;text-transform:uppercase;letter-spacing:0.12em;padding:2px 8px;border-radius:4px;">
                                {{ $cand->trabajo->nombre }}
                            </span>
                        @endif

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

                    <div class="flex flex-wrap gap-x-3 gap-y-0.5 text-xs" style="color:rgba(245,241,234,0.45);">
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
                    <div class="ev-csel" id="ev-est-{{ $cand->id }}" style="width:auto;min-width:140px;">
                        <div class="ev-csel-trigger cand-estado-trigger estado-{{ $cand->estado_candidatura }}"
                             id="trig-est-{{ $cand->id }}"
                             onclick="cselToggle('ev-est-{{ $cand->id }}')">
                            <span id="ev-est-{{ $cand->id }}-label">{{ $estLabels[$cand->estado_candidatura] ?? 'Nuevo' }}</span>
                            <span class="ev-csel-arrow">▾</span>
                        </div>
                        <ul class="ev-csel-menu" style="min-width:140px;left:auto;right:0;">
                            @foreach($estLabels as $val => $lab)
                                <li class="ev-csel-opt {{ $cand->estado_candidatura == $val ? 'selected' : '' }}"
                                    onclick="cselPick('ev-est-{{ $cand->id }}','inp-est-{{ $cand->id }}','{{ $val }}','{{ $lab }}',this); cambiarEstado({{ $cand->id }}, '{{ $val }}', document.getElementById('trig-est-{{ $cand->id }}'))">
                                    {{ $lab }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <input type="hidden" id="inp-est-{{ $cand->id }}" value="{{ $cand->estado_candidatura }}">

                    {{-- Ver CV --}}
                    <button onclick="verCv({{ $cand->id }})"
                            title="Ver CV completo"
                            class="w-8 h-8 flex items-center justify-center transition-colors"
                            style="background:rgba(168,85,247,0.18);border:1px solid rgba(168,85,247,0.4);color:#c084fc;">
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
                           class="w-8 h-8 flex items-center justify-center transition-colors"
                           style="background:rgba(52,211,153,0.15);border:1px solid rgba(52,211,153,0.4);color:#34d399;">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </a>
                    @endif

                    {{-- Enviar correo de selección (aparece para todos los candidatos en estado Seleccionado) --}}
                    @if($cand->estado_candidatura == \App\Models\CandidaturaTrabajo::ESTADO_SELECCIONADO)
                        <button id="btn-correo-{{ $cand->id }}"
                                onclick="enviarCorreoSeleccion({{ $cand->id }}, '{{ route('empresa.candidaturas.enviar-seleccion', $cand->id) }}')"
                                title="Enviar correo de selección al candidato"
                                class="w-8 h-8 flex items-center justify-center transition-colors"
                                style="background:rgba(251,146,60,0.15);border:1px solid rgba(251,146,60,0.4);color:#fb923c;">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </button>
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
        <div class="flex items-center justify-between px-5 py-4 flex-shrink-0" style="border-bottom:1px solid rgba(245,241,234,0.10);">
            <div>
                <h3 class="font-black text-lg" id="cv-modal-nombre" style="color:#f5f1ea;font-family:'Anton',sans-serif;text-transform:uppercase;letter-spacing:-0.005em;">CV Candidato</h3>
                <p class="text-xs" id="cv-modal-sub" style="color:rgba(245,241,234,0.40);"></p>
            </div>
            <button onclick="cerrarCvModalBtn()" style="color:rgba(245,241,234,0.30);" class="transition-colors hover:text-white">
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
        <div class="flex gap-2 px-5 py-3 flex-shrink-0" style="border-top:1px solid rgba(245,241,234,0.10);">
            <a id="cv-download-btn" href="#" class="hidden text-sm font-semibold" style="background:rgba(52,211,153,0.15);border:1px solid rgba(52,211,153,0.4);color:#34d399;padding:8px 14px;font-family:'Archivo Narrow',sans-serif;text-transform:uppercase;letter-spacing:0.16em;font-size:0.6875rem;">
                Descargar PDF
            </a>
            <button onclick="cerrarCvModalBtn()" class="ml-auto text-sm px-4 py-2 transition-colors font-medium" style="color:rgba(245,241,234,0.40);">
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
@extends('layouts.app')

@section('titulo', 'Revisar Currículums — ' . $empresa->nombre_empresa)

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/empresa-candidaturas-ofertas.css') }}">
@endpush

@section('content')

@include('partials.home.nav')

{{-- ══ Hero ══ --}}
<section class="cand-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Breadcrumb --}}
        <a href="{{ route('empresa.home') }}"
           class="inline-flex items-center gap-1.5 text-slate-400 hover:text-white text-sm font-medium transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Panel de empresa
        </a>

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold mb-3"
                     style="background:rgba(139,92,246,0.15);border:1px solid rgba(139,92,246,0.3);color:#c084fc;letter-spacing:.06em;text-transform:uppercase">
                    Revisar Currículums
                </div>
                <h1 class="text-3xl sm:text-4xl font-black text-white leading-tight">
                    Tus ofertas publicadas
                </h1>
                <p class="text-slate-400 mt-1 text-sm">{{ $empresa->nombre_empresa }}</p>
            </div>

            {{-- Stats --}}
            <div class="flex gap-3">
                <div class="stat-box">
                    <p class="text-2xl font-black text-white">{{ $ofertas->total() }}</p>
                    <p class="text-slate-400 text-xs mt-0.5">Ofertas</p>
                </div>
                <div class="stat-box">
                    <p class="text-2xl font-black text-purple-300">{{ $totalCandidaturas }}</p>
                    <p class="text-slate-400 text-xs mt-0.5">Candidaturas</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ Filtros ══ --}}
@php
    $baseOfertas   = route('empresa.candidaturas.ofertas');
    $ordenOfAct    = request('orden', 'reciente');
    $estadoOfAct   = request('estado', null);
    $estadoPrefix  = $estadoOfAct !== null ? 'estado=' . $estadoOfAct . '&' : '';
    $mkUrl = fn($est, $ord) => $baseOfertas . '?' . http_build_query(array_filter(
        ['estado' => $est, 'orden' => $ord],
        fn($v) => $v !== null && $v !== ''
    ));
@endphp
<div class="sticky top-16 z-30" style="background:rgba(13,8,32,0.92);border-bottom:1px solid rgba(245,241,234,0.10);backdrop-filter:blur(20px);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex flex-wrap items-center gap-3">

        {{-- Estado --}}
        <div class="flex items-center gap-1.5">
            <a href="{{ $mkUrl(null, $ordenOfAct) }}"
               class="filtro-btn {{ $estadoOfAct === null ? 'activo' : '' }}">Todas</a>
            <a href="{{ $mkUrl('1', $ordenOfAct) }}"
               class="filtro-btn {{ $estadoOfAct === '1' ? 'activo' : '' }}">Activas</a>
            <a href="{{ $mkUrl('0', $ordenOfAct) }}"
               class="filtro-btn {{ $estadoOfAct === '0' ? 'activo' : '' }}">Cerradas</a>
        </div>

        <div class="ml-auto flex items-center gap-2">
            <label class="text-navy/50 text-xs font-semibold uppercase tracking-wider">Ordenar:</label>
            <select class="filtro-select"
                    onchange="window.location.href='{{ $baseOfertas }}?{{ $estadoPrefix }}orden='+this.value">
                <option value="reciente"   {{ $ordenOfAct === 'reciente'   ? 'selected' : '' }}>Más reciente</option>
                <option value="candidatos" {{ $ordenOfAct === 'candidatos' ? 'selected' : '' }}>Más candidatos</option>
                <option value="titulo"     {{ $ordenOfAct === 'titulo'     ? 'selected' : '' }}>Alfabético</option>
            </select>
            @if($estadoOfAct !== null || request('orden'))
                <a href="{{ $baseOfertas }}"
                   class="text-navy/40 hover:text-navy text-xs font-semibold transition-colors">
                    Limpiar
                </a>
            @endif
        </div>

    </div>
</div>

{{-- ══ Grid de ofertas ══ --}}
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    @if($ofertas->isEmpty())
        <div class="empty-state">
            <div class="w-16 h-16 flex items-center justify-center mx-auto mb-4" style="background:rgba(245,241,234,0.05);">
                <svg class="w-8 h-8" style="color:rgba(245,241,234,0.20);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="font-bold text-lg mb-1" style="color:rgba(245,241,234,0.50);">No hay ofertas publicadas</h3>
            <p class="text-sm" style="color:rgba(245,241,234,0.35);">Cuando publiques ofertas de trabajo, aparecerán aquí.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach($ofertas as $oferta)
            <div class="oferta-card">

                {{-- Header: título + estado --}}
                <div class="flex items-start justify-between gap-2">
                    <h2 class="text-navy font-bold text-base leading-snug flex-1">
                        {{ $oferta->titulo }}
                    </h2>
                    <span class="badge-estado {{ $oferta->estado ? 'badge-activa' : 'badge-cerrada' }} flex-shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full {{ $oferta->estado ? 'bg-green-500' : 'bg-slate-400' }}"></span>
                        {{ $oferta->estado ? 'Activa' : 'Cerrada' }}
                    </span>
                </div>

                {{-- Categoría + ubicación --}}
                <div class="flex flex-wrap gap-2 text-xs text-navy/50 font-medium">
                    @if($oferta->categoria)
                        <span class="bg-purple-50 text-purple-700 px-2 py-0.5 rounded-md">
                            {{ $oferta->categoria->nombre }}
                        </span>
                    @endif
                    @if($oferta->ubicacion)
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            {{ $oferta->ubicacion }}
                        </span>
                    @endif
                </div>

                {{-- Fecha + salario --}}
                <div class="flex items-center gap-4 text-xs text-navy/40 font-medium">
                    <span>Publicada el {{ \Carbon\Carbon::parse($oferta->fecha_creacion)->format('d/m/Y') }}</span>
                    <span class="text-green-600 font-semibold">{{ $oferta->salario_formateado }}</span>
                </div>

                {{-- Separador --}}
                <hr class="border-navy/6">

                {{-- Candidaturas + botón --}}
                <div class="flex items-center justify-between">
                    <span class="badge-cands {{ $oferta->candidaturas_count > 0 ? 'badge-cands-ok' : 'badge-cands-none' }}">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $oferta->candidaturas_count }}
                        {{ $oferta->candidaturas_count === 1 ? 'candidato' : 'candidatos' }}
                    </span>

                    <a href="{{ route('empresa.candidaturas.detalle', $oferta->id) }}"
                       class="inline-flex items-center gap-1.5 text-sm font-bold text-purple-600 hover:text-purple-800 transition-colors">
                        Ver CVs
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

            </div>
            @endforeach
        </div>

        {{-- Paginación --}}
        @if($ofertas->hasPages())
            <div class="mt-10 flex justify-center">
                {{ $ofertas->links() }}
            </div>
        @endif
    @endif
</main>

@endsection
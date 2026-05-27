@extends('layouts.app')

@section('titulo', 'Horas del trabajador — VIBEZ')

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/empresa-trabajador-horas.css') }}">
{{-- CSS extraido a public/css/empresa-trabajador-horas.css --}}
@endpush

@section('content')

@include('partials.home.nav')

<div class="th-wrap">

    {{-- ─── Breadcrumb — adapta el destino según el contexto de acceso ─── --}}
    @if($candidatura)
        <a href="{{ route('empresa.candidaturas.detalle', $candidatura->oferta_id) }}" class="th-back">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver a candidaturas
        </a>
    @else
        <a href="{{ route('empresa.equipo.index') }}" class="th-back">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver al equipo
        </a>
    @endif

    @if($trabajador)

        {{-- ─── Hero con datos del trabajador ─── --}}
        <div class="th-hero">
            <div>
                <h1 class="th-hero-titulo">
                    {{ $trabajador->nombre }} {{ $trabajador->apellido1 }}
                </h1>
                <p class="th-hero-sub">
                    {{ $candidatura?->trabajo?->nombre ?? 'Miembro del equipo' }} · Registro de horas
                </p>
                <p class="th-hero-email">{{ $trabajador->email }}</p>
            </div>
            <div class="th-stats">
                <div class="th-stat">
                    <p class="th-stat-num">{{ number_format($horasMes, 1) }}</p>
                    <p class="th-stat-label">h este mes</p>
                </div>
                <div class="th-stat">
                    <p class="th-stat-num">{{ $registros->count() }}</p>
                    <p class="th-stat-label">registros</p>
                </div>
            </div>
        </div>

        {{-- ─── Historial de horas ─── --}}
        <p style="font-family:'Archivo Narrow',sans-serif;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.16em;color:rgba(245,241,234,0.40);margin-bottom:0.75rem;">
            Historial reciente (últimos 60 registros)
        </p>

        <div class="th-tabla-wrap">
            @if($registros->isEmpty())
                <p class="th-empty">Este trabajador aún no tiene horas registradas.</p>
            @else
                <div class="th-tabla-head">
                    <span class="th-th">Fecha</span>
                    <span class="th-th">Horas</span>
                    <span class="th-th">Descripción</span>
                </div>
                @foreach($registros as $reg)
                    <div class="th-fila">
                        <span class="th-fecha">
                            {{ \Carbon\Carbon::parse($reg->fecha)->locale('es')->isoFormat('ddd D MMM YYYY') }}
                        </span>
                        <span class="th-horas">
                            {{ number_format($reg->horas, 1) }}<span style="font-size:11px;color:rgba(245,241,234,0.35);font-family:'Archivo Narrow',sans-serif;"> h</span>
                        </span>
                        <span class="th-desc">{{ $reg->descripcion ?: '—' }}</span>
                    </div>
                @endforeach
            @endif
        </div>

    @else

        {{-- ─── Trabajador sin cuenta registrada ─── --}}
        <div class="th-sin-cuenta">
            <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                 style="color:rgba(245,241,234,0.20);margin:0 auto 1rem;">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <p style="font-family:'Anton',sans-serif;font-size:1.25rem;color:rgba(245,241,234,0.50);text-transform:uppercase;margin-bottom:0.5rem;">
                Sin cuenta en VIBEZ
            </p>
            <p style="font-family:'Archivo Narrow',sans-serif;font-size:13px;color:rgba(245,241,234,0.30);">
                @if($candidatura && $candidatura->email_candidato)
                    Este candidato ({{ $candidatura->email_candidato }}) no tiene una cuenta registrada
                    y por tanto no puede registrar horas en la plataforma.
                @else
                    El trabajador no tiene una cuenta registrada en la plataforma.
                @endif
            </p>
        </div>

    @endif

</div>

@endsection

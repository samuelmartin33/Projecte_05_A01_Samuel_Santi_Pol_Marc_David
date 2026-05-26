@extends('layouts.app')

@section('titulo', 'Horas del trabajador — VIBEZ')

@push('estilos')
<style>
body { background: #07060c; }

.th-wrap {
    max-width: 760px;
    margin: 0 auto;
    padding: 2.5rem 1rem 5rem;
}

/* ─── Breadcrumb ─────────────────────────────── */
.th-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    color: rgba(245,241,234,0.45);
    text-decoration: none;
    margin-bottom: 1.5rem;
    transition: color 0.15s;
}
.th-back:hover { color: #f5f1ea; }

/* ─── Hero ───────────────────────────────────── */
.th-hero {
    background: radial-gradient(circle at 20% 30%, rgba(52,211,153,0.15), transparent 55%),
                radial-gradient(circle at 80% 70%, rgba(16,185,129,0.10), transparent 60%),
                #0d0820;
    border: 1px solid rgba(245,241,234,0.10);
    padding: 2rem 1.5rem;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    flex-wrap: wrap;
}
.th-hero-titulo {
    font-family: 'Anton', sans-serif;
    font-size: clamp(1.5rem, 4vw, 2.5rem);
    color: #f5f1ea;
    text-transform: uppercase;
    letter-spacing: -0.02em;
    line-height: 1;
    margin: 0 0 0.4rem;
}
.th-hero-sub {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    color: rgba(245,241,234,0.45);
}
.th-hero-email {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 13px;
    color: rgba(245,241,234,0.60);
    margin-top: 4px;
}
.th-stats {
    display: flex;
    gap: 1rem;
    flex-shrink: 0;
}
.th-stat {
    text-align: center;
    background: rgba(52,211,153,0.08);
    border: 1px solid rgba(52,211,153,0.22);
    padding: 1rem 1.25rem;
}
.th-stat-num {
    font-family: 'Anton', sans-serif;
    font-size: 2rem;
    color: #6ee7b7;
    line-height: 1;
}
.th-stat-label {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.16em;
    color: rgba(245,241,234,0.40);
    margin-top: 4px;
}

/* ─── Tabla historial ────────────────────────── */
.th-tabla-wrap {
    background: #0d0a18;
    border: 1px solid rgba(245,241,234,0.10);
    overflow: hidden;
}
.th-tabla-head {
    background: rgba(52,211,153,0.05);
    border-bottom: 1px solid rgba(245,241,234,0.10);
    display: grid;
    grid-template-columns: 150px 80px 1fr;
    gap: 1rem;
    padding: 10px 20px;
}
.th-th {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.16em;
    color: rgba(245,241,234,0.35);
}
.th-fila {
    display: grid;
    grid-template-columns: 150px 80px 1fr;
    gap: 1rem;
    padding: 14px 20px;
    border-bottom: 1px solid rgba(245,241,234,0.06);
    align-items: center;
    transition: background 0.1s;
}
.th-fila:last-child { border-bottom: none; }
.th-fila:hover { background: rgba(245,241,234,0.03); }
.th-fecha {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 13px;
    color: rgba(245,241,234,0.75);
}
.th-horas {
    font-family: 'Anton', sans-serif;
    font-size: 18px;
    color: #6ee7b7;
}
.th-desc {
    font-size: 12px;
    color: rgba(245,241,234,0.45);
    font-family: 'Archivo Narrow', sans-serif;
}
.th-empty {
    padding: 3rem;
    text-align: center;
    color: rgba(245,241,234,0.30);
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 14px;
}
.th-sin-cuenta {
    background: #0d0a18;
    border: 1px solid rgba(245,241,234,0.10);
    padding: 3rem;
    text-align: center;
}
</style>
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

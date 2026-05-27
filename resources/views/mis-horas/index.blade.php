@extends('layouts.app')

@section('titulo', 'Mis horas — VIBEZ')

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/mis-horas.css') }}">
{{-- CSS extraido a public/css/mis-horas.css --}}
@endpush

@section('content')

@include('partials.home.nav')

<div class="horas-wrap">

    {{-- ─── Hero ─── --}}
    <div class="horas-hero">
        <div>
            <h1 class="horas-hero-titulo">Mis horas</h1>
            <p class="horas-hero-sub">
                @if(Auth::user()->isPortero()) Portero · @else Organizador · @endif
                Registro diario
            </p>
        </div>
        <div class="horas-stat">
            <p class="horas-stat-num">{{ number_format($horasMes, 1) }}</p>
            <p class="horas-stat-label">h este mes</p>
        </div>
    </div>

    {{-- ─── Formulario ─── --}}
    <div class="horas-form-card">
        <p class="horas-form-title">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Registrar horas
        </p>

        <form action="{{ route('horas.store') }}" method="POST" id="form-horas">
            @csrf
            <div class="horas-grid">

                <div class="horas-campo">
                    <label class="horas-label" for="fecha">Fecha <span style="color:#f87171">*</span></label>
                    <input type="date" id="fecha" name="fecha" class="horas-input"
                           value="{{ old('fecha', now()->toDateString()) }}"
                           max="{{ now()->toDateString() }}"
                           required>
                </div>

                <div class="horas-campo">
                    <label class="horas-label" for="horas">Horas trabajadas <span style="color:#f87171">*</span></label>
                    <input type="number" id="horas" name="horas" class="horas-input"
                           value="{{ old('horas') }}"
                           min="0.5" max="24" step="0.5"
                           placeholder="Ej: 8 o 4.5"
                           required>
                    <span class="horas-hint">De 0.5 a 24 h · Usa .5 para medias horas</span>
                </div>

                <div class="horas-campo full">
                    <label class="horas-label" for="descripcion">Descripción (opcional)</label>
                    <input type="text" id="descripcion" name="descripcion" class="horas-input"
                           value="{{ old('descripcion') }}"
                           placeholder="¿Qué hiciste hoy? Ej: Control de acceso evento Sala Razzmatazz"
                           maxlength="500">
                </div>

            </div>

            <button type="submit" class="horas-btn-enviar">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Guardar horas
            </button>
        </form>
    </div>

    {{-- ─── Historial ─── --}}
    <p style="font-family:'Archivo Narrow',sans-serif;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.16em;color:rgba(245,241,234,0.40);margin-bottom:0.75rem;">
        Historial reciente
    </p>

    <div class="horas-tabla-wrap">
        @if($registros->isEmpty())
            <p class="horas-empty">Aún no tienes horas registradas. Usa el formulario de arriba.</p>
        @else
            <div class="horas-tabla-head">
                <span class="horas-tabla-th">Fecha</span>
                <span class="horas-tabla-th">Horas</span>
                <span class="horas-tabla-th">Descripción</span>
            </div>
            @foreach($registros as $reg)
                <div class="horas-fila">
                    <span class="horas-fecha">
                        {{ \Carbon\Carbon::parse($reg->fecha)->locale('es')->isoFormat('ddd D MMM YYYY') }}
                    </span>
                    <span class="horas-num">{{ number_format($reg->horas, 1) }}<span style="font-size:11px;color:rgba(245,241,234,0.35);font-family:'Archivo Narrow',sans-serif;"> h</span></span>
                    <span class="horas-desc">{{ $reg->descripcion ?: '—' }}</span>
                </div>
            @endforeach
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script>
window.HORAS_FLASH = {
    success: @json(session('success')),
    error:   @json(session('error')),
    errores: @json($errors->any() ? $errors->all() : [])
};
</script>
<script src="{{ asset('js/mis-horas.js') }}"></script>
{{-- JS en public/js/mis-horas.js --}}
@endpush

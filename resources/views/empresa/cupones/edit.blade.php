@extends('layouts.app')

@section('titulo', 'Editar cupón — VIBEZ')

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/empresa-home.css') }}">
<style>
    body { background: #07060c; }

    .form-crear-cupon {
        background: #0d0a18;
        border: 1px solid rgba(245,241,234,0.10);
        padding: 2.25rem;
    }
    .form-grupo { margin-bottom: 1.25rem; }
    .form-grupo-doble {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
        margin-bottom: 1.25rem;
    }
    @media (max-width: 640px) {
        .form-grupo-doble { grid-template-columns: 1fr; }
    }
    .form-label {
        display: flex;
        justify-content: space-between;
        font-family: 'Archivo Narrow', sans-serif;
        font-size: 0.625rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.16em;
        color: rgba(245,241,234,0.55);
        margin-bottom: 8px;
    }
    .form-label .form-required { color: #f87171; margin-left: 4px; }
    .form-label .form-hint-inline {
        color: rgba(245,241,234,0.25);
        font-size: 0.5rem;
        font-weight: 600;
    }
    .form-input,
    .form-select {
        width: 100%;
        padding: 6px 0;
        border: none;
        border-bottom: 1.5px solid rgba(245,241,234,0.18);
        border-radius: 0;
        font-size: 1.0625rem;
        font-family: 'Archivo', sans-serif;
        color: #f5f1ea;
        background: transparent;
        transition: border-color 0.2s;
        outline: none;
        box-sizing: border-box;
    }
    .form-input:focus,
    .form-select:focus { border-bottom-color: #a855f7; }
    .form-input::placeholder { color: rgba(245,241,234,0.25); }
    .form-input.font-mono { font-family: 'Archivo Narrow', monospace; letter-spacing: 0.12em; }
    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23c084fc' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 4px center;
        padding-right: 24px;
        cursor: pointer;
    }
    .form-select option { background: #0d0a18; color: #f5f1ea; }
    .form-divider {
        border: none;
        border-top: 1px dashed rgba(245,241,234,0.10);
        margin: 1.75rem 0;
    }
    .form-section-title {
        font-family: 'Archivo Narrow', sans-serif;
        font-size: 0.6875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.16em;
        color: #c084fc;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-section-title svg { width: 14px; height: 14px; flex-shrink: 0; }

    /* Lista de eventos (checkboxes) */
    .eventos-lista {
        border: 1px solid rgba(245,241,234,0.10);
        background: rgba(0,0,0,0.2);
        max-height: 240px;
        overflow-y: auto;
    }
    .evento-check-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-bottom: 1px solid rgba(245,241,234,0.06);
        cursor: pointer;
        transition: background 0.15s;
    }
    .evento-check-item:last-child { border-bottom: none; }
    .evento-check-item:hover { background: rgba(168,85,247,0.08); }
    .evento-check-item input[type="checkbox"] {
        width: 14px; height: 14px;
        accent-color: #a855f7;
        flex-shrink: 0;
        cursor: pointer;
    }
    .evento-check-nombre {
        font-family: 'Archivo', sans-serif;
        font-size: 0.875rem;
        color: #f5f1ea;
    }
    .evento-check-fecha {
        font-family: 'Archivo Narrow', sans-serif;
        font-size: 0.5625rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: rgba(245,241,234,0.30);
        margin-left: auto;
        flex-shrink: 0;
    }
    .eventos-lista-empty {
        padding: 16px;
        font-family: 'Archivo Narrow', sans-serif;
        font-size: 0.625rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: rgba(245,241,234,0.25);
    }

    .form-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(245,241,234,0.10);
    }
    .btn-guardar {
        font-family: 'Anton', sans-serif;
        font-size: 0.8125rem;
        text-transform: uppercase;
        letter-spacing: -0.005em;
        display: inline-flex; align-items: center; gap: 8px;
        padding: 12px 22px;
        background: #a855f7;
        color: #f5f1ea;
        border: none;
        cursor: pointer;
        transition: background 0.15s;
    }
    .btn-guardar:hover { background: #c084fc; color: #07060c; }
    .btn-cancelar {
        font-family: 'Archivo Narrow', sans-serif;
        font-size: 0.6875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.16em;
        padding: 12px 18px;
        background: transparent;
        border: 1px solid rgba(245,241,234,0.10);
        color: rgba(245,241,234,0.55);
        cursor: pointer;
        text-decoration: none;
        transition: border-color 0.15s, color 0.15s;
    }
    .btn-cancelar:hover { border-color: rgba(245,241,234,0.3); color: #f5f1ea; }

    .alert-errores {
        background: rgba(239,68,68,0.10);
        border: 1px solid rgba(239,68,68,0.4);
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        color: #f87171;
        font-size: 0.85rem;
    }
    .alert-errores strong {
        display: block; margin-bottom: 0.4rem;
        font-family: 'Archivo Narrow', sans-serif;
        text-transform: uppercase; letter-spacing: 0.1em;
    }
    .alert-errores ul { margin: 0; padding-left: 1.25rem; }
    .alert-errores li { margin-bottom: 0.2rem; }

    /* Cabecera del formulario con el código del cupón */
    .cupon-edit-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid rgba(245,241,234,0.10);
    }
    .cupon-edit-badge {
        font-family: 'Anton', sans-serif;
        font-size: 1.5rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #c084fc;
        background: rgba(168,85,247,0.12);
        border: 1px solid rgba(168,85,247,0.3);
        padding: 6px 16px;
    }
    .cupon-edit-meta {
        font-family: 'Archivo Narrow', sans-serif;
        font-size: 0.625rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.16em;
        color: rgba(245,241,234,0.40);
    }
</style>
@endpush

@section('content')

@include('partials.home.nav')

{{-- Hero --}}
<section class="hero-home">
    <div class="hero-particula hero-particula-1"></div>
    <div class="hero-particula hero-particula-2"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-center relative z-10">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 mb-4"
             style="background:rgba(168,85,247,0.15);border:1px solid rgba(168,85,247,0.3);color:#c084fc;font-family:'Archivo Narrow',sans-serif;font-size:0.625rem;font-weight:600;text-transform:uppercase;letter-spacing:0.16em;">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Editar cupón
        </div>
        <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight leading-tight"
            style="font-family:'Anton',sans-serif;text-transform:uppercase;letter-spacing:-0.005em;">
            Editar <span class="text-gradient-claro">cupón</span>
        </h1>
        <p style="font-family:'Archivo Narrow',sans-serif;font-size:0.6875rem;font-weight:600;text-transform:uppercase;letter-spacing:0.16em;color:rgba(245,241,234,0.40);margin-top:0.75rem;">
            Modificando: {{ $cupon->codigo }}
        </p>
    </div>
</section>

<section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    @if($errors->any())
        <div class="alert-errores">
            <strong>⚠ Revisa los siguientes campos:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('empresa.cupones.update', $cupon->id) }}" class="form-crear-cupon">
        @csrf
        @method('PUT')

        {{-- Cabecera informativa --}}
        <div class="cupon-edit-header">
            <span class="cupon-edit-badge">{{ $cupon->codigo }}</span>
            <span class="cupon-edit-meta">
                Creado el {{ optional($cupon->fecha_creacion)->format('d/m/Y') }}
                · {{ $cupon->usos_actuales }} uso(s) registrado(s)
            </span>
        </div>

        {{-- ── DATOS DEL CUPÓN ── --}}
        <div class="form-section-title">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            Datos del cupón
        </div>

        <div class="form-grupo-doble">
            <div>
                <label class="form-label">
                    Código <span class="form-required">*</span>
                </label>
                <input type="text" name="codigo" value="{{ old('codigo', $cupon->codigo) }}"
                       maxlength="50" required class="form-input font-mono" style="text-transform:uppercase">
            </div>
            <div>
                <label class="form-label">
                    Descuento (%) <span class="form-required">*</span>
                </label>
                <input type="number" name="valor_descuento"
                       value="{{ old('valor_descuento', $cupon->valor_descuento) }}"
                       min="0" max="100" step="0.01" required class="form-input">
            </div>
        </div>

        <div class="form-grupo">
            <label class="form-label">Descripción</label>
            <input type="text" name="descripcion"
                   value="{{ old('descripcion', $cupon->descripcion) }}"
                   maxlength="255" class="form-input">
        </div>

        <div class="form-grupo">
            <label class="form-label">Estado <span class="form-required">*</span></label>
            <select name="estado" required class="form-select">
                <option value="1" {{ old('estado', $cupon->estado) == '1' ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ old('estado', $cupon->estado) == '0' ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>

        <hr class="form-divider">

        {{-- ── FECHAS Y LÍMITES ── --}}
        <div class="form-section-title">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Vigencia y límites
        </div>

        <div class="form-grupo-doble">
            <div>
                <label class="form-label">Fecha inicio <span class="form-required">*</span></label>
                <input type="datetime-local" name="fecha_inicio" required class="form-input"
                       value="{{ old('fecha_inicio', optional($cupon->fecha_inicio)->format('Y-m-d\TH:i')) }}">
            </div>
            <div>
                <label class="form-label">Fecha fin <span class="form-required">*</span></label>
                <input type="datetime-local" name="fecha_fin" required class="form-input"
                       value="{{ old('fecha_fin', optional($cupon->fecha_fin)->format('Y-m-d\TH:i')) }}">
            </div>
        </div>

        <div class="form-grupo-doble">
            <div>
                <label class="form-label">
                    Límite total de usos
                    <span class="form-hint-inline">vacío = ilimitado</span>
                </label>
                <input type="number" name="limite_usos_total"
                       value="{{ old('limite_usos_total', $cupon->limite_usos_total) }}"
                       min="1" class="form-input">
            </div>
            <div>
                <label class="form-label">Límite por usuario</label>
                <input type="number" name="limite_usos_por_usuario"
                       value="{{ old('limite_usos_por_usuario', $cupon->limite_usos_por_usuario) }}"
                       min="1" class="form-input">
            </div>
        </div>

        <hr class="form-divider">

        {{-- ── EVENTOS ── --}}
        <div class="form-section-title">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
            </svg>
            Eventos donde aplica
            <span style="font-family:'Archivo Narrow',sans-serif;font-size:0.5rem;font-weight:600;color:rgba(245,241,234,0.25);text-transform:uppercase;letter-spacing:0.16em;margin-left:4px;">
                vacío = todos tus eventos
            </span>
        </div>

        <div class="form-grupo">
            <div class="eventos-lista">
                @forelse($eventos as $ev)
                    <label class="evento-check-item">
                        <input type="checkbox" name="eventos[]" value="{{ $ev->id }}"
                               {{ in_array($ev->id, old('eventos', $eventosSeleccionados)) ? 'checked' : '' }}>
                        <span class="evento-check-nombre">{{ $ev->titulo }}</span>
                        <span class="evento-check-fecha">
                            {{ optional($ev->fecha_inicio)->format('d/m/Y') }}
                        </span>
                    </label>
                @empty
                    <p class="eventos-lista-empty">No tienes eventos activos.</p>
                @endforelse
            </div>
        </div>

        {{-- ── ACCIONES ── --}}
        <div class="form-actions">
            <button type="submit" class="btn-guardar">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" width="16" height="16">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Guardar cambios
            </button>
            <a href="{{ route('empresa.cupones.index') }}" class="btn-cancelar">Cancelar</a>
        </div>

    </form>
</section>

@endsection

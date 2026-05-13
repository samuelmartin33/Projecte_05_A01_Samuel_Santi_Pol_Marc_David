@extends('layouts.app')

@section('titulo', 'Crear Oferta — VIBEZ')

@push('estilos')
<style>
    body { background: #07060c; }

    .form-crear-evento {
        background: #0d0a18;
        border: 1px solid rgba(245,241,234,0.10);
        border-radius: 0;
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
    .form-input,
    .form-select,
    .form-textarea {
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
    .form-select:focus,
    .form-textarea:focus { border-bottom-color: #a855f7; }
    .form-input::placeholder,
    .form-textarea::placeholder { color: rgba(245,241,234,0.25); }
    .form-textarea { resize: vertical; min-height: 80px; }
    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23c084fc' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 4px center;
        padding-right: 24px;
        cursor: pointer;
    }
    .form-select option { background: #0d0a18; color: #f5f1ea; }
    .form-hint {
        font-family: 'Archivo Narrow', sans-serif;
        font-size: 0.5625rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.16em;
        color: rgba(245,241,234,0.18);
        margin-top: 6px;
    }
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
    .alert-errores strong { display: block; margin-bottom: 0.4rem; font-family: 'Archivo Narrow', sans-serif; text-transform: uppercase; letter-spacing: 0.1em; }
    .alert-errores ul { margin: 0; padding-left: 1.25rem; }
    .alert-errores li { margin-bottom: 0.2rem; }
    .salario-prefix { display: flex; align-items: flex-end; gap: 0; }
    .salario-prefix > span {
        font-family: 'Archivo Narrow', sans-serif;
        font-size: 0.625rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.16em;
        color: rgba(245,241,234,0.55);
        padding: 0 8px 8px 0;
        flex-shrink: 0;
    }
    .salario-prefix .form-input { flex: 1; }
</style>
@endpush

@section('content')

@include('partials.home.nav')

{{-- Hero --}}
<section class="hero-home">
    <div class="hero-particula hero-particula-1"></div>
    <div class="hero-particula hero-particula-2"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-center relative z-10">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold mb-4"
             style="background:rgba(168,85,247,0.15);border:1px solid rgba(168,85,247,0.3);color:#c084fc;letter-spacing:0.06em;text-transform:uppercase;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nueva oferta
        </div>
        <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight leading-tight">
            Publicar <span class="text-gradient-claro">oferta de trabajo</span>
        </h1>
        <p class="mt-3 text-white/50 text-base max-w-lg mx-auto">
            Rellena los datos del puesto y publícalo en la bolsa de trabajo de VIBEZ.
        </p>
    </div>
</section>

{{-- Formulario --}}
<section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    @if ($errors->any())
        <div class="alert-errores">
            <strong>⚠ Revisa los siguientes campos:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('empresa.ofertas.store') }}" method="POST" class="form-crear-evento">
        @csrf

        {{-- ── INFORMACIÓN BÁSICA ── --}}
        <div class="form-section-title">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Información básica
        </div>

        <div class="form-grupo">
            <label class="form-label">Título del puesto <span class="form-required">*</span></label>
            <input type="text" name="titulo" class="form-input" maxlength="300"
                   value="{{ old('titulo') }}"
                   placeholder="Ej: Camarero/a para eventos de verano">
        </div>

        <div class="form-grupo">
            <label class="form-label">Descripción del puesto</label>
            <textarea name="descripcion" class="form-textarea" rows="4"
                      placeholder="Describe las tareas, el ambiente de trabajo, horarios...">{{ old('descripcion') }}</textarea>
        </div>

        <div class="form-grupo">
            <label class="form-label">Requisitos</label>
            <textarea name="requisitos" class="form-textarea" rows="3"
                      placeholder="Experiencia mínima, formación requerida, habilidades...">{{ old('requisitos') }}</textarea>
        </div>

        <div class="form-grupo-doble">
            <div>
                <label class="form-label">Categoría <span class="form-required">*</span></label>
                <select name="categoria_trabajo_id" class="form-select">
                    <option value="">Selecciona categoría</option>
                    @foreach ($categorias as $cat)
                        <option value="{{ $cat->id }}" @selected(old('categoria_trabajo_id') == $cat->id)>
                            {{ $cat->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Vacantes</label>
                <input type="number" min="1" name="vacantes" class="form-input"
                       value="{{ old('vacantes') }}" placeholder="Ej: 3">
                <p class="form-hint">Opcional. Déjalo vacío si no hay límite.</p>
            </div>
        </div>

        <hr class="form-divider">

        {{-- ── UBICACIÓN Y FECHAS ── --}}
        <div class="form-section-title">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Ubicación y fechas
        </div>

        <div class="form-grupo">
            <label class="form-label">Ciudad / Ubicación</label>
            <input type="text" name="ubicacion" class="form-input" maxlength="300"
                   value="{{ old('ubicacion') }}" placeholder="Ej: Barcelona">
        </div>

        <div class="form-grupo-doble">
            <div>
                <label class="form-label">Fecha de inicio</label>
                <input type="date" name="fecha_inicio_trabajo" class="form-input"
                       value="{{ old('fecha_inicio_trabajo') }}">
                <p class="form-hint">Opcional. Cuándo empieza el trabajo.</p>
            </div>
            <div>
                <label class="form-label">Fecha de fin</label>
                <input type="date" name="fecha_fin_trabajo" class="form-input"
                       value="{{ old('fecha_fin_trabajo') }}">
                <p class="form-hint">Opcional. Déjalo vacío si es indefinido.</p>
            </div>
        </div>

        <hr class="form-divider">

        {{-- ── SALARIO ── --}}
        <div class="form-section-title">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Salario mensual
        </div>

        <div class="form-grupo-doble">
            <div>
                <label class="form-label">Salario mínimo (€/mes)</label>
                <div class="salario-prefix">
                    <span>€</span>
                    <input type="number" min="0" step="50" name="salario_min" class="form-input"
                           value="{{ old('salario_min') }}" placeholder="1.200">
                </div>
                <p class="form-hint">Opcional. Déjalo vacío si es a negociar.</p>
            </div>
            <div>
                <label class="form-label">Salario máximo (€/mes)</label>
                <div class="salario-prefix">
                    <span>€</span>
                    <input type="number" min="0" step="50" name="salario_max" class="form-input"
                           value="{{ old('salario_max') }}" placeholder="1.800">
                </div>
            </div>
        </div>

        {{-- ── ACCIONES ── --}}
        <div class="form-actions">
            <button type="submit" class="btn-guardar">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" width="18" height="18">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Publicar oferta
            </button>
            <a href="{{ route('empresa.home') }}" class="btn-cancelar">Cancelar</a>
        </div>
    </form>
</section>

@endsection

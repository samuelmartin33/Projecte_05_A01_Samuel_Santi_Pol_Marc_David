@extends('layouts.app')

@section('titulo', 'Crear cupón — VIBEZ')

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/empresa-home.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="{{ asset('css/empresa-cupones-form.css') }}">
{{-- CSS extraído a public/css/empresa-cupones-form.css --}}
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
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo cupón
        </div>
        <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight leading-tight"
            style="font-family:'Anton',sans-serif;text-transform:uppercase;letter-spacing:-0.005em;">
            Crear <span class="text-gradient-claro">cupón</span>
        </h1>
        <p style="font-family:'Archivo Narrow',sans-serif;font-size:0.6875rem;font-weight:600;text-transform:uppercase;letter-spacing:0.16em;color:rgba(245,241,234,0.40);margin-top:0.75rem;">
            Define el código, el descuento y los eventos donde aplica
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

    <form method="POST" action="{{ route('empresa.cupones.store') }}" class="form-crear-cupon">
        @csrf

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
                <input type="text" name="codigo" value="{{ old('codigo') }}"
                       placeholder="Ej: VIBEZ10" maxlength="50" required
                       class="form-input font-mono" style="text-transform:uppercase">
            </div>
            <div>
                <label class="form-label">
                    Descuento (%) <span class="form-required">*</span>
                </label>
                <input type="number" name="valor_descuento"
                       value="{{ old('valor_descuento', 10) }}"
                       min="0" max="100" step="0.01" required
                       class="form-input">
            </div>
        </div>

        <div class="form-grupo">
            <label class="form-label">Descripción</label>
            <input type="text" name="descripcion" value="{{ old('descripcion') }}"
                   placeholder="Ej: 10% de descuento en todas las entradas" maxlength="255"
                   class="form-input">
        </div>

        <div class="form-grupo">
            <label class="form-label">Estado <span class="form-required">*</span></label>
            @php $estCupCr = old('estado', '1'); @endphp
            <input type="hidden" id="inp-est-cup-cr" name="estado" value="{{ $estCupCr }}">
            <div class="ev-csel" id="ev-est-cup-cr">
                <div class="ev-csel-trigger" onclick="cselToggle('ev-est-cup-cr')">
                    <span id="ev-est-cup-cr-label">{{ $estCupCr == '1' ? 'Activo' : 'Inactivo' }}</span>
                    <span class="ev-csel-arrow">▾</span>
                </div>
                <ul class="ev-csel-menu">
                    <li class="ev-csel-opt {{ $estCupCr == '1' ? 'selected' : '' }}"
                        onclick="cselPick('ev-est-cup-cr', 'inp-est-cup-cr', '1', 'Activo', this)">Activo</li>
                    <li class="ev-csel-opt {{ $estCupCr != '1' ? 'selected' : '' }}"
                        onclick="cselPick('ev-est-cup-cr', 'inp-est-cup-cr', '0', 'Inactivo', this)">Inactivo</li>
                </ul>
            </div>
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
                <input type="text" name="fecha_inicio" id="cup-fecha-inicio"
                       value="{{ old('fecha_inicio') }}" required class="form-input"
                       placeholder="dd/mm/aaaa hh:mm" autocomplete="off" readonly>
            </div>
            <div>
                <label class="form-label">Fecha fin <span class="form-required">*</span></label>
                <input type="text" name="fecha_fin" id="cup-fecha-fin"
                       value="{{ old('fecha_fin') }}" required class="form-input"
                       placeholder="dd/mm/aaaa hh:mm" autocomplete="off" readonly>
            </div>
        </div>

        <div class="form-grupo-doble">
            <div>
                <label class="form-label">
                    Límite total de usos
                    <span class="form-hint-inline">vacío = ilimitado</span>
                </label>
                <input type="number" name="limite_usos_total"
                       value="{{ old('limite_usos_total') }}"
                       min="1" class="form-input">
            </div>
            <div>
                <label class="form-label">Límite por usuario</label>
                <input type="number" name="limite_usos_por_usuario"
                       value="{{ old('limite_usos_por_usuario', 1) }}"
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
                               {{ in_array($ev->id, old('eventos', [])) ? 'checked' : '' }}>
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
                Crear cupón
            </button>
            <a href="{{ route('empresa.cupones.index') }}" class="btn-cancelar">Cancelar</a>
        </div>

    </form>
</section>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script>window.CUPON_IDS = { inicio: 'cup-fecha-inicio', fin: 'cup-fecha-fin' };</script>
<script src="{{ asset('js/empresa-cupones-form.js') }}"></script>
{{-- JS en public/js/empresa-cupones-form.js --}}
@endpush

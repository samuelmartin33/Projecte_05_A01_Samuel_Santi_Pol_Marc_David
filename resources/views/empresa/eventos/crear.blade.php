@extends('layouts.app')

@section('titulo', 'Crear Evento — VIBEZ')

@push('estilos')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="{{ asset('css/empresa-eventos-form.css') }}">
{{-- CSS extraído a public/css/empresa-eventos-form.css --}}
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
            Nuevo evento
        </div>
        <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight leading-tight">
            Crear <span class="text-gradient-claro">evento</span>
        </h1>
        <p class="mt-3 text-white/50 text-base max-w-lg mx-auto">
            Rellena los datos de tu evento y publícalo en VIBEZ.
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

    <form action="{{ route('empresa.eventos.store') }}" method="POST" class="form-crear-evento" enctype="multipart/form-data">
        @csrf

        {{-- ── INFORMACIÓN BÁSICA ── --}}
        <div class="form-section-title">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Información básica
        </div>

        <div class="form-grupo">
            <label class="form-label">Título del evento <span class="form-required">*</span></label>
            <input type="text" name="titulo" class="form-input" maxlength="300"
                   value="{{ old('titulo') }}"
                   placeholder="Ej: Festival de Verano 2026">
        </div>

        <div class="form-grupo">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-textarea" rows="4"
                      placeholder="Describe tu evento: qué van a encontrar los asistentes, artistas, actividades...">{{ old('descripcion') }}</textarea>
        </div>

        <div class="form-grupo">
            <label class="form-label">Categorías <span class="form-required">*</span> <span style="color:rgba(245,241,234,0.3);font-size:0.5rem;">Puedes seleccionar varias</span></label>
            <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:4px;">
                @foreach ($categorias as $cat)
                    @php $checked = is_array(old('categorias')) && in_array($cat->id, old('categorias')); @endphp
                    <label style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border:1px solid {{ $checked ? 'rgba(168,85,247,0.7)' : 'rgba(245,241,234,0.14)' }};cursor:pointer;transition:border-color 0.15s;user-select:none;"
                           onmouseenter="this.style.borderColor='rgba(168,85,247,0.5)'"
                           onmouseleave="this.style.borderColor=this.querySelector('input').checked?'rgba(168,85,247,0.7)':'rgba(245,241,234,0.14)'"
                           onclick="actualizarBordeCat(this)">
                        <input type="checkbox" name="categorias[]" value="{{ $cat->id }}"
                               style="accent-color:#a855f7;width:14px;height:14px;"
                               @checked($checked)>
                        <span style="font-family:'Archivo Narrow',sans-serif;font-size:13px;color:#f5f1ea;">{{ $cat->nombre }}</span>
                    </label>
                @endforeach
            </div>
            @error('categorias') <p style="color:#f87171;font-size:11px;margin-top:6px;">{{ $message }}</p> @enderror
        </div>

        <div class="form-grupo">
            <label class="form-label">Tipo de evento <span class="form-required">*</span></label>
            @php
                $tipoEvActual = old('tipo_evento', 1);
                $tipoEvLabel  = $tipoEvActual == 2 ? 'Online' : 'Presencial';
            @endphp
            <input type="hidden" id="tipo_evento" name="tipo_evento" value="{{ $tipoEvActual }}">
            <div class="ev-csel" id="ev-tipo-evento">
                <div class="ev-csel-trigger" onclick="cselToggle('ev-tipo-evento')">
                    <span id="ev-tipo-evento-label">{{ $tipoEvLabel }}</span>
                    <span class="ev-csel-arrow">▾</span>
                </div>
                <ul class="ev-csel-menu">
                    <li class="ev-csel-opt {{ $tipoEvActual == 1 ? 'selected' : '' }}"
                        onclick="cselPick('ev-tipo-evento','tipo_evento','1','Presencial',this)">Presencial</li>
                    <li class="ev-csel-opt {{ $tipoEvActual == 2 ? 'selected' : '' }}"
                        onclick="cselPick('ev-tipo-evento','tipo_evento','2','Online',this)">Online</li>
                </ul>
            </div>
        </div>

        <hr class="form-divider">

        {{-- ── FECHA Y HORA ── --}}
        <div class="form-section-title">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Fecha y hora
        </div>

        <div class="form-grupo-doble">
            <div>
                <label class="form-label">Fecha de inicio <span class="form-required">*</span></label>
                <input type="text" name="fecha_inicio" id="fecha_inicio" class="form-input flatpickr-input"
                       value="{{ old('fecha_inicio') }}" placeholder="dd/mm/aaaa hh:mm" autocomplete="off" readonly>
            </div>
            <div>
                <label class="form-label">Fecha de fin</label>
                <input type="text" name="fecha_fin" id="fecha_fin" class="form-input flatpickr-input"
                       value="{{ old('fecha_fin') }}" placeholder="dd/mm/aaaa hh:mm" autocomplete="off" readonly>
                <p class="form-hint">Opcional. Déjalo vacío si es un evento de un solo momento.</p>
            </div>
        </div>

        <hr class="form-divider">

        {{-- ── UBICACIÓN ── --}}
        <div class="form-section-title">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Ubicación
        </div>

        <div class="form-grupo-doble">
            <div>
                <label class="form-label">Nombre del lugar <span class="form-required">*</span></label>
                <input type="text" name="ubicacion_nombre" class="form-input" maxlength="300"
                       value="{{ old('ubicacion_nombre') }}" required
                       placeholder="Ej: Palau Sant Jordi">
            </div>
            <div>
                <label class="form-label">Dirección</label>
                <input type="text" name="ubicacion_direccion" class="form-input" maxlength="500"
                       value="{{ old('ubicacion_direccion') }}"
                       placeholder="Ej: Passeig Olímpic, 5-7, Barcelona">
            </div>
        </div>

        <div class="form-grupo-doble">
            <div>
                <label class="form-label">Latitud</label>
                <input type="number" step="0.0000001" name="latitud" class="form-input"
                       value="{{ old('latitud') }}" placeholder="41.3642">
            </div>
            <div>
                <label class="form-label">Longitud</label>
                <input type="number" step="0.0000001" name="longitud" class="form-input"
                       value="{{ old('longitud') }}" placeholder="2.1527">
            </div>
        </div>

        <hr class="form-divider">

        {{-- ── PRECIO Y AFORO ── --}}
        <div class="form-section-title">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
            </svg>
            Precio y aforo
        </div>

        <div class="form-grupo">
            <label class="form-checkbox-wrap" for="es_gratuito">
                <input type="checkbox" id="es_gratuito" name="es_gratuito" value="1"
                       @checked(old('es_gratuito'))
                       onchange="togglePrecio()">
                <span class="form-checkbox-label">Este evento es gratuito</span>
            </label>
        </div>

        <div class="form-grupo-doble">
            <div id="precio-wrap">
                <label class="form-label">Precio base (€) <span class="form-required">*</span></label>
                <input type="number" min="0" step="0.01" name="precio_base" class="form-input"
                       id="precio_base_input"
                       value="{{ old('precio_base', 0) }}" placeholder="0.00">
            </div>
            <div>
                <label class="form-label">Aforo máximo</label>
                <input type="number" min="1" name="aforo_maximo" class="form-input"
                       value="{{ old('aforo_maximo') }}" placeholder="Ej: 500">
                <p class="form-hint">Opcional. Déjalo vacío si no hay límite.</p>
            </div>
        </div>

        <div class="form-grupo-doble">
            <div>
                <label class="form-label">Edad mínima</label>
                <input type="number" min="0" max="120" name="edad_minima" class="form-input"
                       value="{{ old('edad_minima') }}" placeholder="Ej: 16">
                <p class="form-hint">Opcional. Déjalo vacío si no hay restricción.</p>
            </div>
            <div>
                <label class="form-label">URL externa</label>
                <input type="url" name="url_externa" class="form-input" maxlength="500"
                       value="{{ old('url_externa') }}" placeholder="https://...">
                <p class="form-hint">Enlace a la web del evento, si la tiene.</p>
            </div>
        </div>

        <hr class="form-divider">

        {{-- ── IMAGEN DE PORTADA ── --}}
        <div class="form-section-title">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Imagen de portada
        </div>

        <div class="form-grupo">
            <label class="form-label">Imagen de portada</label>
            <div class="upload-zona" id="upload-zona">
                <input type="file" name="imagen_portada" id="imagen_portada_input"
                       accept="image/jpeg,image/png,image/webp,image/gif">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="upload-zona-texto">
                    <strong>Haz clic o arrastra</strong> una imagen aquí
                </p>
                <p class="upload-zona-texto" style="font-size:0.75rem;margin-top:0.2rem;">
                    JPG, PNG, WebP o GIF · Máx. 5 MB
                </p>
            </div>
            <div class="upload-preview" id="upload-preview">
                <img id="imagen-preview-img" alt="Vista previa">
                <p class="upload-nombre" id="upload-nombre"></p>
            </div>
        </div>

        {{-- ── ACCIONES ── --}}
        <div class="form-actions">
            <button type="submit" class="btn-guardar">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" width="18" height="18">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Publicar evento
            </button>
            <a href="{{ route('empresa.home') }}" class="btn-cancelar">Cancelar</a>
        </div>
    </form>
</section>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script src="{{ asset('js/empresa-eventos-form.js') }}"></script>
{{-- JS en public/js/empresa-eventos-form.js --}}
@endpush

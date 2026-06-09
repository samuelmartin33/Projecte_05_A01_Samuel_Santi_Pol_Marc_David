@extends('layouts.app')

@section('titulo', 'Crear Evento Fiesta — VIBEZ')

@push('estilos')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    body { background: #07060c; }

    .form-crear-evento {
        background: #0d0a18;
        border: 1px solid rgba(245,241,234,0.10);
        border-radius: 0;
        padding: 2.25rem;
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

    .upload-zona {
        border: 1.5px dashed #a855f7;
        background: rgba(168,85,247,0.06);
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s;
        position: relative;
    }
    .upload-zona:hover, .upload-zona.dragover { border-color: #c084fc; background: rgba(168,85,247,0.12); }
    .upload-zona svg { width: 36px; height: 36px; color: #c084fc; margin: 0 auto 10px; display: block; }
    .upload-zona-texto { color: #c084fc; font-family: 'Archivo Narrow', sans-serif; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.16em; }
    .upload-zona-texto strong { color: #c084fc; }
    .upload-zona input[type="file"] { position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
    .upload-preview { margin-top: 1rem; display: none; }
    .upload-preview img { width: 100%; max-height: 220px; object-fit: cover; border: 1px solid rgba(245,241,234,0.10); }
    .upload-preview .upload-nombre { margin-top: 6px; font-family: 'Archivo Narrow', sans-serif; font-size: 0.625rem; color: rgba(245,241,234,0.40); text-transform: uppercase; letter-spacing: 0.1em; }

    .campo-error { display: none; color: #f87171; font-size: 0.75rem; margin-top: 5px; }

    .fiesta-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 14px;
        border: 1px solid rgba(168,85,247,0.7);
        background: rgba(168,85,247,0.08);
        border-radius: 4px;
        font-family: 'Archivo Narrow', sans-serif;
        font-size: 13px;
        color: #c084fc;
        font-weight: 600;
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
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold mb-4"
             style="background:rgba(168,85,247,0.15);border:1px solid rgba(168,85,247,0.3);color:#c084fc;letter-spacing:0.06em;text-transform:uppercase;">
            🎉 Nuevo evento Fiesta
        </div>
        <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight leading-tight">
            Crear evento <span class="text-gradient-claro">Fiesta</span>
        </h1>
        <p class="mt-3 text-white/50 text-base max-w-lg mx-auto">
            Pago obligatorio · Acceso +18 · Camarero asignado requerido
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

    <form action="{{ route('empresa.fiesta.guardar') }}" method="POST" class="form-crear-evento" enctype="multipart/form-data">
        @csrf

        {{-- Categoría Fiesta preseleccionada (oculta) --}}
        <input type="hidden" name="categorias[]" value="{{ $fiestaId }}">

        {{-- ── INFORMACIÓN BÁSICA ── --}}
        <div class="form-section-title">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Información básica
        </div>

        <div class="form-grupo">
            <label class="form-label">Categoría</label>
            <span class="fiesta-badge">🎉 Fiesta</span>
        </div>

        <div class="form-grupo">
            <label class="form-label">Título del evento <span class="form-required">*</span></label>
            <input type="text" name="titulo" id="campo-titulo" class="form-input" maxlength="300"
                   value="{{ old('titulo') }}"
                   placeholder="Ej: Noche de Verano 2026"
                   onblur="validarTitulo()">
            <p class="campo-error" id="error-titulo"></p>
        </div>

        <div class="form-grupo">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-textarea" rows="4"
                      placeholder="Describe la fiesta: música, ambiente, artistas...">{{ old('descripcion') }}</textarea>
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
                <p class="campo-error" id="error-fecha-inicio"></p>
            </div>
            <div>
                <label class="form-label">Fecha de fin</label>
                <input type="text" name="fecha_fin" id="fecha_fin" class="form-input flatpickr-input"
                       value="{{ old('fecha_fin') }}" placeholder="dd/mm/aaaa hh:mm" autocomplete="off" readonly>
                <p class="form-hint">Opcional.</p>
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
                       value="{{ old('ubicacion_nombre') }}"
                       placeholder="Ej: Club Moog"
                       onblur="validarUbicacion()">
                <p class="campo-error" id="error-ubicacion"></p>
            </div>
            <div>
                <label class="form-label">Dirección</label>
                <input type="text" name="ubicacion_direccion" class="form-input" maxlength="500"
                       value="{{ old('ubicacion_direccion') }}"
                       placeholder="Ej: Carrer de l'Arc del Teatre, 3">
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

        <div class="form-grupo-doble">
            <div>
                <label class="form-label">Precio entrada (€) <span class="form-required">*</span></label>
                <input type="number" min="10" step="0.01" name="precio_base" class="form-input"
                       id="precio_base_input"
                       value="{{ old('precio_base', 10) }}" placeholder="Mín. 10,00 €"
                       onblur="validarPrecio()">
                <p class="campo-error" id="error-precio"></p>
                <p class="form-hint">Los eventos Fiesta son de pago obligatorio (mín. 10 €).</p>
            </div>
            <div>
                <label class="form-label">Aforo máximo</label>
                <input type="number" min="1" name="aforo_maximo" class="form-input"
                       value="{{ old('aforo_maximo') }}" placeholder="Ej: 300">
                <p class="form-hint">Opcional.</p>
            </div>
        </div>

        <div class="form-grupo-doble">
            <div>
                <label class="form-label">Edad mínima</label>
                <input type="number" name="edad_minima" class="form-input"
                       value="18" readonly style="opacity:0.6;cursor:not-allowed;">
                <p class="form-hint">Los eventos Fiesta requieren ser mayor de 18 años.</p>
            </div>
            <div>
                <label class="form-label">URL externa</label>
                <input type="url" name="url_externa" class="form-input" maxlength="500"
                       value="{{ old('url_externa') }}" placeholder="https://...">
                <p class="form-hint">Enlace a la web del evento, si la tiene.</p>
            </div>
        </div>

        <hr class="form-divider">

        {{-- ── CAMARERO ── --}}
        <div class="form-section-title">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Camarero asignado
        </div>

        <div class="form-grupo">
            <label class="form-label">Camarero <span class="form-required">*</span></label>
            <select name="camarero_id" id="camarero_id_select" class="form-input"
                    onblur="validarCamarero()" onchange="validarCamarero()">
                <option value="">— Selecciona un camarero —</option>
                @foreach($camareros as $cam)
                    <option value="{{ $cam->id }}" @selected(old('camarero_id') == $cam->id)>
                        {{ $cam->usuario->nombre }} {{ $cam->usuario->apellido1 }}
                    </option>
                @endforeach
            </select>
            <p class="campo-error" id="error-camarero"></p>
            @if($camareros->isEmpty())
                <p style="color:#f59e0b;font-size:0.75rem;margin-top:6px;">
                    ⚠ No tienes camareros contratados. Publica una oferta con categoría Camarero/a en la bolsa de trabajo.
                </p>
            @endif
            @error('camarero_id')
                <p style="color:#f87171;font-size:11px;margin-top:6px;">{{ $message }}</p>
            @enderror
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
            <div class="upload-zona" id="upload-zona">
                <input type="file" name="imagen_portada" id="imagen_portada_input"
                       accept="image/jpeg,image/png,image/webp,image/gif">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="upload-zona-texto"><strong>Haz clic o arrastra</strong> una imagen aquí</p>
                <p class="upload-zona-texto" style="font-size:0.75rem;margin-top:0.2rem;">JPG, PNG, WebP o GIF · Máx. 5 MB</p>
            </div>
            <div class="upload-preview" id="upload-preview">
                <img id="imagen-preview-img" alt="Vista previa">
                <p class="upload-nombre" id="upload-nombre"></p>
            </div>
        </div>

        {{-- ── ACCIONES ── --}}
        <div class="form-actions">
            <button type="button" class="btn-guardar" onclick="validarYEnviarFiesta()">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" width="18" height="18">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Publicar evento Fiesta
            </button>
            <a href="{{ route('empresa.fiesta.index') }}" class="btn-cancelar">Cancelar</a>
        </div>
    </form>
</section>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script src="{{ asset('js/fiesta-crear.js') }}"></script>
<script>iniciarFiestaCrear();</script>
@endpush

@extends('layouts.app')

@section('titulo', 'Crear Evento — VIBEZ')

@push('estilos')
<style>
    .form-crear-evento {
        background: white;
        border: 1px solid #ede9fe;
        border-radius: 1.25rem;
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 8px 32px rgba(124,58,237,0.06);
    }
    .form-grupo {
        margin-bottom: 1.25rem;
    }
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
        display: block;
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--navy);
        margin-bottom: 0.35rem;
        letter-spacing: 0.02em;
    }
    .form-label .form-required {
        color: #dc2626;
        margin-left: 2px;
    }
    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 0.7rem 0.9rem;
        border: 1.5px solid #ddd6fe;
        border-radius: 0.75rem;
        font-size: 0.9rem;
        font-family: 'Inter', sans-serif;
        color: var(--navy);
        background: #faf8ff;
        transition: border-color 0.2s, box-shadow 0.2s;
        outline: none;
        box-sizing: border-box;
    }
    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        border-color: var(--morado);
        box-shadow: 0 0 0 3px rgba(124,58,237,0.1);
    }
    .form-input::placeholder,
    .form-textarea::placeholder {
        color: rgba(15,23,42,0.3);
    }
    .form-textarea { resize: vertical; min-height: 100px; }
    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%237c3aed' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 36px;
    }
    .form-hint {
        font-size: 0.75rem;
        color: rgba(15,23,42,0.4);
        margin-top: 0.3rem;
    }
    .form-checkbox-wrap {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.85rem 1rem;
        background: #faf8ff;
        border: 1.5px solid #ddd6fe;
        border-radius: 0.75rem;
        cursor: pointer;
        transition: border-color 0.2s;
    }
    .form-checkbox-wrap:hover { border-color: var(--morado); }
    .form-checkbox-wrap input[type="checkbox"] {
        width: 18px; height: 18px; accent-color: var(--morado); cursor: pointer;
    }
    .form-checkbox-label {
        font-size: 0.9rem; font-weight: 600; color: var(--navy); cursor: pointer;
    }
    .form-divider {
        border: none;
        border-top: 1px solid #f0eeff;
        margin: 1.5rem 0;
    }
    .form-section-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--morado);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .form-section-title svg { width: 18px; height: 18px; flex-shrink: 0; }
    .form-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #f0eeff;
    }
    .btn-guardar {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.85rem 2rem;
        background: linear-gradient(135deg, #7c3aed, #a855f7);
        color: #fff; font-weight: 800; font-size: 0.95rem;
        border: none; border-radius: 0.85rem; cursor: pointer;
        transition: transform 0.15s, box-shadow 0.2s;
        box-shadow: 0 4px 20px rgba(124,58,237,0.3);
    }
    .btn-guardar:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 28px rgba(124,58,237,0.45);
    }
    .btn-cancelar {
        padding: 0.85rem 1.5rem;
        background: transparent;
        border: 1.5px solid #ddd6fe;
        color: var(--navy);
        font-weight: 600; font-size: 0.9rem;
        border-radius: 0.85rem; cursor: pointer;
        text-decoration: none;
        transition: background 0.15s, border-color 0.15s;
    }
    .btn-cancelar:hover { background: #f7f5ff; border-color: var(--morado); }

    .alert-errores {
        background: #fef2f2;
        border: 1.5px solid #fecaca;
        border-radius: 0.75rem;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        color: #991b1b;
        font-size: 0.85rem;
    }
    .alert-errores strong { display: block; margin-bottom: 0.4rem; }
    .alert-errores ul { margin: 0; padding-left: 1.25rem; }
    .alert-errores li { margin-bottom: 0.2rem; }

    #precio-wrap { transition: opacity 0.3s; }
    #precio-wrap.desactivado { opacity: 0.4; pointer-events: none; }

    /* Zona de subida de imagen */
    .upload-zona {
        border: 2px dashed #ddd6fe;
        border-radius: 0.75rem;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s;
        background: #faf8ff;
        position: relative;
    }
    .upload-zona:hover,
    .upload-zona.dragover {
        border-color: var(--morado);
        background: #f0eaff;
    }
    .upload-zona svg {
        width: 40px; height: 40px;
        color: var(--morado);
        opacity: 0.5;
        margin: 0 auto 0.5rem;
    }
    .upload-zona-texto {
        color: rgba(15,23,42,0.5);
        font-size: 0.85rem;
    }
    .upload-zona-texto strong {
        color: var(--morado);
        font-weight: 700;
    }
    .upload-zona input[type="file"] {
        position: absolute;
        inset: 0;
        width: 100%; height: 100%;
        opacity: 0;
        cursor: pointer;
    }
    .upload-preview {
        margin-top: 1rem;
        display: none;
    }
    .upload-preview img {
        width: 100%;
        max-height: 220px;
        object-fit: cover;
        border-radius: 0.75rem;
        border: 1.5px solid #ede9fe;
    }
    .upload-preview .upload-nombre {
        margin-top: 0.4rem;
        font-size: 0.8rem;
        color: rgba(15,23,42,0.5);
    }
</style>
@endpush

@section('contenido')

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

        <div class="form-grupo-doble">
            <div>
                <label class="form-label">Categoría <span class="form-required">*</span></label>
                <select name="categoria_evento_id" class="form-select">
                    <option value="">Selecciona categoría</option>
                    @foreach ($categorias as $cat)
                        <option value="{{ $cat->id }}" @selected(old('categoria_evento_id') == $cat->id)>
                            {{ $cat->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Tipo de evento <span class="form-required">*</span></label>
                <select name="tipo_evento" class="form-select">
                    <option value="1" @selected(old('tipo_evento', 1) == 1)>Presencial</option>
                    <option value="2" @selected(old('tipo_evento') == 2)>Online</option>
                </select>
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
                <input type="datetime-local" name="fecha_inicio" class="form-input"
                       value="{{ old('fecha_inicio') }}">
            </div>
            <div>
                <label class="form-label">Fecha de fin</label>
                <input type="datetime-local" name="fecha_fin" class="form-input"
                       value="{{ old('fecha_fin') }}">
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
<script>
function togglePrecio() {
    var checkbox = document.getElementById('es_gratuito');
    var precioWrap = document.getElementById('precio-wrap');
    var precioInput = document.getElementById('precio_base_input');

    if (checkbox.checked) {
        precioWrap.classList.add('desactivado');
        precioInput.value = '0';
    } else {
        precioWrap.classList.remove('desactivado');
    }
}

// Preview de imagen subida
document.addEventListener('DOMContentLoaded', function() {
    togglePrecio();

    var fileInput = document.getElementById('imagen_portada_input');
    var zona = document.getElementById('upload-zona');
    var preview = document.getElementById('upload-preview');
    var previewImg = document.getElementById('imagen-preview-img');
    var nombreSpan = document.getElementById('upload-nombre');

    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            var file = this.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
                nombreSpan.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });

    // Drag & drop visual feedback
    zona.addEventListener('dragover', function(e) {
        e.preventDefault();
        zona.classList.add('dragover');
    });
    zona.addEventListener('dragleave', function() {
        zona.classList.remove('dragover');
    });
    zona.addEventListener('drop', function() {
        zona.classList.remove('dragover');
    });
});
</script>
@endpush

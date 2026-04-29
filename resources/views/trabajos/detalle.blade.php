@extends('layouts.app')

@section('titulo', $oferta->titulo)

@push('estilos')
<style>
    .cv-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: #1a1a2e;
        opacity: 0.55;
        margin-bottom: 0.35rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }
    .cv-input {
        display: block;
        width: 100%;
        padding: 0.6rem 0.85rem;
        border: 2px solid rgba(26, 26, 46, 0.13);
        border-radius: 0.75rem;
        font-size: 0.875rem;
        color: #1a1a2e;
        background: #fff;
        transition: border-color 0.15s, box-shadow 0.15s;
        outline: none;
        font-family: inherit;
    }
    .cv-input:focus {
        border-color: #8b5cf6;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.12);
    }
    .cv-input::placeholder { color: rgba(26,26,46,0.28); }
    textarea.cv-input { resize: vertical; }
    .cv-section-num {
        width: 1.75rem; height: 1.75rem;
        background: #ede9fe; color: #7c3aed;
        border-radius: 0.5rem;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.8rem; font-weight: 900;
        flex-shrink: 0;
    }
    .modal-overlay {
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.55);
        backdrop-filter: blur(4px);
        z-index: 200;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    .modal-overlay.abierto { display: flex; }
    .modal-box {
        background: #fff;
        border-radius: 1.25rem;
        box-shadow: 0 25px 60px rgba(0,0,0,0.2);
        width: 100%;
        animation: modalIn 0.2s ease;
    }
    @keyframes modalIn {
        from { opacity: 0; transform: translateY(16px) scale(0.98); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    .modal-scrollable { overflow-y: auto; flex: 1; }
    .exp-item, .edu-item {
        background: #f9f9fb;
        border-radius: 0.875rem;
        padding: 1rem;
        position: relative;
    }
    .btn-eliminar-item {
        position: absolute; top: 0.6rem; right: 0.6rem;
        background: none; border: none; cursor: pointer;
        color: rgba(26,26,46,0.3); font-size: 1.1rem; line-height: 1;
        padding: 0.2rem 0.4rem;
        border-radius: 0.4rem;
        transition: color 0.15s, background 0.15s;
    }
    .btn-eliminar-item:hover { color: #ef4444; background: #fee2e2; }
    .dropzone-area {
        border: 2px dashed rgba(26,26,46,0.18);
        border-radius: 1rem;
        padding: 2.5rem 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.15s, background 0.15s;
    }
    .dropzone-area:hover, .dropzone-area.drag-over {
        border-color: #8b5cf6;
        background: rgba(139,92,246,0.04);
    }
</style>
@endpush

@section('contenido')

{{-- ════════════════════════════════════════════════════
     HERO DE LA OFERTA — sin imagen, fondo navy
════════════════════════════════════════════════════ --}}
<div class="hero-trabajo">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

        {{-- Botón volver --}}
        <a href="{{ route('home') }}?categoria=trabajo" class="btn-volver">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver a Bolsa de Trabajo
        </a>

        {{-- Badge de tipo --}}
        <span class="badge-trabajo inline-block mt-6">Oferta de trabajo</span>

        {{-- Título de la oferta --}}
        <h1 class="text-3xl sm:text-5xl font-black text-white mt-3 leading-tight max-w-3xl">
            {{ $oferta->titulo }}
        </h1>

        {{-- Datos clave: empresa, ubicación, salario --}}
        <div class="flex flex-wrap gap-6 mt-6">

            {{-- Empresa --}}
            @if ($oferta->organizador?->empresa)
                <div class="dato-hero">
                    <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="font-semibold">{{ $oferta->organizador->empresa->nombre_empresa }}</span>
                </div>
            @endif

            {{-- Ubicación --}}
            @if ($oferta->ubicacion)
                <div class="dato-hero">
                    <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    </svg>
                    <span>{{ $oferta->ubicacion }}</span>
                </div>
            @endif

            {{-- Salario --}}
            <div class="dato-hero">
                <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-bold text-lg text-green-300">{{ $oferta->salario_formateado }}</span>
            </div>

        </div>

    </div>
</div>

{{-- ════════════════════════════════════════════════════
     CUERPO DEL DETALLE
════════════════════════════════════════════════════ --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        {{-- ─── Columna izquierda: descripción y requisitos ─── --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- Descripción de la oferta --}}
            @if ($oferta->descripcion)
                <section class="seccion-detalle">
                    <h2 class="seccion-titulo">Descripción del puesto</h2>
                    <p class="text-navy/80 leading-relaxed">{{ $oferta->descripcion }}</p>
                </section>
            @endif

            {{-- Requisitos --}}
            @if ($oferta->requisitos)
                <section class="seccion-detalle">
                    <h2 class="seccion-titulo">Requisitos</h2>
                    <p class="text-navy/80 leading-relaxed">{{ $oferta->requisitos }}</p>
                </section>
            @endif

            {{-- Datos adicionales --}}
            <section class="seccion-detalle">
                <h2 class="seccion-titulo">Detalles</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">

                    <div class="ficha-dato">
                        <span class="ficha-dato-label">Vacantes</span>
                        <span class="ficha-dato-valor">{{ $oferta->vacantes }}</span>
                    </div>

                    @if ($oferta->fecha_inicio_trabajo)
                        <div class="ficha-dato">
                            <span class="ficha-dato-label">Inicio</span>
                            <span class="ficha-dato-valor">
                                {{ \Carbon\Carbon::parse($oferta->fecha_inicio_trabajo)->format('d/m/Y') }}
                            </span>
                        </div>
                    @endif

                    @if ($oferta->fecha_fin_trabajo)
                        <div class="ficha-dato">
                            <span class="ficha-dato-label">Fin contrato</span>
                            <span class="ficha-dato-valor">
                                {{ \Carbon\Carbon::parse($oferta->fecha_fin_trabajo)->format('d/m/Y') }}
                            </span>
                        </div>
                    @endif

                </div>
            </section>

        </div>

        {{-- ─── Columna derecha: botón de postulación ─── --}}
        <div class="lg:col-span-1">
            <div class="lg:sticky lg:top-24">
                <div class="ficha-compra">

                    {{-- Salario destacado --}}
                    <div class="text-center mb-6">
                        <p class="text-navy/50 text-sm uppercase tracking-widest font-semibold mb-1">Salario</p>
                        <p class="text-2xl font-black text-green-600">{{ $oferta->salario_formateado }}</p>
                    </div>

                    {{-- Vacantes disponibles --}}
                    <div class="bg-purple-50 rounded-xl p-4 text-center mb-6">
                        <p class="text-3xl font-black text-navy">{{ $oferta->vacantes }}</p>
                        <p class="text-navy/50 text-sm">vacante{{ $oferta->vacantes !== 1 ? 's' : '' }} disponible{{ $oferta->vacantes !== 1 ? 's' : '' }}</p>
                    </div>

                    {{-- Botón de postulación --}}
                    <button class="btn-comprar w-full"
                            onclick="abrirPostulacion({{ $oferta->id }})">
                        Postularme ahora
                    </button>

                    <p class="text-center text-navy/40 text-xs mt-4">
                        Tu candidatura se enviará al organizador
                    </p>

                </div>
            </div>
        </div>

    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════
     MODALES DE POSTULACIÓN
═══════════════════════════════════════════════════════════════ --}}
<div id="modal-overlay" class="modal-overlay" onclick="cerrarAlClickarFuera(event)">

    {{-- ── MODAL 1: Elegir método ── --}}
    <div id="modal-eleccion" class="modal-box max-w-lg" style="display:none">
        <div class="p-7">
            <div class="flex items-start justify-between mb-5">
                <div>
                    <h2 class="text-2xl font-black text-navy">¿Cómo quieres postularte?</h2>
                    <p class="text-navy/50 text-sm mt-1">Elige cómo enviar tu candidatura</p>
                </div>
                <button onclick="cerrarModal()" class="ml-4 mt-1 text-navy/30 hover:text-navy transition-colors flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- Opción: Rellenar formulario CV --}}
                <button onclick="mostrarFormulario()"
                        class="group text-left flex flex-col p-5 border-2 border-navy/10 rounded-2xl hover:border-purple-400 hover:bg-purple-50/60 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-400">
                    <div class="w-12 h-12 bg-purple-100 group-hover:bg-purple-200 rounded-xl flex items-center justify-center mb-4 transition-colors">
                        <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <p class="font-bold text-navy mb-1">Rellenar formulario</p>
                    <p class="text-navy/50 text-sm leading-snug">Completa tu CV con tus datos, experiencia y formación</p>
                </button>

                {{-- Opción: Subir archivo --}}
                <button onclick="mostrarSubirArchivo()"
                        class="group text-left flex flex-col p-5 border-2 border-navy/10 rounded-2xl hover:border-green-400 hover:bg-green-50/60 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-400">
                    <div class="w-12 h-12 bg-green-100 group-hover:bg-green-200 rounded-xl flex items-center justify-center mb-4 transition-colors">
                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                    </div>
                    <p class="font-bold text-navy mb-1">Subir archivo</p>
                    <p class="text-navy/50 text-sm leading-snug">Sube tu CV en PDF o Word ya preparado</p>
                </button>
            </div>
        </div>
    </div>

    {{-- ── MODAL 2: Formulario CV ── --}}
    <div id="modal-formulario" class="modal-box max-w-2xl flex flex-col" style="display:none; max-height:92vh">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-navy/10 flex-shrink-0">
            <div class="flex items-center gap-3">
                <button onclick="volverAEleccion()" class="text-navy/35 hover:text-navy transition-colors" title="Volver">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <div>
                    <h2 class="text-xl font-black text-navy leading-tight">Currículum Vitae</h2>
                    <p class="text-navy/40 text-xs" id="subtitulo-oferta-form">Completa tu perfil para postularte</p>
                </div>
            </div>
            <button onclick="cerrarModal()" class="text-navy/30 hover:text-navy transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Formulario scrollable --}}
        <div class="modal-scrollable">
            <form id="form-cv" class="px-6 py-5 space-y-7" novalidate>
                @csrf
                <input type="hidden" name="oferta_id" id="oferta-id-form" value="">

                {{-- ── 1. Información personal ── --}}
                <section>
                    <h3 class="flex items-center gap-2 font-bold text-navy text-sm mb-4">
                        <span class="cv-section-num">1</span>
                        Información Personal
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="cv-label">Nombre <span class="text-red-400 normal-case">*</span></label>
                            <input type="text" name="nombre" class="cv-input" placeholder="María" required>
                        </div>
                        <div>
                            <label class="cv-label">Apellidos <span class="text-red-400 normal-case">*</span></label>
                            <input type="text" name="apellidos" class="cv-input" placeholder="García López" required>
                        </div>
                        <div>
                            <label class="cv-label">Email <span class="text-red-400 normal-case">*</span></label>
                            <input type="email" name="email" class="cv-input" placeholder="maria@email.com" required>
                        </div>
                        <div>
                            <label class="cv-label">Teléfono <span class="text-red-400 normal-case">*</span></label>
                            <input type="tel" name="telefono" class="cv-input" placeholder="612 345 678" required>
                        </div>
                        <div>
                            <label class="cv-label">Ciudad <span class="text-red-400 normal-case">*</span></label>
                            <input type="text" name="ciudad" class="cv-input" placeholder="Barcelona" required>
                        </div>
                        <div>
                            <label class="cv-label">LinkedIn / Portfolio</label>
                            <input type="url" name="linkedin" class="cv-input" placeholder="linkedin.com/in/tu-perfil">
                        </div>
                    </div>
                </section>

                {{-- ── 2. Perfil profesional ── --}}
                <section>
                    <h3 class="flex items-center gap-2 font-bold text-navy text-sm mb-4">
                        <span class="cv-section-num">2</span>
                        Perfil Profesional
                    </h3>
                    <textarea name="perfil_profesional" class="cv-input" rows="3"
                              placeholder="Describe brevemente quién eres, tu objetivo profesional y qué te diferencia como candidato..." required></textarea>
                </section>

                {{-- ── 3. Experiencia laboral ── --}}
                <section>
                    <h3 class="flex items-center gap-2 font-bold text-navy text-sm mb-1">
                        <span class="cv-section-num">3</span>
                        Experiencia Laboral
                    </h3>
                    <p class="text-navy/40 text-xs mb-3 ml-8">Añade tus experiencias más relevantes</p>

                    <div id="exp-container" class="space-y-3">
                        <div class="exp-item">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="cv-label">Empresa</label>
                                    <input type="text" name="exp_empresa[]" class="cv-input" placeholder="Empresa S.L.">
                                </div>
                                <div>
                                    <label class="cv-label">Cargo / Puesto</label>
                                    <input type="text" name="exp_cargo[]" class="cv-input" placeholder="Técnico de Sonido">
                                </div>
                                <div>
                                    <label class="cv-label">Desde</label>
                                    <input type="month" name="exp_desde[]" class="cv-input">
                                </div>
                                <div>
                                    <label class="cv-label">Hasta (vacío = actualidad)</label>
                                    <input type="month" name="exp_hasta[]" class="cv-input">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="cv-label">Descripción de tareas y logros</label>
                                    <textarea name="exp_descripcion[]" class="cv-input" rows="2"
                                              placeholder="Principales responsabilidades y logros en el puesto..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" onclick="agregarExperiencia()"
                            class="mt-3 text-purple-600 hover:text-purple-800 text-sm font-semibold flex items-center gap-1.5 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        Añadir experiencia
                    </button>
                </section>

                {{-- ── 4. Formación académica ── --}}
                <section>
                    <h3 class="flex items-center gap-2 font-bold text-navy text-sm mb-1">
                        <span class="cv-section-num">4</span>
                        Formación Académica
                    </h3>
                    <p class="text-navy/40 text-xs mb-3 ml-8">Estudios, cursos y certificaciones</p>

                    <div id="edu-container" class="space-y-3">
                        <div class="edu-item">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="sm:col-span-2">
                                    <label class="cv-label">Institución</label>
                                    <input type="text" name="edu_institucion[]" class="cv-input" placeholder="Universitat Politècnica de Catalunya">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="cv-label">Titulación / Certificación</label>
                                    <input type="text" name="edu_titulo[]" class="cv-input" placeholder="Grado en Ingeniería de Sonido e Imagen">
                                </div>
                                <div>
                                    <label class="cv-label">Año inicio</label>
                                    <input type="number" name="edu_inicio[]" class="cv-input" placeholder="2020" min="1950" max="2035">
                                </div>
                                <div>
                                    <label class="cv-label">Año fin</label>
                                    <input type="number" name="edu_fin[]" class="cv-input" placeholder="2024" min="1950" max="2035">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" onclick="agregarFormacion()"
                            class="mt-3 text-purple-600 hover:text-purple-800 text-sm font-semibold flex items-center gap-1.5 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        Añadir titulación
                    </button>
                </section>

                {{-- ── 5. Habilidades ── --}}
                <section>
                    <h3 class="flex items-center gap-2 font-bold text-navy text-sm mb-4">
                        <span class="cv-section-num">5</span>
                        Habilidades
                    </h3>
                    <input type="text" name="habilidades" class="cv-input"
                           placeholder="Pro Tools, Logic Pro, Ableton Live, gestión de equipos, trabajo bajo presión...">
                    <p class="text-navy/35 text-xs mt-1.5">Separa las habilidades con comas</p>
                </section>

                {{-- ── 6. Idiomas ── --}}
                <section>
                    <h3 class="flex items-center gap-2 font-bold text-navy text-sm mb-4">
                        <span class="cv-section-num">6</span>
                        Idiomas
                    </h3>
                    <input type="text" name="idiomas" class="cv-input"
                           placeholder="Español (nativo), Catalán (nativo), Inglés (B2), Francés (A2)">
                </section>

                {{-- ── 7. Carta de presentación ── --}}
                <section class="pb-2">
                    <h3 class="flex items-center gap-2 font-bold text-navy text-sm mb-4">
                        <span class="cv-section-num">7</span>
                        Carta de Presentación
                    </h3>
                    <textarea name="carta_presentacion" class="cv-input" rows="4"
                              placeholder="Explica por qué eres el candidato ideal para este puesto, qué te motiva de la empresa y qué puedes aportar al equipo..." required></textarea>
                </section>

            </form>
        </div>

        {{-- Footer --}}
        <div class="flex gap-3 px-6 py-4 border-t border-navy/10 flex-shrink-0">
            <button type="button" onclick="volverAEleccion()"
                    class="px-5 py-2.5 border-2 border-navy/15 text-navy/70 font-semibold rounded-xl hover:bg-navy/5 transition-colors text-sm">
                Volver
            </button>
            <button type="submit" form="form-cv" id="btn-enviar-cv"
                    class="btn-comprar flex-1 py-2.5 rounded-xl font-bold text-sm disabled:opacity-60 disabled:cursor-not-allowed">
                Enviar candidatura
            </button>
        </div>
    </div>

    {{-- ── MODAL 3: Subir archivo ── --}}
    <div id="modal-archivo" class="modal-box max-w-md" style="display:none">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-navy/10">
            <div class="flex items-center gap-3">
                <button onclick="volverAEleccion()" class="text-navy/35 hover:text-navy transition-colors" title="Volver">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <div>
                    <h2 class="text-xl font-black text-navy leading-tight">Subir CV</h2>
                    <p class="text-navy/40 text-xs" id="subtitulo-oferta-archivo">Adjunta tu currículum</p>
                </div>
            </div>
            <button onclick="cerrarModal()" class="text-navy/30 hover:text-navy transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="form-archivo" class="px-6 py-5 space-y-5">
            @csrf
            <input type="hidden" name="oferta_id" id="oferta-id-archivo" value="">

            {{-- Dropzone --}}
            <div id="dropzone"
                 class="dropzone-area"
                 onclick="document.getElementById('cv-file-input').click()"
                 ondragover="dragOver(event)"
                 ondragleave="dragLeave(event)"
                 ondrop="dropFile(event)">
                <div class="w-14 h-14 bg-navy/5 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-navy/35" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                </div>
                <p class="text-navy font-semibold text-sm mb-1">Arrastra tu CV aquí</p>
                <p class="text-navy/40 text-sm">o haz clic para seleccionar un archivo</p>
                <p id="file-name-label" class="mt-3 text-sm text-purple-600 font-semibold hidden"></p>
                <input type="file" id="cv-file-input" name="cv_file" class="hidden"
                       accept=".pdf,.doc,.docx" onchange="mostrarNombreArchivo(this)">
            </div>
            <p class="text-navy/35 text-xs text-center -mt-2">Formatos aceptados: PDF, DOC, DOCX &middot; Máximo 5 MB</p>

            <div>
                <label class="cv-label">Carta de presentación <span class="normal-case font-normal">(opcional)</span></label>
                <textarea name="carta_presentacion_archivo" class="cv-input" rows="3"
                          placeholder="Añade un mensaje personalizado para el reclutador..."></textarea>
            </div>
        </form>

        <div class="flex gap-3 px-6 py-4 border-t border-navy/10">
            <button type="button" onclick="volverAEleccion()"
                    class="px-5 py-2.5 border-2 border-navy/15 text-navy/70 font-semibold rounded-xl hover:bg-navy/5 transition-colors text-sm">
                Volver
            </button>
            <button type="submit" form="form-archivo" id="btn-enviar-archivo"
                    class="btn-comprar flex-1 py-2.5 rounded-xl font-bold text-sm disabled:opacity-60 disabled:cursor-not-allowed">
                Enviar candidatura
            </button>
        </div>
    </div>

    {{-- ── MODAL: Éxito ── --}}
    <div id="modal-exito" class="modal-box max-w-md text-center" style="display:none">
        <div class="p-8">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h2 class="text-2xl font-black text-navy mb-2">¡Candidatura enviada!</h2>
            <p class="text-navy/55 text-sm leading-relaxed mb-6">
                Tu candidatura ha sido recibida correctamente. El equipo de selección revisará tu perfil y se pondrá en contacto contigo.
            </p>
            <button onclick="cerrarModal()"
                    class="btn-comprar px-10 py-3 rounded-xl font-bold">
                Perfecto
            </button>
        </div>
    </div>

</div>{{-- /modal-overlay --}}

@endsection

@push('scripts')
<script>
let _ofertaActual = null;

function abrirPostulacion(ofertaId) {
    _ofertaActual = ofertaId;
    document.getElementById('oferta-id-form').value    = ofertaId;
    document.getElementById('oferta-id-archivo').value = ofertaId;
    _mostrarModal('modal-eleccion');
}

function _mostrarModal(id) {
    const overlay = document.getElementById('modal-overlay');
    overlay.classList.add('abierto');

    ['modal-eleccion', 'modal-formulario', 'modal-archivo', 'modal-exito'].forEach(function(mid) {
        document.getElementById(mid).style.display = 'none';
    });

    const target = document.getElementById(id);
    target.style.display = id === 'modal-formulario' ? 'flex' : 'block';
}

function cerrarModal() {
    document.getElementById('modal-overlay').classList.remove('abierto');
}

function cerrarAlClickarFuera(e) {
    if (e.target === document.getElementById('modal-overlay')) cerrarModal();
}

function mostrarFormulario()   { _mostrarModal('modal-formulario'); }
function mostrarSubirArchivo() { _mostrarModal('modal-archivo'); }
function volverAEleccion()     { _mostrarModal('modal-eleccion'); }

// ── Añadir / eliminar entradas de experiencia ──
function agregarExperiencia() {
    var tpl = document.querySelector('#exp-container .exp-item');
    var clone = tpl.cloneNode(true);
    clone.querySelectorAll('input, textarea').forEach(function(el) { el.value = ''; });
    if (!clone.querySelector('.btn-eliminar-item')) {
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn-eliminar-item';
        btn.title = 'Eliminar';
        btn.innerHTML = '&#10005;';
        btn.onclick = function() { this.closest('.exp-item').remove(); };
        clone.appendChild(btn);
    }
    document.getElementById('exp-container').appendChild(clone);
}

function agregarFormacion() {
    var tpl = document.querySelector('#edu-container .edu-item');
    var clone = tpl.cloneNode(true);
    clone.querySelectorAll('input').forEach(function(el) { el.value = ''; });
    if (!clone.querySelector('.btn-eliminar-item')) {
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn-eliminar-item';
        btn.title = 'Eliminar';
        btn.innerHTML = '&#10005;';
        btn.onclick = function() { this.closest('.edu-item').remove(); };
        clone.appendChild(btn);
    }
    document.getElementById('edu-container').appendChild(clone);
}

// ── Drag & drop ──
function mostrarNombreArchivo(input) {
    var label = document.getElementById('file-name-label');
    if (input.files.length) {
        label.textContent = '📄 ' + input.files[0].name;
        label.classList.remove('hidden');
    }
}

function dragOver(e) {
    e.preventDefault();
    e.currentTarget.classList.add('drag-over');
}
function dragLeave(e) {
    e.currentTarget.classList.remove('drag-over');
}
function dropFile(e) {
    e.preventDefault();
    e.currentTarget.classList.remove('drag-over');
    var dt = e.dataTransfer;
    if (dt.files.length) {
        var input = document.getElementById('cv-file-input');
        var allowed = ['application/pdf',
                       'application/msword',
                       'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!allowed.includes(dt.files[0].type)) {
            alert('Formato no permitido. Usa PDF, DOC o DOCX.');
            return;
        }
        // DataTransfer trick to assign files
        var dTrans = new DataTransfer();
        dTrans.items.add(dt.files[0]);
        input.files = dTrans.files;
        mostrarNombreArchivo(input);
    }
}

// ── Envío formulario CV ──
document.getElementById('form-cv').addEventListener('submit', async function(e) {
    e.preventDefault();
    var btn = document.getElementById('btn-enviar-cv');
    btn.disabled = true;
    btn.textContent = 'Enviando...';

    try {
        var res = await fetch('/trabajos/' + _ofertaActual + '/postular', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: new FormData(this),
        });
        var data = await res.json();
        if (data.success) {
            _mostrarModal('modal-exito');
            this.reset();
        } else {
            alert(data.message || 'Error al enviar la candidatura.');
        }
    } catch(err) {
        alert('Error de conexión. Inténtalo de nuevo.');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Enviar candidatura';
    }
});

// ── Envío formulario archivo ──
document.getElementById('form-archivo').addEventListener('submit', async function(e) {
    e.preventDefault();
    var fileInput = document.getElementById('cv-file-input');
    if (!fileInput.files.length) {
        alert('Por favor, selecciona un archivo CV antes de enviar.');
        return;
    }
    var btn = document.getElementById('btn-enviar-archivo');
    btn.disabled = true;
    btn.textContent = 'Enviando...';

    try {
        var res = await fetch('/trabajos/' + _ofertaActual + '/postular-archivo', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: new FormData(this),
        });
        var data = await res.json();
        if (data.success) {
            _mostrarModal('modal-exito');
            this.reset();
            document.getElementById('file-name-label').classList.add('hidden');
        } else {
            alert(data.message || 'Error al enviar el CV.');
        }
    } catch(err) {
        alert('Error de conexión. Inténtalo de nuevo.');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Enviar candidatura';
    }
});
</script>
@endpush

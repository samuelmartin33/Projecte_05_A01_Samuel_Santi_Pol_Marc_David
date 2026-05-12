<?php $__env->startSection('titulo', $oferta->titulo); ?>

<?php $__env->startPush('estilos'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/trabajos-detalle.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<link rel="stylesheet" href="<?php echo e(asset('css/vibez-home.css')); ?>">

<?php echo $__env->make('partials.home.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<div class="relative overflow-hidden bg-ink" style="min-height:360px;">

    <div style="position:absolute;inset:0;background-image:radial-gradient(circle,rgba(139,120,204,0.13) 1.5px,transparent 1.5px);background-size:28px 28px;pointer-events:none;"></div>
    <div style="position:absolute;width:480px;height:480px;border-radius:50%;background:radial-gradient(circle,rgba(139,120,204,0.2) 0%,transparent 60%);top:-160px;right:-100px;pointer-events:none;"></div>

    <div class="max-w-7xl mx-auto px-6 sm:px-10 py-14 relative" style="z-index:1">

        <a href="<?php echo e(route('trabajos.index')); ?>"
           class="inline-flex items-center gap-2 font-mono text-xs uppercase tracking-widest text-paper/50 hover:text-paper transition-colors duration-100 mb-8">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Bolsa de Trabajo
        </a>

        <span class="inline-block font-mono text-xs uppercase tracking-widest text-lilac border border-lilac/40 px-3 py-1 mb-4">
            Oferta de trabajo
        </span>

        <h1 class="font-display font-black uppercase text-paper tracking-tightest leading-[0.88] max-w-4xl"
            style="font-size:clamp(2rem,5vw,5rem)">
            <?php echo e($oferta->titulo); ?>

        </h1>

        <div class="flex flex-wrap items-center gap-6 mt-6">
            <?php if($oferta->organizador?->empresa): ?>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-paper/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="font-mono text-xs uppercase tracking-widest text-paper/60 font-bold"><?php echo e($oferta->organizador->empresa->nombre_empresa); ?></span>
                </div>
            <?php endif; ?>
            <?php if($oferta->ubicacion): ?>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-paper/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    </svg>
                    <span class="font-mono text-xs uppercase tracking-widest text-paper/60"><?php echo e($oferta->ubicacion); ?></span>
                </div>
            <?php endif; ?>
            <div class="flex items-center gap-2">
                <span class="font-mono text-xs uppercase tracking-widest text-lilac font-bold"><?php echo e($oferta->salario_formateado); ?></span>
            </div>
        </div>

    </div>
</div>


<div class="trabajos-detalle-wrap">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        <div class="lg:col-span-2 space-y-8">

            <?php if($oferta->descripcion): ?>
                <section class="seccion-detalle">
                    <h2 class="seccion-titulo">Descripción del puesto</h2>
                    <p class="text-navy/80 leading-relaxed"><?php echo e($oferta->descripcion); ?></p>
                </section>
            <?php endif; ?>

            <?php if($oferta->requisitos): ?>
                <section class="seccion-detalle">
                    <h2 class="seccion-titulo">Requisitos</h2>
                    <p class="text-navy/80 leading-relaxed"><?php echo e($oferta->requisitos); ?></p>
                </section>
            <?php endif; ?>

            <section class="seccion-detalle">
                <h2 class="seccion-titulo">Detalles</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <div class="ficha-dato">
                        <span class="ficha-dato-label">Vacantes</span>
                        <span class="ficha-dato-valor"><?php echo e($oferta->vacantes); ?></span>
                    </div>
                    <?php if($oferta->fecha_inicio_trabajo): ?>
                        <div class="ficha-dato">
                            <span class="ficha-dato-label">Inicio</span>
                            <span class="ficha-dato-valor"><?php echo e(\Carbon\Carbon::parse($oferta->fecha_inicio_trabajo)->format('d/m/Y')); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if($oferta->fecha_fin_trabajo): ?>
                        <div class="ficha-dato">
                            <span class="ficha-dato-label">Fin contrato</span>
                            <span class="ficha-dato-valor"><?php echo e(\Carbon\Carbon::parse($oferta->fecha_fin_trabajo)->format('d/m/Y')); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

        </div>

        <div class="lg:col-span-1">
            <div class="lg:sticky lg:top-24">
                <div class="ficha-compra">

                    <div class="text-center mb-6">
                        <p class="text-navy/50 text-sm uppercase tracking-widest font-semibold mb-1">Salario</p>
                        <p class="text-2xl font-black text-green-600"><?php echo e($oferta->salario_formateado); ?></p>
                    </div>

                    <div class="bg-purple-50 rounded-xl p-4 text-center mb-6">
                        <p class="text-3xl font-black text-navy"><?php echo e($oferta->vacantes); ?></p>
                        <p class="text-navy/50 text-sm">vacante<?php echo e($oferta->vacantes !== 1 ? 's' : ''); ?> disponible<?php echo e($oferta->vacantes !== 1 ? 's' : ''); ?></p>
                    </div>

                    <button class="btn-comprar w-full" onclick="abrirPostulacion(<?php echo e($oferta->id); ?>)">
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
</div>


<div id="modal-overlay" class="modal-overlay" onclick="cerrarAlClickarFuera(event)">

    
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
                <button onclick="mostrarFormulario()" class="group text-left flex flex-col p-5 border-2 border-navy/10 rounded-2xl hover:border-purple-400 hover:bg-purple-50/60 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-400">
                    <div class="w-12 h-12 bg-purple-100 group-hover:bg-purple-200 rounded-xl flex items-center justify-center mb-4 transition-colors">
                        <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <p class="font-bold text-navy mb-1">Rellenar formulario</p>
                    <p class="text-navy/50 text-sm leading-snug">Completa tu CV con tus datos, experiencia y formación</p>
                </button>
                <button onclick="mostrarSubirArchivo()" class="group text-left flex flex-col p-5 border-2 border-navy/10 rounded-2xl hover:border-green-400 hover:bg-green-50/60 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-400">
                    <div class="w-12 h-12 bg-green-100 group-hover:bg-green-200 rounded-xl flex items-center justify-center mb-4 transition-colors">
                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                    </div>
                    <p class="font-bold text-navy mb-1">Subir archivo</p>
                    <p class="text-navy/50 text-sm leading-snug">Sube tu CV en PDF o Word ya preparado</p>
                </button>
            </div>
        </div>
    </div>

    
    <div id="modal-formulario" class="modal-box max-w-2xl flex flex-col" style="display:none;max-height:92vh">
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
        <div class="modal-scrollable">
            <form id="form-cv" class="px-6 py-5 space-y-7" novalidate>
                <?php echo csrf_field(); ?>
                <input type="hidden" name="oferta_id" id="oferta-id-form" value="">

                <section>
                    <h3 class="flex items-center gap-2 font-bold text-navy text-sm mb-4"><span class="cv-section-num">1</span>Información Personal</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div><label class="cv-label">Nombre <span class="text-red-400 normal-case">*</span></label><input type="text" name="nombre" class="cv-input" placeholder="María" required></div>
                        <div><label class="cv-label">Apellidos <span class="text-red-400 normal-case">*</span></label><input type="text" name="apellidos" class="cv-input" placeholder="García López" required></div>
                        <div><label class="cv-label">Email <span class="text-red-400 normal-case">*</span></label><input type="email" name="email" class="cv-input" placeholder="maria@email.com" required></div>
                        <div><label class="cv-label">Teléfono <span class="text-red-400 normal-case">*</span></label><input type="tel" name="telefono" class="cv-input" placeholder="612 345 678" required></div>
                        <div><label class="cv-label">Ciudad <span class="text-red-400 normal-case">*</span></label><input type="text" name="ciudad" class="cv-input" placeholder="Barcelona" required></div>
                        <div><label class="cv-label">LinkedIn / Portfolio</label><input type="url" name="linkedin" class="cv-input" placeholder="linkedin.com/in/tu-perfil"></div>
                    </div>
                </section>

                <section>
                    <h3 class="flex items-center gap-2 font-bold text-navy text-sm mb-4"><span class="cv-section-num">2</span>Perfil Profesional</h3>
                    <textarea name="perfil_profesional" class="cv-input" rows="3" placeholder="Describe brevemente quién eres, tu objetivo profesional y qué te diferencia como candidato..." required></textarea>
                </section>

                <section>
                    <h3 class="flex items-center gap-2 font-bold text-navy text-sm mb-1"><span class="cv-section-num">3</span>Experiencia Laboral</h3>
                    <p class="text-navy/40 text-xs mb-3 ml-8">Añade tus experiencias más relevantes</p>
                    <div id="exp-container" class="space-y-3">
                        <div class="exp-item">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div><label class="cv-label">Empresa</label><input type="text" name="exp_empresa[]" class="cv-input" placeholder="Empresa S.L."></div>
                                <div><label class="cv-label">Cargo / Puesto</label><input type="text" name="exp_cargo[]" class="cv-input" placeholder="Técnico de Sonido"></div>
                                <div><label class="cv-label">Desde</label><input type="month" name="exp_desde[]" class="cv-input"></div>
                                <div><label class="cv-label">Hasta (vacío = actualidad)</label><input type="month" name="exp_hasta[]" class="cv-input"></div>
                                <div class="sm:col-span-2"><label class="cv-label">Descripción de tareas y logros</label><textarea name="exp_descripcion[]" class="cv-input" rows="2" placeholder="Principales responsabilidades y logros en el puesto..."></textarea></div>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="agregarExperiencia()" class="mt-3 text-purple-600 hover:text-purple-800 text-sm font-semibold flex items-center gap-1.5 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Añadir experiencia
                    </button>
                </section>

                <section>
                    <h3 class="flex items-center gap-2 font-bold text-navy text-sm mb-1"><span class="cv-section-num">4</span>Formación Académica</h3>
                    <p class="text-navy/40 text-xs mb-3 ml-8">Estudios, cursos y certificaciones</p>
                    <div id="edu-container" class="space-y-3">
                        <div class="edu-item">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="sm:col-span-2"><label class="cv-label">Institución</label><input type="text" name="edu_institucion[]" class="cv-input" placeholder="Universitat Politècnica de Catalunya"></div>
                                <div class="sm:col-span-2"><label class="cv-label">Titulación / Certificación</label><input type="text" name="edu_titulo[]" class="cv-input" placeholder="Grado en Ingeniería de Sonido e Imagen"></div>
                                <div><label class="cv-label">Año inicio</label><input type="number" name="edu_inicio[]" class="cv-input" placeholder="2020" min="1950" max="2035"></div>
                                <div><label class="cv-label">Año fin</label><input type="number" name="edu_fin[]" class="cv-input" placeholder="2024" min="1950" max="2035"></div>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="agregarFormacion()" class="mt-3 text-purple-600 hover:text-purple-800 text-sm font-semibold flex items-center gap-1.5 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Añadir titulación
                    </button>
                </section>

                <section>
                    <h3 class="flex items-center gap-2 font-bold text-navy text-sm mb-4"><span class="cv-section-num">5</span>Habilidades</h3>
                    <input type="text" name="habilidades" class="cv-input" placeholder="Pro Tools, Logic Pro, Ableton Live, gestión de equipos, trabajo bajo presión...">
                    <p class="text-navy/35 text-xs mt-1.5">Separa las habilidades con comas</p>
                </section>

                <section>
                    <h3 class="flex items-center gap-2 font-bold text-navy text-sm mb-4"><span class="cv-section-num">6</span>Idiomas</h3>
                    <input type="text" name="idiomas" class="cv-input" placeholder="Español (nativo), Catalán (nativo), Inglés (B2), Francés (A2)">
                </section>

                <section class="pb-2">
                    <h3 class="flex items-center gap-2 font-bold text-navy text-sm mb-4"><span class="cv-section-num">7</span>Carta de Presentación</h3>
                    <textarea name="carta_presentacion" class="cv-input" rows="4" placeholder="Explica por qué eres el candidato ideal para este puesto, qué te motiva de la empresa y qué puedes aportar al equipo..." required></textarea>
                </section>

            </form>
        </div>
        <div class="flex gap-3 px-6 py-4 border-t border-navy/10 flex-shrink-0">
            <button type="button" onclick="volverAEleccion()" class="px-5 py-2.5 border-2 border-navy/15 text-navy/70 font-semibold rounded-xl hover:bg-navy/5 transition-colors text-sm">Volver</button>
            <button type="submit" form="form-cv" id="btn-enviar-cv" class="btn-comprar flex-1 py-2.5 rounded-xl font-bold text-sm disabled:opacity-60 disabled:cursor-not-allowed">Enviar candidatura</button>
        </div>
    </div>

    
    <div id="modal-archivo" class="modal-box max-w-md" style="display:none">
        <div class="flex items-center justify-between px-6 py-4 border-b border-navy/10">
            <div class="flex items-center gap-3">
                <button onclick="volverAEleccion()" class="text-navy/35 hover:text-navy transition-colors" title="Volver">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <div>
                    <h2 class="text-xl font-black text-navy leading-tight">Subir CV</h2>
                    <p class="text-navy/40 text-xs" id="subtitulo-oferta-archivo">Adjunta tu currículum</p>
                </div>
            </div>
            <button onclick="cerrarModal()" class="text-navy/30 hover:text-navy transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="form-archivo" class="px-6 py-5 space-y-5">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="oferta_id" id="oferta-id-archivo" value="">
            <div id="dropzone" class="dropzone-area" onclick="document.getElementById('cv-file-input').click()" ondragover="dragOver(event)" ondragleave="dragLeave(event)" ondrop="dropFile(event)">
                <div class="w-14 h-14 bg-navy/5 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-navy/35" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                </div>
                <p class="text-navy font-semibold text-sm mb-1">Arrastra tu CV aquí</p>
                <p class="text-navy/40 text-sm">o haz clic para seleccionar un archivo</p>
                <p id="file-name-label" class="mt-3 text-sm text-purple-600 font-semibold hidden"></p>
                <input type="file" id="cv-file-input" name="cv_file" class="hidden" accept=".pdf,.doc,.docx" onchange="mostrarNombreArchivo(this)">
            </div>
            <p class="text-navy/35 text-xs text-center -mt-2">Formatos aceptados: PDF, DOC, DOCX &middot; Máximo 5 MB</p>
            <div>
                <label class="cv-label">Carta de presentación <span class="normal-case font-normal">(opcional)</span></label>
                <textarea name="carta_presentacion_archivo" class="cv-input" rows="3" placeholder="Añade un mensaje personalizado para el reclutador..."></textarea>
            </div>
        </form>
        <div class="flex gap-3 px-6 py-4 border-t border-navy/10">
            <button type="button" onclick="volverAEleccion()" class="px-5 py-2.5 border-2 border-navy/15 text-navy/70 font-semibold rounded-xl hover:bg-navy/5 transition-colors text-sm">Volver</button>
            <button type="submit" form="form-archivo" id="btn-enviar-archivo" class="btn-comprar flex-1 py-2.5 rounded-xl font-bold text-sm disabled:opacity-60 disabled:cursor-not-allowed">Enviar candidatura</button>
        </div>
    </div>

    
    <div id="modal-exito" class="modal-box max-w-md text-center" style="display:none">
        <div class="p-8">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h2 class="text-2xl font-black text-navy mb-2">¡Candidatura enviada!</h2>
            <p class="text-navy/55 text-sm leading-relaxed mb-6">Tu candidatura ha sido recibida correctamente. El equipo de selección revisará tu perfil y se pondrá en contacto contigo.</p>
            <button onclick="cerrarModal()" class="btn-comprar px-10 py-3 rounded-xl font-bold">Perfecto</button>
        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/trabajos-detalle.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\proyectos\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/trabajos/detalle.blade.php ENDPATH**/ ?>
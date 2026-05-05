<?php $__env->startSection('titulo', 'Crear Oferta — VIBEZ'); ?>

<?php $__env->startPush('estilos'); ?>
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
    .salario-prefix {
        display: flex;
        align-items: center;
    }
    .salario-prefix span {
        background: #ede9fe;
        border: 1.5px solid #ddd6fe;
        border-right: none;
        border-radius: 0.75rem 0 0 0.75rem;
        padding: 0.7rem 0.75rem;
        font-weight: 700;
        color: var(--morado);
        font-size: 0.9rem;
    }
    .salario-prefix .form-input {
        border-radius: 0 0.75rem 0.75rem 0;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('contenido'); ?>


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


<section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <?php if($errors->any()): ?>
        <div class="alert-errores">
            <strong>⚠ Revisa los siguientes campos:</strong>
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('empresa.ofertas.store')); ?>" method="POST" class="form-crear-evento">
        <?php echo csrf_field(); ?>

        
        <div class="form-section-title">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Información básica
        </div>

        <div class="form-grupo">
            <label class="form-label">Título del puesto <span class="form-required">*</span></label>
            <input type="text" name="titulo" class="form-input" maxlength="300"
                   value="<?php echo e(old('titulo')); ?>"
                   placeholder="Ej: Camarero/a para eventos de verano">
        </div>

        <div class="form-grupo">
            <label class="form-label">Descripción del puesto</label>
            <textarea name="descripcion" class="form-textarea" rows="4"
                      placeholder="Describe las tareas, el ambiente de trabajo, horarios..."><?php echo e(old('descripcion')); ?></textarea>
        </div>

        <div class="form-grupo">
            <label class="form-label">Requisitos</label>
            <textarea name="requisitos" class="form-textarea" rows="3"
                      placeholder="Experiencia mínima, formación requerida, habilidades..."><?php echo e(old('requisitos')); ?></textarea>
        </div>

        <div class="form-grupo-doble">
            <div>
                <label class="form-label">Categoría <span class="form-required">*</span></label>
                <select name="categoria_trabajo_id" class="form-select">
                    <option value="">Selecciona categoría</option>
                    <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat->id); ?>" <?php if(old('categoria_trabajo_id') == $cat->id): echo 'selected'; endif; ?>>
                            <?php echo e($cat->nombre); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="form-label">Vacantes</label>
                <input type="number" min="1" name="vacantes" class="form-input"
                       value="<?php echo e(old('vacantes')); ?>" placeholder="Ej: 3">
                <p class="form-hint">Opcional. Déjalo vacío si no hay límite.</p>
            </div>
        </div>

        <hr class="form-divider">

        
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
                   value="<?php echo e(old('ubicacion')); ?>" placeholder="Ej: Barcelona">
        </div>

        <div class="form-grupo-doble">
            <div>
                <label class="form-label">Fecha de inicio</label>
                <input type="date" name="fecha_inicio_trabajo" class="form-input"
                       value="<?php echo e(old('fecha_inicio_trabajo')); ?>">
                <p class="form-hint">Opcional. Cuándo empieza el trabajo.</p>
            </div>
            <div>
                <label class="form-label">Fecha de fin</label>
                <input type="date" name="fecha_fin_trabajo" class="form-input"
                       value="<?php echo e(old('fecha_fin_trabajo')); ?>">
                <p class="form-hint">Opcional. Déjalo vacío si es indefinido.</p>
            </div>
        </div>

        <hr class="form-divider">

        
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
                           value="<?php echo e(old('salario_min')); ?>" placeholder="1.200">
                </div>
                <p class="form-hint">Opcional. Déjalo vacío si es a negociar.</p>
            </div>
            <div>
                <label class="form-label">Salario máximo (€/mes)</label>
                <div class="salario-prefix">
                    <span>€</span>
                    <input type="number" min="0" step="50" name="salario_max" class="form-input"
                           value="<?php echo e(old('salario_max')); ?>" placeholder="1.800">
                </div>
            </div>
        </div>

        
        <div class="form-actions">
            <button type="submit" class="btn-guardar">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" width="18" height="18">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Publicar oferta
            </button>
            <a href="<?php echo e(route('empresa.home')); ?>" class="btn-cancelar">Cancelar</a>
        </div>
    </form>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views\empresa\ofertas\crear.blade.php ENDPATH**/ ?>
<?php $__env->startSection('titulo', 'Bolsa de Trabajo'); ?>

<?php $__env->startPush('estilos'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/trabajos-index.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('contenido'); ?>


<section class="relative overflow-hidden bg-ink" style="padding:80px 0 64px;">

    <div style="position:absolute;inset:0;background-image:radial-gradient(circle,rgba(139,120,204,0.15) 1.5px,transparent 1.5px);background-size:28px 28px;pointer-events:none;z-index:0"></div>
    <div style="position:absolute;width:500px;height:500px;border-radius:50%;background:radial-gradient(circle,rgba(139,120,204,0.22) 0%,transparent 60%);top:-160px;left:-80px;pointer-events:none;z-index:0;animation:orbA 16s ease-in-out infinite"></div>
    <div style="position:absolute;width:380px;height:380px;border-radius:50%;background:radial-gradient(circle,rgba(78,58,150,0.18) 0%,transparent 60%);bottom:-80px;right:-60px;pointer-events:none;z-index:0;animation:orbB 20s ease-in-out infinite"></div>

    <div class="max-w-7xl mx-auto px-6 sm:px-10 relative" style="z-index:1">

        <div class="grid md:grid-cols-12 gap-8 items-end">

            <div class="md:col-span-8 anim-in">
                <p class="font-mono text-xs uppercase tracking-widest text-paper/40 mb-4">
                    — Bolsa de trabajo · Escena musical y eventos
                </p>
                <h1 class="font-display font-black uppercase text-paper tracking-tightest leading-[0.88]"
                    style="font-size:clamp(3rem,8vw,7.5rem)">
                    Trabaja en<br>
                    <em class="text-lilac not-italic">la escena.</em>
                </h1>
            </div>

            <div class="md:col-span-4 anim-in-2">
                <p class="font-sans text-paper/60 text-sm leading-relaxed mb-8">
                    Fotógrafos, técnicos de sonido, relaciones públicas —
                    encuentra tu lugar en los mejores eventos del país.
                </p>
                <div class="flex gap-6">
                    <div class="border-t border-paper/20 pt-4">
                        <p class="font-display font-black text-2xl text-paper"><?php echo e($ofertas->count()); ?></p>
                        <p class="font-mono text-xs uppercase tracking-widest text-paper/35 mt-1">Ofertas</p>
                    </div>
                    <div class="border-t border-paper/20 pt-4">
                        <p class="font-display font-black text-2xl text-paper"><?php echo e($ciudades->count()); ?></p>
                        <p class="font-mono text-xs uppercase tracking-widest text-paper/35 mt-1">Ciudades</p>
                    </div>
                    <div class="border-t border-paper/20 pt-4">
                        <p class="font-display font-black text-2xl text-lilac"><?php echo e($categoriasTrabajo->count()); ?></p>
                        <p class="font-mono text-xs uppercase tracking-widest text-paper/35 mt-1">Categorías</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


<section class="barra-filtros sticky top-14 z-40">

    <div id="overlay-dropdowns"
         style="display:none;position:fixed;inset:0;z-index:200;"
         onclick="cerrarTodosDropdowns()"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex flex-wrap items-end gap-3">

        <p class="text-sm font-semibold mr-auto self-center" style="color:rgba(15,23,42,0.5)">
            <span id="contador-resultados"><?php echo e($ofertas->count()); ?></span>
            <span style="color:var(--morado)"> oferta<?php echo e($ofertas->count() !== 1 ? 's' : ''); ?></span>
        </p>

        <div class="filtro-grupo" style="position:relative;z-index:250;">
            <label class="filtro-label">Categoría</label>
            <div class="custom-select-wrapper" id="wrapper-categoria" onclick="toggleDropdown('categoria')">
                <span id="categoria-display" class="custom-select-display">Todas las categorías</span>
                <svg class="custom-select-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M6 9l6 6 6-6"/>
                </svg>
                <div id="categoria-dropdown" class="custom-select-dropdown" style="display:none">
                    <div class="custom-select-option seleccionado" onclick="seleccionarFiltro('categoria','','Todas las categorías',event)">Todas las categorías</div>
                    <?php $__currentLoopData = $categoriasTrabajo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="custom-select-option" onclick="seleccionarFiltro('categoria','<?php echo e($cat->id); ?>','<?php echo e($cat->nombre); ?>',event)"><?php echo e($cat->nombre); ?></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <input type="hidden" id="filtro-categoria" value="">
        </div>

        <div class="filtro-grupo" style="position:relative;z-index:240;">
            <label class="filtro-label">Ciudad</label>
            <div class="custom-select-wrapper" id="wrapper-ciudad" onclick="toggleDropdown('ciudad')">
                <span id="ciudad-display" class="custom-select-display">Todas las ciudades</span>
                <svg class="custom-select-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M6 9l6 6 6-6"/>
                </svg>
                <div id="ciudad-dropdown" class="custom-select-dropdown" style="display:none">
                    <div class="custom-select-option seleccionado" onclick="seleccionarFiltro('ciudad','','Todas las ciudades',event)">Todas las ciudades</div>
                    <?php $__currentLoopData = $ciudades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ciudad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="custom-select-option" onclick="seleccionarFiltro('ciudad','<?php echo e($ciudad); ?>','<?php echo e($ciudad); ?>',event)"><?php echo e($ciudad); ?></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <input type="hidden" id="filtro-ciudad" value="">
        </div>

        <div class="filtro-grupo">
            <span class="filtro-label" style="visibility:hidden">–</span>
            <button class="btn-limpiar" onclick="limpiarFiltros()">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
                Limpiar
            </button>
        </div>

    </div>
</section>


<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div id="cargando" class="hidden flex justify-center py-16">
        <div class="spinner"></div>
    </div>

    <div id="sin-resultados" class="hidden text-center py-20">
        <p class="font-display font-black text-xl uppercase tracking-tightest text-ink mb-2">Sin ofertas</p>
        <p class="font-mono text-xs uppercase tracking-widest text-muted mb-6">Prueba con otra categoría o ciudad</p>
        <button class="btn-morado" onclick="limpiarFiltros()">Ver todas las ofertas</button>
    </div>

    <div id="grid-ofertas" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <?php $__currentLoopData = $ofertas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $oferta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <article class="card-trabajo-grande" onclick="irAOferta(<?php echo e($oferta->id); ?>)">

                <div class="ctg-header">
                    <div class="ctg-icono">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="ctg-badge"><?php echo e($oferta->categoria?->nombre ?? 'General'); ?></span>
                        <p class="ctg-empresa"><?php echo e($oferta->organizador?->empresa?->nombre_empresa ?? 'Empresa'); ?></p>
                    </div>
                </div>

                <h3 class="ctg-titulo"><?php echo e($oferta->titulo); ?></h3>

                <?php if($oferta->descripcion): ?>
                    <p class="ctg-desc"><?php echo e(Str::limit($oferta->descripcion, 110)); ?></p>
                <?php endif; ?>

                <div class="ctg-datos">
                    <?php if($oferta->ubicacion): ?>
                        <span class="ctg-dato">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <?php echo e($oferta->ubicacion); ?>

                        </span>
                    <?php endif; ?>
                    <span class="ctg-dato">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <?php echo e($oferta->vacantes); ?> vacante<?php echo e($oferta->vacantes !== 1 ? 's' : ''); ?>

                    </span>
                    <?php if($oferta->fecha_inicio_trabajo): ?>
                        <span class="ctg-dato">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <?php echo e(\Carbon\Carbon::parse($oferta->fecha_inicio_trabajo)->locale('es')->isoFormat('D MMM YYYY')); ?>

                        </span>
                    <?php endif; ?>
                </div>

                <div class="ctg-footer">
                    <div>
                        <p class="ctg-salario-label">Salario</p>
                        <p class="ctg-salario"><?php echo e($oferta->salario_formateado); ?></p>
                    </div>
                    <button class="ctg-btn" onclick="irAOferta(<?php echo e($oferta->id); ?>)">
                        Ver oferta
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>

            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>

</section>


<section class="bg-ink relative overflow-hidden" style="padding:80px 0;margin-top:20px;">
    <div style="position:absolute;inset:0;background-image:radial-gradient(circle,rgba(139,120,204,0.12) 1.5px,transparent 1.5px);background-size:28px 28px;pointer-events:none;"></div>
    <div class="max-w-3xl mx-auto px-6 text-center relative" style="z-index:1">
        <p class="font-mono text-xs uppercase tracking-widest text-paper/35 mb-4">— Para organizadores</p>
        <h2 class="font-display font-black uppercase text-paper tracking-tightest leading-[0.9]"
            style="font-size:clamp(2rem,5vw,4rem)">
            ¿Organizas<br>eventos?
        </h2>
        <p class="font-sans text-paper/50 text-base mt-6 mb-8 max-w-md mx-auto leading-relaxed">
            Publica tus ofertas de trabajo y encuentra al equipo perfecto para tus festivales, conciertos y eventos.
        </p>
        <a href="<?php echo e(route('home')); ?>"
           class="btn-ink font-mono text-xs uppercase tracking-widest px-8 py-4 inline-block">
            <span>Explorar la plataforma →</span>
        </a>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/trabajos-index.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/trabajos/index.blade.php ENDPATH**/ ?>
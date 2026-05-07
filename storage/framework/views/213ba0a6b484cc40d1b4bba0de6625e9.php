<?php $__env->startSection('titulo', 'Explorar Eventos'); ?>

<?php $__env->startPush('estilos'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/home.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('contenido'); ?>


<section class="relative overflow-hidden bg-ink" style="padding:80px 0 64px;">

    
    <div style="position:absolute;inset:0;background-image:radial-gradient(circle,rgba(139,120,204,0.15) 1.5px,transparent 1.5px);background-size:28px 28px;pointer-events:none;z-index:0"></div>

    
    <div style="position:absolute;width:560px;height:560px;border-radius:50%;background:radial-gradient(circle,rgba(139,120,204,0.25) 0%,transparent 60%);top:-180px;right:-120px;pointer-events:none;z-index:0;animation:orbA 16s ease-in-out infinite"></div>
    <div style="position:absolute;width:380px;height:380px;border-radius:50%;background:radial-gradient(circle,rgba(78,58,150,0.2) 0%,transparent 60%);bottom:-100px;left:-80px;pointer-events:none;z-index:0;animation:orbB 20s ease-in-out infinite"></div>

    <div class="max-w-7xl mx-auto px-6 sm:px-10 relative" style="z-index:1">

        
        <div class="grid md:grid-cols-12 gap-8 items-end">

            
            <div class="md:col-span-7 anim-in">
                <p class="font-mono text-xs uppercase tracking-widest text-paper/40 mb-4">
                    — La escena joven · <?php echo e(\Carbon\Carbon::now()->locale('es')->isoFormat('MMMM YYYY')); ?>

                </p>
                <h1 class="font-display font-black uppercase text-paper tracking-tightest leading-[0.88]"
                    style="font-size:clamp(3.5rem,10vw,9rem)">
                    Descubre<br>
                    tu próximo<br>
                    <em class="text-lilac not-italic">evento.</em>
                </h1>
            </div>

            
            <div class="md:col-span-5 anim-in-2">
                <p class="font-sans text-paper/60 text-base leading-relaxed mb-8 max-w-sm">
                    Conciertos, festivales, exposiciones y trabajo —
                    todo lo que vive tu escena en un solo lugar.
                </p>
                <div class="flex gap-8">
                    <div class="border-t border-paper/20 pt-4">
                        <p class="font-display font-black text-3xl text-paper"><?php echo e($eventos->count()); ?></p>
                        <p class="font-mono text-xs uppercase tracking-widest text-paper/35 mt-1">Eventos</p>
                    </div>
                    <div class="border-t border-paper/20 pt-4">
                        <p class="font-display font-black text-3xl text-lilac">Live</p>
                        <p class="font-mono text-xs uppercase tracking-widest text-paper/35 mt-1">Ahora</p>
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

        <p class="text-sm font-semibold mr-auto self-center"
           style="color:rgba(15,23,42,0.5)">
            <span id="contador-resultados"><?php echo e($eventos->count()); ?></span>
            <span style="color:var(--morado)"> resultados</span>
        </p>

        <div class="filtro-grupo" style="position:relative;z-index:250;">
            <label class="filtro-label">Categoría</label>
            <div class="custom-select-wrapper" id="wrapper-categoria" onclick="toggleDropdown('categoria')">
                <span id="categoria-display" class="custom-select-display">Todas</span>
                <svg class="custom-select-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M6 9l6 6 6-6"/>
                </svg>
                <div id="categoria-dropdown" class="custom-select-dropdown" style="display:none">
                    <div class="custom-select-option seleccionado" onclick="seleccionarFiltro('categoria','','Todas',event)">Todas</div>
                    <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="custom-select-option" onclick="seleccionarFiltro('categoria','<?php echo e($categoria->id); ?>','<?php echo e($categoria->nombre); ?>',event)">
                            <?php echo e($categoria->nombre); ?>

                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <input type="hidden" id="filtro-categoria" value="">
        </div>

        <div class="filtro-grupo" style="position:relative;z-index:240;">
            <label class="filtro-label">Ubicación</label>
            <div class="custom-select-wrapper" id="wrapper-ubicacion" onclick="toggleDropdown('ubicacion')">
                <span id="ubicacion-display" class="custom-select-display">Todas las ciudades</span>
                <svg class="custom-select-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M6 9l6 6 6-6"/>
                </svg>
                <div id="ubicacion-dropdown" class="custom-select-dropdown" style="display:none">
                    <div class="custom-select-option seleccionado" onclick="seleccionarFiltro('ubicacion','','Todas las ciudades',event)">Todas las ciudades</div>
                    <?php $__currentLoopData = $ubicaciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ubicacion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="custom-select-option" onclick="seleccionarFiltro('ubicacion','<?php echo e($ubicacion); ?>','<?php echo e($ubicacion); ?>',event)">
                            <?php echo e($ubicacion); ?>

                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <input type="hidden" id="filtro-ubicacion" value="">
        </div>

        <?php if(auth()->guard()->check()): ?>
        <div class="filtro-grupo">
            <label class="filtro-label">Favoritos</label>
            <button type="button" id="btn-solo-favoritos" class="btn-favoritos-filtro" onclick="toggleSoloFavoritos()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
                <span id="texto-solo-favoritos">Solo favoritos</span>
            </button>
            <input type="hidden" id="filtro-favoritos" value="0">
        </div>
        <?php endif; ?>

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
        <span class="flex justify-center mb-3" aria-hidden="true">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:#8B78CC;opacity:0.5;">
                <circle cx="10.5" cy="10.5" r="6.75"/>
                <path d="M15.75 15.75L21 21"/>
            </svg>
        </span>
        <p class="font-display font-black text-xl uppercase tracking-tightest text-ink">Sin resultados</p>
        <p class="font-mono text-xs uppercase tracking-widest text-muted mt-2 mb-6">Prueba con otra categoría o ciudad</p>
        <button class="btn-morado" onclick="limpiarFiltros()">Ver todo</button>
    </div>

    <div id="seccion-eventos">
        <div class="seccion-vibez-titulo">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M16.5 6v.75a3.75 3.75 0 0 1-7.5 0V6m-4.5 3h16.5m-16.5 0a2.25 2.25 0 0 0-2.25 2.25v8.25A2.25 2.25 0 0 0 4.5 21.75h15a2.25 2.25 0 0 0 2.25-2.25V11.25A2.25 2.25 0 0 0 19.5 9H4.5z"/>
            </svg>
            Eventos
        </div>
        <p class="seccion-vibez-sub">
            <?php echo e($eventos->count()); ?> evento<?php echo e($eventos->count() !== 1 ? 's' : ''); ?> disponible<?php echo e($eventos->count() !== 1 ? 's' : ''); ?>

        </p>

        <div id="grid-eventos"
             class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            <?php $__currentLoopData = $eventos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <article class="card-evento" onclick="irADetalle('evento', <?php echo e($evento->id); ?>)">

                    <div class="card-imagen-wrap">
                        <button type="button"
                                class="btn-favorito-card <?php echo e(in_array((int) $evento->id, $favoritosIds ?? [], true) ? 'activo' : ''); ?>"
                                data-evento-id="<?php echo e($evento->id); ?>"
                                data-favorito="<?php echo e(in_array((int) $evento->id, $favoritosIds ?? [], true) ? '1' : '0'); ?>"
                                aria-label="Marcar favorito"
                                aria-pressed="<?php echo e(in_array((int) $evento->id, $favoritosIds ?? [], true) ? 'true' : 'false'); ?>"
                                onclick="toggleFavorito(event, this)">
                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                        </button>
                        <img src="<?php echo e($evento->url_portada); ?>"
                             alt="<?php echo e($evento->titulo); ?>"
                             class="card-imagen"
                             onerror="this.src='https://picsum.photos/seed/fallback-<?php echo e($evento->id); ?>/600/400'">
                        <span class="badge-categoria" data-cat="<?php echo e($evento->categoria?->nombre ?? 'Evento'); ?>">
                            <?php echo e($evento->categoria?->nombre ?? 'Evento'); ?>

                        </span>
                        <span class="badge-precio <?php echo e($evento->es_gratuito ? 'badge-gratis' : ''); ?>">
                            <?php echo e($evento->precio_formateado); ?>

                        </span>
                    </div>

                    <div class="card-cuerpo">
                        <h3 class="card-titulo"><?php echo e($evento->titulo); ?></h3>
                        <p class="card-meta">
                            <svg class="icono-meta" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <?php echo e(\Carbon\Carbon::parse($evento->fecha_inicio)->locale('es')->isoFormat('D MMM YYYY')); ?>

                        </p>
                        <?php if($evento->ubicacion_nombre): ?>
                            <p class="card-meta">
                                <svg class="icono-meta" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <?php echo e($evento->ubicacion_nombre); ?>

                            </p>
                        <?php endif; ?>
                        <?php if($evento->organizador?->empresa): ?>
                            <p class="card-organizador"><?php echo e($evento->organizador->empresa->nombre_empresa); ?></p>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>
    </div>

    <div id="grid-resultados" class="hidden">
        <div id="grid-resultados-inner"
             class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        </div>
    </div>

</section>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
window.vibezFavoritosConfig = {
    userAuthenticated: <?php echo json_encode(Auth::check(), 15, 512) ?>,
    loginUrl: <?php echo json_encode(route('login'), 15, 512) ?>
};
window.vibezHomeConfig = {
    totalEventos: <?php echo e($eventos->count()); ?>

};
</script>
<script src="<?php echo e(asset('js/favoritos.js')); ?>"></script>
<script src="<?php echo e(asset('js/home.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/home.blade.php ENDPATH**/ ?>
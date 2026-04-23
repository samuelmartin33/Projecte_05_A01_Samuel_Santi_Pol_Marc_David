<?php $__env->startSection('titulo', $oferta->titulo); ?>

<?php $__env->startSection('contenido'); ?>


<div class="hero-trabajo">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

        
        <a href="<?php echo e(route('home')); ?>?categoria=trabajo" class="btn-volver">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver a Bolsa de Trabajo
        </a>

        
        <span class="badge-trabajo inline-block mt-6">Oferta de trabajo</span>

        
        <h1 class="text-3xl sm:text-5xl font-black text-white mt-3 leading-tight max-w-3xl">
            <?php echo e($oferta->titulo); ?>

        </h1>

        
        <div class="flex flex-wrap gap-6 mt-6">

            
            <?php if($oferta->organizador?->empresa): ?>
                <div class="dato-hero">
                    <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="font-semibold"><?php echo e($oferta->organizador->empresa->nombre_empresa); ?></span>
                </div>
            <?php endif; ?>

            
            <?php if($oferta->ubicacion): ?>
                <div class="dato-hero">
                    <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    </svg>
                    <span><?php echo e($oferta->ubicacion); ?></span>
                </div>
            <?php endif; ?>

            
            <div class="dato-hero">
                <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-bold text-lg text-green-300"><?php echo e($oferta->salario_formateado); ?></span>
            </div>

        </div>

    </div>
</div>


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
                            <span class="ficha-dato-valor">
                                <?php echo e(\Carbon\Carbon::parse($oferta->fecha_inicio_trabajo)->format('d/m/Y')); ?>

                            </span>
                        </div>
                    <?php endif; ?>

                    <?php if($oferta->fecha_fin_trabajo): ?>
                        <div class="ficha-dato">
                            <span class="ficha-dato-label">Fin contrato</span>
                            <span class="ficha-dato-valor">
                                <?php echo e(\Carbon\Carbon::parse($oferta->fecha_fin_trabajo)->format('d/m/Y')); ?>

                            </span>
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

                    
                    <button class="btn-comprar w-full"
                            onclick="abrirPostulacion(<?php echo e($oferta->id); ?>)">
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

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
/**
 * Abre el flujo de postulación a la oferta de trabajo.
 * @param {number} ofertaId - ID de la oferta en la BD
 */
function abrirPostulacion(ofertaId) {
    // TODO: Integrar con el sistema de candidaturas de VIBEZ
    alert('Próximamente: formulario de candidatura para la oferta #' + ofertaId);
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/trabajos/detalle.blade.php ENDPATH**/ ?>
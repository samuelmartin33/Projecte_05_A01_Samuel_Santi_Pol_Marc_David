<?php $__env->startSection('titulo', 'Mis entradas — VIBEZ'); ?>


<?php $__env->startPush('estilos'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/entradas-mis-entradas.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('contenido'); ?>


<section class="perfil-hero">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <a href="<?php echo e(route('home')); ?>" class="btn-volver" style="display:inline-flex;margin-bottom:1.5rem">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver
        </a>

        <h1 class="text-2xl sm:text-3xl font-black text-white">
            Mis entradas
        </h1>
        <p class="text-white/50 text-sm mt-1">
            <?php echo e($pedidos->sum(fn($p) => $p->entradas->count())); ?>

            <?php echo e($pedidos->sum(fn($p) => $p->entradas->count()) === 1 ? 'entrada' : 'entradas'); ?> en total
        </p>

    </div>
</section>


<div class="mis-entradas-page-wrap">
<div class="max-w-3xl mx-auto px-4 py-8">

    <?php if($pedidos->isEmpty()): ?>

        
        <div class="seccion-detalle mis-entradas-vacio">
            <div class="mis-entradas-vacio-icono">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
            </div>
            <p class="mis-entradas-vacio-titulo">
                Aún no tienes entradas
            </p>
            <p class="mis-entradas-vacio-texto">
                Explora los eventos disponibles y compra tu primera entrada.
            </p>
            <a href="<?php echo e(route('home')); ?>" class="btn-comprar mis-entradas-vacio-btn">
                Explorar eventos
            </a>
        </div>

    <?php else: ?>

        
        <?php $__currentLoopData = $pedidos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pedido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            
            <?php $evento = $pedido->entradas->first()?->evento; ?>

            
            <div class="seccion-detalle mis-entradas-pedido">

                
                <div class="mis-entradas-pedido-header">
                    <div class="mis-entradas-pedido-info">
                        <p class="mis-entradas-pedido-titulo">
                            <?php echo e($evento?->titulo ?? 'Evento eliminado'); ?>

                        </p>
                        <?php if($evento): ?>
                            <p class="mis-entradas-pedido-fecha">
                                <?php echo e(\Carbon\Carbon::parse($evento->fecha_inicio)->locale('es')->isoFormat('D MMM YYYY')); ?>

                                <?php if($evento->ubicacion_nombre): ?>
                                    &nbsp;·&nbsp;<?php echo e($evento->ubicacion_nombre); ?>

                                <?php endif; ?>
                            </p>
                        <?php endif; ?>
                        <p class="mis-entradas-pedido-meta">
                            Pedido #<?php echo e($pedido->id); ?>

                            · <?php echo e(\Carbon\Carbon::parse($pedido->fecha_creacion)->locale('es')->isoFormat('D MMM YYYY, HH:mm')); ?>

                        </p>
                    </div>
                    <div class="mis-entradas-pedido-precio-col">
                        
                        <span class="mis-entradas-badge-entradas">
                            <?php echo e($pedido->entradas->count()); ?>

                            <?php echo e($pedido->entradas->count() === 1 ? 'entrada' : 'entradas'); ?>

                        </span>
                        <p class="mis-entradas-precio-total">
                            <?php if($pedido->total_final == 0): ?>
                                <span class="precio-gratis">Gratis</span>
                            <?php else: ?>
                                <span class="text-gradient"><?php echo e(number_format($pedido->total_final, 2)); ?> €</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                
                <div class="mis-entradas-entradas-lista">
                    <?php $__currentLoopData = $pedido->entradas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $entrada): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        
                        <div class="mis-entradas-talonario-sep">
                            <div class="mis-entradas-talonario-circulo-izq"></div>
                            <div class="mis-entradas-talonario-circulo-der"></div>
                        </div>

                        <div class="mis-entradas-entrada-fila">
                            
                            <div class="mis-entradas-entrada-header">
                                <div class="mis-entradas-entrada-info">
                                    <p class="mis-entradas-entrada-num">
                                        Entrada #<?php echo e($i + 1); ?>

                                    </p>
                                    
                                    <p class="mis-entradas-entrada-codigo"><?php echo e($entrada->codigo_qr); ?></p>
                                </div>
                                
                                <button id="btn-<?php echo e($entrada->id); ?>"
                                        onclick="toggleQr('qr-<?php echo e($entrada->id); ?>','btn-<?php echo e($entrada->id); ?>')"
                                        class="btn-secundario mis-entradas-btn-qr">
                                    Ver QR
                                </button>
                            </div>

                            
                            <div id="qr-<?php echo e($entrada->id); ?>" style="display:none"
                                 class="mis-entradas-qr-panel">
                                <div class="mis-entradas-qr-marco">
                                    
                                    <div id="qr-canvas-<?php echo e($entrada->id); ?>"
                                         data-codigo="<?php echo e($entrada->codigo_qr); ?>"
                                         class="mis-entradas-qr-canvas"></div>
                                </div>
                                <p class="mis-entradas-qr-texto">
                                    Presenta este QR en la entrada del evento
                                </p>
                            </div>
                        </div>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <div style="padding-bottom:4px"></div>
                </div>

            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php endif; ?>

</div>
</div>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="<?php echo e(asset('js/entradas-mis-entradas.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/entradas/mis-entradas.blade.php ENDPATH**/ ?>
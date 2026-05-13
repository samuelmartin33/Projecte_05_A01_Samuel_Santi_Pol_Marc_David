<?php $__env->startSection('titulo', 'Confirmación de compra — VIBEZ'); ?>


<?php $__env->startPush('estilos'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/entradas-confirmacion.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<?php echo $__env->make('partials.home.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>



<div class="confirmacion-hero">
    
    <div class="confirmacion-hero-icono">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
    </div>
    <h1 class="confirmacion-hero-titulo">
        ¡Compra realizada!
    </h1>
    <p class="confirmacion-hero-subtitulo">
        Pedido #<?php echo e($pedido->id); ?> · <?php echo e(\Carbon\Carbon::parse($pedido->fecha_creacion)->locale('es')->isoFormat('D [de] MMMM [de] YYYY, HH:mm')); ?>

    </p>
</div>


<div class="confirmacion-page-wrap">
<div class="max-w-2xl mx-auto px-4 py-8">

    
    <div class="ficha-evento" style="margin-bottom:1.5rem">
        <h2 class="seccion-titulo" style="margin-bottom:1rem">Resumen del pedido</h2>

        
        <?php $primerEvento = $pedido->entradas->first()?->evento; ?>
        <?php if($primerEvento): ?>
            
            <div class="confirmacion-evento-fila">
                <div class="confirmacion-evento-icono">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <div>
                    <p class="confirmacion-evento-titulo"><?php echo e($primerEvento->titulo); ?></p>
                    <p class="confirmacion-evento-fecha">
                        <?php echo e(\Carbon\Carbon::parse($primerEvento->fecha_inicio)->locale('es')->isoFormat('D [de] MMMM [de] YYYY')); ?>

                    </p>
                    <?php if($primerEvento->ubicacion_nombre): ?>
                        <p class="confirmacion-evento-lugar">📍 <?php echo e($primerEvento->ubicacion_nombre); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        
        <div class="confirmacion-resumen-footer">
            <div>
                <p class="confirmacion-entrada-count"><?php echo e($pedido->entradas->count()); ?> entrada(s)</p>
            </div>
            <div>
                <p class="confirmacion-total-label">Total</p>
                <p class="text-gradient" style="margin:0;font-weight:900;font-size:1.5rem">
                    <?php if($pedido->total_final == 0): ?>
                        Gratis
                    <?php else: ?>
                        <?php echo e(number_format($pedido->total_final, 2)); ?> €
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>

    
    <h2 class="seccion-titulo" style="margin-bottom:1rem">
        Tus entradas (<?php echo e($pedido->entradas->count()); ?>)
    </h2>

    <?php $__currentLoopData = $pedido->entradas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $entrada): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="ficha-evento" style="margin-bottom:1.25rem;overflow:hidden">

            
            <div class="confirmacion-entrada-cabecera">
                <div>
                    <p class="confirmacion-entrada-num">
                        Entrada #<?php echo e($i + 1); ?>

                    </p>
                    
                    <p class="confirmacion-entrada-codigo"><?php echo e($entrada->codigo_qr); ?></p>
                </div>
                <div class="confirmacion-entrada-precio-col">
                    <p class="text-gradient" style="margin:0;font-weight:800;font-size:1.1rem">
                        <?php if($entrada->precio_pagado == 0): ?> Gratis
                        <?php else: ?> <?php echo e(number_format($entrada->precio_pagado, 2)); ?> €
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            
            <div class="confirmacion-entrada-divisor"></div>

            
            <div class="confirmacion-qr-centrado">
                <div class="confirmacion-qr-marco">
                    
                    <div id="qr-<?php echo e($i); ?>" data-codigo="<?php echo e($entrada->codigo_qr); ?>"
                         class="confirmacion-qr-canvas"></div>
                </div>
                <p class="confirmacion-qr-texto">
                    Presenta este QR en la entrada del evento
                </p>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    <div class="confirmacion-acciones">
        <a href="<?php echo e(route('home')); ?>" class="btn-comprar">
            Explorar más eventos
        </a>
        <a href="<?php echo e(route('perfil')); ?>" style="color:#7c3aed;font-size:0.875rem;text-decoration:none">
            Ver mi perfil
        </a>
    </div>

</div>
</div>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="<?php echo e(asset('js/entradas-confirmacion.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\LARAVEL\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/entradas/confirmacion.blade.php ENDPATH**/ ?>
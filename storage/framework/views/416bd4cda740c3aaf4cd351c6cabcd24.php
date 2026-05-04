<?php $__env->startSection('titulo', 'Confirmación de compra — VIBEZ'); ?>

<?php $__env->startSection('contenido'); ?>


<div style="background:linear-gradient(135deg,#059669,#10b981);padding:3rem 1rem;text-align:center">
    <div style="width:64px;height:64px;background:rgba(255,255,255,0.2);border-radius:50%;
                display:flex;align-items:center;justify-content:center;margin:0 auto 1rem">
        <svg style="width:36px;height:36px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
    </div>
    <h1 style="color:#fff;font-weight:900;font-size:1.75rem;margin:0 0 0.5rem">
        ¡Compra realizada!
    </h1>
    <p style="color:rgba(255,255,255,0.85);font-size:1rem;margin:0">
        Pedido #<?php echo e($pedido->id); ?> · <?php echo e(\Carbon\Carbon::parse($pedido->fecha_creacion)->locale('es')->isoFormat('D [de] MMMM [de] YYYY, HH:mm')); ?>

    </p>
</div>


<div class="max-w-2xl mx-auto px-4 py-8">

    
    <div class="ficha-evento" style="margin-bottom:1.5rem">
        <h2 class="seccion-titulo" style="margin-bottom:1rem">Resumen del pedido</h2>

        <?php $primerEvento = $pedido->entradas->first()?->evento; ?>
        <?php if($primerEvento): ?>
            <div style="display:flex;gap:12px;align-items:flex-start;margin-bottom:1rem">
                <div style="width:48px;height:48px;background:linear-gradient(135deg,#7c3aed,#a855f7);
                            border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg style="width:24px;height:24px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <div>
                    <p style="font-weight:700;color:var(--navy,#0f172a);margin:0"><?php echo e($primerEvento->titulo); ?></p>
                    <p style="color:#7c3aed;font-size:0.875rem;margin:2px 0 0">
                        <?php echo e(\Carbon\Carbon::parse($primerEvento->fecha_inicio)->locale('es')->isoFormat('D [de] MMMM [de] YYYY')); ?>

                    </p>
                    <?php if($primerEvento->ubicacion_nombre): ?>
                        <p style="color:#64748b;font-size:0.8rem;margin:2px 0 0">📍 <?php echo e($primerEvento->ubicacion_nombre); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <div style="display:flex;justify-content:space-between;align-items:center;
                    border-top:1px solid #ede9fe;padding-top:1rem">
            <div>
                <p style="margin:0;color:#64748b;font-size:0.875rem"><?php echo e($pedido->entradas->count()); ?> entrada(s)</p>
            </div>
            <div style="text-align:right">
                <p style="margin:0;font-size:0.75rem;color:#94a3b8">Total</p>
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
            
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
                <div>
                    <p style="font-weight:800;color:var(--navy,#0f172a);margin:0;font-size:1rem">
                        Entrada #<?php echo e($i + 1); ?>

                    </p>
                    <p style="font-family:monospace;font-size:0.7rem;color:#94a3b8;margin:4px 0 0;word-break:break-all">
                        <?php echo e($entrada->codigo_qr); ?>

                    </p>
                </div>
                <div style="text-align:right;flex-shrink:0;margin-left:12px">
                    <p class="text-gradient" style="margin:0;font-weight:800;font-size:1.1rem">
                        <?php if($entrada->precio_pagado == 0): ?> Gratis
                        <?php else: ?> <?php echo e(number_format($entrada->precio_pagado, 2)); ?> €
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            
            <div style="border-top:2px dashed #ede9fe;margin:0 -2rem 1.25rem"></div>

            
            <div style="text-align:center">
                <div style="display:inline-block;padding:12px;background:#fff;
                            border:3px solid #ede9fe;border-radius:16px;
                            box-shadow:0 4px 20px rgba(124,58,237,0.08)">
                    <div id="qr-<?php echo e($i); ?>" data-codigo="<?php echo e($entrada->codigo_qr); ?>"
                         style="width:220px;height:220px;margin:0 auto"></div>
                </div>
                <p style="font-size:0.75rem;color:#94a3b8;margin:10px 0 0">
                    Presenta este QR en la entrada del evento
                </p>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    <div style="text-align:center;margin-top:2rem;display:flex;flex-direction:column;gap:12px;align-items:center">
        <a href="<?php echo e(route('home')); ?>" class="btn-comprar" style="display:inline-block;padding:0.85rem 2.5rem">
            Explorar más eventos
        </a>
        <a href="<?php echo e(route('perfil')); ?>" style="color:#7c3aed;font-size:0.875rem;text-decoration:none">
            Ver mi perfil
        </a>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
document.querySelectorAll('[data-codigo]').forEach(function(el) {
    new QRCode(el, {
        text: el.dataset.codigo,
        width: parseInt(el.style.width) || 220,
        height: parseInt(el.style.height) || 220,
        colorDark: '#000000',
        colorLight: '#ffffff',
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/entradas/confirmacion.blade.php ENDPATH**/ ?>
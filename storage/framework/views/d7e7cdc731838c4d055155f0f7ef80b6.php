<?php $__env->startSection('titulo', 'Mis entradas — VIBEZ'); ?>

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


<div class="max-w-3xl mx-auto px-4 py-8">

    <?php if($pedidos->isEmpty()): ?>

        <div class="seccion-detalle" style="text-align:center;padding:3rem 1.5rem">
            <div style="width:64px;height:64px;background:#f0ecff;border-radius:50%;
                        display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;
                        border:2px solid #ddd6fe">
                <svg style="width:32px;height:32px;color:#7c3aed" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
            </div>
            <p style="font-weight:800;color:var(--navy);margin:0 0 0.5rem;font-size:1.1rem">
                Aún no tienes entradas
            </p>
            <p style="color:#64748b;margin:0 0 1.75rem;font-size:0.9rem">
                Explora los eventos disponibles y compra tu primera entrada.
            </p>
            <a href="<?php echo e(route('home')); ?>" class="btn-comprar"
               style="display:inline-block;padding:0.8rem 2rem;width:auto">
                Explorar eventos
            </a>
        </div>

    <?php else: ?>

        <?php $__currentLoopData = $pedidos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pedido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $evento = $pedido->entradas->first()?->evento; ?>

            <div class="seccion-detalle" style="margin-bottom:1.5rem;padding:0;overflow:hidden">

                
                <div style="padding:18px 28px;
                            background:linear-gradient(135deg,#05000f,#1e1035);
                            display:flex;justify-content:space-between;align-items:flex-start;gap:12px">
                    <div style="min-width:0;flex:1">
                        <p style="font-weight:800;color:#fff;margin:0;font-size:0.95rem;
                                  white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                            <?php echo e($evento?->titulo ?? 'Evento eliminado'); ?>

                        </p>
                        <?php if($evento): ?>
                            <p style="color:rgba(192,132,252,0.85);font-size:0.78rem;margin:3px 0 0">
                                <?php echo e(\Carbon\Carbon::parse($evento->fecha_inicio)->locale('es')->isoFormat('D MMM YYYY')); ?>

                                <?php if($evento->ubicacion_nombre): ?>
                                    &nbsp;·&nbsp;<?php echo e($evento->ubicacion_nombre); ?>

                                <?php endif; ?>
                            </p>
                        <?php endif; ?>
                        <p style="color:rgba(255,255,255,0.3);font-size:0.7rem;margin:3px 0 0">
                            Pedido #<?php echo e($pedido->id); ?>

                            · <?php echo e(\Carbon\Carbon::parse($pedido->fecha_creacion)->locale('es')->isoFormat('D MMM YYYY, HH:mm')); ?>

                        </p>
                    </div>
                    <div style="text-align:right;flex-shrink:0">
                        <span style="background:rgba(168,85,247,0.2);border:1px solid rgba(168,85,247,0.3);
                                     color:#c084fc;font-weight:700;font-size:0.7rem;
                                     padding:3px 10px;border-radius:999px;display:inline-block">
                            <?php echo e($pedido->entradas->count()); ?>

                            <?php echo e($pedido->entradas->count() === 1 ? 'entrada' : 'entradas'); ?>

                        </span>
                        <p style="margin:5px 0 0;font-weight:800;font-size:1rem">
                            <?php if($pedido->total_final == 0): ?>
                                <span style="color:#34d399">Gratis</span>
                            <?php else: ?>
                                <span class="text-gradient"><?php echo e(number_format($pedido->total_final, 2)); ?> €</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                
                <div style="padding:0 28px">
                    <?php $__currentLoopData = $pedido->entradas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $entrada): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        
                        <div style="position:relative;margin:0 -28px;border-top:2px dashed #ede9fe">
                            <div style="position:absolute;left:-11px;top:-11px;width:22px;height:22px;
                                        background:#f0ecff;border-radius:50%;border:2px solid #ede9fe"></div>
                            <div style="position:absolute;right:-11px;top:-11px;width:22px;height:22px;
                                        background:#f0ecff;border-radius:50%;border:2px solid #ede9fe"></div>
                        </div>

                        <div style="padding:16px 0">
                            <div style="display:flex;justify-content:space-between;align-items:center;gap:12px">
                                <div style="min-width:0">
                                    <p style="font-weight:700;color:var(--navy);margin:0;font-size:0.875rem">
                                        Entrada #<?php echo e($i + 1); ?>

                                    </p>
                                    <p style="font-family:monospace;font-size:0.62rem;color:#94a3b8;
                                              margin:3px 0 0;overflow:hidden;text-overflow:ellipsis;
                                              white-space:nowrap;max-width:200px">
                                        <?php echo e($entrada->codigo_qr); ?>

                                    </p>
                                </div>
                                <button id="btn-<?php echo e($entrada->id); ?>"
                                        onclick="toggleQr('qr-<?php echo e($entrada->id); ?>','btn-<?php echo e($entrada->id); ?>')"
                                        class="btn-secundario"
                                        style="padding:7px 18px;font-size:0.8rem;border-radius:999px;
                                               white-space:nowrap;flex-shrink:0;width:auto">
                                    Ver QR
                                </button>
                            </div>

                            <div id="qr-<?php echo e($entrada->id); ?>" style="display:none;text-align:center;padding-top:14px">
                                <div style="display:inline-block;padding:10px;background:#fff;
                                            border:2px solid #ede9fe;border-radius:14px;
                                            box-shadow:0 4px 20px rgba(124,58,237,0.1)">
                                    <div id="qr-canvas-<?php echo e($entrada->id); ?>" data-codigo="<?php echo e($entrada->codigo_qr); ?>"
                                         style="width:200px;height:200px;margin:0 auto"></div>
                                </div>
                                <p style="font-size:0.72rem;color:#94a3b8;margin:8px 0 0">
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

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
document.querySelectorAll('[data-codigo]').forEach(function(el) {
    new QRCode(el, {
        text: el.dataset.codigo,
        width: 200,
        height: 200,
        colorDark: '#000000',
        colorLight: '#ffffff',
    });
});
function toggleQr(qrId, btnId) {
    const panel   = document.getElementById(qrId);
    const btn     = document.getElementById(btnId);
    const visible = panel.style.display !== 'none';
    panel.style.display = visible ? 'none' : 'block';
    btn.textContent     = visible ? 'Ver QR' : 'Ocultar QR';
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\0616\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/entradas/mis-entradas.blade.php ENDPATH**/ ?>
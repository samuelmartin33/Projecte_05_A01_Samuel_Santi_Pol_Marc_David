

<?php $__env->startSection('title', 'Admin | Pedidos'); ?>

<?php $__env->startSection('content'); ?>
    <header class="admin-header">
        <div>
            <h1>Gestión de Pedidos</h1>
            <p>Listado informativo de pedidos realizados en la plataforma.</p>
        </div>
    </header>

    <?php if(session('success')): ?>
        <div class="alert success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <section class="card">
        <table class="tabla-eventos">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Total</th>
                    <th>Descuento</th>
                    <th>Total final</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $pedidos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pedido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td data-label="ID"><?php echo e($pedido->id); ?></td>
                        <td data-label="Usuario">
                            <?php echo e($pedido->usuario->nombre ?? 'Sin usuario'); ?>

                            <?php if($pedido->usuario?->email): ?>
                                <small style="display:block;opacity:.7"><?php echo e($pedido->usuario->email); ?></small>
                            <?php endif; ?>
                        </td>
                        <td data-label="Total"><?php echo e(number_format((float) $pedido->total, 2, ',', '.')); ?> €</td>
                        <td data-label="Descuento"><?php echo e(number_format((float) $pedido->total_descuento, 2, ',', '.')); ?> €</td>
                        <td data-label="Total final"><?php echo e(number_format((float) $pedido->total_final, 2, ',', '.')); ?> €</td>
                        <td data-label="Estado">
                            <span class="estado <?php echo e((int) $pedido->estado === 1 ? 'activo' : 'inactivo'); ?>">
                                <?php echo e((int) $pedido->estado === 1 ? 'Completado' : 'Cancelado'); ?>

                            </span>
                        </td>
                        <td data-label="Fecha"><?php echo e(optional($pedido->fecha_creacion)->format('d/m/Y H:i') ?? '—'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="empty">No hay pedidos registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

    <?php if($pedidos->hasPages()): ?>
        <nav class="paginacion" aria-label="Paginacion de pedidos">
            <div class="pagination-summary">
                Mostrando <strong><?php echo e($pedidos->firstItem()); ?></strong>
                a <strong><?php echo e($pedidos->lastItem()); ?></strong>
                de <strong><?php echo e($pedidos->total()); ?></strong> resultados
            </div>

            <div class="pagination-controls">
                <?php if($pedidos->onFirstPage()): ?>
                    <span class="pagination-arrow disabled" aria-hidden="true">‹</span>
                <?php else: ?>
                    <a class="pagination-arrow" href="<?php echo e($pedidos->previousPageUrl()); ?>" rel="prev" aria-label="Pagina anterior">‹</a>
                <?php endif; ?>

                <?php for($page = 1; $page <= $pedidos->lastPage(); $page++): ?>
                    <?php if($page === $pedidos->currentPage()): ?>
                        <span class="pagination-page active" aria-current="page"><?php echo e($page); ?></span>
                    <?php else: ?>
                        <a class="pagination-page" href="<?php echo e($pedidos->url($page)); ?>" aria-label="Ir a la pagina <?php echo e($page); ?>"><?php echo e($page); ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if($pedidos->hasMorePages()): ?>
                    <a class="pagination-arrow" href="<?php echo e($pedidos->nextPageUrl()); ?>" rel="next" aria-label="Pagina siguiente">›</a>
                <?php else: ?>
                    <span class="pagination-arrow disabled" aria-hidden="true">›</span>
                <?php endif; ?>
            </div>
        </nav>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\0616\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/admin/pedidos/index.blade.php ENDPATH**/ ?>
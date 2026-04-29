

<?php $__env->startSection('title', 'Admin | Pagos'); ?>

<?php $__env->startSection('content'); ?>
    <header class="admin-header">
        <div>
            <h1>Gestión de Pagos</h1>
            <p>Consulta, crea y edita pagos registrados en la plataforma.</p>
        </div>
        <a class="btn btn-primary" href="<?php echo e(route('admin.pagos.create')); ?>">Nuevo pago</a>
    </header>

    <?php if(session('success')): ?>
        <div class="alert success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <section class="card">
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pedido</th>
                        <th>Método</th>
                        <th>Estado pago</th>
                        <th>Importe</th>
                        <th>Moneda</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $pagos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pago): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td data-label="#"><?php echo e($pago->id); ?></td>
                            <td data-label="Pedido">#<?php echo e($pago->pedido_id); ?></td>
                            <td data-label="Método"><?php echo e($pago->metodo_pago); ?></td>
                            <td data-label="Estado pago"><?php echo e($pago->estado_pago); ?></td>
                            <td data-label="Importe"><?php echo e(number_format($pago->importe, 2)); ?> <?php echo e($pago->moneda); ?></td>
                            <td data-label="Moneda"><?php echo e($pago->moneda); ?></td>
                            <td data-label="Estado">
                                <span class="estado <?php echo e((int) $pago->estado === 1 ? 'activo' : 'inactivo'); ?>">
                                    <?php echo e((int) $pago->estado === 1 ? 'Activo' : 'Inactivo'); ?>

                                </span>
                            </td>
                            <td data-label="Acciones" class="actions-cell">
                                <a class="btn btn-secondary" href="<?php echo e(route('admin.pagos.edit', $pago)); ?>">Editar</a>
                                <form method="POST" action="<?php echo e(route('admin.pagos.destroy', $pago)); ?>" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar el pago #<?php echo e($pago->id); ?>?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="8" class="empty">No hay pagos registrados.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <?php if($pagos->hasPages()): ?>
        <nav class="paginacion" aria-label="Paginacion de pagos">
            <div class="pagination-summary">
                Mostrando <strong><?php echo e($pagos->firstItem()); ?></strong> a <strong><?php echo e($pagos->lastItem()); ?></strong> de <strong><?php echo e($pagos->total()); ?></strong> resultados
            </div>
            <div class="pagination-controls">
                <?php if($pagos->onFirstPage()): ?>
                    <span class="pagination-arrow disabled" aria-hidden="true">‹</span>
                <?php else: ?>
                    <a class="pagination-arrow" href="<?php echo e($pagos->previousPageUrl()); ?>" rel="prev">‹</a>
                <?php endif; ?>
                <?php if($pagos->hasMorePages()): ?>
                    <a class="pagination-arrow" href="<?php echo e($pagos->nextPageUrl()); ?>" rel="next">›</a>
                <?php else: ?>
                    <span class="pagination-arrow disabled" aria-hidden="true">›</span>
                <?php endif; ?>
            </div>
        </nav>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\0616\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/admin/pagos/index.blade.php ENDPATH**/ ?>
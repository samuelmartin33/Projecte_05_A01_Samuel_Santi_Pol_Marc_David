<?php $__env->startSection('title', 'Admin | Pedidos'); ?>

<?php $__env->startSection('content'); ?>
    <header class="admin-header">
        <div>
            <h1>Gestión de Pedidos</h1>
            <p>Consulta, crea y edita pedidos registrados en la plataforma.</p>
        </div>
        <a class="btn btn-primary" href="<?php echo e(route('admin.pedidos.create')); ?>">Nuevo pedido</a>
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
                        <th>Usuario</th>
                        <th>Total</th>
                        <th>Descuento</th>
                        <th>Final</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $pedidos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pedido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td data-label="#"><?php echo e($pedido->id); ?></td>
                            <td data-label="Usuario"><?php echo e($pedido->usuario?->nombre); ?> <?php echo e($pedido->usuario?->apellido1); ?></td>
                            <td data-label="Total"><?php echo e(number_format($pedido->total, 2)); ?> €</td>
                            <td data-label="Descuento"><?php echo e(number_format($pedido->total_descuento, 2)); ?> €</td>
                            <td data-label="Final"><?php echo e(number_format($pedido->total_final, 2)); ?> €</td>
                            <td data-label="Estado">
                                <span class="estado <?php echo e((int) $pedido->estado === 1 ? 'activo' : 'inactivo'); ?>">
                                    <?php echo e((int) $pedido->estado === 1 ? 'Activo' : 'Inactivo'); ?>

                                </span>
                            </td>
                            <td data-label="Fecha"><?php echo e(\Carbon\Carbon::parse($pedido->fecha_creacion)->format('d/m/Y H:i')); ?></td>
                            <td data-label="Acciones" class="actions-cell">
                                <a class="btn btn-secondary" href="<?php echo e(route('admin.pedidos.edit', $pedido)); ?>">Editar</a>
                                <form method="POST" action="<?php echo e(route('admin.pedidos.destroy', $pedido)); ?>" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar el pedido #<?php echo e($pedido->id); ?>?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="8" class="empty">No hay pedidos registrados.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <?php if($pedidos->hasPages()): ?>
        <nav class="paginacion" aria-label="Paginacion de pedidos">
            <div class="pagination-summary">
                Mostrando <strong><?php echo e($pedidos->firstItem()); ?></strong> a <strong><?php echo e($pedidos->lastItem()); ?></strong> de <strong><?php echo e($pedidos->total()); ?></strong> resultados
            </div>
            <div class="pagination-controls">
                <?php if($pedidos->onFirstPage()): ?>
                    <span class="pagination-arrow disabled" aria-hidden="true">‹</span>
                <?php else: ?>
                    <a class="pagination-arrow" href="<?php echo e($pedidos->previousPageUrl()); ?>" rel="prev">‹</a>
                <?php endif; ?>
                <?php if($pedidos->hasMorePages()): ?>
                    <a class="pagination-arrow" href="<?php echo e($pedidos->nextPageUrl()); ?>" rel="next">›</a>
                <?php else: ?>
                    <span class="pagination-arrow disabled" aria-hidden="true">›</span>
                <?php endif; ?>
            </div>
        </nav>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/admin/pedidos/index.blade.php ENDPATH**/ ?>
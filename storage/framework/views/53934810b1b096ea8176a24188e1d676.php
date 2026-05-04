<?php $__env->startSection('title', 'Admin | Eventos'); ?>

<?php $__env->startSection('content'); ?>
    <header class="admin-header">
        <div>
            <h1>Gestor de Eventos</h1>
            <p>Panel de administracion para crear, editar y eliminar eventos.</p>
        </div>
        <a class="btn btn-primary" href="<?php echo e(route('admin.eventos.create')); ?>">Nuevo evento</a>
    </header>

    <?php if(session('success')): ?>
        <div class="alert success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <section class="card">
        <table class="tabla-eventos">
            <thead>
            <tr>
                <th>ID</th>
                <th>Titulo</th>
                <th>Categoria</th>
                <th>Organizador</th>
                <th>Inicio</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $eventos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td data-label="ID"><?php echo e($evento->id); ?></td>
                    <td data-label="Titulo"><?php echo e($evento->titulo); ?></td>
                    <td data-label="Categoria"><?php echo e($evento->categoriaEvento->nombre ?? 'Sin categoria'); ?></td>
                    <td data-label="Organizador">#<?php echo e($evento->organizador_id); ?></td>
                    <td data-label="Inicio"><?php echo e(optional($evento->fecha_inicio)->format('d/m/Y H:i')); ?></td>
                    <td data-label="Estado">
                        <span class="estado <?php echo e($evento->estado ? 'activo' : 'inactivo'); ?>">
                            <?php echo e($evento->estado ? 'Activo' : 'Inactivo'); ?>

                        </span>
                    </td>
                    <td data-label="Acciones" class="acciones">
                        <a class="btn btn-secondary" href="<?php echo e(route('admin.eventos.edit', $evento)); ?>">Editar</a>
                        <form method="POST" action="<?php echo e(route('admin.eventos.destroy', $evento)); ?>" class="delete-form">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="empty">No hay eventos registrados.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </section>

    <?php if($eventos->hasPages()): ?>
        <nav class="paginacion" aria-label="Paginacion de eventos">
            <div class="pagination-summary">
                Mostrando <strong><?php echo e($eventos->firstItem()); ?></strong>
                a <strong><?php echo e($eventos->lastItem()); ?></strong>
                de <strong><?php echo e($eventos->total()); ?></strong> resultados
            </div>

            <div class="pagination-controls">
                <?php if($eventos->onFirstPage()): ?>
                    <span class="pagination-arrow disabled" aria-hidden="true">‹</span>
                <?php else: ?>
                    <a class="pagination-arrow" href="<?php echo e($eventos->previousPageUrl()); ?>" rel="prev" aria-label="Pagina anterior">‹</a>
                <?php endif; ?>

                <?php for($page = 1; $page <= $eventos->lastPage(); $page++): ?>
                    <?php if($page === $eventos->currentPage()): ?>
                        <span class="pagination-page active" aria-current="page"><?php echo e($page); ?></span>
                    <?php else: ?>
                        <a class="pagination-page" href="<?php echo e($eventos->url($page)); ?>" aria-label="Ir a la pagina <?php echo e($page); ?>"><?php echo e($page); ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if($eventos->hasMorePages()): ?>
                    <a class="pagination-arrow" href="<?php echo e($eventos->nextPageUrl()); ?>" rel="next" aria-label="Pagina siguiente">›</a>
                <?php else: ?>
                    <span class="pagination-arrow disabled" aria-hidden="true">›</span>
                <?php endif; ?>
            </div>
        </nav>
    <?php endif; ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/admin/eventos/index.blade.php ENDPATH**/ ?>
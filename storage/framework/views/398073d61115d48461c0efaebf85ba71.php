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

    <div class="paginacion"><?php echo e($eventos->links()); ?></div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\0616\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/admin/eventos/index.blade.php ENDPATH**/ ?>
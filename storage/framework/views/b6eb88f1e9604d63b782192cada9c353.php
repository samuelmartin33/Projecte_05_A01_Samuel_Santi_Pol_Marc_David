<?php $__env->startSection('title', 'Admin | Categorías'); ?>

<?php $__env->startSection('content'); ?>
    <header class="admin-header">
        <div>
            <h1>Gestor de Categorías</h1>
            <p>Administra las categorías y etiquetas de los eventos.</p>
        </div>
        <a class="btn btn-primary" href="<?php echo e(route('admin.categorias.create')); ?>">Nueva categoría</a>
    </header>

    <?php if(session('success')): ?>
        <div class="alert success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <section class="card">
        <table class="tabla-eventos">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td data-label="ID"><?php echo e($cat->id); ?></td>
                        <td data-label="Nombre"><?php echo e($cat->nombre); ?></td>
                        <td data-label="Descripción"><?php echo e(Str::limit($cat->descripcion, 80)); ?></td>
                        <td data-label="Estado"><span class="estado <?php echo e($cat->estado ? 'activo' : 'inactivo'); ?>"><?php echo e($cat->estado ? 'Activo' : 'Inactivo'); ?></span></td>
                        <td data-label="Acciones" class="acciones">
                            <a class="btn btn-secondary" href="<?php echo e(route('admin.categorias.edit', $cat)); ?>">Editar</a>
                            <form method="POST" action="<?php echo e(route('admin.categorias.destroy', $cat)); ?>" class="delete-form" style="display:inline-block">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar esta categoría?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="empty">No hay categorías registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/admin/categorias/index.blade.php ENDPATH**/ ?>
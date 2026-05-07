

<?php $__env->startSection('title', 'Admin | Crear categoría'); ?>

<?php $__env->startSection('content'); ?>
    <header class="admin-header">
        <div>
            <h1>Crear Categoría</h1>
            <p>Completa los datos para registrar una nueva categoría de evento.</p>
        </div>
    </header>

    <section class="card">
        <form action="<?php echo e(route('admin.categorias.store')); ?>" method="POST" class="evento-form">
            <?php echo csrf_field(); ?>
            <?php echo $__env->make('admin.categorias._form', ['categoria' => $categoria], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </form>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\0616\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/admin/categorias/create.blade.php ENDPATH**/ ?>
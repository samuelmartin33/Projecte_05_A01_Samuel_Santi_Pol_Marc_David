<?php $__env->startSection('title', 'Admin | Editar usuario'); ?>

<?php $__env->startSection('content'); ?>
    <header class="admin-header">
        <div>
            <h1>Editar Usuario #<?php echo e($usuario->id); ?></h1>
            <p>Actualiza los datos de la cuenta seleccionada.</p>
        </div>
    </header>

    <section class="card">
        <form method="POST" action="<?php echo e(route('admin.usuarios.update', $usuario)); ?>" class="evento-form">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <?php echo $__env->make('admin.usuarios._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </form>
    </section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views\admin\usuarios\edit.blade.php ENDPATH**/ ?>
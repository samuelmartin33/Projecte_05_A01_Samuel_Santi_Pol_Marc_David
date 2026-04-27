

<?php $__env->startSection('title', 'Admin | Crear usuario'); ?>

<?php $__env->startSection('content'); ?>
    <header class="admin-header">
        <div>
            <h1>Crear Usuario</h1>
            <p>Registra una nueva cuenta desde el panel de administración.</p>
        </div>
    </header>

    <section class="card">
        <form method="POST" action="<?php echo e(route('admin.usuarios.store')); ?>" class="evento-form">
            <?php echo csrf_field(); ?>
            <?php echo $__env->make('admin.usuarios._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </form>
    </section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\0616\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/admin/usuarios/create.blade.php ENDPATH**/ ?>
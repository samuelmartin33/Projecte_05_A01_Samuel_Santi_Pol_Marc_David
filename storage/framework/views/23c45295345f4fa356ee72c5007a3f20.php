<?php $__env->startSection('title', 'Admin | Editar pago'); ?>

<?php $__env->startSection('content'); ?>
    <header class="admin-header">
        <div>
            <h1>Editar Pago #<?php echo e($pago->id); ?></h1>
            <p>Actualiza los datos del pago seleccionado.</p>
        </div>
    </header>

    <section class="card">
        <form method="POST" action="<?php echo e(route('admin.pagos.update', $pago)); ?>" class="evento-form">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <?php echo $__env->make('admin.pagos._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </form>
    </section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views\admin\pagos\edit.blade.php ENDPATH**/ ?>
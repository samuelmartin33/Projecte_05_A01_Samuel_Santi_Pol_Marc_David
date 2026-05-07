<?php $__env->startSection('title', 'Admin | Crear pedido'); ?>

<?php $__env->startSection('content'); ?>
    <header class="admin-header">
        <div>
            <h1>Crear Pedido</h1>
            <p>Registra un nuevo pedido manualmente desde el panel.</p>
        </div>
    </header>

    <section class="card">
        <form method="POST" action="<?php echo e(route('admin.pedidos.store')); ?>" class="evento-form">
            <?php echo csrf_field(); ?>
            <?php echo $__env->make('admin.pedidos._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </form>
    </section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/admin/pedidos/create.blade.php ENDPATH**/ ?>
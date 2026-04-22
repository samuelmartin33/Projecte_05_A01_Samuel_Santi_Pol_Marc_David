<?php $__env->startSection('title', 'Admin | Crear evento'); ?>

<?php $__env->startSection('content'); ?>
    <header class="admin-header">
        <div>
            <h1>Crear Evento</h1>
            <p>Completa los datos para registrar un nuevo evento.</p>
        </div>
    </header>

    <section class="card">
        <form method="POST" action="<?php echo e(route('admin.eventos.store')); ?>" class="evento-form">
            <?php echo csrf_field(); ?>
            <?php echo $__env->make('admin.eventos._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </form>
    </section>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\0616\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/admin/eventos/create.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Admin | Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <header class="admin-header">
        <div>
            <h1>Dashboard inicial</h1>
            <p>Primera version del panel. Actualmente se administran eventos, usuarios y empresas.</p>
        </div>
        <a class="btn btn-secondary" href="<?php echo e(route('home')); ?>">Volver al inicio</a>
    </header>

    <section class="card quick-actions">
        <h2>Acciones rapidas</h2>
        <div class="quick-actions-grid">
            <a class="quick-action-item" href="<?php echo e(route('admin.eventos.create')); ?>">Crear evento</a>
            <a class="quick-action-item" href="<?php echo e(route('admin.usuarios.create')); ?>">Crear usuario</a>
            <a class="quick-action-item" href="<?php echo e(route('admin.empresas.index')); ?>">Gestionar empresas</a>
            <a class="quick-action-item" href="<?php echo e(route('admin.pedidos.create')); ?>">Crear pedido</a>
            <a class="quick-action-item" href="<?php echo e(route('admin.pagos.create')); ?>">Registrar pago</a>
        </div>
    </section>

    <section class="dashboard-cards">
        <article class="card stat-card">
            <h2>Eventos activos</h2>
            <p class="stat-number"><?php echo e($eventosActivos); ?></p>
        </article>

        <a href="<?php echo e(route('admin.usuarios.index')); ?>" class="card stat-card" style="text-decoration:none;color:inherit;">
            <h2>Usuarios activos</h2>
            <p class="stat-number"><?php echo e($usuariosActivos); ?></p>
        </a>

        <a href="<?php echo e(route('admin.empresas.index')); ?>" class="card stat-card <?php echo e($empresasPendientes > 0 ? 'stat-card--alert' : ''); ?>" style="text-decoration:none;color:inherit;">
            <h2>Empresas pendientes</h2>
            <p class="stat-number"><?php echo e($empresasPendientes); ?></p>
            <?php if($empresasPendientes > 0): ?>
                <p class="stat-hint">Requieren revisión</p>
            <?php endif; ?>
        </a>

        <a href="<?php echo e(route('admin.pedidos.index')); ?>" class="card stat-card" style="text-decoration:none;color:inherit;">
            <h2>Pedidos</h2>
            <p class="stat-number"><?php echo e($totalPedidos); ?></p>
        </a>

        <a href="<?php echo e(route('admin.pagos.index')); ?>" class="card stat-card" style="text-decoration:none;color:inherit;">
            <h2>Pagos</h2>
            <p class="stat-number"><?php echo e($totalPagos); ?></p>
        </a>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\0616\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>
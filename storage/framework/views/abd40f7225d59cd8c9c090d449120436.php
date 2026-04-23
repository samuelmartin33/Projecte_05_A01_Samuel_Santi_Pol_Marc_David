<?php $__env->startSection('title', 'Admin | Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <header class="admin-header">
        <div>
            <h1>Dashboard inicial</h1>
            <p>Primera version del panel. Actualmente solo se administra el modulo de eventos.</p>
        </div>
        <a class="btn btn-secondary" href="<?php echo e(route('home')); ?>">Volver al inicio</a>
    </header>

    <section class="card quick-actions">
        <h2>Acciones rapidas</h2>
        <div class="quick-actions-grid">
            <a class="quick-action-item" href="<?php echo e(route('admin.eventos.create')); ?>">Crear evento</a>
            <span class="quick-action-item disabled">Crear usuario (proximamente)</span>
            <span class="quick-action-item disabled">Crear empresa (proximamente)</span>
            <span class="quick-action-item disabled">Crear pedido (proximamente)</span>
            <span class="quick-action-item disabled">Registrar pago (proximamente)</span>
        </div>
    </section>

    <section class="dashboard-cards">
        <article class="card stat-card">
            <h2>Total eventos</h2>
            <p class="stat-number"><?php echo e($totalEventos); ?></p>
        </article>

        <article class="card stat-card">
            <h2>Eventos activos</h2>
            <p class="stat-number"><?php echo e($eventosActivos); ?></p>
        </article>

        <article class="card stat-card muted">
            <h2>Usuarios</h2>
            <p class="soon">Proximamente</p>
        </article>

        <article class="card stat-card muted">
            <h2>Empresas</h2>
            <p class="soon">Proximamente</p>
        </article>

        <article class="card stat-card muted">
            <h2>Pedidos</h2>
            <p class="soon">Proximamente</p>
        </article>

        <article class="card stat-card muted">
            <h2>Pagos</h2>
            <p class="soon">Proximamente</p>
        </article>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>
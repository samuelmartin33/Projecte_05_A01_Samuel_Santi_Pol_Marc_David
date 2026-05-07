<?php $__env->startSection('title', 'Admin | Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <section class="dashboard-page">
        <div class="card dashboard-search-card">
            <div class="dashboard-search-inner">
                <div class="dashboard-search-field">
                    <svg class="dashboard-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="M20 20l-3.5-3.5"></path>
                    </svg>
                    <input
                        type="text"
                        class="dashboard-search-input"
                        placeholder="Buscar eventos, usuarios, pedidos..."
                        aria-label="Buscar eventos, usuarios, pedidos"
                    >
                </div>
            </div>
        </div>

        <section class="card dashboard-metrics-card" aria-label="Métricas del panel">
            <div class="dashboard-metrics-grid">
                <article class="dashboard-metric-item">
                    <span class="dashboard-metric-label">Eventos activos</span>
                    <span class="dashboard-metric-value"><?php echo e($eventosActivos); ?></span>
                </article>
                <article class="dashboard-metric-item">
                    <span class="dashboard-metric-label">Usuarios activos</span>
                    <span class="dashboard-metric-value"><?php echo e($usuariosActivos); ?></span>
                </article>
                <article class="dashboard-metric-item">
                    <span class="dashboard-metric-label">Empresas pendientes</span>
                    <span class="dashboard-metric-value dashboard-metric-value--danger"><?php echo e($empresasPendientes); ?></span>
                </article>
                <article class="dashboard-metric-item">
                    <span class="dashboard-metric-label">Pedidos</span>
                    <span class="dashboard-metric-value"><?php echo e($totalPedidos); ?></span>
                </article>
                <article class="dashboard-metric-item">
                    <span class="dashboard-metric-label">Pagos</span>
                    <span class="dashboard-metric-value"><?php echo e($totalPagos); ?></span>
                </article>
            </div>
        </section>

        <section class="card dashboard-actions-card" aria-label="Acciones rápidas">
            <div class="dashboard-actions-grid">
                <a class="dashboard-action-item" href="<?php echo e(route('admin.eventos.create')); ?>">
                    <span>Crear evento</span>
                </a>
                <a class="dashboard-action-item" href="<?php echo e(route('admin.usuarios.create')); ?>">
                    <span>Crear usuario</span>
                </a>
                <a class="dashboard-action-item" href="<?php echo e(route('admin.empresas.index')); ?>">
                    <span>Gestionar empresas</span>
                </a>
                <a class="dashboard-action-item" href="<?php echo e(route('admin.pagos.create')); ?>">
                    <span>Registrar pago</span>
                </a>
            </div>
        </section>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\0616\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>
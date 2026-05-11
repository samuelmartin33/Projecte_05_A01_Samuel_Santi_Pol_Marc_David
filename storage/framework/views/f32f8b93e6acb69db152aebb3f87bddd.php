<?php $__env->startSection('title', 'Dashboard — VIBEZ Admin'); ?>

<?php $__env->startSection('content'); ?>


<div class="adm-hero">
    <div class="adm-hero-row">
        <div>
            <p class="adm-hero-kicker">
                ▸ Panel de control · <?php echo e(now()->locale('es')->isoFormat('dddd, D [de] MMMM')); ?>

            </p>
            <h1>Panel <em>Admin</em></h1>
            <p class="adm-hero-sub">
                <?php echo e($eventosActivos); ?> evento<?php echo e($eventosActivos !== 1 ? 's' : ''); ?> activo<?php echo e($eventosActivos !== 1 ? 's' : ''); ?>,
                <?php echo e($usuariosActivos); ?> usuarios verificados
                <?php if($empresasPendientes > 0): ?>
                    · <strong style="color:var(--adm-warn)"><?php echo e($empresasPendientes); ?> empresa<?php echo e($empresasPendientes !== 1 ? 's' : ''); ?> pendiente<?php echo e($empresasPendientes !== 1 ? 's' : ''); ?></strong>
                <?php endif; ?>
            </p>
        </div>
        <div class="adm-hero-actions">
            <a href="<?php echo e(route('admin.eventos.create')); ?>" class="adm-btn-pri">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Nuevo evento
            </a>
            <a href="<?php echo e(route('admin.empresas.index')); ?>" class="adm-btn-ghost">
                Gestionar empresas
            </a>
        </div>
    </div>
</div>


<div class="adm-kpi-grid">

    <div class="adm-kpi">
        <div class="adm-kpi-head">
            <div class="adm-kpi-label">Eventos activos</div>
            <div class="adm-kpi-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8"  y1="2" x2="8"  y2="6"/>
                    <line x1="3"  y1="10" x2="21" y2="10"/>
                </svg>
            </div>
        </div>
        <div class="adm-kpi-value"><?php echo e($eventosActivos); ?></div>
        <div class="adm-kpi-foot">
            <span class="adm-kpi-lbl">eventos publicados</span>
        </div>
        <svg class="adm-spark" viewBox="0 0 80 24" preserveAspectRatio="none">
            <polyline points="0,18 14,15 28,17 42,12 56,10 70,7 80,4"
                      fill="none" stroke="#a855f7" stroke-width="1.5"/>
        </svg>
    </div>

    <div class="adm-kpi">
        <div class="adm-kpi-head">
            <div class="adm-kpi-label">Usuarios activos</div>
            <div class="adm-kpi-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
        </div>
        <div class="adm-kpi-value"><?php echo e($usuariosActivos); ?></div>
        <div class="adm-kpi-foot">
            <span class="adm-kpi-lbl">de <?php echo e($totalUsuarios); ?> registrados</span>
        </div>
        <svg class="adm-spark" viewBox="0 0 80 24" preserveAspectRatio="none">
            <polyline points="0,20 14,18 28,14 42,16 56,10 70,7 80,3"
                      fill="none" stroke="#a855f7" stroke-width="1.5"/>
        </svg>
    </div>

    <div class="adm-kpi">
        <div class="adm-kpi-head">
            <div class="adm-kpi-label">Empresas pendientes</div>
            <div class="adm-kpi-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16"/>
                    <path d="M3 21h18"/>
                    <path d="M9 9h1m-1 4h1m4-4h1m-1 4h1"/>
                </svg>
            </div>
        </div>
        <div class="adm-kpi-value <?php echo e($empresasPendientes > 0 ? 'warn' : ''); ?>"><?php echo e($empresasPendientes); ?></div>
        <div class="adm-kpi-foot">
            <span class="adm-kpi-lbl">esperando aprobación</span>
        </div>
    </div>

    <div class="adm-kpi">
        <div class="adm-kpi-head">
            <div class="adm-kpi-label">Total pedidos</div>
            <div class="adm-kpi-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M2 9V7a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v2a2 2 0 0 0 0 4v2a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-2a2 2 0 0 0 0-4z"/>
                    <line x1="13" y1="5" x2="13" y2="19"/>
                </svg>
            </div>
        </div>
        <div class="adm-kpi-value"><?php echo e($totalPedidos); ?></div>
        <div class="adm-kpi-foot">
            <span class="adm-kpi-lbl">pedidos registrados</span>
        </div>
        <svg class="adm-spark" viewBox="0 0 80 24" preserveAspectRatio="none">
            <polyline points="0,16 14,14 28,12 42,10 56,8 70,5 80,3"
                      fill="none" stroke="#a855f7" stroke-width="1.5"/>
        </svg>
    </div>

</div>


<div class="adm-two-col">

    <div class="adm-card">
        <div class="adm-card-head">
            <div>
                <h3 class="adm-card-title">Acciones rápidas</h3>
                <div class="adm-card-sub">Gestión del panel</div>
            </div>
        </div>
        <div class="adm-actions-grid">
            <a href="<?php echo e(route('admin.eventos.create')); ?>" class="adm-action-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                    <line x1="12" y1="14" x2="12" y2="18"/><line x1="10" y1="16" x2="14" y2="16"/>
                </svg>
                Crear evento
            </a>
            <a href="<?php echo e(route('admin.usuarios.create')); ?>" class="adm-action-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="8.5" cy="7" r="4"/>
                    <line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/>
                </svg>
                Crear usuario
            </a>
            <a href="<?php echo e(route('admin.empresas.index')); ?>" class="adm-action-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16"/>
                    <path d="M3 21h18"/>
                    <path d="M9 9h1m-1 4h1m4-4h1m-1 4h1"/>
                </svg>
                Gestionar empresas
            </a>
            <a href="<?php echo e(route('admin.categorias.index')); ?>" class="adm-action-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                    <line x1="7" y1="7" x2="7.01" y2="7"/>
                </svg>
                Categorías
            </a>
            <a href="<?php echo e(route('admin.pedidos.index')); ?>" class="adm-action-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M2 9V7a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v2a2 2 0 0 0 0 4v2a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-2a2 2 0 0 0 0-4z"/>
                </svg>
                Ver pedidos
            </a>
            <a href="<?php echo e(route('admin.pagos.index')); ?>" class="adm-action-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="1" x2="12" y2="23"/>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
                Ver pagos
            </a>
        </div>
    </div>

    <div class="adm-card">
        <div class="adm-card-head">
            <div>
                <h3 class="adm-card-title">Resumen</h3>
                <div class="adm-card-sub">Estadísticas generales</div>
            </div>
        </div>
        <div>
            <div class="adm-stat-row">
                <span class="adm-stat-label">Total usuarios</span>
                <span class="adm-stat-value"><?php echo e($totalUsuarios); ?></span>
            </div>
            <div class="adm-stat-row">
                <span class="adm-stat-label">Pagos registrados</span>
                <span class="adm-stat-value"><?php echo e($totalPagos); ?></span>
            </div>
            <div class="adm-stat-row">
                <span class="adm-stat-label">Categorías</span>
                <span class="adm-stat-value"><?php echo e($totalCategorias); ?></span>
            </div>
            <div class="adm-stat-row">
                <span class="adm-stat-label">Empresas pendientes</span>
                <span class="adm-stat-value" style="<?php echo e($empresasPendientes > 0 ? 'color:var(--adm-warn)' : ''); ?>">
                    <?php echo e($empresasPendientes); ?>

                </span>
            </div>
        </div>
    </div>

</div>


<?php if($empresasPendientes > 0): ?>
<div class="adm-card adm-section">
    <div class="adm-card-head">
        <div>
            <h3 class="adm-card-title">Pendientes de aprobación</h3>
            <div class="adm-card-sub">
                <?php echo e($empresasPendientes); ?> empresa<?php echo e($empresasPendientes !== 1 ? 's' : ''); ?>

                esperando revisión
            </div>
        </div>
        <a href="<?php echo e(route('admin.empresas.index')); ?>" class="adm-btn-ghost">Ver todas →</a>
    </div>
    <p style="color:var(--adm-ink-dim);font-size:13px;margin:0;font-family:'Archivo Narrow',sans-serif;text-transform:uppercase;letter-spacing:0.08em;line-height:1.6">
        Revisa y aprueba o rechaza las empresas pendientes desde el gestor de empresas.
    </p>
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>
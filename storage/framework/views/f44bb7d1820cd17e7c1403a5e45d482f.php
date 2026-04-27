<?php $__env->startSection('title', 'Admin | Empresas'); ?>

<?php $__env->startSection('content'); ?>
    <header class="admin-header">
        <div>
            <h1>Gestión de Empresas</h1>
            <p>Aprueba o rechaza las solicitudes de registro de cuentas de empresa.</p>
        </div>
        <a class="btn btn-secondary" href="<?php echo e(route('admin.dashboard')); ?>">Volver al inicio</a>
    </header>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    
    <section class="card" style="margin-bottom: 2rem;">
        <h2 style="margin-bottom: 1rem;">
            Solicitudes pendientes
            <?php if($pendientes->count() > 0): ?>
                <span class="badge-count"><?php echo e($pendientes->count()); ?></span>
            <?php endif; ?>
        </h2>

        <?php if($pendientes->isEmpty()): ?>
            <p class="text-muted">No hay solicitudes pendientes en este momento.</p>
        <?php else: ?>
            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Fecha solicitud</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $pendientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $empresa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($empresa->id); ?></td>
                                <td><?php echo e($empresa->nombre); ?> <?php echo e($empresa->apellido1); ?> <?php echo e($empresa->apellido2); ?></td>
                                <td><?php echo e($empresa->email); ?></td>
                                <td><?php echo e($empresa->telefono ?? '—'); ?></td>
                                <td><?php echo e(\Carbon\Carbon::parse($empresa->fecha_creacion)->format('d/m/Y H:i')); ?></td>
                                <td class="actions-cell">
                                    <form method="POST" action="<?php echo e(route('admin.empresas.aprobar', $empresa->id)); ?>" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-success btn-sm"
                                            onclick="return confirm('¿Aprobar la cuenta de <?php echo e(addslashes($empresa->nombre.' '.$empresa->apellido1)); ?>?')">
                                            Aprobar
                                        </button>
                                    </form>
                                    <form method="POST" action="<?php echo e(route('admin.empresas.rechazar', $empresa->id)); ?>" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('¿Rechazar la solicitud de <?php echo e(addslashes($empresa->nombre.' '.$empresa->apellido1)); ?>?')">
                                            Rechazar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

    
    <section class="card">
        <h2 style="margin-bottom: 1rem;">Historial</h2>

        <?php if($gestionadas->isEmpty()): ?>
            <p class="text-muted">Todavía no hay empresas gestionadas.</p>
        <?php else: ?>
            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Fecha solicitud</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $gestionadas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $empresa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($empresa->id); ?></td>
                                <td><?php echo e($empresa->nombre); ?> <?php echo e($empresa->apellido1); ?> <?php echo e($empresa->apellido2); ?></td>
                                <td><?php echo e($empresa->email); ?></td>
                                <td><?php echo e($empresa->telefono ?? '—'); ?></td>
                                <td><?php echo e(\Carbon\Carbon::parse($empresa->fecha_creacion)->format('d/m/Y H:i')); ?></td>
                                <td>
                                    <?php if($empresa->estado_registro === 'aprobado'): ?>
                                        <span class="badge badge-success">Aprobada</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Rechazada</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

    <style>
        .badge-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #e53e3e;
            color: #fff;
            font-size: .75rem;
            font-weight: 700;
            min-width: 1.4rem;
            height: 1.4rem;
            border-radius: 999px;
            padding: 0 .4rem;
            margin-left: .5rem;
            vertical-align: middle;
        }
        .table-wrap { overflow-x: auto; }
        .admin-table { width: 100%; border-collapse: collapse; font-size: .875rem; }
        .admin-table th,
        .admin-table td { padding: .6rem 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .admin-table th { font-weight: 600; color: #4a5568; background: #f7fafc; }
        .admin-table tbody tr:hover { background: #f7fafc; }
        .actions-cell { display: flex; gap: .5rem; align-items: center; flex-wrap: wrap; }
        .btn-sm { padding: .3rem .75rem; font-size: .8rem; border-radius: 4px; border: none; cursor: pointer; font-weight: 600; }
        .btn-success { background: #38a169; color: #fff; }
        .btn-success:hover { background: #2f855a; }
        .btn-danger { background: #e53e3e; color: #fff; }
        .btn-danger:hover { background: #c53030; }
        .badge { display: inline-block; padding: .2rem .6rem; border-radius: 999px; font-size: .75rem; font-weight: 700; }
        .badge-success { background: #c6f6d5; color: #22543d; }
        .badge-danger  { background: #fed7d7; color: #742a2a; }
        .alert { padding: .75rem 1rem; border-radius: 6px; margin-bottom: 1rem; font-size: .9rem; }
        .alert-success { background: #c6f6d5; color: #22543d; border: 1px solid #9ae6b4; }
        .alert-danger  { background: #fed7d7; color: #742a2a; border: 1px solid #fc8181; }
        .text-muted { color: #718096; font-size: .9rem; }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/admin/empresas/index.blade.php ENDPATH**/ ?>
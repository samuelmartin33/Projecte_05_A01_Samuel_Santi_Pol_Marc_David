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

    
    <section class="card admin-panel-section" style="margin-bottom: 2rem;">
        <div class="panel-header">
            <h2>
                Solicitudes pendientes
                <?php if($pendientes->count() > 0): ?>
                    <span class="badge-count"><?php echo e($pendientes->count()); ?></span>
                <?php endif; ?>
            </h2>
        </div>

        <?php if($pendientes->isEmpty()): ?>
            <div class="panel-empty">
                <p class="text-muted">No hay solicitudes pendientes en este momento.</p>
            </div>
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
                                <td data-label="#"><?php echo e($empresa->id); ?></td>
                                <td data-label="Nombre"><?php echo e($empresa->nombre); ?> <?php echo e($empresa->apellido1); ?> <?php echo e($empresa->apellido2); ?></td>
                                <td data-label="Email"><?php echo e($empresa->email); ?></td>
                                <td data-label="Teléfono"><?php echo e($empresa->telefono ?? '—'); ?></td>
                                <td data-label="Fecha solicitud"><?php echo e(\Carbon\Carbon::parse($empresa->fecha_creacion)->format('d/m/Y H:i')); ?></td>
                                <td data-label="Acciones" class="actions-cell">
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

    
    <section class="card admin-panel-section">
        <div class="panel-header">
            <h2>Historial</h2>
        </div>

        <?php if($gestionadas->isEmpty()): ?>
            <div class="panel-empty">
                <p class="text-muted">Todavía no hay empresas gestionadas.</p>
            </div>
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
                                <td data-label="#"><?php echo e($empresa->id); ?></td>
                                <td data-label="Nombre"><?php echo e($empresa->nombre); ?> <?php echo e($empresa->apellido1); ?> <?php echo e($empresa->apellido2); ?></td>
                                <td data-label="Email"><?php echo e($empresa->email); ?></td>
                                <td data-label="Teléfono"><?php echo e($empresa->telefono ?? '—'); ?></td>
                                <td data-label="Fecha solicitud"><?php echo e(\Carbon\Carbon::parse($empresa->fecha_creacion)->format('d/m/Y H:i')); ?></td>
                                <td data-label="Estado">
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\0616\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/admin/empresas/index.blade.php ENDPATH**/ ?>
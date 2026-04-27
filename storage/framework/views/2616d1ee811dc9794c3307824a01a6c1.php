

<?php $__env->startSection('title', 'Admin | Usuarios'); ?>

<?php $__env->startSection('content'); ?>
    <header class="admin-header">
        <div>
            <h1>Gestión de Usuarios</h1>
            <p>Administra las cuentas registradas en la plataforma.</p>
        </div>
        <a class="btn btn-primary" href="<?php echo e(route('admin.usuarios.create')); ?>">Nuevo usuario</a>
    </header>

    <?php if(session('success')): ?>
        <div class="alert success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert error"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <section class="card">
        <table class="tabla-eventos">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Cuenta</th>
                <th>Registro</th>
                <th>Admin</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td data-label="ID"><?php echo e($usuario->id); ?></td>
                    <td data-label="Nombre">
                        <?php echo e($usuario->nombre); ?> <?php echo e($usuario->apellido1); ?> <?php echo e($usuario->apellido2); ?>

                    </td>
                    <td data-label="Email"><?php echo e($usuario->email); ?></td>
                    <td data-label="Cuenta"><?php echo e(ucfirst($usuario->tipo_cuenta ?? 'cliente')); ?></td>
                    <td data-label="Registro"><?php echo e(ucfirst($usuario->estado_registro ?? 'aprobado')); ?></td>
                    <td data-label="Admin">
                        <span class="estado <?php echo e($usuario->es_admin ? 'activo' : 'inactivo'); ?>">
                            <?php echo e($usuario->es_admin ? 'Sí' : 'No'); ?>

                        </span>
                    </td>
                    <td data-label="Estado">
                        <span class="estado <?php echo e((int) $usuario->estado === 1 ? 'activo' : 'inactivo'); ?>">
                            <?php echo e((int) $usuario->estado === 1 ? 'Activo' : 'Inactivo'); ?>

                        </span>
                    </td>
                    <td data-label="Acciones" class="acciones">
                        <a class="btn btn-secondary" href="<?php echo e(route('admin.usuarios.edit', $usuario)); ?>">Editar</a>
                        <form method="POST" action="<?php echo e(route('admin.usuarios.destroy', $usuario)); ?>" class="delete-form">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar a <?php echo e(addslashes($usuario->nombre.' '.$usuario->apellido1)); ?>?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="empty">No hay usuarios registrados.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </section>

    <?php if($usuarios->hasPages()): ?>
        <nav class="paginacion" aria-label="Paginacion de usuarios">
            <div class="pagination-summary">
                Mostrando <strong><?php echo e($usuarios->firstItem()); ?></strong>
                a <strong><?php echo e($usuarios->lastItem()); ?></strong>
                de <strong><?php echo e($usuarios->total()); ?></strong> resultados
            </div>

            <div class="pagination-controls">
                <?php if($usuarios->onFirstPage()): ?>
                    <span class="pagination-arrow disabled" aria-hidden="true">‹</span>
                <?php else: ?>
                    <a class="pagination-arrow" href="<?php echo e($usuarios->previousPageUrl()); ?>" rel="prev" aria-label="Pagina anterior">‹</a>
                <?php endif; ?>

                <?php for($page = 1; $page <= $usuarios->lastPage(); $page++): ?>
                    <?php if($page === $usuarios->currentPage()): ?>
                        <span class="pagination-page active" aria-current="page"><?php echo e($page); ?></span>
                    <?php else: ?>
                        <a class="pagination-page" href="<?php echo e($usuarios->url($page)); ?>" aria-label="Ir a la pagina <?php echo e($page); ?>"><?php echo e($page); ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if($usuarios->hasMorePages()): ?>
                    <a class="pagination-arrow" href="<?php echo e($usuarios->nextPageUrl()); ?>" rel="next" aria-label="Pagina siguiente">›</a>
                <?php else: ?>
                    <span class="pagination-arrow disabled" aria-hidden="true">›</span>
                <?php endif; ?>
            </div>
        </nav>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\0616\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/admin/usuarios/index.blade.php ENDPATH**/ ?>
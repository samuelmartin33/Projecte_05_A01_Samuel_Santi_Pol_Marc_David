<?php $__env->startSection('title', 'Admin | Detalle empresa'); ?>

<?php $__env->startSection('content'); ?>
    <header class="admin-header">
        <div>
            <h1>Solicitud de empresa</h1>
            <p>Revisa los datos del promotor antes de aprobar o rechazar.</p>
        </div>
        <a class="btn btn-secondary" href="<?php echo e(route('admin.empresas.index')); ?>">← Volver al listado</a>
    </header>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem;">

        
        <section class="card admin-panel-section">
            <div class="panel-header"><h2>Datos del responsable</h2></div>
            <table class="admin-table" style="margin:0">
                <tbody>
                    <tr>
                        <th style="width:40%">Nombre completo</th>
                        <td><?php echo e($usuario->nombre); ?> <?php echo e($usuario->apellido1); ?> <?php echo e($usuario->apellido2); ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><a href="mailto:<?php echo e($usuario->email); ?>"><?php echo e($usuario->email); ?></a></td>
                    </tr>
                    <tr>
                        <th>Teléfono</th>
                        <td><?php echo e($usuario->telefono ?? '—'); ?></td>
                    </tr>
                    <tr>
                        <th>Fecha nacimiento</th>
                        <td><?php echo e($usuario->fecha_nacimiento ? \Carbon\Carbon::parse($usuario->fecha_nacimiento)->format('d/m/Y') : '—'); ?></td>
                    </tr>
                    <tr>
                        <th>Registrado el</th>
                        <td><?php echo e(\Carbon\Carbon::parse($usuario->fecha_creacion)->format('d/m/Y H:i')); ?></td>
                    </tr>
                    <tr>
                        <th>Estado</th>
                        <td>
                            <?php if($usuario->estado_registro === 'pendiente'): ?>
                                <span class="badge" style="background:#f59e0b;color:#fff;padding:3px 10px;border-radius:999px;font-size:12px;">Pendiente</span>
                            <?php elseif($usuario->estado_registro === 'aprobado'): ?>
                                <span class="badge badge-success">Aprobada</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Rechazada</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        
        <section class="card admin-panel-section">
            <div class="panel-header"><h2>Datos de la empresa</h2></div>
            <?php if($usuario->empresa): ?>
                <?php $emp = $usuario->empresa; ?>
                <table class="admin-table" style="margin:0">
                    <tbody>
                        <tr>
                            <th style="width:40%">Nombre empresa</th>
                            <td><?php echo e($emp->nombre_empresa ?? '—'); ?></td>
                        </tr>
                        <tr>
                            <th>NIF / CIF</th>
                            <td><?php echo e($emp->nif_cif ?? '—'); ?></td>
                        </tr>
                        <tr>
                            <th>Tipo de promotor</th>
                            <td>
                                <?php
                                    $tiposPromotor = [
                                        'sala_club'  => 'Sala / Club nocturno',
                                        'promotora'  => 'Promotora de eventos',
                                        'festival'   => 'Festival',
                                        'artista'    => 'Artista / DJ',
                                        'autonomo'   => 'Autónomo',
                                        'otro'       => 'Otro',
                                    ];
                                ?>
                                <?php echo e($tiposPromotor[$emp->tipo_promotor] ?? ($emp->tipo_promotor ?? '—')); ?>

                            </td>
                        </tr>
                        <tr>
                            <th>Sitio web</th>
                            <td>
                                <?php if($emp->sitio_web): ?>
                                    <a href="<?php echo e($emp->sitio_web); ?>" target="_blank" rel="noopener"><?php echo e($emp->sitio_web); ?></a>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Descripción</th>
                            <td><?php echo e($emp->descripcion ?? '—'); ?></td>
                        </tr>
                        <tr>
                            <th>Perfil fiscal</th>
                            <td>
                                <?php if($emp->perfil_fiscal_completo): ?>
                                    <span class="badge badge-success">Completo</span>
                                <?php else: ?>
                                    <span class="badge" style="background:#64748b;color:#fff;padding:3px 10px;border-radius:999px;font-size:12px;">Pendiente</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="panel-empty">
                    <p class="text-muted">Esta cuenta no tiene registro de empresa asociado.</p>
                </div>
            <?php endif; ?>
        </section>

    </div>

    
    <?php if($usuario->estado_registro === 'pendiente'): ?>
        <section class="card admin-panel-section">
            <div class="panel-header"><h2>Acciones</h2></div>
            <div style="display:flex;gap:12px;padding:12px 0 4px;">
                <form method="POST" action="<?php echo e(route('admin.empresas.aprobar', $usuario->id)); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-success"
                        onclick="return confirm('¿Aprobar la cuenta de <?php echo e(addslashes($usuario->nombre.' '.$usuario->apellido1)); ?>?')">
                        ✔ Aprobar empresa
                    </button>
                </form>
                <form method="POST" action="<?php echo e(route('admin.empresas.rechazar', $usuario->id)); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('¿Rechazar la solicitud de <?php echo e(addslashes($usuario->nombre.' '.$usuario->apellido1)); ?>?')">
                        ✖ Rechazar solicitud
                    </button>
                </form>
            </div>
        </section>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\LARAVEL\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/admin/empresas/show.blade.php ENDPATH**/ ?>
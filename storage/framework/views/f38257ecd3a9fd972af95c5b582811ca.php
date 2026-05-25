<?php $__env->startSection('title', 'Admin | Empresas'); ?>

<?php $__env->startSection('content'); ?>


<div id="modalAprobar"
     style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.7);backdrop-filter:blur(4px);align-items:center;justify-content:center;">
  <div style="background:#0f172a;border:1px solid rgba(168,85,247,0.3);border-radius:16px;padding:32px;max-width:440px;width:90%;box-shadow:0 24px 60px rgba(0,0,0,0.6);">
    <div style="width:52px;height:52px;border-radius:50%;background:rgba(16,185,129,0.15);border:1px solid rgba(16,185,129,0.4);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:22px;">✔</div>
    <h2 style="font-family:'Anton',sans-serif;font-size:22px;color:#f1f5f9;text-align:center;margin:0 0 8px;letter-spacing:0.02em;">¿Aprobar esta empresa?</h2>
    <p style="font-family:'Archivo Narrow',sans-serif;font-size:14px;color:rgba(241,245,249,0.6);text-align:center;margin:0 0 6px;">
      Empresa: <strong id="modalAprobarNombre" style="color:#f1f5f9;">—</strong>
    </p>
    <p style="font-family:'Archivo Narrow',sans-serif;font-size:12px;color:rgba(241,245,249,0.4);text-align:center;margin:0 0 28px;">
      La cuenta quedará activa y el promotor recibirá acceso inmediato.
    </p>
    <div style="display:flex;gap:12px;justify-content:center;">
      <button onclick="cerrarModalAprobar()"
              style="padding:10px 24px;background:rgba(241,245,249,0.06);border:1px solid rgba(241,245,249,0.15);border-radius:999px;color:#94a3b8;font-family:'Archivo Narrow',sans-serif;font-size:13px;text-transform:uppercase;letter-spacing:0.06em;cursor:pointer;">
        Cancelar
      </button>
      <form id="formAprobar" method="POST" action="" style="display:inline;">
        <?php echo csrf_field(); ?>
        <button type="submit"
                style="padding:10px 28px;background:linear-gradient(135deg,#059669,#10b981);border:none;border-radius:999px;color:#fff;font-family:'Anton',sans-serif;font-size:14px;letter-spacing:0.04em;cursor:pointer;box-shadow:0 4px 16px rgba(16,185,129,0.35);">
          Confirmar aprobación
        </button>
      </form>
    </div>
  </div>
</div>
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
                                    <a href="<?php echo e(route('admin.empresas.show', $empresa->id)); ?>" class="btn btn-secondary btn-sm">
                                        Ver detalle
                                    </a>
                                    
                                    <button type="button" class="btn btn-success btn-sm"
                                        onclick="abrirModalAprobar('<?php echo e(route('admin.empresas.aprobar', $empresa->id)); ?>', '<?php echo e(addslashes($empresa->nombre.' '.$empresa->apellido1)); ?>')">
                                        Aprobar
                                    </button>
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
<script>
function abrirModalAprobar(url, nombre) {
    document.getElementById('modalAprobarNombre').textContent = nombre;
    document.getElementById('formAprobar').action = url;
    document.getElementById('modalAprobar').style.display = 'flex';
}
function cerrarModalAprobar() {
    document.getElementById('modalAprobar').style.display = 'none';
}
/* Cerrar modal al hacer clic en el fondo oscuro */
document.getElementById('modalAprobar').onclick = function(e) {
    if (e.target === this) cerrarModalAprobar();
};
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\LARAVEL\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/admin/empresas/index.blade.php ENDPATH**/ ?>
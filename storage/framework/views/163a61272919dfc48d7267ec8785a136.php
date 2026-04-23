<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin — VIBEZ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #F5F3FF; color: #1F2937; min-height: 100vh; }

        .topbar {
            background: linear-gradient(135deg, #7C3AED, #5B21B6);
            padding: 0 2rem;
            display: flex; align-items: center; justify-content: space-between;
            height: 56px; position: sticky; top: 0; z-index: 100;
            box-shadow: 0 2px 12px rgba(91,33,182,0.25);
        }
        .topbar-brand { font-size: 1.4rem; font-weight: 900; color: #fff; letter-spacing: -0.02em; }
        .topbar-right { display: flex; align-items: center; gap: 1.25rem; }
        .topbar-user  { font-size: 0.82rem; color: rgba(255,255,255,0.8); }
        .btn-top {
            background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3);
            color: #fff; padding: 6px 16px; border-radius: 8px;
            font-size: 0.8rem; font-weight: 600; cursor: pointer;
            font-family: inherit; transition: background 0.2s;
            text-decoration: none; display: inline-block;
        }
        .btn-top:hover { background: rgba(255,255,255,0.25); }

        .main { max-width: 1050px; margin: 0 auto; padding: 2rem 1.5rem 4rem; }

        .page-title    { font-size: 1.65rem; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 0.25rem; }
        .page-subtitle { font-size: 0.9rem; color: #6B7280; margin-bottom: 2rem; }

        .flash { padding: 0.85rem 1.2rem; border-radius: 10px; font-size: 0.9rem;
                 font-weight: 500; margin-bottom: 1.5rem; border-left: 4px solid; }
        .flash-success { background: #F0FDF4; border-color: #22C55E; color: #166534; }
        .flash-error   { background: #FEF2F2; border-color: #EF4444; color: #991B1B; }
        .flash-warning { background: #FFFBEB; border-color: #F59E0B; color: #92400E; }

        .tabs { display: flex; border-bottom: 2px solid #E5E7EB; margin-bottom: 1.75rem; }
        .tab-btn {
            padding: 0.65rem 1.4rem; border: none; background: transparent;
            font-family: inherit; font-size: 0.88rem; font-weight: 600;
            color: #6B7280; cursor: pointer; border-bottom: 2px solid transparent;
            margin-bottom: -2px; transition: color 0.2s, border-color 0.2s;
        }
        .tab-btn.active { color: #7C3AED; border-bottom-color: #7C3AED; }
        .tab-btn:hover:not(.active) { color: #374151; }
        .tab-badge {
            display: inline-flex; align-items: center; justify-content: center;
            background: #7C3AED; color: #fff; border-radius: 999px;
            font-size: 0.68rem; font-weight: 700; min-width: 18px; height: 18px;
            padding: 0 5px; margin-left: 6px;
        }
        .tab-badge.grey { background: #D1D5DB; color: #374151; }

        .tab-panel { display: none; }
        .tab-panel.active { display: block; }

        .card { background: #fff; border-radius: 14px; border: 1px solid #E5E7EB;
                overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.06); }

        .empty-state { padding: 3rem; text-align: center; color: #9CA3AF; font-size: 0.9rem; }

        table { width: 100%; border-collapse: collapse; }
        thead th {
            background: #F9FAFB; padding: 0.7rem 1rem; text-align: left;
            font-size: 0.72rem; font-weight: 700; color: #6B7280;
            text-transform: uppercase; letter-spacing: 0.06em;
            border-bottom: 1px solid #E5E7EB;
        }
        tbody tr { border-bottom: 1px solid #F3F4F6; transition: background 0.15s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #FAFAFA; }
        td { padding: 0.85rem 1rem; font-size: 0.875rem; color: #374151; vertical-align: middle; }

        .user-name  { font-weight: 600; color: #111827; }
        .user-email { font-size: 0.8rem; color: #6B7280; }
        .muted      { color: #9CA3AF; font-size: 0.78rem; }

        .badge-ok  { display:inline-flex;align-items:center;gap:4px;padding:3px 10px;
                     border-radius:999px;font-size:0.72rem;font-weight:700;
                     background:#DCFCE7;color:#166534; }

        .btn-verificar {
            padding: 6px 16px; border-radius: 8px; font-family: inherit;
            font-size: 0.8rem; font-weight: 700; cursor: pointer; border: none;
            background: linear-gradient(135deg, #7C3AED, #5B21B6); color: #fff;
            transition: opacity 0.2s, transform 0.1s;
        }
        .btn-verificar:hover  { opacity: 0.85; transform: translateY(-1px); }
        .btn-verificar:active { transform: translateY(0); }
    </style>
</head>
<body>

<header class="topbar">
    <span class="topbar-brand">VIBEZ <span style="font-weight:400;font-size:0.85rem;opacity:.7">/ Admin</span></span>
    <div class="topbar-right">
        <span class="topbar-user"><?php echo e(Auth::user()->nombre); ?> (admin)</span>
        <a href="<?php echo e(route('index')); ?>" class="btn-top">Ir al index</a>
        <form method="POST" action="<?php echo e(route('api.logout')); ?>" style="margin:0">
            <?php echo csrf_field(); ?>
            <button class="btn-top" type="submit">Cerrar sesión</button>
        </form>
    </div>
</header>

<main class="main">

    <h1 class="page-title">Verificación de usuarios</h1>
    <p class="page-subtitle">Gestiona las solicitudes de registro pendientes de verificación.</p>

    <?php if(session('success')): ?>
        <div class="flash flash-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="flash flash-error"><?php echo e(session('error')); ?></div>
    <?php endif; ?>
    <?php if(session('warning')): ?>
        <div class="flash flash-warning"><?php echo e(session('warning')); ?></div>
    <?php endif; ?>

    <div class="tabs">
        <button class="tab-btn active" onclick="switchTab('pendientes', this)">
            Pendientes de verificación
            <?php if($pendientes->count()): ?>
                <span class="tab-badge"><?php echo e($pendientes->count()); ?></span>
            <?php endif; ?>
        </button>
        <button class="tab-btn" onclick="switchTab('verificados', this)">
            Verificados
            <span class="tab-badge grey"><?php echo e($verificados->count()); ?></span>
        </button>
    </div>

    
    <div id="tab-pendientes" class="tab-panel active">
        <div class="card">
            <?php if($pendientes->isEmpty()): ?>
                <div class="empty-state">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                         stroke="#9CA3AF" stroke-width="1.5" style="margin-bottom:.75rem;display:block;margin-inline:auto">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    No hay solicitudes pendientes de verificación.
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Usuario</th>
                            <th>Teléfono</th>
                            <th>F. nacimiento</th>
                            <th>Registrado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $pendientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="muted"><?php echo e($u->id); ?></td>
                            <td>
                                <div class="user-name"><?php echo e($u->nombre); ?> <?php echo e($u->apellido1); ?> <?php echo e($u->apellido2); ?></div>
                                <div class="user-email"><?php echo e($u->email); ?></div>
                            </td>
                            <td><?php echo e($u->telefono ?? '—'); ?></td>
                            <td><?php echo e($u->fecha_nacimiento
                                    ? \Carbon\Carbon::parse($u->fecha_nacimiento)->format('d/m/Y')
                                    : '—'); ?></td>
                            <td><?php echo e(\Carbon\Carbon::parse($u->fecha_creacion)->format('d/m/Y H:i')); ?></td>
                            <td>
                                <form method="POST" action="<?php echo e(route('admin.verificar', $u->id)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button class="btn-verificar" type="submit"
                                        onclick="return confirm('¿Verificar la cuenta de <?php echo e(addslashes($u->nombre)); ?>? Se le enviará un email de bienvenida.')">
                                        Verificar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    
    <div id="tab-verificados" class="tab-panel">
        <div class="card">
            <?php if($verificados->isEmpty()): ?>
                <div class="empty-state">Ningún usuario verificado todavía.</div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Usuario</th>
                            <th>Teléfono</th>
                            <th>Estado</th>
                            <th>Verificado</th>
                            <th>Último acceso</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $verificados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="muted"><?php echo e($u->id); ?></td>
                            <td>
                                <div class="user-name"><?php echo e($u->nombre); ?> <?php echo e($u->apellido1); ?></div>
                                <div class="user-email"><?php echo e($u->email); ?></div>
                            </td>
                            <td><?php echo e($u->telefono ?? '—'); ?></td>
                            <td><span class="badge-ok">✓ Verificado</span></td>
                            <td><?php echo e(\Carbon\Carbon::parse($u->fecha_actualizacion)->format('d/m/Y H:i')); ?></td>
                            <td><?php echo e($u->ultimo_acceso
                                    ? \Carbon\Carbon::parse($u->ultimo_acceso)->format('d/m/Y H:i')
                                    : '—'); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

</main>

<script>
function switchTab(name, btn) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    btn.classList.add('active');
}
</script>

</body>
</html>
<?php /**PATH C:\wamp64\www\DAW2\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/admin/usuarios.blade.php ENDPATH**/ ?>
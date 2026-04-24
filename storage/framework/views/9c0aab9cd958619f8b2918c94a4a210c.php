<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Admin Dashboard'); ?></title>
    <?php if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/admin-eventos.css']); ?>
    <?php else: ?>
        <link rel="stylesheet" href="<?php echo e(asset('css/admin-eventos.css')); ?>">
    <?php endif; ?>
</head>
<body>
<div class="dashboard-wrap">
    <aside class="dashboard-sidebar" id="sidebar">
        <div class="brand">
            <span>VIBEZ</span>
        </div>

        
        <button class="menu-toggle" id="menuToggle" aria-label="Abrir menú" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav class="menu" id="mainMenu">
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="menu-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">Inicio</a>
            <a href="<?php echo e(route('admin.eventos.index')); ?>" class="menu-link <?php echo e(request()->routeIs('admin.eventos.*') ? 'active' : ''); ?>">Eventos</a>
            <span class="menu-link disabled">Usuarios (proximamente)</span>
            <span class="menu-link disabled">Empresas (proximamente)</span>
            <span class="menu-link disabled">Pedidos (proximamente)</span>
            <span class="menu-link disabled">Pagos (proximamente)</span>
        </nav>
    </aside>

    <div class="dashboard-main">
        <main class="admin-shell">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))): ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/admin-eventos.js']); ?>
<?php else: ?>
    <script src="<?php echo e(asset('js/admin-eventos.js')); ?>"></script>
<?php endif; ?>


</body>
</html>
<?php /**PATH C:\wamp64\www\DAW2\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/admin/layouts/dashboard.blade.php ENDPATH**/ ?>
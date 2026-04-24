<?php $__env->startSection('title', 'Dashboard — VIBEZ'); ?>


<?php $__env->startSection('content'); ?>


<div class="auth-bg">
    <div class="aurora-blob"></div>
    <div class="aurora-blob"></div>
    <div class="aurora-blob"></div>
    <div class="aurora-blob"></div>
</div>

<div class="index-wrapper page-transition">

    
    <div class="glass-card">

        
        <div class="index-avatar">
            <?php echo e(strtoupper(mb_substr(auth()->user()->nombre, 0, 1))); ?>

        </div>

        
        <h1 class="index-greeting">
            Hola, <span><?php echo e(auth()->user()->nombre); ?></span>
        </h1>

        
        <div class="session-badge">Sesión activa</div>

        
        <div class="user-info">
            <div class="info-row">
                <span class="info-label">Nombre</span>
                <span><?php echo e(auth()->user()->nombre); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span><?php echo e(auth()->user()->email); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">ID</span>
                <span>#<?php echo e(auth()->user()->id); ?></span>
            </div>
            <?php if(auth()->user()->es_admin): ?>
            <div class="info-row">
                <span class="info-label">Rol</span>
                <span class="role-admin">⚡ Administrador</span>
            </div>
            <?php endif; ?>
        </div>

        <p class="index-note">
            Autenticación verificada correctamente.<br>
            El circuito <strong>login → sesión → dashboard</strong> funciona.
        </p>

        
        <button class="btn-logout" id="logoutBtn">
            Cerrar sesión
        </button>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/index.js']); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/index.blade.php ENDPATH**/ ?>
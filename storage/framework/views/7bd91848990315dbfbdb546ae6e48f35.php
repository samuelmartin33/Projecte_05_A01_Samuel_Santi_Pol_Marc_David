<?php $__env->startSection('title', 'Panel Empresa — VIBEZ'); ?>

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
            Panel de Empresa
        </h1>
        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">
            Bienvenido, <strong><?php echo e(auth()->user()->nombre); ?></strong>
        </p>

        
        <?php if(auth()->user()->isEmpresa() && auth()->user()->empresa): ?>
            <div style="
                background: rgba(124,58,237,0.12);
                border: 1px solid rgba(124,58,237,0.3);
                border-radius: 12px;
                padding: 1rem 1.25rem;
                margin-bottom: 1.5rem;
                font-size: 0.85rem;
                color: #c4b5fd;
            ">
                Empresa:
                <strong><?php echo e(auth()->user()->empresa->nombre_empresa); ?></strong>
                <?php if(auth()->user()->empresa->nif_cif): ?>
                    &nbsp;·&nbsp; NIF/CIF: <?php echo e(auth()->user()->empresa->nif_cif); ?>

                <?php endif; ?>
            </div>
        <?php endif; ?>

        
        <div style="
            background: rgba(255,255,255,0.04);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            color: var(--text-muted);
        ">
            Desde aquí podrás gestionar cupones, patrocinios, ofertas de trabajo y visualizar estadísticas de tu empresa.
        </div>

        <button id="logoutBtn" class="btn-primary" style="margin-top: 0.5rem;">
            Cerrar sesión
        </button>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="<?php echo e(asset('js/index.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views\empresa\dashboard.blade.php ENDPATH**/ ?>
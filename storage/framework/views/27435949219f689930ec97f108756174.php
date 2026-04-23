

<?php $__env->startSection('title', '403 | No tienes permisos de administrador'); ?>
<?php $__env->startSection('body-class', 'bg-slate-50'); ?>

<?php
    $mensaje403 = trim((string) ($exception->getMessage() ?? ''));
    if ($mensaje403 === '') {
        $mensaje403 = 'No tienes permisos de administrador';
    }
?>

<?php $__env->startSection('contenido'); ?>
<section class="relative overflow-hidden">
    <div class="absolute inset-0 -z-10 bg-gradient-to-br from-rose-100 via-orange-50 to-amber-100"></div>

    <div class="max-w-3xl mx-auto px-6 py-20 sm:py-28">
        <div class="bg-white/90 backdrop-blur rounded-3xl border border-slate-200 shadow-xl p-8 sm:p-12 text-center">
            <p class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold tracking-wide uppercase bg-rose-100 text-rose-700">
                Error de acceso
            </p>

            <h1 class="mt-5 text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                403 | <?php echo e($mensaje403); ?>

            </h1>

            <p class="mt-4 text-slate-600 text-base sm:text-lg">
                Tu cuenta no tiene permisos para entrar en esta seccion.
                Si crees que es un error, contacta con el equipo de soporte.
            </p>

            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="<?php echo e(route('home')); ?>"
                   class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 rounded-xl bg-slate-900 text-white font-semibold hover:bg-slate-800 transition-colors">
                    Volver al inicio
                </a>

                <button onclick="history.back()"
                        class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 rounded-xl border border-slate-300 text-slate-700 font-semibold hover:bg-slate-100 transition-colors">
                    Regresar
                </button>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\0616\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/errors/403.blade.php ENDPATH**/ ?>
<?php $__env->startPush('estilos'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/formularios.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('title', 'Iniciar sesión — VIBEZ'); ?>

<?php $__env->startSection('content'); ?>

<div class="min-h-screen flex">

    
    <div class="hidden md:flex md:w-5/12 lg:w-1/2 flex-col justify-between p-12 relative overflow-hidden bg-ink">

        
        <div style="position:absolute;inset:0;background-image:radial-gradient(circle,rgba(139,120,204,0.13) 1.5px,transparent 1.5px);background-size:28px 28px;pointer-events:none;z-index:0"></div>

        
        <div style="position:absolute;width:520px;height:520px;border-radius:50%;background:radial-gradient(circle,rgba(139,120,204,0.22) 0%,transparent 60%);top:-160px;right:-160px;pointer-events:none;z-index:0"></div>
        <div style="position:absolute;width:300px;height:300px;border-radius:50%;background:radial-gradient(circle,rgba(78,58,150,0.18) 0%,transparent 60%);bottom:-80px;left:-60px;pointer-events:none;z-index:0"></div>

        
        <div style="position:relative;z-index:1">
            <a href="<?php echo e(route('home')); ?>"
               class="font-display font-black text-2xl tracking-brutal text-paper hover:text-lilac transition-colors duration-100 select-none">
                VIBEZ
            </a>
        </div>

        
        <div style="position:relative;z-index:1">
            <p class="font-mono text-xs uppercase tracking-widest text-paper/35 mb-6">— Acceso a tu cuenta</p>
            <h2 class="font-display font-black uppercase tracking-tightest text-paper leading-[0.88]"
                style="font-size:clamp(2.8rem,5vw,5rem)">
                Tu escena<br>
                <span class="text-lilac" style="font-style:italic">te espera.</span>
            </h2>
            <div class="flex gap-8 mt-10">
                <div class="border-t border-paper/15 pt-3">
                    <p class="font-mono text-xs uppercase tracking-widest text-paper/30">Eventos</p>
                </div>
                <div class="border-t border-paper/15 pt-3">
                    <p class="font-mono text-xs uppercase tracking-widest text-paper/30">Trabajo</p>
                </div>
                <div class="border-t border-paper/15 pt-3">
                    <p class="font-mono text-xs uppercase tracking-widest text-paper/30">Social</p>
                </div>
            </div>
        </div>

        
        <div style="position:relative;z-index:1">
            <p class="font-mono text-xs text-paper/20">&copy; <?php echo e(date('Y')); ?> VIBEZ</p>
        </div>

    </div>

    
    <div class="flex-1 flex flex-col justify-center px-8 sm:px-12 lg:px-16 py-16"
         style="background:#F7F5FF;background-image:radial-gradient(circle,rgba(139,120,204,0.1) 1.5px,transparent 1.5px);background-size:28px 28px;">

        <div style="max-width:420px;width:100%;margin:0 auto;">

            
            <a href="<?php echo e(route('home')); ?>"
               class="md:hidden font-display font-black text-2xl tracking-brutal text-ink hover:text-lilac transition-colors duration-100 select-none block mb-10">
                VIBEZ
            </a>

            <div class="form-header">
                <h1 class="form-title">Bienvenido de nuevo</h1>
                <p class="form-subtitle">Accede a tu cuenta para continuar</p>
            </div>

            <div id="alert-global" class="alert alert-error" role="alert"></div>

            <form id="loginForm" novalidate autocomplete="off" onsubmit="iniciarSesion(event)">

                <div class="field-group">

                    <div class="field" id="field-email">
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder=" "
                            autocomplete="email"
                            inputmode="email"
                            onblur="validarEmail()"
                        >
                        <label for="email">Correo electrónico</label>
                        <span class="field-error" id="error-email" role="alert"></span>
                    </div>

                    <div class="field" id="field-password">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder=" "
                            autocomplete="current-password"
                            onblur="validarContrasena()"
                        >
                        <label for="password">Contraseña</label>
                        <button type="button" class="toggle-password" onclick="togglePassword('password', this)" aria-label="Mostrar contraseña" tabindex="-1">
                            <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <svg class="eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display:none"><path d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                        </button>
                        <span class="field-error" id="error-password" role="alert"></span>
                    </div>

                </div>

                <button type="submit" class="btn-primary" id="submitBtn" onclick="rippleBtn(event, this)">
                    <span class="btn-text">Iniciar sesión</span>
                    <span class="btn-spinner" aria-hidden="true">
                        <span class="spinner-ring"></span>
                    </span>
                </button>

            </form>

            <p class="form-switch">
                ¿No tienes cuenta? <a href="<?php echo e(route('register')); ?>">Regístrate</a>
            </p>

        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="<?php echo e(asset('js/login.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/login.blade.php ENDPATH**/ ?>
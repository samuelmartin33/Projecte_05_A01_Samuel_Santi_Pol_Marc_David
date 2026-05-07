<?php $__env->startSection('title', 'Iniciar sesión — VIBEZ'); ?>
<?php $__env->startSection('html-class', 'auth-page'); ?>
<?php $__env->startSection('body-class', 'grain'); ?>

<?php $__env->startSection('content'); ?>

<div class="auth-shell">

    
    <div class="auth-side">
        <img
            src="https://picsum.photos/seed/vibez-night-login/800/1200"
            alt=""
            class="auth-side-img"
            aria-hidden="true"
        >
        <div class="auth-side-overlay"></div>

        
        <div class="auth-side-top">
            <a href="<?php echo e(route('home')); ?>" class="auth-logo" aria-label="VIBEZ — Inicio">
                <img src="<?php echo e(asset('images/logo_vibez_white.png')); ?>" alt="VIBEZ">
                <span>VIBEZ</span>
            </a>
            <a href="<?php echo e(route('home')); ?>" class="auth-back">← Explorar</a>
        </div>

        
        <div class="auth-side-content">
            <p class="auth-kicker mono">
                <span class="kicker-line"></span>
                Tu acceso a la escena
            </p>
            <h2 class="auth-side-title display">
                Esta noche<br>
                <em>se rompe</em>
            </h2>
            <p class="auth-side-sub">
                Eventos, trabajo y comunidad para los que viven la noche de verdad.
            </p>
            <div class="auth-side-pills">
                <span class="auth-pill"><span class="dot"></span> 200+ eventos esta semana</span>
                <span class="auth-pill"><span class="dot"></span> Madrid · Barcelona · Valencia</span>
            </div>
        </div>

        <div class="auth-side-bottom">
            <span class="mono" style="font-size:10px;letter-spacing:0.18em">VIBEZ © <?php echo e(date('Y')); ?></span>
        </div>
    </div>

    
    <div class="auth-main">

        
        <div class="auth-main-topbar">
            <a href="<?php echo e(route('welcome')); ?>" class="auth-back-home">← Inicio</a>
            <a href="<?php echo e(route('welcome')); ?>" class="auth-logo" aria-label="VIBEZ — Inicio">
                <img src="<?php echo e(asset('images/logo_vibez_white.png')); ?>" alt="VIBEZ">
                <span>VIBEZ</span>
            </a>
        </div>

        <div class="deco-sticker deco-1">↯ Bienvenido</div>
        <div class="deco-numbers">06<br>25</div>

        <div class="auth-form-wrap">

            <p class="auth-step mono">— ACCESO</p>

            <h1 class="auth-title display">
                Entra a<br>
                <em>la fiesta.</em>
            </h1>

            <p class="auth-sub">
                ¿No tienes cuenta? <a href="<?php echo e(route('register')); ?>">Regístrate gratis</a>
            </p>

            
            <div id="alert-global" class="alert alert-error" role="alert"></div>

            
            <form id="loginForm" novalidate autocomplete="off" onsubmit="iniciarSesion(event)">
                <div class="auth-form">

                    
                    <div class="auth-field" id="field-email">
                        <label class="auth-label" for="email">Correo electrónico</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="tu@email.com"
                            autocomplete="email"
                            inputmode="email"
                            onblur="validarEmail()"
                        >
                        <span class="field-error" id="error-email" role="alert"></span>
                    </div>

                    
                    <div class="auth-field" id="field-password">
                        <label class="auth-label" for="password">Contraseña</label>
                        <div class="auth-input-wrap">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="••••••••"
                                autocomplete="current-password"
                                onblur="validarContrasena()"
                            >
                            <button
                                type="button"
                                class="auth-eye"
                                onclick="togglePassword('password', this)"
                                aria-label="Mostrar contraseña"
                                tabindex="-1"
                            >
                                <svg class="eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/>
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <svg class="eye-closed" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display:none">
                                    <path d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                                </svg>
                            </button>
                        </div>
                        <span class="field-error" id="error-password" role="alert"></span>
                    </div>

                    
                    <button
                        type="submit"
                        class="btn-primary auth-btn-main"
                        id="submitBtn"
                        onclick="rippleBtn(event, this)"
                    >
                        <span class="btn-text">Entrar</span>
                        <span class="btn-spinner" aria-hidden="true">
                            <span class="spinner-ring"></span>
                        </span>
                    </button>

                </div>
            </form>

            <p class="auth-fineprint">
                Al entrar aceptas nuestros <a href="#">Términos</a> y <a href="#">Política de privacidad</a>
            </p>

        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="<?php echo e(asset('js/login.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\proyectos\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/login.blade.php ENDPATH**/ ?>
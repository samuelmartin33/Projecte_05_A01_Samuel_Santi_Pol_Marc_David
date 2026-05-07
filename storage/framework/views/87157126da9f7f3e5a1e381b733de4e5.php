<?php $__env->startSection('title', 'Iniciar sesión — VIBEZ'); ?>
<?php $__env->startSection('html-class', 'auth-page'); ?>
<?php $__env->startSection('body-class', 'grain'); ?>

<?php $__env->startSection('content'); ?>

<div class="auth-shell">

    
    <aside class="auth-side">

        
        <div class="auth-side-watermark" aria-hidden="true">
            <span>VBZ</span>
        </div>

        
        <div class="auth-side-top">
            <a href="<?php echo e(route('welcome')); ?>" class="auth-logo" aria-label="VIBEZ — Inicio">
                <img src="<?php echo e(asset('images/logo_vibez_white.png')); ?>" alt="VIBEZ">
                <span class="display">VIBEZ</span>
            </a>
            <a href="<?php echo e(route('welcome')); ?>" class="mono auth-back">← Volver</a>
        </div>

        
        <div class="auth-side-content">
            <div class="mono auth-kicker">
                <span class="kicker-line"></span>
                Tu acceso a la escena
            </div>
            <h1 class="display auth-side-title">
                Esta noche<br><em>se rompe</em>.
            </h1>
            <p class="auth-side-sub">
                Eventos, trabajo y comunidad para los que viven la noche de verdad.
            </p>
            <div class="auth-side-pills">
                <span class="auth-pill"><span class="dot"></span> 200+ eventos esta semana</span>
                <span class="auth-pill">Madrid · Barcelona · Valencia</span>
            </div>
        </div>

        <div class="auth-side-bottom mono">
            VIBEZ · NIGHT EDITION <?php echo e(date('Y')); ?> · BCN
        </div>

    </aside>

    
    <main class="auth-main">

        
        <div class="deco-sticker deco-1">VIP · ACCESS</div>
        <div class="deco-sticker deco-2">★ MEMBER</div>
        <div class="deco-numbers" aria-hidden="true"><?php echo e(now()->format('d')); ?><br><?php echo e(now()->format('m')); ?></div>

        <div class="auth-form-wrap">

            <p class="mono auth-step">01 · Acceso</p>

            <h2 class="display auth-title">
                Entra a la <em>fiesta</em>.
            </h2>

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
                                <svg class="eye-closed" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                                </svg>
                            </button>
                        </div>
                        <span class="field-error" id="error-password" role="alert"></span>
                    </div>

                    
                    <div class="auth-row-between">
                        <label class="auth-check">
                            <input type="checkbox" name="remember" checked>
                            <span class="auth-check-box"></span>
                            <span>Recuérdame 30 días</span>
                        </label>
                        <a href="#" class="auth-link-small">¿Olvidaste tu contraseña?</a>
                    </div>

                    
                    <button
                        type="submit"
                        class="btn-primary auth-btn-main"
                        id="submitBtn"
                        onclick="rippleBtn(event, this)"
                    >
                        <span class="btn-text">Entrar a VIBEZ →</span>
                        <span class="btn-spinner" aria-hidden="true">
                            <span class="spinner-ring"></span>
                        </span>
                    </button>

                </div>
            </form>

            
            <div class="auth-divider"><span class="mono">o continúa con</span></div>
            <div class="auth-socials">
                
                <div class="google-btn-wrapper" id="google-btn-wrapper">
                    <div id="google-signin-btn" data-client-id="<?php echo e(config('services.google.client_id')); ?>"></div>
                </div>
                <button type="button" class="auth-social" onclick="alert('Apple Sign-In próximamente')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16.4 1.5c.1 1.2-.4 2.4-1.2 3.3-.9 1-2.2 1.7-3.4 1.6-.1-1.2.5-2.4 1.2-3.2.9-1 2.3-1.7 3.4-1.7zm3.6 16.4c-.7 1.6-1 2.3-1.9 3.7-1.2 1.9-2.9 4.3-5 4.3-1.9 0-2.4-1.2-5-1.2-2.5 0-3.1 1.2-5 1.2-2.1 0-3.7-2.2-4.9-4.1C-1 18.3-1.4 13.5.7 10.6c1.4-2 3.7-3.2 5.8-3.2 2.2 0 3.6 1.2 5.4 1.2 1.8 0 2.9-1.2 5.4-1.2 1.9 0 3.9 1 5.3 2.8-4.7 2.6-3.9 9.3-2.6 7.7z"/></svg>
                    Apple
                </button>
                <button type="button" class="auth-social" onclick="alert('Facebook Sign-In próximamente')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22 12a10 10 0 1 0-11.6 9.9V14.9H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.7-3.9 1.1 0 2.2.2 2.2.2v2.4h-1.2c-1.2 0-1.6.8-1.6 1.6V12h2.7l-.4 2.9h-2.3v6.9A10 10 0 0 0 22 12z"/></svg>
                    Facebook
                </button>
            </div>

            <p class="auth-fineprint mono">
                Al entrar aceptas nuestros <a href="#">Términos</a> y la <a href="#">Política de privacidad</a>
            </p>

        </div>

    </main>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script src="<?php echo e(asset('js/login.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/login.blade.php ENDPATH**/ ?>
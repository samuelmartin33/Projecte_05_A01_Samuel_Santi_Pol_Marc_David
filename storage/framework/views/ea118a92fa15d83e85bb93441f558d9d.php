<?php $__env->startPush('estilos'); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('title', 'Crear cuenta — VIBEZ'); ?>
<?php $__env->startSection('html-class', 'auth-page'); ?>
<?php $__env->startSection('body-class', 'grain'); ?>

<?php $__env->startSection('content'); ?>

<div class="auth-shell">

    
    <div class="auth-side">
        <img
            src="https://picsum.photos/seed/vibez-night-reg/800/1200"
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
            <a href="<?php echo e(route('login')); ?>" class="auth-back">← Entrar</a>
        </div>

        
        <div class="auth-side-content">
            <p class="auth-kicker mono">
                <span class="kicker-line"></span>
                Tu viaje empieza aquí
            </p>
            <h2 class="auth-side-title display">
                Únete<br>
                <em>al ritual.</em>
            </h2>
            <ul class="auth-side-bullets">
                <li>
                    <span class="bullet-num">01</span>
                    Descubre eventos únicos
                </li>
                <li>
                    <span class="bullet-num">02</span>
                    Conecta con la comunidad
                </li>
                <li>
                    <span class="bullet-num">03</span>
                    Trabaja en lo que te apasiona
                </li>
            </ul>
        </div>

        <div class="auth-side-bottom">
            <span class="mono" style="font-size:10px;letter-spacing:0.18em">VIBEZ © <?php echo e(date('Y')); ?></span>
        </div>
    </div>

    
    <div class="auth-main">

        <div class="deco-sticker deco-2">★ Nueva cuenta</div>
        <div class="deco-numbers">07<br>01</div>

        <div class="auth-form-wrap auth-form-wide">

            <p class="auth-step mono">— REGISTRO</p>

            
            <div id="formHeader">
                <h1 class="auth-title display">
                    Crea tu<br>
                    <em>cuenta.</em>
                </h1>
                <p class="auth-sub">
                    ¿Ya tienes cuenta? <a href="<?php echo e(route('login')); ?>">Inicia sesión</a>
                </p>
            </div>

            
            <div id="alert-global" class="alert alert-error" role="alert"></div>

            
            <div class="auth-tabs">
                <button
                    type="button"
                    class="auth-tab active"
                    data-tipo="cliente"
                    onclick="seleccionarTab('cliente', this)"
                >
                    <span class="auth-tab-num">01</span>
                    <span class="auth-tab-label">Soy raver</span>
                    <span class="auth-tab-sub">Acceso inmediato</span>
                </button>
                <button
                    type="button"
                    class="auth-tab"
                    data-tipo="empresa"
                    onclick="seleccionarTab('empresa', this)"
                >
                    <span class="auth-tab-num">02</span>
                    <span class="auth-tab-label">Soy promotor</span>
                    <span class="auth-tab-sub">Requiere aprobación</span>
                </button>
            </div>

            
            <form id="registerForm" novalidate autocomplete="off" onsubmit="registrar(event)">
                <div class="auth-form">

                    
                    <div class="auth-field" id="field-nombre">
                        <label class="auth-label" for="nombre">Nombre</label>
                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            placeholder="Tu nombre"
                            autocomplete="given-name"
                            inputmode="text"
                            onblur="validarNombre()"
                        >
                        <span class="field-error" id="error-nombre" role="alert"></span>
                    </div>

                    
                    <div class="auth-grid-2">
                        <div class="auth-field" id="field-apellido1">
                            <label class="auth-label" for="apellido1">Primer apellido</label>
                            <input
                                type="text"
                                id="apellido1"
                                name="apellido1"
                                placeholder="Primer apellido"
                                autocomplete="family-name"
                                inputmode="text"
                                onblur="validarApellido1()"
                            >
                            <span class="field-error" id="error-apellido1" role="alert"></span>
                        </div>

                        <div class="auth-field" id="field-apellido2">
                            <label class="auth-label" for="apellido2">Segundo apellido</label>
                            <input
                                type="text"
                                id="apellido2"
                                name="apellido2"
                                placeholder="Segundo apellido"
                                autocomplete="additional-name"
                                inputmode="text"
                                onblur="validarApellido2()"
                            >
                            <span class="field-error" id="error-apellido2" role="alert"></span>
                        </div>
                    </div>

                    
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

                    
                    <div class="auth-grid-2">
                        <div class="auth-field" id="field-password">
                            <label class="auth-label" for="password">Contraseña</label>
                            <div class="auth-input-wrap">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="Mínimo 8 caracteres"
                                    autocomplete="new-password"
                                    onblur="validarContrasena()"
                                    oninput="actualizarFortaleza(this.value)"
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
                            
                            <div class="auth-strength">
                                <div class="auth-strength-track">
                                    <div class="auth-strength-bar" id="strength-bar"></div>
                                </div>
                                <span class="auth-strength-label" id="strength-label"></span>
                            </div>
                            <span class="field-error" id="error-password" role="alert"></span>
                        </div>

                        <div class="auth-field" id="field-password_confirmation">
                            <label class="auth-label" for="password_confirmation">Confirmar contraseña</label>
                            <div class="auth-input-wrap">
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder="Repite la contraseña"
                                    autocomplete="new-password"
                                    onblur="validarConfirmacion()"
                                >
                                <button
                                    type="button"
                                    class="auth-eye"
                                    onclick="togglePassword('password_confirmation', this)"
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
                            <span class="field-error" id="error-password_confirmation" role="alert"></span>
                        </div>
                    </div>

                    
                    
                    <div id="field-tipo_cuenta" style="display:none" aria-hidden="true">
                        <select
                            id="tipo_cuenta"
                            name="tipo_cuenta"
                            onchange="cambiarTipoCuenta(this)"
                            onblur="validarTipoCuenta()"
                        >
                            <option value="" disabled selected hidden></option>
                            <option value="cliente">Cliente</option>
                            <option value="empresa">Empresa</option>
                        </select>
                    </div>
                    
                    <span class="field-error" id="error-tipo_cuenta" role="alert"></span>
                    <p id="hint-tipo_cuenta" style="font-family:'Archivo',sans-serif;font-size:12px;margin-top:-4px;min-height:18px"></p>

                    
                    <div class="auth-grid-2">
                        <div class="auth-field" id="field-fecha_nacimiento">
                            <label class="auth-label" for="fecha_nacimiento">Fecha de nacimiento</label>
                            <input
                                type="text"
                                id="fecha_nacimiento"
                                name="fecha_nacimiento"
                                placeholder="DD/MM/AAAA"
                                readonly
                            >
                            <span class="field-error" id="error-fecha_nacimiento" role="alert"></span>
                        </div>

                        <div class="auth-field" id="field-telefono">
                            <label class="auth-label" for="telefono">Teléfono</label>
                            <input
                                type="tel"
                                id="telefono"
                                name="telefono"
                                placeholder="+34 600 000 000"
                                autocomplete="tel"
                                inputmode="tel"
                                onblur="validarTelefono()"
                            >
                            <span class="field-error" id="error-telefono" role="alert"></span>
                        </div>
                    </div>

                    
                    <div id="btnRow">
                        <button
                            type="submit"
                            class="btn-primary auth-btn-main"
                            id="submitBtn"
                            onclick="rippleBtn(event, this)"
                        >
                            <span class="btn-text">Crear cuenta</span>
                            <span class="btn-spinner" aria-hidden="true">
                                <span class="spinner-ring"></span>
                            </span>
                        </button>

                        <div class="auth-divider"><span>o continúa con</span></div>

                        <div class="google-btn-wrapper">
                            <div id="google-signin-btn" data-client-id="<?php echo e(config('services.google.client_id')); ?>"></div>
                        </div>
                    </div>

                </div>
            </form>

            <p class="auth-fineprint">
                Al registrarte aceptas nuestros <a href="#">Términos</a> y <a href="#">Política de privacidad</a>
            </p>

        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script src="<?php echo e(asset('js/register.js')); ?>"></script>
    <script>
        /* Inicializar tabs: el tab "cliente" arranca activo, sincronizar el select */
        (function () {
            var select = document.getElementById('tipo_cuenta');
            if (select) { select.value = 'cliente'; cambiarTipoCuenta(select); }
        })();

        /* Cambia el tab activo y sincroniza el select oculto */
        function seleccionarTab(tipo, elTab) {
            /* Actualizar estilos de tabs */
            var tabs = document.querySelectorAll('.auth-tab');
            for (var i = 0; i < tabs.length; i++) tabs[i].classList.remove('active');
            elTab.classList.add('active');

            /* Sincronizar select oculto y disparar la lógica de hint */
            var select = document.getElementById('tipo_cuenta');
            select.value = tipo;
            cambiarTipoCuenta(select);
        }

        /* Actualiza la barra de fortaleza de la contraseña */
        function actualizarFortaleza(valor) {
            var barra    = document.getElementById('strength-bar');
            var etiqueta = document.getElementById('strength-label');
            if (!barra) return;
            var f = 0;
            if (valor.length >= 8)          f += 25;
            if (/[A-Z]/.test(valor))         f += 25;
            if (/[0-9]/.test(valor))         f += 25;
            if (/[^A-Za-z0-9]/.test(valor)) f += 25;
            barra.style.width = f + '%';
            var colores  = { 25: '#ef4444', 50: '#f59e0b', 75: '#3b82f6', 100: '' };
            var niveles  = { 25: 'Débil',   50: 'Regular', 75: 'Fuerte',  100: 'Excelente' };
            barra.style.background = colores[f] || '';
            etiqueta.textContent   = f > 0 ? (niveles[f] || '') : '';
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/register.blade.php ENDPATH**/ ?>
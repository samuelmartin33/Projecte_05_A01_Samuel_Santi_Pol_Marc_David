<?php $__env->startPush('estilos'); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="<?php echo e(asset('css/formularios.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('title', 'Crear cuenta — VIBEZ'); ?>

<?php $__env->startSection('content'); ?>

<div class="min-h-screen flex">

    
    <div class="hidden md:flex md:w-5/12 lg:w-2/5 flex-col justify-between p-12 relative overflow-hidden bg-ink">

        <div style="position:absolute;inset:0;background-image:radial-gradient(circle,rgba(139,120,204,0.13) 1.5px,transparent 1.5px);background-size:28px 28px;pointer-events:none;z-index:0"></div>
        <div style="position:absolute;width:520px;height:520px;border-radius:50%;background:radial-gradient(circle,rgba(139,120,204,0.22) 0%,transparent 60%);top:-160px;left:-160px;pointer-events:none;z-index:0"></div>
        <div style="position:absolute;width:300px;height:300px;border-radius:50%;background:radial-gradient(circle,rgba(78,58,150,0.18) 0%,transparent 60%);bottom:-80px;right:-60px;pointer-events:none;z-index:0"></div>

        <div style="position:relative;z-index:1">
            <a href="<?php echo e(route('home')); ?>"
               class="font-display font-black text-2xl tracking-brutal text-paper hover:text-lilac transition-colors duration-100 select-none">
                VIBEZ
            </a>
        </div>

        <div style="position:relative;z-index:1">
            <p class="font-mono text-xs uppercase tracking-widest text-paper/35 mb-6">— Tu viaje empieza aquí</p>
            <h2 class="font-display font-black uppercase tracking-tightest text-paper leading-[0.88]"
                style="font-size:clamp(2.5rem,4.5vw,4.5rem)">
                Únete a la<br>
                <span class="text-lilac" style="font-style:italic">escena.</span>
            </h2>
            <div class="flex flex-col gap-4 mt-10">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-px bg-lilac/50"></div>
                    <p class="font-mono text-xs uppercase tracking-widest text-paper/40">Descubre eventos únicos</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-7 h-px bg-lilac/50"></div>
                    <p class="font-mono text-xs uppercase tracking-widest text-paper/40">Trabaja en la industria</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-7 h-px bg-lilac/50"></div>
                    <p class="font-mono text-xs uppercase tracking-widest text-paper/40">Conecta con tu red</p>
                </div>
            </div>
        </div>

        <div style="position:relative;z-index:1">
            <p class="font-mono text-xs text-paper/20">&copy; <?php echo e(date('Y')); ?> VIBEZ</p>
        </div>

    </div>

    
    <div class="flex-1 flex flex-col justify-center px-8 sm:px-12 lg:px-14 py-12 overflow-y-auto"
         style="background:#F7F5FF;background-image:radial-gradient(circle,rgba(139,120,204,0.1) 1.5px,transparent 1.5px);background-size:28px 28px;">

        <div style="max-width:520px;width:100%;margin:0 auto;">

            
            <a href="<?php echo e(route('home')); ?>"
               class="md:hidden font-display font-black text-2xl tracking-brutal text-ink hover:text-lilac transition-colors duration-100 select-none block mb-10">
                VIBEZ
            </a>

            <div class="form-header" id="formHeader">
                <h1 class="form-title">Crea tu cuenta</h1>
                <p class="form-subtitle">Únete a VIBEZ y empieza a vibrar</p>
            </div>

            <div id="alert-global" class="alert alert-error" role="alert"></div>

            <form id="registerForm" novalidate autocomplete="off" onsubmit="registrar(event)">

                <div class="field-group">

                    <div class="field" id="field-nombre">
                        <input type="text" id="nombre" name="nombre" placeholder=" " autocomplete="given-name" inputmode="text" onblur="validarNombre()">
                        <label for="nombre">Nombre</label>
                        <span class="field-error" id="error-nombre" role="alert"></span>
                    </div>

                    <div class="field-row">
                        <div class="field" id="field-apellido1">
                            <input type="text" id="apellido1" name="apellido1" placeholder=" " autocomplete="family-name" inputmode="text" onblur="validarApellido1()">
                            <label for="apellido1">Primer apellido</label>
                            <span class="field-error" id="error-apellido1" role="alert"></span>
                        </div>
                        <div class="field" id="field-apellido2">
                            <input type="text" id="apellido2" name="apellido2" placeholder=" " autocomplete="additional-name" inputmode="text" onblur="validarApellido2()">
                            <label for="apellido2">Segundo apellido</label>
                            <span class="field-error" id="error-apellido2" role="alert"></span>
                        </div>
                    </div>

                    <div class="field" id="field-email">
                        <input type="email" id="email" name="email" placeholder=" " autocomplete="email" inputmode="email" onblur="validarEmail()">
                        <label for="email">Correo electrónico</label>
                        <span class="field-error" id="error-email" role="alert"></span>
                    </div>

                    <div class="field-row">
                        <div class="field" id="field-password">
                            <input type="password" id="password" name="password" placeholder=" " autocomplete="new-password" onblur="validarContrasena()">
                            <label for="password">Contraseña</label>
                            <button type="button" class="toggle-password" onclick="togglePassword('password', this)" aria-label="Mostrar contraseña" tabindex="-1">
                                <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <svg class="eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display:none"><path d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                            </button>
                            <span class="field-error" id="error-password" role="alert"></span>
                        </div>
                        <div class="field" id="field-password_confirmation">
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder=" " autocomplete="new-password" onblur="validarConfirmacion()">
                            <label for="password_confirmation">Confirmar contraseña</label>
                            <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation', this)" aria-label="Mostrar contraseña" tabindex="-1">
                                <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <svg class="eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display:none"><path d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                            </button>
                            <span class="field-error" id="error-password_confirmation" role="alert"></span>
                        </div>
                    </div>

                    <div class="field field-select" id="field-tipo_cuenta">
                        <select id="tipo_cuenta" name="tipo_cuenta" onchange="cambiarTipoCuenta(this)" onblur="validarTipoCuenta()">
                            <option value="" disabled selected hidden></option>
                            <option value="cliente">Cliente</option>
                            <option value="empresa">Empresa</option>
                        </select>
                        <label for="tipo_cuenta">Tipo de cuenta</label>
                        <span class="field-error" id="error-tipo_cuenta" role="alert"></span>
                        <span id="hint-tipo_cuenta" style="font-size:0.75rem;margin-top:2px;display:block"></span>
                    </div>

                    <div class="field-row">
                        <div class="field" id="field-fecha_nacimiento">
                            <input type="text" id="fecha_nacimiento" name="fecha_nacimiento" placeholder=" " readonly>
                            <label for="fecha_nacimiento">Fecha de nacimiento</label>
                            <span class="field-error" id="error-fecha_nacimiento" role="alert"></span>
                        </div>
                        <div class="field" id="field-telefono">
                            <input type="tel" id="telefono" name="telefono" placeholder=" " autocomplete="tel" inputmode="tel" onblur="validarTelefono()">
                            <label for="telefono">Teléfono</label>
                            <span class="field-error" id="error-telefono" role="alert"></span>
                        </div>
                    </div>

                </div>

                <div class="btn-row" id="btnRow">
                    <button type="submit" class="btn-primary" id="submitBtn" onclick="rippleBtn(event, this)">
                        <span class="btn-text">Crear cuenta</span>
                        <span class="btn-spinner" aria-hidden="true">
                            <span class="spinner-ring"></span>
                        </span>
                    </button>
                    <div class="google-btn-wrapper">
                        <div id="google-signin-btn" data-client-id="<?php echo e(config('services.google.client_id')); ?>"></div>
                    </div>
                </div>

            </form>

            <p class="form-switch">
                ¿Ya tienes cuenta? <a href="<?php echo e(route('login')); ?>">Inicia sesión</a>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/register.blade.php ENDPATH**/ ?>
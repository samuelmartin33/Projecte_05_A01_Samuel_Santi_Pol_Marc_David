<?php $__env->startSection('title', 'Crear cuenta — VIBEZ'); ?>
<?php $__env->startSection('html-class', 'auth-page'); ?>
<?php $__env->startSection('body-class', 'auth-page'); ?>

<?php $__env->startSection('content'); ?>


<div class="auth-bg">
    <div class="aurora-blob"></div>
    <div class="aurora-blob"></div>
    <div class="aurora-blob"></div>
    <div class="aurora-blob"></div>
</div>

<div class="auth-wrapper page-transition">

    
    <div class="art-panel">
        <div class="art-content">
            <div class="brand-name">VIBEZ</div>
            <div class="brand-tagline">Tu viaje empieza aquí</div>

            
            <svg class="art-svg" viewBox="0 0 440 440" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">

                <g class="blob-1">
                    <path d="M218,72 C282,52 350,98 362,168 C374,238 336,308 272,334 C208,360 136,338 100,274 C64,210 72,136 116,100 C160,64 154,92 218,72Z"
                          fill="#5B21B6" opacity="0.10"/>
                </g>

                <g class="blob-2">
                    <path d="M212,130 C255,108 302,138 312,182 C322,226 298,268 258,280 C218,292 174,270 158,228 C142,186 169,152 212,130Z"
                          fill="#7C3AED" opacity="0.22"/>
                </g>

                <g class="blob-3">
                    <path d="M108,296 C130,278 162,287 170,314 C178,341 158,364 134,362 C110,360 94,340 97,316 C100,292 86,314 108,296Z"
                          fill="#C4B5FD" opacity="0.55"/>
                </g>

                <g class="blob-4">
                    <path d="M324,72 C346,58 370,70 374,96 C378,122 360,142 336,140 C312,138 298,120 302,98 C306,76 302,86 324,72Z"
                          fill="#7C3AED" opacity="0.38"/>
                </g>

                <g class="blob-5">
                    <path d="M292,334 C314,318 342,326 348,352 C354,378 334,396 310,393 C286,390 272,374 276,352 C280,330 270,350 292,334Z"
                          fill="#5B21B6" opacity="0.28"/>
                </g>

                <circle class="orb-1" cx="332" cy="206" r="17"  fill="#C4B5FD" opacity="0.6"/>
                <circle class="orb-2" cx="86"  cy="182" r="11"  fill="#7C3AED" opacity="0.38"/>
                <circle class="orb-3" cx="208" cy="382" r="8"   fill="#EDE9FE" opacity="0.75"/>
                <circle class="orb-1" cx="358" cy="324" r="10"  fill="#5B21B6" opacity="0.28"/>
                <circle class="orb-2" cx="62"  cy="290" r="6"   fill="#C4B5FD" opacity="0.5"/>
                <circle class="orb-3" cx="174" cy="74"  r="14"  fill="#7C3AED" opacity="0.18"/>
                <circle class="orb-1" cx="150" cy="352" r="5"   fill="#C4B5FD" opacity="0.45"/>

                <path d="M118,118 Q220,82 322,130"
                      stroke="#C4B5FD" stroke-width="1.2"
                      stroke-dasharray="5,5" opacity="0.28" fill="none"/>
                <path d="M140,308 Q218,268 298,312"
                      stroke="#7C3AED" stroke-width="1"
                      opacity="0.18" fill="none"/>

                <circle cx="268" cy="116" r="3" fill="#EDE9FE" opacity="0.8"/>
                <circle cx="168" cy="246" r="2" fill="#C4B5FD" opacity="0.6"/>
                <circle cx="338" cy="168" r="4" fill="#7C3AED" opacity="0.25"/>
                <circle cx="90"  cy="234" r="3" fill="#5B21B6" opacity="0.2"/>
            </svg>
        </div>
    </div>

    
    <div class="form-panel">

        <div class="form-header">
            <h1 class="form-title">Crea tu cuenta</h1>
            <p class="form-subtitle">Únete a VIBEZ y empieza a vibrar</p>
        </div>

        <div id="alert-global"
             class="alert alert-error<?php echo e(session('error') ? ' visible' : ''); ?>"
             role="alert"><?php echo e(session('error', '')); ?></div>

        
        <form id="registerForm"
              method="POST"
              action="<?php echo e(route('api.register')); ?>"
              novalidate
              autocomplete="off">
            <?php echo csrf_field(); ?>

            <div class="field-group">

                
                <div class="field <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="field-nombre">
                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        placeholder=" "
                        value="<?php echo e(old('nombre')); ?>"
                        autocomplete="given-name"
                        inputmode="text"
                    >
                    <label for="nombre">Nombre</label>
                    <span class="field-error <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> visible <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                          id="error-nombre"
                          role="alert"><?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><?php echo e($message); ?><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></span>
                </div>

                
                <div class="field-row">
                    <div class="field <?php $__errorArgs = ['apellido1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="field-apellido1">
                        <input
                            type="text"
                            id="apellido1"
                            name="apellido1"
                            placeholder=" "
                            value="<?php echo e(old('apellido1')); ?>"
                            autocomplete="family-name"
                            inputmode="text"
                        >
                        <label for="apellido1">Primer apellido</label>
                        <span class="field-error <?php $__errorArgs = ['apellido1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> visible <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                              id="error-apellido1"
                              role="alert"><?php $__errorArgs = ['apellido1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><?php echo e($message); ?><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></span>
                    </div>

                    <div class="field <?php $__errorArgs = ['apellido2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="field-apellido2">
                        <input
                            type="text"
                            id="apellido2"
                            name="apellido2"
                            placeholder=" "
                            value="<?php echo e(old('apellido2')); ?>"
                            autocomplete="additional-name"
                            inputmode="text"
                        >
                        <label for="apellido2">Segundo apellido</label>
                        <span class="field-error <?php $__errorArgs = ['apellido2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> visible <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                              id="error-apellido2"
                              role="alert"><?php $__errorArgs = ['apellido2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><?php echo e($message); ?><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></span>
                    </div>
                </div>

                
                <div class="field <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="field-email">
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder=" "
                        value="<?php echo e(old('email')); ?>"
                        autocomplete="email"
                        inputmode="email"
                    >
                    <label for="email">Correo electrónico</label>
                    <span class="field-error <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> visible <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                          id="error-email"
                          role="alert"><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><?php echo e($message); ?><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></span>
                </div>

                
                <div class="field-row">
                    <div class="field" id="field-password">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder=" "
                            autocomplete="new-password"
                        >
                        <label for="password">Contraseña</label>
                        <span class="field-error" id="error-password" role="alert"></span>
                    </div>

                    <div class="field" id="field-password_confirmation">
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder=" "
                            autocomplete="new-password"
                        >
                        <label for="password_confirmation">Confirmar contraseña</label>
                        <span class="field-error" id="error-password_confirmation" role="alert"></span>
                    </div>
                </div>

                
                <div class="field field-select" id="field-tipo_cuenta">
                    <select id="tipo_cuenta" name="tipo_cuenta">
                        <option value="" disabled selected hidden></option>
                        <option value="cliente">Cliente</option>
                        <option value="empresa">Empresa</option>
                    </select>
                    <label for="tipo_cuenta">Tipo de cuenta</label>
                    <span class="field-error" id="error-tipo_cuenta" role="alert"></span>
                </div>

                
                <div class="field-row">
                    <div class="field" id="field-fecha_nacimiento">
                        <input
                            type="date"
                            id="fecha_nacimiento"
                            name="fecha_nacimiento"
                            placeholder=" "
                        >
                        <label for="fecha_nacimiento">Fecha de nacimiento</label>
                        <span class="field-error" id="error-fecha_nacimiento" role="alert"></span>
                    </div>

                    <div class="field" id="field-telefono">
                        <input
                            type="tel"
                            id="telefono"
                            name="telefono"
                            placeholder=" "
                            autocomplete="tel"
                            inputmode="tel"
                        >
                        <label for="telefono">Teléfono</label>
                        <span class="field-error" id="error-telefono" role="alert"></span>
                    </div>
                </div>

            </div>

            
            <div class="btn-row">
                <button type="submit" class="btn-primary" id="submitBtn">
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

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/register.js']); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\0616\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/register.blade.php ENDPATH**/ ?>
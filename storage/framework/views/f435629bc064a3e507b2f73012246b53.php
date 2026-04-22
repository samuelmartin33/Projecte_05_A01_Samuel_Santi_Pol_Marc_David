<?php $__env->startSection('title', 'Iniciar sesión — VIBEZ'); ?>
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
            <div class="brand-tagline">Conecta · Vibra · Vive</div>

            
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
            <h1 class="form-title">Bienvenido de nuevo</h1>
            <p class="form-subtitle">Accede a tu cuenta para continuar</p>
        </div>

        
        <div id="alert-global"
             class="alert alert-error<?php echo e(session('error') ? ' visible' : ''); ?>"
             role="alert"><?php echo e(session('error', '')); ?></div>

        
        <form id="loginForm"
              method="POST"
              action="<?php echo e(route('api.login')); ?>"
              novalidate
              autocomplete="off">
            <?php echo csrf_field(); ?>

            <div class="field-group">

                
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

                
                <div class="field <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="field-password">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder=" "
                        autocomplete="current-password"
                    >
                    <label for="password">Contraseña</label>
                    <span class="field-error <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> visible <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                          id="error-password"
                          role="alert"><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><?php echo e($message); ?><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></span>
                </div>

            </div>

            
            <button type="submit" class="btn-primary" id="submitBtn">
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

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/login.js']); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\LARAVEL\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/login.blade.php ENDPATH**/ ?>
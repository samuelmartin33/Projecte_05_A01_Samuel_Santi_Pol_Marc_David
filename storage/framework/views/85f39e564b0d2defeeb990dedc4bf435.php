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

        <div id="alert-global" class="alert alert-error" role="alert"></div>

        <form id="registerForm" novalidate autocomplete="off" onsubmit="registrar(event)">

            <div class="field-group">

                
                <div class="field" id="field-nombre">
                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        placeholder=" "
                        autocomplete="given-name"
                        inputmode="text"
                    >
                    <label for="nombre">Nombre</label>
                    <span class="field-error" id="error-nombre" role="alert"></span>
                </div>

                
                <div class="field-row">
                    <div class="field" id="field-apellido1">
                        <input
                            type="text"
                            id="apellido1"
                            name="apellido1"
                            placeholder=" "
                            autocomplete="family-name"
                            inputmode="text"
                        >
                        <label for="apellido1">Primer apellido</label>
                        <span class="field-error" id="error-apellido1" role="alert"></span>
                    </div>

                    <div class="field" id="field-apellido2">
                        <input
                            type="text"
                            id="apellido2"
                            name="apellido2"
                            placeholder=" "
                            autocomplete="additional-name"
                            inputmode="text"
                        >
                        <label for="apellido2">Segundo apellido</label>
                        <span class="field-error" id="error-apellido2" role="alert"></span>
                    </div>
                </div>

                
                <div class="field" id="field-email">
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder=" "
                        autocomplete="email"
                        inputmode="email"
                    >
                    <label for="email">Correo electrónico</label>
                    <span class="field-error" id="error-email" role="alert"></span>
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
                    <select id="tipo_cuenta" name="tipo_cuenta" onchange="cambiarTipoCuenta(this)">
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

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <?php if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/js/register.js']); ?>
    <?php endif; ?>
<script>
/** Google Identity Services — el SDK llama esta función automáticamente */
window.onGoogleLibraryLoad = function () {
    const btn = document.getElementById('google-signin-btn');
    if (!btn) return;
    const clientId = btn.dataset.clientId;
    if (!clientId) return;
    google.accounts.id.initialize({
        client_id: clientId,
        callback: window.handleGoogleCredential,
        auto_select: false,
        cancel_on_tap_outside: true,
    });
    const wrapper  = btn.closest('.google-btn-wrapper');
    const btnWidth = wrapper ? wrapper.offsetWidth : 200;
    google.accounts.id.renderButton(btn, {
        theme: 'outline', size: 'large', width: Math.max(btnWidth, 200),
        text: 'continue_with', shape: 'rectangular', logo_alignment: 'left', locale: 'es',
    });
};

window.handleGoogleCredential = async function (response) {
    const alertEl = document.getElementById('alert-global');
    try {
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const res  = await fetch('/api/auth/google', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ credential: response.credential }),
        });
        const data = await res.json();
        if (data.success) {
            document.body.style.transition = 'opacity 0.35s ease';
            document.body.style.opacity    = '0';
            setTimeout(() => { window.location.href = '/home'; }, 360);
        } else {
            alertEl.textContent = data.message || 'Error al iniciar sesión con Google.';
            alertEl.className   = 'alert alert-error visible';
        }
    } catch (err) {
        alertEl.textContent = 'Error de conexión. Inténtalo de nuevo.';
        alertEl.className   = 'alert alert-error visible';
    }
};

/** Efecto ripple desde el punto del click */
function rippleBtn(e, btn) {
    const rect   = btn.getBoundingClientRect();
    const size   = Math.max(rect.width, rect.height);
    const ripple = document.createElement('span');
    ripple.classList.add('ripple');
    ripple.style.cssText = `width:${size}px;height:${size}px;left:${e.clientX - rect.left - size/2}px;top:${e.clientY - rect.top - size/2}px`;
    btn.appendChild(ripple);
    setTimeout(() => ripple.remove(), 700);
}

/** Muestra hint según tipo de cuenta seleccionado */
function cambiarTipoCuenta(sel) {
    const hint = document.getElementById('hint-tipo_cuenta');
    if (!hint) return;
    if (sel.value === 'empresa') {
        hint.textContent = 'Requiere aprobación del administrador.';
        hint.style.color = '#D97706';
    } else if (sel.value === 'cliente') {
        hint.textContent = 'Acceso inmediato tras el registro.';
        hint.style.color = '#059669';
    } else {
        hint.textContent = '';
    }
}

function isValidEmail(e) { return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(e); }

function mostrarErrorCampo(fieldId, errorId, msg) {
    document.getElementById(fieldId)?.classList.add('has-error');
    const el = document.getElementById(errorId);
    if (el) { el.textContent = msg; el.classList.add('visible'); }
}

function limpiarErrorCampo(fieldId, errorId) {
    document.getElementById(fieldId)?.classList.remove('has-error');
    const el = document.getElementById(errorId);
    if (el) { el.textContent = ''; el.classList.remove('visible'); }
}

function mostrarAlerta(msg, tipo = 'error') {
    const el = document.getElementById('alert-global');
    el.textContent = msg;
    el.className   = `alert alert-${tipo} visible`;
}

function sacudirElemento(el) {
    el.classList.add('shake');
    el.addEventListener('animationend', () => el.classList.remove('shake'), { once: true });
}

/** Submit del formulario de registro */
async function registrar(e) {
    e.preventDefault();
    const nombre               = document.getElementById('nombre').value.trim();
    const apellido1            = document.getElementById('apellido1').value.trim();
    const apellido2            = document.getElementById('apellido2').value.trim();
    const email                = document.getElementById('email').value.trim();
    const password             = document.getElementById('password').value;
    const passwordConfirmation = document.getElementById('password_confirmation').value;
    const fechaNacimiento      = document.getElementById('fecha_nacimiento').value;
    const telefono             = document.getElementById('telefono').value.trim();
    const tipoCuenta           = document.getElementById('tipo_cuenta').value;
    const btn                  = document.getElementById('submitBtn');
    const form                 = document.getElementById('registerForm');
    let valid                  = true;

    [
        ['field-nombre','error-nombre'], ['field-apellido1','error-apellido1'],
        ['field-apellido2','error-apellido2'], ['field-email','error-email'],
        ['field-password','error-password'], ['field-password_confirmation','error-password_confirmation'],
        ['field-fecha_nacimiento','error-fecha_nacimiento'], ['field-telefono','error-telefono'],
        ['field-tipo_cuenta','error-tipo_cuenta'],
    ].forEach(([f, err]) => limpiarErrorCampo(f, err));
    document.getElementById('alert-global').className = 'alert alert-error';

    if (!nombre || nombre.length < 2)       { mostrarErrorCampo('field-nombre','error-nombre', nombre ? 'Mínimo 2 caracteres' : 'El nombre es obligatorio'); valid = false; }
    if (!apellido1 || apellido1.length < 2) { mostrarErrorCampo('field-apellido1','error-apellido1', apellido1 ? 'Mínimo 2 caracteres' : 'El primer apellido es obligatorio'); valid = false; }
    if (!apellido2 || apellido2.length < 2) { mostrarErrorCampo('field-apellido2','error-apellido2', apellido2 ? 'Mínimo 2 caracteres' : 'El segundo apellido es obligatorio'); valid = false; }

    if (!email)                  { mostrarErrorCampo('field-email','error-email', 'El email es obligatorio'); valid = false; }
    else if (!isValidEmail(email)) { mostrarErrorCampo('field-email','error-email', 'Introduce un email válido'); valid = false; }

    if (!password)               { mostrarErrorCampo('field-password','error-password', 'La contraseña es obligatoria'); valid = false; }
    else if (password.length < 8){ mostrarErrorCampo('field-password','error-password', 'Mínimo 8 caracteres'); valid = false; }

    if (!passwordConfirmation)                { mostrarErrorCampo('field-password_confirmation','error-password_confirmation', 'Confirma tu contraseña'); valid = false; }
    else if (password !== passwordConfirmation){ mostrarErrorCampo('field-password_confirmation','error-password_confirmation', 'Las contraseñas no coinciden'); valid = false; }

    if (!fechaNacimiento) {
        mostrarErrorCampo('field-fecha_nacimiento','error-fecha_nacimiento', 'La fecha de nacimiento es obligatoria'); valid = false;
    } else {
        const hoy  = new Date();
        const nac  = new Date(fechaNacimiento);
        const edad = hoy.getFullYear() - nac.getFullYear() - (hoy < new Date(hoy.getFullYear(), nac.getMonth(), nac.getDate()) ? 1 : 0);
        if (edad < 14)  { mostrarErrorCampo('field-fecha_nacimiento','error-fecha_nacimiento', 'Debes tener al menos 14 años'); valid = false; }
        if (edad > 120) { mostrarErrorCampo('field-fecha_nacimiento','error-fecha_nacimiento', 'Fecha no válida'); valid = false; }
    }

    if (!telefono)                             { mostrarErrorCampo('field-telefono','error-telefono', 'El teléfono es obligatorio'); valid = false; }
    else if (!/^\+?[\d\s\-]{7,20}$/.test(telefono)) { mostrarErrorCampo('field-telefono','error-telefono', 'Introduce un teléfono válido'); valid = false; }

    if (!tipoCuenta) { mostrarErrorCampo('field-tipo_cuenta','error-tipo_cuenta', 'Selecciona el tipo de cuenta'); valid = false; }

    if (!valid) { sacudirElemento(form); return; }

    btn.classList.add('loading');
    try {
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const res  = await fetch('/api/register', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ nombre, apellido1, apellido2, email, password, password_confirmation: passwordConfirmation, fecha_nacimiento: fechaNacimiento, telefono, tipo_cuenta: tipoCuenta }),
        });
        const data = await res.json();

        if (data.success && data.status === 'active') {
            document.body.style.transition = 'opacity 0.35s ease';
            document.body.style.opacity    = '0';
            setTimeout(() => { window.location.href = '/home'; }, 360);
            return;
        }
        if (data.success && data.status === 'pending') {
            btn.classList.remove('loading');
            form.style.display = 'none';
            document.querySelector('.btn-row')?.remove();
            const pending = document.createElement('div');
            pending.innerHTML = `<div class="pending-inline"><div class="pending-inline-icon"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="M12 6v6l4 2"/></svg></div><h2 class="pending-inline-title">Solicitud enviada</h2><p class="pending-inline-text">Tu cuenta está <strong>pendiente de aprobación</strong> por el administrador.</p><a href="/login" class="pending-back-link">← Volver al login</a></div>`;
            document.querySelector('.form-header').replaceWith(pending);
        } else {
            btn.classList.remove('loading');
            if (data.errors) Object.entries(data.errors).forEach(([f, m]) => mostrarErrorCampo(`field-${f}`, `error-${f}`, m[0]));
            mostrarAlerta(data.message || 'No se pudo crear la cuenta. Revisa los datos.');
            sacudirElemento(form);
        }
    } catch (err) {
        btn.classList.remove('loading');
        mostrarAlerta('Error de conexión. Verifica tu red e inténtalo de nuevo.');
        console.error('[VIBEZ] Error en registro:', err);
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\proyectos\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/register.blade.php ENDPATH**/ ?>
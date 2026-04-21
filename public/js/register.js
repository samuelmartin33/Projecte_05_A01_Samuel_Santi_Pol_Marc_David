/**
 * VIBEZ — register.js
 * Valida el formulario en el frontend antes de enviarlo (form POST).
 * Si la validación pasa, activa el spinner y deja que el navegador envíe el form.
 * Los errores del servidor se renderizan directamente en el HTML por Blade.
 */

/* ============================================================
   RIPPLE EFFECT
   ============================================================ */
const submitBtn = document.getElementById('submitBtn');

submitBtn.addEventListener('click', function (e) {
    const rect   = this.getBoundingClientRect();
    const size   = Math.max(rect.width, rect.height);
    const x      = e.clientX - rect.left - size / 2;
    const y      = e.clientY - rect.top  - size / 2;

    const ripple = document.createElement('span');
    ripple.classList.add('ripple');
    ripple.style.cssText = `width:${size}px; height:${size}px; left:${x}px; top:${y}px`;
    this.appendChild(ripple);
    setTimeout(() => ripple.remove(), 700);
});

/* ============================================================
   UTILIDADES
   ============================================================ */

function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email);
}

function showFieldError(fieldId, errorId, message) {
    const field = document.getElementById(fieldId);
    const error = document.getElementById(errorId);
    if (!field || !error) return;
    field.classList.add('has-error');
    error.textContent = message;
    error.classList.add('visible');
}

function clearFieldError(fieldId, errorId) {
    const field = document.getElementById(fieldId);
    const error = document.getElementById(errorId);
    if (!field || !error) return;
    field.classList.remove('has-error');
    error.textContent = '';
    error.classList.remove('visible');
}

function showAlert(message, type = 'error') {
    const alert = document.getElementById('alert-global');
    alert.textContent = message;
    alert.className   = `alert alert-${type} visible`;
}

function clearAlert() {
    document.getElementById('alert-global').className = 'alert alert-error';
}

function shakeElement(element) {
    element.classList.add('shake');
    element.addEventListener('animationend', () => {
        element.classList.remove('shake');
    }, { once: true });
}

/* ============================================================
   LÓGICA PRINCIPAL — Validación antes del envío
   ============================================================ */
document.getElementById('registerForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const nombre               = document.getElementById('nombre').value.trim();
    const apellido1            = document.getElementById('apellido1').value.trim();
    const apellido2            = document.getElementById('apellido2').value.trim();
    const email                = document.getElementById('email').value.trim();
    const password             = document.getElementById('password').value;
    const passwordConfirmation = document.getElementById('password_confirmation').value;
    let valid                  = true;

    // Limpiar todos los errores
    [
        ['field-nombre',               'error-nombre'],
        ['field-apellido1',            'error-apellido1'],
        ['field-apellido2',            'error-apellido2'],
        ['field-email',                'error-email'],
        ['field-password',             'error-password'],
        ['field-password_confirmation','error-password_confirmation'],
    ].forEach(([fId, eId]) => clearFieldError(fId, eId));
    clearAlert();

    // — Validaciones —

    if (!nombre) {
        showFieldError('field-nombre', 'error-nombre', 'El nombre es obligatorio');
        valid = false;
    } else if (nombre.length < 2) {
        showFieldError('field-nombre', 'error-nombre', 'Mínimo 2 caracteres');
        valid = false;
    }

    if (!apellido1) {
        showFieldError('field-apellido1', 'error-apellido1', 'El primer apellido es obligatorio');
        valid = false;
    } else if (apellido1.length < 2) {
        showFieldError('field-apellido1', 'error-apellido1', 'Mínimo 2 caracteres');
        valid = false;
    }

    if (!apellido2) {
        showFieldError('field-apellido2', 'error-apellido2', 'El segundo apellido es obligatorio');
        valid = false;
    } else if (apellido2.length < 2) {
        showFieldError('field-apellido2', 'error-apellido2', 'Mínimo 2 caracteres');
        valid = false;
    }

    if (!email) {
        showFieldError('field-email', 'error-email', 'El email es obligatorio');
        valid = false;
    } else if (!isValidEmail(email)) {
        showFieldError('field-email', 'error-email', 'Introduce un email válido');
        valid = false;
    }

    if (!password) {
        showFieldError('field-password', 'error-password', 'La contraseña es obligatoria');
        valid = false;
    } else if (password.length < 8) {
        showFieldError('field-password', 'error-password', 'Mínimo 8 caracteres');
        valid = false;
    }

    if (!passwordConfirmation) {
        showFieldError('field-password_confirmation', 'error-password_confirmation', 'Confirma tu contraseña');
        valid = false;
    } else if (password && password !== passwordConfirmation) {
        showFieldError('field-password_confirmation', 'error-password_confirmation', 'Las contraseñas no coinciden');
        valid = false;
    }

    if (!valid) {
        shakeElement(this);
        return;
    }

    // — Activar spinner y enviar el formulario —
    submitBtn.classList.add('loading');
    this.submit();
});

/**
 * VIBEZ — register.js
 * Maneja el formulario de registro con Fetch API (AJAX)
 * Campos: nombre, apellido1, apellido2, email, password, password_confirmation
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
   SUBMIT
   ============================================================ */
document.getElementById('registerForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const nombre                = document.getElementById('nombre').value.trim();
    const apellido1             = document.getElementById('apellido1').value.trim();
    const apellido2             = document.getElementById('apellido2').value.trim();
    const email                 = document.getElementById('email').value.trim();
    const password              = document.getElementById('password').value;
    const passwordConfirmation  = document.getElementById('password_confirmation').value;
    let valid                   = true;

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

    submitBtn.classList.add('loading');

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const response = await fetch('/api/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept':       'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                nombre,
                apellido1,
                apellido2,
                email,
                password,
                password_confirmation: passwordConfirmation,
            }),
        });

        const data = await response.json();

        if (data.success) {
            submitBtn.innerHTML = `
                <span class="btn-text success-check">
                    <svg class="check-icon" viewBox="0 0 24 24" fill="none"
                         stroke="white" stroke-width="2.5"
                         stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    ¡Cuenta creada!
                </span>
            `;
            submitBtn.classList.remove('loading');
            submitBtn.style.background = 'linear-gradient(135deg, #22C55E, #16A34A)';

            setTimeout(() => {
                document.body.style.transition = 'opacity 0.35s ease';
                document.body.style.opacity    = '0';
                setTimeout(() => { window.location.href = '/home'; }, 360);
            }, 750);

        } else {
            submitBtn.classList.remove('loading');

            if (data.errors && typeof data.errors === 'object') {
                Object.entries(data.errors).forEach(([field, messages]) => {
                    showFieldError(`field-${field}`, `error-${field}`, messages[0]);
                });
            }

            showAlert(data.message || 'No se pudo crear la cuenta. Revisa los datos.');
            shakeElement(document.getElementById('registerForm'));
        }

    } catch (err) {
        submitBtn.classList.remove('loading');
        showAlert('Error de conexión. Verifica tu red e inténtalo de nuevo.');
        console.error('[VIBEZ] Error en register:', err);
    }
});

/**
 * VIBEZ — register.js
 * Maneja el formulario de registro con Fetch API (AJAX)
 * Incluye Google Identity Services (onGoogleLibraryLoad expuesto en window
 * para que el SDK externo lo invoque tras cargar).
 */

/* ============================================================
   GOOGLE IDENTITY SERVICES
   El SDK llama window.onGoogleLibraryLoad automáticamente.
   El client_id se lee del data-attribute del botón para evitar
   mezclar valores de Blade en archivos JS compilados por Vite.
   ============================================================ */
window.onGoogleLibraryLoad = function () {
    const btn = document.getElementById('google-signin-btn');
    if (!btn) return;

    const clientId = btn.dataset.clientId;

    google.accounts.id.initialize({
        client_id: clientId,
        callback: window.handleGoogleCredential,
        auto_select: false,
        cancel_on_tap_outside: true,
    });

    const wrapper  = btn.closest('.google-btn-wrapper');
    const btnWidth = wrapper ? wrapper.offsetWidth : (btn.offsetWidth || 200);

    google.accounts.id.renderButton(btn, {
        theme: 'outline',
        size:  'large',
        width: Math.max(btnWidth, 200),
        text:  'continue_with',
        shape: 'rectangular',
        logo_alignment: 'left',
        locale: 'es',
    });
};

window.handleGoogleCredential = async function (response) {
    const alertEl = document.getElementById('alert-global');
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const res = await fetch('/api/auth/google', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept':       'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
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
        console.error('[VIBEZ] Google auth error:', err);
    }
};

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
   HINT DEL TIPO DE CUENTA
   ============================================================ */
document.getElementById('tipo_cuenta').addEventListener('change', function () {
    const hint = document.getElementById('hint-tipo_cuenta');
    if (this.value === 'empresa') {
        hint.textContent = 'Requiere aprobación del administrador.';
        hint.style.color = '#D97706';
    } else if (this.value === 'cliente') {
        hint.textContent = 'Acceso inmediato tras el registro.';
        hint.style.color = '#059669';
    } else {
        hint.textContent = '';
    }
});

/* ============================================================
   SUBMIT
   ============================================================ */
document.getElementById('registerForm').addEventListener('submit', async function (e) {
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
    let valid                  = true;

    [
        ['field-nombre',                'error-nombre'],
        ['field-apellido1',             'error-apellido1'],
        ['field-apellido2',             'error-apellido2'],
        ['field-email',                 'error-email'],
        ['field-password',              'error-password'],
        ['field-password_confirmation', 'error-password_confirmation'],
        ['field-fecha_nacimiento',      'error-fecha_nacimiento'],
        ['field-telefono',              'error-telefono'],
        ['field-tipo_cuenta',           'error-tipo_cuenta'],
    ].forEach(([fId, eId]) => clearFieldError(fId, eId));
    clearAlert();

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

    if (!fechaNacimiento) {
        showFieldError('field-fecha_nacimiento', 'error-fecha_nacimiento', 'La fecha de nacimiento es obligatoria');
        valid = false;
    } else {
        const hoy    = new Date();
        const nacido = new Date(fechaNacimiento);
        const edad   = hoy.getFullYear() - nacido.getFullYear() -
                       (hoy < new Date(hoy.getFullYear(), nacido.getMonth(), nacido.getDate()) ? 1 : 0);
        if (edad < 14) {
            showFieldError('field-fecha_nacimiento', 'error-fecha_nacimiento', 'Debes tener al menos 14 años');
            valid = false;
        } else if (edad > 120) {
            showFieldError('field-fecha_nacimiento', 'error-fecha_nacimiento', 'Fecha no válida');
            valid = false;
        }
    }

    if (!telefono) {
        showFieldError('field-telefono', 'error-telefono', 'El teléfono es obligatorio');
        valid = false;
    } else if (!/^\+?[\d\s\-]{7,20}$/.test(telefono)) {
        showFieldError('field-telefono', 'error-telefono', 'Introduce un teléfono válido');
        valid = false;
    }

    if (!tipoCuenta) {
        showFieldError('field-tipo_cuenta', 'error-tipo_cuenta', 'Selecciona el tipo de cuenta');
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
                fecha_nacimiento: fechaNacimiento,
                telefono,
                tipo_cuenta: tipoCuenta,
            }),
        });

        const data = await response.json();

        if (data.success && data.status === 'active') {
            document.body.style.transition = 'opacity 0.35s ease';
            document.body.style.opacity    = '0';
            setTimeout(() => { window.location.href = '/home'; }, 360);
            return;
        }

        if (data.success && data.status === 'pending') {
            submitBtn.classList.remove('loading');
            document.getElementById('registerForm').style.display = 'none';
            document.querySelector('.btn-row')?.remove();

            const pending = document.createElement('div');
            pending.innerHTML = `
                <div class="pending-inline">
                    <div class="pending-inline-icon">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none"
                             stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/>
                            <path d="M12 6v6l4 2"/>
                        </svg>
                    </div>
                    <h2 class="pending-inline-title">Solicitud enviada</h2>
                    <p class="pending-inline-text">
                        Tu cuenta está <strong>pendiente de aprobación</strong>
                        por el administrador. Recibirás un correo electrónico cuando sea aceptada.
                    </p>
                    <a href="/login" class="pending-back-link">← Volver al login</a>
                </div>
            `;
            document.querySelector('.form-header').replaceWith(pending);

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

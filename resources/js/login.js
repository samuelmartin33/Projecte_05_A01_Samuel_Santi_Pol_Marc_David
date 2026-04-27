/**
 * VIBEZ — login.js
 * Valida el formulario en el frontend y lo envía via fetch a /api/login.
 * Redirige a /home en caso de éxito; muestra errores en caso contrario.
 */

/* ============================================================
   RIPPLE EFFECT — onda circular desde el punto del cursor
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
   UTILIDADES DE VALIDACIÓN Y UI
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
    const alert = document.getElementById('alert-global');
    alert.className = 'alert alert-error';
    alert.textContent = '';
}

function shakeElement(element) {
    element.classList.add('shake');
    element.addEventListener('animationend', () => {
        element.classList.remove('shake');
    }, { once: true });
}

/* ============================================================
   LÓGICA PRINCIPAL — Validación y envío via fetch
   ============================================================ */
document.getElementById('loginForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const email    = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    let valid      = true;

    // — Limpiar errores previos —
    clearFieldError('field-email', 'error-email');
    clearFieldError('field-password', 'error-password');
    clearAlert();

    // — Validaciones en frontend —
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

    if (!valid) {
        shakeElement(this);
        return;
    }

    submitBtn.classList.add('loading');

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')
                                  .getAttribute('content');

        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type':  'application/json',
                'Accept':        'application/json',
                'X-CSRF-TOKEN':  csrfToken,
            },
            body: JSON.stringify({ email, password }),
        });

        const data = await response.json();

        if (data.success) {
            submitBtn.innerHTML = `
                <span class="btn-text success-check">
                    <svg class="check-icon"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="white"
                         stroke-width="2.5"
                         stroke-linecap="round"
                         stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    ¡Sesión iniciada!
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

            if (data.unverified) {
                const tipo = data.estado_registro === 'rechazado' ? 'error' : 'warning';
                showAlert(data.message, tipo);
                return;
            }

            if (data.errors && typeof data.errors === 'object') {
                Object.entries(data.errors).forEach(([field, messages]) => {
                    showFieldError(`field-${field}`, `error-${field}`, messages[0]);
                });
            }

            showAlert(data.message || 'Credenciales incorrectas. Inténtalo de nuevo.');
            shakeElement(document.getElementById('loginForm'));
        }

    } catch (err) {
        submitBtn.classList.remove('loading');
        showAlert('Error de conexión. Verifica tu red e inténtalo de nuevo.');
        console.error('[VIBEZ] Error en login:', err);
    }
});

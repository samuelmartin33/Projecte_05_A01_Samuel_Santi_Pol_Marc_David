/**
 * VIBEZ — login.js
 * Maneja el formulario de login con Fetch API (AJAX)
 * Incluye: validación frontend, ripple en botón, estados loading/error/éxito
 */

/* ============================================================
   RIPPLE EFFECT — onda circular desde el punto del cursor
   Se crea un <span> en la posición exacta del click y se anima
   ============================================================ */
const submitBtn = document.getElementById('submitBtn');

submitBtn.addEventListener('click', function (e) {
    // Calcular posición relativa del cursor dentro del botón
    const rect    = this.getBoundingClientRect();
    const size    = Math.max(rect.width, rect.height);
    const x       = e.clientX - rect.left - size / 2;
    const y       = e.clientY - rect.top  - size / 2;

    // Crear el elemento onda
    const ripple = document.createElement('span');
    ripple.classList.add('ripple');
    ripple.style.cssText = `width:${size}px; height:${size}px; left:${x}px; top:${y}px`;
    this.appendChild(ripple);

    // Eliminar tras la animación (0.65s definido en CSS)
    setTimeout(() => ripple.remove(), 700);
});

/* ============================================================
   UTILIDADES DE VALIDACIÓN Y UI
   ============================================================ */

/**
 * Valida formato de email con expresión regular
 * @param {string} email
 * @returns {boolean}
 */
function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email);
}

/**
 * Muestra error en un campo específico
 * @param {string} fieldId  - id del .field wrapper
 * @param {string} errorId  - id del .field-error span
 * @param {string} message  - texto del error
 */
function showFieldError(fieldId, errorId, message) {
    const field = document.getElementById(fieldId);
    const error = document.getElementById(errorId);
    if (!field || !error) return;
    field.classList.add('has-error');
    error.textContent = message;
    error.classList.add('visible');
}

/**
 * Limpia el error de un campo
 * @param {string} fieldId
 * @param {string} errorId
 */
function clearFieldError(fieldId, errorId) {
    const field = document.getElementById(fieldId);
    const error = document.getElementById(errorId);
    if (!field || !error) return;
    field.classList.remove('has-error');
    error.textContent = '';
    error.classList.remove('visible');
}

/**
 * Muestra la alerta global (errores de servidor)
 * @param {string} message
 * @param {'error'|'success'} type
 */
function showAlert(message, type = 'error') {
    const alert = document.getElementById('alert-global');
    alert.textContent = message;
    alert.className   = `alert alert-${type} visible`;
}

/** Oculta la alerta global */
function clearAlert() {
    const alert = document.getElementById('alert-global');
    alert.className = 'alert alert-error';
    alert.textContent = '';
}

/**
 * Aplica la animación de shake al formulario (feedback de error)
 * @param {HTMLElement} element
 */
function shakeElement(element) {
    element.classList.add('shake');
    // Eliminar la clase tras la animación para poder reutilizarla
    element.addEventListener('animationend', () => {
        element.classList.remove('shake');
    }, { once: true });
}

/* ============================================================
   LÓGICA PRINCIPAL — Submit del formulario
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

    // — Activar estado loading —
    submitBtn.classList.add('loading');

    try {
        // Leer el CSRF token del meta tag inyectado por Laravel
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
            // — Estado de éxito: checkmark animado + mensaje —
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

            // Pequeño delay para que el usuario vea el checkmark
            // luego fade-out de la página y redirect
            setTimeout(() => {
                document.body.style.transition = 'opacity 0.35s ease';
                document.body.style.opacity    = '0';
                setTimeout(() => { window.location.href = '/index'; }, 360);
            }, 750);

        } else {
            submitBtn.classList.remove('loading');

            // Cuenta registrada pero aún no verificada por el admin:
            // permanecer en login y mostrar aviso claro (no redirigir)
            if (data.unverified) {
                showAlert(data.message, 'warning');
                return;
            }

            // Errores de validación por campo (422)
            if (data.errors && typeof data.errors === 'object') {
                Object.entries(data.errors).forEach(([field, messages]) => {
                    showFieldError(`field-${field}`, `error-${field}`, messages[0]);
                });
            }

            showAlert(data.message || 'Credenciales incorrectas. Inténtalo de nuevo.');
            shakeElement(document.getElementById('loginForm'));
        }

    } catch (err) {
        // — Error de red o servidor inesperado —
        submitBtn.classList.remove('loading');
        showAlert('Error de conexión. Verifica tu red e inténtalo de nuevo.');
        console.error('[VIBEZ] Error en login:', err);
    }
});

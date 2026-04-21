/**
 * VIBEZ — login.js
 * Valida el formulario en el frontend antes de enviarlo (form POST).
 * Si la validación pasa, activa el spinner y deja que el navegador envíe el form.
 * Los errores del servidor se renderizan directamente en el HTML por Blade.
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
}

function shakeElement(element) {
    element.classList.add('shake');
    element.addEventListener('animationend', () => {
        element.classList.remove('shake');
    }, { once: true });
}

/* ============================================================
   LÓGICA PRINCIPAL — Validación antes del envío
   El formulario tiene method="POST" y action="/api/login".
   Si la validación JS pasa → activa spinner y envía el form.
   Los errores del servidor aparecen en la página a través de Blade.
   ============================================================ */
document.getElementById('loginForm').addEventListener('submit', function (e) {
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

    // — Activar spinner y enviar el formulario —
    submitBtn.classList.add('loading');
    this.submit();
});

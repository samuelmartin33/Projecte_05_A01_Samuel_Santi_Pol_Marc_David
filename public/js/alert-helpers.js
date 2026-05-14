/**
 * VIBEZ — Alert Helpers
 * Utilidades para mostrar alertas con SweetAlert2
 * Compatible con el sistema de diseño VIBEZ
 */

/**
 * Mostrar alerta de éxito
 */
function showSuccessAlert(title, message, callback = null) {
    Swal.fire({
        icon: 'success',
        title: title || 'Éxito',
        html: message,
        confirmButtonText: 'Aceptar',
        allowOutsideClick: false,
        didClose: callback,
    });
}

/**
 * Mostrar alerta de error
 */
function showErrorAlert(title, message, callback = null) {
    Swal.fire({
        icon: 'error',
        title: title || 'Error',
        html: message,
        confirmButtonText: 'Aceptar',
        allowOutsideClick: false,
        didClose: callback,
    });
}

/**
 * Mostrar alerta de advertencia
 */
function showWarningAlert(title, message, callback = null) {
    Swal.fire({
        icon: 'warning',
        title: title || 'Advertencia',
        html: message,
        confirmButtonText: 'Aceptar',
        allowOutsideClick: false,
        didClose: callback,
    });
}

/**
 * Mostrar alerta de información
 */
function showInfoAlert(title, message, callback = null) {
    Swal.fire({
        icon: 'info',
        title: title || 'Información',
        html: message,
        confirmButtonText: 'Aceptar',
        allowOutsideClick: false,
        didClose: callback,
    });
}

/**
 * Mostrar alerta de confirmación
 */
function showConfirmAlert(title, message, onConfirm, onCancel = null) {
    Swal.fire({
        icon: 'question',
        title: title || 'Confirmar',
        html: message,
        showCancelButton: true,
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar',
        allowOutsideClick: false,
    }).then((result) => {
        if (result.isConfirmed) {
            if (typeof onConfirm === 'function') onConfirm();
        } else if (result.isDismissed && typeof onCancel === 'function') {
            onCancel();
        }
    });
}

/**
 * Mostrar alerta de carga
 */
function showLoadingAlert(title, message = null) {
    Swal.fire({
        title: title || 'Por favor espera...',
        html: message,
        icon: undefined,
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
}

/**
 * Cerrar alerta actual
 */
function closeAlert() {
    Swal.close();
}

/**
 * Mostrar alerta personalizada (función genérica)
 */
function showCustomAlert(options = {}) {
    const defaults = {
        allowOutsideClick: false,
        confirmButtonText: 'Aceptar',
    };
    Swal.fire({ ...defaults, ...options });
}

/**
 * Mostrar pendiente de aprobación
 */
function showPendingAlert(callback = null) {
    Swal.fire({
        icon: 'info',
        title: 'Solicitud Enviada',
        html: `
            <p style="margin-bottom: 12px;">Tu cuenta está <strong>pendiente de aprobación</strong> por el administrador.</p>
            <p>Recibirás un correo electrónico cuando sea aceptada.</p>
        `,
        confirmButtonText: 'Volver al Login',
        allowOutsideClick: false,
        didClose: () => {
            if (typeof callback === 'function') callback();
            else window.location.href = '/login';
        },
    }).then(() => {
        window.location.href = '/login';
    });
}

/* Exportar para módulos (si se usa como ESM) */
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        showSuccessAlert,
        showErrorAlert,
        showWarningAlert,
        showInfoAlert,
        showConfirmAlert,
        showLoadingAlert,
        closeAlert,
        showCustomAlert,
        showPendingAlert,
    };
}

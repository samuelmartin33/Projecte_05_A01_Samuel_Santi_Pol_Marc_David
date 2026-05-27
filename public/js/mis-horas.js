/**
 * mis-horas.js
 * Lógica del registro de horas de trabajador:
 * - Alertas flash (success/error/validation) con SweetAlert2
 * - Confirmación antes de guardar el registro de horas
 * Depende de window.HORAS_FLASH definido en el blade.
 */

/* Script cargado al final del body: DOM ya disponible, no se necesita DOMContentLoaded */
var flash = window.HORAS_FLASH || {};

/* Alerta de éxito */
if (flash.success) {
    Swal.fire({
        icon: 'success',
        title: '¡Guardado!',
        text: flash.success,
        background: '#0d0a18',
        color: '#f5f1ea',
        confirmButtonColor: '#a855f7',
        timer: 3000,
        timerProgressBar: true,
    });
}

/* Alerta de error genérico */
if (flash.error) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: flash.error,
        background: '#0d0a18',
        color: '#f5f1ea',
        confirmButtonColor: '#a855f7',
    });
}

/* Alerta de errores de validación (array) */
if (flash.errores && flash.errores.length) {
    Swal.fire({
        icon: 'warning',
        title: 'Revisa los campos',
        html: flash.errores.map(function(e) { return '• ' + e; }).join('<br>'),
        background: '#0d0a18',
        color: '#f5f1ea',
        confirmButtonColor: '#a855f7',
    });
}

/* Confirmación antes de enviar el formulario de horas */
var formHoras = document.getElementById('form-horas');
if (formHoras) {
    formHoras.onsubmit = function(e) {
        e.preventDefault();
        var horas = document.getElementById('horas').value;
        var fecha = document.getElementById('fecha').value;
        Swal.fire({
            title: '¿Confirmas el registro?',
            html: '<b>' + horas + ' h</b> el <b>' + fecha + '</b>',
            icon: 'question',
            background: '#0d0a18',
            color: '#f5f1ea',
            showCancelButton: true,
            confirmButtonColor: '#a855f7',
            cancelButtonColor: 'rgba(245,241,234,0.12)',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar',
        }).then(function(result) {
            if (result.isConfirmed) formHoras.submit();
        });
    };
}

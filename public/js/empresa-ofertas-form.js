/**
 * empresa-ofertas-form.js
 * Inicialización de Flatpickr para el formulario de creación de oferta de trabajo.
 */
document.addEventListener('DOMContentLoaded', function () {
    flatpickr('.emp-fp-date', {
        dateFormat:    'Y-m-d',
        altInput:      true,
        altFormat:     'd/m/Y',
        altInputClass: 'form-input',
        locale:        'es',
    });
});

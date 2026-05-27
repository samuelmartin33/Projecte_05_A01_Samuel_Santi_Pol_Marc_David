/**
 * empresa-cupones-form.js
 * Inicialización de Flatpickr para los formularios de creación/edición de cupones.
 * Los IDs de los inputs de fecha se pasan via window.CUPON_IDS definido en el blade:
 *   window.CUPON_IDS = { inicio: '#cup-fecha-inicio', fin: '#cup-fecha-fin' }
 */

var fpCupConfig = {
    enableTime:    true,
    time_24hr:     true,
    dateFormat:    'Y-m-d H:i',
    altInput:      true,
    altFormat:     'd/m/Y H:i',
    altInputClass: 'form-input',
    locale:        'es',
    disableMobile: true,
};

var idInicio = (window.CUPON_IDS && window.CUPON_IDS.inicio) || '#cup-fecha-inicio';
var idFin    = (window.CUPON_IDS && window.CUPON_IDS.fin)    || '#cup-fecha-fin';

var fpCupInicio = flatpickr(idInicio, Object.assign({}, fpCupConfig, {
    onChange: function(dates) {
        if (dates[0]) fpCupFin.set('minDate', dates[0]);
    }
}));
var fpCupFin = flatpickr(idFin, Object.assign({}, fpCupConfig));

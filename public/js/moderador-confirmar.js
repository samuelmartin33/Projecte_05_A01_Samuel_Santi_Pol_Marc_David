/**
 * moderador-confirmar.js — confirmacion SweetAlert2 para formularios de borrado
 */

/**
 * Muestra confirmación SweetAlert2 antes de enviar el formulario de borrado.
 * Llamado desde: onsubmit="return confirmarBorrar(event, this)" en cada form.
 * @param {Event} event  - El evento submit
 * @param {HTMLFormElement} form - El formulario que disparó el submit
 */
function confirmarBorrar(event, form) {
    event.preventDefault();
    var msg = form.getAttribute('data-confirm-msg') || '¿Confirmar acción?';
    Swal.fire({
        title: msg,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#ef4444',
    }).then(function(result) {
        if (result.isConfirmed) { form.submit(); }
    });
    return false;
}

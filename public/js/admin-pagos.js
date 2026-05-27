/**
 * admin-pagos.js
 * Modal de confirmación de reembolso en el panel de administración.
 */

/**
 * Abre el modal de reembolso precargando el importe y la URL de acción del formulario.
 * @param {string} actionUrl - URL de la ruta POST de reembolso
 * @param {number} importe   - Importe a reembolsar
 */
function abrirModalReembolso(actionUrl, importe) {
    document.getElementById('form-reembolso').action = actionUrl;
    document.getElementById('modal-importe').textContent = importe;
    document.getElementById('modal-reembolso').style.display = 'flex';
    document.getElementById('form-reembolso').querySelector('textarea').value = '';
}

/**
 * Cierra el modal de reembolso.
 */
function cerrarModalReembolso() {
    document.getElementById('modal-reembolso').style.display = 'none';
}

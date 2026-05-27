/**
 * admin-trabajos.js
 * Gestión de puestos de trabajo en el panel de administración:
 * - Toggle del panel de creación
 * - Modal SweetAlert2 para edición de un puesto
 */

/**
 * Muestra u oculta el panel de creación de nuevo puesto.
 */
function toggleFormulario() {
    var panel = document.getElementById('tr-form-panel');
    panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
}

/**
 * Abre un diálogo SweetAlert2 para editar el nombre y descripción de un puesto.
 * Al confirmar, rellena un formulario oculto y lo envía.
 *
 * @param {number} id          - ID del puesto de trabajo
 * @param {string} nombre      - Nombre actual
 * @param {string} descripcion - Descripción actual
 * @param {string} url         - URL de la ruta PATCH
 */
function editarPuesto(id, nombre, descripcion, url) {
    Swal.fire({
        title: 'Editar puesto',
        background: '#0d0a18',
        color: '#f5f1ea',
        showCancelButton: true,
        confirmButtonColor: '#a855f7',
        cancelButtonColor: 'rgba(245,241,234,0.10)',
        confirmButtonText: 'Guardar cambios',
        cancelButtonText: 'Cancelar',
        html:
            '<div style="text-align:left;margin-top:0.5rem;">' +
            '  <label style="font-family:\'Archivo Narrow\',sans-serif;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.14em;color:rgba(245,241,234,0.45);display:block;margin-bottom:5px;">Nombre *</label>' +
            '  <input id="swal-nombre" class="swal2-input" style="background:rgba(245,241,234,0.05);border:1px solid rgba(168,85,247,0.35);color:#f5f1ea;font-family:\'Archivo Narrow\',sans-serif;font-size:14px;border-radius:0;box-shadow:none;height:42px;" ' +
            '    placeholder="Nombre del puesto" maxlength="100" value="' + nombre + '">' +
            '  <label style="font-family:\'Archivo Narrow\',sans-serif;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.14em;color:rgba(245,241,234,0.45);display:block;margin:14px 0 5px;">Descripción (opcional)</label>' +
            '  <input id="swal-desc" class="swal2-input" style="background:rgba(245,241,234,0.05);border:1px solid rgba(245,241,234,0.12);color:#f5f1ea;font-family:\'Archivo Narrow\',sans-serif;font-size:14px;border-radius:0;box-shadow:none;height:42px;" ' +
            '    placeholder="Breve descripción del puesto" maxlength="500" value="' + descripcion + '">' +
            '</div>',
        customClass: { popup: 'swal2-popup', confirmButton: 'swal2-confirm', cancelButton: 'swal2-cancel' },
        didOpen: function() { document.getElementById('swal-nombre').focus(); },
        preConfirm: function() {
            var n = document.getElementById('swal-nombre').value.trim();
            if (!n) { Swal.showValidationMessage('El nombre del puesto es obligatorio.'); return false; }
            return { nombre: n, descripcion: document.getElementById('swal-desc').value.trim() };
        }
    }).then(function(result) {
        if (result.isConfirmed) {
            var form = document.getElementById('form-editar');
            form.action = url;
            document.getElementById('edit-nombre').value      = result.value.nombre;
            document.getElementById('edit-descripcion').value = result.value.descripcion;
            form.submit();
        }
    });
}

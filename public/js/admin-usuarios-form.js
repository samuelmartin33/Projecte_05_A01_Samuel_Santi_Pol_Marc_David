/**
 * admin-usuarios-form.js
 * Lógica del formulario de creación/edición de usuario en el panel admin.
 * Controla el estado de los checkboxes de rol según el tipo de cuenta.
 */

/**
 * Actualiza el estado de los checkboxes de rol según el tipo de cuenta y si es admin.
 * - Empresa: no puede ser admin ni moderador.
 * - Admin activo: no puede ser moderador al mismo tiempo.
 */
function vibezActualizarRoles() {
    var tipo      = document.getElementById('tipo_cuenta').value;
    var chkAdmin  = document.getElementById('es_admin');
    var chkMod    = document.getElementById('es_moderador');
    var wrapAdmin = document.getElementById('wrap-es-admin');
    var wrapMod   = document.getElementById('wrap-es-moderador');

    if (tipo === 'empresa') {
        /* Empresa: desactivar y desmarcar ambos roles */
        chkAdmin.checked  = false;
        chkAdmin.disabled = true;
        chkMod.checked    = false;
        chkMod.disabled   = true;
        wrapAdmin.style.opacity       = '0.4';
        wrapAdmin.style.pointerEvents = 'none';
        wrapMod.style.opacity         = '0.4';
        wrapMod.style.pointerEvents   = 'none';
    } else {
        /* Cliente: habilitar admin */
        chkAdmin.disabled             = false;
        wrapAdmin.style.opacity       = '';
        wrapAdmin.style.pointerEvents = '';

        if (chkAdmin.checked) {
            /* Admin activo: deshabilitar moderador */
            chkMod.checked            = false;
            chkMod.disabled           = true;
            wrapMod.style.opacity     = '0.4';
            wrapMod.style.pointerEvents = 'none';
        } else {
            /* No es admin: habilitar moderador */
            chkMod.disabled           = false;
            wrapMod.style.opacity     = '';
            wrapMod.style.pointerEvents = '';
        }
    }
}

/* Sincronizar estado al cargar la página */
vibezActualizarRoles();

/**
 * admin-usuarios-list.js
 * Render callback para la búsqueda en tiempo real de usuarios.
 * Utilizado por admin-buscar.js para pintar los resultados AJAX.
 */
window.adminBuscarRender = function(items) {
    if (!items.length) return '<tr><td colspan="9" class="empty">Sin resultados.</td></tr>';
    return items.map(function(u) {
        var estadoBadge = u.estado === 1
            ? '<span class="estado activo">Activo</span>'
            : '<span class="estado inactivo">Inactivo</span>';
        var adminBadge = u.es_admin
            ? '<span class="estado activo">Sí</span>'
            : '<span class="estado inactivo">No</span>';
        var modBadge = u.es_moderador
            ? '<span class="estado activo">Sí</span>'
            : '<span class="estado inactivo">No</span>';
        return '<tr>'
            + '<td data-label="ID">'       + u.id          + '</td>'
            + '<td data-label="Nombre">'   + u.nombre      + '</td>'
            + '<td data-label="Email">'    + u.email       + '</td>'
            + '<td data-label="Cuenta">'   + u.tipo_cuenta + '</td>'
            + '<td data-label="Registro">' + u.estado_reg  + '</td>'
            + '<td data-label="Admin">'    + adminBadge    + '</td>'
            + '<td data-label="Mod.">'     + modBadge      + '</td>'
            + '<td data-label="Estado">'   + estadoBadge   + '</td>'
            + '<td data-label="Acciones" class="acciones"><a class="btn btn-secondary" href="' + u.edit_url + '">Editar</a></td>'
            + '</tr>';
    }).join('');
};

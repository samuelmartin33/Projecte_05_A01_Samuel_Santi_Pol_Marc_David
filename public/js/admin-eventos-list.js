/**
 * admin-eventos-list.js
 * Render callback para la búsqueda en tiempo real de eventos.
 * Utilizado por admin-buscar.js para pintar los resultados AJAX.
 */
window.adminBuscarRender = function(items) {
    if (!items.length) return '<tr><td colspan="7" class="empty">Sin resultados.</td></tr>';
    return items.map(function(e) {
        var estadoBadge = e.estado
            ? '<span class="estado activo">Activo</span>'
            : '<span class="estado inactivo">Inactivo</span>';
        return '<tr>'
            + '<td data-label="ID">'          + e.id           + '</td>'
            + '<td data-label="Titulo">'       + e.titulo       + '</td>'
            + '<td data-label="Categoria">'    + e.categoria    + '</td>'
            + '<td data-label="Organizador">—</td>'
            + '<td data-label="Inicio">'       + e.fecha_inicio + '</td>'
            + '<td data-label="Estado">'       + estadoBadge    + '</td>'
            + '<td data-label="Acciones" class="acciones"><a class="btn btn-secondary" href="' + e.edit_url + '">Editar</a></td>'
            + '</tr>';
    }).join('');
};

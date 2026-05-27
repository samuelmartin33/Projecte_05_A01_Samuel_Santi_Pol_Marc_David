/**
 * admin-cupones-list.js
 * Render callback para la búsqueda en tiempo real de cupones.
 * Utilizado por admin-buscar.js para pintar los resultados AJAX.
 */
window.adminBuscarRender = function(items) {
    if (!items.length) return '<tr><td colspan="7" class="empty">Sin resultados.</td></tr>';
    return items.map(function(c) {
        var usos   = c.usos_actuales + ' / ' + (c.limite_usos ? c.limite_usos : '∞');
        var eventos = c.num_eventos > 0
            ? '<span style="font-size:0.8rem;">' + c.num_eventos + ' evento(s)</span>'
            : '<span style="color:rgba(245,241,234,0.4);font-size:0.8rem;">Todos</span>';
        return '<tr>'
            + '<td data-label="Código"><strong style="font-family:monospace;letter-spacing:0.05em;">' + c.codigo + '</strong></td>'
            + '<td data-label="Empresa">'       + c.empresa           + '</td>'
            + '<td data-label="Descuento">'     + c.valor_descuento   + '%</td>'
            + '<td data-label="Válido desde">'  + c.fecha_inicio      + '</td>'
            + '<td data-label="Válido hasta">'  + c.fecha_fin         + '</td>'
            + '<td data-label="Usos">'          + usos                + '</td>'
            + '<td data-label="Eventos">'       + eventos             + '</td>'
            + '</tr>';
    }).join('');
};

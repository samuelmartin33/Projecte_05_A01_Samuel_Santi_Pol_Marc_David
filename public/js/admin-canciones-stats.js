/* ════════════════════════════════════════════════════════════════
   admin-canciones-stats.js — Estadísticas de canciones (Admin VIBEZ)

   Reglas de código:
   - Sin addEventListener (se usa onchange directo)
   - Sin querySelector/querySelectorAll → getElementById / getElementsByClassName
   - Variables con nombres descriptivos en español
════════════════════════════════════════════════════════════════ */

/* ── Leer CSRF del meta tag ──────────────────────────────────────── */
var csrfTokenStats = '';

(function leerCsrf() {
    var metas = document.getElementsByTagName('meta');
    for (var i = 0; i < metas.length; i++) {
        if (metas[i].getAttribute('name') === 'csrf-token') {
            csrfTokenStats = metas[i].getAttribute('content');
            break;
        }
    }
}());

/* ── Disparar filtrado al cambiar cualquier select ───────────────── */
window.onload = function () {
    var selectEvento  = document.getElementById('filtro-evento');
    var selectEmpresa = document.getElementById('filtro-empresa');

    if (selectEvento)  { selectEvento.onchange  = filtrarEstadisticas; }
    if (selectEmpresa) { selectEmpresa.onchange = filtrarEstadisticas; }
};

/* ── Filtrar estadísticas por AJAX ──────────────────────────────── */
function filtrarEstadisticas() {
    var eventoId  = document.getElementById('filtro-evento').value;
    var empresaId = document.getElementById('filtro-empresa').value;

    var parametros = new URLSearchParams();
    if (eventoId)  { parametros.set('evento_id',  eventoId); }
    if (empresaId) { parametros.set('empresa_id', empresaId); }

    var url = '/admin/canciones/estadisticas?' + parametros.toString();

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfTokenStats,
            'Accept': 'application/json'
        }
    })
    .then(function (respuesta) { return respuesta.json(); })
    .then(function (datos) {
        renderizarTabla(datos.datos, datos.total_global);
    })
    .catch(function () {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudieron cargar las estadísticas.',
            background: '#0d0a18',
            color: '#f5f1ea'
        });
    });
}

/* ── Renderizar filas de la tabla ────────────────────────────────── */
function renderizarTabla(filas, totalGlobal) {
    var cuerpoTabla = document.getElementById('tabla-stats-body');

    if (!filas || filas.length === 0) {
        cuerpoTabla.innerHTML =
            '<tr><td colspan="4" class="stats-vacio">Sin datos para los filtros seleccionados.</td></tr>';
        document.getElementById('total-global').textContent = '0,00 €';
        return;
    }

    var html = '';
    for (var i = 0; i < filas.length; i++) {
        var fila = filas[i];
        html +=
            '<tr>' +
            '<td data-label="Canción">' + escaparHtml(fila.titulo) + '</td>' +
            '<td data-label="Artista">' + escaparHtml(fila.artista) + '</td>' +
            '<td data-label="Veces"><span class="badge-veces">' + fila.veces + '</span></td>' +
            '<td data-label="Ganancia">' + fila.ganancia + ' €</td>' +
            '</tr>';
    }

    cuerpoTabla.innerHTML = html;
    document.getElementById('total-global').textContent = totalGlobal + ' €';
}

/* ── Descargar PDF del mes actual ────────────────────────────────── */
function descargarPdfMes() {
    var eventoId  = document.getElementById('filtro-evento').value;
    var empresaId = document.getElementById('filtro-empresa').value;

    var parametros = new URLSearchParams();
    if (eventoId)  { parametros.set('evento_id',  eventoId); }
    if (empresaId) { parametros.set('empresa_id', empresaId); }

    window.location.href = '/admin/canciones/pdf-mes?' + parametros.toString();
}

/* ── Utilidad: escapar HTML para prevenir XSS ────────────────────── */
function escaparHtml(texto) {
    var div = document.createElement('div');
    div.appendChild(document.createTextNode(texto));
    return div.innerHTML;
}

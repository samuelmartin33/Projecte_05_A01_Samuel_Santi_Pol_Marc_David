/**
 * admin-buscar.js — Búsqueda AJAX con debounce para los listados del panel Admin.
 *
 * Uso en cada vista:
 *   1. Añadir un <input> con oninput="adminBuscar(this, 'usuarios')" (o el tipo que corresponda).
 *   2. Añadir id="admin-search-tbody" al <tbody> de la tabla.
 *   3. Definir window.adminBuscarRender = function(items) { return '<tr>...</tr>'; }
 *      en el @push('scripts') de cada vista.
 */

var adminBuscarTimer      = null;
var adminBuscarTbodyOrig  = null; /* HTML original del tbody antes de la primera búsqueda */

/**
 * Lanza la búsqueda con 300ms de debounce.
 * Se llama desde el atributo oninput del input de búsqueda.
 *
 * @param {HTMLInputElement} input - El input de búsqueda
 * @param {string}           tipo  - 'usuarios' | 'eventos' | 'cupones'
 */
function adminBuscar(input, tipo) {
    clearTimeout(adminBuscarTimer);
    var q = input.value.trim();

    adminBuscarTimer = setTimeout(function () {
        adminBuscarEjecutar(q, tipo);
    }, 300);
}

/**
 * Ejecuta la petición AJAX y actualiza el tbody.
 * Si q está vacío, restaura el contenido original de la tabla.
 */
function adminBuscarEjecutar(q, tipo) {
    var tbody   = document.getElementById('admin-search-tbody');
    var spinner = document.getElementById('admin-search-spinner');
    if (!tbody) return;

    /* Guardar HTML original la primera vez que se hace una búsqueda */
    if (adminBuscarTbodyOrig === null) {
        adminBuscarTbodyOrig = tbody.innerHTML;
    }

    /* Sin texto: restaurar tabla original con paginación intacta */
    if (!q) {
        tbody.innerHTML = adminBuscarTbodyOrig;
        var pag = document.querySelector('.paginacion');
        if (pag) pag.style.display = '';
        return;
    }

    /* Ocultar paginación durante la búsqueda */
    var pag = document.querySelector('.paginacion');
    if (pag) pag.style.display = 'none';

    if (spinner) spinner.style.display = 'flex';

    fetch('/admin/' + tipo + '/buscar?q=' + encodeURIComponent(q), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
        if (spinner) spinner.style.display = 'none';
        if (typeof window.adminBuscarRender === 'function') {
            var html = window.adminBuscarRender(data);
            tbody.innerHTML = html || '<tr><td colspan="20" class="empty">Sin resultados para "' + q + '".</td></tr>';
        }
    })
    .catch(function () {
        if (spinner) spinner.style.display = 'none';
    });
}

/**
 * resources/js/camarero/stock.js
 *
 * Lógica fetch para la gestión del stock de barra (rol camarero).
 * Las rutas y el token CSRF se leen del elemento contenedor via dataset.
 * Las funciones se exponen en window.Stock para que Alpine.js las invoque.
 *
 * REGLAS:
 *  - Sin addEventListener, querySelector, querySelectorAll ni getElementById.
 *  - Sin onclick/onchange inline en el HTML.
 *  - El contenedor se pasa como parámetro cuando sea necesario.
 */

// ── Helpers internos ──────────────────────────────────────────────────────────

/**
 * Lee el elemento raíz del módulo de stock.
 * Se identifica por el id "stock-contenedor".
 * Usamos document.getElementById únicamente aquí, centralizado.
 */
function obtenerContenedor() {
    // Permitido: una única lectura centralizada del DOM para obtener el
    // contenedor raíz del que se extraen todos los dataset.
    return document.getElementById('stock-contenedor');
}

/**
 * Extrae la URL base (index) del dataset del contenedor.
 * Ejemplo: /camarero/stock
 */
function urlBase() {
    return obtenerContenedor().dataset.urlBase;
}

/**
 * Extrae la URL de store (POST) del dataset del contenedor.
 */
function urlStore() {
    return obtenerContenedor().dataset.urlStore;
}

/**
 * Extrae el token CSRF del dataset del contenedor.
 */
function tokenCsrf() {
    return obtenerContenedor().dataset.csrf;
}

/**
 * Construye la URL de un recurso específico (show/update/destroy).
 * Equivale a route('camarero.stock.show', id) → /camarero/stock/{id}
 */
function urlRecurso(id) {
    return urlBase() + '/' + id;
}

// ── Funciones públicas ────────────────────────────────────────────────────────

/**
 * Recarga la página completa para refrescar la tabla.
 * Las operaciones AJAX modifican datos en servidor; una recarga
 * es la forma más limpia de reflejar el estado actualizado sin
 * duplicar lógica de renderizado en JS.
 */
async function cargarProductos() {
    window.location.reload();
}

/**
 * Crea (id=null) o actualiza (id=número) un producto de barra.
 *
 * @param {FormData} formData - Datos del formulario Alpine.js (objeto plano).
 * @param {number|null} id    - ID del producto a editar, null para crear.
 * @returns {Promise<{ok: boolean, mensaje: string}>}
 */
async function guardarProducto(formData, id = null) {
    const esEdicion = id !== null && id !== undefined;
    const url       = esEdicion ? urlRecurso(id) : urlStore();

    // Para PUT Laravel requiere el campo _method cuando se envía via fetch
    const cuerpo = new URLSearchParams();
    Object.entries(formData).forEach(function (par) {
        cuerpo.append(par[0], par[1]);
    });
    if (esEdicion) {
        cuerpo.append('_method', 'PUT');
    }

    const respuesta = await fetch(url, {
        method:  'POST',
        headers: {
            'X-CSRF-TOKEN': tokenCsrf(),
            'Content-Type': 'application/x-www-form-urlencoded',
            'Accept':       'application/json',
        },
        body: cuerpo.toString(),
    });

    const datos = await respuesta.json();
    return datos;
}

/**
 * Elimina un producto de barra tras confirmación previa (gestionada en Alpine).
 *
 * @param {number} id - ID del producto a eliminar.
 * @returns {Promise<{ok: boolean, mensaje?: string}>}
 */
async function eliminarProducto(id) {
    const respuesta = await fetch(urlRecurso(id), {
        method:  'POST',
        headers: {
            'X-CSRF-TOKEN': tokenCsrf(),
            'Content-Type': 'application/x-www-form-urlencoded',
            'Accept':       'application/json',
        },
        body: '_method=DELETE',
    });

    const datos = await respuesta.json();
    return datos;
}

/**
 * Obtiene los datos de un producto para precargar el modal de edición.
 *
 * @param {number} id - ID del producto a cargar.
 * @returns {Promise<object>} - Objeto con los campos del producto.
 */
async function cargarParaEditar(id) {
    const respuesta = await fetch(urlRecurso(id), {
        method:  'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': tokenCsrf(),
        },
    });

    const datos = await respuesta.json();
    return datos;
}

// ── Exposición global para Alpine.js ──────────────────────────────────────────

window.Stock = {
    cargarProductos,
    guardarProducto,
    eliminarProducto,
    cargarParaEditar,
};

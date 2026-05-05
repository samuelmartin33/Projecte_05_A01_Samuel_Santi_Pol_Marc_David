/**
 * trabajos-index.js — Filtrado AJAX de ofertas de trabajo
 *
 * Este archivo gestiona los filtros personalizados y el grid dinámico
 * de la página de la Bolsa de Trabajo (/bolsa-de-trabajo).
 *
 * Arquitectura:
 *   - Dos "custom select" accesibles (Categoría, Ciudad) construidos con HTML + CSS,
 *     ya que los <select> nativos del navegador son difíciles de estilizar.
 *   - Un overlay transparent (div fijo) que cierra los dropdowns al hacer clic fuera.
 *   - La función aplicarFiltros() hace fetch a /api/filtrar-trabajos y reconstruye el grid.
 *
 * Dependencias:
 *   - Los elementos HTML con IDs: filtro-categoria, filtro-ciudad,
 *     categoria-display, ciudad-display, overlay-dropdowns,
 *     cargando, grid-ofertas, sin-resultados, contador-resultados.
 *   - La API GET /api/filtrar-trabajos?categoria=&ciudad= (EventoController::filtrarTrabajos)
 */


/**
 * Abre o cierra el dropdown del filtro indicado.
 * Primero cierra todos los demás para que solo haya uno abierto a la vez.
 *
 * @param {string} id - 'categoria' o 'ciudad'
 */
function toggleDropdown(id) {
    var dropdown = document.getElementById(id + '-dropdown');
    var wrapper  = document.getElementById('wrapper-' + id);
    var overlay  = document.getElementById('overlay-dropdowns');
    var estaAbierto = dropdown.style.display === 'block';

    // Cerrar primero todos los dropdowns (incluido el que se va a abrir)
    cerrarTodosDropdowns();

    // Si no estaba abierto, lo abrimos
    if (!estaAbierto) {
        dropdown.style.display = 'block';
        wrapper.classList.add('abierto'); // la clase 'abierto' gira la flecha en CSS
        overlay.style.display = 'block';  // activa el overlay para cerrar al hacer clic fuera
    }
}

/**
 * Cierra todos los dropdowns y oculta el overlay.
 * Se llama desde toggleDropdown() y desde el onclick del overlay.
 */
function cerrarTodosDropdowns() {
    ['categoria', 'ciudad'].forEach(function(id) {
        var d = document.getElementById(id + '-dropdown');
        var w = document.getElementById('wrapper-' + id);
        if (d) d.style.display = 'none';
        if (w) w.classList.remove('abierto');
    });
    var overlay = document.getElementById('overlay-dropdowns');
    if (overlay) overlay.style.display = 'none';
}

/**
 * Selecciona una opción del filtro, actualiza el texto visible del select
 * y dispara el filtrado AJAX.
 *
 * @param {string} filtroId - 'categoria' o 'ciudad'
 * @param {string} valor    - valor a enviar a la API (id o nombre)
 * @param {string} texto    - texto a mostrar en el custom select
 * @param {Event}  event    - evento de clic (necesario para stopPropagation)
 */
function seleccionarFiltro(filtroId, valor, texto, event) {
    // Evita que el clic propague al overlay y cierre el dropdown antes de seleccionar
    event.stopPropagation();

    // Actualiza el input oculto (el que se leerá en aplicarFiltros)
    var inputHidden = document.getElementById('filtro-' + filtroId);
    var display     = document.getElementById(filtroId + '-display');
    if (inputHidden) inputHidden.value = valor;
    if (display)     display.textContent = texto;

    // Marca visualmente la opción seleccionada
    var dropdown = document.getElementById(filtroId + '-dropdown');
    if (dropdown) {
        dropdown.querySelectorAll('.custom-select-option').forEach(function(op) {
            op.classList.remove('seleccionado');
        });
        if (event.target && event.target.classList) {
            event.target.classList.add('seleccionado');
        }
    }

    cerrarTodosDropdowns();
    aplicarFiltros(); // lanza la petición AJAX con los filtros actuales
}

/**
 * Hace fetch al endpoint /api/filtrar-trabajos con los filtros activos
 * y reconstruye el grid con las ofertas devueltas.
 *
 * Ciclo de estados visuales:
 *   1. Muestra spinner + oculta grid y mensaje de sin resultados
 *   2. Cuando llega la respuesta: oculta spinner
 *      a. Si total === 0 → muestra mensaje de sin resultados
 *      b. Si total  >  0 → reconstruye el grid y lo muestra
 */
function aplicarFiltros() {
    var categoria = document.getElementById('filtro-categoria').value;
    var ciudad    = document.getElementById('filtro-ciudad').value;

    // Transición visual: mostrar cargando
    document.getElementById('cargando').classList.remove('hidden');
    document.getElementById('grid-ofertas').classList.add('hidden');
    document.getElementById('sin-resultados').classList.add('hidden');

    // encodeURIComponent garantiza que caracteres como tildes o ñ no rompan la URL
    fetch('/api/filtrar-trabajos?categoria=' + encodeURIComponent(categoria) + '&ciudad=' + encodeURIComponent(ciudad))
        .then(function(r) { return r.json(); })
        .then(function(datos) {

            // Actualiza el contador de ofertas en la barra de filtros
            var contador = document.getElementById('contador-resultados');
            if (contador) contador.textContent = datos.total;

            document.getElementById('cargando').classList.add('hidden');

            if (datos.total === 0) {
                document.getElementById('sin-resultados').classList.remove('hidden');
                return;
            }

            // Reconstruye el grid con las tarjetas recibidas del servidor
            var html = '';
            datos.ofertas.forEach(function(oferta) {
                html += crearTarjetaOferta(oferta);
            });

            var grid = document.getElementById('grid-ofertas');
            grid.innerHTML = html;
            grid.classList.remove('hidden');
        })
        .catch(function(error) {
            // En caso de fallo de red, volvemos a mostrar el grid anterior sin cambios
            console.error('Error al filtrar trabajos:', error);
            document.getElementById('cargando').classList.add('hidden');
            document.getElementById('grid-ofertas').classList.remove('hidden');
        });
}

/**
 * Resetea todos los filtros a su valor inicial ("Todos")
 * y vuelve a cargar todas las ofertas sin filtro.
 */
function limpiarFiltros() {
    // Vacía los inputs ocultos que usa aplicarFiltros()
    document.getElementById('filtro-categoria').value = '';
    document.getElementById('filtro-ciudad').value    = '';

    // Restaura el texto visible de los custom selects
    document.getElementById('categoria-display').textContent = 'Todas las categorías';
    document.getElementById('ciudad-display').textContent    = 'Todas las ciudades';

    // Resetea el marcado visual de las opciones seleccionadas
    document.querySelectorAll('.custom-select-dropdown .custom-select-option').forEach(function(op) {
        op.classList.remove('seleccionado');
    });
    // La primera opción de cada dropdown ("Todas las...") vuelve a quedar seleccionada
    ['categoria-dropdown', 'ciudad-dropdown'].forEach(function(id) {
        var primera = document.querySelector('#' + id + ' .custom-select-option');
        if (primera) primera.classList.add('seleccionado');
    });

    aplicarFiltros();
}

/**
 * Navega a la página de detalle de una oferta.
 *
 * @param {number} id - ID de la oferta en la base de datos
 */
function irAOferta(id) {
    window.location.href = '/trabajos/' + id;
}

/**
 * Genera el HTML de una tarjeta de oferta para insertarla en el grid AJAX.
 * Se construye con concatenación de strings porque es más rápido que
 * crear elementos DOM uno a uno cuando hay varias tarjetas a renderizar.
 *
 * @param {Object} oferta - objeto con los datos de la oferta devueltos por la API
 * @returns {string} HTML de la tarjeta
 */
function crearTarjetaOferta(oferta) {
    // El campo fecha_inicio puede ser null si la oferta no tiene fecha de inicio de trabajo
    var fechaHtml = oferta.fecha_inicio
        ? '<span class="ctg-dato">'
            + '<svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">'
            + '<path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>'
            + '</svg>'
            + oferta.fecha_inicio + '</span>'
        : '';

    return '<article class="card-trabajo-grande" onclick="irAOferta(' + oferta.id + ')">'
        + '<div class="ctg-header">'
        + '<div class="ctg-icono">'
        + '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="width:22px;height:22px">'
        + '<path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'
        + '</svg></div>'
        + '<div><span class="ctg-badge">' + oferta.categoria + '</span>'
        + '<p class="ctg-empresa">' + oferta.organizador + '</p></div>'
        + '</div>'
        + '<h3 class="ctg-titulo">' + oferta.titulo + '</h3>'
        + (oferta.descripcion ? '<p class="ctg-desc">' + oferta.descripcion + '</p>' : '')
        + '<div class="ctg-datos">'
        + '<span class="ctg-dato">'
        + '<svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">'
        + '<path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>'
        + '</svg>' + oferta.ubicacion + '</span>'
        + '<span class="ctg-dato">'
        + '<svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">'
        + '<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>'
        + '</svg>' + oferta.vacantes + ' vacante' + (oferta.vacantes !== 1 ? 's' : '') + '</span>'
        + fechaHtml
        + '</div>'
        + '<div class="ctg-footer">'
        + '<div><p class="ctg-salario-label">Salario</p><p class="ctg-salario">' + oferta.salario_formateado + '</p></div>'
        + '<button class="ctg-btn" onclick="irAOferta(' + oferta.id + ')">'
        + 'Ver oferta '
        + '<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">'
        + '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>'
        + '</button>'
        + '</div>'
        + '</article>';
}

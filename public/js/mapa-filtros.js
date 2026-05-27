/**
 * mapa-filtros.js
 * Lógica del panel de filtros del mapa de eventos a pantalla completa:
 * - Responsividad del botón toggle de filtros en móvil
 * - Toggle del panel de chips de categorías
 */

/**
 * Ajusta la visibilidad del botón toggle y del panel de filtros
 * según el ancho de pantalla. En móvil (≤640px) muestra el botón
 * y oculta el panel; en escritorio los invierte.
 */
function vibezResponsiveFiltrosMapa() {
    var btn   = document.getElementById('mapa-filtros-btn');
    var panel = document.getElementById('mapa-filtros-panel');
    if (!btn || !panel) return;

    if (window.innerWidth <= 640) {
        btn.style.display = 'flex';
        /* En móvil el panel empieza cerrado */
        if (panel.dataset.init !== '1') {
            panel.style.display = 'none';
            panel.dataset.init  = '1';
        }
    } else {
        btn.style.display = 'none';
        panel.style.display = 'flex';
    }
}

/**
 * Muestra u oculta el panel de chips de filtros al pulsar el botón toggle (móvil).
 */
function vibezToggleFiltrosMapa() {
    var panel = document.getElementById('mapa-filtros-panel');
    var arrow = document.getElementById('mapa-filtros-arrow');
    if (!panel) return;

    var abierto = panel.style.display !== 'none';
    panel.style.display = abierto ? 'none' : 'flex';
    if (arrow) arrow.textContent = abierto ? '▾' : '▴';
}

/**
 * Filtra los marcadores del mapa por categoría.
 * Recarga el mapa completamente con los eventos filtrados.
 *
 * @param {string} cat - Nombre de la categoría, o 'Todo' para mostrar todos
 */
function vibezFiltrarMapa(cat) {
    document.querySelectorAll('.vibez-cat-chip').forEach(function(c) {
        c.classList.toggle('active', c.dataset.cat === cat);
    });
    /* Guardar la lista original la primera vez */
    window.EVENTOS_DATA_ORIGINAL = window.EVENTOS_DATA_ORIGINAL || window.EVENTOS_DATA;
    window.EVENTOS_DATA = cat === 'Todo'
        ? window.EVENTOS_DATA_ORIGINAL
        : window.EVENTOS_DATA_ORIGINAL.filter(function(e) { return e.categoria === cat; });

    /* Destruir y reinicializar el mapa con los datos filtrados */
    var container = document.getElementById('vibez-map-full');
    if (container && container._leaflet_id) {
        container._leaflet_id = null;
        container.innerHTML = '';
    }
    vibezInitMapFull();
}

/* Inicializar al cargar y al redimensionar la ventana */
vibezInitMapFull();
vibezResponsiveFiltrosMapa();
window.onresize = vibezResponsiveFiltrosMapa;

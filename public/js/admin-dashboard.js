/**
 * admin-dashboard.js
 * Lógica del layout principal del panel de administración:
 * - Inicialización de Flatpickr para campos de fecha/hora
 * - Apertura/cierre del dropdown de usuario
 * - Navegación móvil (hamburguesa)
 * - Cierre automático al hacer clic fuera
 */

/* ── Flatpickr: inicializa todos los inputs de fecha y datetime ── */
/* Script cargado al final del body: DOM ya disponible, no se necesita DOMContentLoaded */
flatpickr('.adm-fp-date', {
    dateFormat:    'Y-m-d',
    altInput:      true,
    altFormat:     'd/m/Y',
    altInputClass: 'adm-fp-alt',
    locale:        'es',
    disableMobile: true,
});
flatpickr('.adm-fp-datetime', {
    enableTime:    true,
    time_24hr:     true,
    dateFormat:    'Y-m-d H:i',
    altInput:      true,
    altFormat:     'd/m/Y H:i',
    altInputClass: 'adm-fp-alt',
    locale:        'es',
    disableMobile: true,
});

/**
 * Abre o cierra el dropdown del avatar en el footer del sidebar.
 * Llamado con onclick desde el elemento #admSideFoot.
 */
function toggleAdmDropdown() {
    var d = document.getElementById('admDropdown');
    d.style.display = d.style.display === 'none' ? 'block' : 'none';
}

/**
 * Abre o cierra la navegación en móvil (botón hamburguesa).
 * Posiciona el panel debajo del top bar calculando su altura real.
 */
function toggleAdmNav() {
    var nav  = document.querySelector('.adm-nav-group');
    var btn  = document.getElementById('adm-hamburger');
    var side = document.querySelector('.adm-side');
    if (!nav || !btn) return;

    var abierto = nav.classList.toggle('adm-nav-abierto');
    btn.classList.toggle('abierto', abierto);
    btn.setAttribute('aria-expanded', abierto);

    /* Posicionar el panel justo debajo del top bar (altura real) */
    if (abierto && side) {
        nav.style.top = side.offsetHeight + 'px';
    }
}

/* ── Cierre al hacer clic fuera del dropdown y del menú móvil ── */
document.onclick = function (e) {
    var foot = document.getElementById('admSideFoot');
    var drop = document.getElementById('admDropdown');
    if (drop && foot && !foot.contains(e.target)) {
        drop.style.display = 'none';
    }

    /* Cierra el menú móvil si se hace clic fuera */
    var nav       = document.querySelector('.adm-nav-group');
    var hamburger = document.getElementById('adm-hamburger');
    if (nav && nav.classList.contains('adm-nav-abierto')) {
        if (!nav.contains(e.target) && e.target !== hamburger && !hamburger.contains(e.target)) {
            nav.classList.remove('adm-nav-abierto');
            if (hamburger) {
                hamburger.classList.remove('abierto');
                hamburger.setAttribute('aria-expanded', 'false');
            }
        }
    }
};

/**
 * empresa-perfil-fiscal.js
 * Selector custom de tipo de empresa en el formulario de perfil fiscal.
 */

/** Abre o cierra el selector de tipo de empresa. */
function toggleTeCsel() {
    var el = document.getElementById('te-csel');
    el.classList.toggle('open');
}

/**
 * Selecciona un tipo de empresa y cierra el dropdown.
 * @param {string} val   - Valor interno (ej: 'sl', 'sa')
 * @param {string} label - Texto visible en el trigger
 */
function pickTeCsel(val, label) {
    document.getElementById('tipo_empresa').value         = val;
    document.getElementById('te-csel-label').textContent  = label;
    document.getElementById('te-csel').classList.remove('open');
    document.querySelectorAll('.ev-csel-opt').forEach(function(li) {
        li.classList.toggle('selected', li.getAttribute('onclick').indexOf("'" + val + "'") !== -1);
    });
}

/* Cierra el selector al hacer clic fuera de él */
document.addEventListener('click', function(e) {
    var el = document.getElementById('te-csel');
    if (el && !el.contains(e.target)) el.classList.remove('open');
});

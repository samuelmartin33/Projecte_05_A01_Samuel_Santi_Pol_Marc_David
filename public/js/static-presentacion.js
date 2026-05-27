/**
 * static-presentacion.js — animaciones pagina presentacion
 */
/* Copia el texto al portapapeles y muestra un tooltip */
function copiarText(text) {
    navigator.clipboard.writeText(text).then(function() {
        var tooltip = document.getElementById('pres-tooltip');
        tooltip.classList.add('visible');
        setTimeout(function() {
            tooltip.classList.remove('visible');
        }, 1800);
    });
}

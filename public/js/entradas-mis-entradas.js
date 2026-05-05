/**
 * entradas-mis-entradas.js — VIBEZ
 *
 * Gestiona la interactividad de la página "Mis Entradas":
 *   1. Generación de QR: busca todos los elementos con `data-codigo` y crea
 *      el código QR dentro de cada uno usando la librería qrcodejs.
 *   2. Toggle del panel QR: muestra u oculta el QR individual de cada entrada
 *      al pulsar el botón "Ver QR" / "Ocultar QR".
 *
 * Dependencias:
 *   - Librería externa QRCode.js (qrcodejs): debe cargarse en el HTML ANTES que
 *     este script. Si se invierte el orden, `QRCode` no estará definida y la
 *     generación de QR fallará con ReferenceError.
 *   - El HTML debe tener elementos con `data-codigo` para la generación automática
 *     de QR, y pares (panel, botón) accesibles por ID para el toggle.
 *
 * Funciones públicas (llamadas desde onclick en el HTML):
 *   toggleQr(qrId, btnId) — muestra u oculta el panel QR de una entrada concreta
 */

// Al igual que en entradas-confirmacion.js, usamos el patrón de selección por
// data-attribute para localizar todos los contenedores QR sin depender de clases
// o IDs específicos. El servidor puede generar N entradas y este código las
// procesa todas de forma genérica con un solo forEach.
document.querySelectorAll('[data-codigo]').forEach(function(el) {

    // La librería qrcodejs recibe el elemento contenedor y las opciones,
    // genera el QR internamente y lo inserta como hijo del elemento.
    new QRCode(el, {
        // El código único de la entrada viaja desde PHP hasta aquí a través del
        // atributo data-codigo del HTML, evitando así mezclar PHP y JavaScript.
        text:       el.dataset.codigo,
        width:      200,
        height:     200,
        colorDark:  '#000000',
        colorLight: '#ffffff',
    });
});

/**
 * Muestra u oculta el panel que contiene el QR de una entrada,
 * y actualiza el texto del botón según el estado resultante.
 * Se llama directamente desde el atributo onclick del botón en el HTML.
 *
 * @param {string} qrId  - ID del elemento <div> que contiene el QR (ej: "qr-panel-5")
 * @param {string} btnId - ID del botón que disparó la acción (ej: "qr-btn-5")
 */
function toggleQr(qrId, btnId) {
    var panel   = document.getElementById(qrId);
    var btn     = document.getElementById(btnId);
    // Detectamos el estado actual comprobando si el panel ya es visible
    var visible = panel.style.display !== 'none';
    // Alternamos la visibilidad: si era visible lo ocultamos, y viceversa
    panel.style.display = visible ? 'none' : 'block';
    // Actualizamos el texto del botón para que refleje la acción disponible:
    // si el QR acaba de ocultarse el botón dice "Ver QR", y si se acaba de mostrar, "Ocultar QR"
    btn.textContent     = visible ? 'Ver QR' : 'Ocultar QR';
}

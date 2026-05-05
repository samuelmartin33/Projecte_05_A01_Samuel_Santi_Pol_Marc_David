/**
 * entradas-confirmacion.js — VIBEZ
 *
 * Genera automáticamente los códigos QR en la página de confirmación de compra.
 * Por cada entrada comprada, busca el elemento contenedor marcado con el atributo
 * `data-codigo` y crea dentro de él un QR con el código único de la entrada.
 *
 * Dependencias:
 *   - Librería externa QRCode.js (qrcodejs): debe estar cargada en el HTML
 *     ANTES de este script mediante una etiqueta <script src="...qrcode.min.js">.
 *     Si este script se ejecuta primero, la clase `QRCode` no existirá aún y
 *     se producirá un ReferenceError que impediría generar ningún QR.
 *   - El HTML debe contener elementos con el atributo data-codigo y un style
 *     con width/height opcionales, por ejemplo:
 *       <div data-codigo="ABC123" style="width:220px;height:220px;"></div>
 *
 * Funciones: ninguna pública; el código se ejecuta directamente al cargar el script.
 */

// `document.querySelectorAll('[data-codigo]')` usa el patrón "data-attribute selector":
// en lugar de buscar por clase o ID, buscamos todos los elementos que tengan
// el atributo HTML personalizado `data-codigo`, independientemente de su tag.
// Esto permite que el servidor genere tantos contenedores QR como entradas haya,
// y este script los procesa todos sin necesidad de conocer cuántos son de antemano.
document.querySelectorAll('[data-codigo]').forEach(function(el) {

    // `QRCode` es la clase principal de la librería qrcodejs.
    // Al instanciarla, la librería genera internamente una imagen (canvas o tabla)
    // y la inserta como hijo del elemento `el` que le pasamos como primer argumento.
    new QRCode(el, {
        // `el.dataset.codigo` lee el valor del atributo data-codigo del elemento HTML.
        // Si el elemento es <div data-codigo="VIBEZ-001">, aquí obtenemos "VIBEZ-001".
        text:       el.dataset.codigo,
        // parseInt devuelve NaN si el style no tiene width definido; en ese caso usamos 220 px
        width:      parseInt(el.style.width)  || 220,
        height:     parseInt(el.style.height) || 220,
        colorDark:  '#000000',  // color de los módulos oscuros del QR (los cuadraditos)
        colorLight: '#ffffff',  // color del fondo blanco del QR
    });
});

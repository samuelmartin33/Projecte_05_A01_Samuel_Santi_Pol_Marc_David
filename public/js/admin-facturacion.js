/**
 * admin-facturacion.js
 * Recalcula la previsualización de la factura en tiempo real.
 * Depende de window.BRUTO (importe bruto de ventas) definido en el blade.
 */

/**
 * Recalcula comisión, IVA y neto según los inputs del formulario
 * y actualiza los spans de previsualización.
 */
function recalcular() {
    var bruto = window.BRUTO || 0;
    var pct   = parseFloat(document.getElementById('inputComision').value) || 0;
    var iva   = parseFloat(document.getElementById('inputIva').value) || 0;
    var com   = Math.round(bruto * pct / 100 * 100) / 100;
    var cuota = Math.round(com * iva / 100 * 100) / 100;
    var neto  = Math.round((bruto - com - cuota) * 100) / 100;

    document.getElementById('prev-pct-com').textContent  = pct;
    document.getElementById('prev-pct-iva').textContent  = iva;
    document.getElementById('prev-comision').textContent = fmt(com);
    document.getElementById('prev-iva').textContent      = fmt(cuota);
    document.getElementById('prev-neto').textContent     = fmt(neto);
}

/**
 * Formatea un número como moneda española (2 decimales, separador de miles punto).
 * @param {number} n
 * @returns {string}
 */
function fmt(n) {
    return n.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

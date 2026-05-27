/**
 * detail-modal.js — logica del modal de detalle de evento
 */
function vibezCantidad(delta) {
  var el = document.getElementById('modal-cantidad');
  if (!el) return;
  var v = parseInt(el.value) + delta;
  el.value = Math.min(10, Math.max(1, v));
  // Recalcular precio si hay cupón activo
  if (window._cuponActivo) {
    _vibezAplicarDescuentoUI(window._cuponActivo.valor_descuento);
  }
}

/* Validación AJAX del cupón */
function vibezValidarCupon() {
  var codigoEl = document.getElementById('modal-cupon-codigo');
  var msgEl    = document.getElementById('modal-cupon-msg');
  var btnEl    = document.getElementById('btn-aplicar-cupon');
  var eventoId = (document.getElementById('modal-comprar') || {}).dataset.eventoId;

  if (!codigoEl || !codigoEl.value.trim()) return;
  if (!eventoId) return;

  var codigo = codigoEl.value.trim().toUpperCase();
  if (btnEl) { btnEl.textContent = '...'; btnEl.disabled = true; }

  var csrf = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
  fetch('/api/cupones/validar', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
    body: JSON.stringify({ codigo: codigo, evento_id: parseInt(eventoId) })
  })
  .then(function(r) { return r.json(); })
  .then(function(data) {
    if (btnEl) { btnEl.textContent = 'Aplicar'; btnEl.disabled = false; }
    if (msgEl) {
      msgEl.style.display = 'block';
      msgEl.textContent   = data.message || '';
      msgEl.style.color   = data.valid ? '#4ade80' : '#fca5a5';
    }
    if (data.valid) {
      window._cuponActivo = data;
      codigoEl.style.borderColor = 'rgba(34,197,94,0.5)';
      _vibezAplicarDescuentoUI(data.valor_descuento);
    } else {
      window._cuponActivo = null;
      _vibezOcultarDescuentoUI();
    }
  })
  .catch(function() {
    if (btnEl) { btnEl.textContent = 'Aplicar'; btnEl.disabled = false; }
    if (msgEl) { msgEl.style.display='block'; msgEl.textContent='Error de conexión.'; msgEl.style.color='#fca5a5'; }
  });
}

function _vibezAplicarDescuentoUI(pct) {
  var qtyEl    = document.getElementById('modal-cantidad');
  var resumen  = document.getElementById('modal-cupon-resumen');
  var precioEl = document.getElementById('modal-precio');
  var origEl   = document.getElementById('modal-precio-original');
  var ahorroEl = document.getElementById('modal-ahorro');
  var finalEl  = document.getElementById('modal-precio-final');
  if (!resumen || !precioEl) return;

  var precioTexto = precioEl.textContent.trim();
  var cantidad    = qtyEl ? parseInt(qtyEl.value) || 1 : 1;
  // Extraer número del precio (ej: "15 €" o "Gratis")
  var match = precioTexto.match(/([\d.,]+)/);
  if (!match) return;
  var pUnit   = parseFloat(match[1].replace(',', '.'));
  var total   = pUnit * cantidad;
  var dto     = Math.round(total * (pct / 100) * 100) / 100;
  var fin     = Math.round((total - dto) * 100) / 100;

  if (origEl)  origEl.textContent  = total.toFixed(2) + ' €';
  if (ahorroEl) ahorroEl.textContent = '-' + dto.toFixed(2) + ' € (' + pct + '%)';
  if (finalEl)  finalEl.textContent  = fin.toFixed(2) + ' €';
  resumen.style.display = 'block';
}

function _vibezOcultarDescuentoUI() {
  var resumen = document.getElementById('modal-cupon-resumen');
  if (resumen) resumen.style.display = 'none';
}

function vibezResetCupon() {
  window._cuponActivo = null;
  var msgEl   = document.getElementById('modal-cupon-msg');
  var codigoEl = document.getElementById('modal-cupon-codigo');
  if (msgEl)    { msgEl.style.display = 'none'; msgEl.textContent = ''; }
  if (codigoEl) { codigoEl.style.borderColor = 'var(--line)'; }
  _vibezOcultarDescuentoUI();
}

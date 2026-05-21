{{-- Modal de detalle de evento. Oculto por defecto, rellenado por vibez-home.js --}}
<div id="vibez-detail-modal"
     class="modal-back"
     style="display:none;align-items:center;justify-content:center;"
     onclick="vibezCloseModal()">

  <div class="modal-card detail-modal"
       onclick="event.stopPropagation()"
       style="position:fixed;inset:5% 8%;z-index:10000;background:var(--bg);border:1px solid var(--line);overflow:auto;display:grid;grid-template-columns:1.2fr 1fr;">

    {{-- Columna izquierda: imagen --}}
    <div style="position:relative;overflow:hidden;min-height:520px;">
      <img id="modal-img" src="" alt="" style="width:100%;height:100%;object-fit:cover;filter:contrast(1.05) saturate(1.1) brightness(0.85);">
      <div style="position:absolute;inset:0;background:linear-gradient(180deg,transparent 50%,rgba(7,6,12,0.7));"></div>
      <button onclick="vibezCloseModal()"
              style="position:absolute;top:20px;right:20px;width:44px;height:44px;border-radius:50%;background:rgba(7,6,12,0.6);border:1px solid var(--ink-faint);color:var(--ink);cursor:pointer;backdrop-filter:blur(10px);font-size:18px;">
        ×
      </button>
      <div style="position:absolute;bottom:28px;left:28px;right:28px;">
        {{-- Badge en curso --}}
        <div id="modal-en-curso"
             style="display:none;align-items:center;gap:6px;background:var(--magenta);color:var(--cream);padding:5px 12px;border-radius:999px;font-family:'Archivo Narrow',sans-serif;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:10px;width:fit-content;">
          <span class="pulse-dot" style="width:7px;height:7px;border-radius:50%;background:var(--cream);"></span>
          EN CURSO AHORA
        </div>
        <span id="modal-sticker" class="sticker"></span>
        <h2 id="modal-titulo" class="display" style="font-size:clamp(40px,5vw,80px);margin:16px 0 0;color:var(--ink);line-height:0.9;"></h2>
      </div>
    </div>

    {{-- Columna derecha: info + compra --}}
    <div style="padding:48px;display:flex;flex-direction:column;gap:22px;overflow-y:auto;">

      <div>
        <div class="mono" style="font-size:11px;color:var(--ink-dim);margin-bottom:6px;">Artistas / Organizador</div>
        <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
          <p id="modal-artista" style="font-size:17px;margin:0;font-weight:600;"></p>
          @auth
            @if(!Auth::user()->isAdmin() && !Auth::user()->isEmpresa())
              <button id="modal-btn-seguir"
                      style="display:none;align-items:center;gap:6px;padding:6px 14px;border:1.5px solid rgba(168,85,247,0.6);background:transparent;color:#a855f7;font-family:'Syne',sans-serif;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;cursor:pointer;transition:background 0.18s,color 0.18s;white-space:nowrap;flex-shrink:0;"
                      onclick="vibezToggleSeguirModal(this)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                <span id="modal-btn-seguir-texto">Seguir</span>
              </button>
            @endif
          @endauth
        </div>
      </div>

      <p id="modal-tagline"
         style="font-family:'Archivo Narrow',sans-serif;font-size:17px;color:var(--cream);font-style:italic;margin:0;border-left:2px solid var(--magenta);padding-left:14px;"></p>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;padding-top:16px;border-top:1px solid var(--line);">
        @foreach([['modal-fecha','Fecha'],['modal-hora','Hora'],['modal-lugar','Sala'],['modal-ciudad','Ciudad']] as [$elId, $label])
          <div>
            <div class="mono" style="font-size:10px;color:var(--ink-dim);margin-bottom:4px;">{{ $label }}</div>
            <div id="{{ $elId }}" style="font-size:14px;font-weight:600;"></div>
          </div>
        @endforeach
        <div>
          <div class="mono" style="font-size:10px;color:var(--ink-dim);margin-bottom:4px;">Disponibilidad</div>
          <div id="modal-cupos" style="font-size:14px;font-weight:600;"></div>
        </div>
      </div>

      {{-- Precio + cantidad + comprar --}}
      <div style="margin-top:auto;padding-top:20px;border-top:1px solid var(--line);">
        <div style="display:flex;align-items:baseline;justify-content:space-between;margin-bottom:16px;">
          <span class="mono" style="font-size:11px;color:var(--ink-dim);">Precio por entrada</span>
          <span id="modal-precio" class="display" style="font-size:52px;color:var(--magenta);"></span>
        </div>

        {{-- Selector de cantidad --}}
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
          <span class="mono" style="font-size:11px;color:var(--ink-dim);">Cantidad</span>
          <div style="display:flex;align-items:center;gap:0;border:1px solid var(--line);">
            <button onclick="vibezCantidad(-1)"
                    style="width:36px;height:36px;background:transparent;border:none;color:var(--ink);cursor:pointer;font-size:18px;display:flex;align-items:center;justify-content:center;">−</button>
            <input id="modal-cantidad" type="number" value="1" min="1" max="10" readonly
                   style="width:40px;height:36px;text-align:center;background:transparent;border:none;border-left:1px solid var(--line);border-right:1px solid var(--line);color:var(--ink);font-family:'Anton',sans-serif;font-size:16px;outline:none;">
            <button onclick="vibezCantidad(1)"
                    style="width:36px;height:36px;background:transparent;border:none;color:var(--ink);cursor:pointer;font-size:18px;display:flex;align-items:center;justify-content:center;">+</button>
          </div>
        </div>

        {{-- Campo de cupón de descuento --}}
        <div id="modal-cupon-wrap" style="margin-bottom:14px;">
          <div style="display:flex;gap:8px;align-items:stretch;">
            <input id="modal-cupon-codigo"
                   type="text"
                   placeholder="Código de cupón (opcional)"
                   maxlength="50"
                   style="flex:1;background:rgba(255,255,255,0.06);border:1px solid var(--line);color:var(--ink);
                          font-family:'Archivo Narrow',sans-serif;font-size:13px;padding:10px 14px;
                          border-radius:0;letter-spacing:0.08em;text-transform:uppercase;outline:none;
                          transition:border-color 0.2s;"
                   oninput="vibezResetCupon()"
                   onfocus="this.style.borderColor='rgba(168,85,247,0.5)'"
                   onblur="this.style.borderColor='var(--line)'">
            <button onclick="vibezValidarCupon()"
                    id="btn-aplicar-cupon"
                    style="background:rgba(124,58,237,0.2);border:1px solid rgba(124,58,237,0.35);
                           color:#a855f7;padding:10px 16px;cursor:pointer;
                           font-family:'Archivo Narrow',sans-serif;font-size:11px;font-weight:700;
                           text-transform:uppercase;letter-spacing:0.1em;white-space:nowrap;
                           transition:all 0.18s;border-radius:0;"
                    onmouseover="this.style.background='rgba(124,58,237,0.35)'"
                    onmouseout="this.style.background='rgba(124,58,237,0.2)'">
              Aplicar
            </button>
          </div>
          <div id="modal-cupon-msg"
               style="font-family:'Archivo Narrow',sans-serif;font-size:11px;
                      margin-top:6px;display:none;letter-spacing:0.06em;"></div>
          {{-- Resumen de precio con descuento --}}
          <div id="modal-cupon-resumen"
               style="display:none;margin-top:10px;padding:10px 14px;
                      background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);
                      font-family:'Archivo Narrow',sans-serif;font-size:12px;">
            <div style="display:flex;justify-content:space-between;color:rgba(245,241,234,0.5);">
              <span>Precio original:</span>
              <span id="modal-precio-original">—</span>
            </div>
            <div style="display:flex;justify-content:space-between;color:#4ade80;margin-top:4px;font-weight:700;">
              <span>Cupón aplicado:</span>
              <span id="modal-ahorro">—</span>
            </div>
            <div style="display:flex;justify-content:space-between;color:#f5f1ea;margin-top:4px;font-weight:800;font-size:14px;">
              <span>Total:</span>
              <span id="modal-precio-final">—</span>
            </div>
          </div>
        </div>

        <button id="modal-comprar"
                class="btn-primary"
                onclick="vibezBuy(this.dataset.eventoId)"
                style="width:100%;padding:18px;font-size:17px;border-radius:999px;">
          Comprar entrada →
        </button>
        <button onclick="event.stopPropagation();vibezToggleFav(document.getElementById('modal-comprar').dataset.eventoId, this)"
                class="btn-ghost"
                style="width:100%;padding:13px;font-size:13px;border-radius:999px;margin-top:10px;">
          ♡ Guardar para luego
        </button>
      </div>

    </div>
  </div>
</div>

<script>
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
</script>

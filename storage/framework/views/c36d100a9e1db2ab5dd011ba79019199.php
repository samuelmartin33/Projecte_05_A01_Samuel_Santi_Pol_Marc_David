
<div id="vibez-detail-modal"
     class="modal-back"
     style="display:none;align-items:center;justify-content:center;"
     onclick="vibezCloseModal()">

  <div class="modal-card detail-modal"
       onclick="event.stopPropagation()"
       style="position:fixed;inset:5% 8%;background:var(--bg);border:1px solid var(--line);overflow:auto;display:grid;grid-template-columns:1.2fr 1fr;">

    
    <div style="position:relative;overflow:hidden;min-height:520px;">
      <img id="modal-img" src="" alt="" style="width:100%;height:100%;object-fit:cover;filter:contrast(1.05) saturate(1.1) brightness(0.85);">
      <div style="position:absolute;inset:0;background:linear-gradient(180deg,transparent 50%,rgba(7,6,12,0.7));"></div>
      <button onclick="vibezCloseModal()"
              style="position:absolute;top:20px;right:20px;width:44px;height:44px;border-radius:50%;background:rgba(7,6,12,0.6);border:1px solid var(--ink-faint);color:var(--ink);cursor:pointer;backdrop-filter:blur(10px);font-size:18px;">
        ×
      </button>
      <div style="position:absolute;bottom:28px;left:28px;right:28px;">
        
        <div id="modal-en-curso"
             style="display:none;align-items:center;gap:6px;background:var(--magenta);color:var(--cream);padding:5px 12px;border-radius:999px;font-family:'Archivo Narrow',sans-serif;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:10px;width:fit-content;">
          <span class="pulse-dot" style="width:7px;height:7px;border-radius:50%;background:var(--cream);"></span>
          EN CURSO AHORA
        </div>
        <span id="modal-sticker" class="sticker"></span>
        <h2 id="modal-titulo" class="display" style="font-size:clamp(40px,5vw,80px);margin:16px 0 0;color:var(--ink);line-height:0.9;"></h2>
      </div>
    </div>

    
    <div style="padding:48px;display:flex;flex-direction:column;gap:22px;overflow-y:auto;">

      <div>
        <div class="mono" style="font-size:11px;color:var(--ink-dim);margin-bottom:6px;">Artistas / Organizador</div>
        <p id="modal-artista" style="font-size:17px;margin:0;font-weight:600;"></p>
      </div>

      <p id="modal-tagline"
         style="font-family:'Archivo Narrow',sans-serif;font-size:17px;color:var(--cream);font-style:italic;margin:0;border-left:2px solid var(--magenta);padding-left:14px;"></p>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;padding-top:16px;border-top:1px solid var(--line);">
        <?php $__currentLoopData = [['modal-fecha','Fecha'],['modal-hora','Hora'],['modal-lugar','Sala'],['modal-ciudad','Ciudad']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$elId, $label]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div>
            <div class="mono" style="font-size:10px;color:var(--ink-dim);margin-bottom:4px;"><?php echo e($label); ?></div>
            <div id="<?php echo e($elId); ?>" style="font-size:14px;font-weight:600;"></div>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <div>
          <div class="mono" style="font-size:10px;color:var(--ink-dim);margin-bottom:4px;">Disponibilidad</div>
          <div id="modal-cupos" style="font-size:14px;font-weight:600;"></div>
        </div>
      </div>

      
      <div style="margin-top:auto;padding-top:20px;border-top:1px solid var(--line);">
        <div style="display:flex;align-items:baseline;justify-content:space-between;margin-bottom:16px;">
          <span class="mono" style="font-size:11px;color:var(--ink-dim);">Precio por entrada</span>
          <span id="modal-precio" class="display" style="font-size:52px;color:var(--magenta);"></span>
        </div>

        
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
}
</script>
<?php /**PATH C:\wamp64\www\LARAVEL\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/partials/home/detail-modal.blade.php ENDPATH**/ ?>
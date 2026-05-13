
<section style="padding:90px 48px 0;max-width:1480px;margin:0 auto;" id="seccion-grid-eventos">

  
  <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:36px;gap:20px;flex-wrap:wrap;">
    <div>
      <div class="mono" style="font-size:11px;color:var(--magenta);margin-bottom:12px;display:flex;align-items:center;gap:10px;">
        <span style="width:28px;height:1px;background:var(--magenta);display:inline-block;"></span>
        Todos los eventos · curados por VIBEZ
      </div>
      <h2 class="display" style="font-size:clamp(48px,6vw,96px);margin:0;color:var(--ink);">
        Top <em style="font-style:italic;color:var(--magenta);font-family:'Bebas Neue',sans-serif;">Picks</em>
      </h2>
      <p style="font-family:'Archivo Narrow',sans-serif;font-size:14px;color:var(--ink-dim);margin:10px 0 0;text-transform:uppercase;letter-spacing:0.1em;">
        <span id="vibez-grid-count"><?php echo e($eventos->count()); ?></span> eventos disponibles
      </p>
    </div>

    
    <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
      <select id="grid-filtro-ubicacion"
              onchange="vibezGridFiltrar()"
              style="background:rgba(7,6,12,0.6);border:1px solid var(--line);color:var(--ink);padding:10px 16px;font-family:'Archivo Narrow',sans-serif;font-size:12px;text-transform:uppercase;letter-spacing:0.06em;cursor:pointer;appearance:none;-webkit-appearance:none;outline:none;">
        <option value="">Todas las ciudades</option>
        <?php $__currentLoopData = $ubicaciones ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($ub); ?>"><?php echo e($ub); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>

      <button onclick="vibezGridLimpiar()"
              style="background:transparent;border:1px solid var(--ink-faint);color:var(--ink-dim);padding:10px 16px;font-family:'Archivo Narrow',sans-serif;font-size:11px;text-transform:uppercase;letter-spacing:0.08em;cursor:pointer;">
        Limpiar
      </button>
    </div>
  </div>

  
  <div id="vibez-grid-spinner" style="display:none;justify-content:center;padding:48px 0;">
    <div style="width:32px;height:32px;border-radius:50%;border:2px solid var(--line);border-top-color:var(--magenta);animation:vibez-spin 0.8s linear infinite;"></div>
  </div>

  
  <div id="vibez-grid-todos"
       style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px;">

    <?php $__empty_1 = true; $__currentLoopData = $eventos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <?php $esFav = in_array($evento->id, $favoritosIds ?? [], true); ?>
      <article class="vibe-card vibez-grid-card vibez-event-card"
               data-id="<?php echo e($evento->id); ?>"
               data-categoria="<?php echo e($evento->categoria?->nombre ?? ''); ?>"
               onclick="vibezOpenModal(<?php echo e($evento->id); ?>)"
               style="cursor:pointer;">

        <div class="img-wrap" style="position:relative;aspect-ratio:3/4;overflow:hidden;">
          <img src="<?php echo e($evento->url_portada); ?>"
               alt="<?php echo e($evento->titulo); ?>"
               style="width:100%;height:100%;object-fit:cover;filter:contrast(1.05) saturate(1.1) brightness(0.85);">
          <div style="position:absolute;inset:0;background:linear-gradient(180deg,transparent 50%,rgba(7,6,12,0.88));"></div>

          
          <?php if($evento->fecha_inicio <= now() && ($evento->fecha_fin === null || $evento->fecha_fin >= now())): ?>
            <div style="position:absolute;top:12px;left:12px;background:var(--magenta);color:var(--cream);padding:4px 10px;border-radius:999px;font-family:'Archivo Narrow',sans-serif;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;display:flex;align-items:center;gap:5px;">
              <span class="pulse-dot" style="width:6px;height:6px;border-radius:50%;background:var(--cream);"></span>
              En curso
            </div>
          <?php endif; ?>

          
          <?php if($evento->sold_out): ?>
            <div style="position:absolute;top:18px;right:18px;background:var(--cream);color:var(--bg);padding:4px 12px;font-family:'Anton',sans-serif;font-size:11px;transform:rotate(4deg);">
              SOLD OUT
            </div>
          <?php endif; ?>

          
          <button onclick="event.stopPropagation();vibezToggleFav(<?php echo e($evento->id); ?>,this)"
                  data-fav-id="<?php echo e($evento->id); ?>"
                  style="position:absolute;top:18px;right:<?php echo e($evento->sold_out ? '90px' : '18px'); ?>;width:38px;height:38px;border-radius:50%;background:rgba(7,6,12,0.55);border:1px solid var(--ink-faint);color:<?php echo e($esFav ? 'var(--magenta)' : 'var(--ink)'); ?>;backdrop-filter:blur(10px);cursor:pointer;display:flex;align-items:center;justify-content:center;"
                  class="<?php echo e($esFav ? 'activo' : ''); ?>">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="<?php echo e($esFav ? 'var(--magenta)' : 'currentColor'); ?>">
              <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
          </button>

          
          <div style="position:absolute;bottom:0;left:0;right:0;padding:18px;">
            <div class="mono" style="font-size:10px;color:var(--magenta-2);margin-bottom:6px;display:flex;justify-content:space-between;">
              <span><?php echo e($evento->fecha_fmt); ?> · <?php echo e($evento->categoria?->nombre ?? ''); ?></span>
              <span><?php echo e($evento->precio_formateado); ?></span>
            </div>
            <h3 class="display" style="font-size:24px;margin:0;line-height:0.95;color:var(--ink);"><?php echo e($evento->titulo); ?></h3>
            <p style="font-family:'Archivo Narrow',sans-serif;font-size:11px;color:var(--ink-dim);margin:6px 0 0;text-transform:uppercase;letter-spacing:0.08em;">
              <?php echo e($evento->ubicacion_nombre); ?>

            </p>
          </div>
        </div>

      </article>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <p style="color:var(--ink-dim);font-family:'Archivo Narrow',sans-serif;padding:60px 0;grid-column:1/-1;text-align:center;">
        No hay eventos disponibles.
      </p>
    <?php endif; ?>

  </div>

</section>

<style>
@keyframes vibez-spin { to { transform: rotate(360deg); } }
</style>

<script>
/* Filtrado AJAX del grid */
function vibezGridFiltrar() {
  var cat      = document.getElementById('grid-filtro-cat')?.value || '';
  var ubicacion = document.getElementById('grid-filtro-ubicacion')?.value || '';
  var grid     = document.getElementById('vibez-grid-todos');
  var spinner  = document.getElementById('vibez-grid-spinner');
  var countEl  = document.getElementById('vibez-grid-count');

  if (spinner) spinner.style.display = 'flex';

  var url = '/api/filtrar?categoria=' + encodeURIComponent(cat) + '&ubicacion=' + encodeURIComponent(ubicacion);

  fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if (spinner) spinner.style.display = 'none';
      var eventos = data.eventos || [];
      if (countEl) countEl.textContent = eventos.length;
      if (!grid) return;
      if (!eventos.length) {
        grid.innerHTML = '<p style="color:var(--ink-dim);font-family:\'Archivo Narrow\',sans-serif;padding:60px 0;text-align:center;grid-column:1/-1;">No hay eventos para estos filtros.</p>';
        return;
      }
      grid.innerHTML = eventos.map(function(e) {
        var esFav = (window.FAVORITOS_IDS || []).includes(e.id);
        return '<article class="vibe-card vibez-grid-card" data-id="' + e.id + '" onclick="vibezOpenModal(' + e.id + ')" style="cursor:pointer;">'
          + '<div class="img-wrap" style="position:relative;aspect-ratio:3/4;overflow:hidden;">'
          + '<img src="' + (e.img || e.url_portada || '') + '" alt="' + e.titulo + '" style="width:100%;height:100%;object-fit:cover;filter:contrast(1.05) saturate(1.1) brightness(0.85);">'
          + '<div style="position:absolute;inset:0;background:linear-gradient(180deg,transparent 50%,rgba(7,6,12,0.88));"></div>'
          + (e.estaOcurriendo ? '<div style="position:absolute;top:12px;left:12px;background:var(--magenta);color:var(--cream);padding:4px 10px;border-radius:999px;font-family:\'Archivo Narrow\',sans-serif;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;display:flex;align-items:center;gap:5px;"><span style="width:6px;height:6px;border-radius:50%;background:var(--cream);"></span>En curso</div>' : '')
          + '<button onclick="event.stopPropagation();vibezToggleFav(' + e.id + ',this)" data-fav-id="' + e.id + '" class="' + (esFav ? 'activo' : '') + '" style="position:absolute;top:18px;right:18px;width:38px;height:38px;border-radius:50%;background:rgba(7,6,12,0.55);border:1px solid var(--ink-faint);color:var(--ink);backdrop-filter:blur(10px);cursor:pointer;display:flex;align-items:center;justify-content:center;">'
          + '<svg width="14" height="14" viewBox="0 0 24 24" fill="' + (esFav ? 'var(--magenta)' : 'currentColor') + '"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>'
          + '</button>'
          + '<div style="position:absolute;bottom:0;left:0;right:0;padding:18px;">'
          + '<div class="mono" style="font-size:10px;color:var(--magenta-2);margin-bottom:6px;display:flex;justify-content:space-between;"><span>' + (e.fecha_fmt || e.fechaFmt || '') + ' · ' + (e.categoria || '') + '</span><span>' + (e.precio_formateado || e.precio || '') + '</span></div>'
          + '<h3 class="display" style="font-size:24px;margin:0;line-height:0.95;">' + e.titulo + '</h3>'
          + '<p style="font-family:\'Archivo Narrow\',sans-serif;font-size:11px;color:var(--ink-dim);margin:6px 0 0;text-transform:uppercase;letter-spacing:0.08em;">' + (e.ubicacion_nombre || e.lugar || '') + '</p>'
          + '</div></div></article>';
      }).join('');
    })
    .catch(function() {
      if (spinner) spinner.style.display = 'none';
    });
}

function vibezGridLimpiar() {
  var ub = document.getElementById('grid-filtro-ubicacion');
  if (ub) ub.value = '';
  /* Resetear chip activo a "Todo" */
  document.querySelectorAll('.vibez-cat-chip').forEach(function(c) {
    c.classList.toggle('active', c.dataset.cat === 'Todo');
  });
  vibezActiveCategoria = 'Todo';
  vibezGridFiltrar();
}
</script>
<?php /**PATH C:\wamp64\www\LARAVEL\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/partials/home/grid-eventos.blade.php ENDPATH**/ ?>
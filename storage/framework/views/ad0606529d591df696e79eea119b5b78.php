<?php $__env->startSection('titulo', 'Mapa de eventos — VIBEZ'); ?>

<?php $__env->startSection('content'); ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<link rel="stylesheet" href="<?php echo e(asset('css/vibez-home.css')); ?>">

<script>
  window.EVENTOS_DATA  = <?php echo json_encode($eventosParaJs ?? [], 15, 512) ?>;
  window.FAVORITOS_IDS = <?php echo json_encode($favoritosIds ?? [], 15, 512) ?>;
  window.USER_AUTH     = <?php echo json_encode(Auth::check(), 15, 512) ?>;
  window.LOGIN_URL     = <?php echo json_encode(route('login'), 15, 512) ?>;
</script>


<?php echo $__env->make('partials.home.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<div style="position:relative;height:calc(100vh - 80px);display:flex;">

  
  <div id="mapa-panel"
       style="display:none;width:300px;flex-shrink:0;background:var(--bg);border-right:1px solid var(--line);overflow-y:auto;z-index:10;position:relative;">
  </div>

  
  <div style="flex:1;position:relative;">
    <div id="vibez-map-full" style="width:100%;height:100%;"></div>

    
    <div style="position:absolute;top:16px;left:50%;transform:translateX(-50%);z-index:400;background:rgba(7,6,12,0.9);backdrop-filter:blur(14px);border:1px solid var(--line);padding:12px 20px;display:flex;align-items:center;gap:12px;flex-wrap:wrap;min-width:320px;">
      <span class="mono" style="font-size:10px;color:var(--ink-dim);">
        <?php echo e(count($eventosParaJs ?? [])); ?> eventos en el mapa
      </span>
      <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <button class="chip vibez-cat-chip" data-cat="<?php echo e($cat->nombre); ?>"
                  onclick="vibezFiltrarMapa('<?php echo e($cat->nombre); ?>')"
                  style="font-size:10px;padding:4px 10px;">
            <?php echo e($cat->nombre); ?>

          </button>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <button class="chip active vibez-cat-chip" data-cat="Todo"
                onclick="vibezFiltrarMapa('Todo')"
                style="font-size:10px;padding:4px 10px;">
          Todo
        </button>
      </div>
    </div>

    
    <div style="position:absolute;bottom:20px;left:20px;z-index:400;background:rgba(7,6,12,0.9);backdrop-filter:blur(10px);padding:14px;border:1px solid var(--line);">
      <div class="mono" style="font-size:9px;color:var(--ink-dim);margin-bottom:8px;">LEYENDA</div>
      <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
        <span style="width:12px;height:12px;border-radius:50%;background:var(--magenta);display:inline-block;"></span>
        <span style="font-size:11px;">Próximo</span>
      </div>
      <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
        <span style="width:16px;height:16px;border-radius:50%;background:var(--magenta);box-shadow:0 0 12px var(--magenta);display:inline-block;"></span>
        <span style="font-size:11px;">Featured / destacado</span>
      </div>
      <div style="display:flex;align-items:center;gap:8px;">
        <span style="width:16px;height:16px;border-radius:50%;background:#00ff88;box-shadow:0 0 12px #00ff88;display:inline-block;"></span>
        <span style="font-size:11px;">En curso ahora</span>
      </div>
    </div>

    
    <a href="<?php echo e(url()->previous() !== url()->current() ? url()->previous() : route('home')); ?>"
       style="position:absolute;top:16px;right:16px;z-index:400;background:rgba(7,6,12,0.85);border:1px solid var(--line);color:var(--ink);padding:8px 16px;text-decoration:none;font-family:'Archivo Narrow',sans-serif;font-size:12px;text-transform:uppercase;letter-spacing:0.08em;backdrop-filter:blur(10px);">
      ← Volver
    </a>
  </div>
</div>


<?php echo $__env->make('partials.home.detail-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div id="vibez-toast" class="toast" style="display:none;"></div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="<?php echo e(asset('js/vibez-home.js')); ?>"></script>
<script>
  vibezInitMapFull();

  function vibezFiltrarMapa(cat) {
    document.querySelectorAll('.vibez-cat-chip').forEach(function(c) {
      c.classList.toggle('active', c.dataset.cat === cat);
    });
    /* Filtrar marcadores ya cargados no es trivial con Leaflet sin LayerGroups.
       Recargamos el mapa con los eventos filtrados. */
    window.EVENTOS_DATA_ORIGINAL = window.EVENTOS_DATA_ORIGINAL || window.EVENTOS_DATA;
    window.EVENTOS_DATA = cat === 'Todo'
      ? window.EVENTOS_DATA_ORIGINAL
      : window.EVENTOS_DATA_ORIGINAL.filter(function(e) { return e.categoria === cat; });

    /* Destruir y reinicializar el mapa */
    var container = document.getElementById('vibez-map-full');
    if (container && container._leaflet_id) {
      container._leaflet_id = null;
      container.innerHTML = '';
    }
    vibezInitMapFull();
  }
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/mapa.blade.php ENDPATH**/ ?>
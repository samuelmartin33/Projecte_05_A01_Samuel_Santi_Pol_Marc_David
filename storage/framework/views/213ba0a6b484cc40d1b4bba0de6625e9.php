<?php $__env->startSection('titulo', 'VIBEZ — Descubre tu próximo evento'); ?>


<?php $__env->startSection('content'); ?>


<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<link rel="stylesheet" href="<?php echo e(asset('css/vibez-home.css')); ?>">
<style>
  /* Pin "en curso" para el mapa */
  .vibez-pin.happening {
    background: linear-gradient(135deg, #00ff88, #00cc66) !important;
    box-shadow: 0 0 0 3px rgba(0,255,136,0.4), 0 0 22px rgba(0,255,136,0.7) !important;
    animation: vibez-pulse 1.4s ease-in-out infinite !important;
  }
  @keyframes vibez-pulse {
    0%, 100% { box-shadow: 0 0 0 3px rgba(0,255,136,0.4), 0 0 22px rgba(0,255,136,0.7); }
    50%       { box-shadow: 0 0 0 6px rgba(0,255,136,0.2), 0 0 36px rgba(0,255,136,0.9); }
  }

  /* Popup de Leaflet con estilo VIBEZ */
  .vibez-leaflet-popup .leaflet-popup-content-wrapper {
    background: var(--bg, #07060c);
    border: 1px solid rgba(245,241,234,0.12);
    border-radius: 0;
    padding: 0;
    box-shadow: 0 20px 50px rgba(0,0,0,0.7);
    color: #f5f1ea;
    min-width: 220px;
    overflow: hidden;
  }
  .vibez-leaflet-popup .leaflet-popup-content { margin: 0; width: 220px !important; }
  .vibez-leaflet-popup .leaflet-popup-tip-container { display: none; }
  .vibez-leaflet-popup .leaflet-popup-close-button {
    color: rgba(245,241,234,0.5) !important;
    font-size: 18px !important;
    padding: 8px 10px !important;
    z-index: 10;
  }
  .vibez-map-popup img { display: block; }

  /* Grid responsive para los eventos */
  .vibez-grid-card { border-radius: 0; }
  .vibez-grid-card .img-wrap { border-radius: 0; }

  /* Logged hero grid responsive */
  @media (max-width: 768px) {
    .logged-hero-grid { grid-template-columns: 1fr !important; }
  }
</style>


<script>
  window.EVENTOS_DATA   = <?php echo json_encode($eventosParaJs ?? [], 15, 512) ?>;
  window.FAVORITOS_IDS  = <?php echo json_encode($favoritosIds ?? [], 15, 512) ?>;
  window.CATEGORIAS     = <?php echo json_encode($categorias->pluck('nombre')->prepend('Todo')->values(), 15, 512) ?>;
  window.USER_AUTH      = <?php echo json_encode(Auth::check(), 15, 512) ?>;
  window.LOGIN_URL      = <?php echo json_encode(route('login'), 15, 512) ?>;
</script>


<?php echo $__env->make('partials.home.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<?php if(auth()->guard()->check()): ?>
  <?php echo $__env->make('partials.home.logged-hero', [
    'user'    => Auth::user(),
    'eventos' => $eventos,
  ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php else: ?>
  <?php echo $__env->make('partials.home.hero-poster', ['eventoFeatured' => $eventoFeatured], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>


<?php if(auth()->guard()->check()): ?>
  
  <?php echo $__env->make('partials.home.para-ti', [
    'user'    => Auth::user(),
    'eventos' => $eventos->take(6),
  ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>


<?php echo $__env->make('partials.home.marquee', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<section style="position:sticky;top:0;z-index:30;background:rgba(7,6,12,0.92);backdrop-filter:blur(18px);border-bottom:1px solid var(--line);padding:16px 48px;">
  <div style="max-width:1480px;margin:0 auto;display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
    <span class="mono" style="font-size:11px;color:var(--ink-dim);white-space:nowrap;">
      <span id="vibez-count-label"><?php echo e(isset($totalEventos) ? $totalEventos : count($eventosParaJs ?? [])); ?></span> eventos
    </span>
    <div class="no-scrollbar" style="overflow-x:auto;white-space:nowrap;padding:4px 0;flex:1;">
      <div style="display:inline-flex;gap:10px;">
        <button class="chip active vibez-cat-chip" data-cat="Todo"
                onclick="vibezFilterCategoria('Todo')">Todo</button>
        <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <button class="chip vibez-cat-chip" data-cat="<?php echo e($cat->nombre); ?>"
                  onclick="vibezFilterCategoria('<?php echo e($cat->nombre); ?>')"><?php echo e($cat->nombre); ?></button>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div>
  </div>
</section>


<?php if(auth()->guard()->guest()): ?>
  
  <?php echo $__env->make('partials.home.carousel', [
    'carouselId' => 'carousel-destacados',
    'kicker'     => 'Lo mejor de esta semana',
    'titulo'     => 'Highlights',
    'subtitulo'  => 'Selección editorial',
    'eventos'    => $eventos->take(8),
    'big'        => false,
  ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>


<?php echo $__env->make('partials.home.grid-eventos', [
  'eventos'      => $eventos,
  'categorias'   => $categorias,
  'favoritosIds' => $favoritosIds ?? [],
  'ubicaciones'  => $ubicaciones ?? [],
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<?php echo $__env->make('partials.home.map-eventos', [
  'totalEventos'  => count($eventosParaJs ?? []),
  'eventosParaJs' => $eventosParaJs ?? [],
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<?php echo $__env->make('partials.home.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<?php echo $__env->make('partials.home.detail-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<div id="vibez-toast" class="toast" style="display:none;"></div>


<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="<?php echo e(asset('js/vibez-home.js')); ?>"></script>
<script>
  vibezInitMap();

  /* El filtro por categoría también actualiza el grid */
  var _vibezFilterCatOriginal = vibezFilterCategoria;
  vibezFilterCategoria = function(cat) {
    _vibezFilterCatOriginal(cat);
    /* Sync con el select de ubicación del grid */
    vibezActiveCategoria = cat;
    var ub = document.getElementById('grid-filtro-ubicacion');
    var ubVal = ub ? ub.value : '';
    var url = '/api/filtrar?categoria=' + encodeURIComponent(cat === 'Todo' ? '' : cat)
            + '&ubicacion=' + encodeURIComponent(ubVal);
    var spinner = document.getElementById('vibez-grid-spinner');
    var grid    = document.getElementById('vibez-grid-todos');
    var countEl = document.getElementById('vibez-grid-count');
    if (spinner) spinner.style.display = 'flex';
    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then(function(r) { return r.json(); })
      .then(function(data) {
        if (spinner) spinner.style.display = 'none';
        var eventos = data.eventos || [];
        if (countEl) countEl.textContent = eventos.length;
        var mainCount = document.getElementById('vibez-count-label');
        if (mainCount) mainCount.textContent = eventos.length;
        if (grid) _vibezRenderGrid(grid, eventos);
      })
      .catch(function() { if (spinner) spinner.style.display = 'none'; });
  };
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/home.blade.php ENDPATH**/ ?>
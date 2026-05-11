<?php
$marqueeItems = [
    'Esta noche se rompe',
    'Charlotte de Witte · 09 May',
    'Primavera Sound · phase 3',
    'No te lo pierdas',
    'BCN never sleeps',
    'Lista negra · membership',
    'El mejor techno de la escena',
    'Pacha · Bad Bunny · 21 Jun',
];
?>

<div style="overflow:hidden;border-top:1px solid var(--line);border-bottom:1px solid var(--line);padding:18px 0;">
  <div class="marquee-track">
    <?php $__currentLoopData = array_merge($marqueeItems, $marqueeItems, $marqueeItems); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <span class="display" style="font-size:clamp(36px,5vw,72px);color:var(--ink);opacity:<?php echo e($loop->index % 3 === 1 ? '1' : '0.45'); ?>;display:inline-flex;align-items:center;gap:32px;">
        <?php echo e($item); ?>

        <span style="color:var(--magenta);font-size:0.6em;">✦</span>
      </span>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>
</div>
<?php /**PATH C:\wamp64\www\DAW2\proyectos\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/partials/home/marquee.blade.php ENDPATH**/ ?>

<section style="padding:60px 48px 40px;max-width:1480px;margin:0 auto;">

  <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:28px;gap:20px;flex-wrap:wrap;">
    <div>
      <div class="mono" style="font-size:11px;color:var(--magenta);margin-bottom:12px;display:flex;align-items:center;gap:10px;">
        <span style="width:28px;height:1px;background:var(--magenta);display:inline-block;"></span>
        Mis tickets · <?php echo e(count($entradas ?? [])); ?> activos
      </div>
      <h2 class="display" style="font-size:clamp(40px,6vw,80px);margin:0;">
        Lista <em style="font-style:italic;color:var(--magenta);font-family:'Bebas Neue',sans-serif;">VIP</em>
      </h2>
    </div>
    <a href="#" class="mono" style="font-size:11px;color:var(--magenta-2);text-decoration:none;border-bottom:1px solid currentColor;">
      Ver historial →
    </a>
  </div>

  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:16px;" class="tickets-grid">

    <?php $__empty_1 = true; $__currentLoopData = $entradas ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $entrada): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <?php $ev = $entrada->evento ?? null; ?>
    <?php if($ev): ?>
    <div onclick="vibezOpenModal(<?php echo e($ev->id); ?>)"
         style="display:grid;grid-template-columns:1fr 1px 90px;background:linear-gradient(135deg,rgba(168,85,247,0.08),rgba(13,10,24,0.7));border:1px solid rgba(168,85,247,0.3);border-radius:14px;overflow:hidden;cursor:pointer;position:relative;transition:transform 0.25s ease,box-shadow 0.25s ease;"
         onmouseenter="this.style.transform='translateY(-3px)';this.style.boxShadow='0 14px 30px rgba(168,85,247,0.3)'"
         onmouseleave="this.style.transform='';this.style.boxShadow=''">

      
      <div style="padding:16px 18px;">
        <div class="mono" style="font-size:9px;color:var(--magenta-2);margin-bottom:6px;">
          <?php echo e($ev->categoria?->nombre ?? 'Evento'); ?> · <?php echo e($entrada->cantidad ?? 1); ?>× ENTRADA
        </div>
        <div class="display" style="font-size:18px;line-height:1;margin-bottom:8px;"><?php echo e($ev->titulo); ?></div>
        <div style="font-size:11px;color:var(--ink-dim);font-family:'Archivo Narrow',sans-serif;text-transform:uppercase;letter-spacing:0.1em;">
          <?php echo e($ev->fecha_fmt); ?> · <?php echo e($ev->hora); ?>

        </div>
        <div style="font-size:11px;color:var(--ink-dim);font-family:'Archivo Narrow',sans-serif;text-transform:uppercase;letter-spacing:0.1em;margin-top:2px;">
          <?php echo e($ev->ubicacion_nombre); ?>

        </div>
      </div>

      
      <div style="background:repeating-linear-gradient(0deg,var(--magenta) 0 4px,transparent 4px 8px);"></div>

      
      <div style="padding:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px;">
        <div style="width:56px;height:56px;background:var(--cream);border-radius:6px;padding:4px;display:flex;align-items:center;justify-content:center;">
          
          <svg width="48" height="48" viewBox="0 0 48 48">
            <rect width="48" height="48" fill="white"/>
            <g fill="black">
              <?php for($k = 0; $k < 64; $k++): ?>
                <?php if((($i * 31 + $k * 7) % 100) < 50): ?>
                  <rect x="<?php echo e(($k % 8) * 6); ?>" y="<?php echo e(floor($k / 8) * 6); ?>" width="6" height="6"/>
                <?php endif; ?>
              <?php endfor; ?>
            </g>
          </svg>
        </div>
        <div class="mono" style="font-size:8px;color:var(--ink-dim);">#<?php echo e($entrada->id ?? ('VBZ-' . ($i + 1))); ?></div>
      </div>

    </div>
    <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <p style="color:var(--ink-dim);font-family:'Archivo Narrow',sans-serif;padding:24px 0;grid-column:1/-1;">
        No tienes tickets activos.
      </p>
    <?php endif; ?>

  </div>

</section>
<?php /**PATH C:\wamp64\www\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/partials/home/mis-tickets.blade.php ENDPATH**/ ?>
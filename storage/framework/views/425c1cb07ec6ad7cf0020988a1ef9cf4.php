<section style="padding:90px 48px 0;max-width:1480px;margin:0 auto;" class="map-section">

  <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:36px;gap:20px;flex-wrap:wrap;">
    <div>
      <div class="mono" style="font-size:11px;color:var(--magenta);margin-bottom:12px;display:flex;align-items:center;gap:10px;">
        <span style="width:28px;height:1px;background:var(--magenta);display:inline-block;"></span>
        La ciudad ardiendo
      </div>
      <h2 class="display" style="font-size:clamp(48px,6vw,96px);margin:0;">
        BCN <em style="font-style:italic;color:var(--magenta);font-family:'Bebas Neue',sans-serif;">en llamas</em>
      </h2>
      <p style="font-family:'Archivo Narrow',sans-serif;font-size:14px;color:var(--ink-dim);margin:12px 0 0;text-transform:uppercase;letter-spacing:0.1em;">
        <?php echo e(isset($totalEventos) ? $totalEventos : count($eventosParaJs ?? [])); ?> eventos activos · radio 8 km
      </p>
    </div>
    
    <a href="<?php echo e(route('mapa')); ?>"
       style="display:inline-flex;align-items:center;gap:8px;background:transparent;border:1px solid var(--ink-faint);color:var(--ink);padding:12px 24px;text-decoration:none;font-family:'Archivo Narrow',sans-serif;font-size:12px;text-transform:uppercase;letter-spacing:0.1em;transition:border-color 0.2s,color 0.2s;"
       onmouseenter="this.style.borderColor='var(--magenta)';this.style.color='var(--magenta)'"
       onmouseleave="this.style.borderColor='var(--ink-faint)';this.style.color='var(--ink)'">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M8 3H5a2 2 0 00-2 2v3m18 0V5a2 2 0 00-2-2h-3m0 18h3a2 2 0 002-2v-3M3 16v3a2 2 0 002 2h3"/>
      </svg>
      Ver en grande
    </a>
  </div>

  
  <div style="position:relative;height:560px;border:1px solid var(--line);overflow:hidden;">
    <div id="vibez-map" style="width:100%;height:100%;"></div>

    
    <div style="position:absolute;top:20px;left:20px;background:rgba(7,6,12,0.9);backdrop-filter:blur(10px);padding:16px;border:1px solid var(--line);max-width:210px;z-index:400;">
      <div class="mono" style="font-size:10px;color:var(--ink-dim);margin-bottom:10px;">LEYENDA</div>
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
        <span style="width:14px;height:14px;border-radius:50%;background:linear-gradient(135deg,var(--magenta),var(--morado));flex-shrink:0;"></span>
        <span style="font-size:12px;">Próximo evento</span>
      </div>
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
        <span style="width:18px;height:18px;border-radius:50%;background:linear-gradient(135deg,var(--magenta),var(--morado));box-shadow:0 0 14px var(--magenta);flex-shrink:0;"></span>
        <span style="font-size:12px;">Featured · destacado</span>
      </div>
      <div style="display:flex;align-items:center;gap:10px;">
        <span style="width:18px;height:18px;border-radius:50%;background:#00ff88;box-shadow:0 0 14px #00ff88;flex-shrink:0;"></span>
        <span style="font-size:12px;">En curso ahora</span>
      </div>
    </div>

    
    <a href="<?php echo e(route('mapa')); ?>"
       style="position:absolute;bottom:20px;right:20px;z-index:400;background:rgba(7,6,12,0.9);backdrop-filter:blur(10px);border:1px solid var(--magenta);color:var(--magenta);padding:10px 18px;text-decoration:none;font-family:'Archivo Narrow',sans-serif;font-size:11px;text-transform:uppercase;letter-spacing:0.1em;display:flex;align-items:center;gap:6px;">
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M8 3H5a2 2 0 00-2 2v3m18 0V5a2 2 0 00-2-2h-3m0 18h3a2 2 0 002-2v-3M3 16v3a2 2 0 002 2h3"/>
      </svg>
      Abrir mapa completo
    </a>
  </div>

</section>
<?php /**PATH C:\wamp64\www\LARAVEL\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/partials/home/map-eventos.blade.php ENDPATH**/ ?>
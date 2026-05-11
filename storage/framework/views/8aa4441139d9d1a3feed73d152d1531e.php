<footer style="margin-top:100px;padding:60px 48px 36px;border-top:1px solid var(--line);">
  <div style="max-width:1480px;margin:0 auto;">

    
    <div class="display" style="font-size:clamp(80px,14vw,240px);line-height:0.85;color:transparent;-webkit-text-stroke:1.5px var(--ink-faint);">
      VIBEZ ✦ <em style="font-style:italic;color:var(--magenta);-webkit-text-stroke:0;font-family:'Bebas Neue',sans-serif;">NIGHTS</em>
    </div>

    
    <div style="margin-top:60px;display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:32px;padding-top:32px;border-top:1px solid var(--line);">

      <div>
        <div class="mono" style="font-size:10px;color:var(--magenta);margin-bottom:14px;">Plataforma</div>
        <a href="<?php echo e(route('home')); ?>"       style="display:block;font-size:13px;color:var(--ink-dim);text-decoration:none;padding:4px 0;">Explorar</a>
        <a href="<?php echo e(route('home')); ?>"       style="display:block;font-size:13px;color:var(--ink-dim);text-decoration:none;padding:4px 0;">Esta noche</a>
        <?php if(auth()->guard()->check()): ?>
        <a href="<?php echo e(route('entradas.mis-entradas')); ?>" style="display:block;font-size:13px;color:var(--ink-dim);text-decoration:none;padding:4px 0;">Mis entradas</a>
        <?php endif; ?>
        <a href="<?php echo e(route('trabajos.index')); ?>" style="display:block;font-size:13px;color:var(--ink-dim);text-decoration:none;padding:4px 0;">Bolsa de trabajo</a>
        <a href="<?php echo e(route('mapa')); ?>"       style="display:block;font-size:13px;color:var(--ink-dim);text-decoration:none;padding:4px 0;">Mapa de eventos</a>
      </div>

      <div>
        <div class="mono" style="font-size:10px;color:var(--magenta);margin-bottom:14px;">Para empresas</div>
        <?php if(\Route::has('empresa.eventos.create')): ?>
        <a href="<?php echo e(route('empresa.eventos.create')); ?>" style="display:block;font-size:13px;color:var(--ink-dim);text-decoration:none;padding:4px 0;">Crear evento</a>
        <?php endif; ?>
        <?php if(\Route::has('empresa.home')): ?>
        <a href="<?php echo e(route('empresa.home')); ?>" style="display:block;font-size:13px;color:var(--ink-dim);text-decoration:none;padding:4px 0;">Panel empresa</a>
        <?php endif; ?>
        <?php if(\Route::has('empresa.ofertas.create')): ?>
        <a href="<?php echo e(route('empresa.ofertas.create')); ?>" style="display:block;font-size:13px;color:var(--ink-dim);text-decoration:none;padding:4px 0;">Publicar oferta</a>
        <?php endif; ?>
        <a href="<?php echo e(route('register')); ?>"   style="display:block;font-size:13px;color:var(--ink-dim);text-decoration:none;padding:4px 0;">Registro empresa</a>
      </div>

      <div>
        <div class="mono" style="font-size:10px;color:var(--magenta);margin-bottom:14px;">Vibez</div>
        <a href="<?php echo e(route('quienes-somos')); ?>" style="display:block;font-size:13px;color:var(--ink-dim);text-decoration:none;padding:4px 0;">Quiénes somos</a>
        <a href="<?php echo e(route('manifiesto')); ?>"    style="display:block;font-size:13px;color:var(--ink-dim);text-decoration:none;padding:4px 0;">Manifiesto</a>
        <a href="<?php echo e(route('prensa')); ?>"        style="display:block;font-size:13px;color:var(--ink-dim);text-decoration:none;padding:4px 0;">Prensa</a>
        <a href="<?php echo e(route('contacto')); ?>"      style="display:block;font-size:13px;color:var(--ink-dim);text-decoration:none;padding:4px 0;">Contacto</a>
      </div>

      <div>
        <div class="mono" style="font-size:10px;color:var(--magenta);margin-bottom:14px;">Legal</div>
        <a href="<?php echo e(route('privacidad')); ?>"    style="display:block;font-size:13px;color:var(--ink-dim);text-decoration:none;padding:4px 0;">Privacidad</a>
        <a href="<?php echo e(route('cookies')); ?>"       style="display:block;font-size:13px;color:var(--ink-dim);text-decoration:none;padding:4px 0;">Cookies</a>
        <a href="<?php echo e(route('terminos')); ?>"      style="display:block;font-size:13px;color:var(--ink-dim);text-decoration:none;padding:4px 0;">Términos</a>
        <a href="<?php echo e(route('devoluciones')); ?>"  style="display:block;font-size:13px;color:var(--ink-dim);text-decoration:none;padding:4px 0;">Devoluciones</a>
      </div>

    </div>

    
    <div style="margin-top:40px;padding-top:24px;border-top:1px solid var(--line);display:flex;justify-content:space-between;flex-wrap:wrap;gap:16px;">
      <span class="mono" style="font-size:10px;color:var(--ink-dim);">© <?php echo e(date('Y')); ?> VIBEZ · BCN · MAD · LIS</span>
      <span class="mono" style="font-size:10px;color:var(--ink-dim);">Made for the night</span>
    </div>

  </div>
</footer>
<?php /**PATH C:\wamp64\www\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/partials/home/footer.blade.php ENDPATH**/ ?>
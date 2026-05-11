<?php $__env->startSection('titulo', 'Prensa — VIBEZ'); ?>
<?php $__env->startSection('contenido'); ?>

<div style="max-width:860px;margin:80px auto;padding:0 32px;">
  <div class="mono" style="font-size:11px;color:var(--magenta);margin-bottom:16px;display:flex;align-items:center;gap:10px;">
    <span style="width:28px;height:1px;background:var(--magenta);display:inline-block;"></span>
    Media kit
  </div>
  <h1 class="display" style="font-size:clamp(56px,8vw,120px);margin:0 0 48px;line-height:0.88;color:var(--ink);">
    Prensa<br><em style="color:var(--magenta);font-style:italic;">& media</em>.
  </h1>

  <div style="font-family:'Archivo Narrow',sans-serif;font-size:17px;color:var(--ink-dim);line-height:1.7;margin-bottom:48px;">
    <p>¿Eres periodista, blogger o creador de contenido? Nos encanta hablar de lo que hacemos.</p>
    <p style="margin-top:16px;">Para entrevistas, menciones, colaboraciones o acceso al media kit, escríbenos directamente:</p>
    <p style="margin-top:16px;">
      <a href="mailto:prensa@vibez.es" style="color:var(--magenta);font-size:20px;font-weight:700;">prensa@vibez.es</a>
    </p>
  </div>

  <div style="border:1px solid var(--line);padding:32px;margin-bottom:48px;">
    <div class="mono" style="font-size:10px;color:var(--magenta);margin-bottom:16px;">DATOS CLAVE</div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:20px;">
      <?php $__currentLoopData = [['2024', 'Fundación'], ['BCN', 'Sede'], ['16-35', 'Público objetivo'], ['B2C + B2B', 'Modelo']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$v, $l]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div>
        <div class="display" style="font-size:36px;color:var(--ink);line-height:1;"><?php echo e($v); ?></div>
        <div class="mono" style="font-size:9px;color:var(--ink-dim);margin-top:4px;text-transform:uppercase;letter-spacing:0.1em;"><?php echo e($l); ?></div>
      </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  </div>

  <div style="margin-top:20px;">
    <a href="<?php echo e(route('home')); ?>" style="display:inline-flex;align-items:center;gap:8px;color:var(--magenta);font-family:'Archivo Narrow',sans-serif;font-size:13px;text-transform:uppercase;letter-spacing:0.1em;text-decoration:none;border-bottom:1px solid var(--magenta);padding-bottom:2px;">
      ← Volver al inicio
    </a>
  </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\proyectos\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/static/prensa.blade.php ENDPATH**/ ?>
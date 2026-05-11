<?php $__env->startSection('titulo', 'Términos y condiciones — VIBEZ'); ?>
<?php $__env->startSection('contenido'); ?>

<div style="max-width:780px;margin:80px auto;padding:0 32px;">
  <div class="mono" style="font-size:11px;color:var(--magenta);margin-bottom:16px;">Legal</div>
  <h1 class="display" style="font-size:clamp(48px,7vw,100px);margin:0 0 48px;line-height:0.88;">
    Térmi<em style="color:var(--magenta);font-style:italic;">nos</em>.
  </h1>

  <div style="font-family:'Archivo Narrow',sans-serif;font-size:16px;color:var(--ink-dim);line-height:1.75;">
    <p class="mono" style="font-size:10px;color:var(--ink-dim);margin-bottom:32px;">Última actualización: <?php echo e(date('d/m/Y')); ?></p>

    <?php $__currentLoopData = [
      ['1. Objeto', 'VIBEZ es una plataforma de descubrimiento y compra de entradas para eventos culturales y de ocio. El acceso y uso de VIBEZ implica la aceptación de estos términos.'],
      ['2. Registro', 'Para comprar entradas es necesario crear una cuenta con datos verídicos. El usuario es responsable de mantener la confidencialidad de sus credenciales.'],
      ['3. Compra de entradas', 'Las entradas adquiridas a través de VIBEZ son nominativas y no transferibles salvo que el organizador lo permita explícitamente. Cada entrada incluye un código QR único para acceso al evento.'],
      ['4. Precios', 'Los precios mostrados incluyen todos los cargos aplicables. VIBEZ puede cobrar una tarifa de servicio detallada antes de confirmar la compra.'],
      ['5. Cancelaciones', 'En caso de cancelación de un evento por parte del organizador, VIBEZ gestionará el reembolso íntegro al método de pago original en un plazo de 5-10 días hábiles.'],
      ['6. Responsabilidad del organizador', 'El organizador es responsable de la organización del evento, el cumplimiento de las normativas aplicables y la atención al asistente en el recinto.'],
      ['7. Propiedad intelectual', 'Todo el contenido de VIBEZ (diseño, textos, logotipos) es propiedad de VIBEZ S.L. y está protegido por derechos de autor.'],
      ['8. Legislación aplicable', 'Estos términos se rigen por la legislación española. Para cualquier controversia, las partes se someten a los juzgados de Barcelona.'],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$titulo, $texto]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div style="margin-bottom:24px;padding-bottom:24px;border-bottom:1px solid var(--line);">
      <div class="mono" style="font-size:10px;color:var(--magenta);margin-bottom:8px;text-transform:uppercase;letter-spacing:0.1em;"><?php echo e($titulo); ?></div>
      <p style="margin:0;"><?php echo e($texto); ?></p>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>

  <a href="<?php echo e(route('home')); ?>" style="display:inline-flex;align-items:center;gap:8px;color:var(--magenta);font-family:'Archivo Narrow',sans-serif;font-size:13px;text-transform:uppercase;letter-spacing:0.1em;text-decoration:none;border-bottom:1px solid var(--magenta);padding-bottom:2px;margin-top:20px;">
    ← Volver al inicio
  </a>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\proyectos\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/static/terminos.blade.php ENDPATH**/ ?>
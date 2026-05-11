<?php $__env->startSection('titulo', 'Política de privacidad — VIBEZ'); ?>
<?php $__env->startSection('contenido'); ?>

<div style="max-width:780px;margin:80px auto;padding:0 32px;">
  <div class="mono" style="font-size:11px;color:var(--magenta);margin-bottom:16px;">Legal</div>
  <h1 class="display" style="font-size:clamp(48px,7vw,100px);margin:0 0 48px;line-height:0.88;">
    Privaci<em style="color:var(--magenta);font-style:italic;">dad</em>.
  </h1>

  <div style="font-family:'Archivo Narrow',sans-serif;font-size:16px;color:var(--ink-dim);line-height:1.75;">
    <p class="mono" style="font-size:10px;color:var(--ink-dim);margin-bottom:32px;">Última actualización: <?php echo e(date('d/m/Y')); ?></p>

    <?php $__currentLoopData = [
      ['Responsable del tratamiento', 'VIBEZ S.L., con domicilio en Barcelona (España). Contacto: privacidad@vibez.es'],
      ['Datos que recogemos', 'Nombre, dirección de correo electrónico, contraseña cifrada y, opcionalmente, foto de perfil. Para compras: datos de pago procesados por pasarelas externas certificadas (nunca almacenamos números de tarjeta).'],
      ['Finalidad', 'Gestionar tu cuenta, procesar compras de entradas, enviarte confirmaciones y, con tu consentimiento, comunicaciones sobre eventos de tu interés.'],
      ['Base legal', 'Ejecución del contrato (art. 6.1.b RGPD) para la cuenta y las compras. Interés legítimo para la seguridad y prevención del fraude.'],
      ['Conservación', 'Mientras tu cuenta esté activa o sea necesario para cumplir obligaciones legales (hasta 5 años tras la última compra para documentos fiscales).'],
      ['Tus derechos', 'Acceso, rectificación, supresión, oposición, portabilidad y limitación del tratamiento. Ejércelos en privacidad@vibez.es adjuntando copia de tu DNI.'],
      ['Cookies', 'Usamos cookies propias de sesión y analítica. Consulta nuestra Política de Cookies para más detalles.'],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$titulo, $texto]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div style="margin-bottom:28px;padding-bottom:28px;border-bottom:1px solid var(--line);">
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\proyectos\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/static/privacidad.blade.php ENDPATH**/ ?>
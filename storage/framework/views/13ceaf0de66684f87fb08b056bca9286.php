<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tus entradas — VIBEZ</title>
<style>
  /* Reset básico para clientes de correo */
  body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
  table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
  img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
  body { margin: 0; padding: 0; background-color: #0f0d1e; font-family: Arial, sans-serif; }
</style>
</head>
<body style="margin:0;padding:0;background-color:#0f0d1e;">

<?php
  $primerEvento = $pedido->entradas->first()?->evento;
  $usuario      = $pedido->usuario;
?>

<!-- Wrapper -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#0f0d1e;">
  <tr>
    <td align="center" style="padding:32px 16px;">

      <!-- Contenedor principal (max 600px) -->
      <table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;">

        <!-- ── CABECERA VIBEZ ── -->
        <tr>
          <td style="background:linear-gradient(135deg,#1e1b4b,#312e81);border-radius:16px 16px 0 0;padding:32px 40px;text-align:center;">
            <div style="font-size:28px;font-weight:900;color:#ffffff;letter-spacing:0.08em;margin-bottom:6px;">VIBEZ</div>
            <div style="font-size:13px;color:rgba(196,181,253,0.85);letter-spacing:0.05em;">Plataforma de eventos para jóvenes</div>
          </td>
        </tr>

        <!-- ── HERO: ICONO + TÍTULO ── -->
        <tr>
          <td style="background:#13102a;padding:36px 40px 28px;text-align:center;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;">

            <!-- Icono de ticket -->
            <div style="width:64px;height:64px;background:rgba(124,58,237,0.15);border-radius:50%;margin:0 auto 20px;display:table-cell;vertical-align:middle;text-align:center;">
              <img src="https://cdn-icons-png.flaticon.com/512/3082/3082383.png" width="32" height="32" alt="ticket" style="margin-top:16px;">
            </div>

            <h1 style="margin:0 0 8px;font-size:24px;font-weight:900;color:#ffffff;">
              ¡Compra confirmada!
            </h1>
            <p style="margin:0;font-size:15px;color:rgba(148,163,184,0.9);">
              Hola <strong style="color:#a78bfa;"><?php echo e($usuario->nombre); ?></strong>, aquí tienes tus entradas.
            </p>
          </td>
        </tr>

        <!-- ── RESUMEN DEL PEDIDO ── -->
        <tr>
          <td style="background:#13102a;padding:0 40px 28px;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                   style="background:#1a1535;border:1px solid rgba(139,92,246,0.2);border-radius:12px;overflow:hidden;">
              <tr>
                <td style="padding:20px 24px;">

                  <?php if($primerEvento): ?>
                  <!-- Nombre del evento -->
                  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:16px;">
                    <tr>
                      <td width="44" valign="top">
                        <div style="width:40px;height:40px;background:linear-gradient(135deg,#7c3aed,#a855f7);border-radius:10px;"></div>
                      </td>
                      <td style="padding-left:12px;">
                        <div style="font-size:16px;font-weight:700;color:#f1f5f9;"><?php echo e($primerEvento->titulo); ?></div>
                        <div style="font-size:13px;color:#a78bfa;margin-top:2px;">
                          <?php echo e(\Carbon\Carbon::parse($primerEvento->fecha_inicio)->locale('es')->isoFormat('D [de] MMMM [de] YYYY, HH:mm')); ?>

                        </div>
                        <?php if($primerEvento->ubicacion_nombre): ?>
                        <div style="font-size:12px;color:#64748b;margin-top:2px;">📍 <?php echo e($primerEvento->ubicacion_nombre); ?></div>
                        <?php endif; ?>
                      </td>
                    </tr>
                  </table>
                  <?php endif; ?>

                  <!-- Separador -->
                  <div style="border-top:1px solid rgba(139,92,246,0.15);margin-bottom:14px;"></div>

                  <!-- Estadísticas del pedido -->
                  <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td style="font-size:13px;color:#94a3b8;">
                        Pedido #<?php echo e($pedido->id); ?> · <?php echo e($pedido->entradas->count()); ?> entrada<?php echo e($pedido->entradas->count() !== 1 ? 's' : ''); ?>

                      </td>
                      <td align="right">
                        <?php if($pedido->total_final == 0): ?>
                          <span style="font-size:18px;font-weight:900;color:#10b981;">GRATIS</span>
                        <?php else: ?>
                          <span style="font-size:18px;font-weight:900;color:#a78bfa;"><?php echo e(number_format($pedido->total_final, 2)); ?> €</span>
                        <?php endif; ?>
                      </td>
                    </tr>
                  </table>

                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- ── ENTRADAS CON QR ── -->
        <tr>
          <td style="background:#13102a;padding:0 40px 8px;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;">
            <div style="font-size:13px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:16px;">
              Tus entradas (<?php echo e($pedido->entradas->count()); ?>)
            </div>
          </td>
        </tr>

        <?php $__currentLoopData = $pedido->entradas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $entrada): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
          <td style="background:#13102a;padding:0 40px <?php echo e($loop->last ? '36px' : '0'); ?>;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;">

            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                   style="background:#1a1535;border:1px solid rgba(139,92,246,0.2);border-radius:12px;overflow:hidden;margin-bottom:16px;">

              <!-- Cabecera de entrada -->
              <tr>
                <td style="padding:16px 24px 12px;border-bottom:2px dashed rgba(139,92,246,0.2);">
                  <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td>
                        <div style="font-size:14px;font-weight:700;color:#f1f5f9;">Entrada #<?php echo e($i + 1); ?></div>
                        <div style="font-size:11px;color:#475569;font-family:monospace;margin-top:4px;word-break:break-all;">
                          <?php echo e($entrada->codigo_qr); ?>

                        </div>
                      </td>
                      <td align="right" valign="top" style="padding-left:12px;white-space:nowrap;">
                        <?php if($entrada->precio_pagado == 0): ?>
                          <span style="font-size:15px;font-weight:700;color:#10b981;">GRATIS</span>
                        <?php else: ?>
                          <span style="font-size:15px;font-weight:700;color:#a78bfa;"><?php echo e(number_format($entrada->precio_pagado, 2)); ?> €</span>
                        <?php endif; ?>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

              <!-- QR Code -->
              <tr>
                <td align="center" style="padding:24px;">
                  <?php if(!empty($qrImages[$entrada->id])): ?>
                    <div style="display:inline-block;padding:12px;background:#ffffff;border-radius:12px;">
                      <img src="<?php echo e($message->embedData($qrImages[$entrada->id], 'qr-'.$entrada->id.'.png', 'image/png')); ?>"
                           width="200" height="200"
                           alt="QR Entrada #<?php echo e($i + 1); ?>"
                           style="display:block;">
                    </div>
                  <?php else: ?>
                    <div style="background:#1e293b;border-radius:12px;padding:24px;text-align:center;color:#64748b;font-size:13px;">
                      QR no disponible — muestra el código: <?php echo e($entrada->codigo_qr); ?>

                    </div>
                  <?php endif; ?>
                  <div style="font-size:12px;color:#64748b;margin-top:10px;">
                    Presenta este QR en la entrada del evento
                  </div>
                </td>
              </tr>

            </table>
          </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <!-- ── AVISO ── -->
        <tr>
          <td style="background:#13102a;padding:0 40px 32px;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                   style="background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.25);border-radius:10px;">
              <tr>
                <td style="padding:14px 18px;">
                  <div style="font-size:13px;color:#fbbf24;font-weight:700;margin-bottom:4px;">⚠ Importante</div>
                  <div style="font-size:12px;color:#94a3b8;line-height:1.6;">
                    Cada entrada solo puede usarse una vez. Una vez escaneada, quedará invalidada y no podrá volver a utilizarse para acceder al evento.
                  </div>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- ── BOTÓN VER ENTRADAS ── -->
        <tr>
          <td style="background:#13102a;padding:0 40px 36px;text-align:center;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;">
            <a href="<?php echo e(url('/mis-entradas')); ?>"
               style="display:inline-block;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#ffffff;text-decoration:none;font-size:15px;font-weight:700;padding:14px 36px;border-radius:10px;">
              Ver mis entradas
            </a>
          </td>
        </tr>

        <!-- ── PIE ── -->
        <tr>
          <td style="background:#0d0b1e;border:1px solid #1e1b4b;border-top:none;border-radius:0 0 16px 16px;padding:24px 40px;text-align:center;">
            <div style="font-size:18px;font-weight:900;color:#4c1d95;letter-spacing:0.06em;margin-bottom:8px;">VIBEZ</div>
            <div style="font-size:12px;color:#334155;line-height:1.7;">
              Este correo fue enviado automáticamente. Por favor, no respondas a este mensaje.<br>
              © <?php echo e(date('Y')); ?> VIBEZ — Plataforma de eventos para jóvenes.
            </div>
          </td>
        </tr>

      </table>
      <!-- /Contenedor principal -->

    </td>
  </tr>
</table>
<!-- /Wrapper -->

</body>
</html>
<?php /**PATH C:\wamp64\www\LARAVEL\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/emails/entrada-comprada.blade.php ENDPATH**/ ?>
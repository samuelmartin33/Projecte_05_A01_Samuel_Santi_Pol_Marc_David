<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nueva solicitud de empresa — VIBEZ</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'Helvetica Neue',Arial,sans-serif; background:#F5F3FF; color:#1F2937; }
.wrapper { max-width:600px; margin:40px auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(91,33,182,0.12); }
.header { background:linear-gradient(135deg,#7C3AED 0%,#5B21B6 100%); padding:36px 48px; text-align:center; }
.logo { font-size:2.2rem; font-weight:900; color:#ffffff; letter-spacing:-0.03em; }
.header-sub { font-size:0.85rem; color:rgba(255,255,255,0.75); margin-top:4px; letter-spacing:0.06em; text-transform:uppercase; }
.body { padding:36px 48px; }
.alert-badge { display:inline-block; background:#FEF3C7; border:1px solid #FCD34D; color:#92400E; border-radius:999px; padding:5px 16px; font-size:0.8rem; font-weight:700; margin-bottom:22px; }
.titulo { font-size:1.4rem; font-weight:700; color:#1F2937; margin-bottom:12px; }
.texto { font-size:0.95rem; color:#4B5563; line-height:1.7; margin-bottom:20px; }
.data-box { background:#F5F3FF; border:1px solid #DDD6FE; border-radius:10px; padding:20px 24px; margin-bottom:24px; }
.data-box table { width:100%; border-collapse:collapse; }
.data-box td { padding:7px 0; font-size:0.9rem; vertical-align:top; }
.data-box td.label { color:#6D28D9; font-weight:700; width:40%; font-size:0.82rem; text-transform:uppercase; letter-spacing:0.05em; }
.data-box td.valor { color:#1F2937; }
.divider { border:none; border-top:1px solid #E5E7EB; margin:22px 0; }
.btn-wrapper { text-align:center; margin:28px 0 8px; }
.btn { display:inline-block; background:linear-gradient(135deg,#7C3AED,#5B21B6); color:#ffffff; text-decoration:none; padding:14px 40px; border-radius:10px; font-size:1rem; font-weight:700; letter-spacing:0.03em; }
.footer { background:#F9FAFB; padding:22px 48px; text-align:center; }
.footer p { font-size:0.76rem; color:#9CA3AF; line-height:1.6; }
.footer strong { color:#7C3AED; }
</style>
</head>
<body>
<div class="wrapper">

    <div class="header">
        <div class="logo">VIBEZ</div>
        <div class="header-sub">Panel de Administración</div>
    </div>

    <div class="body">
        <span class="alert-badge">⏳ Solicitud pendiente de revisión</span>

        <div class="titulo">Nueva empresa registrada</div>
        <p class="texto">
            Una nueva empresa acaba de completar el registro en la plataforma y está
            esperando tu aprobación para poder operar. Revisa los datos a continuación
            y accede al panel de administración para aprobar o rechazar la solicitud.
        </p>

        <div class="data-box">
            <table>
                <tr>
                    <td class="label">Empresa</td>
                    <td class="valor"><strong><?php echo e($empresa->nombre_empresa); ?></strong></td>
                </tr>
                <?php if($empresa->razon_social): ?>
                <tr>
                    <td class="label">Razón social</td>
                    <td class="valor"><?php echo e($empresa->razon_social); ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td class="label">NIF / CIF</td>
                    <td class="valor"><?php echo e($empresa->nif_cif ?? '—'); ?></td>
                </tr>
                <tr>
                    <td class="label">Tipo</td>
                    <td class="valor"><?php echo e(ucfirst(str_replace('_', ' ', $empresa->tipo_promotor ?? '—'))); ?></td>
                </tr>
                <tr>
                    <td class="label">Representante</td>
                    <td class="valor"><?php echo e($usuario->nombre); ?> <?php echo e($usuario->apellido1); ?></td>
                </tr>
                <tr>
                    <td class="label">Email</td>
                    <td class="valor"><?php echo e($usuario->email); ?></td>
                </tr>
                <?php if($usuario->telefono): ?>
                <tr>
                    <td class="label">Teléfono</td>
                    <td class="valor"><?php echo e($usuario->telefono); ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td class="label">Registrada</td>
                    <td class="valor"><?php echo e(\Carbon\Carbon::parse($usuario->fecha_creacion)->format('d/m/Y \a \l\a\s H:i')); ?></td>
                </tr>
            </table>
        </div>

        <hr class="divider">

        <div class="btn-wrapper">
            <a href="<?php echo e(url('/admin/empresas')); ?>" class="btn">Revisar solicitud en el panel →</a>
        </div>
    </div>

    <div class="footer">
        <p>
            Este correo se ha generado automáticamente por <strong>VIBEZ Platform</strong>.<br>
            Solo los administradores de Vibez reciben este tipo de notificaciones.
        </p>
    </div>

</div>
</body>
</html>
<?php /**PATH C:\wamp64\www\LARAVEL\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/emails/nueva-empresa-admin.blade.php ENDPATH**/ ?>
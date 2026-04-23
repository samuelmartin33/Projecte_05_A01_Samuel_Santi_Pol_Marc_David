<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Bienvenido a VIBEZ!</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background: #F5F3FF; color: #1F2937; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(91,33,182,0.12); }
        .header { background: linear-gradient(135deg, #7C3AED 0%, #5B21B6 100%); padding: 40px 48px; text-align: center; }
        .logo { font-size: 2.4rem; font-weight: 900; color: #ffffff; letter-spacing: -0.03em; }
        .header-sub { font-size: 0.9rem; color: rgba(255,255,255,0.75); margin-top: 4px; letter-spacing: 0.06em; text-transform: uppercase; }
        .body { padding: 40px 48px; }
        .greeting { font-size: 1.5rem; font-weight: 700; color: #1F2937; margin-bottom: 16px; }
        .greeting span { color: #7C3AED; }
        .text { font-size: 1rem; color: #4B5563; line-height: 1.7; margin-bottom: 16px; }
        .badge { display: inline-block; background: #F0FDF4; border: 1px solid #86EFAC; color: #166534; border-radius: 999px; padding: 6px 18px; font-size: 0.82rem; font-weight: 700; margin-bottom: 28px; }
        .btn-wrapper { text-align: center; margin: 32px 0; }
        .btn { display: inline-block; background: linear-gradient(135deg, #7C3AED, #5B21B6); color: #ffffff; text-decoration: none; padding: 14px 40px; border-radius: 10px; font-size: 1rem; font-weight: 700; letter-spacing: 0.03em; }
        .divider { border: none; border-top: 1px solid #E5E7EB; margin: 28px 0; }
        .info-box { background: #F5F3FF; border-left: 3px solid #7C3AED; border-radius: 8px; padding: 16px 20px; margin-bottom: 24px; }
        .info-box p { font-size: 0.88rem; color: #5B21B6; line-height: 1.6; }
        .footer { background: #F9FAFB; padding: 24px 48px; text-align: center; }
        .footer p { font-size: 0.78rem; color: #9CA3AF; line-height: 1.6; }
        .footer strong { color: #7C3AED; }
    </style>
</head>
<body>
<div class="wrapper">

    <div class="header">
        <div class="logo">VIBEZ</div>
        <div class="header-sub">Plataforma de eventos y networking</div>
    </div>

    <div class="body">
        <p class="greeting">¡Hola, <span><?php echo e($usuario->nombre); ?></span>! 🎉</p>

        <span class="badge">✓ Cuenta aprobada</span>

        <p class="text">
            Nos complace informarte de que tu solicitud de registro en <strong>VIBEZ</strong>
            ha sido revisada y <strong>aprobada por nuestro equipo</strong>.
            Ya puedes acceder a la plataforma y disfrutar de todos los eventos y funcionalidades disponibles.
        </p>

        <div class="info-box">
            <p>
                <strong>Tu cuenta registrada:</strong> <?php echo e($usuario->email); ?><br>
                Usa esta dirección para iniciar sesión. Si olvidaste tu contraseña,
                podrás recuperarla desde la pantalla de login.
            </p>
        </div>

        <div class="btn-wrapper">
            <a href="<?php echo e(config('app.url')); ?>/login" class="btn">Iniciar sesión →</a>
        </div>

        <hr class="divider">

        <p class="text" style="font-size:0.9rem;">
            Si tienes alguna duda o problema para acceder, responde a este correo
            y te ayudaremos lo antes posible.
        </p>

        <p class="text" style="font-size:0.9rem; color:#6B7280;">
            El equipo de VIBEZ está encantado de tenerte con nosotros. ¡Empieza a vibrar!
        </p>
    </div>

    <div class="footer">
        <p>
            Has recibido este correo porque te registraste en <strong>VIBEZ</strong>.<br>
            © <?php echo e(date('Y')); ?> VIBEZ. Todos los derechos reservados.
        </p>
    </div>

</div>
</body>
</html>
<?php /**PATH C:\wamp64\www\DAW2\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/emails/bienvenida.blade.php ENDPATH**/ ?>
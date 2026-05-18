<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablece tu contraseña — VIBEZ</title>
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
        .btn-wrapper { text-align: center; margin: 32px 0; }
        .btn { display: inline-block; background: linear-gradient(135deg, #7C3AED, #5B21B6); color: #ffffff; text-decoration: none; padding: 14px 40px; border-radius: 10px; font-size: 1rem; font-weight: 700; letter-spacing: 0.03em; }
        .divider { border: none; border-top: 1px solid #E5E7EB; margin: 28px 0; }
        .info-box { background: #F5F3FF; border-left: 3px solid #7C3AED; border-radius: 8px; padding: 16px 20px; margin-bottom: 24px; }
        .info-box p { font-size: 0.88rem; color: #5B21B6; line-height: 1.6; }
        .url-box { background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px 16px; margin: 20px 0; word-break: break-all; }
        .url-box span { font-size: 0.78rem; color: #6B7280; }
        .footer { background: #F9FAFB; padding: 24px 48px; text-align: center; }
        .footer p { font-size: 0.78rem; color: #9CA3AF; line-height: 1.6; }
        .footer strong { color: #7C3AED; }
        .warning { font-size: 0.85rem; color: #9CA3AF; margin-top: 8px; }
    </style>
</head>
<body>
<div class="wrapper">

    <div class="header">
        <div class="logo">VIBEZ</div>
        <div class="header-sub">Restablecimiento de contraseña</div>
    </div>

    <div class="body">
        <p class="greeting">Hola, <span>{{ $usuario->nombre }}</span></p>

        <p class="text">
            Hemos recibido una solicitud para restablecer la contraseña de tu cuenta <strong>VIBEZ</strong>.
            Haz clic en el botón de abajo para crear una nueva contraseña.
        </p>

        <div class="info-box">
            <p>
                <strong>Este enlace es válido durante 60 minutos.</strong><br>
                Si no solicitaste restablecer tu contraseña, puedes ignorar este mensaje. Tu cuenta sigue segura.
            </p>
        </div>

        <div class="btn-wrapper">
            <a href="{{ $resetUrl }}" class="btn">Restablecer contraseña →</a>
        </div>

        <hr class="divider">

        <p class="text" style="font-size:0.88rem; color:#6B7280;">
            Si el botón no funciona, copia y pega este enlace en tu navegador:
        </p>
        <div class="url-box">
            <span>{{ $resetUrl }}</span>
        </div>

        <p class="warning">
            Por seguridad, este enlace expirará automáticamente a los 60 minutos.
            Si lo necesitas de nuevo, solicita un nuevo enlace desde la pantalla de inicio de sesión.
        </p>
    </div>

    <div class="footer">
        <p>
            Has recibido este correo porque se solicitó un restablecimiento de contraseña para tu cuenta en <strong>VIBEZ</strong>.<br>
            Si no fuiste tú, ignora este mensaje. Tu contraseña no ha cambiado.<br><br>
            © {{ date('Y') }} VIBEZ. Todos los derechos reservados.
        </p>
    </div>

</div>
</body>
</html>

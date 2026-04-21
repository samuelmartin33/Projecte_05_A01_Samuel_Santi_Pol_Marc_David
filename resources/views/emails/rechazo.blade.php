<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información sobre tu registro en VIBEZ</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background: #F9FAFB; color: #1F2937; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #7C3AED 0%, #5B21B6 100%); padding: 40px 48px; text-align: center; }
        .logo { font-size: 2.4rem; font-weight: 900; color: #ffffff; letter-spacing: -0.03em; }
        .header-sub { font-size: 0.9rem; color: rgba(255,255,255,0.75); margin-top: 4px; letter-spacing: 0.06em; text-transform: uppercase; }
        .body { padding: 40px 48px; }
        .greeting { font-size: 1.4rem; font-weight: 700; color: #1F2937; margin-bottom: 16px; }
        .text { font-size: 1rem; color: #4B5563; line-height: 1.7; margin-bottom: 16px; }
        .badge { display: inline-block; background: #FEF2F2; border: 1px solid #FCA5A5; color: #991B1B; border-radius: 999px; padding: 6px 18px; font-size: 0.82rem; font-weight: 700; margin-bottom: 28px; }
        .info-box { background: #FFF7ED; border-left: 3px solid #F59E0B; border-radius: 8px; padding: 16px 20px; margin-bottom: 24px; }
        .info-box p { font-size: 0.88rem; color: #92400E; line-height: 1.6; }
        .divider { border: none; border-top: 1px solid #E5E7EB; margin: 28px 0; }
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
        <p class="greeting">Hola, {{ $usuario->nombre }}</p>

        <span class="badge">✗ Solicitud no aprobada</span>

        <p class="text">
            Gracias por tu interés en unirte a <strong>VIBEZ</strong>.
            Tras revisar tu solicitud de registro, nuestro equipo ha decidido
            no aprobarla en este momento.
        </p>

        <div class="info-box">
            <p>
                Si crees que se trata de un error o deseas más información sobre
                los motivos, puedes ponerte en contacto con nuestro equipo respondiendo
                directamente a este correo. Estaremos encantados de atenderte.
            </p>
        </div>

        <p class="text">
            Lamentamos los inconvenientes que esto pueda ocasionarte.
        </p>

        <hr class="divider">

        <p class="text" style="font-size:0.9rem; color:#6B7280;">
            El equipo de VIBEZ
        </p>
    </div>

    <div class="footer">
        <p>
            Has recibido este correo en relación a tu solicitud de registro en <strong>VIBEZ</strong>.<br>
            © {{ date('Y') }} VIBEZ. Todos los derechos reservados.
        </p>
    </div>

</div>
</body>
</html>

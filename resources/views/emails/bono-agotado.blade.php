<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bono Agotado</title>
</head>
<body style="margin: 0; padding: 0; background-color: #0f091f; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #f1f5f9; -webkit-font-smoothing: antialiased;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #0f091f; margin: 0; padding: 40px 0; width: 100%;">
        <tr>
            <td align="center">
                <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="max-width: 600px; margin: 0 auto; padding: 0;">
                    
                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding: 25px 0; background-color: #0f091f;">
                            <h1 style="color: #c4b5fd; font-size: 32px; font-weight: 900; margin: 0; letter-spacing: -1px;">VIBEZ</h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td class="body" width="100%" cellpadding="0" cellspacing="0" style="background-color: #1a1033; border: 1px solid #2e1f4d; border-radius: 12px; padding: 40px;">
                            <h1 style="color: #ffffff; font-size: 24px; font-weight: bold; margin-top: 0; margin-bottom: 20px;">
                                🍹 ¡Tu bono se ha agotado!
                            </h1>

                            <p style="color: #e2e8f0; font-size: 16px; line-height: 1.6; margin-top: 0; margin-bottom: 20px;">
                                Hola <strong>{{ $bono->usuario->nombre }}</strong>,
                            </p>

                            <p style="color: #e2e8f0; font-size: 16px; line-height: 1.6; margin-top: 0; margin-bottom: 30px;">
                                Te informamos que acabas de consumir la última bebida de tu <strong>bono de {{ $bono->tipo->cantidad_bebidas }} bebidas</strong> para el evento <strong>{{ $bono->evento->titulo }}</strong>.
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: rgba(255,255,255,0.05); border-radius: 8px; padding: 20px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding-bottom: 10px;">
                                        <p style="margin: 0; font-size: 13px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Evento</p>
                                        <p style="margin: 5px 0 0 0; font-size: 16px; color: #ffffff; font-weight: bold;">{{ $bono->evento->titulo }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="margin: 0; font-size: 13px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Estado del Bono</p>
                                        <p style="margin: 5px 0 0 0; font-size: 16px; color: #f87171; font-weight: bold;">0 bebidas restantes</p>
                                    </td>
                                </tr>
                            </table>

                            <p style="color: #e2e8f0; font-size: 16px; line-height: 1.6; margin-top: 0; margin-bottom: 30px;">
                                Puedes seguir disfrutando de la fiesta comprando bebidas sueltas en la barra.
                            </p>

                            <p style="color: #94a3b8; font-size: 14px; line-height: 1.6; margin-top: 0; margin-bottom: 0;">
                                ¡Sigue disfrutando la noche con nosotros!<br>
                                El equipo de <strong>VIBEZ</strong>.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="padding: 30px 0 20px 0;">
                            <p style="color: #64748b; font-size: 12px; margin: 0;">
                                © {{ date('Y') }} VIBEZ. Todos los derechos reservados.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>

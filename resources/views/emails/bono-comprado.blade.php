<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tu bono de bebidas — VIBEZ</title>
<style>
  body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
  table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
  img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
  body { margin: 0; padding: 0; background-color: #0f0d1e; font-family: Arial, sans-serif; }
</style>
</head>
<body style="margin:0;padding:0;background-color:#0f0d1e;">

@php
  $tipo   = $bono->tipo;
  $evento = $bono->evento;
@endphp

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#0f0d1e;">
  <tr>
    <td align="center" style="padding:32px 16px;">
      <table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;">

        <!-- Cabecera VIBEZ -->
        <tr>
          <td style="background:linear-gradient(135deg,#1e1b4b,#312e81);border-radius:16px 16px 0 0;padding:32px 40px;text-align:center;">
            <div style="font-size:28px;font-weight:900;color:#ffffff;letter-spacing:0.08em;margin-bottom:6px;">VIBEZ</div>
            <div style="font-size:13px;color:rgba(196,181,253,0.85);letter-spacing:0.05em;">Plataforma de eventos para jóvenes</div>
          </td>
        </tr>

        <!-- Hero -->
        <tr>
          <td style="background:#13102a;padding:36px 40px 28px;text-align:center;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;">
            <div style="font-size:3rem;margin-bottom:16px;">🍹</div>
            <h1 style="margin:0 0 8px;font-size:24px;font-weight:900;color:#ffffff;">
              ¡Bono activado!
            </h1>
            <p style="margin:0;font-size:15px;color:rgba(148,163,184,0.9);">
              Hola <strong style="color:#a78bfa;">{{ $usuario->nombre }}</strong>,
              aquí tienes tu bono de bebidas.
            </p>
          </td>
        </tr>

        <!-- Detalles del bono -->
        <tr>
          <td style="background:#13102a;padding:0 40px 28px;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                   style="background:#1a1535;border:1px solid rgba(139,92,246,0.2);border-radius:12px;overflow:hidden;">
              <tr>
                <td style="padding:20px 24px;">
                  <div style="font-size:11px;font-weight:700;color:rgba(196,181,253,0.6);letter-spacing:0.1em;text-transform:uppercase;margin-bottom:14px;">
                    Detalles del bono
                  </div>

                  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:12px;">
                    <tr>
                      <td style="font-size:13px;color:rgba(148,163,184,0.7);">Tipo de bono</td>
                      <td style="font-size:14px;font-weight:700;color:#ffffff;text-align:right;">
                        {{ $tipo?->cantidad_bebidas ?? '?' }} bebidas
                      </td>
                    </tr>
                  </table>
                  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:12px;">
                    <tr>
                      <td style="font-size:13px;color:rgba(148,163,184,0.7);">Saldo inicial</td>
                      <td style="font-size:14px;color:#4ade80;font-weight:700;text-align:right;">
                        {{ $bono->bebidas_restantes }} bebidas disponibles
                      </td>
                    </tr>
                  </table>
                  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:12px;">
                    <tr>
                      <td style="font-size:13px;color:rgba(148,163,184,0.7);">Evento de compra</td>
                      <td style="font-size:14px;color:#f5f1ea;text-align:right;">
                        {{ $evento?->titulo ?? '—' }}
                      </td>
                    </tr>
                  </table>

                  <table width="100%" cellpadding="0" cellspacing="0" border="0"
                         style="border-top:1px solid rgba(139,92,246,0.2);margin:16px 0;"><tr><td></td></tr></table>

                  <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td style="font-size:13px;font-weight:700;color:rgba(196,181,253,0.7);">Importe pagado</td>
                      <td style="font-size:22px;font-weight:900;color:#a78bfa;text-align:right;">
                        {{ number_format($tipo?->precio ?? 0, 2) }} €
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- QR del bono -->
        <tr>
          <td style="background:#13102a;padding:0 40px 32px;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;text-align:center;">
            <div style="font-size:11px;font-weight:700;color:rgba(196,181,253,0.6);letter-spacing:0.1em;text-transform:uppercase;margin-bottom:16px;">
              Muestra este QR al camarero
            </div>

            @if($qrImageData)
              <div style="display:inline-block;background:#ffffff;padding:12px;border-radius:8px;">
                <img src="data:image/png;base64,{{ base64_encode($qrImageData) }}"
                     width="200" height="200" alt="QR del bono"
                     style="display:block;">
              </div>
            @else
              <div style="background:rgba(124,58,237,0.15);border:1px solid rgba(124,58,237,0.3);padding:16px;border-radius:8px;display:inline-block;">
                <p style="color:#c084fc;font-size:12px;margin:0;">Código: {{ $bono->codigo_qr }}</p>
              </div>
            @endif

            <p style="font-size:11px;color:rgba(148,163,184,0.5);margin:12px 0 0;font-family:monospace;word-break:break-all;">
              {{ $bono->codigo_qr }}
            </p>
            <p style="font-size:11px;color:rgba(148,163,184,0.4);margin:6px 0 0;font-family:Arial,sans-serif;">
              Si no se muestra el QR, está adjunto en este correo como imagen.
            </p>
          </td>
        </tr>

        <!-- Nota validez -->
        <tr>
          <td style="background:#13102a;padding:16px 40px 28px;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;text-align:center;">
            <div style="background:rgba(74,222,128,0.08);border:1px solid rgba(74,222,128,0.2);border-radius:8px;padding:12px 16px;">
              <p style="margin:0;font-size:13px;color:rgba(74,222,128,0.85);line-height:1.5;">
                ✓ Válido en cualquier evento Fiesta de la misma empresa.<br>
                Cada bebida consume 1 unidad del saldo del bono.
              </p>
            </div>
          </td>
        </tr>

        <!-- Footer -->
        <tr>
          <td style="background:#0f0d1e;border:1px solid #1e1b4b;border-top:none;border-radius:0 0 16px 16px;padding:24px 40px;text-align:center;">
            <p style="margin:0 0 8px;font-size:13px;color:rgba(148,163,184,0.5);">
              © {{ date('Y') }} VIBEZ · Plataforma de eventos para jóvenes
            </p>
            <p style="margin:0;font-size:11px;color:rgba(148,163,184,0.3);">
              Si no realizaste esta compra, contacta con soporte.
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>

</body>
</html>

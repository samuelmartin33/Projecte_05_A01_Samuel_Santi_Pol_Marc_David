<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>¡Bienvenido a VIBEZ Premium!</title>
<style>
  body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
  table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
  img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
  body { margin: 0; padding: 0; background-color: #0f0d1e; font-family: Arial, sans-serif; }
</style>
</head>
<body style="margin:0;padding:0;background-color:#0f0d1e;">

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
            <!-- Estrella Premium -->
            <div style="font-size:52px;line-height:1;margin-bottom:20px;">⭐</div>

            <h1 style="margin:0 0 8px;font-size:24px;font-weight:900;color:#ffffff;">
              ¡Ya eres Premium!
            </h1>
            <p style="margin:0;font-size:15px;color:rgba(148,163,184,0.9);">
              Hola <strong style="color:#a78bfa;">{{ $usuario->nombre }}</strong>, tu suscripción está activa. Adjuntamos tu factura.
            </p>
          </td>
        </tr>

        <!-- ── RESUMEN DEL PAGO ── -->
        <tr>
          <td style="background:#13102a;padding:0 40px 28px;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                   style="background:#1a1535;border:1px solid rgba(139,92,246,0.2);border-radius:12px;overflow:hidden;">
              <tr>
                <td style="padding:20px 24px;">

                  <!-- Producto -->
                  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:16px;">
                    <tr>
                      <td width="44" valign="top">
                        <div style="width:40px;height:40px;background:linear-gradient(135deg,#7c3aed,#a855f7);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;line-height:40px;text-align:center;">⭐</div>
                      </td>
                      <td style="padding-left:12px;">
                        <div style="font-size:16px;font-weight:700;color:#f1f5f9;">VIBEZ Premium</div>
                        <div style="font-size:13px;color:#a78bfa;margin-top:2px;">Acceso a cupones exclusivos de promotoras</div>
                      </td>
                    </tr>
                  </table>

                  <!-- Separador -->
                  <div style="border-top:1px solid rgba(139,92,246,0.15);margin-bottom:14px;"></div>

                  <!-- Detalles del pago -->
                  <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td style="font-size:13px;color:#94a3b8;padding-bottom:6px;">
                        Referencia: <span style="font-family:monospace;color:#64748b;">{{ strtoupper(substr($sessionId, -12)) }}</span>
                      </td>
                    </tr>
                    <tr>
                      <td style="font-size:13px;color:#94a3b8;padding-bottom:6px;">
                        Fecha: {{ $fechaPago->locale('es')->isoFormat('D [de] MMMM [de] YYYY, HH:mm') }}
                      </td>
                    </tr>
                    <tr>
                      <td align="right">
                        <span style="font-size:22px;font-weight:900;color:#a78bfa;">{{ number_format($importe, 2) }} €</span>
                        <div style="font-size:11px;color:#64748b;margin-top:2px;">pago único · sin renovación automática</div>
                      </td>
                    </tr>
                  </table>

                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- ── BENEFICIOS PREMIUM ── -->
        <tr>
          <td style="background:#13102a;padding:0 40px 28px;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;">
            <div style="font-size:13px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:16px;">
              Lo que incluye tu Premium
            </div>
            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                   style="background:#1a1535;border:1px solid rgba(139,92,246,0.2);border-radius:12px;">
              <tr>
                <td style="padding:20px 24px;">
                  <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td style="padding:8px 0;font-size:14px;color:#e2e8f0;">
                        <span style="color:#a78bfa;font-weight:700;margin-right:10px;">✓</span>
                        Cupones de descuento exclusivos de promotoras
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:8px 0;font-size:14px;color:#e2e8f0;border-top:1px solid rgba(139,92,246,0.1);">
                        <span style="color:#a78bfa;font-weight:700;margin-right:10px;">✓</span>
                        Acceso anticipado a preventas de eventos
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:8px 0;font-size:14px;color:#e2e8f0;border-top:1px solid rgba(139,92,246,0.1);">
                        <span style="color:#a78bfa;font-weight:700;margin-right:10px;">✓</span>
                        Insignia Premium visible en tu perfil
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- ── AVISO FACTURA ── -->
        <tr>
          <td style="background:#13102a;padding:0 40px 32px;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                   style="background:rgba(124,58,237,0.08);border:1px solid rgba(124,58,237,0.25);border-radius:10px;">
              <tr>
                <td style="padding:14px 18px;">
                  <div style="font-size:13px;color:#a78bfa;font-weight:700;margin-bottom:4px;">📄 Factura adjunta</div>
                  <div style="font-size:12px;color:#94a3b8;line-height:1.6;">
                    Encontrarás el justificante de compra en PDF adjunto a este correo. Guárdalo para tu contabilidad o declaración de gastos.
                  </div>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- ── BOTÓN VER PERFIL ── -->
        <tr>
          <td style="background:#13102a;padding:0 40px 36px;text-align:center;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;">
            <a href="{{ url('/premium') }}"
               style="display:inline-block;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#ffffff;text-decoration:none;font-size:15px;font-weight:700;padding:14px 36px;border-radius:10px;">
              Ver mi cuenta Premium
            </a>
          </td>
        </tr>

        <!-- ── PIE ── -->
        <tr>
          <td style="background:#0d0b1e;border:1px solid #1e1b4b;border-top:none;border-radius:0 0 16px 16px;padding:24px 40px;text-align:center;">
            <div style="font-size:18px;font-weight:900;color:#4c1d95;letter-spacing:0.06em;margin-bottom:8px;">VIBEZ</div>
            <div style="font-size:12px;color:#334155;line-height:1.7;">
              Este correo fue enviado automáticamente. Por favor, no respondas a este mensaje.<br>
              © {{ date('Y') }} VIBEZ — Plataforma de eventos para jóvenes.
            </div>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>

</body>
</html>

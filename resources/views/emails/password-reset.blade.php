<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Restablece tu contraseña — VIBEZ</title>
<style>
  /* Reset básico para clientes de correo */
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
            <!-- Icono candado -->
            <div style="font-size:48px;line-height:1;margin-bottom:20px;">🔐</div>

            <h1 style="margin:0 0 8px;font-size:24px;font-weight:900;color:#ffffff;">
              Restablece tu contraseña
            </h1>
            <p style="margin:0;font-size:15px;color:rgba(148,163,184,0.9);">
              Hola <strong style="color:#a78bfa;">{{ $usuario->nombre }}</strong>, recibimos una solicitud para cambiar tu contraseña.
            </p>
          </td>
        </tr>

        <!-- ── AVISO EXPIRACIÓN ── -->
        <tr>
          <td style="background:#13102a;padding:0 40px 24px;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                   style="background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.25);border-radius:10px;">
              <tr>
                <td style="padding:14px 18px;">
                  <div style="font-size:13px;color:#fbbf24;font-weight:700;margin-bottom:4px;">⏱ Enlace válido 60 minutos</div>
                  <div style="font-size:12px;color:#94a3b8;line-height:1.6;">
                    Si no solicitaste este cambio, puedes ignorar este correo. Tu cuenta sigue siendo segura.
                  </div>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- ── BOTÓN PRINCIPAL ── -->
        <tr>
          <td style="background:#13102a;padding:0 40px 36px;text-align:center;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;">
            <a href="{{ $resetUrl }}"
               style="display:inline-block;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#ffffff;text-decoration:none;font-size:15px;font-weight:700;padding:16px 40px;border-radius:10px;">
              Restablecer contraseña →
            </a>
          </td>
        </tr>

        <!-- ── URL DE RESERVA ── -->
        <tr>
          <td style="background:#13102a;padding:0 40px 32px;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;">
            <div style="font-size:12px;color:#64748b;margin-bottom:10px;">
              Si el botón no funciona, copia y pega este enlace en tu navegador:
            </div>
            <div style="background:#1a1535;border:1px solid rgba(139,92,246,0.2);border-radius:8px;padding:12px 16px;word-break:break-all;">
              <span style="font-size:11px;color:#7c3aed;font-family:monospace;">{{ $resetUrl }}</span>
            </div>
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

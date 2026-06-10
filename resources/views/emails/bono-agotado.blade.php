<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tu bono se ha agotado — VIBEZ</title>
<style>
  body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
  table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
  body { margin: 0; padding: 0; background-color: #0f0d1e; font-family: Arial, sans-serif; }
</style>
</head>
<body style="margin:0;padding:0;background-color:#0f0d1e;">

@php
  $usuario = $bono->usuario;
  $tipo    = $bono->tipo;
  $evento  = $bono->evento;
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
              ¡Tu bono se ha agotado!
            </h1>
            <p style="margin:0;font-size:15px;color:rgba(148,163,184,0.9);">
              Hola <strong style="color:#a78bfa;">{{ $usuario?->nombre }}</strong>,
              has consumido todas las bebidas de tu bono.
            </p>
          </td>
        </tr>

        <!-- Detalle del bono -->
        <tr>
          <td style="background:#13102a;padding:0 40px 28px;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                   style="background:#1a1535;border:1px solid rgba(239,68,68,0.2);border-radius:12px;">
              <tr>
                <td style="padding:20px 24px;">
                  <div style="font-size:11px;font-weight:700;color:rgba(196,181,253,0.6);letter-spacing:0.1em;text-transform:uppercase;margin-bottom:14px;">
                    Resumen del bono
                  </div>
                  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:10px;">
                    <tr>
                      <td style="font-size:13px;color:rgba(148,163,184,0.7);">Tipo de bono</td>
                      <td style="font-size:14px;color:#ffffff;text-align:right;font-weight:700;">{{ $tipo?->cantidad_bebidas ?? '?' }} bebidas</td>
                    </tr>
                  </table>
                  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:10px;">
                    <tr>
                      <td style="font-size:13px;color:rgba(148,163,184,0.7);">Evento</td>
                      <td style="font-size:14px;color:#f5f1ea;text-align:right;">{{ $evento?->titulo ?? '—' }}</td>
                    </tr>
                  </table>
                  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:10px;">
                    <tr>
                      <td style="font-size:13px;color:rgba(148,163,184,0.7);">Saldo restante</td>
                      <td style="font-size:14px;font-weight:700;color:#f87171;text-align:right;">0 bebidas</td>
                    </tr>
                  </table>
                  <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td style="font-size:13px;color:rgba(148,163,184,0.7);">Agotado el</td>
                      <td style="font-size:14px;color:#f5f1ea;text-align:right;">{{ now()->format('d/m/Y H:i') }}</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- CTA comprar otro bono -->
        <tr>
          <td style="background:#13102a;padding:16px 40px 32px;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;text-align:center;">
            <p style="margin:0 0 16px;font-size:14px;color:rgba(148,163,184,0.7);line-height:1.5;">
              ¿La noche aún no ha terminado? Puedes comprar un nuevo bono desde la app.
            </p>
            <a href="{{ route('entradas.mis-entradas') }}"
               style="display:inline-block;background:linear-gradient(135deg,#7c3aed,#a855f7);color:#fff;padding:12px 28px;border-radius:999px;font-family:Arial,sans-serif;font-size:14px;font-weight:700;text-decoration:none;letter-spacing:0.03em;">
              Ir a Mis Entradas
            </a>
          </td>
        </tr>

        <!-- Footer -->
        <tr>
          <td style="background:#0f0d1e;border:1px solid #1e1b4b;border-top:none;border-radius:0 0 16px 16px;padding:24px 40px;text-align:center;">
            <p style="margin:0;font-size:13px;color:rgba(148,163,184,0.5);">
              © {{ date('Y') }} VIBEZ · Plataforma de eventos para jóvenes
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>

</body>
</html>

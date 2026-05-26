<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reembolso procesado — VIBEZ</title>
<style>
  body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
  table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
  img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
  body { margin: 0; padding: 0; background-color: #0f0d1e; font-family: Arial, sans-serif; }
</style>
</head>
<body style="margin:0;padding:0;background-color:#0f0d1e;">

@php
  $evento  = $pedido->entradas->first()?->evento;
  $usuario = $pedido->usuario;
  $cantidad = $pedido->entradas->count();
@endphp

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

            <!-- Círculo con icono de reembolso -->
            <div style="width:72px;height:72px;background:rgba(34,197,94,0.12);border:2px solid rgba(34,197,94,0.3);border-radius:50%;margin:0 auto 20px;display:inline-block;line-height:72px;text-align:center;font-size:32px;">
              💸
            </div>

            <h1 style="margin:0 0 10px;font-size:24px;font-weight:900;color:#ffffff;">
              Reembolso confirmado
            </h1>
            <p style="margin:0;font-size:15px;color:rgba(148,163,184,0.9);line-height:1.6;">
              Hola <strong style="color:#a78bfa;">{{ $usuario->nombre }}</strong>, hemos procesado tu reembolso correctamente.<br>
              Recibirás el importe en tu método de pago original en los próximos días.
            </p>
          </td>
        </tr>

        <!-- ── RESUMEN DEL REEMBOLSO ── -->
        <tr>
          <td style="background:#13102a;padding:4px 40px 28px;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                   style="background:#1a1535;border:1px solid rgba(139,92,246,0.2);border-radius:12px;overflow:hidden;">
              <tr>
                <td style="padding:24px;">

                  @if($evento)
                  <!-- Evento -->
                  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:18px;">
                    <tr>
                      <td width="44" valign="top">
                        <div style="width:40px;height:40px;background:linear-gradient(135deg,#7c3aed,#a855f7);border-radius:10px;"></div>
                      </td>
                      <td style="padding-left:12px;">
                        <div style="font-size:16px;font-weight:700;color:#f1f5f9;">{{ $evento->titulo }}</div>
                        <div style="font-size:13px;color:#a78bfa;margin-top:2px;">
                          {{ \Carbon\Carbon::parse($evento->fecha_inicio)->locale('es')->isoFormat('D [de] MMMM [de] YYYY, HH:mm') }}
                        </div>
                        @if($evento->ubicacion_nombre)
                        <div style="font-size:12px;color:#64748b;margin-top:2px;">📍 {{ $evento->ubicacion_nombre }}</div>
                        @endif
                      </td>
                    </tr>
                  </table>
                  @endif

                  <!-- Separador -->
                  <div style="border-top:1px solid rgba(139,92,246,0.15);margin-bottom:18px;"></div>

                  <!-- Filas de detalle -->
                  <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td style="font-size:13px;color:#64748b;padding-bottom:10px;">Pedido</td>
                      <td align="right" style="font-size:13px;color:#94a3b8;padding-bottom:10px;">#{{ $pedido->id }}</td>
                    </tr>
                    <tr>
                      <td style="font-size:13px;color:#64748b;padding-bottom:10px;">Entradas canceladas</td>
                      <td align="right" style="font-size:13px;color:#94a3b8;padding-bottom:10px;">
                        {{ $cantidad }} entrada{{ $cantidad !== 1 ? 's' : '' }}
                      </td>
                    </tr>
                    <tr>
                      <td style="font-size:13px;color:#64748b;padding-bottom:4px;">Importe reembolsado</td>
                      <td align="right" style="padding-bottom:4px;">
                        @if($pedido->total_final == 0)
                          <span style="font-size:18px;font-weight:900;color:#22c55e;">GRATIS</span>
                        @else
                          <span style="font-size:18px;font-weight:900;color:#22c55e;">{{ number_format($pedido->total_final, 2) }} €</span>
                        @endif
                      </td>
                    </tr>
                  </table>

                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- ── AVISO PLAZO ── -->
        <tr>
          <td style="background:#13102a;padding:0 40px 32px;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                   style="background:rgba(34,197,94,0.06);border:1px solid rgba(34,197,94,0.2);border-radius:10px;">
              <tr>
                <td style="padding:14px 18px;">
                  <div style="font-size:13px;color:#4ade80;font-weight:700;margin-bottom:4px;">ℹ Plazo de devolución</div>
                  <div style="font-size:12px;color:#94a3b8;line-height:1.6;">
                    Los reembolsos con tarjeta pueden tardar entre <strong style="color:#cbd5e1;">5 y 10 días hábiles</strong>
                    en aparecer en tu extracto bancario, según tu entidad financiera.
                  </div>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- ── BOTÓN VER ENTRADAS ── -->
        <tr>
          <td style="background:#13102a;padding:0 40px 36px;text-align:center;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;">
            <a href="{{ url('/mis-entradas') }}"
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
              © {{ date('Y') }} VIBEZ — Plataforma de eventos para jóvenes.
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

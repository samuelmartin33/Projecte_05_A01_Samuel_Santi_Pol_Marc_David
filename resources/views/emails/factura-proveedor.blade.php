<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reposición confirmada — VIBEZ</title>
<style>
  /* Reset básico para clientes de correo */
  body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
  table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
  body { margin: 0; padding: 0; background-color: #0f0d1e; font-family: Arial, sans-serif; }
</style>
</head>
<body style="margin:0;padding:0;background-color:#0f0d1e;">

@php
  $producto  = $pedido->producto;
  $evento    = $producto?->evento;
  $usuario   = $camarero->usuario;
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
            <div style="font-size:13px;color:rgba(196,181,253,0.85);letter-spacing:0.05em;">Gestión de stock de barra</div>
          </td>
        </tr>

        <!-- ── HERO ── -->
        <tr>
          <td style="background:#13102a;padding:36px 40px 28px;text-align:center;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;">
            <h1 style="margin:0 0 8px;font-size:24px;font-weight:900;color:#ffffff;">
              📦 ¡Reposición confirmada!
            </h1>
            <p style="margin:0;font-size:15px;color:rgba(148,163,184,0.9);">
              Hola <strong style="color:#a78bfa;">{{ $usuario->nombre }}</strong>, tu pedido de reposición se ha procesado correctamente.
            </p>
          </td>
        </tr>

        <!-- ── RESUMEN DEL PEDIDO ── -->
        <tr>
          <td style="background:#13102a;padding:0 40px 28px;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                   style="background:#1a1535;border:1px solid rgba(139,92,246,0.2);border-radius:12px;overflow:hidden;">
              <tr>
                <td style="padding:20px 24px;">

                  <!-- Datos del producto -->
                  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:16px;">
                    <tr>
                      <td style="font-size:12px;font-weight:700;color:#a78bfa;text-transform:uppercase;letter-spacing:0.06em;padding-bottom:12px;">
                        Detalle de la reposición
                      </td>
                    </tr>
                    <tr>
                      <td style="font-size:13px;color:#94a3b8;padding:6px 0;">Producto</td>
                      <td align="right" style="font-size:13px;font-weight:700;color:#f1f5f9;">{{ $producto?->nombre ?? '—' }}</td>
                    </tr>
                    <tr>
                      <td style="font-size:13px;color:#94a3b8;padding:6px 0;">Proveedor</td>
                      <td align="right" style="font-size:13px;color:#f1f5f9;">{{ $producto?->proveedor ?? '—' }}</td>
                    </tr>
                    <tr>
                      <td style="font-size:13px;color:#94a3b8;padding:6px 0;">Evento</td>
                      <td align="right" style="font-size:13px;color:#f1f5f9;">{{ $evento?->titulo ?? '—' }}</td>
                    </tr>
                    <tr>
                      <td style="font-size:13px;color:#94a3b8;padding:6px 0;">Cantidad repuesta</td>
                      <td align="right" style="font-size:13px;font-weight:700;color:#10b981;">{{ $pedido->cantidad }} unidades</td>
                    </tr>
                  </table>

                  <!-- Separador -->
                  <div style="border-top:1px solid rgba(139,92,246,0.15);margin-bottom:16px;"></div>

                  <!-- Total -->
                  <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td style="font-size:14px;font-weight:700;color:#94a3b8;">Total pagado</td>
                      <td align="right">
                        <span style="font-size:22px;font-weight:900;color:#a78bfa;">{{ number_format($pedido->precio_total, 2) }} €</span>
                      </td>
                    </tr>
                  </table>

                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- ── AVISO ADJUNTO ── -->
        <tr>
          <td style="background:#13102a;padding:0 40px 32px;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                   style="background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.25);border-radius:10px;">
              <tr>
                <td style="padding:14px 18px;">
                  <div style="font-size:13px;color:#34d399;font-weight:700;margin-bottom:4px;">📎 Factura adjunta</div>
                  <div style="font-size:12px;color:#94a3b8;line-height:1.6;">
                    Encontrarás la factura de esta reposición adjunta a este correo en formato PDF.
                    Guárdala para tus registros contables.
                  </div>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- ── BOTÓN ── -->
        <tr>
          <td style="background:#13102a;padding:0 40px 36px;text-align:center;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;">
            <a href="{{ route('camarero.stock.index') }}"
               style="display:inline-block;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#ffffff;text-decoration:none;font-size:15px;font-weight:700;padding:14px 36px;border-radius:10px;">
              Ver stock de barra
            </a>
          </td>
        </tr>

        <!-- ── PIE ── -->
        <tr>
          <td style="background:#0d0b1e;border:1px solid #1e1b4b;border-top:none;border-radius:0 0 16px 16px;padding:24px 40px;text-align:center;">
            <div style="font-size:18px;font-weight:900;color:#4c1d95;letter-spacing:0.06em;margin-bottom:8px;">VIBEZ</div>
            <div style="font-size:12px;color:#334155;line-height:1.7;">
              Este correo fue enviado automáticamente. Por favor, no respondas a este mensaje.<br>
              © {{ date('Y') }} VIBEZ — Plataforma de gestión de eventos.
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

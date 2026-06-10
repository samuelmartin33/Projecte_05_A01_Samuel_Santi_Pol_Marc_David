<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Confirmación de pago — VIBEZ</title>
<style>
  body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
  table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
  img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
  body { margin: 0; padding: 0; background-color: #0f0d1e; font-family: Arial, sans-serif; }
</style>
</head>
<body style="margin:0;padding:0;background-color:#0f0d1e;">

@php
  $producto = $pedido->producto;
  $evento   = $producto?->evento;
@endphp

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#0f0d1e;">
  <tr>
    <td align="center" style="padding:32px 16px;">
      <table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;">

        <!-- Cabecera VIBEZ -->
        <tr>
          <td style="background:linear-gradient(135deg,#1e1b4b,#312e81);border-radius:16px 16px 0 0;padding:32px 40px;text-align:center;">
            <div style="font-size:28px;font-weight:900;color:#ffffff;letter-spacing:0.08em;margin-bottom:6px;">VIBEZ</div>
            <div style="font-size:13px;color:rgba(196,181,253,0.85);letter-spacing:0.05em;">Gestión de stock · Pasarela de pago</div>
          </td>
        </tr>

        <!-- Hero -->
        <tr>
          <td style="background:#13102a;padding:36px 40px 28px;text-align:center;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;">
            <div style="font-size:3rem;margin-bottom:16px;">✅</div>
            <h1 style="margin:0 0 8px;font-size:24px;font-weight:900;color:#ffffff;">
              ¡Pago confirmado!
            </h1>
            <p style="margin:0;font-size:15px;color:rgba(148,163,184,0.9);">
              Tu pedido <strong style="color:#4ade80;">#{{ $pedido->id }}</strong> ha sido procesado correctamente.
              El stock ha sido actualizado.
            </p>
          </td>
        </tr>

        <!-- Detalles del pedido (factura) -->
        <tr>
          <td style="background:#13102a;padding:0 40px 28px;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                   style="background:#1a1535;border:1px solid rgba(74,222,128,0.2);border-radius:12px;overflow:hidden;">
              <tr>
                <td style="padding:20px 24px;">
                  <div style="font-size:11px;font-weight:700;color:rgba(74,222,128,0.7);letter-spacing:0.1em;text-transform:uppercase;margin-bottom:14px;">
                    Factura de reposición
                  </div>

                  <!-- Producto -->
                  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:10px;">
                    <tr>
                      <td style="font-size:13px;color:rgba(148,163,184,0.7);padding-bottom:4px;">Producto</td>
                      <td style="font-size:13px;color:#f1f5f9;font-weight:700;text-align:right;">{{ $producto?->nombre ?? '—' }}</td>
                    </tr>
                    <tr>
                      <td style="font-size:13px;color:rgba(148,163,184,0.7);padding-bottom:4px;">Tipo</td>
                      <td style="font-size:13px;color:#f1f5f9;text-align:right;">{{ $producto?->tipo_producto ?? '—' }}</td>
                    </tr>
                    <tr>
                      <td style="font-size:13px;color:rgba(148,163,184,0.7);padding-bottom:4px;">Proveedor</td>
                      <td style="font-size:13px;color:#f1f5f9;text-align:right;">{{ $producto?->proveedor ?? '—' }}</td>
                    </tr>
                    <tr>
                      <td style="font-size:13px;color:rgba(148,163,184,0.7);padding-bottom:4px;">Evento</td>
                      <td style="font-size:13px;color:#f1f5f9;text-align:right;">{{ $evento?->titulo ?? '—' }}</td>
                    </tr>
                    <tr>
                      <td style="font-size:13px;color:rgba(148,163,184,0.7);padding-bottom:4px;">Unidades pedidas</td>
                      <td style="font-size:13px;color:#4ade80;font-weight:700;text-align:right;">{{ $pedido->cantidad }} uds.</td>
                    </tr>
                    <tr>
                      <td style="font-size:13px;color:rgba(148,163,184,0.7);padding-bottom:4px;">Precio unitario</td>
                      <td style="font-size:13px;color:#f1f5f9;text-align:right;">{{ number_format($producto?->precio_unitario ?? 0, 2, ',', '.') }} €</td>
                    </tr>
                  </table>

                  <!-- Separador -->
                  <div style="height:1px;background:rgba(74,222,128,0.15);margin:14px 0;"></div>

                  <!-- Total -->
                  <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td style="font-size:15px;font-weight:700;color:#ffffff;">Total pagado</td>
                      <td style="font-size:22px;font-weight:900;color:#4ade80;text-align:right;">
                        {{ number_format($pedido->precio_total, 2, ',', '.') }} €
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Confirmación de stock -->
        <tr>
          <td style="background:#13102a;padding:0 40px 32px;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;">
            <div style="background:rgba(74,222,128,0.08);border:1px solid rgba(74,222,128,0.25);border-radius:10px;padding:16px 20px;text-align:center;">
              <p style="margin:0;font-size:14px;color:rgba(74,222,128,0.9);font-weight:700;">
                📦 Se han añadido <strong>{{ $pedido->cantidad }} unidades</strong> al stock de
                <strong>{{ $producto?->nombre }}</strong>.
              </p>
              <p style="margin:6px 0 0;font-size:12px;color:rgba(148,163,184,0.6);">
                Pedido procesado el {{ $pedido->updated_at->locale('es')->isoFormat('D MMMM YYYY [a las] H:mm') }}
              </p>
            </div>
          </td>
        </tr>

        <!-- Footer -->
        <tr>
          <td style="background:#0d0b1f;border-radius:0 0 16px 16px;padding:24px 40px;text-align:center;border:1px solid #1e1b4b;border-top:none;">
            <p style="margin:0 0 6px;font-size:11px;color:rgba(148,163,184,0.4);letter-spacing:0.05em;">
              Este correo es una confirmación automática de la pasarela de pago de VIBEZ.
            </p>
            <p style="margin:0;font-size:11px;color:rgba(148,163,184,0.3);">
              © {{ date('Y') }} VIBEZ — Plataforma de eventos para jóvenes
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>

</body>
</html>

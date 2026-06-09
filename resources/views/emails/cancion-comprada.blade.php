<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Canción añadida — VIBEZ</title>
<style>
  body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
  table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
  img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
  body { margin: 0; padding: 0; background-color: #0f0d1e; font-family: Arial, sans-serif; }
</style>
</head>
<body style="margin:0;padding:0;background-color:#0f0d1e;">

@php
  $cancion = $registro->cancion;
  $evento  = $registro->evento;
  $esPago  = $registro->precio_pagado > 0;
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

            <!-- Portada de la canción -->
            @if($cancion?->portada_url)
              <img src="{{ url($cancion->portada_url) }}" alt="{{ $cancion->titulo }}"
                   width="88" height="88"
                   style="width:88px;height:88px;object-fit:cover;border-radius:8px;margin-bottom:20px;border:2px solid rgba(124,58,237,0.4);">
            @else
              <div style="width:88px;height:88px;background:rgba(124,58,237,0.15);border-radius:8px;margin:0 auto 20px;display:table-cell;vertical-align:middle;text-align:center;font-size:2.5rem;border:2px solid rgba(124,58,237,0.25);">
                🎵
              </div>
            @endif

            <h1 style="margin:0 0 8px;font-size:24px;font-weight:900;color:#ffffff;">
              ¡{{ $esPago ? 'Compra confirmada' : 'Añadida a tu playlist' }}!
            </h1>
            <p style="margin:0;font-size:15px;color:rgba(148,163,184,0.9);">
              Hola <strong style="color:#a78bfa;">{{ $usuario->nombre }}</strong>, tu canción está en la playlist.
            </p>
          </td>
        </tr>

        <!-- Detalle de la canción -->
        <tr>
          <td style="background:#13102a;padding:0 40px 28px;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                   style="background:#1a1535;border:1px solid rgba(139,92,246,0.2);border-radius:12px;overflow:hidden;">
              <tr>
                <td style="padding:20px 24px;">
                  <div style="font-size:11px;font-weight:700;color:rgba(196,181,253,0.6);letter-spacing:0.1em;text-transform:uppercase;margin-bottom:14px;">
                    Detalle de la canción
                  </div>

                  <!-- Título y artista -->
                  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:12px;">
                    <tr>
                      <td style="font-size:13px;color:rgba(148,163,184,0.7);">Título</td>
                      <td style="font-size:14px;font-weight:700;color:#ffffff;text-align:right;">
                        {{ $cancion?->titulo ?? '—' }}
                      </td>
                    </tr>
                  </table>
                  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:12px;">
                    <tr>
                      <td style="font-size:13px;color:rgba(148,163,184,0.7);">Artista</td>
                      <td style="font-size:14px;color:#c4b5fd;text-align:right;">
                        {{ $cancion?->artista ?? '—' }}
                      </td>
                    </tr>
                  </table>
                  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:12px;">
                    <tr>
                      <td style="font-size:13px;color:rgba(148,163,184,0.7);">Evento</td>
                      <td style="font-size:14px;color:#f5f1ea;text-align:right;">
                        {{ $evento?->titulo ?? '—' }}
                      </td>
                    </tr>
                  </table>
                  @if($evento?->fecha_inicio)
                  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:12px;">
                    <tr>
                      <td style="font-size:13px;color:rgba(148,163,184,0.7);">Fecha del evento</td>
                      <td style="font-size:14px;color:#f5f1ea;text-align:right;">
                        {{ $evento->fecha_inicio->format('d/m/Y H:i') }}
                      </td>
                    </tr>
                  </table>
                  @endif

                  <!-- Separador -->
                  <table width="100%" cellpadding="0" cellspacing="0" border="0"
                         style="border-top:1px solid rgba(139,92,246,0.2);margin:16px 0 16px;"><tr><td></td></tr></table>

                  <!-- Importe -->
                  <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td style="font-size:13px;font-weight:700;color:rgba(196,181,253,0.7);">Importe {{ $esPago ? 'pagado' : '' }}</td>
                      <td style="font-size:22px;font-weight:900;color:{{ $esPago ? '#a78bfa' : '#4ade80' }};text-align:right;">
                        {{ $esPago ? number_format($registro->precio_pagado, 2) . ' €' : 'Gratis' }}
                      </td>
                    </tr>
                  </table>

                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Mensaje de cierre -->
        <tr>
          <td style="background:#13102a;padding:20px 40px 32px;border-left:1px solid #1e1b4b;border-right:1px solid #1e1b4b;text-align:center;">
            <p style="margin:0;font-size:14px;color:rgba(148,163,184,0.7);line-height:1.7;">
              Tu canción se reproducirá durante el evento. ¡Disfruta de la noche!
            </p>
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

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:Arial,Helvetica,sans-serif; font-size:12px; color:#1a1a2e; background:#fff; }
.page { padding:40px 45px; }

/* Cabecera */
.header-table { width:100%; border-collapse:collapse; margin-bottom:28px; }
.logo-text     { font-size:28px; font-weight:900; color:#7c3aed; letter-spacing:2px; }
.logo-sub      { font-size:9px; color:#999; text-transform:uppercase; letter-spacing:1.2px; margin-top:3px; }
.ref-cell      { text-align:right; vertical-align:top; }
.titulo-doc    { font-size:18px; font-weight:900; color:#1a1a2e; }
.numero-factura{ font-size:11px; color:#7c3aed; font-weight:700; margin-top:5px; }
.fecha-gen     { font-size:10px; color:#666; margin-top:4px; }
.doc-badge     { display:inline-block; background:#7c3aed; color:#fff;
                 padding:3px 10px; font-size:9px; font-weight:700;
                 text-transform:uppercase; letter-spacing:1px; margin-top:8px; }

/* Separador */
.divider { border:none; border-top:2px solid #7c3aed; margin:0 0 24px; }

/* Partes */
.partes-table { width:100%; border-collapse:collapse; margin-bottom:22px; }
.parte-cell   { width:50%; vertical-align:top; padding-right:20px; }
.parte-label  { font-size:8px; font-weight:700; text-transform:uppercase;
                letter-spacing:1.5px; color:#7c3aed; margin-bottom:6px; }
.parte-nombre { font-size:13px; font-weight:700; color:#1a1a2e; margin-bottom:3px; }
.parte-dato   { font-size:10px; color:#555; margin-bottom:2px; }

/* Caja Premium */
.premium-box   { background:#f3f0ff; border-left:4px solid #7c3aed;
                 padding:12px 16px; margin-bottom:24px; }
.premium-label { font-size:8px; font-weight:700; text-transform:uppercase;
                 letter-spacing:1.5px; color:#7c3aed; margin-bottom:5px; }
.premium-titulo{ font-size:15px; font-weight:900; color:#1a1a2e; }
.premium-meta  { font-size:10px; color:#666; margin-top:4px; }

/* Tabla de líneas */
.lineas-table       { width:100%; border-collapse:collapse; margin-bottom:16px; }
.lineas-table thead tr { background:#1a1a2e; color:#fff; }
.lineas-table th    { padding:9px 12px; font-size:9px; text-transform:uppercase;
                      letter-spacing:.8px; text-align:left; font-weight:700; }
.lineas-table th.r  { text-align:right; }
.lineas-table td    { padding:8px 12px; font-size:10px; color:#333;
                      border-bottom:1px solid #eee; vertical-align:top; }
.lineas-table td.r  { text-align:right; }
.td-precio { color:#1a1a2e; font-weight:700; }

/* Totales */
.totales-wrap  { text-align:right; margin-bottom:28px; }
.totales-table { display:inline-table; min-width:280px; border-collapse:collapse; }
.totales-table td { padding:7px 14px; font-size:11px; border-bottom:1px solid #eee; }
.totales-table td.lbl { text-align:left; color:#555; }
.totales-table td.val { text-align:right; font-weight:700; }
.totales-table tr.total-final td { background:#7c3aed; color:#fff;
                                   font-size:14px; font-weight:900; border:none; }

/* Pie */
.footer { margin-top:36px; border-top:1px solid #eee; padding-top:12px;
          font-size:9px; color:#aaa; text-align:center; line-height:1.7; }
.footer strong { color:#7c3aed; }
</style>
</head>
<body>
<div class="page">

    {{-- CABECERA --}}
    <table class="header-table">
        <tr>
            <td style="vertical-align:top;width:55%;">
                <div class="logo-text">VIBEZ</div>
                <div class="logo-sub">Plataforma de eventos para jóvenes</div>
            </td>
            <td class="ref-cell">
                <div class="titulo-doc">Justificante de Compra</div>
                <div class="numero-factura">Ref. {{ strtoupper(substr($sessionId, -12)) }}</div>
                <div class="fecha-gen">
                    Fecha: {{ $fechaPago->format('d/m/Y \a \l\a\s H:i') }}
                </div>
                <div>
                    <span class="doc-badge">Premium</span>
                </div>
            </td>
        </tr>
    </table>

    <hr class="divider">

    {{-- COMPRADOR / EMISOR --}}
    <table class="partes-table">
        <tr>
            <td class="parte-cell">
                <div class="parte-label">Emitido por</div>
                <div class="parte-nombre">VIBEZ Platform</div>
                <div class="parte-dato">vibez.es</div>
            </td>
            <td class="parte-cell">
                <div class="parte-label">Comprador</div>
                <div class="parte-nombre">{{ $usuario->nombre }} {{ $usuario->apellido1 }}</div>
                <div class="parte-dato">{{ $usuario->email }}</div>
            </td>
        </tr>
    </table>

    {{-- CAJA PRODUCTO --}}
    <div class="premium-box">
        <div class="premium-label">Producto</div>
        <div class="premium-titulo">VIBEZ Premium</div>
        <div class="premium-meta">
            Suscripción de pago único · sin renovación automática
        </div>
    </div>

    {{-- TABLA DE LÍNEAS --}}
    <table class="lineas-table">
        <thead>
            <tr>
                <th style="width:8%;">#</th>
                <th style="width:57%;">Descripción</th>
                <th class="r" style="width:17%;">Precio unit.</th>
                <th class="r" style="width:18%;">Importe</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color:#aaa;font-size:9px;">1</td>
                <td>
                    VIBEZ Premium — Acceso a cupones exclusivos<br>
                    <span style="font-size:9px;color:#aaa;">Ref: {{ strtoupper(substr($sessionId, -8)) }}</span>
                </td>
                <td class="r">
                    <span class="td-precio">{{ number_format($importe, 2, ',', '.') }} €</span>
                </td>
                <td class="r">
                    <span class="td-precio">{{ number_format($importe, 2, ',', '.') }} €</span>
                </td>
            </tr>
        </tbody>
    </table>

    {{-- TOTALES --}}
    <div class="totales-wrap">
        <table class="totales-table">
            <tr>
                <td class="lbl">Subtotal</td>
                <td class="val">{{ number_format($importe, 2, ',', '.') }} €</td>
            </tr>
            <tr class="total-final">
                <td class="lbl">TOTAL PAGADO</td>
                <td class="val">{{ number_format($importe, 2, ',', '.') }} €</td>
            </tr>
        </table>
    </div>

    {{-- PIE --}}
    <div class="footer">
        Documento generado automáticamente por <strong>VIBEZ Platform</strong>
        el {{ now()->format('d/m/Y \a \l\a\s H:i') }}.<br>
        Este justificante acredita la activación del plan Premium. · <strong>vibez.es</strong>
    </div>

</div>
</body>
</html>

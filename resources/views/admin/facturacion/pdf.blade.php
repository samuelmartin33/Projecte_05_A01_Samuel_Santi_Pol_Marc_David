<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:Arial,Helvetica,sans-serif; font-size:12px; color:#1a1a2e; background:#fff; }

.page { padding:40px 45px; }

/* Cabecera */
.header-table  { width:100%; border-collapse:collapse; margin-bottom:28px; }
.logo-text     { font-size:26px; font-weight:900; color:#7c3aed; letter-spacing:2px; }
.logo-sub      { font-size:9px; color:#999; text-transform:uppercase; letter-spacing:1.2px; margin-top:3px; }
.logo-datos    { font-size:9px; color:#666; margin-top:10px; line-height:1.6; }
.ref-cell      { text-align:right; vertical-align:top; }
.numero-factura{ font-size:20px; font-weight:900; color:#1a1a2e; }
.fecha-emision { font-size:10px; color:#666; margin-top:5px; }
.estado-badge  { display:inline-block; background:#059669; color:#fff;
                 padding:3px 10px; font-size:9px; font-weight:700;
                 text-transform:uppercase; letter-spacing:1px; margin-top:8px; }

/* Separador morado */
.divider { border:none; border-top:2px solid #7c3aed; margin:0 0 24px; }

/* Bloques emisor / receptor */
.partes-table { width:100%; border-collapse:collapse; margin-bottom:22px; }
.parte-cell   { width:50%; vertical-align:top; padding-right:20px; }
.parte-label  { font-size:8px; font-weight:700; text-transform:uppercase;
                letter-spacing:1.5px; color:#7c3aed; margin-bottom:6px; }
.parte-nombre { font-size:13px; font-weight:700; color:#1a1a2e; margin-bottom:3px; }
.parte-dato   { font-size:10px; color:#555; margin-bottom:2px; }

/* Caja evento */
.evento-box   { background:#f3f0ff; border-left:4px solid #7c3aed;
                padding:12px 16px; margin-bottom:24px; }
.evento-label { font-size:8px; font-weight:700; text-transform:uppercase;
                letter-spacing:1.5px; color:#7c3aed; margin-bottom:5px; }
.evento-titulo{ font-size:14px; font-weight:900; color:#1a1a2e; }
.evento-meta  { font-size:10px; color:#666; margin-top:4px; }

/* Tabla de líneas */
.lineas-table       { width:100%; border-collapse:collapse; margin-bottom:16px; }
.lineas-table thead tr { background:#1a1a2e; color:#fff; }
.lineas-table th    { padding:9px 12px; font-size:9px; text-transform:uppercase;
                      letter-spacing:.8px; text-align:left; font-weight:700; }
.lineas-table th.r  { text-align:right; }
.lineas-table td    { padding:10px 12px; font-size:11px; color:#333;
                      border-bottom:1px solid #eee; vertical-align:top; }
.lineas-table td.r  { text-align:right; }
.td-concepto        { font-weight:600; color:#1a1a2e; }
.td-cargo           { color:#dc2626; font-weight:700; }
.td-venta           { color:#059669; font-weight:700; }

/* Tabla de totales (flotar a la derecha) */
.totales-wrap   { text-align:right; margin-bottom:28px; }
.totales-table  { display:inline-table; min-width:300px; border-collapse:collapse; }
.totales-table td { padding:7px 14px; font-size:11px; border-bottom:1px solid #eee; }
.totales-table td.lbl  { text-align:left; color:#555; }
.totales-table td.val  { text-align:right; font-weight:700; }
.totales-table tr.neto td { background:#7c3aed; color:#fff; font-size:14px;
                             font-weight:900; border:none; }

/* Notas */
.notas-box { margin-top:16px; padding:10px 14px; background:#fafafa;
             border:1px solid #eee; font-size:10px; color:#555; line-height:1.6; }

/* Pie de página */
.footer { margin-top:36px; border-top:1px solid #eee; padding-top:12px;
          font-size:9px; color:#aaa; text-align:center; line-height:1.7; }
.footer strong { color:#7c3aed; }
</style>
</head>
<body>
<div class="page">

    {{-- ── CABECERA ── --}}
    <table class="header-table">
        <tr>
            <td style="vertical-align:top;width:55%;">
                <div class="logo-text">VIBEZ</div>
                <div class="logo-sub">Plataforma de eventos para jóvenes</div>
                <div class="logo-datos">
                    VIBEZ Platform S.L. · NIF: B-XXXXXXXX<br>
                    Calle Ejemplo 123, 08001 Barcelona<br>
                    facturacion@vibez.es
                </div>
            </td>
            <td class="ref-cell">
                <div class="numero-factura">{{ $factura->numero_factura }}</div>
                <div class="fecha-emision">
                    Emisión: {{ $factura->fecha_emision->format('d/m/Y \a \l\a\s H:i') }}
                </div>
                <div>
                    <span class="estado-badge">{{ strtoupper($factura->estado) }}</span>
                </div>
            </td>
        </tr>
    </table>

    <hr class="divider">

    {{-- ── EMISOR / RECEPTOR ── --}}
    <table class="partes-table">
        <tr>
            <td class="parte-cell">
                <div class="parte-label">Emitida por</div>
                <div class="parte-nombre">VIBEZ Platform S.L.</div>
                <div class="parte-dato">NIF: B-XXXXXXXX</div>
                <div class="parte-dato">Calle Ejemplo 123, 08001 Barcelona</div>
                <div class="parte-dato">facturacion@vibez.es</div>
            </td>
            <td class="parte-cell">
                <div class="parte-label">Facturado a</div>
                <div class="parte-nombre">{{ $factura->nombre_empresa_frozen }}</div>
                @if($factura->razon_social_frozen)
                    <div class="parte-dato">{{ $factura->razon_social_frozen }}</div>
                @endif
                @if($factura->nif_cif_frozen)
                    <div class="parte-dato">NIF/CIF: {{ $factura->nif_cif_frozen }}</div>
                @endif
                @if($factura->direccion_frozen)
                    <div class="parte-dato">{{ $factura->direccion_frozen }}</div>
                @endif
            </td>
        </tr>
    </table>

    {{-- ── DATOS DEL EVENTO ── --}}
    <div class="evento-box">
        <div class="evento-label">Evento facturado</div>
        <div class="evento-titulo">{{ $factura->nombre_evento_frozen }}</div>
        <div class="evento-meta">
            Fecha del evento:
            {{ \Carbon\Carbon::parse($factura->fecha_evento_frozen)->format('d/m/Y H:i') }}
            &nbsp;&bull;&nbsp;
            {{ number_format($factura->total_entradas_vendidas) }} entradas vendidas
        </div>
    </div>

    {{-- ── LÍNEAS DE DETALLE ── --}}
    <table class="lineas-table">
        <thead>
            <tr>
                <th style="width:50%;">Concepto</th>
                <th class="r" style="width:12%;">Cantidad</th>
                <th class="r" style="width:18%;">Precio unit.</th>
                <th class="r" style="width:20%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
        @foreach($factura->lineas as $linea)
            <tr>
                <td class="td-concepto">{{ $linea->concepto }}</td>
                <td class="r">{{ $linea->cantidad }}</td>
                <td class="r">
                    @if($linea->tipo === 'venta')
                        {{ number_format(abs($linea->precio_unitario), 4, ',', '.') }} €
                    @else
                        {{ number_format(abs($linea->precio_unitario), 2, ',', '.') }} €
                    @endif
                </td>
                <td class="r {{ $linea->subtotal < 0 ? 'td-cargo' : 'td-venta' }}">
                    @if($linea->subtotal < 0)−@endif{{ number_format(abs($linea->subtotal), 2, ',', '.') }} €
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{-- ── TOTALES ── --}}
    <div class="totales-wrap">
        <table class="totales-table">
            <tr>
                <td class="lbl">Ventas brutas</td>
                <td class="val">{{ number_format($factura->importe_bruto, 2, ',', '.') }} €</td>
            </tr>
            @if($factura->porcentaje_comision > 0)
            <tr>
                <td class="lbl">Comisión VIBEZ ({{ $factura->porcentaje_comision }}%)</td>
                <td class="val" style="color:#dc2626;">−{{ number_format($factura->importe_comision, 2, ',', '.') }} €</td>
            </tr>
            @if($factura->tipo_iva > 0)
            <tr>
                <td class="lbl">IVA comisión ({{ $factura->tipo_iva }}%)</td>
                <td class="val" style="color:#dc2626;">−{{ number_format($factura->cuota_iva, 2, ',', '.') }} €</td>
            </tr>
            @endif
            @endif
            <tr class="neto">
                <td class="lbl">NETO A LIQUIDAR</td>
                <td class="val">{{ number_format($factura->importe_neto_empresa, 2, ',', '.') }} €</td>
            </tr>
        </table>
    </div>

    @if($factura->notas)
    <div class="notas-box">
        <strong>Notas:</strong> {{ $factura->notas }}
    </div>
    @endif

    {{-- ── PIE ── --}}
    <div class="footer">
        Documento generado automáticamente por <strong>VIBEZ Platform</strong>
        el {{ $factura->fecha_emision->format('d/m/Y \a \l\a\s H:i') }}.<br>
        Este documento es válido como liquidación de ventas.
        <strong>Referencia: {{ $factura->numero_factura }}</strong> · facturacion@vibez.es
    </div>

</div>
</body>
</html>

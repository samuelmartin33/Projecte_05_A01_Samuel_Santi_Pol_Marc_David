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
.logo-text     { font-size:28px; font-weight:900; color:#7c3aed; letter-spacing:2px; }
.logo-sub      { font-size:9px; color:#999; text-transform:uppercase; letter-spacing:1.2px; margin-top:3px; }
.ref-cell      { text-align:right; vertical-align:top; }
.titulo-doc    { font-size:18px; font-weight:900; color:#1a1a2e; }
.numero-fact   { font-size:11px; color:#7c3aed; font-weight:700; margin-top:5px; }
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

/* Caja de evento */
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
.lineas-table td    { padding:8px 12px; font-size:10px; color:#333;
                      border-bottom:1px solid #eee; vertical-align:top; }
.lineas-table td.r  { text-align:right; }
.lineas-table tbody tr:nth-child(even) td { background:#fafafa; }

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

@php
    $producto  = $pedido->producto;
    $evento    = $producto?->evento;
    $usuario   = $camarero->usuario;
    $empresa   = $camarero->empresa;
    $anyo      = now()->year;
    $numFact   = 'PROV-' . str_pad($pedido->id, 6, '0', STR_PAD_LEFT) . '-' . $anyo;
@endphp

    {{-- CABECERA --}}
    <table class="header-table">
        <tr>
            <td style="vertical-align:top;width:55%;">
                <div class="logo-text">VIBEZ</div>
                <div class="logo-sub">Plataforma de gestión de eventos</div>
            </td>
            <td class="ref-cell">
                <div class="titulo-doc">Factura de Reposición</div>
                <div class="numero-fact">{{ $numFact }}</div>
                <div class="fecha-gen">Fecha: {{ now()->format('d/m/Y') }}</div>
                <div><span class="doc-badge">Stock de Barra</span></div>
            </td>
        </tr>
    </table>

    <hr class="divider">

    {{-- PARTES --}}
    <table class="partes-table">
        <tr>
            <td class="parte-cell">
                <div class="parte-label">Proveedor / Suministrador</div>
                <div class="parte-nombre">{{ $producto?->proveedor ?? '—' }}</div>
                <div class="parte-dato">Proveedor de hostelería</div>
            </td>
            <td class="parte-cell">
                <div class="parte-label">Comprador</div>
                <div class="parte-nombre">{{ $usuario->nombre }} {{ $usuario->apellido1 }}</div>
                <div class="parte-dato">{{ $usuario->email }}</div>
                @if($empresa)
                    <div class="parte-dato">{{ $empresa->nombre_empresa }}</div>
                @endif
            </td>
        </tr>
    </table>

    {{-- EVENTO --}}
    @if($evento)
    <div class="evento-box">
        <div class="evento-label">Evento asociado</div>
        <div class="evento-titulo">{{ $evento->titulo }}</div>
        @if($evento->fecha_inicio)
        <div class="evento-meta">
            Fecha evento: {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y H:i') }}
        </div>
        @endif
    </div>
    @endif

    {{-- TABLA DE LÍNEAS --}}
    <table class="lineas-table">
        <thead>
            <tr>
                <th style="width:40%;">Producto</th>
                <th style="width:15%;">Tipo</th>
                <th style="width:15%;" class="r">Cantidad</th>
                <th style="width:15%;" class="r">Precio unit.</th>
                <th style="width:15%;" class="r">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $producto?->nombre ?? '—' }}</td>
                <td>
                    @php
                        $etiquetaTipo = match($producto?->tipo_producto) {
                            'cocktail'    => 'Cocktail',
                            'destilado'   => 'Destilado',
                            'sin_alcohol' => 'Sin alcohol',
                            default       => ucfirst($producto?->tipo_producto ?? '—'),
                        };
                    @endphp
                    {{ $etiquetaTipo }}
                </td>
                <td class="r">{{ $pedido->cantidad }} ud.</td>
                <td class="r">{{ number_format($producto?->precio_unitario ?? 0, 2, ',', '.') }} €</td>
                <td class="r" style="font-weight:700;">{{ number_format($pedido->precio_total, 2, ',', '.') }} €</td>
            </tr>
        </tbody>
    </table>

    {{-- TOTALES --}}
    <div class="totales-wrap">
        <table class="totales-table">
            <tr>
                <td class="lbl">Subtotal (sin IVA)</td>
                <td class="val">{{ number_format($pedido->precio_total / 1.21, 2, ',', '.') }} €</td>
            </tr>
            <tr>
                <td class="lbl">IVA (21%)</td>
                <td class="val">{{ number_format($pedido->precio_total - ($pedido->precio_total / 1.21), 2, ',', '.') }} €</td>
            </tr>
            <tr class="total-final">
                <td class="lbl">TOTAL PAGADO</td>
                <td class="val">{{ number_format($pedido->precio_total, 2, ',', '.') }} €</td>
            </tr>
        </table>
    </div>

    {{-- PIE --}}
    <div class="footer">
        Factura generada automáticamente por <strong>VIBEZ Platform</strong>
        el {{ now()->format('d/m/Y \a \l\a\s H:i') }}.<br>
        Referencia: <strong>{{ $numFact }}</strong> · <strong>vibez.es</strong>
    </div>

</div>
</body>
</html>

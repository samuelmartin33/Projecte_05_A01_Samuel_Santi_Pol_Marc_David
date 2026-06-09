<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe de bonos — VIBEZ</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #1a1a2e;
            background: #ffffff;
            padding: 30px;
        }
        .pdf-header {
            border-bottom: 3px solid #d4537e;
            padding-bottom: 14px;
            margin-bottom: 20px;
        }
        .pdf-header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 6px;
        }
        .pdf-brand { font-size: 20px; font-weight: 700; color: #d4537e; letter-spacing: 0.1em; }
        .pdf-meta  { text-align: right; color: #666; font-size: 10px; line-height: 1.6; }
        .pdf-title { font-size: 15px; font-weight: 700; color: #1a1a2e; margin-bottom: 2px; }
        .pdf-subtitle { font-size: 10px; color: #777; }

        h2.evento-nombre {
            font-size: 13px;
            font-weight: 700;
            color: #d4537e;
            margin: 22px 0 8px;
            padding-bottom: 4px;
            border-bottom: 1px solid #f7d0de;
        }

        table { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
        thead tr { background: #d4537e; color: #fff; }
        thead th {
            padding: 7px 10px;
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        thead th.num { text-align: right; }
        tbody tr { border-bottom: 1px solid #f2d4dc; }
        tbody tr:nth-child(even) { background: #fff5f8; }
        tbody td { padding: 7px 10px; font-size: 11px; color: #1a1a2e; }
        tbody td.num { text-align: right; font-variant-numeric: tabular-nums; }
        tfoot tr { background: #fce9ef; border-top: 2px solid #d4537e; }
        tfoot td { padding: 8px 10px; font-size: 12px; font-weight: 700; color: #a53060; }
        tfoot td.num { text-align: right; }

        .sin-datos { text-align: center; color: #aaa; font-style: italic; padding: 18px; }
        .pdf-footer {
            margin-top: 28px;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
            font-size: 9px;
            color: #aaa;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="pdf-header">
        <div class="pdf-header-top">
            <div class="pdf-brand">VIBEZ</div>
            <div class="pdf-meta">
                Generado el {{ now()->format('d/m/Y H:i') }}<br>
                Panel de administración
            </div>
        </div>
        <div class="pdf-title">Informe de bonos de bebidas · Desglose por evento</div>
        <div class="pdf-subtitle">Bonos vendidos agrupados por evento y tipo · Ordenados por evento</div>
    </div>

    @forelse($datos as $fila)
        <h2 class="evento-nombre">
            {{ $fila['evento'] }}
            @if($fila['fecha_evento'] !== '—')
                — {{ $fila['fecha_evento'] }}
            @endif
        </h2>

        <table>
            <thead>
                <tr>
                    <th>Tipo de bono</th>
                    <th class="num">Precio unitario</th>
                    <th class="num">Cantidad vendida</th>
                    <th class="num">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fila['por_tipo'] as $tipo)
                    <tr>
                        <td>Bono {{ $tipo['cantidad_bebidas'] }} bebidas</td>
                        <td class="num">{{ number_format($tipo['precio'], 2) }} €</td>
                        <td class="num">{{ $tipo['cantidad'] }}</td>
                        <td class="num">{{ number_format($tipo['subtotal'], 2) }} €</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">Total del evento</td>
                    <td class="num">{{ $fila['total_bonos'] }} bonos</td>
                    <td class="num">{{ number_format($fila['total_ingresos'], 2) }} €</td>
                </tr>
            </tfoot>
        </table>
    @empty
        <p class="sin-datos">No hay bonos vendidos para los criterios seleccionados.</p>
    @endforelse

    <div class="pdf-footer">
        VIBEZ &copy; {{ now()->year }} · Documento generado automáticamente · Uso interno
    </div>

</body>
</html>

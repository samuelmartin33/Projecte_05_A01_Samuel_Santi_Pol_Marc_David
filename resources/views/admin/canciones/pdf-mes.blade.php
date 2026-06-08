<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe de canciones — {{ $mes }}/{{ $anyo }}</title>
    <style>
        /* DomPDF requiere CSS inline; este archivo NO es una vista Blade normal */
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #1a1a2e;
            background: #ffffff;
            padding: 40px;
        }

        /* ── Cabecera del documento ── */
        .pdf-header {
            border-bottom: 3px solid #7c3aed;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }
        .pdf-header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
        }
        .pdf-brand {
            font-size: 22px;
            font-weight: 700;
            color: #7c3aed;
            letter-spacing: 0.1em;
        }
        .pdf-meta {
            text-align: right;
            color: #555;
            font-size: 11px;
            line-height: 1.6;
        }
        .pdf-title {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 2px;
        }
        .pdf-subtitle {
            font-size: 11px;
            color: #666;
        }

        /* ── Tabla ── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        thead tr {
            background: #7c3aed;
            color: #ffffff;
        }
        thead th {
            padding: 10px 12px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        thead th.num { text-align: right; }

        tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }
        tbody tr:nth-child(even) {
            background: #f5f3ff;
        }
        tbody td {
            padding: 9px 12px;
            font-size: 12px;
            color: #1a1a2e;
        }
        tbody td.num {
            text-align: right;
            font-variant-numeric: tabular-nums;
        }

        /* ── Fila de total ── */
        tfoot tr {
            background: #ede9fe;
            border-top: 2px solid #7c3aed;
        }
        tfoot td {
            padding: 10px 12px;
            font-size: 13px;
            font-weight: 700;
            color: #5b21b6;
        }
        tfoot td.num {
            text-align: right;
        }

        /* ── Sin datos ── */
        .sin-datos {
            text-align: center;
            color: #9ca3af;
            font-style: italic;
            padding: 24px;
        }

        /* ── Pie de página ── */
        .pdf-footer {
            margin-top: 32px;
            border-top: 1px solid #e5e7eb;
            padding-top: 12px;
            font-size: 10px;
            color: #9ca3af;
            text-align: center;
        }
    </style>
</head>
<body>

    {{-- ── Cabecera ──────────────────────────────────────────────── --}}
    <div class="pdf-header">
        <div class="pdf-header-top">
            <div class="pdf-brand">VIBEZ</div>
            <div class="pdf-meta">
                Generado el {{ now()->format('d/m/Y H:i') }}<br>
                Panel de administración
            </div>
        </div>
        <div class="pdf-title">Informe de canciones — {{ \Carbon\Carbon::createFromDate($anyo, $mes, 1)->locale('es')->isoFormat('MMMM [de] YYYY') }}</div>
        <div class="pdf-subtitle">Canciones seleccionadas en playlists de eventos · Ordenadas por popularidad</div>
    </div>

    {{-- ── Tabla de datos ─────────────────────────────────────────── --}}
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Canción</th>
                <th>Artista</th>
                <th class="num">Veces seleccionada</th>
                <th class="num">Ganancia (€)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($datos as $i => $fila)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $fila->cancion?->titulo ?? '—' }}</td>
                    <td>{{ $fila->cancion?->artista ?? '—' }}</td>
                    <td class="num">{{ $fila->veces }}</td>
                    <td class="num">{{ number_format((float) $fila->ganancia, 2) }} €</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="sin-datos">
                        No hay canciones seleccionadas en playlists durante este mes.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if($datos->isNotEmpty())
            <tfoot>
                <tr>
                    <td colspan="4">Total del mes</td>
                    <td class="num">{{ number_format($datos->sum('ganancia'), 2) }} €</td>
                </tr>
            </tfoot>
        @endif
    </table>

    {{-- ── Pie ────────────────────────────────────────────────────── --}}
    <div class="pdf-footer">
        VIBEZ &copy; {{ $anyo }} · Documento generado automáticamente · Uso interno
    </div>

</body>
</html>

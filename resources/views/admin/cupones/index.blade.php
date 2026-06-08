@extends('admin.layouts.dashboard')

@section('title', 'Admin | Cupones activos')

@section('content')
    <header class="admin-header">
        <div>
            <h1>Cupones activos</h1>
            <p>Listado de cupones vigentes creados por las empresas.</p>
        </div>
    </header>

    @if (session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    <div class="admin-search-bar">
        <input type="search"
               class="admin-search-input"
               placeholder="Buscar por código o empresa…"
               oninput="adminBuscar(this, 'cupones')">
        <div id="admin-search-spinner"></div>
    </div>

    <section class="card">
        <table class="tabla-eventos">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Empresa</th>
                    <th>Descuento</th>
                    <th>Válido desde</th>
                    <th>Válido hasta</th>
                    <th>Usos</th>
                    <th>Eventos</th>
                </tr>
            </thead>
            <tbody id="admin-search-tbody">
            @forelse ($cupones as $cupon)
                <tr>
                    <td data-label="Código">
                        <strong style="font-family:monospace;letter-spacing:0.05em;">{{ $cupon->codigo }}</strong>
                    </td>
                    <td data-label="Empresa">
                        {{ $cupon->empresa?->nombre ?? '—' }}
                    </td>
                    <td data-label="Descuento">
                        {{ $cupon->valor_descuento }}%
                    </td>
                    <td data-label="Válido desde">
                        {{ $cupon->fecha_inicio->format('d/m/Y') }}
                    </td>
                    <td data-label="Válido hasta">
                        {{ $cupon->fecha_fin->format('d/m/Y') }}
                    </td>
                    <td data-label="Usos">
                        {{ $cupon->usos_actuales }}
                        @if ($cupon->limite_usos_total)
                            / {{ $cupon->limite_usos_total }}
                        @else
                            / ∞
                        @endif
                    </td>
                    <td data-label="Eventos">
                        @if ($cupon->eventos->isEmpty())
                            <span style="color:rgba(245,241,234,0.4);font-size:0.8rem;">Todos</span>
                        @else
                            <span style="font-size:0.8rem;">{{ $cupon->eventos->count() }} evento(s)</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty">No hay cupones activos en este momento.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </section>

    @push('scripts')
    <script>
    window.adminBuscarRender = function(items) {
        if (!items.length) return '<tr><td colspan="7" class="empty">Sin resultados.</td></tr>';
        return items.map(function(c) {
            var usos = c.usos_actuales + ' / ' + (c.limite_usos ? c.limite_usos : '∞');
            var eventos = c.num_eventos > 0
                ? '<span style="font-size:0.8rem;">' + c.num_eventos + ' evento(s)</span>'
                : '<span style="color:rgba(245,241,234,0.4);font-size:0.8rem;">Todos</span>';
            return '<tr>'
                + '<td data-label="Código"><strong style="font-family:monospace;letter-spacing:0.05em;">' + c.codigo + '</strong></td>'
                + '<td data-label="Empresa">'       + c.empresa           + '</td>'
                + '<td data-label="Descuento">'     + c.valor_descuento   + '%</td>'
                + '<td data-label="Válido desde">'  + c.fecha_inicio      + '</td>'
                + '<td data-label="Válido hasta">'  + c.fecha_fin         + '</td>'
                + '<td data-label="Usos">'          + usos                + '</td>'
                + '<td data-label="Eventos">'       + eventos             + '</td>'
                + '</tr>';
        }).join('');
    };
    </script>
    @endpush
@endsection

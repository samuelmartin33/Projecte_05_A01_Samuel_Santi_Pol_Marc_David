@extends('admin.layouts.dashboard')

@section('title', 'Admin | Pagos')

@section('content')
    <header class="admin-header">
        <div>
            <h1>Pagos</h1>
            <p>Consulta los pagos registrados en la plataforma. Solo lectura.</p>
        </div>
    </header>

    @if (session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    <section class="card">
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pedido</th>
                        <th>Método</th>
                        <th>Estado pago</th>
                        <th>Importe</th>
                        <th>Moneda</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pagos as $pago)
                        <tr>
                            <td data-label="#">{{ $pago->id }}</td>
                            <td data-label="Pedido">#{{ $pago->pedido_id }}</td>
                            <td data-label="Método">{{ $pago->metodo_pago }}</td>
                            <td data-label="Estado pago">{{ $pago->estado_pago }}</td>
                            <td data-label="Importe">{{ number_format($pago->importe, 2) }} {{ $pago->moneda }}</td>
                            <td data-label="Moneda">{{ $pago->moneda }}</td>
                            <td data-label="Estado">
                                <span class="estado {{ (int) $pago->estado === 1 ? 'activo' : 'inactivo' }}">
                                    {{ (int) $pago->estado === 1 ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="empty">No hay pagos registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @if ($pagos->hasPages())
        <nav class="paginacion" aria-label="Paginacion de pagos">
            <div class="pagination-summary">
                Mostrando <strong>{{ $pagos->firstItem() }}</strong> a <strong>{{ $pagos->lastItem() }}</strong> de <strong>{{ $pagos->total() }}</strong> resultados
            </div>
            <div class="pagination-controls">
                @if ($pagos->onFirstPage())
                    <span class="pagination-arrow disabled" aria-hidden="true">‹</span>
                @else
                    <a class="pagination-arrow" href="{{ $pagos->previousPageUrl() }}" rel="prev">‹</a>
                @endif
                @if ($pagos->hasMorePages())
                    <a class="pagination-arrow" href="{{ $pagos->nextPageUrl() }}" rel="next">›</a>
                @else
                    <span class="pagination-arrow disabled" aria-hidden="true">›</span>
                @endif
            </div>
        </nav>
    @endif
@endsection
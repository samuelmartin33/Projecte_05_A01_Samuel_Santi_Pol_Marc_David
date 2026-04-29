@extends('admin.layouts.dashboard')

@section('title', 'Admin | Pagos')

@section('content')
    <header class="admin-header">
        <div>
            <h1>Gestión de Pagos</h1>
            <p>Consulta, crea y edita pagos registrados en la plataforma.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('admin.pagos.create') }}">Nuevo pago</a>
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
                        <th>Acciones</th>
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
                            <td data-label="Acciones" class="actions-cell">
                                <a class="btn btn-secondary" href="{{ route('admin.pagos.edit', $pago) }}">Editar</a>
                                <form method="POST" action="{{ route('admin.pagos.destroy', $pago) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar el pago #{{ $pago->id }}?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="empty">No hay pagos registrados.</td></tr>
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
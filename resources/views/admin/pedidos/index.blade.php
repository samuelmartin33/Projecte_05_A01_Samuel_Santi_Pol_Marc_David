@extends('admin.layouts.dashboard')

@section('title', 'Admin | Pedidos')

@section('content')
    <header class="admin-header">
        <div>
            <h1>Gestión de Pedidos</h1>
            <p>Consulta, crea y edita pedidos registrados en la plataforma.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('admin.pedidos.create') }}">Nuevo pedido</a>
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
                        <th>Usuario</th>
                        <th>Total</th>
                        <th>Descuento</th>
                        <th>Final</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pedidos as $pedido)
                        <tr>
                            <td data-label="#">{{ $pedido->id }}</td>
                            <td data-label="Usuario">{{ $pedido->usuario?->nombre }} {{ $pedido->usuario?->apellido1 }}</td>
                            <td data-label="Total">{{ number_format($pedido->total, 2) }} €</td>
                            <td data-label="Descuento">{{ number_format($pedido->total_descuento, 2) }} €</td>
                            <td data-label="Final">{{ number_format($pedido->total_final, 2) }} €</td>
                            <td data-label="Estado">
                                <span class="estado {{ (int) $pedido->estado === 1 ? 'activo' : 'inactivo' }}">
                                    {{ (int) $pedido->estado === 1 ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td data-label="Fecha">{{ \Carbon\Carbon::parse($pedido->fecha_creacion)->format('d/m/Y H:i') }}</td>
                            <td data-label="Acciones" class="actions-cell">
                                <a class="btn btn-secondary" href="{{ route('admin.pedidos.edit', $pedido) }}">Editar</a>
                                <form method="POST" action="{{ route('admin.pedidos.destroy', $pedido) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar el pedido #{{ $pedido->id }}?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="empty">No hay pedidos registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @if ($pedidos->hasPages())
        <nav class="paginacion" aria-label="Paginacion de pedidos">
            <div class="pagination-summary">
                Mostrando <strong>{{ $pedidos->firstItem() }}</strong> a <strong>{{ $pedidos->lastItem() }}</strong> de <strong>{{ $pedidos->total() }}</strong> resultados
            </div>
            <div class="pagination-controls">
                @if ($pedidos->onFirstPage())
                    <span class="pagination-arrow disabled" aria-hidden="true">‹</span>
                @else
                    <a class="pagination-arrow" href="{{ $pedidos->previousPageUrl() }}" rel="prev">‹</a>
                @endif
                @if ($pedidos->hasMorePages())
                    <a class="pagination-arrow" href="{{ $pedidos->nextPageUrl() }}" rel="next">›</a>
                @else
                    <span class="pagination-arrow disabled" aria-hidden="true">›</span>
                @endif
            </div>
        </nav>
    @endif
@endsection
@extends('admin.layouts.dashboard')

@section('title', 'Admin | Pedidos')

@section('content')
    <header class="admin-header">
        <div>
            <h1>Gestión de Pedidos</h1>
            <p>Listado informativo de pedidos realizados en la plataforma.</p>
        </div>
    </header>

    @if (session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    <section class="card">
        <table class="tabla-eventos">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Total</th>
                    <th>Descuento</th>
                    <th>Total final</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pedidos as $pedido)
                    <tr>
                        <td data-label="ID">{{ $pedido->id }}</td>
                        <td data-label="Usuario">
                            {{ $pedido->usuario->nombre ?? 'Sin usuario' }}
                            @if($pedido->usuario?->email)
                                <small style="display:block;opacity:.7">{{ $pedido->usuario->email }}</small>
                            @endif
                        </td>
                        <td data-label="Total">{{ number_format((float) $pedido->total, 2, ',', '.') }} €</td>
                        <td data-label="Descuento">{{ number_format((float) $pedido->total_descuento, 2, ',', '.') }} €</td>
                        <td data-label="Total final">{{ number_format((float) $pedido->total_final, 2, ',', '.') }} €</td>
                        <td data-label="Estado">
                            <span class="estado {{ (int) $pedido->estado === 1 ? 'activo' : 'inactivo' }}">
                                {{ (int) $pedido->estado === 1 ? 'Completado' : 'Cancelado' }}
                            </span>
                        </td>
                        <td data-label="Fecha">{{ optional($pedido->fecha_creacion)->format('d/m/Y H:i') ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty">No hay pedidos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>

    @if ($pedidos->hasPages())
        <nav class="paginacion" aria-label="Paginacion de pedidos">
            <div class="pagination-summary">
                Mostrando <strong>{{ $pedidos->firstItem() }}</strong>
                a <strong>{{ $pedidos->lastItem() }}</strong>
                de <strong>{{ $pedidos->total() }}</strong> resultados
            </div>

            <div class="pagination-controls">
                @if ($pedidos->onFirstPage())
                    <span class="pagination-arrow disabled" aria-hidden="true">‹</span>
                @else
                    <a class="pagination-arrow" href="{{ $pedidos->previousPageUrl() }}" rel="prev" aria-label="Pagina anterior">‹</a>
                @endif

                @for ($page = 1; $page <= $pedidos->lastPage(); $page++)
                    @if ($page === $pedidos->currentPage())
                        <span class="pagination-page active" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="pagination-page" href="{{ $pedidos->url($page) }}" aria-label="Ir a la pagina {{ $page }}">{{ $page }}</a>
                    @endif
                @endfor

                @if ($pedidos->hasMorePages())
                    <a class="pagination-arrow" href="{{ $pedidos->nextPageUrl() }}" rel="next" aria-label="Pagina siguiente">›</a>
                @else
                    <span class="pagination-arrow disabled" aria-hidden="true">›</span>
                @endif
            </div>
        </nav>
    @endif
@endsection

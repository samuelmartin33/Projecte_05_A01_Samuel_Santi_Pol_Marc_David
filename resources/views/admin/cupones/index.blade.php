@extends('admin.layouts.dashboard')

@section('title', 'Admin | Cupones')

@section('content')
    <header class="admin-header">
        <div>
            <h1>Gestión de Cupones</h1>
            <p>Crea, edita y gestiona los cupones de descuento de la plataforma.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('admin.cupones.create') }}">Nuevo cupón</a>
    </header>

    @if (session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    <section class="card">
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Código</th>
                        <th>Descuento</th>
                        <th>Descripción</th>
                        <th>Eventos</th>
                        <th>Usos</th>
                        <th>Validez</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($cupones as $cupon)
                    <tr>
                        <td>{{ $cupon->id }}</td>
                        <td>
                            <code style="font-family:monospace;font-size:0.95rem;font-weight:700;
                                         background:rgba(168,85,247,0.12);padding:3px 8px;border-radius:6px;
                                         color:var(--morado-2);letter-spacing:0.08em;">
                                {{ $cupon->codigo }}
                            </code>
                        </td>
                        <td style="font-weight:800;font-size:1.1rem;color:var(--morado-2);">
                            @if($cupon->valor_descuento == 0)
                                <span style="color:#4ade80">GRATIS</span>
                            @else
                                {{ number_format($cupon->valor_descuento, 0) }}%
                            @endif
                        </td>
                        <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ $cupon->descripcion ?? '—' }}
                        </td>
                        <td>
                            @if($cupon->eventos->isEmpty())
                                <span style="color:var(--morado-2);font-size:0.8rem;">Global</span>
                            @else
                                <span style="color:rgba(245,241,234,0.6);font-size:0.8rem;">
                                    {{ $cupon->eventos->count() }} evento(s)
                                </span>
                            @endif
                        </td>
                        <td style="font-size:0.85rem;">
                            {{ $cupon->usos_actuales }}
                            @if($cupon->limite_usos_total)
                                / {{ $cupon->limite_usos_total }}
                            @else
                                / ∞
                            @endif
                        </td>
                        <td style="font-size:0.8rem;white-space:nowrap;">
                            {{ optional($cupon->fecha_inicio)->format('d/m/Y') }}
                            →
                            {{ optional($cupon->fecha_fin)->format('d/m/Y') }}
                        </td>
                        <td>
                            @if(!$cupon->estado)
                                <span class="estado inactivo">Inactivo</span>
                            @elseif($cupon->expirado)
                                <span class="estado inactivo">Expirado</span>
                            @elseif($cupon->agotado)
                                <span class="estado inactivo">Agotado</span>
                            @else
                                <span class="estado activo">Activo</span>
                            @endif
                        </td>
                        <td class="acciones">
                            <a class="btn btn-secondary"
                               href="{{ route('admin.cupones.edit', $cupon->id) }}">Editar</a>
                            <form method="POST"
                                  action="{{ route('admin.cupones.destroy', $cupon->id) }}"
                                  class="delete-form"
                                  data-confirm-msg="¿Eliminar el cupón {{ $cupon->codigo }}?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="empty">No hay cupones registrados.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- Paginación --}}
    @if($cupones->hasPages())
        <nav class="paginacion" aria-label="Paginación de cupones">
            <div class="pagination-summary">
                Mostrando <strong>{{ $cupones->firstItem() }}</strong>
                a <strong>{{ $cupones->lastItem() }}</strong>
                de <strong>{{ $cupones->total() }}</strong> cupones
            </div>
            <div class="pagination-controls">
                @if($cupones->onFirstPage())
                    <span class="pagination-arrow disabled">‹</span>
                @else
                    <a class="pagination-arrow" href="{{ $cupones->previousPageUrl() }}">‹</a>
                @endif
                @for($page = 1; $page <= $cupones->lastPage(); $page++)
                    @if($page === $cupones->currentPage())
                        <span class="pagination-page active">{{ $page }}</span>
                    @else
                        <a class="pagination-page" href="{{ $cupones->url($page) }}">{{ $page }}</a>
                    @endif
                @endfor
                @if($cupones->hasMorePages())
                    <a class="pagination-arrow" href="{{ $cupones->nextPageUrl() }}">›</a>
                @else
                    <span class="pagination-arrow disabled">›</span>
                @endif
            </div>
        </nav>
    @endif
@endsection

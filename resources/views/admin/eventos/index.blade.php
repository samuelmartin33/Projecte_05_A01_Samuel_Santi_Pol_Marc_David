@extends('admin.layouts.dashboard')

@section('title', 'Admin | Eventos')

@section('content')
    <header class="admin-header">
        <div>
            <h1>Gestor de Eventos</h1>
            <p>Panel de administracion para crear, editar y eliminar eventos.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('admin.eventos.create') }}">Nuevo evento</a>
    </header>

    @if (session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    {{-- Buscador --}}
    <form method="GET" action="{{ route('admin.eventos.index') }}" style="margin-bottom:1.25rem;display:flex;gap:.75rem;align-items:center;flex-wrap:wrap;">
        <input type="text"
               name="busqueda"
               value="{{ $busqueda }}"
               placeholder="Buscar por nombre de evento o promotora…"
               style="flex:1;min-width:220px;padding:9px 14px;background:#0d0a18;border:1px solid rgba(245,241,234,0.15);color:#f5f1ea;font-family:'Archivo Narrow',sans-serif;font-size:14px;outline:none;">
        <button type="submit"
                style="padding:9px 20px;background:#a855f7;color:#fff;border:none;cursor:pointer;font-family:'Archivo Narrow',sans-serif;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;white-space:nowrap;">
            Buscar
        </button>
        @if($busqueda)
            <a href="{{ route('admin.eventos.index') }}"
               style="padding:9px 16px;background:rgba(245,241,234,0.07);color:rgba(245,241,234,0.6);border:1px solid rgba(245,241,234,0.12);font-family:'Archivo Narrow',sans-serif;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;text-decoration:none;white-space:nowrap;">
                Limpiar
            </a>
        @endif
    </form>

    <section class="card">
        <table class="tabla-eventos">
            <thead>
            <tr>
                <th>ID</th>
                <th>Titulo</th>
                <th>Categoria</th>
                <th>Organizador</th>
                <th>Inicio</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($eventos as $evento)
                <tr>
                    <td data-label="ID">{{ $evento->id }}</td>
                    <td data-label="Titulo">{{ $evento->titulo }}</td>
                    <td data-label="Categoria">{{ $evento->categoriaEvento->nombre ?? 'Sin categoria' }}</td>
                    <td data-label="Organizador">{{ $evento->organizador?->empresa?->nombre_empresa ?? '#' . $evento->organizador_id }}</td>
                    <td data-label="Inicio">{{ optional($evento->fecha_inicio)->format('d/m/Y H:i') }}</td>
                    <td data-label="Estado">
                        <span class="estado {{ $evento->estado ? 'activo' : 'inactivo' }}">
                            {{ $evento->estado ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td data-label="Acciones" class="acciones">
                        <a class="btn btn-secondary" href="{{ route('admin.eventos.edit', $evento) }}">Editar</a>
                        <form method="POST" action="{{ route('admin.eventos.destroy', $evento) }}" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty">No hay eventos registrados.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </section>

    @if ($eventos->hasPages())
        <nav class="paginacion" aria-label="Paginacion de eventos">
            <div class="pagination-summary">
                Mostrando <strong>{{ $eventos->firstItem() }}</strong>
                a <strong>{{ $eventos->lastItem() }}</strong>
                de <strong>{{ $eventos->total() }}</strong> resultados
            </div>

            <div class="pagination-controls">
                @if ($eventos->onFirstPage())
                    <span class="pagination-arrow disabled" aria-hidden="true">‹</span>
                @else
                    <a class="pagination-arrow" href="{{ $eventos->previousPageUrl() }}" rel="prev" aria-label="Pagina anterior">‹</a>
                @endif

                @for ($page = 1; $page <= $eventos->lastPage(); $page++)
                    @if ($page === $eventos->currentPage())
                        <span class="pagination-page active" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="pagination-page" href="{{ $eventos->url($page) }}" aria-label="Ir a la pagina {{ $page }}">{{ $page }}</a>
                    @endif
                @endfor

                @if ($eventos->hasMorePages())
                    <a class="pagination-arrow" href="{{ $eventos->nextPageUrl() }}" rel="next" aria-label="Pagina siguiente">›</a>
                @else
                    <span class="pagination-arrow disabled" aria-hidden="true">›</span>
                @endif
            </div>
        </nav>
    @endif
@endsection


@extends('admin.layouts.dashboard')

@section('title', 'Admin | Usuarios')

@section('content')
    <header class="admin-header">
        <div>
            <h1>Gestión de Usuarios</h1>
            <p>Administra las cuentas registradas en la plataforma.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('admin.usuarios.create') }}">Nuevo usuario</a>
    </header>

    @if (session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert error">{{ session('error') }}</div>
    @endif

    <section class="card">
        <table class="tabla-eventos">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Cuenta</th>
                <th>Registro</th>
                <th>Admin</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($usuarios as $usuario)
                <tr>
                    <td data-label="ID">{{ $usuario->id }}</td>
                    <td data-label="Nombre">
                        {{ $usuario->nombre }} {{ $usuario->apellido1 }} {{ $usuario->apellido2 }}
                    </td>
                    <td data-label="Email">{{ $usuario->email }}</td>
                    <td data-label="Cuenta">{{ ucfirst($usuario->tipo_cuenta ?? 'cliente') }}</td>
                    <td data-label="Registro">{{ ucfirst($usuario->estado_registro ?? 'aprobado') }}</td>
                    <td data-label="Admin">
                        <span class="estado {{ $usuario->es_admin ? 'activo' : 'inactivo' }}">
                            {{ $usuario->es_admin ? 'Sí' : 'No' }}
                        </span>
                    </td>
                    <td data-label="Estado">
                        <span class="estado {{ (int) $usuario->estado === 1 ? 'activo' : 'inactivo' }}">
                            {{ (int) $usuario->estado === 1 ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td data-label="Acciones" class="acciones">
                        <a class="btn btn-secondary" href="{{ route('admin.usuarios.edit', $usuario) }}">Editar</a>
                        <form method="POST" action="{{ route('admin.usuarios.destroy', $usuario) }}" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar a {{ addslashes($usuario->nombre.' '.$usuario->apellido1) }}?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="empty">No hay usuarios registrados.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </section>

    @if ($usuarios->hasPages())
        <nav class="paginacion" aria-label="Paginacion de usuarios">
            <div class="pagination-summary">
                Mostrando <strong>{{ $usuarios->firstItem() }}</strong>
                a <strong>{{ $usuarios->lastItem() }}</strong>
                de <strong>{{ $usuarios->total() }}</strong> resultados
            </div>

            <div class="pagination-controls">
                @if ($usuarios->onFirstPage())
                    <span class="pagination-arrow disabled" aria-hidden="true">‹</span>
                @else
                    <a class="pagination-arrow" href="{{ $usuarios->previousPageUrl() }}" rel="prev" aria-label="Pagina anterior">‹</a>
                @endif

                @for ($page = 1; $page <= $usuarios->lastPage(); $page++)
                    @if ($page === $usuarios->currentPage())
                        <span class="pagination-page active" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="pagination-page" href="{{ $usuarios->url($page) }}" aria-label="Ir a la pagina {{ $page }}">{{ $page }}</a>
                    @endif
                @endfor

                @if ($usuarios->hasMorePages())
                    <a class="pagination-arrow" href="{{ $usuarios->nextPageUrl() }}" rel="next" aria-label="Pagina siguiente">›</a>
                @else
                    <span class="pagination-arrow disabled" aria-hidden="true">›</span>
                @endif
            </div>
        </nav>
    @endif
@endsection
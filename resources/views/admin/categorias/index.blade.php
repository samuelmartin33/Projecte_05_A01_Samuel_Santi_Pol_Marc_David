@extends('admin.layouts.dashboard')

@section('title', 'Admin | Categorías')

@section('content')
    <header class="admin-header">
        <div>
            <h1>Gestor de Categorías</h1>
            <p>Administra las categorías y etiquetas de los eventos.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('admin.categorias.create') }}">Nueva categoría</a>
    </header>

    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    <section class="card">
        <table class="tabla-eventos">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categorias as $cat)
                    <tr>
                        <td data-label="ID">{{ $cat->id }}</td>
                        <td data-label="Nombre">{{ $cat->nombre }}</td>
                        <td data-label="Descripción">{{ Str::limit($cat->descripcion, 80) }}</td>
                        <td data-label="Estado"><span class="estado {{ $cat->estado ? 'activo' : 'inactivo' }}">{{ $cat->estado ? 'Activo' : 'Inactivo' }}</span></td>
                        <td data-label="Acciones" class="acciones">
                            <a class="btn btn-secondary" href="{{ route('admin.categorias.edit', $cat) }}">Editar</a>
                            <form method="POST" action="{{ route('admin.categorias.destroy', $cat) }}" class="delete-form" style="display:inline-block" data-confirm-msg="¿Eliminar esta categoría?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty">No hay categorías registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
@endsection

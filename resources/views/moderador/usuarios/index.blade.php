@extends('moderador.layouts.dashboard')

@section('title', 'Moderador | Usuarios')

@section('content')
<header class="admin-header">
    <div>
        <h1>Usuarios</h1>
        <p>Banea o desbanea usuarios de la plataforma. No puedes banear administradores.</p>
    </div>
    <a class="btn btn-secondary" href="{{ route('moderador.dashboard') }}">← Volver</a>
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
            <th>Moderador</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($usuarios as $usuario)
            <tr>
                <td data-label="ID">{{ $usuario->id }}</td>
                <td data-label="Nombre">{{ $usuario->nombre }} {{ $usuario->apellido1 }}</td>
                <td data-label="Email">{{ $usuario->email }}</td>
                <td data-label="Cuenta">{{ ucfirst($usuario->tipo_cuenta ?? 'cliente') }}</td>
                <td data-label="Moderador">
                    <span class="estado {{ $usuario->es_moderador ? 'activo' : 'inactivo' }}">
                        {{ $usuario->es_moderador ? 'Sí' : 'No' }}
                    </span>
                </td>
                <td data-label="Estado">
                    <span class="estado {{ (int) $usuario->estado === 1 ? 'activo' : 'inactivo' }}">
                        {{ (int) $usuario->estado === 1 ? 'Activo' : 'Baneado' }}
                    </span>
                </td>
                <td data-label="Acciones" class="acciones">
                    @if((int) $usuario->estado === 1)
                        <form method="POST"
                              action="{{ route('moderador.usuarios.banear', $usuario) }}"
                              class="delete-form"
                              data-confirm-msg="¿Banear a {{ addslashes($usuario->nombre.' '.$usuario->apellido1) }}? No podrá acceder a su cuenta.">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger">Banear</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('moderador.usuarios.desbanear', $usuario) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-info">Desbanear</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="empty">No hay usuarios registrados.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</section>

<div style="margin-top:1rem">
    {{ $usuarios->links() }}
</div>

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.delete-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            var msg = form.getAttribute('data-confirm-msg') || '¿Confirmar acción?';
            Swal.fire({
                title: msg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, banear',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#ef4444',
            }).then(function(result) {
                if (result.isConfirmed) { form.submit(); }
            });
        });
    });
</script>
@endpush

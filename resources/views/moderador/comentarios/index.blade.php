@extends('moderador.layouts.dashboard')

@section('title', 'Moderador | Comentarios')

@section('content')
<header class="admin-header">
    <div>
        <h1>Comentarios</h1>
        <p>Revisa y elimina comentarios inapropiados del social.</p>
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
            <th>Usuario</th>
            <th>Contenido</th>
            <th>Post ID</th>
            <th>Respuesta</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($comentarios as $comentario)
            <tr>
                <td data-label="ID">{{ $comentario->id }}</td>
                <td data-label="Usuario">
                    {{ $comentario->usuario->nombre ?? '—' }} {{ $comentario->usuario->apellido1 ?? '' }}
                </td>
                <td data-label="Contenido">
                    {{ \Illuminate\Support\Str::limit($comentario->contenido, 80) }}
                </td>
                <td data-label="Post ID">{{ $comentario->evento_post_id }}</td>
                <td data-label="Respuesta">
                    <span class="estado {{ $comentario->padre_id ? 'activo' : 'inactivo' }}">
                        {{ $comentario->padre_id ? 'Sí' : 'No' }}
                    </span>
                </td>
                <td data-label="Fecha">{{ $comentario->fecha_creacion }}</td>
                <td data-label="Estado">
                    <span class="estado {{ (int) $comentario->estado === 1 ? 'activo' : 'inactivo' }}">
                        {{ (int) $comentario->estado === 1 ? 'Visible' : 'Eliminado' }}
                    </span>
                </td>
                <td data-label="Acciones" class="acciones">
                    @if((int) $comentario->estado === 1)
                    <form method="POST"
                          action="{{ route('moderador.comentarios.destroy', $comentario) }}"
                          class="delete-form"
                          data-confirm-msg="¿Eliminar el comentario de {{ addslashes($comentario->usuario->nombre ?? 'este usuario') }}?">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                    @else
                        <span style="color:var(--adm-ink-dim);font-size:.85rem">Ya eliminado</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="empty">No hay comentarios registrados.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</section>

<div style="margin-top:1rem">
    {{ $comentarios->links() }}
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/moderador-confirmar.js') }}"></script>
{{-- JS en public/js/moderador-confirmar.js --}}
@endpush

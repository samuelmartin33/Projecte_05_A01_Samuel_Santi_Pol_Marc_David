@extends('moderador.layouts.dashboard')

@section('title', 'Moderador | Publicaciones')

@section('content')
<header class="admin-header">
    <div>
        <h1>Publicaciones</h1>
        <p>Revisa y elimina publicaciones inapropiadas del social.</p>
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
            <th>Descripción</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($posts as $post)
            <tr>
                <td data-label="ID">{{ $post->id }}</td>
                <td data-label="Usuario">
                    {{ $post->usuario->nombre ?? '—' }} {{ $post->usuario->apellido1 ?? '' }}
                </td>
                <td data-label="Descripción">
                    {{ \Illuminate\Support\Str::limit($post->descripcion ?? '(sin texto)', 80) }}
                </td>
                <td data-label="Fecha">{{ $post->fecha_creacion }}</td>
                <td data-label="Estado">
                    <span class="estado {{ (int) $post->estado === 1 ? 'activo' : 'inactivo' }}">
                        {{ (int) $post->estado === 1 ? 'Visible' : 'Eliminado' }}
                    </span>
                </td>
                <td data-label="Acciones" class="acciones">
                    @if((int) $post->estado === 1)
                    <form method="POST"
                          action="{{ route('moderador.posts.destroy', $post) }}"
                          class="delete-form"
                          onsubmit="return confirmarBorrar(event, this)"
                          data-confirm-msg="¿Eliminar la publicación de {{ addslashes($post->usuario->nombre ?? 'este usuario') }}?">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                    @else
                        <span style="color:var(--adm-ink-dim);font-size:.85rem">Ya eliminada</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="empty">No hay publicaciones registradas.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</section>

<div style="margin-top:1rem">
    {{ $posts->links() }}
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/moderador-confirmar.js') }}"></script>
{{-- JS en public/js/moderador-confirmar.js --}}
@endpush

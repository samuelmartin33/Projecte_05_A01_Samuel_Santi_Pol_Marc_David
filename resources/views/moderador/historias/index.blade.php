@extends('moderador.layouts.dashboard')

@section('title', 'Moderador | Historias')

@section('content')
<header class="admin-header">
    <div>
        <h1>Historias</h1>
        <p>Revisa y elimina historias inapropiadas del social.</p>
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
            <th>Texto</th>
            <th>Expira</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($historias as $historia)
            <tr>
                <td data-label="ID">{{ $historia->id }}</td>
                <td data-label="Usuario">
                    {{ $historia->usuario->nombre ?? '—' }} {{ $historia->usuario->apellido1 ?? '' }}
                </td>
                <td data-label="Texto">
                    {{ \Illuminate\Support\Str::limit($historia->texto ?? '(solo media)', 80) }}
                </td>
                <td data-label="Expira">
                    @if($historia->expira_en)
                        <span style="color: {{ \Carbon\Carbon::parse($historia->expira_en)->isPast() ? 'var(--adm-ink-dim)' : 'inherit' }}">
                            {{ $historia->expira_en }}
                        </span>
                    @else
                        —
                    @endif
                </td>
                <td data-label="Estado">
                    <span class="estado {{ (int) $historia->estado === 1 ? 'activo' : 'inactivo' }}">
                        {{ (int) $historia->estado === 1 ? 'Visible' : 'Eliminada' }}
                    </span>
                </td>
                <td data-label="Acciones" class="acciones">
                    @if((int) $historia->estado === 1)
                    <form method="POST"
                          action="{{ route('moderador.historias.destroy', $historia) }}"
                          class="delete-form"
                          onsubmit="return confirmarBorrar(event, this)"
                          data-confirm-msg="¿Eliminar la historia de {{ addslashes($historia->usuario->nombre ?? 'este usuario') }}?">
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
                <td colspan="6" class="empty">No hay historias registradas.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</section>

<div style="margin-top:1rem">
    {{ $historias->links() }}
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/moderador-confirmar.js') }}"></script>
{{-- JS en public/js/moderador-confirmar.js --}}
@endpush

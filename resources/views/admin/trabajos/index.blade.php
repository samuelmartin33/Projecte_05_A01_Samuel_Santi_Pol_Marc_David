@extends('admin.layouts.dashboard')

@section('title', 'Puestos de trabajo — Admin VIBEZ')

@section('content')

{{-- Formulario oculto para la edición (lo rellena el SweetAlert) --}}
<form id="form-editar" method="POST" style="display:none">
    @csrf
    @method('PATCH')
    <input type="hidden" name="nombre"      id="edit-nombre">
    <input type="hidden" name="descripcion" id="edit-descripcion">
</form>

{{-- Cabecera --}}
<header class="admin-header">
    <div>
        <h1>Puestos de trabajo</h1>
        <p>Gestiona los puestos disponibles · Aparecen en candidaturas y ofertas</p>
    </div>
    <button class="btn btn-primary" onclick="toggleFormulario()">+ Nuevo puesto</button>
</header>

{{-- Panel de creación (oculto por defecto, se abre con el botón) --}}
<section class="card" id="tr-form-panel"
         style="{{ $errors->any() ? 'display:block' : 'display:none' }};margin-bottom:1.5rem;">
    <form action="{{ route('admin.trabajos.store') }}" method="POST">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr auto;gap:1rem;align-items:end;flex-wrap:wrap;">
            <div class="form-grupo" style="margin-bottom:0;">
                <label class="form-label">Nombre <span style="color:#f87171">*</span></label>
                <input type="text" name="nombre" class="form-input"
                       value="{{ old('nombre') }}"
                       placeholder="Camarero, Portero, Barman…"
                       maxlength="100" required>
            </div>
            <div class="form-grupo" style="margin-bottom:0;">
                <label class="form-label">Descripción (opcional)</label>
                <input type="text" name="descripcion" class="form-input"
                       value="{{ old('descripcion') }}"
                       placeholder="Breve descripción del puesto"
                       maxlength="500">
            </div>
            <div style="display:flex;gap:0.5rem;padding-bottom:2px;">
                <button type="submit" class="btn btn-primary">Crear</button>
                <button type="button" class="btn btn-secondary" onclick="toggleFormulario()">Cancelar</button>
            </div>
        </div>
    </form>
</section>

@if(session('success'))
    <div class="alert success">{{ session('success') }}</div>
@endif

{{-- Tabla de puestos --}}
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
            @forelse($trabajos as $trabajo)
                <tr>
                    <td data-label="ID">{{ $trabajo->id }}</td>
                    <td data-label="Nombre">{{ $trabajo->nombre }}</td>
                    <td data-label="Descripción">{{ $trabajo->descripcion ? Str::limit($trabajo->descripcion, 80) : '—' }}</td>
                    <td data-label="Estado">
                        <span class="estado {{ $trabajo->estado ? 'activo' : 'inactivo' }}">
                            {{ $trabajo->estado ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td data-label="Acciones" class="acciones">
                        <form method="POST" action="{{ route('admin.trabajos.estado', $trabajo) }}" style="display:inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-secondary">
                                {{ $trabajo->estado ? 'Pausar' : 'Activar' }}
                            </button>
                        </form>
                        <button type="button" class="btn btn-secondary"
                                onclick="editarPuesto(
                                    {{ $trabajo->id }},
                                    '{{ addslashes($trabajo->nombre) }}',
                                    '{{ addslashes($trabajo->descripcion ?? '') }}',
                                    '{{ route('admin.trabajos.update', $trabajo) }}'
                                )">
                            Editar
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty">No hay puestos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</section>

@endsection

@push('scripts')
<script src="{{ asset('js/admin-trabajos.js') }}"></script>
@endpush

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
<script>
/* Muestra u oculta el panel de creación */
function toggleFormulario() {
    var panel = document.getElementById('tr-form-panel');
    panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
}

/* Abre SweetAlert2 para editar un puesto */
function editarPuesto(id, nombre, descripcion, url) {
    Swal.fire({
        title: 'Editar puesto',
        background: '#0d0a18',
        color: '#f5f1ea',
        showCancelButton: true,
        confirmButtonColor: '#a855f7',
        cancelButtonColor: 'rgba(245,241,234,0.10)',
        confirmButtonText: 'Guardar cambios',
        cancelButtonText: 'Cancelar',
        html:
            '<div style="text-align:left;margin-top:0.5rem;">' +
            '  <label style="font-family:\'Archivo Narrow\',sans-serif;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.14em;color:rgba(245,241,234,0.45);display:block;margin-bottom:5px;">Nombre *</label>' +
            '  <input id="swal-nombre" class="swal2-input" style="background:rgba(245,241,234,0.05);border:1px solid rgba(168,85,247,0.35);color:#f5f1ea;font-family:\'Archivo Narrow\',sans-serif;font-size:14px;border-radius:0;box-shadow:none;height:42px;" ' +
            '    placeholder="Nombre del puesto" maxlength="100" value="' + nombre + '">' +
            '  <label style="font-family:\'Archivo Narrow\',sans-serif;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.14em;color:rgba(245,241,234,0.45);display:block;margin:14px 0 5px;">Descripción (opcional)</label>' +
            '  <input id="swal-desc" class="swal2-input" style="background:rgba(245,241,234,0.05);border:1px solid rgba(245,241,234,0.12);color:#f5f1ea;font-family:\'Archivo Narrow\',sans-serif;font-size:14px;border-radius:0;box-shadow:none;height:42px;" ' +
            '    placeholder="Breve descripción del puesto" maxlength="500" value="' + descripcion + '">' +
            '</div>',
        customClass: { popup: 'swal2-popup', confirmButton: 'swal2-confirm', cancelButton: 'swal2-cancel' },
        didOpen: function() { document.getElementById('swal-nombre').focus(); },
        preConfirm: function() {
            var n = document.getElementById('swal-nombre').value.trim();
            if (!n) { Swal.showValidationMessage('El nombre del puesto es obligatorio.'); return false; }
            return { nombre: n, descripcion: document.getElementById('swal-desc').value.trim() };
        }
    }).then(function(result) {
        if (result.isConfirmed) {
            var form = document.getElementById('form-editar');
            form.action = url;
            document.getElementById('edit-nombre').value      = result.value.nombre;
            document.getElementById('edit-descripcion').value = result.value.descripcion;
            form.submit();
        }
    });
}
</script>
@endpush

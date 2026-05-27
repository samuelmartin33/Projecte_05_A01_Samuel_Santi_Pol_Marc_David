@extends('admin.layouts.dashboard')

@section('title', 'Puestos de trabajo — Admin VIBEZ')

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/admin-trabajos.css') }}">
@endpush

@section('content')

{{-- Formulario oculto para la edición (lo rellena el SweetAlert) --}}
<form id="form-editar" method="POST" style="display:none">
    @csrf
    @method('PATCH')
    <input type="hidden" name="nombre"      id="edit-nombre">
    <input type="hidden" name="descripcion" id="edit-descripcion">
</form>

{{-- ── Cabecera ── --}}
<div class="tr-header">
    <div>
        <h1 class="tr-titulo">Puestos de trabajo</h1>
        <p class="tr-subtitulo">Gestiona los puestos disponibles · Aparecen en candidaturas y ofertas</p>
    </div>
    <button class="tr-btn-nuevo" onclick="toggleFormulario()">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo puesto
    </button>
</div>

{{-- ── Panel formulario de creación ── --}}
<div class="tr-form-panel {{ $errors->any() ? 'open' : '' }}" id="tr-form-panel">
    <p class="tr-form-titulo">
        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Crear nuevo puesto
    </p>
    <form action="{{ route('admin.trabajos.store') }}" method="POST">
        @csrf
        <div class="tr-form-grid">
            <div class="tr-form-campo">
                <label class="tr-form-label" for="nombre">Nombre <span style="color:#f87171">*</span></label>
                <input type="text" id="nombre" name="nombre" class="tr-form-input"
                       value="{{ old('nombre') }}"
                       placeholder="Camarero, Portero, Barman…"
                       maxlength="100" required>
            </div>
            <div class="tr-form-campo">
                <label class="tr-form-label" for="descripcion">Descripción (opcional)</label>
                <input type="text" id="descripcion" name="descripcion" class="tr-form-input"
                       value="{{ old('descripcion') }}"
                       placeholder="Breve descripción del puesto"
                       maxlength="500">
            </div>
            <div class="tr-form-actions">
                <button type="submit" class="tr-btn-crear">Crear</button>
                <button type="button" class="tr-btn-cancelar" onclick="toggleFormulario()">Cancelar</button>
            </div>
        </div>
    </form>
</div>

{{-- ── Tabla de puestos ── --}}
<div class="tr-tabla-wrap">
    @if($trabajos->isEmpty())
        <p class="tr-empty">Aún no hay puestos creados. Usa el botón "Nuevo puesto" para añadir el primero.</p>
    @else
        <div class="tr-tabla-head">
            <span class="tr-th">ID</span>
            <span class="tr-th">Nombre</span>
            <span class="tr-th">Descripción</span>
            <span class="tr-th">Estado</span>
            <span class="tr-th">Acciones</span>
        </div>

        @foreach($trabajos as $trabajo)
        <div class="tr-fila">
            <span class="tr-id">#{{ $trabajo->id }}</span>

            <span class="tr-nombre">{{ $trabajo->nombre }}</span>

            <span class="tr-desc">{{ $trabajo->descripcion ? Str::limit($trabajo->descripcion, 80) : '—' }}</span>

            {{-- Badge + acciones agrupados para la vista card en móvil --}}
            <div class="tr-acciones-row">
                <span class="tr-badge {{ $trabajo->estado ? 'tr-badge-activo' : 'tr-badge-inactivo' }}">
                    <span style="width:5px;height:5px;border-radius:50%;background:currentColor;"></span>
                    {{ $trabajo->estado ? 'Activo' : 'Inactivo' }}
                </span>

                <div class="tr-acciones">
                    {{-- Toggle estado --}}
                    <form method="POST" action="{{ route('admin.trabajos.estado', $trabajo) }}" style="display:inline">
                        @csrf @method('PATCH')
                        <button type="submit" class="tr-accion tr-accion-toggle"
                                title="{{ $trabajo->estado ? 'Desactivar' : 'Activar' }}">
                            @if($trabajo->estado)
                                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                                Pausar
                            @else
                                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Activar
                            @endif
                        </button>
                    </form>

                    {{-- Editar con SweetAlert --}}
                    <button type="button" class="tr-accion tr-accion-editar"
                            onclick="editarPuesto(
                                {{ $trabajo->id }},
                                '{{ addslashes($trabajo->nombre) }}',
                                '{{ addslashes($trabajo->descripcion ?? '') }}',
                                '{{ route('admin.trabajos.update', $trabajo) }}'
                            )">
                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>

@endsection

@push('scripts')
<script>
/* Muestra u oculta el panel de creación */
function toggleFormulario() {
    var panel = document.getElementById('tr-form-panel');
    panel.classList.toggle('open');
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
        customClass: {
            popup:         'swal2-popup',
            confirmButton: 'swal2-confirm',
            cancelButton:  'swal2-cancel',
        },
        didOpen: function() {
            /* Enfocar el campo nombre al abrir */
            document.getElementById('swal-nombre').focus();
        },
        preConfirm: function() {
            var n = document.getElementById('swal-nombre').value.trim();
            if (!n) {
                Swal.showValidationMessage('El nombre del puesto es obligatorio.');
                return false;
            }
            return {
                nombre:      n,
                descripcion: document.getElementById('swal-desc').value.trim()
            };
        }
    }).then(function(result) {
        if (result.isConfirmed) {
            /* Rellenar el formulario oculto y enviarlo */
            var form = document.getElementById('form-editar');
            form.action = url;
            document.getElementById('edit-nombre').value      = result.value.nombre;
            document.getElementById('edit-descripcion').value = result.value.descripcion;
            form.submit();
        }
    });
}

/* ── SweetAlert2: flash de sesión ── */
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Listo!',
            text: '{{ session('success') }}',
            background: '#0d0a18',
            color: '#f5f1ea',
            confirmButtonColor: '#a855f7',
            timer: 3500,
            timerProgressBar: true,
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'warning',
            title: 'Revisa los campos',
            html: '@foreach($errors->all() as $e)• {{ $e }}<br>@endforeach',
            background: '#0d0a18',
            color: '#f5f1ea',
            confirmButtonColor: '#a855f7',
        });
    @endif
});
</script>
@endpush

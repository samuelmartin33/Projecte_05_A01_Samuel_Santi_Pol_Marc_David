@extends('admin.layouts.dashboard')

@section('title', 'Puestos de trabajo — Admin VIBEZ')

@push('estilos')
<style>
/* ══ Variables locales ══════════════════════════════════════════ */
:root {
    --tr-bg:      #0d0a18;
    --tr-border:  rgba(245,241,234,0.08);
    --tr-hover:   rgba(168,85,247,0.06);
    --purple:     #a855f7;
    --purple-dim: rgba(168,85,247,0.18);
    --purple-bd:  rgba(168,85,247,0.35);
    --ink:        #f5f1ea;
    --ink-dim:    rgba(245,241,234,0.45);
    --ink-faint:  rgba(245,241,234,0.18);
}

/* ── Cabecera de sección ── */
.tr-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.75rem;
    flex-wrap: wrap;
}
.tr-titulo {
    font-family: 'Anton', sans-serif;
    font-size: 1.75rem;
    color: var(--ink);
    text-transform: uppercase;
    letter-spacing: -0.01em;
    line-height: 1;
    margin: 0 0 4px;
}
.tr-subtitulo {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.14em;
    color: var(--ink-dim);
}
.tr-btn-nuevo {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    padding: 10px 20px;
    background: var(--purple);
    color: #fff;
    border: none;
    cursor: pointer;
    transition: background 0.15s;
    white-space: nowrap;
}
.tr-btn-nuevo:hover { background: #c084fc; color: #07060c; }

/* ── Panel formulario nuevo puesto ── */
.tr-form-panel {
    background: var(--tr-bg);
    border: 1px solid var(--purple-bd);
    border-top: 2px solid var(--purple);
    padding: 1.5rem 1.75rem;
    margin-bottom: 1.75rem;
    display: none;
}
.tr-form-panel.open { display: block; }
.tr-form-titulo {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.18em;
    color: var(--purple);
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 8px;
}
.tr-form-grid {
    display: grid;
    grid-template-columns: 1fr 2fr auto;
    gap: 1rem;
    align-items: flex-end;
}
.tr-form-campo { display: flex; flex-direction: column; gap: 5px; }
.tr-form-label {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.14em;
    color: var(--ink-dim);
}
.tr-form-input {
    background: rgba(245,241,234,0.04);
    border: 1px solid var(--tr-border);
    color: var(--ink);
    padding: 9px 13px;
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 14px;
    outline: none;
    width: 100%;
    transition: border-color 0.15s;
}
.tr-form-input:focus { border-color: var(--purple-bd); }
.tr-form-input::placeholder { color: rgba(245,241,234,0.25); }
.tr-btn-crear {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    padding: 10px 22px;
    background: var(--purple);
    color: #fff;
    border: none;
    cursor: pointer;
    transition: background 0.15s;
    white-space: nowrap;
}
.tr-btn-crear:hover { background: #c084fc; color: #07060c; }
.tr-btn-cancelar {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    padding: 10px 16px;
    background: transparent;
    color: var(--ink-dim);
    border: 1px solid var(--tr-border);
    cursor: pointer;
    margin-left: 8px;
}

/* ── Tabla ── */
.tr-tabla-wrap {
    background: var(--tr-bg);
    border: 1px solid var(--tr-border);
    overflow: hidden;
}
.tr-tabla-head {
    display: grid;
    grid-template-columns: 50px 1fr 2fr 90px 140px;
    gap: 1rem;
    padding: 11px 20px;
    background: rgba(168,85,247,0.07);
    border-bottom: 1px solid var(--tr-border);
}
.tr-th {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 9px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.18em;
    color: rgba(245,241,234,0.35);
}
.tr-fila {
    display: grid;
    grid-template-columns: 50px 1fr 2fr 90px 140px;
    gap: 1rem;
    padding: 15px 20px;
    border-bottom: 1px solid rgba(245,241,234,0.05);
    align-items: center;
    transition: background 0.1s;
}
.tr-fila:last-child { border-bottom: none; }
.tr-fila:hover { background: var(--tr-hover); }
.tr-id {
    font-family: 'Anton', sans-serif;
    font-size: 13px;
    color: rgba(245,241,234,0.25);
}
.tr-nombre {
    font-family: 'Archivo', sans-serif;
    font-size: 14px;
    font-weight: 700;
    color: var(--ink);
}
.tr-desc {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 12px;
    color: var(--ink-dim);
}
.tr-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 9px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.14em;
    padding: 4px 10px;
}
.tr-badge-activo   { background:rgba(52,211,153,0.12); color:#34d399; border:1px solid rgba(52,211,153,0.28); }
.tr-badge-inactivo { background:rgba(248,113,113,0.10); color:#f87171; border:1px solid rgba(248,113,113,0.22); }
.tr-acciones { display: flex; align-items: center; gap: 6px; }

/* Botones de acción en tabla */
.tr-accion {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.10em;
    padding: 6px 12px;
    border: none;
    cursor: pointer;
    transition: background 0.15s;
    white-space: nowrap;
}
.tr-accion-toggle {
    background: rgba(245,241,234,0.06);
    color: var(--ink-dim);
    border: 1px solid var(--tr-border);
}
.tr-accion-toggle:hover { background: rgba(245,241,234,0.12); color: var(--ink); }
.tr-accion-editar {
    background: var(--purple-dim);
    color: #c084fc;
    border: 1px solid var(--purple-bd);
}
.tr-accion-editar:hover { background: rgba(168,85,247,0.30); color: var(--ink); }

/* Empty state */
.tr-empty {
    padding: 3.5rem 2rem;
    text-align: center;
    color: rgba(245,241,234,0.25);
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 14px;
}
</style>
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
            <div style="display:flex;align-items:flex-end;gap:8px;">
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

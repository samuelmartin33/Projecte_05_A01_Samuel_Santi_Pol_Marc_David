@extends('layouts.app')

@section('titulo', 'Equipo — ' . $empresa->nombre_empresa)

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/empresa-home.css') }}">
<style>
/* ── Tabla equipo ── */
.eq-table-wrap { background:#0d0a18; border:1px solid rgba(245,241,234,0.10); overflow-x:auto; }
.eq-table { width:100%; border-collapse:collapse; }
.eq-table thead tr { border-bottom:1px solid rgba(245,241,234,0.10); }
.eq-table thead th {
    padding:13px 20px; font-family:'Archivo Narrow',sans-serif;
    font-size:0.5625rem; font-weight:600; text-transform:uppercase; letter-spacing:0.18em;
    color:rgba(245,241,234,0.35); white-space:nowrap; text-align:left;
}
.eq-table tbody tr { border-bottom:1px solid rgba(245,241,234,0.06); transition:background 0.12s; }
.eq-table tbody tr:last-child { border-bottom:none; }
.eq-table tbody tr:hover { background:rgba(245,241,234,0.03); }
.eq-table td { padding:14px 20px; color:#f5f1ea; vertical-align:middle; }

.eq-avatar {
    width:38px; height:38px; border-radius:50%; background:linear-gradient(135deg,#7c3aed,#a855f7);
    display:inline-flex; align-items:center; justify-content:center;
    font-family:'Anton',sans-serif; font-size:0.9rem; color:#fff; flex-shrink:0;
}
.eq-nombre { font-family:'Archivo',sans-serif; font-weight:700; font-size:0.9rem; color:#f5f1ea; }
.eq-email  { font-family:'Archivo Narrow',sans-serif; font-size:0.7rem; color:rgba(245,241,234,0.40); margin-top:2px; }

/* Badges de acceso y puesto (solo lectura) */
.rol-badge {
    display:inline-flex; align-items:center; gap:5px;
    font-family:'Archivo Narrow',sans-serif; font-size:0.5rem; font-weight:700;
    text-transform:uppercase; letter-spacing:0.14em; padding:4px 10px; white-space:nowrap;
}
.rol-organizador { background:rgba(168,85,247,0.12); color:#c084fc; border:1px solid rgba(168,85,247,0.25); }
.rol-portero     { background:rgba(52,211,153,0.12);  color:#34d399; border:1px solid rgba(52,211,153,0.25); }
.puesto-badge {
    display:inline-flex; align-items:center; gap:5px;
    font-family:'Archivo Narrow',sans-serif; font-size:0.5rem; font-weight:700;
    text-transform:uppercase; letter-spacing:0.12em; padding:4px 10px; white-space:nowrap;
    background:rgba(245,241,234,0.06); color:rgba(245,241,234,0.55); border:1px solid rgba(245,241,234,0.10);
}
.puesto-badge.asignado { background:rgba(251,191,36,0.10); color:#fbbf24; border-color:rgba(251,191,36,0.25); }

/* ──────────────────────────────────────────────────────────────────────────
   PILL-WRAP — selector custom colapsable (reemplaza <select> nativo).
   Cerrado por defecto; se despliega al hacer click en el trigger.
   Estructura:
     .pill-wrap                  → posición relativa
       .pill-trigger-btn         → botón visible (muestra opción actual)
         span                    → texto de la opción seleccionada
         svg.pill-arrow          → chevron que rota al abrir
       .pill-options             → lista de opciones (oculta por defecto)
         button.pill-opt         → cada opción
────────────────────────────────────────────────────────────────────────── */
.pill-wrap { position:relative; min-width:150px; }

/* Trigger cerrado */
.pill-trigger-btn {
    width:100%; display:flex; align-items:center; justify-content:space-between; gap:8px;
    background:#0d0a18; border:1px solid rgba(168,85,247,0.40); border-radius:0;
    padding:8px 12px; color:#f5f1ea; cursor:pointer; user-select:none; text-align:left;
    font-family:'Archivo Narrow',sans-serif; font-size:0.78rem; font-weight:600;
    transition:border-color 0.15s;
}
.pill-trigger-btn:hover { border-color:#a855f7; }

/* Al abrirse, solo resalta el borde del trigger (el menú está desacoplado como fixed) */
.pill-wrap.open .pill-trigger-btn {
    border-color:#a855f7;
}

/* Chevron: rota 180° cuando está abierto */
.pill-arrow {
    flex-shrink:0; opacity:0.60; width:12px; height:12px;
    transition:transform 0.18s, opacity 0.15s;
}
.pill-wrap.open .pill-arrow { transform:rotate(180deg); opacity:1; }

/* Lista de opciones (oculta por defecto).
   La posición (fixed) y las coordenadas top/left/width se calculan
   y aplican vía JS en togglePillWrap(), lo que permite que el menú
   escape cualquier contenedor con overflow sin causar scrollbar. */
.pill-options {
    display:none;
    position:fixed;          /* se sobreescribe con valores exactos en JS */
    z-index:500;
    background:#0d0a18; border:1px solid rgba(168,85,247,0.40);
    border-radius:0;
    padding:4px 4px 6px;
    flex-direction:column; gap:2px;
    max-height:220px; overflow-y:auto;
    box-shadow:0 8px 28px rgba(0,0,0,0.65);
}
.pill-wrap.open .pill-options { display:flex; }

/* Scrollbar discreta dentro del dropdown */
.pill-options::-webkit-scrollbar { width:4px; }
.pill-options::-webkit-scrollbar-track { background:transparent; }
.pill-options::-webkit-scrollbar-thumb { background:rgba(168,85,247,0.35); border-radius:2px; }

/* Cada opción */
.pill-opt {
    display:block; width:100%; text-align:left;
    padding:7px 10px; border-radius:0; border:none; cursor:pointer;
    font-family:'Archivo Narrow',sans-serif; font-size:0.78rem; font-weight:600;
    background:transparent; color:rgba(245,241,234,0.55);
    transition:background 0.12s, color 0.12s;
}
.pill-opt:hover { background:rgba(168,85,247,0.15); color:#f5f1ea; }
.pill-opt.activo { background:rgba(168,85,247,0.20); color:#c084fc; }

/* Formulario de edición inline */
.edit-form { display:flex; flex-direction:column; gap:10px; min-width:160px; }
.edit-form-label {
    font-family:'Archivo Narrow',sans-serif; font-size:0.55rem; font-weight:700;
    text-transform:uppercase; letter-spacing:0.12em; color:rgba(245,241,234,0.35);
    margin-bottom:3px; display:block;
}
.btn-rol-save {
    font-family:'Archivo Narrow',sans-serif; font-size:0.55rem; font-weight:700;
    text-transform:uppercase; letter-spacing:0.12em; padding:6px 10px;
    background:rgba(168,85,247,0.15); color:#c084fc; border:1px solid rgba(168,85,247,0.30);
    cursor:pointer; transition:background 0.15s; width:100%; border-radius:0;
}
.btn-rol-save:hover { background:rgba(168,85,247,0.28); }
.btn-remove {
    font-family:'Archivo Narrow',sans-serif; font-size:0.55rem; font-weight:700;
    text-transform:uppercase; letter-spacing:0.12em; padding:5px 10px;
    background:transparent; color:rgba(248,113,113,0.7); border:1px solid rgba(248,113,113,0.20);
    cursor:pointer; transition:background 0.15s, color 0.15s; border-radius:0;
}
.btn-remove:hover { background:rgba(248,113,113,0.10); color:#f87171; }

/* Botón "＋ Añadir miembro" */
.btn-anadir-miembro {
    display:inline-flex; align-items:center; gap:7px;
    background:#a855f7;
    color:#fff; border:none; border-radius:0;
    padding:9px 18px; font-family:'Archivo Narrow',sans-serif;
    font-size:0.8rem; font-weight:700; cursor:pointer;
    transition:background 0.15s; white-space:nowrap;
}
.btn-anadir-miembro:hover { background:#c084fc; color:#07060c; }

/* ──────────────────────────────────────────────────────────────────────────
   Modal "Crear nuevo miembro"
────────────────────────────────────────────────────────────────────────── */
.modal-overlay {
    position:fixed; inset:0; z-index:1000;
    background:rgba(0,0,0,0.72);
    display:flex; align-items:center; justify-content:center;
    padding:1rem;
}
.modal-panel {
    background:#0d0a18; backdrop-filter:blur(20px);
    border:1px solid rgba(168,85,247,0.35); border-radius:0;
    padding:2rem; max-width:600px; width:100%;
    max-height:90vh; overflow-y:auto;
}
.modal-titulo {
    font-family:'Anton',sans-serif; font-size:1.35rem; letter-spacing:0.04em;
    color:#f5f1ea; margin:0 0 1.5rem;
}
.modal-label {
    display:block; font-family:'Archivo Narrow',sans-serif; font-size:0.72rem;
    font-weight:700; text-transform:uppercase; letter-spacing:0.10em;
    color:rgba(245,241,234,0.50); margin-bottom:6px;
}
.modal-input {
    width:100%; box-sizing:border-box;
    background:#0d0a18; border:1px solid rgba(245,241,234,0.10);
    color:#f5f1ea; border-radius:0; padding:12px 16px;
    font-family:'Archivo Narrow',sans-serif; font-size:0.9rem;
    outline:none; transition:border-color 0.15s, box-shadow 0.15s;
}
.modal-input::placeholder { color:rgba(245,241,234,0.25); }
.modal-input:focus { border-color:#a855f7; box-shadow:0 0 0 3px rgba(168,85,247,0.15); }
.modal-field { margin-bottom:1rem; }
.modal-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
@media (max-width:520px) { .modal-grid-2 { grid-template-columns:1fr; } }
.modal-error {
    background:rgba(248,113,113,0.12); border:1px solid rgba(248,113,113,0.30);
    color:#f87171; padding:10px 16px; border-radius:0;
    font-family:'Archivo Narrow',sans-serif; font-size:0.85rem;
    margin-bottom:1.25rem; display:none;
}

/* Los pill-options usan position:fixed con z-index:500 (calculado en JS),
   así que escapan el stacking context del modal sin reglas adicionales. */
</style>
@endpush

@section('content')

@include('partials.home.nav')

{{-- Overlay transparente para cerrar pill-wraps al hacer clic fuera (en la tabla principal).
     z-index 150: por encima de la página pero por debajo del modal (z-index 1000). --}}
<div id="pill-sel-overlay"
     onclick="cerrarTodosPillWraps()"
     style="display:none;position:fixed;inset:0;z-index:150;"></div>

{{-- ── HERO ── --}}
<section class="hero-home">
    <div class="hero-particula hero-particula-1"></div>
    <div class="hero-particula hero-particula-2"></div>
    <div class="hero-particula hero-particula-3"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 text-center relative z-10">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold mb-5"
             style="background:rgba(168,85,247,0.12);border:1px solid rgba(168,85,247,0.28);color:#c084fc;letter-spacing:0.06em;text-transform:uppercase;">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Gestión de equipo
        </div>
        <h1 class="text-4xl sm:text-5xl font-black text-white tracking-tight leading-tight mb-3">Equipo</h1>
        <p class="text-slate-400 text-lg max-w-xl mx-auto">
            Gestiona el acceso y el puesto de trabajo de cada miembro.
        </p>
    </div>
</section>

<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 pb-20">

    {{-- Alertas --}}
    @if(session('success'))
        <div style="background:rgba(52,211,153,0.10);border:1px solid rgba(52,211,153,0.25);color:#34d399;padding:12px 18px;margin-bottom:1.5rem;font-family:'Archivo Narrow',sans-serif;font-size:0.85rem;">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div style="background:rgba(248,113,113,0.10);border:1px solid rgba(248,113,113,0.25);color:#f87171;padding:12px 18px;margin-bottom:1.5rem;font-family:'Archivo Narrow',sans-serif;font-size:0.85rem;">
            @foreach($errors->all() as $err) <div>· {{ $err }}</div> @endforeach
        </div>
    @endif

    {{-- Cabecera: título + botón Añadir miembro --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
        <div>
            <div class="seccion-empresa-titulo" style="margin-bottom:0;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Miembros del equipo
            </div>
            <p class="seccion-empresa-sub" style="margin-top:4px;">
                {{ $miembros->count() }} miembro{{ $miembros->count() !== 1 ? 's' : '' }} activo{{ $miembros->count() !== 1 ? 's' : '' }}
            </p>
        </div>
        <button type="button" class="btn-anadir-miembro" onclick="abrirModalCrear()">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Añadir miembro
        </button>
    </div>

    {{-- Tabla de miembros --}}
    @if($miembros->isEmpty())
        <div class="empty-state">
            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="empty-titulo">Aún no tienes miembros en tu equipo</p>
            <p class="empty-desc">Pulsa "Añadir miembro" para incorporar al primer integrante.</p>
        </div>
    @else
    <div class="eq-table-wrap">
        <table class="eq-table">
            <thead>
                <tr>
                    <th>Miembro</th>
                    <th>Acceso</th>
                    <th>Puesto</th>
                    <th>Editar</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach($miembros as $miembro)
            @php $usr = $miembro->usuario; @endphp
            <tr>
                {{-- Nombre + email --}}
                <td>
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div class="eq-avatar">
                            {{ strtoupper(substr($usr->nombre ?? '?', 0, 1)) }}{{ strtoupper(substr($usr->apellido1 ?? '', 0, 1)) }}
                        </div>
                        <div>
                            <div class="eq-nombre">{{ $usr->nombre }} {{ $usr->apellido1 }}</div>
                            <div class="eq-email">{{ $usr->email }}</div>
                        </div>
                    </div>
                </td>

                {{-- Badge de acceso (solo lectura) --}}
                <td>
                    @if($miembro->rol === 'portero')
                        <span class="rol-badge rol-portero">
                            <svg width="9" height="9" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Portero
                        </span>
                    @else
                        <span class="rol-badge rol-organizador">
                            <svg width="9" height="9" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Organizador
                        </span>
                    @endif
                </td>

                {{-- Badge de puesto (solo lectura) --}}
                <td>
                    @if($miembro->categoriaTrabajo)
                        <span class="puesto-badge asignado">
                            <svg width="9" height="9" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ $miembro->categoriaTrabajo->nombre }}
                        </span>
                    @else
                        <span class="puesto-badge">— Sin puesto —</span>
                    @endif
                </td>

                {{-- ── Editar: pill-wraps colapsables (reemplazan <select> nativos) ── --}}
                <td>
                    <form method="POST" action="{{ route('empresa.equipo.rol', $miembro) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="rol"                  id="rol-{{ $miembro->id }}" value="{{ $miembro->rol }}">
                        <input type="hidden" name="categoria_trabajo_id" id="cat-{{ $miembro->id }}" value="{{ $miembro->categoria_trabajo_id }}">

                        <div class="edit-form">

                            {{-- Selector de Acceso — cerrado por defecto, se abre con click --}}
                            <div>
                                <span class="edit-form-label">Acceso</span>
                                <div class="pill-wrap" id="pw-rol-{{ $miembro->id }}">
                                    <button type="button" class="pill-trigger-btn"
                                        onclick="togglePillWrap('pw-rol-{{ $miembro->id }}')">
                                        <span>{{ $miembro->rol === 'portero' ? 'Portero' : 'Organizador' }}</span>
                                        <svg class="pill-arrow" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div class="pill-options">
                                        <button type="button"
                                            class="pill-opt {{ $miembro->rol === 'organizador' ? 'activo' : '' }}"
                                            onclick="elegirRolPill('{{ $miembro->id }}', 'organizador', this)">
                                            Organizador
                                        </button>
                                        <button type="button"
                                            class="pill-opt {{ $miembro->rol === 'portero' ? 'activo' : '' }}"
                                            onclick="elegirRolPill('{{ $miembro->id }}', 'portero', this)">
                                            Portero
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Selector de Puesto — cerrado por defecto, se abre con click --}}
                            <div>
                                <span class="edit-form-label">Puesto</span>
                                <div class="pill-wrap" id="pw-cat-{{ $miembro->id }}">
                                    <button type="button" class="pill-trigger-btn"
                                        onclick="togglePillWrap('pw-cat-{{ $miembro->id }}')">
                                        <span>{{ $miembro->categoriaTrabajo ? $miembro->categoriaTrabajo->nombre : '— Sin puesto —' }}</span>
                                        <svg class="pill-arrow" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div class="pill-options">
                                        <button type="button"
                                            class="pill-opt {{ !$miembro->categoria_trabajo_id ? 'activo' : '' }}"
                                            onclick="elegirCatPill('{{ $miembro->id }}', '', this)">
                                            — Sin puesto —
                                        </button>
                                        @foreach($categorias as $cat)
                                        <button type="button"
                                            class="pill-opt {{ $miembro->categoria_trabajo_id == $cat->id ? 'activo' : '' }}"
                                            onclick="elegirCatPill('{{ $miembro->id }}', '{{ $cat->id }}', this)">
                                            {{ $cat->nombre }}
                                        </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn-rol-save">Guardar cambios</button>
                        </div>
                    </form>
                </td>

                {{-- Acciones: horas + eliminar --}}
                <td>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <a href="{{ route('empresa.equipo.horas', $miembro->usuario_id) }}"
                           title="Ver horas de {{ $usr->nombre }}"
                           style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;background:rgba(168,85,247,0.12);border:1px solid rgba(168,85,247,0.30);color:#c084fc;text-decoration:none;border-radius:0;transition:background 0.15s;"
                           onmouseover="this.style.background='rgba(168,85,247,0.25)'"
                           onmouseout="this.style.background='rgba(168,85,247,0.12)'">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('empresa.equipo.destroy', $miembro) }}"
                              onsubmit="return confirm('¿Eliminar a {{ $usr->nombre }} del equipo?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-remove">Eliminar</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Leyenda --}}
    <div style="margin-top:24px;display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div style="background:#0d0a18;border:1px solid rgba(245,241,234,0.07);padding:16px 20px;border-radius:0;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                <span class="rol-badge rol-organizador" style="font-size:0.45rem;">Organizador</span>
            </div>
            <p style="font-family:'Archivo Narrow',sans-serif;font-size:0.8rem;color:rgba(245,241,234,0.45);line-height:1.5;">
                Acceso completo al panel de empresa: eventos, candidaturas, validación QR y administración.
            </p>
        </div>
        <div style="background:#0d0a18;border:1px solid rgba(245,241,234,0.07);padding:16px 20px;border-radius:0;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                <span class="rol-badge rol-portero" style="font-size:0.45rem;">Portero</span>
            </div>
            <p style="font-family:'Archivo Narrow',sans-serif;font-size:0.8rem;color:rgba(245,241,234,0.45);line-height:1.5;">
                Acceso restringido: solo puede validar entradas QR e iniciar sesión como usuario normal con funciones extra.
            </p>
        </div>
    </div>

</section>

{{-- ════════════════════════════════════════════════════════════════════════
     Modal "Crear nuevo miembro"
     · El panel captura el click con vibezModalClickFuera() para cerrar
       cualquier pill-wrap abierto al hacer clic fuera de él (sin addEventListener).
     · El overlay principal (onclick="cerrarModalCrear()") cierra el modal
       al hacer clic fuera del panel.
════════════════════════════════════════════════════════════════════════ --}}
<div id="modal-crear-miembro" class="modal-overlay" style="display:none;" onclick="cerrarModalCrear()">
    <div class="modal-panel" onclick="vibezModalClickFuera(event)">

        <p class="modal-titulo">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"
                 style="display:inline;vertical-align:middle;margin-right:6px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo miembro
        </p>

        <div id="modal-error" class="modal-error"></div>

        <form id="form-crear-miembro" onsubmit="return false">
            @csrf

            {{-- Nombre y Apellido --}}
            <div class="modal-grid-2">
                <div class="modal-field">
                    <label class="modal-label">Nombre</label>
                    <input type="text" name="nombre" class="modal-input"
                        placeholder="Juan" required autocomplete="off">
                </div>
                <div class="modal-field">
                    <label class="modal-label">Apellido</label>
                    <input type="text" name="apellido1" class="modal-input"
                        placeholder="García" required autocomplete="off">
                </div>
            </div>

            {{-- Email --}}
            <div class="modal-field">
                <label class="modal-label">Email</label>
                <input type="email" name="email" class="modal-input"
                    placeholder="correo@vibez.com" required autocomplete="off">
            </div>

            {{-- Contraseñas --}}
            <div class="modal-grid-2">
                <div class="modal-field">
                    <label class="modal-label">Contraseña</label>
                    <input type="password" name="password" class="modal-input"
                        placeholder="Mínimo 8 caracteres" required>
                </div>
                <div class="modal-field">
                    <label class="modal-label">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" class="modal-input"
                        placeholder="Repite la contraseña" required>
                </div>
            </div>

            {{-- Fecha de nacimiento: campo texto con máscara DD/MM/AAAA --}}
            <div class="modal-field">
                <label class="modal-label">Fecha de nacimiento</label>
                <input type="text" name="fecha_nacimiento" class="modal-input"
                    placeholder="DD/MM/AAAA" maxlength="10" autocomplete="off"
                    oninput="mascFechaNac(this)">
            </div>

            {{-- Acceso y Puesto: pill-wraps colapsables --}}
            <div class="modal-grid-2">

                {{-- Selector Acceso --}}
                <div class="modal-field">
                    <label class="modal-label">Acceso</label>
                    <input type="hidden" id="modal-rol" name="rol" value="organizador">
                    <div class="pill-wrap" id="pw-modal-rol">
                        <button type="button" class="pill-trigger-btn"
                            onclick="togglePillWrap('pw-modal-rol')">
                            <span id="pw-modal-rol-lbl">Organizador</span>
                            <svg class="pill-arrow" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div class="pill-options">
                            <button type="button" class="pill-opt activo"
                                onclick="elegirModalPill('modal-rol', 'organizador', this)">Organizador</button>
                            <button type="button" class="pill-opt"
                                onclick="elegirModalPill('modal-rol', 'portero', this)">Portero</button>
                        </div>
                    </div>
                </div>

                {{-- Selector Puesto --}}
                <div class="modal-field">
                    <label class="modal-label">Puesto</label>
                    <input type="hidden" id="modal-cat" name="categoria_trabajo_id" value="">
                    <div class="pill-wrap" id="pw-modal-cat">
                        <button type="button" class="pill-trigger-btn"
                            onclick="togglePillWrap('pw-modal-cat')">
                            <span id="pw-modal-cat-lbl">— Sin puesto —</span>
                            <svg class="pill-arrow" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div class="pill-options">
                            <button type="button" class="pill-opt activo"
                                onclick="elegirModalPill('modal-cat', '', this)">— Sin puesto —</button>
                            @foreach($categorias as $cat)
                            <button type="button" class="pill-opt"
                                onclick="elegirModalPill('modal-cat', '{{ $cat->id }}', this)">{{ $cat->nombre }}</button>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            {{-- Botones del modal --}}
            <div style="display:flex;justify-content:flex-end;align-items:center;gap:12px;margin-top:1.75rem;">
                <button type="button"
                    style="border:1px solid rgba(245,241,234,0.15);color:rgba(245,241,234,0.55);border-radius:0;padding:10px 20px;background:transparent;font-family:'Archivo Narrow',sans-serif;font-size:0.85rem;font-weight:600;cursor:pointer;transition:border-color 0.15s,color 0.15s;"
                    onmouseover="this.style.borderColor='#9ca3af';this.style.color='#fff'"
                    onmouseout="this.style.borderColor='#4b5563';this.style.color='#9ca3af'"
                    onclick="cerrarModalCrear()">Cancelar</button>

                <button type="button" id="btn-crear-miembro"
                    style="background:#a855f7;color:#fff;border:none;border-radius:0;padding:10px 20px;font-family:'Archivo Narrow',sans-serif;font-size:0.85rem;font-weight:700;cursor:pointer;transition:background 0.15s;"
                    onmouseover="this.style.opacity='0.88'"
                    onmouseout="this.style.opacity='1'"
                    onclick="submitCrearMiembro()">Crear miembro</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
/* ════════════════════════════════════════════════════════════════════════
   equipo/index.js — lógica de la página de equipo
   Regla: PROHIBIDO addEventListener. Todo inline: onclick, oninput…
════════════════════════════════════════════════════════════════════════ */

/* ── Pill-wrap: selector custom colapsable ──────────────────────────── */

/**
 * Cierra todos los pill-wrap abiertos y limpia los estilos inline de posición
 * que se aplicaron vía JS al abrir el dropdown.
 */
function cerrarTodosPillWraps() {
    document.querySelectorAll('.pill-wrap.open').forEach(function(wrap) {
        var menu = wrap.querySelector('.pill-options');
        if (menu) {
            /* Elimina los estilos inline de posicionamiento fijo */
            menu.style.top   = '';
            menu.style.left  = '';
            menu.style.width = '';
        }
        wrap.classList.remove('open');
    });
    var overlay = document.getElementById('pill-sel-overlay');
    if (overlay) overlay.style.display = 'none';
}

/**
 * Abre o cierra un pill-wrap concreto.
 *
 * Al abrir calcula la posición exacta del trigger mediante getBoundingClientRect()
 * y aplica position:fixed al menú con esas coordenadas, de modo que el dropdown
 * escapa cualquier contenedor con overflow (no provoca scrollbar en la tabla).
 *
 * @param {string} id - ID del elemento .pill-wrap
 */
function togglePillWrap(id) {
    var el          = document.getElementById(id);
    var estaAbierto = el.classList.contains('open');

    /* Cierra cualquier otro abierto y limpia sus estilos */
    cerrarTodosPillWraps();

    if (!estaAbierto) {
        var trigger = el.querySelector('.pill-trigger-btn');
        var menu    = el.querySelector('.pill-options');
        var rect    = trigger.getBoundingClientRect();

        /* Posiciona el menú justo debajo del trigger, con su mismo ancho */
        menu.style.top   = (rect.bottom + 4) + 'px';
        menu.style.left  = rect.left + 'px';
        menu.style.width = rect.width + 'px';

        el.classList.add('open');

        /* Overlay para cerrar al hacer clic fuera (fuera del modal también) */
        var overlay = document.getElementById('pill-sel-overlay');
        if (overlay) overlay.style.display = 'block';
    }
}

/**
 * Actualiza el label del trigger de un pill-wrap con el texto de la opción elegida
 * y cierra el dropdown.
 *
 * @param {HTMLElement} btn  - botón .pill-opt pulsado
 */
function _actualizarTriggerLabel(btn) {
    var wrap = btn.closest('.pill-wrap');
    if (!wrap) return;
    var lbl = wrap.querySelector('.pill-trigger-btn span');
    if (lbl) lbl.textContent = btn.textContent.trim();
    cerrarTodosPillWraps();
}

/* ── Selectores de la tabla ─────────────────────────────────────────── */

/**
 * Selecciona el rol de acceso de un miembro en la tabla de edición.
 *
 * @param {string}      mid - ID del organizador (para localizar el input hidden)
 * @param {string}      val - 'organizador' | 'portero'
 * @param {HTMLElement} btn - botón pulsado
 */
function elegirRolPill(mid, val, btn) {
    /* Desmarca todas las opciones del mismo grupo */
    btn.closest('.pill-options').querySelectorAll('.pill-opt').forEach(function(b) {
        b.classList.remove('activo');
    });
    btn.classList.add('activo');
    document.getElementById('rol-' + mid).value = val;
    _actualizarTriggerLabel(btn);
}

/**
 * Selecciona el puesto de trabajo de un miembro en la tabla de edición.
 *
 * @param {string}      mid - ID del organizador
 * @param {string}      val - ID de categoría_trabajo, o '' para sin puesto
 * @param {HTMLElement} btn - botón pulsado
 */
function elegirCatPill(mid, val, btn) {
    btn.closest('.pill-options').querySelectorAll('.pill-opt').forEach(function(b) {
        b.classList.remove('activo');
    });
    btn.classList.add('activo');
    document.getElementById('cat-' + mid).value = val;
    _actualizarTriggerLabel(btn);
}

/* ── Modal "Crear nuevo miembro" ─────────────────────────────────────── */

/**
 * Selecciona una opción en cualquier pill-wrap del modal.
 *
 * @param {string}      inputId - ID del input hidden asociado
 * @param {string}      val     - valor a guardar
 * @param {HTMLElement} btn     - botón pulsado
 */
function elegirModalPill(inputId, val, btn) {
    btn.closest('.pill-options').querySelectorAll('.pill-opt').forEach(function(b) {
        b.classList.remove('activo');
    });
    btn.classList.add('activo');
    document.getElementById(inputId).value = val;
    _actualizarTriggerLabel(btn);
}

/**
 * Handler del onclick del panel del modal.
 * Cierra cualquier pill-wrap abierto dentro del modal al hacer clic fuera de él.
 * También evita que el clic cierre el modal (stopPropagation).
 *
 * @param {MouseEvent} e
 */
function vibezModalClickFuera(e) {
    e.stopPropagation(); /* evita propagar al overlay del modal */
    if (!e.target.closest('.pill-wrap')) {
        cerrarTodosPillWraps();
    }
}

/**
 * Abre el modal de creación de miembro y bloquea el scroll del body.
 */
function abrirModalCrear() {
    document.getElementById('modal-crear-miembro').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

/**
 * Cierra el modal, resetea el formulario y devuelve los pill-wraps
 * a su estado inicial (primera opción activa, trigger con su label).
 */
function cerrarModalCrear() {
    document.getElementById('modal-crear-miembro').style.display = 'none';
    document.body.style.overflow = '';

    /* Resetea campos de texto, email y password */
    var form = document.getElementById('form-crear-miembro');
    if (form) form.reset();

    /* Oculta el bloque de errores */
    var errDiv = document.getElementById('modal-error');
    if (errDiv) errDiv.style.display = 'none';

    /* Reactiva el botón de submit */
    var btn = document.getElementById('btn-crear-miembro');
    if (btn) { btn.disabled = false; btn.textContent = 'Crear miembro'; }

    /* Cierra cualquier pill-wrap abierto */
    cerrarTodosPillWraps();

    /* Resetea pill-wraps del modal: activa el primero y restaura el label del trigger */
    document.querySelectorAll('#modal-crear-miembro .pill-wrap').forEach(function(wrap) {
        wrap.classList.remove('open');
        var opts = wrap.querySelectorAll('.pill-opt');
        var lbl  = wrap.querySelector('.pill-trigger-btn span');
        opts.forEach(function(o, i) { o.classList.toggle('activo', i === 0); });
        if (opts[0] && lbl) lbl.textContent = opts[0].textContent.trim();
    });

    /* Restaura los hidden inputs */
    document.getElementById('modal-rol').value = 'organizador';
    document.getElementById('modal-cat').value = '';
}

/**
 * Máscara para el campo de fecha de nacimiento.
 * Auto-inserta "/" al escribir para el formato DD/MM/AAAA (sin <input type="date">).
 *
 * @param {HTMLInputElement} input
 */
function mascFechaNac(input) {
    var v = input.value.replace(/\D/g, '');          /* solo dígitos */
    if (v.length > 2) v = v.slice(0, 2) + '/' + v.slice(2);  /* DD/ */
    if (v.length > 5) v = v.slice(0, 5) + '/' + v.slice(5);  /* MM/ */
    if (v.length > 10) v = v.slice(0, 10);           /* máximo DD/MM/AAAA */
    input.value = v;
}

/**
 * Envía el formulario de creación de miembro vía fetch (sin recargar la página).
 * Éxito (201) → cierra el modal y recarga para mostrar la nueva fila.
 * Error        → muestra los mensajes en rojo dentro del modal.
 */
function submitCrearMiembro() {
    var form   = document.getElementById('form-crear-miembro');
    var errDiv = document.getElementById('modal-error');
    var btn    = document.getElementById('btn-crear-miembro');

    errDiv.style.display = 'none';
    btn.disabled = true;
    btn.textContent = 'Creando…';

    fetch('{{ route("empresa.equipo.store") }}', {
        method: 'POST',
        headers: {
            'Accept':           'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN':     form.querySelector('input[name="_token"]').value
        },
        body: new FormData(form)
    })
    .then(function(r) {
        return r.json().then(function(d) { return { status: r.status, data: d }; });
    })
    .then(function(res) {
        if (res.status === 200 || res.status === 201) {
            cerrarModalCrear();
            window.location.reload();
        } else {
            /* Extrae y muestra los mensajes de validación de Laravel (422) */
            var msgs = [];
            if (res.data.errors) {
                Object.values(res.data.errors).forEach(function(arr) {
                    arr.forEach(function(m) { msgs.push('· ' + m); });
                });
            } else {
                msgs.push(res.data.message || 'Error al crear el miembro.');
            }
            errDiv.innerHTML = msgs.join('<br>');
            errDiv.style.display = 'block';
            errDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    })
    .catch(function() {
        errDiv.innerHTML = 'Error de conexión. Inténtalo de nuevo.';
        errDiv.style.display = 'block';
    })
    .finally(function() {
        btn.disabled = false;
        btn.textContent = 'Crear miembro';
    });
}
</script>
@endpush

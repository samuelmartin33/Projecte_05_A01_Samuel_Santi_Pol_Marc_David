@extends('layouts.app')

@section('titulo', 'Equipo — ' . $empresa->nombre_empresa)

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/empresa-home.css') }}">
<link rel="stylesheet" href="{{ asset('css/empresa-equipo.css') }}">
{{-- CSS extraído a public/css/empresa-equipo.css --}}
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
<script>window.EQUIPO_STORE_URL = '{{ route("empresa.equipo.store") }}';</script>
<script src="{{ asset('js/empresa-equipo.js') }}"></script>
{{-- JS en public/js/empresa-equipo.js --}}
@endpush

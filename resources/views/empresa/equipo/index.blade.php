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

/* Badges */
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

/* Formulario de edición inline (rol + puesto juntos) */
.edit-form { display:flex; flex-direction:column; gap:6px; min-width:180px; }
.csinline { position:relative; display:block; }
.csinline-trigger {
    display:flex; align-items:center; justify-content:space-between; gap:8px;
    background:#0d0a18; border:1px solid rgba(245,241,234,0.12); color:#f5f1ea;
    padding:5px 10px; font-family:'Archivo Narrow',sans-serif; font-size:0.75rem;
    cursor:pointer; user-select:none; transition:border-color 0.15s; width:100%;
}
.csinline.open .csinline-trigger { border-color:rgba(168,85,247,0.50); }
.csinline-arrow { width:9px;height:9px;flex-shrink:0;opacity:0.45;transition:transform 0.15s; }
.csinline.open .csinline-arrow { transform:rotate(180deg); opacity:0.8; }
.csinline-menu {
    display:none; position:absolute; top:calc(100% + 2px); left:0; right:0; z-index:200;
    background:#0d0a18; border:1px solid rgba(168,85,247,0.30); max-height:180px; overflow-y:auto;
}
.csinline.open .csinline-menu { display:block; }
.csinline-opt {
    padding:7px 10px; font-family:'Archivo Narrow',sans-serif; font-size:0.75rem;
    color:rgba(245,241,234,0.65); cursor:pointer; transition:background 0.12s;
}
.csinline-opt:hover { background:rgba(168,85,247,0.12); color:#f5f1ea; }
.csinline-opt.sel { background:rgba(168,85,247,0.18); color:#c084fc; font-weight:700; }
.btn-rol-save {
    font-family:'Archivo Narrow',sans-serif; font-size:0.55rem; font-weight:700;
    text-transform:uppercase; letter-spacing:0.12em; padding:5px 10px;
    background:rgba(168,85,247,0.15); color:#c084fc; border:1px solid rgba(168,85,247,0.30);
    cursor:pointer; transition:background 0.15s; width:100%;
}
.btn-rol-save:hover { background:rgba(168,85,247,0.28); }
.btn-remove {
    font-family:'Archivo Narrow',sans-serif; font-size:0.55rem; font-weight:700;
    text-transform:uppercase; letter-spacing:0.12em; padding:5px 10px;
    background:transparent; color:rgba(248,113,113,0.7); border:1px solid rgba(248,113,113,0.20);
    cursor:pointer; transition:background 0.15s, color 0.15s;
}
.btn-remove:hover { background:rgba(248,113,113,0.10); color:#f87171; }

</style>
@endpush

@section('content')

@include('partials.home.nav')

{{-- HERO --}}
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

    {{-- Cabecera sección --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
        <div>
            <div class="seccion-empresa-titulo" style="margin-bottom:0;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Miembros del equipo
            </div>
            <p class="seccion-empresa-sub" style="margin-top:4px;">{{ $miembros->count() }} miembro{{ $miembros->count() !== 1 ? 's' : '' }} activo{{ $miembros->count() !== 1 ? 's' : '' }}</p>
        </div>
    </div>

    {{-- Tabla --}}
    @if($miembros->isEmpty())
        <div class="empty-state">
            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="empty-titulo">Aún no tienes miembros en tu equipo</p>
            <p class="empty-desc">Aún no hay miembros asociados a esta empresa.</p>
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

                {{-- Badge de acceso (permiso) --}}
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

                {{-- Badge de puesto (categorias_trabajo) --}}
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

                {{-- Formulario de edición: acceso + puesto en una sola acción --}}
                <td>
                    <form method="POST" action="{{ route('empresa.equipo.rol', $miembro) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="rol" id="rol-{{ $miembro->id }}" value="{{ $miembro->rol }}">
                        <input type="hidden" name="categoria_trabajo_id" id="cat-{{ $miembro->id }}" value="{{ $miembro->categoria_trabajo_id }}">

                        <div class="edit-form">
                            {{-- Selector acceso --}}
                            <div class="csinline" id="csi-rol-{{ $miembro->id }}"
                                 onmouseleave="cerrarCsInline('csi-rol-{{ $miembro->id }}')">
                                <div class="csinline-trigger" onclick="toggleCsInline('csi-rol-{{ $miembro->id }}')">
                                    <span id="lbl-rol-{{ $miembro->id }}">{{ $miembro->rol === 'portero' ? 'Portero' : 'Organizador' }}</span>
                                    <svg class="csinline-arrow" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
                                </div>
                                <div class="csinline-menu">
                                    <div class="csinline-opt {{ $miembro->rol === 'organizador' ? 'sel' : '' }}"
                                         onclick="elegirRol('{{ $miembro->id }}', 'organizador', 'Organizador')">Organizador</div>
                                    <div class="csinline-opt {{ $miembro->rol === 'portero' ? 'sel' : '' }}"
                                         onclick="elegirRol('{{ $miembro->id }}', 'portero', 'Portero')">Portero</div>
                                </div>
                            </div>

                            {{-- Selector puesto --}}
                            <div class="csinline" id="csi-cat-{{ $miembro->id }}"
                                 onmouseleave="cerrarCsInline('csi-cat-{{ $miembro->id }}')">
                                <div class="csinline-trigger" onclick="toggleCsInline('csi-cat-{{ $miembro->id }}')">
                                    <span id="lbl-cat-{{ $miembro->id }}">{{ $miembro->categoriaTrabajo ? $miembro->categoriaTrabajo->nombre : '— Sin puesto —' }}</span>
                                    <svg class="csinline-arrow" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
                                </div>
                                <div class="csinline-menu">
                                    <div class="csinline-opt {{ !$miembro->categoria_trabajo_id ? 'sel' : '' }}"
                                         onclick="elegirPuesto('{{ $miembro->id }}', '', '— Sin puesto —')">— Sin puesto —</div>
                                    @foreach($categorias as $cat)
                                        <div class="csinline-opt {{ $miembro->categoria_trabajo_id == $cat->id ? 'sel' : '' }}"
                                             onclick="elegirPuesto('{{ $miembro->id }}', '{{ $cat->id }}', '{{ $cat->nombre }}')">
                                            {{ $cat->nombre }}
                                        </div>
                                    @endforeach
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
                           style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;background:rgba(168,85,247,0.12);border:1px solid rgba(168,85,247,0.30);color:#c084fc;text-decoration:none;transition:background 0.15s;"
                           onmouseover="this.style.background='rgba(168,85,247,0.25)'"
                           onmouseout="this.style.background='rgba(168,85,247,0.12)'">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
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
        <div style="background:#0d0a18;border:1px solid rgba(245,241,234,0.07);padding:16px 20px;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                <span class="rol-badge rol-organizador" style="font-size:0.45rem;">Organizador</span>
            </div>
            <p style="font-family:'Archivo Narrow',sans-serif;font-size:0.8rem;color:rgba(245,241,234,0.45);line-height:1.5;">
                Acceso completo al panel de empresa: eventos, candidaturas, validación QR y administración.
            </p>
        </div>
        <div style="background:#0d0a18;border:1px solid rgba(245,241,234,0.07);padding:16px 20px;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                <span class="rol-badge rol-portero" style="font-size:0.45rem;">Portero</span>
            </div>
            <p style="font-family:'Archivo Narrow',sans-serif;font-size:0.8rem;color:rgba(245,241,234,0.45);line-height:1.5;">
                Acceso restringido: solo puede validar entradas QR e iniciar sesión como usuario normal con funciones extra.
            </p>
        </div>
    </div>

</section>

@endsection

@push('scripts')
<script>
/* ── Selectores inline de la tabla ── */
function toggleCsInline(id) {
    var el = document.getElementById(id);
    el.classList.toggle('open');
}

function cerrarCsInline(id) {
    document.getElementById(id).classList.remove('open');
}

/* Elige el rol de acceso para un miembro de la tabla */
function elegirRol(mid, val, label) {
    document.getElementById('csi-rol-' + mid).querySelectorAll('.csinline-opt').forEach(function(o) {
        o.classList.toggle('sel', o.textContent.trim() === label);
    });
    document.getElementById('lbl-rol-' + mid).textContent = label;
    document.getElementById('rol-' + mid).value = val;
    document.getElementById('csi-rol-' + mid).classList.remove('open');
}

/* Elige el puesto de trabajo para un miembro de la tabla */
function elegirPuesto(mid, val, label) {
    document.getElementById('csi-cat-' + mid).querySelectorAll('.csinline-opt').forEach(function(o) {
        o.classList.toggle('sel', o.textContent.trim() === label);
    });
    document.getElementById('lbl-cat-' + mid).textContent = label;
    document.getElementById('cat-' + mid).value = val;
    document.getElementById('csi-cat-' + mid).classList.remove('open');
}
</script>
@endpush

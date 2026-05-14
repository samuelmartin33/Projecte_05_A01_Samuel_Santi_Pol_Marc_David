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
.eq-table td { padding:16px 20px; color:#f5f1ea; vertical-align:middle; }

.eq-avatar {
    width:38px; height:38px; border-radius:50%; background:linear-gradient(135deg,#7c3aed,#a855f7);
    display:inline-flex; align-items:center; justify-content:center;
    font-family:'Anton',sans-serif; font-size:0.9rem; color:#fff; flex-shrink:0;
}
.eq-nombre { font-family:'Archivo',sans-serif; font-weight:700; font-size:0.9rem; color:#f5f1ea; }
.eq-email  { font-family:'Archivo Narrow',sans-serif; font-size:0.7rem; color:rgba(245,241,234,0.40); margin-top:2px; }

/* Badges de rol */
.rol-badge {
    display:inline-flex; align-items:center; gap:5px;
    font-family:'Archivo Narrow',sans-serif; font-size:0.5rem; font-weight:700;
    text-transform:uppercase; letter-spacing:0.14em; padding:4px 10px;
    white-space:nowrap;
}
.rol-organizador { background:rgba(168,85,247,0.12); color:#c084fc; border:1px solid rgba(168,85,247,0.25); }
.rol-portero     { background:rgba(52,211,153,0.12);  color:#34d399; border:1px solid rgba(52,211,153,0.25); }

/* Selector de rol inline */
.rol-form { display:inline-flex; align-items:center; gap:6px; }
.csinline { position:relative; display:inline-block; min-width:130px; }
.csinline-trigger {
    display:flex; align-items:center; justify-content:space-between; gap:8px;
    background:#0d0a18; border:1px solid rgba(245,241,234,0.12); color:#f5f1ea;
    padding:5px 10px; font-family:'Archivo Narrow',sans-serif; font-size:0.75rem;
    cursor:pointer; user-select:none; transition:border-color 0.15s;
}
.csinline.open .csinline-trigger { border-color:rgba(168,85,247,0.50); }
.csinline-arrow { width:9px;height:9px;flex-shrink:0;opacity:0.45;transition:transform 0.15s; }
.csinline.open .csinline-arrow { transform:rotate(180deg);opacity:0.8; }
.csinline-menu {
    display:none; position:absolute; top:calc(100% + 3px); left:0; right:0; z-index:200;
    background:#0d0a18; border:1px solid rgba(168,85,247,0.30);
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
    cursor:pointer; transition:background 0.15s;
}
.btn-rol-save:hover { background:rgba(168,85,247,0.28); }
.btn-remove {
    font-family:'Archivo Narrow',sans-serif; font-size:0.55rem; font-weight:700;
    text-transform:uppercase; letter-spacing:0.12em; padding:5px 10px;
    background:transparent; color:rgba(248,113,113,0.7); border:1px solid rgba(248,113,113,0.20);
    cursor:pointer; transition:background 0.15s, color 0.15s;
}
.btn-remove:hover { background:rgba(248,113,113,0.10); color:#f87171; }

/* ── Modal / panel crear usuario ── */
.crear-panel {
    background:#0d0a18; border:1px solid rgba(245,241,234,0.10);
    padding:28px 32px; margin-bottom:24px; display:none;
}
.crear-panel.open { display:block; }
.crear-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
.form-field { display:flex; flex-direction:column; gap:6px; }
.form-label {
    font-family:'Archivo Narrow',sans-serif; font-size:0.5rem; font-weight:700;
    text-transform:uppercase; letter-spacing:0.16em; color:rgba(245,241,234,0.35);
}
.form-input {
    background:#07050f; border:1px solid rgba(245,241,234,0.12); color:#f5f1ea;
    padding:9px 12px; font-size:0.85rem; font-family:'Archivo Narrow',sans-serif;
    outline:none; transition:border-color 0.15s;
}
.form-input::placeholder { color:rgba(245,241,234,0.20); }
.form-input:focus { border-color:rgba(168,85,247,0.55); }
.form-input.error { border-color:rgba(248,113,113,0.55); }

/* Selector de rol en formulario (custom igual al de filtros) */
.cselect-form { position:relative; }
.cselect-form-trigger {
    display:flex; align-items:center; justify-content:space-between; gap:10px;
    background:#07050f; border:1px solid rgba(245,241,234,0.12); color:#f5f1ea;
    padding:9px 12px; font-size:0.85rem; font-family:'Archivo Narrow',sans-serif;
    cursor:pointer; transition:border-color 0.15s; user-select:none;
}
.cselect-form.open .cselect-form-trigger { border-color:rgba(168,85,247,0.55); }
.cselect-form-arrow { width:10px; height:10px; flex-shrink:0; opacity:0.4; transition:transform 0.15s; }
.cselect-form.open .cselect-form-arrow { transform:rotate(180deg); opacity:0.8; }
.cselect-form-menu {
    display:none; position:absolute; top:calc(100% + 3px); left:0; right:0;
    background:#0f0c1e; border:1px solid rgba(168,85,247,0.30); z-index:300;
    box-shadow:0 8px 32px rgba(0,0,0,0.55);
}
.cselect-form.open .cselect-form-menu { display:block; }
.cselect-form-opt {
    padding:10px 14px; font-family:'Archivo Narrow',sans-serif; font-size:0.85rem;
    color:rgba(245,241,234,0.65); cursor:pointer; transition:background 0.1s,color 0.1s;
}
.cselect-form-opt:hover   { background:rgba(168,85,247,0.12); color:#f5f1ea; }
.cselect-form-opt.sel     { background:rgba(168,85,247,0.18); color:#c084fc; font-weight:700; }
.cselect-form-desc {
    font-family:'Archivo Narrow',sans-serif; font-size:0.65rem;
    color:rgba(245,241,234,0.30); margin-top:4px;
}

.btn-abrir-form {
    display:inline-flex; align-items:center; gap:7px;
    font-family:'Archivo Narrow',sans-serif; font-size:0.625rem; font-weight:700;
    text-transform:uppercase; letter-spacing:0.12em; padding:9px 16px;
    background:rgba(168,85,247,0.15); color:#c084fc; border:1px solid rgba(168,85,247,0.35);
    cursor:pointer; transition:background 0.15s; text-decoration:none;
}
.btn-abrir-form:hover { background:rgba(168,85,247,0.28); }
.btn-submit {
    font-family:'Archivo Narrow',sans-serif; font-size:0.625rem; font-weight:700;
    text-transform:uppercase; letter-spacing:0.12em; padding:10px 20px;
    background:linear-gradient(135deg,#7c3aed,#a855f7); color:#fff; border:none;
    cursor:pointer; transition:opacity 0.15s;
}
.btn-submit:hover { opacity:0.88; }
.btn-cancelar {
    font-family:'Archivo Narrow',sans-serif; font-size:0.625rem; font-weight:700;
    text-transform:uppercase; letter-spacing:0.12em; padding:10px 16px;
    background:transparent; color:rgba(245,241,234,0.35); border:1px solid rgba(245,241,234,0.10);
    cursor:pointer;
}
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
            Añade miembros a tu empresa y asígnales un rol dentro de la plataforma.
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
        <button class="btn-abrir-form" onclick="toggleCrear()">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Añadir miembro
        </button>
    </div>

    {{-- Panel crear usuario --}}
    <div class="crear-panel {{ $errors->any() ? 'open' : '' }}" id="crear-panel">
        <div style="font-family:'Anton',sans-serif;font-size:1.1rem;text-transform:uppercase;letter-spacing:0.02em;color:#f5f1ea;margin-bottom:20px;">
            Nuevo miembro
        </div>
        <form method="POST" action="{{ route('empresa.equipo.store') }}" id="form-crear">
            @csrf
            <div class="crear-grid">
                <div class="form-field">
                    <label class="form-label">Nombre</label>
                    <input class="form-input {{ $errors->has('nombre') ? 'error' : '' }}"
                           type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Nombre">
                </div>
                <div class="form-field">
                    <label class="form-label">Apellido</label>
                    <input class="form-input {{ $errors->has('apellido1') ? 'error' : '' }}"
                           type="text" name="apellido1" value="{{ old('apellido1') }}" placeholder="Apellido">
                </div>
                <div class="form-field">
                    <label class="form-label">Correo electrónico</label>
                    <input class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                           type="email" name="email" value="{{ old('email') }}" placeholder="correo@ejemplo.com">
                </div>
                <div class="form-field">
                    <label class="form-label">Rol</label>
                    <div class="cselect-form" id="csf-rol">
                        <div class="cselect-form-trigger" onclick="toggleCsf('csf-rol')">
                            <span class="csf-val">{{ old('rol') === 'portero' ? 'Portero' : 'Organizador' }}</span>
                            <svg class="cselect-form-arrow" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                        <div class="cselect-form-menu">
                            <div class="cselect-form-opt {{ old('rol','organizador') === 'organizador' ? 'sel' : '' }}" data-val="organizador">Organizador</div>
                            <div class="cselect-form-opt {{ old('rol') === 'portero' ? 'sel' : '' }}" data-val="portero">Portero</div>
                        </div>
                    </div>
                    <input type="hidden" name="rol" id="input-rol" value="{{ old('rol','organizador') }}">
                    <div class="cselect-form-desc" id="desc-rol">
                        @if(old('rol') === 'portero')
                            Solo puede validar entradas QR al acceder a la app.
                        @else
                            Acceso completo al panel de empresa.
                        @endif
                    </div>
                </div>
                <div class="form-field">
                    <label class="form-label">Contraseña</label>
                    <input class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                           type="password" name="password" placeholder="Mínimo 8 caracteres">
                </div>
                <div class="form-field">
                    <label class="form-label">Repetir contraseña</label>
                    <input class="form-input" type="password" name="password_confirmation" placeholder="Repite la contraseña">
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:20px;padding-top:16px;border-top:1px solid rgba(245,241,234,0.07);">
                <button type="submit" class="btn-submit">Crear miembro</button>
                <button type="button" class="btn-cancelar" onclick="toggleCrear()">Cancelar</button>
            </div>
        </form>
    </div>

    {{-- Tabla --}}
    @if($miembros->isEmpty())
        <div class="empty-state">
            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="empty-titulo">Aún no tienes miembros en tu equipo</p>
            <p class="empty-desc">Añade el primer miembro pulsando el botón de arriba.</p>
        </div>
    @else
    <div class="eq-table-wrap">
        <table class="eq-table">
            <thead>
                <tr>
                    <th>Miembro</th>
                    <th>Rol</th>
                    <th>Cambiar rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach($miembros as $miembro)
            @php $usr = $miembro->usuario; @endphp
            <tr>
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
                <td>
                    <form method="POST" action="{{ route('empresa.equipo.rol', $miembro) }}" class="rol-form">
                        @csrf @method('PATCH')
                        <input type="hidden" name="rol" value="{{ $miembro->rol }}">
                        <div class="csinline" onclick="toggleCsInline(this)">
                            <div class="csinline-trigger">
                                <span>{{ $miembro->rol === 'portero' ? 'Portero' : 'Organizador' }}</span>
                                <svg class="csinline-arrow" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
                            </div>
                            <div class="csinline-menu">
                                <div class="csinline-opt {{ $miembro->rol === 'organizador' ? 'sel' : '' }}" data-val="organizador">Organizador</div>
                                <div class="csinline-opt {{ $miembro->rol === 'portero' ? 'sel' : '' }}" data-val="portero">Portero</div>
                            </div>
                        </div>
                        <button type="submit" class="btn-rol-save">Guardar</button>
                    </form>
                </td>
                <td>
                    <form method="POST" action="{{ route('empresa.equipo.destroy', $miembro) }}"
                          onsubmit="return confirm('¿Eliminar a {{ $usr->nombre }} del equipo?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-remove">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Leyenda de roles --}}
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
                Acceso restringido: al iniciar sesión solo puede validar entradas QR en la puerta del evento.
            </p>
        </div>
    </div>

</section>

@endsection

@push('scripts')
<script>
function toggleCrear() {
    var p = document.getElementById('crear-panel');
    p.classList.toggle('open');
}

// Custom select del formulario
function toggleCsf(id) {
    var el = document.getElementById(id);
    el.classList.toggle('open');
}

// Toggle inline rol dropdowns
function toggleCsInline(el) {
    el.classList.toggle('open');
}

document.addEventListener('click', function(e) {
    // Form creation select
    var opt = e.target.closest('.cselect-form-opt');
    if (opt) {
        var cs  = opt.closest('.cselect-form');
        var val = opt.dataset.val;
        cs.querySelectorAll('.cselect-form-opt').forEach(function(o) { o.classList.toggle('sel', o.dataset.val === val); });
        cs.querySelector('.csf-val').textContent = opt.textContent.trim();
        document.getElementById('input-rol').value = val;
        cs.classList.remove('open');
        document.getElementById('desc-rol').textContent = val === 'portero'
            ? 'Solo puede validar entradas QR al acceder a la app.'
            : 'Acceso completo al panel de empresa.';
        return;
    }

    // Inline rol-changer select
    var inlineOpt = e.target.closest('.csinline-opt');
    if (inlineOpt) {
        var cs  = inlineOpt.closest('.csinline');
        var val = inlineOpt.dataset.val;
        cs.querySelectorAll('.csinline-opt').forEach(function(o) { o.classList.toggle('sel', o.dataset.val === val); });
        cs.querySelector('.csinline-trigger span').textContent = inlineOpt.textContent.trim();
        cs.closest('form').querySelector('input[name="rol"]').value = val;
        cs.classList.remove('open');
        return;
    }

    // Close all open dropdowns when clicking outside
    if (!e.target.closest('.cselect-form')) {
        document.querySelectorAll('.cselect-form.open').forEach(function(s) { s.classList.remove('open'); });
    }
    if (!e.target.closest('.csinline')) {
        document.querySelectorAll('.csinline.open').forEach(function(s) { s.classList.remove('open'); });
    }
});
</script>
@endpush

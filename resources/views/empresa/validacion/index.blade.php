@extends('layouts.app')

@section('titulo', 'Validar entradas QR — ' . $empresa->nombre_empresa)

@push('estilos')
<style>
/* ── VIBEZ Validación QR ── */
body { background: #07060c; }

/* ── Layout ── */
.validacion-wrapper {
    max-width: 640px;
    margin: 0 auto;
    padding: 2rem 1rem 5rem;
}

/* ── Hero ── */
.validacion-hero {
    position: relative;
    overflow: hidden;
    background: radial-gradient(circle at 20% 30%, rgba(168,85,247,0.30), transparent 55%),
                radial-gradient(circle at 80% 70%, rgba(124,58,237,0.25), transparent 60%),
                #0d0820;
    border: 1px solid rgba(245,241,234,0.10);
    padding: 2.5rem 1.5rem 2rem;
    text-align: center;
    margin-bottom: 1.5rem;
}
.validacion-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle, rgba(124,58,237,0.16) 1.3px, transparent 1.3px);
    background-size: 28px 28px;
    opacity: 0.5;
    pointer-events: none;
}
.validacion-hero__icono {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 52px; height: 52px;
    background: rgba(168,85,247,0.18);
    margin-bottom: 1rem;
}
.validacion-hero__icono svg { width: 28px; height: 28px; color: #c084fc; }
.validacion-hero h1 {
    position: relative;
    font-family: 'Anton', sans-serif;
    font-size: 2.25rem;
    text-transform: uppercase;
    letter-spacing: -0.005em;
    line-height: 0.9;
    color: #f5f1ea;
    margin: 0 0 0.625rem;
}
.validacion-hero p {
    position: relative;
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 0.6875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.16em;
    color: rgba(245,241,234,0.55);
    margin: 0;
}

/* ── Chip tag ── */
.vibez-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 0.625rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.16em;
    padding: 5px 10px;
    background: rgba(168,85,247,0.16);
    border: 1px solid rgba(168,85,247,0.4);
    color: #c084fc;
    margin-bottom: 14px;
}

/* ── Selector de evento ── */
.evento-selector {
    background: #0d0a18;
    border: 1px solid rgba(245,241,234,0.10);
    padding: 18px 20px;
    margin-bottom: 1.5rem;
}
.evento-selector label {
    display: block;
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 0.625rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.16em;
    color: rgba(245,241,234,0.55);
    margin-bottom: 8px;
}
.ev-csel { position:relative; width:100%; }
.ev-csel-trigger {
    display:flex; align-items:center; justify-content:space-between; gap:8px;
    background:transparent; border:none; border-bottom:1.5px solid rgba(245,241,234,0.18);
    color:#f5f1ea; padding:6px 4px 6px 0;
    font-family:'Archivo',sans-serif; font-size:1rem;
    cursor:pointer; user-select:none; transition:border-color 0.15s; width:100%;
}
.ev-csel.open .ev-csel-trigger { border-bottom-color:#a855f7; }
.ev-csel-arrow { width:12px;height:12px;flex-shrink:0;color:#c084fc;transition:transform 0.15s; }
.ev-csel.open .ev-csel-arrow { transform:rotate(180deg); }
.ev-csel-menu {
    display:none; position:absolute; top:calc(100% + 4px); left:0; right:0; z-index:200;
    background:#0d0a18; border:1px solid rgba(168,85,247,0.30);
    max-height:220px; overflow-y:auto;
}
.ev-csel.open .ev-csel-menu { display:block; }
.ev-csel-opt {
    padding:10px 14px; font-family:'Archivo',sans-serif; font-size:0.9rem;
    color:rgba(245,241,234,0.65); cursor:pointer; transition:background 0.12s;
}
.ev-csel-opt:hover { background:rgba(168,85,247,0.12); color:#f5f1ea; }
.ev-csel-opt.sel { background:rgba(168,85,247,0.18); color:#c084fc; font-weight:600; }

/* ── Pestañas modo ── */
.modo-tabs {
    display: flex;
    gap: 8px;
    margin-bottom: 1.25rem;
}
.modo-tab {
    flex: 1;
    padding: 10px;
    border: 1px solid rgba(245,241,234,0.10);
    background: transparent;
    color: rgba(245,241,234,0.55);
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 0.6875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.16em;
    cursor: pointer;
    transition: color 0.15s, border-color 0.15s, background 0.15s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.modo-tab:hover { color: #f5f1ea; border-color: rgba(245,241,234,0.30); }
.modo-tab.activo {
    background: #a855f7;
    border-color: #a855f7;
    color: #f5f1ea;
}
.modo-tab svg { width: 15px; height: 15px; }

/* ── Panel cámara ── */
.panel-camara { display: block; }
.panel-manual { display: none; }

#qr-reader {
    width: 100%;
    overflow: hidden;
    border: 1px solid rgba(245,241,234,0.10);
}
/* html5-qrcode UI overrides */
#qr-reader__scan_region { background: #000 !important; }
#qr-reader__dashboard {
    background: #0d0a18 !important;
    border-top: 1px solid rgba(245,241,234,0.10) !important;
    padding: 12px !important;
}
#qr-reader__dashboard_section_swaplink { color: #c084fc !important; font-size: 0.75rem !important; }
#qr-reader__camera_permission_button,
#qr-reader__dashboard_section_csr button {
    font-family: 'Anton', sans-serif !important;
    font-size: 0.8125rem !important;
    text-transform: uppercase !important;
    letter-spacing: -0.005em !important;
    background: #a855f7 !important;
    color: #f5f1ea !important;
    border: none !important;
    border-radius: 0 !important;
    padding: 10px 20px !important;
    cursor: pointer !important;
}
#qr-reader__camera_selection {
    background: transparent !important;
    border: 1px solid rgba(245,241,234,0.10) !important;
    color: #f5f1ea !important;
    font-family: 'Archivo Narrow', sans-serif !important;
    font-size: 0.75rem !important;
    padding: 6px 10px !important;
}

/* ── Panel manual ── */
.manual-form {
    background: #0d0a18;
    border: 1px solid rgba(245,241,234,0.10);
    padding: 22px;
}
.manual-form label {
    display: block;
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 0.625rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.16em;
    color: rgba(245,241,234,0.55);
    margin-bottom: 12px;
}
.manual-input-row {
    display: flex;
    gap: 8px;
    align-items: flex-end;
}
.manual-input-row input {
    flex: 1;
    background: transparent;
    border: none;
    border-bottom: 1.5px solid rgba(245,241,234,0.18);
    color: #f5f1ea;
    padding: 6px 0;
    font-family: 'JetBrains Mono', ui-monospace, monospace;
    font-size: 0.9375rem;
    outline: none;
    transition: border-color 0.2s;
}
.manual-input-row input::placeholder { color: rgba(245,241,234,0.25); }
.manual-input-row input:focus { border-bottom-color: #a855f7; }
.btn-validar-manual {
    font-family: 'Anton', sans-serif;
    font-size: 0.8125rem;
    text-transform: uppercase;
    letter-spacing: -0.005em;
    background: #a855f7;
    color: #f5f1ea;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    white-space: nowrap;
    flex-shrink: 0;
    transition: background 0.15s;
}
.btn-validar-manual:hover { background: #c084fc; color: #07060c; }
.btn-validar-manual:disabled { opacity: 0.4; cursor: not-allowed; }

/* ── Resultado ── */
.resultado-card {
    margin-top: 1.5rem;
    padding: 24px;
    display: none;
    animation: slideUp 0.3s ease;
}
@keyframes slideUp {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}
.resultado-card.ok    { background: rgba(52,211,153,0.10);  border: 2px solid #34d399; }
.resultado-card.error { background: rgba(248,113,113,0.10); border: 2px solid #f87171; }
.resultado-card.warn  { background: rgba(245,158,11,0.10);  border: 2px solid #f59e0b; }

.resultado-icono {
    width: 48px; height: 48px;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 14px;
}
.resultado-icono svg { width: 28px; height: 28px; }
.resultado-card.ok    .resultado-icono { background: rgba(52,211,153,0.20);  color: #34d399; }
.resultado-card.error .resultado-icono { background: rgba(248,113,113,0.20); color: #f87171; }
.resultado-card.warn  .resultado-icono { background: rgba(245,158,11,0.20);  color: #f59e0b; }

.resultado-titulo {
    font-family: 'Anton', sans-serif;
    text-transform: uppercase;
    letter-spacing: -0.005em;
    line-height: 0.9;
    text-align: center;
    font-size: 1.5rem;
    margin: 0 0 14px;
}
.resultado-card.ok    .resultado-titulo { color: #34d399; }
.resultado-card.error .resultado-titulo { color: #f87171; }
.resultado-card.warn  .resultado-titulo { color: #f59e0b; }

.resultado-body { display: flex; flex-direction: column; gap: 0; }
.resultado-fila {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.875rem;
    border-top: 1px dashed rgba(245,241,234,0.10);
    padding: 10px 0;
}
.resultado-fila:first-child { border-top: none; padding-top: 0; }
.resultado-fila .lbl {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 0.625rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.16em;
    color: rgba(245,241,234,0.40);
}
.resultado-fila .val {
    color: #f5f1ea;
    font-weight: 600;
    text-align: right;
    max-width: 65%;
    font-size: 0.9375rem;
}
.resultado-error-msg {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 0.6875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    text-align: center;
    color: rgba(245,241,234,0.45);
    margin: 10px 0 0;
    line-height: 1.5;
    text-transform: none;
    letter-spacing: 0;
}

/* ── Historial de scans ── */
.historial-titulo {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 0.625rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.16em;
    color: rgba(245,241,234,0.40);
    margin: 2rem 0 0.75rem;
}
.historial-lista { display: flex; flex-direction: column; gap: 6px; }
.historial-item {
    display: flex;
    align-items: center;
    gap: 12px;
    background: #0d0a18;
    border: 1px solid rgba(245,241,234,0.10);
    padding: 12px 16px;
    font-size: 0.8125rem;
    transition: border-color 0.15s;
}
.historial-item:hover { border-color: rgba(245,241,234,0.20); }
.historial-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.historial-dot.ok    { background: #34d399; }
.historial-dot.error { background: #f87171; }
.historial-dot.warn  { background: #f59e0b; }
.historial-nombre {
    color: #f5f1ea;
    font-weight: 600;
    flex: 1;
    min-width: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.historial-hora {
    font-family: 'JetBrains Mono', ui-monospace, monospace;
    font-size: 0.6875rem;
    color: rgba(245,241,234,0.40);
    white-space: nowrap;
}

/* ── Spinner (validando…) ── */
.spin {
    display: inline-block;
    width: 18px; height: 18px;
    border: 2px solid rgba(168,85,247,0.3);
    border-top-color: #a855f7;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
    vertical-align: middle;
}
@keyframes spin { to { transform: rotate(360deg); } }
</style>
@endpush

@section('content')

@include('partials.home.nav')

<div class="validacion-wrapper">

    {{-- Hero --}}
    <div class="validacion-hero">
        <div style="display:inline-flex;align-items:center;justify-content:center;width:52px;height:52px;background:rgba(124,58,237,0.2);border-radius:50%;margin-bottom:0.875rem;">
            <svg style="width:28px;height:28px;color:#a78bfa" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
            </svg>
        </div>
        <h1>Validar entradas QR</h1>
        <p>{{ $empresa->nombre_empresa }} · Escanea o introduce el código para validar el acceso</p>
    </div>

    {{-- Selector de evento (opcional, solo para referencia visual) --}}
    @if($eventos->count() > 1)
    <div class="evento-selector">
        <label>Evento a validar</label>
        <input type="hidden" id="filtro-evento" value="">
        <div class="ev-csel" id="ev-csel-main">
            <div class="ev-csel-trigger" onclick="toggleEvCsel()">
                <span id="ev-csel-label">— Todos los eventos activos —</span>
                <svg class="ev-csel-arrow" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
            </div>
            <div class="ev-csel-menu">
                <div class="ev-csel-opt sel" data-val="">— Todos los eventos activos —</div>
                @foreach($eventos as $ev)
                    <div class="ev-csel-opt" data-val="{{ $ev->id }}">{{ $ev->titulo }}</div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Pestañas de modo --}}
    <div class="modo-tabs">
        <button class="modo-tab activo" id="tab-camara" onclick="cambiarModo('camara')">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Cámara
        </button>
        <button class="modo-tab" id="tab-manual" onclick="cambiarModo('manual')">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Manual
        </button>
    </div>

    {{-- Panel cámara --}}
    <div class="panel-camara" id="panel-camara">
        <div id="qr-reader"></div>
    </div>

    {{-- Panel manual --}}
    <div class="panel-manual" id="panel-manual">
        <div class="manual-form">
            <label for="input-qr">Apunta aquí con el lector o escribe el código</label>
            <div class="manual-input-row">
                <input type="text"
                       id="input-qr"
                       placeholder="Escanea con el lector o introduce el UUID…"
                       autocomplete="off"
                       autocorrect="off"
                       spellcheck="false"
                       onkeydown="if(event.key==='Enter') validarManual()">
                <button class="btn-validar-manual" id="btn-manual" onclick="validarManual()">
                    Validar
                </button>
            </div>
        </div>
    </div>

    {{-- Resultado --}}
    <div class="resultado-card" id="resultado-card">
        <div class="resultado-icono" id="resultado-icono"></div>
        <p class="resultado-titulo" id="resultado-titulo"></p>
        <div class="resultado-body" id="resultado-body"></div>
        <p class="resultado-error-msg" id="resultado-msg"></p>
    </div>

    {{-- Historial de escaneos de esta sesión --}}
    <p class="historial-titulo" id="historial-label" style="display:none">Escaneos de esta sesión</p>
    <div class="historial-lista" id="historial-lista"></div>

</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
var csrfToken      = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
var validarUrl     = '{{ route('empresa.validacion.validar') }}';
var escaneando     = false;
var cooldown       = false;
var historial      = [];
var html5QrScanner = null;
var modoCamara     = true;

/* ════════════════════════ MODO ════════════════════════ */

function cambiarModo(modo) {
    modoCamara = (modo === 'camara');
    document.getElementById('tab-camara').classList.toggle('activo', modoCamara);
    document.getElementById('tab-manual').classList.toggle('activo', !modoCamara);
    document.getElementById('panel-camara').style.display = modoCamara ? 'block' : 'none';
    document.getElementById('panel-manual').style.display = modoCamara ? 'none' : 'block';

    if (modoCamara) {
        iniciarEscaner();
    } else {
        if (html5QrScanner) {
            html5QrScanner.clear().catch(function() {});
        }
        // Dar foco al input para que el lector físico escriba directamente
        setTimeout(function() {
            document.getElementById('input-qr').focus();
        }, 50);
    }
}

/* ════════════════════════ CÁMARA ════════════════════════ */

function iniciarEscaner() {
    if (html5QrScanner) {
        html5QrScanner.clear().catch(function() {});
    }

    html5QrScanner = new Html5QrcodeScanner(
        'qr-reader',
        {
            fps: 10,
            qrbox: { width: 260, height: 260 },
            rememberLastUsedCamera: true,
            showTorchButtonIfSupported: true,
        },
        false
    );

    html5QrScanner.render(
        function(decodedText) {
            if (!cooldown) procesarCodigo(decodedText);
        },
        function() {}  // fallos de frame son normales, no hacemos nada
    );
}

// Arrancar escáner al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    iniciarEscaner();
});

/* ════════════════════════ MANUAL ════════════════════════ */

function validarManual() {
    var codigo = document.getElementById('input-qr').value.trim();
    if (!codigo) return;
    procesarCodigo(codigo);
}

/* ════════════════════════ LÓGICA PRINCIPAL ════════════════════════ */

function procesarCodigo(codigo) {
    if (cooldown) return;
    cooldown = true;
    mostrarCargando();

    fetch(validarUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept':       'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({ codigo_qr: codigo }),
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        mostrarResultado(data);
        agregarHistorial(data);
        // Cooldown de 3 s para evitar doble lectura involuntaria
        setTimeout(function() {
            cooldown = false;
            ocultarCargando();
            // Devolver el foco al input en modo manual para la siguiente lectura
            if (!modoCamara) {
                document.getElementById('input-qr').focus();
            }
        }, 3000);
    })
    .catch(function() {
        mostrarError('Error de conexión. Revisa tu red e inténtalo de nuevo.');
        setTimeout(function() { cooldown = false; ocultarCargando(); }, 3000);
    });
}

/* ════════════════════════ UI — RESULTADO ════════════════════════ */

function mostrarCargando() {
    var card   = document.getElementById('resultado-card');
    var icono  = document.getElementById('resultado-icono');
    var titulo = document.getElementById('resultado-titulo');
    var body   = document.getElementById('resultado-body');
    var msg    = document.getElementById('resultado-msg');

    card.className   = 'resultado-card'; // quitar clases anteriores
    card.style.display = 'block';
    card.style.borderColor = 'rgba(139,92,246,0.4)';
    card.style.background  = 'rgba(124,58,237,0.08)';
    icono.innerHTML  = '<span class="spin"></span>';
    titulo.textContent = 'Validando…';
    titulo.style.color = '#a78bfa';
    body.innerHTML   = '';
    msg.textContent  = '';
}

function ocultarCargando() {
    // No hace nada aquí — la pantalla de resultado ya se mostró
}

function mostrarResultado(data) {
    var card   = document.getElementById('resultado-card');
    var icono  = document.getElementById('resultado-icono');
    var titulo = document.getElementById('resultado-titulo');
    var body   = document.getElementById('resultado-body');
    var msg    = document.getElementById('resultado-msg');

    card.style.borderColor = '';
    card.style.background  = '';

    if (data.ok) {
        card.className    = 'resultado-card ok';
        icono.innerHTML   = svgCheck();
        titulo.textContent = '¡Entrada válida!';
        body.innerHTML    = filaHtml('Asistente', data.nombre) + filaHtml('Evento', data.evento);
        msg.textContent   = '';
        document.getElementById('input-qr').value = '';

    } else if (data.tipo === 'ya_usada') {
        card.className    = 'resultado-card error';
        icono.innerHTML   = svgX();
        titulo.textContent = 'Entrada ya utilizada';
        body.innerHTML    = filaHtml('Asistente', data.nombre || '—')
                           + filaHtml('Evento', data.evento || '—')
                           + (data.fecha_uso ? filaHtml('Usada el', data.fecha_uso) : '');
        msg.textContent   = 'Esta entrada ha quedado invalidada y no permite el acceso.';

    } else if (data.tipo === 'cancelada') {
        card.className    = 'resultado-card error';
        icono.innerHTML   = svgX();
        titulo.textContent = 'Entrada cancelada';
        body.innerHTML    = filaHtml('Evento', data.evento || '—');
        msg.textContent   = data.error || '';

    } else if (data.tipo === 'no_encontrada') {
        card.className    = 'resultado-card error';
        icono.innerHTML   = svgX();
        titulo.textContent = 'QR no reconocido';
        body.innerHTML    = '';
        msg.textContent   = 'El código no existe en el sistema. Verifica que sea una entrada válida de VIBEZ.';

    } else if (data.tipo === 'no_autorizada') {
        card.className    = 'resultado-card warn';
        icono.innerHTML   = svgWarn();
        titulo.textContent = 'Entrada de otro evento';
        body.innerHTML    = '';
        msg.textContent   = 'Esta entrada no pertenece a ninguno de tus eventos.';

    } else {
        mostrarError(data.error || 'Error desconocido.');
        return;
    }

    card.style.display = 'block';
    card.style.animation = 'none';
    setTimeout(function() { card.style.animation = ''; }, 10);
}

function mostrarError(texto) {
    var card   = document.getElementById('resultado-card');
    var icono  = document.getElementById('resultado-icono');
    var titulo = document.getElementById('resultado-titulo');
    var body   = document.getElementById('resultado-body');
    var msg    = document.getElementById('resultado-msg');

    card.className     = 'resultado-card error';
    card.style.display = 'block';
    icono.innerHTML    = svgX();
    titulo.textContent = 'Error';
    body.innerHTML     = '';
    msg.textContent    = texto;
}

function filaHtml(lbl, val) {
    return '<div class="resultado-fila">'
         + '<span class="lbl">' + lbl + '</span>'
         + '<span class="val">' + escHtml(val) + '</span>'
         + '</div>';
}

/* ════════════════════════ HISTORIAL ════════════════════════ */

function agregarHistorial(data) {
    var tipo   = data.ok ? 'ok' : (data.tipo === 'ya_usada' || data.tipo === 'cancelada' ? 'error' : 'warn');
    var nombre = data.ok ? data.nombre : (data.nombre || data.tipo);
    var hora   = new Date().toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

    historial.unshift({ tipo: tipo, nombre: nombre, hora: hora });
    if (historial.length > 10) historial.pop();
    renderHistorial();
}

function renderHistorial() {
    var lista  = document.getElementById('historial-lista');
    var label  = document.getElementById('historial-label');
    if (historial.length === 0) { label.style.display = 'none'; lista.innerHTML = ''; return; }

    label.style.display = '';
    lista.innerHTML = historial.map(function(item) {
        return '<div class="historial-item">'
             + '<span class="historial-dot ' + item.tipo + '"></span>'
             + '<span class="historial-nombre">' + escHtml(item.nombre) + '</span>'
             + '<span class="historial-hora">' + item.hora + '</span>'
             + '</div>';
    }).join('');
}

/* ════════════════════════ HELPERS ════════════════════════ */

function escHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function svgCheck() {
    return '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';
}
function svgX() {
    return '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>';
}
function svgWarn() {
    return '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>';
}

// Custom evento selector
function toggleEvCsel() {
    document.getElementById('ev-csel-main').classList.toggle('open');
}
document.addEventListener('click', function(e) {
    var opt = e.target.closest('.ev-csel-opt');
    if (opt) {
        var cs = opt.closest('.ev-csel');
        var val = opt.dataset.val;
        cs.querySelectorAll('.ev-csel-opt').forEach(function(o) { o.classList.toggle('sel', o.dataset.val === val); });
        document.getElementById('ev-csel-label').textContent = opt.textContent.trim();
        document.getElementById('filtro-evento').value = val;
        cs.classList.remove('open');
        return;
    }
    if (!e.target.closest('.ev-csel')) {
        var el = document.getElementById('ev-csel-main');
        if (el) el.classList.remove('open');
    }
});
</script>
@endpush

<?php $__env->startSection('titulo', 'Validar entradas QR — ' . $empresa->nombre_empresa); ?>

<?php $__env->startPush('estilos'); ?>
<style>
/* ── Variables reutilizadas del sistema VIBEZ ── */
:root {
    --scanner-ok:   #10b981;
    --scanner-err:  #ef4444;
    --scanner-warn: #f59e0b;
}

/* ── Layout ── */
.validacion-wrapper {
    max-width: 640px;
    margin: 0 auto;
    padding: 1.5rem 1rem 4rem;
}

/* ── Hero ── */
.validacion-hero {
    background: linear-gradient(135deg, #1e1b4b, #312e81);
    border-radius: 1.25rem;
    padding: 1.75rem 1.5rem;
    text-align: center;
    margin-bottom: 1.5rem;
    border: 1px solid rgba(139,92,246,0.3);
}
.validacion-hero h1 {
    font-size: 1.4rem;
    font-weight: 900;
    color: #fff;
    margin: 0 0 0.25rem;
}
.validacion-hero p {
    color: rgba(255,255,255,0.6);
    font-size: 0.875rem;
    margin: 0;
}

/* ── Selector de evento ── */
.evento-selector {
    background: var(--card-bg, #1e1b3a);
    border: 1px solid rgba(139,92,246,0.2);
    border-radius: 0.875rem;
    padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
}
.evento-selector label {
    display: block;
    font-size: 0.75rem;
    font-weight: 700;
    color: #a78bfa;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-bottom: 0.5rem;
}
.evento-selector select {
    width: 100%;
    background: rgba(0,0,0,0.3);
    border: 1px solid rgba(139,92,246,0.25);
    border-radius: 0.5rem;
    color: #e2e8f0;
    padding: 0.6rem 0.875rem;
    font-size: 0.925rem;
    appearance: none;
    -webkit-appearance: none;
    cursor: pointer;
}
.evento-selector select:focus {
    outline: none;
    border-color: #7c3aed;
    box-shadow: 0 0 0 3px rgba(124,58,237,0.2);
}

/* ── Pestañas modo ── */
.modo-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1.25rem;
}
.modo-tab {
    flex: 1;
    padding: 0.6rem;
    border-radius: 0.625rem;
    border: 1px solid rgba(139,92,246,0.25);
    background: transparent;
    color: #94a3b8;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
}
.modo-tab.activo,
.modo-tab:hover {
    background: rgba(124,58,237,0.15);
    border-color: #7c3aed;
    color: #a78bfa;
}
.modo-tab svg { width: 16px; height: 16px; }

/* ── Panel cámara ── */
.panel-camara { display: block; }
.panel-manual { display: none; }

#qr-reader {
    width: 100%;
    border-radius: 0.875rem;
    overflow: hidden;
    border: 2px solid rgba(139,92,246,0.3);
}
/* Personalizar la UI generada por html5-qrcode */
#qr-reader__scan_region { background: #000 !important; }
#qr-reader__dashboard { background: var(--card-bg, #1e1b3a) !important; border-top: 1px solid rgba(139,92,246,0.2) !important; }
#qr-reader__dashboard_section_swaplink { color: #a78bfa !important; font-size: 0.8rem; }
#qr-reader__camera_permission_button,
#qr-reader__dashboard_section_csr button {
    background: linear-gradient(135deg,#7c3aed,#6d28d9) !important;
    color: #fff !important;
    border: none !important;
    border-radius: 0.5rem !important;
    padding: 0.5rem 1.25rem !important;
    font-weight: 700 !important;
    cursor: pointer !important;
}

/* ── Panel manual ── */
.manual-form {
    background: var(--card-bg, #1e1b3a);
    border: 1px solid rgba(139,92,246,0.2);
    border-radius: 0.875rem;
    padding: 1.25rem;
}
.manual-form label {
    display: block;
    font-size: 0.75rem;
    font-weight: 700;
    color: #a78bfa;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-bottom: 0.5rem;
}
.manual-input-row {
    display: flex;
    gap: 0.5rem;
}
.manual-input-row input {
    flex: 1;
    background: rgba(0,0,0,0.3);
    border: 1px solid rgba(139,92,246,0.25);
    border-radius: 0.5rem;
    color: #e2e8f0;
    padding: 0.65rem 0.875rem;
    font-size: 0.9rem;
    font-family: monospace;
}
.manual-input-row input:focus {
    outline: none;
    border-color: #7c3aed;
    box-shadow: 0 0 0 3px rgba(124,58,237,0.2);
}
.btn-validar-manual {
    background: linear-gradient(135deg,#7c3aed,#6d28d9);
    color: #fff;
    border: none;
    border-radius: 0.5rem;
    padding: 0 1.25rem;
    font-weight: 700;
    font-size: 0.9rem;
    cursor: pointer;
    white-space: nowrap;
    transition: opacity 0.2s;
}
.btn-validar-manual:hover { opacity: 0.88; }
.btn-validar-manual:disabled { opacity: 0.5; cursor: not-allowed; }

/* ── Resultado ── */
.resultado-card {
    margin-top: 1.5rem;
    border-radius: 1rem;
    padding: 1.25rem 1.5rem;
    display: none;
    animation: slideUp 0.3s ease;
}
@keyframes slideUp {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}
.resultado-card.ok {
    background: rgba(16,185,129,0.1);
    border: 2px solid #10b981;
}
.resultado-card.error {
    background: rgba(239,68,68,0.1);
    border: 2px solid #ef4444;
}
.resultado-card.warn {
    background: rgba(245,158,11,0.1);
    border: 2px solid #f59e0b;
}
.resultado-icono {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.875rem;
}
.resultado-icono svg { width: 28px; height: 28px; }
.resultado-card.ok  .resultado-icono { background: rgba(16,185,129,0.2); color: #10b981; }
.resultado-card.error .resultado-icono { background: rgba(239,68,68,0.2); color: #ef4444; }
.resultado-card.warn  .resultado-icono { background: rgba(245,158,11,0.2); color: #f59e0b; }
.resultado-titulo {
    text-align: center;
    font-size: 1.1rem;
    font-weight: 800;
    margin: 0 0 0.5rem;
}
.resultado-card.ok    .resultado-titulo { color: #10b981; }
.resultado-card.error .resultado-titulo { color: #ef4444; }
.resultado-card.warn  .resultado-titulo { color: #f59e0b; }
.resultado-body {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}
.resultado-fila {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.875rem;
    border-top: 1px solid rgba(255,255,255,0.06);
    padding-top: 0.4rem;
}
.resultado-fila:first-child { border-top: none; padding-top: 0; }
.resultado-fila .lbl { color: #94a3b8; font-size: 0.78rem; }
.resultado-fila .val { color: #e2e8f0; font-weight: 600; text-align: right; max-width: 65%; }
.resultado-error-msg {
    text-align: center;
    font-size: 0.875rem;
    color: #94a3b8;
    margin: 0.4rem 0 0;
}

/* ── Historial de scans ── */
.historial-titulo {
    font-size: 0.75rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin: 2rem 0 0.75rem;
}
.historial-lista {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}
.historial-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: var(--card-bg, #1e1b3a);
    border: 1px solid rgba(139,92,246,0.12);
    border-radius: 0.625rem;
    padding: 0.625rem 0.875rem;
    font-size: 0.825rem;
}
.historial-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}
.historial-dot.ok   { background: #10b981; }
.historial-dot.error{ background: #ef4444; }
.historial-dot.warn { background: #f59e0b; }
.historial-nombre { color: #e2e8f0; font-weight: 600; flex: 1; min-width: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.historial-hora { color: #64748b; font-size: 0.75rem; white-space: nowrap; }

/* ── Spinner ── */
.spin {
    display: inline-block;
    width: 16px; height: 16px;
    border: 2px solid rgba(255,255,255,0.3);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
    vertical-align: middle;
}
@keyframes spin { to { transform: rotate(360deg); } }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('contenido'); ?>

<div class="validacion-wrapper">

    
    <div class="validacion-hero">
        <div style="display:inline-flex;align-items:center;justify-content:center;width:52px;height:52px;background:rgba(124,58,237,0.2);border-radius:50%;margin-bottom:0.875rem;">
            <svg style="width:28px;height:28px;color:#a78bfa" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
            </svg>
        </div>
        <h1>Validar entradas QR</h1>
        <p><?php echo e($empresa->nombre_empresa); ?> · Escanea o introduce el código para validar el acceso</p>
    </div>

    
    <?php if($eventos->count() > 1): ?>
    <div class="evento-selector">
        <label for="filtro-evento">Evento a validar</label>
        <select id="filtro-evento">
            <option value="">— Todos los eventos activos —</option>
            <?php $__currentLoopData = $eventos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($ev->id); ?>"><?php echo e($ev->titulo); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <?php endif; ?>

    
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

    
    <div class="panel-camara" id="panel-camara">
        <div id="qr-reader"></div>
    </div>

    
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

    
    <div class="resultado-card" id="resultado-card">
        <div class="resultado-icono" id="resultado-icono"></div>
        <p class="resultado-titulo" id="resultado-titulo"></p>
        <div class="resultado-body" id="resultado-body"></div>
        <p class="resultado-error-msg" id="resultado-msg"></p>
    </div>

    
    <p class="historial-titulo" id="historial-label" style="display:none">Escaneos de esta sesión</p>
    <div class="historial-lista" id="historial-lista"></div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
var csrfToken      = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
var validarUrl     = '<?php echo e(route('empresa.validacion.validar')); ?>';
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
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/empresa/validacion/index.blade.php ENDPATH**/ ?>
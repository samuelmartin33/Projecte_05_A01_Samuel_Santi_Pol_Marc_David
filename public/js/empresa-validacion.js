/**
 * empresa-validacion.js
 * Lógica completa del escáner QR para validación de entradas.
 * Depende de window.VALIDACION_URL (ruta POST definida en el blade).
 */

var csrfToken      = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
var validarUrl     = window.VALIDACION_URL;
var escaneando     = false;
var cooldown       = false;
var historial      = [];
var html5QrScanner = null;
var modoCamara     = true;

/* ════════════════════════ MODO ════════════════════════ */

/**
 * Cambia entre modo cámara y modo manual.
 * @param {string} modo - 'camara' | 'manual'
 */
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
        /* Dar foco al input para que el lector físico escriba directamente */
        setTimeout(function() {
            document.getElementById('input-qr').focus();
        }, 50);
    }
}

/* ════════════════════════ CÁMARA ════════════════════════ */

/**
 * Inicializa el escáner de cámara html5-qrcode.
 * Al decodificar un QR llama a procesarCodigo().
 */
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
        function() {}  /* fallos de frame son normales */
    );
}

/* Arrancar escáner al cargar (el script está al final del body, DOM ya disponible) */
iniciarEscaner();

/* ════════════════════════ MANUAL ════════════════════════ */

/**
 * Lee el código del input de texto y lo procesa.
 */
function validarManual() {
    var codigo = document.getElementById('input-qr').value.trim();
    if (!codigo) return;
    procesarCodigo(codigo);
}

/* ════════════════════════ LÓGICA PRINCIPAL ════════════════════════ */

/**
 * Envía el código QR al servidor y gestiona la respuesta.
 * Aplica cooldown de 3s para evitar dobles lecturas.
 * @param {string} codigo - UUID o código escaneado/introducido
 */
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
        /* Cooldown de 3s para evitar doble lectura involuntaria */
        setTimeout(function() {
            cooldown = false;
            ocultarCargando();
            /* Devolver el foco al input en modo manual para la siguiente lectura */
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

/** Muestra el indicador de carga mientras se procesa el QR. */
function mostrarCargando() {
    var card   = document.getElementById('resultado-card');
    var icono  = document.getElementById('resultado-icono');
    var titulo = document.getElementById('resultado-titulo');
    var body   = document.getElementById('resultado-body');
    var msg    = document.getElementById('resultado-msg');

    card.className          = 'resultado-card';
    card.style.display      = 'block';
    card.style.borderColor  = 'rgba(139,92,246,0.4)';
    card.style.background   = 'rgba(124,58,237,0.08)';
    icono.innerHTML         = '<span class="spin"></span>';
    titulo.textContent      = 'Validando…';
    titulo.style.color      = '#a78bfa';
    body.innerHTML          = '';
    msg.textContent         = '';
}

/** No hace nada aquí — la pantalla de resultado ya se mostró. */
function ocultarCargando() {}

/**
 * Pinta el resultado de la validación según el tipo de respuesta del servidor.
 * @param {Object} data - JSON devuelto por el endpoint
 */
function mostrarResultado(data) {
    var card   = document.getElementById('resultado-card');
    var icono  = document.getElementById('resultado-icono');
    var titulo = document.getElementById('resultado-titulo');
    var body   = document.getElementById('resultado-body');
    var msg    = document.getElementById('resultado-msg');

    card.style.borderColor = '';
    card.style.background  = '';

    if (data.ok) {
        card.className     = 'resultado-card ok';
        icono.innerHTML    = svgCheck();
        titulo.textContent = '¡Entrada válida!';
        body.innerHTML     = filaHtml('Asistente', data.nombre) + filaHtml('Evento', data.evento);
        msg.textContent    = '';
        document.getElementById('input-qr').value = '';

    } else if (data.tipo === 'ya_usada') {
        card.className     = 'resultado-card error';
        icono.innerHTML    = svgX();
        titulo.textContent = 'Entrada ya utilizada';
        body.innerHTML     = filaHtml('Asistente', data.nombre || '—')
                           + filaHtml('Evento', data.evento || '—')
                           + (data.fecha_uso ? filaHtml('Usada el', data.fecha_uso) : '');
        msg.textContent    = 'Esta entrada ha quedado invalidada y no permite el acceso.';

    } else if (data.tipo === 'cancelada') {
        card.className     = 'resultado-card error';
        icono.innerHTML    = svgX();
        titulo.textContent = 'Entrada cancelada';
        body.innerHTML     = filaHtml('Evento', data.evento || '—');
        msg.textContent    = data.error || '';

    } else if (data.tipo === 'no_encontrada') {
        card.className     = 'resultado-card error';
        icono.innerHTML    = svgX();
        titulo.textContent = 'QR no reconocido';
        body.innerHTML     = '';
        msg.textContent    = 'El código no existe en el sistema. Verifica que sea una entrada válida de VIBEZ.';

    } else if (data.tipo === 'no_autorizada') {
        card.className     = 'resultado-card warn';
        icono.innerHTML    = svgWarn();
        titulo.textContent = 'Entrada de otro evento';
        body.innerHTML     = '';
        msg.textContent    = 'Esta entrada no pertenece a ninguno de tus eventos.';

    } else {
        mostrarError(data.error || 'Error desconocido.');
        return;
    }

    card.style.display    = 'block';
    card.style.animation  = 'none';
    setTimeout(function() { card.style.animation = ''; }, 10);
}

/**
 * Muestra un mensaje de error genérico en la tarjeta de resultado.
 * @param {string} texto
 */
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

/**
 * Genera el HTML de una fila de detalle del resultado.
 * @param {string} lbl - Etiqueta
 * @param {string} val - Valor
 * @returns {string}
 */
function filaHtml(lbl, val) {
    return '<div class="resultado-fila">'
         + '<span class="lbl">' + lbl + '</span>'
         + '<span class="val">' + escHtml(val) + '</span>'
         + '</div>';
}

/* ════════════════════════ HISTORIAL ════════════════════════ */

/**
 * Añade una entrada al historial de sesión (máximo 10).
 * @param {Object} data
 */
function agregarHistorial(data) {
    var tipo   = data.ok ? 'ok' : (data.tipo === 'ya_usada' || data.tipo === 'cancelada' ? 'error' : 'warn');
    var nombre = data.ok ? data.nombre : (data.nombre || data.tipo);
    var hora   = new Date().toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

    historial.unshift({ tipo: tipo, nombre: nombre, hora: hora });
    if (historial.length > 10) historial.pop();
    renderHistorial();
}

/** Vuelve a pintar la lista de historial en el DOM. */
function renderHistorial() {
    var lista = document.getElementById('historial-lista');
    var label = document.getElementById('historial-label');
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

/* ════════════════════════ SELECTOR DE EVENTO ════════════════════════ */

/** Abre/cierra el selector de evento. */
function toggleEvCsel() {
    document.getElementById('ev-csel-main').classList.toggle('open');
}

/**
 * Selecciona un evento del dropdown y filtra los escaneos.
 * @param {HTMLElement} el  - Opción seleccionada
 * @param {string}      val - ID del evento o '' para todos
 */
function seleccionarEvento(el, val) {
    var cs = document.getElementById('ev-csel-main');
    cs.querySelectorAll('.ev-csel-opt').forEach(function(o) {
        o.classList.toggle('sel', o.dataset.val === val);
    });
    document.getElementById('ev-csel-label').textContent = el.textContent.trim();
    document.getElementById('filtro-evento').value = val;
    cs.classList.remove('open');
}

/* ════════════════════════ HELPERS ════════════════════════ */

/**
 * Escapa caracteres HTML para evitar XSS al insertar texto del servidor en el DOM.
 * @param {string} str
 * @returns {string}
 */
function escHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g,  '&amp;')
        .replace(/</g,  '&lt;')
        .replace(/>/g,  '&gt;')
        .replace(/"/g,  '&quot;');
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

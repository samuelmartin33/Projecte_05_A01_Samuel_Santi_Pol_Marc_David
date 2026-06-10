/**
 * canje-bono.js — Escáner QR para canjear bonos de bebidas.
 *
 * Patrón del proyecto: funciones globales, sin addEventListener.
 * Usa html5-qrcode (misma librería que empresa/validacion/index).
 *
 * Flujo:
 *  1. Camarero elige tipo (Cocktail/Destilado/Sin alcohol) y cantidad
 *  2. Escanea QR con cámara trasera (o introduce manual)
 *  3. POST a /empresa/bonos/validar-canje → resultado en pantalla
 *  4. Historial de canjes de la sesión actualizado
 */

/* ── Estado global ───────────────────────────────────────── */
var tipoSeleccionado = null;
var cantidadSeleccionada = 1;
var escaner = null;
var escanerActivo = false;
var procesando = false;
var historial = [];

/* ── Seleccionar tipo de bebida ──────────────────────────── */
function seleccionarTipo(tipo, btn) {
    tipoSeleccionado = tipo;
    var botones = document.getElementsByClassName('tipo-btn');
    for (var i = 0; i < botones.length; i++) {
        botones[i].classList.remove('seleccionado');
    }
    btn.classList.add('seleccionado');
}

/* ── Cambiar cantidad ────────────────────────────────────── */
function cambiarCantidad(delta) {
    cantidadSeleccionada = Math.max(1, Math.min(10, cantidadSeleccionada + delta));
    document.getElementById('cantidad-valor').textContent = cantidadSeleccionada;
}

/* ── Cambiar pestaña cámara / manual ─────────────────────── */
function mostrarTab(tab) {
    var esCamara = tab === 'camara';
    document.getElementById('panel-camara').style.display = esCamara ? 'block' : 'none';
    document.getElementById('panel-manual').style.display  = esCamara ? 'none'  : 'block';
    document.getElementById('tab-camara').classList.toggle('activo', esCamara);
    document.getElementById('tab-manual').classList.toggle('activo', !esCamara);

    // Detener escáner al cambiar a manual
    if (!esCamara && escanerActivo) {
        pararEscaner();
    }
}

/* ── Iniciar / parar escáner ─────────────────────────────── */
function toggleEscaner() {
    if (escanerActivo) {
        pararEscaner();
    } else {
        iniciarEscaner();
    }
}

function iniciarEscaner() {
    if (!escaner) {
        escaner = new Html5QrcodeScanner('qr-reader', {
            fps: 12,
            qrbox: { width: 260, height: 260 },
            rememberLastUsedCamera: true,
            showTorchButtonIfSupported: true,
            // Prefiere la cámara trasera (environment facing)
            videoConstraints: { facingMode: { ideal: 'environment' } },
        });
    }

    escaner.render(
        function (texto) {
            // QR leído — procesar automáticamente
            if (!procesando) {
                procesarCodigo(texto.trim());
            }
        },
        function (error) {
            // Errores de lectura continuos, no mostrar al usuario
        }
    );

    escanerActivo = true;
    document.getElementById('btn-escaner').textContent = 'Detener cámara';
}

function pararEscaner() {
    if (escaner) {
        escaner.clear().catch(function () {});
        escaner = null;
    }
    escanerActivo = false;
    document.getElementById('btn-escaner').textContent = 'Iniciar cámara';
}

/* ── Procesar código manual ──────────────────────────────── */
function procesarManual() {
    var input = document.getElementById('input-manual');
    var codigo = (input ? input.value : '').trim();
    if (!codigo) { return; }
    procesarCodigo(codigo);
    if (input) { input.value = ''; }
}

/* ── Lógica principal de validación ─────────────────────── */
function procesarCodigo(codigoQr) {
    if (procesando) { return; }

    // Validar que haya tipo seleccionado
    if (!tipoSeleccionado) {
        mostrarResultado('error', 'Elige el tipo de bebida primero', '', null, false);
        return;
    }

    procesando = true;

    fetch(window.canjeData.urlValidar, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': window.canjeData.csrf,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            codigo_qr:     codigoQr,
            cantidad:      cantidadSeleccionada,
            tipo_producto: tipoSeleccionado,
        }),
    })
    .then(function (r) { return r.json(); })
    .then(function (datos) {
        if (datos.ok) {
            var subtitulo = datos.nombre + ' · ' + datos.tipo_bono;
            mostrarResultado('ok', datos.mensaje, subtitulo, datos.bebidas_restantes, datos.agotado);
            añadirHistorial(datos);
        } else if (datos.tipo === 'sin_saldo' || datos.tipo === 'saldo_insuficiente') {
            mostrarResultado('warning', datos.error, datos.nombre || '', datos.bebidas_restantes ?? 0, false);
        } else {
            mostrarResultado('error', datos.error || 'Error al validar', '', null, false);
        }
    })
    .catch(function () {
        mostrarResultado('error', 'Error de conexión. Inténtalo de nuevo.', '', null, false);
    })
    .finally(function () {
        // Esperar 2 segundos antes de permitir otro escaneo
        setTimeout(function () { procesando = false; }, 2000);
    });
}

/* ── Mostrar panel de resultado ──────────────────────────── */
function mostrarResultado(tipo, titulo, detalle, saldoRestante, agotado) {
    var panel  = document.getElementById('resultado-panel');
    var tit    = document.getElementById('resultado-titulo');
    var det    = document.getElementById('resultado-detalle');
    var saldo  = document.getElementById('resultado-saldo');

    panel.className = 'resultado-panel ' + tipo;
    panel.style.display = 'block';

    var iconos = { ok: '✓ ', error: '✕ ', warning: '⚠ ' };
    tit.textContent = (iconos[tipo] || '') + titulo;
    det.textContent = detalle;

    if (saldoRestante !== null && saldoRestante !== undefined) {
        saldo.style.display = 'block';
        if (agotado) {
            saldo.textContent = '0 bebidas restantes — bono agotado';
            saldo.style.color = '#f87171';
        } else {
            saldo.textContent = saldoRestante + ' bebidas restantes';
            saldo.style.color = '#4ade80';
        }
    } else {
        saldo.style.display = 'none';
    }

    // Scroll al resultado en móvil
    panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

/* ── Añadir al historial de sesión ───────────────────────── */
function añadirHistorial(datos) {
    historial.unshift({
        nombre:  datos.nombre || '—',
        tipo:    tipoSeleccionado + ' ×' + cantidadSeleccionada,
        saldo:   datos.bebidas_restantes,
        agotado: datos.agotado,
    });

    // Máximo 10 entradas
    if (historial.length > 10) { historial.pop(); }

    var panel = document.getElementById('panel-historial');
    var lista = document.getElementById('historial-lista');

    panel.style.display = 'block';

    var html = '';
    for (var i = 0; i < historial.length; i++) {
        var h = historial[i];
        html += '<div class="historial-item">'
            + '<span class="historial-nombre">' + h.nombre + '</span>'
            + '<span class="historial-tipo">' + h.tipo + '</span>'
            + '<span class="historial-saldo ' + (h.agotado ? 'agotado' : '') + '">'
            +   (h.agotado ? '0 🚫' : h.saldo + ' 🍹')
            + '</span>'
            + '</div>';
    }
    lista.innerHTML = html;
}

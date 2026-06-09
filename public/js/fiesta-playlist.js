/**
 * fiesta-playlist.js — Lógica de compra de canciones para la playlist de un evento Fiesta.
 *
 * Patrón del proyecto: funciones globales llamadas desde atributos inline del HTML.
 * Sin addEventListener. Sin querySelector/querySelectorAll.
 *
 * Flujo de pago:
 *  1. El usuario pulsa "Añadir" → iniciarAñadir()
 *  2a. Si gratis: llamada directa a /api/playlist/agregar-gratis
 *  2b. Si de pago: se abre el modal con Stripe Elements
 *  3. El usuario introduce la tarjeta y pulsa "Pagar y añadir" → procesarPagoCancion()
 *  4. Se llama a /api/playlist/crear-payment-intent (obtiene client_secret)
 *  5. stripe.confirmCardPayment(client_secret) confirma el cobro
 *  6. Se llama a /api/playlist/confirmar y se actualiza la UI
 */

/* ── Datos globales ──────────────────────────────────────────────────── */
var stripe        = null;
var cardElement   = null;
var cancionActual = null; // { id, titulo, artista, precio, portadaUrl }

var csrfToken = (function () {
    var metas = document.getElementsByTagName('meta');
    for (var i = 0; i < metas.length; i++) {
        if (metas[i].getAttribute('name') === 'csrf-token') {
            return metas[i].getAttribute('content');
        }
    }
    return '';
}());

/* ── Inicialización de Stripe al cargar ──────────────────────────────── */
window.onload = function () {
    if (window.playlistData && window.playlistData.stripeKey) {
        stripe = Stripe(window.playlistData.stripeKey);
        var elements   = stripe.elements({
            appearance: {
                theme: 'night',
                variables: {
                    colorPrimary:    '#a855f7',
                    colorBackground: '#0d0a18',
                    colorText:       '#f5f1ea',
                    borderRadius:    '8px',
                    fontFamily:      'Archivo Narrow, Arial, sans-serif',
                },
            },
        });
        cardElement = elements.create('card', { hidePostalCode: true });
        cardElement.mount('#stripe-card-el');
        cardElement.on('change', function (event) {
            var errorEl = document.getElementById('stripe-error-msg');
            if (event.error) {
                errorEl.textContent = event.error.message;
                errorEl.style.display = 'block';
            } else {
                errorEl.style.display = 'none';
            }
        });
    }
};

/* ── Filtrar canciones por título o artista ──────────────────────────── */
function filtrarCanciones(termino) {
    var termLower = termino.toLowerCase().trim();
    var cards = document.getElementById('grid-canciones').getElementsByClassName('cancion-card');
    for (var i = 0; i < cards.length; i++) {
        var titulo  = cards[i].getAttribute('data-titulo') || '';
        var artista = cards[i].getAttribute('data-artista') || '';
        var visible = titulo.indexOf(termLower) !== -1 || artista.indexOf(termLower) !== -1;
        cards[i].style.display = visible ? '' : 'none';
    }
}

/* ── Iniciar flujo de añadir canción ────────────────────────────────── */
function iniciarAñadir(cancionId, titulo, artista, precio, portadaUrl) {
    cancionActual = { id: cancionId, titulo: titulo, artista: artista, precio: precio, portadaUrl: portadaUrl };

    if (precio <= 0) {
        // Gratuita: confirmación SweetAlert y añadir directamente
        Swal.fire({
            title: '¿Añadir a tu playlist?',
            html: '<strong>' + titulo + '</strong> — ' + artista + '<br><span style="color:#4ade80;font-weight:700;">Gratis</span>',
            icon: 'question',
            background: '#0d0a18',
            color: '#f5f1ea',
            showCancelButton: true,
            confirmButtonText: 'Sí, añadir',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#7c3aed',
            cancelButtonColor: 'rgba(245,241,234,0.10)',
        }).then(function (resultado) {
            if (resultado.isConfirmed) {
                añadirGratis(cancionId);
            }
        });
    } else {
        // De pago: abrir modal con Stripe Elements
        abrirModalPago();
    }
}

/* ── Abrir el modal de pago ─────────────────────────────────────────── */
function abrirModalPago() {
    if (!cancionActual) { return; }

    // Rellenar datos del modal
    document.getElementById('modal-titulo-cancion').textContent  = cancionActual.titulo;
    document.getElementById('modal-artista-cancion').textContent = cancionActual.artista;
    document.getElementById('modal-precio').textContent          = cancionActual.precio.toFixed(2) + ' €';

    // Portada
    var imgEl          = document.getElementById('modal-portada');
    var placeholderEl  = document.getElementById('modal-portada-placeholder');
    if (cancionActual.portadaUrl) {
        imgEl.src           = cancionActual.portadaUrl;
        imgEl.style.display = 'block';
        placeholderEl.style.display = 'none';
    } else {
        imgEl.style.display = 'none';
        placeholderEl.style.display = 'flex';
    }

    document.getElementById('stripe-error-msg').style.display = 'none';
    document.getElementById('modal-pago-overlay').classList.add('abierto');
}

/* ── Cerrar modal ───────────────────────────────────────────────────── */
function cerrarModalPago() {
    document.getElementById('modal-pago-overlay').classList.remove('abierto');
    cancionActual = null;
}

function cerrarModalSiOverlay(evento) {
    if (evento.target === document.getElementById('modal-pago-overlay')) {
        cerrarModalPago();
    }
}

/* ── Cerrar con Escape ──────────────────────────────────────────────── */
document.onkeydown = function (evento) {
    if (evento.key === 'Escape') { cerrarModalPago(); }
};

/* ── Añadir canción gratuita ─────────────────────────────────────────── */
function añadirGratis(cancionId) {
    var btn = document.getElementById('btn-añadir-' + cancionId);
    if (btn) { btn.disabled = true; btn.textContent = 'Añadiendo…'; }

    fetch('/api/playlist/agregar-gratis', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            evento_id:  window.playlistData.eventoId,
            cancion_id: cancionId,
        }),
    })
    .then(function (r) { return r.json(); })
    .then(function (datos) {
        if (datos.success) {
            marcarCancionAñadida(cancionId);
            Swal.fire({
                icon: 'success',
                title: datos.mensaje,
                text: 'Recibirás un email de confirmación.',
                background: '#0d0a18',
                color: '#f5f1ea',
                timer: 3000,
                showConfirmButton: false,
            });
        } else {
            if (btn) { btn.disabled = false; btn.textContent = '+ Añadir gratis'; }
            Swal.fire({
                icon: 'error',
                title: 'No se pudo añadir',
                text: datos.message || 'Inténtalo de nuevo.',
                background: '#0d0a18',
                color: '#f5f1ea',
            });
        }
    })
    .catch(function () {
        if (btn) { btn.disabled = false; btn.textContent = '+ Añadir gratis'; }
        Swal.fire({ icon: 'error', title: 'Error de conexión', background: '#0d0a18', color: '#f5f1ea' });
    });
}

/* ── Procesar pago con Stripe ────────────────────────────────────────── */
function procesarPagoCancion() {
    if (!cancionActual || !stripe || !cardElement) { return; }

    var btn = document.getElementById('btn-pagar-modal');
    btn.disabled    = true;
    btn.textContent = 'Procesando…';
    document.getElementById('stripe-error-msg').style.display = 'none';

    // Paso 1: crear el PaymentIntent en el servidor
    fetch('/api/playlist/crear-payment-intent', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            evento_id:  window.playlistData.eventoId,
            cancion_id: cancionActual.id,
        }),
    })
    .then(function (r) {
        if (!r.ok) { return r.json().then(function (d) { throw d; }); }
        return r.json();
    })
    .then(function (datos) {
        if (!datos.success) { throw { message: datos.message }; }

        // Paso 2: confirmar el pago con Stripe.js
        return stripe.confirmCardPayment(datos.client_secret, {
            payment_method: { card: cardElement },
        }).then(function (resultado) {
            if (resultado.error) {
                throw { message: resultado.error.message };
            }
            // Paso 3: registrar en el servidor
            return fetch('/api/playlist/confirmar', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    payment_intent_id: datos.payment_intent_id,
                    evento_id:         window.playlistData.eventoId,
                    cancion_id:        cancionActual.id,
                }),
            }).then(function (r) { return r.json(); });
        });
    })
    .then(function (datos) {
        if (!datos.success) { throw { message: datos.message || datos.mensaje }; }

        var idGuardado = cancionActual.id;
        cerrarModalPago();
        marcarCancionAñadida(idGuardado);

        Swal.fire({
            icon: 'success',
            title: datos.mensaje,
            text: 'Recibirás un email de confirmación.',
            background: '#0d0a18',
            color: '#f5f1ea',
            timer: 3500,
            showConfirmButton: false,
        });
    })
    .catch(function (error) {
        var msg = (error && (error.message || error.mensaje)) ? (error.message || error.mensaje) : 'Error al procesar el pago.';
        var errorEl = document.getElementById('stripe-error-msg');
        errorEl.textContent  = msg;
        errorEl.style.display = 'block';
    })
    .finally(function () {
        btn.disabled    = false;
        btn.textContent = 'Pagar y añadir';
    });
}

/* ── Actualizar la UI de la card tras añadir con éxito ──────────────── */
function marcarCancionAñadida(cancionId) {
    var card = document.getElementById('card-cancion-' + cancionId);
    var btn  = document.getElementById('btn-añadir-' + cancionId);

    if (btn) {
        btn.disabled    = true;
        btn.style.background = 'rgba(74,222,128,0.15)';
        btn.style.border     = '1px solid rgba(74,222,128,0.3)';
        btn.style.color      = '#4ade80';
        btn.textContent      = '✓ Ya está en tu playlist';
    }

    if (card) {
        card.classList.add('ya-añadida');
    }
}

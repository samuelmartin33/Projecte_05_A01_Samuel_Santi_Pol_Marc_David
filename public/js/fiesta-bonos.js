/**
 * fiesta-bonos.js — Compra de bonos de bebidas con Stripe.
 *
 * Patrón del proyecto: funciones globales, sin addEventListener.
 *
 * Flujo:
 *  1. abrirModalBono() → abre modal con datos del bono seleccionado
 *  2. procesarPagoBono() → crea PI → confirma con Stripe → llama a /api/bonos/confirmar
 */

var stripe       = null;
var cardBono     = null;
var bonoActual   = null; // { tipoId, cantidad, precio }

var csrfToken = (function () {
    var metas = document.getElementsByTagName('meta');
    for (var i = 0; i < metas.length; i++) {
        if (metas[i].getAttribute('name') === 'csrf-token') {
            return metas[i].getAttribute('content');
        }
    }
    return '';
}());

/* ── Inicializar Stripe al cargar ────────────────────────────────── */
window.onload = function () {
    if (window.bonoData && window.bonoData.stripeKey) {
        stripe = Stripe(window.bonoData.stripeKey);
        var elements = stripe.elements({
            appearance: {
                theme: 'night',
                variables: {
                    colorPrimary:    '#a855f7',
                    colorBackground: '#0d0a18',
                    colorText:       '#f5f1ea',
                    borderRadius:    '8px',
                },
            },
        });
        cardBono = elements.create('card', { hidePostalCode: true });
        cardBono.mount('#stripe-bono-card-el');
        cardBono.on('change', function (e) {
            var el = document.getElementById('stripe-bono-error');
            if (e.error) { el.textContent = e.error.message; el.style.display = 'block'; }
            else { el.style.display = 'none'; }
        });
    }
};

/* ── Abrir modal con el bono seleccionado ────────────────────────── */
function abrirModalBono(tipoId, cantidad, precio) {
    bonoActual = { tipoId: tipoId, cantidad: cantidad, precio: precio };

    document.getElementById('modal-bono-titulo').textContent = cantidad + ' bebidas';
    document.getElementById('modal-bono-precio').textContent = precio.toFixed(2) + ' €';
    document.getElementById('stripe-bono-error').style.display = 'none';
    document.getElementById('modal-bono-overlay').classList.add('abierto');
}

/* ── Cerrar modal ────────────────────────────────────────────────── */
function cerrarModalBono() {
    document.getElementById('modal-bono-overlay').classList.remove('abierto');
    bonoActual = null;
}

function cerrarModalBonoSiOverlay(evento) {
    if (evento.target === document.getElementById('modal-bono-overlay')) {
        cerrarModalBono();
    }
}

document.onkeydown = function (e) {
    if (e.key === 'Escape') { cerrarModalBono(); }
};

/* ── Procesar pago del bono ──────────────────────────────────────── */
function procesarPagoBono() {
    if (!bonoActual || !stripe || !cardBono) { return; }

    var btn = document.getElementById('btn-pagar-bono');
    btn.disabled    = true;
    btn.textContent = 'Procesando…';
    document.getElementById('stripe-bono-error').style.display = 'none';

    // Paso 1: crear PaymentIntent
    fetch('/api/bonos/crear-payment-intent', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            evento_id:    window.bonoData.eventoId,
            bono_tipo_id: bonoActual.tipoId,
        }),
    })
    .then(function (r) {
        if (!r.ok) { return r.json().then(function (d) { throw d; }); }
        return r.json();
    })
    .then(function (datos) {
        if (!datos.success) { throw { message: datos.message }; }

        // Paso 2: confirmar con Stripe.js
        return stripe.confirmCardPayment(datos.client_secret, {
            payment_method: { card: cardBono },
        }).then(function (resultado) {
            if (resultado.error) { throw { message: resultado.error.message }; }

            // Paso 3: registrar en el servidor
            return fetch('/api/bonos/confirmar', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    payment_intent_id: datos.payment_intent_id,
                    evento_id:         window.bonoData.eventoId,
                    bono_tipo_id:      bonoActual.tipoId,
                }),
            }).then(function (r) { return r.json(); });
        });
    })
    .then(function (datos) {
        if (!datos.success) { throw { message: datos.message || datos.mensaje }; }

        cerrarModalBono();
        Swal.fire({
            icon: 'success',
            title: datos.mensaje,
            text: 'Recibirás el QR por email. ¡Disfruta de la noche!',
            background: '#0d0a18',
            color: '#f5f1ea',
            timer: 4000,
            showConfirmButton: false,
        }).then(function () {
            // Recargar para mostrar el nuevo bono en "Tus bonos activos"
            window.location.reload();
        });
    })
    .catch(function (error) {
        var msg = (error && (error.message || error.mensaje)) ? (error.message || error.mensaje) : 'Error al procesar el pago.';
        var el = document.getElementById('stripe-bono-error');
        el.textContent   = msg;
        el.style.display = 'block';
    })
    .finally(function () {
        btn.disabled    = false;
        btn.textContent = 'Pagar y activar bono';
    });
}

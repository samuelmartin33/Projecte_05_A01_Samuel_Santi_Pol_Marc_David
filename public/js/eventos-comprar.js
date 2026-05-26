var _cfg = window.comprarData || {};

var EVENTO_ID     = _cfg.eventoId     || 0;
var PRECIO_BASE   = _cfg.precioBase   || 0;
var ES_GRATUITO   = _cfg.esGratuito   || false;
var AFORO_LIBRE   = _cfg.aforoLibre   !== undefined ? _cfg.aforoLibre : 9999;
var STRIPE_KEY    = _cfg.stripeKey    || '';
var STRIPE_ACTIVO = _cfg.stripeActivo || false;

var cantidad = 1;
var _stripe  = null;
var _card    = null;

// ── Inicializar Stripe ────────────────────────────────────────────────────────
function _initStripe() {
    if (!STRIPE_KEY || ES_GRATUITO || !STRIPE_ACTIVO) return;
    if (typeof Stripe === 'undefined') return;
    try {
        _stripe = Stripe(STRIPE_KEY);
        var elements = _stripe.elements();
        _card = elements.create('card', {
            style: {
                base: {
                    color:           '#f5f1ea',
                    fontFamily:      '"DM Sans", sans-serif',
                    fontSize:        '15px',
                    backgroundColor: 'transparent',
                    '::placeholder': { color: 'rgba(245,241,234,0.35)' },
                },
                invalid: { color: '#f87171' },
            },
        });
        _card.mount('#stripe-card-element');
    } catch (e) {
        console.warn('Stripe init error:', e);
        _stripe = null;
        _card   = null;
    }
}

// ── Selector de cantidad ──────────────────────────────────────────────────────
function cambiarCantidad(delta) {
    var nueva = cantidad + delta;
    if (nueva < 1 || nueva > 10 || nueva > AFORO_LIBRE) return;
    cantidad = nueva;
    _actualizarUI();
}

function _actualizarUI() {
    var numEl = document.getElementById('checkout-cantidad');
    var totEl = document.getElementById('checkout-total');
    var btnEl = document.getElementById('checkout-btn');

    if (numEl) numEl.textContent = cantidad;

    if (totEl) {
        if (ES_GRATUITO) {
            totEl.textContent = 'Gratis';
        } else {
            totEl.textContent = (PRECIO_BASE * cantidad).toFixed(2).replace('.', ',') + ' €';
        }
    }

    if (btnEl && !ES_GRATUITO && STRIPE_ACTIVO) {
        var tot = (PRECIO_BASE * cantidad).toFixed(2).replace('.', ',') + ' €';
        btnEl.textContent = 'Pagar ' + tot;
    }
}

// ── Envío del formulario ──────────────────────────────────────────────────────
function procesarPago() {
    if (ES_GRATUITO || !STRIPE_ACTIVO) {
        _reservarGratis();
    } else {
        _pagarConStripe();
    }
}

// ── Flujo gratuito ────────────────────────────────────────────────────────────
function _reservarGratis() {
    var btn      = document.getElementById('checkout-btn');
    var errEl    = document.getElementById('checkout-error');
    var csrf     = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    // Leemos el cupón solo si el input existe (visible únicamente para usuarios Premium).
    var cuponEl  = document.getElementById('premium-cupon-codigo');
    var cupon    = cuponEl ? cuponEl.value.trim() : '';

    btn.disabled    = true;
    btn.textContent = 'Procesando...';
    if (errEl) errEl.style.display = 'none';

    // Construimos el body base y añadimos el cupón solo si se introdujo uno.
    var body = { evento_id: EVENTO_ID, cantidad: cantidad };
    if (cupon) body.cupon_codigo = cupon;

    fetch('/api/entradas/comprar', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
        body:    JSON.stringify(body),
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        if (d.success) {
            _redirigir(d.redirect);
        } else {
            _mostrarError(d.message || 'Error al procesar la reserva.');
            btn.disabled    = false;
            btn.textContent = 'Reservar gratis';
        }
    })
    .catch(function() {
        _mostrarError('Error de conexión. Inténtalo de nuevo.');
        btn.disabled    = false;
        btn.textContent = 'Reservar gratis';
    });
}

// ── Flujo Stripe ──────────────────────────────────────────────────────────────
function _pagarConStripe() {
    if (!_stripe || !_card) {
        _mostrarError('Error al cargar el sistema de pago. Recarga la página e inténtalo de nuevo.');
        return;
    }

    var btn   = document.getElementById('checkout-btn');
    var errEl = document.getElementById('checkout-error');
    var csrf  = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var tot   = (PRECIO_BASE * cantidad).toFixed(2).replace('.', ',') + ' €';

    btn.disabled    = true;
    btn.textContent = 'Preparando pago...';
    if (errEl) errEl.style.display = 'none';

    // Paso 1: crear PaymentIntent en el servidor
    fetch('/api/stripe/crear-payment-intent', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
        body:    JSON.stringify({ evento_id: EVENTO_ID, cantidad: cantidad }),
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        if (!d.success) {
            _mostrarError(d.message || 'Error al iniciar el pago.');
            btn.disabled    = false;
            btn.textContent = 'Pagar ' + tot;
            return Promise.resolve(null);
        }

        btn.textContent = 'Procesando pago...';

        // Paso 2: confirmar pago con la tarjeta introducida
        return _stripe.confirmCardPayment(d.client_secret, {
            payment_method: { card: _card },
        });
    })
    .then(function(res) {
        if (!res) return Promise.resolve(null);

        if (res.error) {
            var cardErr = document.getElementById('stripe-card-error');
            if (cardErr) { cardErr.textContent = res.error.message; cardErr.style.display = 'block'; }
            btn.disabled    = false;
            btn.textContent = 'Pagar ' + tot;
            return Promise.resolve(null);
        }

        btn.textContent = 'Confirmando pedido...';

        var csrf2 = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        return fetch('/api/entradas/confirmar-stripe', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf2 },
            body:    JSON.stringify({
                payment_intent_id: res.paymentIntent.id,
                evento_id:         EVENTO_ID,
                cantidad:          cantidad,
            }),
        }).then(function(r) { return r.json(); });
    })
    .then(function(d) {
        if (!d) return;
        if (d.success) {
            _redirigir(d.redirect);
        } else {
            _mostrarError(d.message || 'Error al confirmar la compra.');
            btn.disabled    = false;
            btn.textContent = 'Pagar ' + tot;
        }
    })
    .catch(function() {
        _mostrarError('Error de conexión. Inténtalo de nuevo.');
        btn.disabled    = false;
        btn.textContent = 'Pagar ' + tot;
    });
}

// ── Helpers ───────────────────────────────────────────────────────────────────
function _mostrarError(msg) {
    var el = document.getElementById('checkout-error');
    if (el) { el.textContent = msg; el.style.display = 'block'; }
}

function _redirigir(url) {
    var btn = document.getElementById('checkout-btn');
    if (btn) btn.textContent = '¡Redirigiendo...';
    document.body.style.transition = 'opacity 0.3s';
    document.body.style.opacity    = '0';
    setTimeout(function() { window.location.href = url; }, 320);
}

// ── Arranque ──────────────────────────────────────────────────────────────────
_actualizarUI();
_initStripe();

/**
 * camarero-stock.js — Gestión de stock de barra por empresa.
 * Stock compartido entre todos los eventos de la empresa.
 * Precio de reposición fijo: 2.50 €/ud.
 * Pago mediante Stripe (mismo patrón que fiesta-bonos.js).
 */

/* ─── Stripe: inicializar al cargar ────────────────────────────── */

var _stripe   = null;
var _cardPago = null;

window.onload = function () {
    if (window.stockData && window.stockData.stripeKey) {
        _stripe = Stripe(window.stockData.stripeKey);
        var elements = _stripe.elements({
            appearance: {
                theme: 'night',
                variables: {
                    colorPrimary:    '#4ade80',
                    colorBackground: '#07060c',
                    colorText:       '#f5f1ea',
                    borderRadius:    '0px',
                },
            },
        });
        _cardPago = elements.create('card', { hidePostalCode: true });
        _cardPago.mount('#stripe-pedido-card-el');
        _cardPago.on('change', function (e) {
            var el = document.getElementById('stripe-pedido-error');
            if (e.error) { el.textContent = e.error.message; el.style.display = 'block'; }
            else { el.style.display = 'none'; }
        });
    }
};

/* ─── Helpers AJAX ──────────────────────────────────────────────── */

function fetchStock(url, method, body) {
    var opts = {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'Accept':       'application/json',
            'X-CSRF-TOKEN': window.stockData.csrf,
        },
    };
    if (body) opts.body = JSON.stringify(body);
    return fetch(url, opts).then(function(r) { return r.json(); });
}

function urlConId(plantilla, id) {
    return plantilla.replace('__ID__', id);
}

/* ─── Modales ───────────────────────────────────────────────────── */

function cerrarModal(id) {
    document.getElementById(id).style.display = 'none';
}

function cerrarModalSiOverlay(event, id) {
    if (event.target.id === id) cerrarModal(id);
}

/* ─── Modal: Crear / Editar producto ────────────────────────────── */

function abrirModalProducto(producto) {
    document.getElementById('modal-producto').style.display = 'flex';

    if (producto) {
        document.getElementById('modal-producto-titulo').textContent = 'Editar producto';
        document.getElementById('producto-id').value        = producto.id;
        document.getElementById('producto-nombre').value    = producto.nombre;
        document.getElementById('producto-proveedor').value = producto.proveedor;

        var sel = document.getElementById('producto-tipo');
        var encontrado = false;
        for (var i = 0; i < sel.options.length; i++) {
            if (sel.options[i].value === producto.tipo_producto) {
                sel.selectedIndex = i;
                encontrado = true;
                break;
            }
        }
        if (!encontrado) {
            var opt = document.createElement('option');
            opt.value = producto.tipo_producto;
            opt.textContent = producto.tipo_producto;
            sel.insertBefore(opt, sel.lastElementChild);
            sel.value = producto.tipo_producto;
        }
        document.getElementById('producto-tipo-nuevo').style.display = 'none';
    } else {
        document.getElementById('modal-producto-titulo').textContent = 'Nuevo producto';
        document.getElementById('producto-id').value        = '';
        document.getElementById('producto-nombre').value    = '';
        document.getElementById('producto-proveedor').value = '';
        document.getElementById('producto-tipo').selectedIndex = 0;
        document.getElementById('producto-tipo-nuevo').style.display = 'none';
    }

    document.getElementById('producto-nombre').focus();
}

function verificarTipoPersonalizado() {
    var sel   = document.getElementById('producto-tipo');
    var input = document.getElementById('producto-tipo-nuevo');
    input.style.display = sel.value === '__nuevo__' ? 'block' : 'none';
    if (sel.value === '__nuevo__') input.focus();
}

function obtenerTipoSeleccionado() {
    var sel = document.getElementById('producto-tipo');
    if (sel.value === '__nuevo__') {
        return document.getElementById('producto-tipo-nuevo').value.trim();
    }
    return sel.value;
}

function guardarProducto() {
    var id        = document.getElementById('producto-id').value;
    var nombre    = document.getElementById('producto-nombre').value.trim();
    var tipo      = obtenerTipoSeleccionado();
    var proveedor = document.getElementById('producto-proveedor').value.trim();

    if (!nombre || !tipo || !proveedor) {
        Swal.fire({ icon: 'warning', title: 'Campos requeridos', text: 'Rellena todos los campos.', background: '#0d0820', color: '#f5f1ea' });
        return;
    }

    var body   = { nombre: nombre, tipo_producto: tipo, proveedor: proveedor };
    var url    = id ? urlConId(window.stockData.urlUpdate, id) : window.stockData.urlStore;
    var method = id ? 'PUT' : 'POST';

    fetchStock(url, method, body)
        .then(function(data) {
            if (data.ok) {
                Swal.fire({ icon: 'success', title: '¡Listo!', text: data.mensaje, background: '#0d0820', color: '#f5f1ea', timer: 1500, showConfirmButton: false })
                    .then(function() { location.reload(); });
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: data.error || 'Error desconocido.', background: '#0d0820', color: '#f5f1ea' });
            }
        });
}

/* ─── Eliminar producto ──────────────────────────────────────────── */

function eliminarProducto(id, nombre) {
    Swal.fire({
        icon: 'warning',
        title: '¿Eliminar producto?',
        text: '¿Seguro que quieres eliminar "' + nombre + '"?',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#f87171',
        background: '#0d0820',
        color: '#f5f1ea',
    }).then(function(res) {
        if (!res.isConfirmed) return;
        fetchStock(urlConId(window.stockData.urlDestroy, id), 'DELETE', null)
            .then(function(data) {
                if (data.ok) {
                    Swal.fire({ icon: 'success', title: 'Eliminado', text: data.mensaje, background: '#0d0820', color: '#f5f1ea', timer: 1500, showConfirmButton: false })
                        .then(function() { location.reload(); });
                } else {
                    Swal.fire({ icon: 'error', title: 'No se puede eliminar', text: data.error, background: '#0d0820', color: '#f5f1ea' });
                }
            });
    });
}

/* ─── Modal: Generar pedido ──────────────────────────────────────── */

function abrirModalPedido(productoId, nombre) {
    document.getElementById('pedido-producto-id').value             = productoId;
    document.getElementById('pedido-producto-nombre').textContent   = nombre;
    document.getElementById('pedido-cantidad').value                = 10;
    calcularTotalPedido();
    document.getElementById('modal-pedido').style.display = 'flex';
}

function calcularTotalPedido() {
    var cant  = parseInt(document.getElementById('pedido-cantidad').value) || 0;
    var total = cant * window.stockData.precioUnidad;
    document.getElementById('pedido-total-estimado').textContent = total.toFixed(2).replace('.', ',') + ' €';
}

function confirmarPedido() {
    var productoId = document.getElementById('pedido-producto-id').value;
    var cantidad   = parseInt(document.getElementById('pedido-cantidad').value);

    if (!cantidad || cantidad < 1) {
        Swal.fire({ icon: 'warning', title: 'Cantidad inválida', text: 'Introduce una cantidad mayor que 0.', background: '#0d0820', color: '#f5f1ea' });
        return;
    }

    fetchStock(urlConId(window.stockData.urlPedido, productoId), 'POST', { cantidad: cantidad })
        .then(function(data) {
            if (data.ok) {
                cerrarModal('modal-pedido');
                Swal.fire({
                    icon: 'success',
                    title: '¡Pedido generado!',
                    html: 'Pedido <strong>#' + data.pedido.id + '</strong> creado.<br>Total: <strong>' + data.pedido.precio_total + ' €</strong>',
                    background: '#0d0820',
                    color: '#f5f1ea',
                    timer: 1800,
                    showConfirmButton: false,
                }).then(function() { location.reload(); });
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: data.error, background: '#0d0820', color: '#f5f1ea' });
            }
        });
}

/* ─── Pago con Stripe (3 pasos) ─────────────────────────────────── */

function abrirModalPago(pedidoId, total) {
    document.getElementById('pago-pedido-id').value               = pedidoId;
    document.getElementById('pago-pedido-ref').textContent         = 'Pedido #' + pedidoId;
    document.getElementById('pago-total').textContent              = total + ' €';
    document.getElementById('stripe-pedido-error').style.display   = 'none';

    var btn = document.getElementById('btn-confirmar-pago');
    btn.disabled    = false;
    btn.textContent = '💳 Pagar ahora';

    document.getElementById('modal-pago').style.display = 'flex';
}

function procesarPagoPedido() {
    var pedidoId = document.getElementById('pago-pedido-id').value;
    if (!pedidoId || !_stripe || !_cardPago) return;

    var btn         = document.getElementById('btn-confirmar-pago');
    var btnCancelar = document.getElementById('btn-cancelar-pago');
    btn.disabled         = true;
    btn.textContent      = 'Procesando…';
    btnCancelar.disabled = true;
    document.getElementById('stripe-pedido-error').style.display = 'none';

    // Paso 1: crear PaymentIntent
    fetchStock(urlConId(window.stockData.urlPaymentIntent, pedidoId), 'POST', null)
        .then(function(datos) {
            if (!datos.success) throw { message: datos.message };

            // Paso 2: confirmar con Stripe.js
            return _stripe.confirmCardPayment(datos.client_secret, {
                payment_method: { card: _cardPago },
            }).then(function(resultado) {
                if (resultado.error) throw { message: resultado.error.message };

                // Paso 3: verificar en servidor
                return fetchStock(
                    urlConId(window.stockData.urlConfirmarPago, pedidoId),
                    'POST',
                    { payment_intent_id: datos.payment_intent_id }
                );
            });
        })
        .then(function(datos) {
            if (!datos.success) throw { message: datos.message || datos.mensaje };

            cerrarModal('modal-pago');
            Swal.fire({
                icon: 'success',
                title: '¡Pago realizado!',
                html: datos.mensaje + '<br><small style="color:rgba(245,241,234,0.4)">Recibirás la confirmación por correo.</small>',
                background: '#0d0820',
                color: '#f5f1ea',
                timer: 2500,
                showConfirmButton: false,
            }).then(function() { location.reload(); });
        })
        .catch(function(error) {
            var msg = (error && (error.message || error.mensaje)) || 'Error al procesar el pago.';
            var el  = document.getElementById('stripe-pedido-error');
            el.textContent   = msg;
            el.style.display = 'block';
        })
        .finally(function() {
            btn.disabled         = false;
            btn.textContent      = '💳 Pagar ahora';
            btnCancelar.disabled = false;
        });
}

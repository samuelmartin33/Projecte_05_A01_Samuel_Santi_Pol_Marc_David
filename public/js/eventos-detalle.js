// Interacciones de la ficha de evento y compra de entradas.
var datosEvento = window.eventoData || {};

var EVENTO_ID   = datosEvento.id        || 0;
var PRECIO_BASE = datosEvento.precioBase || 0;
var ES_GRATUITO = datosEvento.esGratuito || false;
var AFORO_LIBRE = datosEvento.aforoLibre !== undefined ? datosEvento.aforoLibre : 9999;
var LOGIN_URL   = datosEvento.loginUrl   || '/login';

var cantidadModal = 1;

if (datosEvento.latitud && datosEvento.longitud) {
    inicializarMapa(datosEvento.latitud, datosEvento.longitud, datosEvento.nombreUbicacion || 'Ubicación del evento');
}

function inicializarMapa(latitud, longitud, nombreUbicacion) {
    var mapa = L.map('mapa-evento').setView([latitud, longitud], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom:     19,
        attribution: '© <a href="https://openstreetmap.org">OpenStreetMap</a>'
    }).addTo(mapa);

    var iconoVibez = L.divIcon({
        html:        '<div style="background:linear-gradient(135deg,#7c3aed,#a855f7);width:32px;height:32px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.3)"></div>',
        iconSize:    [32, 32],
        iconAnchor:  [16, 32],
        popupAnchor: [0, -35],
        className:   ''
    });

    L.marker([latitud, longitud], { icon: iconoVibez })
        .addTo(mapa)
        .bindPopup('<strong>' + nombreUbicacion + '</strong>')
        .openPopup();
}

function abrirCompra() {
    if (datosEvento.guestRedirect) {
        window.location.href = LOGIN_URL;
        return;
    }

    cantidadModal = 1;
    actualizarModalTotal();
    document.getElementById('modal-error').style.display        = 'none';
    document.getElementById('modal-btn-comprar').disabled       = false;
    document.getElementById('modal-btn-comprar').textContent    = ES_GRATUITO ? 'Reservar gratis' : 'Confirmar compra';
    document.getElementById('modal-compra').style.display       = 'block';
    document.body.style.overflow = 'hidden';
}

function cerrarModalCompra() {
    document.getElementById('modal-compra').style.display = 'none';
    document.body.style.overflow = '';
}

function cambiarCantidad(cambio) {
    var nuevaCantidad = cantidadModal + cambio;
    if (nuevaCantidad < 1 || nuevaCantidad > 10 || nuevaCantidad > AFORO_LIBRE) return;
    cantidadModal = nuevaCantidad;
    actualizarModalTotal();
}

function actualizarModalTotal() {
    document.getElementById('modal-cantidad').textContent = cantidadModal;
    if (!ES_GRATUITO) {
        var total = (PRECIO_BASE * cantidadModal).toFixed(2).replace('.', ',');
        document.getElementById('modal-total').textContent = total + ' €';
    }
}

function confirmarCompra() {
    var botonComprar = document.getElementById('modal-btn-comprar');
    var zonaError    = document.getElementById('modal-error');
    var metadatos = document.getElementsByTagName('meta');
    var csrf      = '';

    for (var indice = 0; indice < metadatos.length; indice++) {
        if (metadatos[indice].getAttribute('name') === 'csrf-token') {
            csrf = metadatos[indice].getAttribute('content');
            break;
        }
    }

    botonComprar.disabled    = true;
    botonComprar.textContent = 'Procesando...';
    zonaError.style.display  = 'none';

    fetch('/api/entradas/comprar', {
        method:  'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept':       'application/json',
            'X-CSRF-TOKEN': csrf,
        },
        body: JSON.stringify({ evento_id: EVENTO_ID, cantidad: cantidadModal }),
    })
    .then(function (respuesta) { return respuesta.json(); })
    .then(function (datos) {
        if (datos.success) {
            botonComprar.textContent       = '¡Redirigiendo...';
            document.body.style.transition = 'opacity 0.3s';
            document.body.style.opacity    = '0';
            setTimeout(function () { window.location.href = datos.redirect; }, 320);
        } else {
            zonaError.textContent    = datos.message || 'Error al procesar la compra.';
            zonaError.style.display  = 'block';
            botonComprar.disabled    = false;
            botonComprar.textContent = ES_GRATUITO ? 'Reservar gratis' : 'Confirmar compra';
        }
    })
    .catch(function () {
        zonaError.textContent    = 'Error de conexión. Inténtalo de nuevo.';
        zonaError.style.display  = 'block';
        botonComprar.disabled    = false;
        botonComprar.textContent = ES_GRATUITO ? 'Reservar gratis' : 'Confirmar compra';
    });
}

/**
 * entradas-mis-entradas.js — VIBEZ
 *
 * Funciones:
 *  1. Generación de QR con qrcodejs para cada entrada (data-codigo).
 *  2. Cuenta atrás en tiempo real para el próximo evento con entrada activa.
 *  3. Toggle de QR al hacer clic en la tarjeta del pedido.
 *  4. Filtro de tarjetas por estado (Todas / Activas / Usadas / Caducadas).
 *  5. Solicitud de reembolso de un pedido con confirmación SweetAlert2.
 */


/* ════ 1. GENERACIÓN DE CÓDIGOS QR ════
   Los QR se generan de forma lazy (al abrir el panel) para evitar canvas en
   blanco cuando el contenedor padre está oculto con display:none. */
function generarQrEnPanel(pedidoId) {
    var panel = document.getElementById('qr-panel-' + pedidoId);
    if (!panel) return;
    Array.from(panel.getElementsByClassName('qr-container')).forEach(function(el) {
        if (el.dataset.generado) return;
        el.dataset.generado = '1';
        new QRCode(el, {
            text:       el.dataset.codigo,
            width:      180,
            height:     180,
            colorDark:  '#000000',
            colorLight: '#ffffff',
        });
    });
}


/* ════ 2. CUENTA ATRÁS DEL PRÓXIMO EVENTO ════
   Lee la fecha ISO del atributo data-fecha del banner y actualiza los
   dígitos cada segundo hasta que el evento llegue. */
(function() {
    var banner = document.getElementsByClassName('me-cnt-row')[0];
    if (!banner) return;

    var target = new Date(banner.dataset.fecha).getTime();

    /* Actualiza los cuatro bloques de tiempo del countdown */
    function actualizar() {
        var diff = target - Date.now();

        if (diff <= 0) {
            ['cnt-dias','cnt-horas','cnt-min','cnt-seg'].forEach(function(id) {
                document.getElementById(id).textContent = '00';
            });
            return;
        }

        var dias     = Math.floor(diff / 86400000);
        var horas    = Math.floor((diff % 86400000) / 3600000);
        var minutos  = Math.floor((diff % 3600000)  / 60000);
        var segundos = Math.floor((diff % 60000)    / 1000);

        document.getElementById('cnt-dias').textContent  = String(dias).padStart(2, '0');
        document.getElementById('cnt-horas').textContent = String(horas).padStart(2, '0');
        document.getElementById('cnt-min').textContent   = String(minutos).padStart(2, '0');
        document.getElementById('cnt-seg').textContent   = String(segundos).padStart(2, '0');
    }

    actualizar();
    setInterval(actualizar, 1000);
})();


/* ════ 3. TOGGLE DE QR POR TARJETA ════
   Al pulsar en una tarjeta de pedido se muestra u oculta el panel de QR.
   El chevron rota para indicar el estado abierto/cerrado.
   Llamado con onclick desde el div .me-card en el HTML. */
function toggleTicketQr(pedidoId) {
    var panel   = document.getElementById('qr-panel-'  + pedidoId);
    var chevron = document.getElementById('chevron-' + pedidoId);
    var abierto = panel.style.display !== 'none';

    if (!abierto) generarQrEnPanel(pedidoId);
    panel.style.display = abierto ? 'none' : 'block';

    /* Rotamos el chevron para dar feedback visual */
    if (chevron) {
        if (abierto) {
            chevron.classList.remove('abierto');
        } else {
            chevron.classList.add('abierto');
        }
    }
}


/* ════ 4. FILTRO DE ENTRADAS ════
   Muestra solo las tarjetas cuyo data-estado coincida con el filtro.
   Los botones de filtro marcan la clase .activo en el seleccionado.
   Llamado con onclick desde los botones .me-filtro-btn en el HTML. */
function filtrarEntradas(filtro, btnPulsado) {
    /* Marcar botón activo */
    Array.from(document.getElementsByClassName('me-filtro-btn')).forEach(function(btn) {
        btn.classList.remove('activo');
    });
    if (btnPulsado) btnPulsado.classList.add('activo');

    var cards    = document.getElementsByClassName('me-card');
    var visibles = 0;

    /* Mostrar u ocultar cada tarjeta según su estado */
    Array.from(cards).forEach(function(card) {
        var mostrar = filtro === 'todas' || card.dataset.estado === filtro;
        card.style.display = mostrar ? '' : 'none';
        if (mostrar) visibles++;
    });

    /* Mensaje si ninguna tarjeta coincide con el filtro */
    var noResultados = document.getElementById('me-no-resultados');
    if (noResultados) {
        noResultados.style.display = visibles === 0 ? '' : 'none';
    }
}


/* ════ 5. SOLICITUD DE REEMBOLSO ════
   Muestra una confirmación SweetAlert2 y, si el usuario acepta, llama al
   endpoint de reembolso. Si tiene éxito, elimina la tarjeta del DOM.
   evt.stopPropagation() evita que el clic abra el panel QR de la tarjeta.
   Llamado con onclick desde el botón .me-btn-reembolso en el HTML. */
function solicitarReembolso(evt, pedidoId, url) {
    evt.stopPropagation();

    Swal.fire({
        title: '¿Pedir reembolso?',
        html: 'Se cancelarán <strong>todas las entradas</strong> de este pedido y recibirás el importe pagado de vuelta.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#7c3aed',
        cancelButtonColor: '#334155',
        confirmButtonText: 'Sí, reembolsar',
        cancelButtonText: 'Cancelar',
        background: '#0f172a',
        color: '#f5f1ea',
    }).then(function(result) {
        if (!result.isConfirmed) return;

        /* Obtenemos el token CSRF del meta tag que Laravel inyecta en el layout */
        var csrfToken = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type':  'application/json',
                'X-CSRF-TOKEN':  csrfToken,
                'Accept':        'application/json',
            },
        })
        .then(function(res) {
            return res.json().then(function(data) {
                return { ok: res.ok, data: data };
            });
        })
        .then(function(respuesta) {
            if (respuesta.ok && respuesta.data.success) {
                Swal.fire({
                    title: '¡Reembolso procesado!',
                    text: respuesta.data.message || 'Tu reembolso se ha procesado correctamente.',
                    icon: 'success',
                    confirmButtonColor: '#7c3aed',
                    background: '#0f172a',
                    color: '#f5f1ea',
                }).then(function() {
                    /* Animación de salida y eliminación de la tarjeta */
                    var card = document.getElementById('pedido-card-' + pedidoId);
                    if (card) {
                        card.style.transition = 'opacity 0.35s ease, transform 0.35s ease';
                        card.style.opacity    = '0';
                        card.style.transform  = 'translateY(-10px)';
                        setTimeout(function() { card.remove(); }, 380);
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: respuesta.data.message || 'No se pudo procesar el reembolso.',
                    icon: 'error',
                    confirmButtonColor: '#7c3aed',
                    background: '#0f172a',
                    color: '#f5f1ea',
                });
            }
        })
        .catch(function() {
            Swal.fire({
                title: 'Error de conexión',
                text: 'No se pudo conectar con el servidor. Inténtalo de nuevo.',
                icon: 'error',
                confirmButtonColor: '#7c3aed',
                background: '#0f172a',
                color: '#f5f1ea',
            });
        });
    });
}

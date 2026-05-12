/**
 * entradas-mis-entradas.js — VIBEZ
 *
 * Funciones:
 *  1. Generación de QR con qrcodejs para cada entrada (data-codigo).
 *  2. Cuenta atrás en tiempo real para el próximo evento con entrada activa.
 *  3. Toggle de QR al hacer clic en la tarjeta del pedido.
 *  4. Filtro de tarjetas por estado (Todas / Activas / Usadas).
 */


/* ════ 1. GENERACIÓN DE CÓDIGOS QR ════
   Busca todos los elementos con [data-codigo] y genera el QR dentro.
   Depende de qrcodejs, que debe cargarse en el HTML ANTES que este script. */
document.querySelectorAll('[data-codigo]').forEach(function(el) {
    new QRCode(el, {
        text:       el.dataset.codigo,
        width:      180,
        height:     180,
        colorDark:  '#000000',
        colorLight: '#ffffff',
    });
});


/* ════ 2. CUENTA ATRÁS DEL PRÓXIMO EVENTO ════
   Lee la fecha ISO del atributo data-fecha del banner y actualiza los
   dígitos cada segundo hasta que el evento llegue. */
(function() {
    var banner = document.querySelector('.me-cnt-row');
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

        var dias  = Math.floor(diff / 86400000);
        var horas = Math.floor((diff % 86400000) / 3600000);
        var min   = Math.floor((diff % 3600000)  / 60000);
        var seg   = Math.floor((diff % 60000)    / 1000);

        document.getElementById('cnt-dias').textContent  = String(dias).padStart(2, '0');
        document.getElementById('cnt-horas').textContent = String(horas).padStart(2, '0');
        document.getElementById('cnt-min').textContent   = String(min).padStart(2, '0');
        document.getElementById('cnt-seg').textContent   = String(seg).padStart(2, '0');
    }

    actualizar();
    setInterval(actualizar, 1000);
})();


/* ════ 3. TOGGLE DE QR POR TARJETA ════
   Al pulsar en una tarjeta de pedido se muestra u oculta el panel de QR.
   El chevron rota para indicar el estado abierto/cerrado.
   Llamado con onclick desde el div .me-ticket-card en el HTML. */
function toggleTicketQr(pedidoId) {
    var panel   = document.getElementById('qr-panel-'  + pedidoId);
    var chevron = document.getElementById('chevron-' + pedidoId);
    var abierto = panel.style.display !== 'none';

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
    document.querySelectorAll('.me-filtro-btn').forEach(function(btn) {
        btn.classList.remove('activo');
    });
    if (btnPulsado) btnPulsado.classList.add('activo');

    var cards    = document.querySelectorAll('.me-ticket-card');
    var visibles = 0;

    /* Mostrar u ocultar cada tarjeta según su estado */
    cards.forEach(function(card) {
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

/**
 * VIBEZ — index.js
 * Maneja el logout por AJAX desde el dashboard
 * + Panel admin: tabs para gestión de usuarios
 */

/* ============================================================
   LOGOUT
   ============================================================ */
document.getElementById('logoutBtn').onclick = async function () {
    this.classList.add('loading');
    this.textContent = 'Cerrando sesión...';

    try {
        var metadatos = document.getElementsByTagName('meta');
        var tokenCsrf = '';

        for (var indice = 0; indice < metadatos.length; indice++) {
            if (metadatos[indice].getAttribute('name') === 'csrf-token') {
                tokenCsrf = metadatos[indice].getAttribute('content');
                break;
            }
        }

        var respuesta = await fetch('/api/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept':       'application/json',
                'X-CSRF-TOKEN': tokenCsrf,
            },
        });

        var datos = await respuesta.json();

        if (datos.success) {
            /* Fade-out y redirect al login */
            document.body.style.transition = 'opacity 0.35s ease';
            document.body.style.opacity    = '0';
            setTimeout(function () { window.location.href = '/login'; }, 360);
        } else {
            this.classList.remove('loading');
            this.textContent = 'Cerrar sesión';
            console.error('[VIBEZ] Error en logout:', datos.message);
        }

    } catch (err) {
        this.classList.remove('loading');
        this.textContent = 'Cerrar sesión';
        console.error('[VIBEZ] Error de conexión en logout:', err);
    }
};


/* ============================================================
   ADMIN PANEL — Tabs (solo si el panel existe en el DOM)
   ============================================================ */
(function () {
    var panelAdmin = document.getElementById('adminPanel');
    if (!panelAdmin) return;

    var botonesTabs  = Array.from(panelAdmin.getElementsByClassName('admin-tab'));
    var panelesTabs  = Array.from(panelAdmin.getElementsByClassName('admin-tab-panel'));

    botonesTabs.forEach(function (boton) {
        boton.onclick = function () {
            var destino = boton.getAttribute('data-tab');

            botonesTabs.forEach(function (botonTab) { botonTab.classList.remove('active'); });
            panelesTabs.forEach(function (panelTab) { panelTab.classList.remove('active'); });

            boton.classList.add('active');
            document.getElementById('panel-' + destino).classList.add('active');
        };
    });

    var mensajeFlash = panelAdmin.getElementsByClassName('admin-flash')[0];
    if (mensajeFlash) {
        setTimeout(function () {
            panelAdmin.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 300);
    }
})();

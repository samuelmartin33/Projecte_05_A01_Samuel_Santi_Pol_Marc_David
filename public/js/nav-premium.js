/**
 * nav-premium.js — logica del dropdown de cupones premium en la navegacion
 */
/* ─── Cupones Premium: dropdown del icono de ticket ─────────── */

var _cuponesAbierto = false;

function toggleCuponesDropdown() {
    var drop      = document.getElementById('navCuponesDropdown');
    var notifDrop = document.getElementById('navNotifDropdown');
    var avatarDrop = document.getElementById('navDropdown');
    if (!drop) return;

    // Cierra los otros dropdowns al abrir éste
    if (notifDrop) notifDrop.style.display = 'none';
    if (avatarDrop) avatarDrop.style.display = 'none';
    _notifAbierto = false;

    _cuponesAbierto = !_cuponesAbierto;
    drop.style.display = _cuponesAbierto ? 'block' : 'none';
}

/* Copia el código del cupón al portapapeles y muestra feedback en el botón */
function copiarCuponNav(codigo, cuponId) {
    navigator.clipboard.writeText(codigo).then(function() {
        var btn = document.getElementById('nav-btn-cup-' + cuponId);
        if (!btn) return;
        btn.textContent = '¡Copiado!';
        btn.style.background = 'rgba(124,58,237,0.35)';
        btn.style.color = '#fff';
        setTimeout(function() {
            btn.textContent = 'Copiar';
            btn.style.background = 'rgba(168,85,247,0.15)';
            btn.style.color = '#e9d5ff';
        }, 2000);
    });
}

/* ─── Notificaciones: campanita del nav ─────────────────────── */

var _notifAbierto = false;
var _csrf = document.querySelector('meta[name="csrf-token"]') ?
    document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

/* Abre/cierra el dropdown y carga las notificaciones si se abre */
function toggleNotifDropdown() {
    var drop = document.getElementById('navNotifDropdown');
    var avatarDrop = document.getElementById('navDropdown');

    // Cerrar el dropdown de avatar si estaba abierto
    if (avatarDrop) avatarDrop.style.display = 'none';

    _notifAbierto = !_notifAbierto;
    drop.style.display = _notifAbierto ? 'block' : 'none';

    if (_notifAbierto) cargarNotificaciones();
}

/* Carga las notificaciones via AJAX y renderiza la lista */
function cargarNotificaciones() {
    fetch('/api/notificaciones', {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': _csrf }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        var lista = document.getElementById('notifLista');
        if (!lista) return;

        if (!data.notificaciones || data.notificaciones.length === 0) {
            lista.innerHTML = '<p style="padding:20px 12px;text-align:center;font-family:\'Archivo Narrow\',sans-serif;font-size:13px;color:rgba(245,241,234,0.35);">Sin notificaciones</p>';
            return;
        }

        var html = '';
        for (var i = 0; i < data.notificaciones.length; i++) {
            var n = data.notificaciones[i];
            var bg = n.leida ? 'transparent' : 'rgba(168,85,247,0.07)';
            var dot = n.leida ? '' : '<span style="width:7px;height:7px;border-radius:50%;background:var(--magenta);flex-shrink:0;margin-top:3px;"></span>';
            var tag = n.url ? 'a' : 'div';
            var href = n.url ? ' href="' + n.url + '"' : '';
            html += '<' + tag + href + ' onclick="leerNotificacion(' + n.id + ', this)"' +
                ' style="display:flex;gap:10px;align-items:flex-start;padding:10px 12px;border-radius:8px;background:' + bg + ';color:var(--ink);text-decoration:none;cursor:pointer;transition:background 0.15s;" ' +
                'onmouseover="this.style.background=\'rgba(245,241,234,0.05)\'" onmouseout="this.style.background=\'' + bg + '\'">' +
                '<span style="font-size:18px;flex-shrink:0;">' + n.icono + '</span>' +
                '<div style="flex:1;min-width:0;">' +
                '<p style="font-family:\'Archivo Narrow\',sans-serif;font-size:13px;font-weight:700;color:var(--ink);margin:0 0 2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + n.titulo + '</p>' +
                (n.mensaje ? '<p style="font-size:11px;color:rgba(245,241,234,0.5);margin:0 0 4px;line-height:1.3;">' + n.mensaje + '</p>' : '') +
                '<p style="font-size:10px;color:rgba(245,241,234,0.3);margin:0;font-family:\'Archivo Narrow\',sans-serif;">' + n.fecha + '</p>' +
                '</div>' + dot +
                '</' + tag + '>';
        }
        lista.innerHTML = html;

        /* Actualizar badge */
        actualizarBadge(data.sin_leer);

        /* Push Notification API: mostrar notificación nativa para no leídas */
        if (data.sin_leer > 0) solicitarPushPermiso(data.notificaciones, data.sin_leer);
    })
    .catch(function() {
        var lista = document.getElementById('notifLista');
        if (lista) lista.innerHTML = '<p style="padding:16px 12px;text-align:center;font-size:12px;color:rgba(245,241,234,0.3);">Error al cargar</p>';
    });
}

/* Marca una notificación como leída */
function leerNotificacion(id, el) {
    fetch('/api/notificaciones/' + id + '/leer', {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': _csrf }
    });
    // Eliminar el punto visual de no leída
    if (el) el.style.background = 'transparent';
    var dot = el ? el.querySelector('span[style*="border-radius:50%"]') : null;
    if (dot && dot.style.background.includes('magenta')) dot.style.display = 'none';
}

/* Marca todas como leídas */
function leerTodasNotificaciones() {
    fetch('/api/notificaciones/leer-todas', {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': _csrf }
    })
    .then(function() {
        actualizarBadge(0);
        cargarNotificaciones();
    });
}

/* Actualiza el badge numérico de la campanita */
function actualizarBadge(count) {
    var badge = document.getElementById('notifBadge');
    if (!badge) return;
    if (count <= 0) {
        badge.style.display = 'none';
    } else {
        badge.style.display = 'flex';
        badge.textContent = count > 9 ? '9+' : count;
    }
}

/* ─── Browser Notification API (Push nativo del SO) ────────── */

/* Solicita permiso y muestra notificación nativa si hay no leídas */
function solicitarPushPermiso(notificaciones, sinLeer) {
    if (!('Notification' in window)) return;
    if (Notification.permission === 'denied') return;

    var mostrar = function() {
        // Solo mostrar la más reciente no leída como notificación nativa
        for (var i = 0; i < notificaciones.length; i++) {
            if (!notificaciones[i].leida) {
                new Notification('VIBEZ — ' + notificaciones[i].titulo, {
                    body: notificaciones[i].mensaje || '',
                    icon: '/images/logo_vibez_white.png',
                    badge: '/images/logo_vibez_white.png',
                    tag: 'vibez-notif-' + notificaciones[i].id,
                });
                break;
            }
        }
    };

    if (Notification.permission === 'granted') {
        mostrar();
    } else if (Notification.permission === 'default') {
        Notification.requestPermission().then(function(permiso) {
            if (permiso === 'granted') mostrar();
        });
    }
}

/* Al cargar la página, pedir permiso si hay notificaciones sin leer */
(function initNotifEnCarga() {
    var badgeEl = document.getElementById('notifBadge');
    if (!badgeEl || badgeEl.style.display === 'none') return;
    // Hay notificaciones — cargar para el push sin abrir el dropdown
    if ('Notification' in window && Notification.permission !== 'denied') {
        fetch('/api/notificaciones', {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': _csrf }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.sin_leer > 0) solicitarPushPermiso(data.notificaciones, data.sin_leer);
        });
    }
})();

/* ─── Cierre al hacer clic fuera (encadena el manejador previo de nav-hamburger.js) ─── */
(function() {
    /* Guardar el manejador previo (nav-hamburger.js lo asigna antes que este script) */
    var _prevClick = document.onclick;
    document.onclick = function(e) {
        /* Propagar al manejador anterior si existe */
        if (_prevClick) _prevClick(e);

        /* Cerrar dropdown de cupones al clicar fuera */
        var btnCup  = document.getElementById('navCuponesBtn');
        var dropCup = document.getElementById('navCuponesDropdown');
        if (btnCup && dropCup && !btnCup.contains(e.target) && !dropCup.contains(e.target)) {
            dropCup.style.display = 'none';
            _cuponesAbierto = false;
        }

        /* Cerrar dropdown de notificaciones al clicar fuera */
        var bell = document.getElementById('navBellBtn');
        var drop = document.getElementById('navNotifDropdown');
        if (bell && drop && !bell.contains(e.target) && !drop.contains(e.target)) {
            drop.style.display = 'none';
            _notifAbierto = false;
        }
    };
})();

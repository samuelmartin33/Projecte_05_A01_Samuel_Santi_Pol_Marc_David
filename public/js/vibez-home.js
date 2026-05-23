    /**
 * vibez-home.js — VIBEZ Home interactiva
 * Funciones globales sin addEventListener (regla del proyecto).
 * Todo se llama desde onclick/onscroll inline en el HTML.
 */

/* ── Aesthetic del body ─────────────────────────────────────── */
document.body.dataset.aesthetic = 'italo';
document.body.dataset.hero      = 'poster';

/* ── Scroll: nav transparente → opaco + parallax hero ───────── */
window.onscroll = function () {
    var nav = document.getElementById('vibez-home-nav');
    if (nav) {
        if (window.scrollY > 12) {
            nav.style.background    = 'rgba(7,6,12,0.85)';
            nav.style.backdropFilter = 'blur(18px)';
            nav.style.borderBottom  = '1px solid var(--line)';
        } else {
            nav.style.background    = 'transparent';
            nav.style.backdropFilter = 'none';
            nav.style.borderBottom  = '1px solid transparent';
        }
    }
    var img = document.getElementById('hero-parallax-img');
    if (img) {
        img.style.transform = 'translateY(' + (window.scrollY * 0.25) + 'px) scale(1.05)';
    }
};

/* ── Countdown ───────────────────────────────────────────────── */
function vibezStartCountdown(isoFecha) {
    function actualizar() {
        var diff    = Math.max(0, new Date(isoFecha).getTime() - Date.now());
        var dias    = Math.floor(diff / (1000 * 60 * 60 * 24));
        var horas   = Math.floor((diff / (1000 * 60 * 60)) % 24);
        var minutos = Math.floor((diff / (1000 * 60)) % 60);
        var segs    = Math.floor((diff / 1000) % 60);
        ['cd-dias','cd-horas','cd-minutos','cd-segundos'].forEach(function (id, i) {
            var el = document.getElementById(id);
            if (el) el.textContent = String([dias, horas, minutos, segs][i]).padStart(2, '0');
        });
    }
    actualizar();
    setInterval(actualizar, 1000);
}

/* ── Filtro por categoría (chip bar) ────────────────────────── */
var vibezActiveCategoria = 'Todo';

function vibezFilterCategoria(cat) {
    vibezActiveCategoria = cat;
    document.querySelectorAll('.vibez-cat-chip').forEach(function (chip) {
        chip.classList.toggle('active', chip.dataset.cat === cat);
    });
    _vibezFiltrarGrid(cat);
}

/* Filtra el grid AJAX de eventos */
function _vibezFiltrarGrid(cat) {
    var grid = document.getElementById('vibez-grid-todos');
    if (!grid) return;

    var url = '/api/filtrar?categoria=' + encodeURIComponent(cat === 'Todo' ? '' : cat);
    var spinner = document.getElementById('vibez-grid-spinner');
    if (spinner) spinner.style.display = 'flex';

    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (spinner) spinner.style.display = 'none';
            var eventos = (data.eventos || []);
            var count   = document.getElementById('vibez-count-label');
            if (count) count.textContent = eventos.length;
            _vibezRenderGrid(grid, eventos);
        })
        .catch(function () {
            if (spinner) spinner.style.display = 'none';
        });
}

function _vibezRenderGrid(grid, eventos) {
    if (!eventos.length) {
        grid.innerHTML = '<p style="color:var(--ink-dim);font-family:\'Archivo Narrow\',sans-serif;padding:60px 0;text-align:center;grid-column:1/-1;">No hay eventos para esta categoría.</p>';
        return;
    }
    grid.innerHTML = eventos.map(function (e) {
        var esFav = (window.FAVORITOS_IDS || []).includes(e.id);
        return '<article class="vibe-card vibez-grid-card" data-id="' + e.id + '" onclick="vibezOpenModal(' + e.id + ')" style="cursor:pointer;">'
            + '<div class="img-wrap" style="position:relative;aspect-ratio:3/4;overflow:hidden;">'
            + '<img src="' + (e.img || e.url_portada || '') + '" alt="' + e.titulo + '" style="width:100%;height:100%;object-fit:cover;filter:contrast(1.05) saturate(1.1) brightness(0.85);">'
            + '<div style="position:absolute;inset:0;background:linear-gradient(180deg,transparent 50%,rgba(7,6,12,0.88));"></div>'
            + (e.estaOcurriendo ? '<div style="position:absolute;top:12px;left:12px;background:var(--magenta);color:var(--cream);padding:4px 10px;border-radius:999px;font-family:\'Archivo Narrow\',sans-serif;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;display:flex;align-items:center;gap:5px;"><span style="width:6px;height:6px;border-radius:50%;background:var(--cream);animation:vibez-pulse 1.4s ease-in-out infinite;display:inline-block;"></span>EN CURSO</div>' : '')
            + (e.soldOut ? '<div style="position:absolute;top:18px;right:18px;background:var(--cream);color:var(--bg);padding:4px 12px;font-family:\'Anton\',sans-serif;font-size:11px;transform:rotate(4deg);">SOLD OUT</div>' : '')
            + '<button onclick="event.stopPropagation();vibezToggleFav(' + e.id + ',this)" class="' + (esFav ? 'activo' : '') + '" style="position:absolute;top:18px;right:18px;width:38px;height:38px;border-radius:50%;background:rgba(7,6,12,0.55);border:1px solid var(--ink-faint);color:var(--ink);backdrop-filter:blur(10px);cursor:pointer;display:flex;align-items:center;justify-content:center;">'
            + '<svg width="14" height="14" viewBox="0 0 24 24" fill="' + (esFav ? 'var(--magenta)' : 'currentColor') + '"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>'
            + '</button>'
            + '<div style="position:absolute;bottom:0;left:0;right:0;padding:18px;">'
            + '<div class="mono" style="font-size:10px;color:var(--magenta-2);margin-bottom:6px;display:flex;justify-content:space-between;">'
            + '<span>' + (e.fechaFmt || '') + ' · ' + (e.categoria || '') + '</span><span>' + (e.precio || '') + '</span></div>'
            + '<h3 class="display" style="font-size:24px;margin:0;line-height:0.95;">' + e.titulo + '</h3>'
            + '<p style="font-family:\'Archivo Narrow\',sans-serif;font-size:11px;color:var(--ink-dim);margin:6px 0 0;text-transform:uppercase;letter-spacing:0.08em;">' + (e.lugar || '') + '</p>'
            + '</div></div></article>';
    }).join('');
}

/* ── Carousel scroll ─────────────────────────────────────────── */
function vibezScrollCarousel(id, dir) {
    var el = document.getElementById(id);
    if (el) el.scrollBy({ left: dir * 420, behavior: 'smooth' });
}

/* ── Navegación al detalle del evento ────────────────────────── */
function vibezOpenModal(eventoId) {
    window.location.href = '/eventos/' + eventoId;
}

function vibezCloseModal() {}

/* ── Compra de entrada ───────────────────────────────────────── */
function vibezBuy(eventoId) {
    window.location.href = '/eventos/' + eventoId + '/comprar';
}

/* ── Toast ───────────────────────────────────────────────────── */
function vibezToast(msg) {
    var t = document.getElementById('vibez-toast');
    if (!t) {
        t = document.createElement('div');
        t.id = 'vibez-toast';
        t.className = 'toast';
        document.body.appendChild(t);
    }
    t.textContent = msg;
    t.style.display = 'block';
    setTimeout(function () { t.style.display = 'none'; }, 2800);
}

/* ── Favoritos ───────────────────────────────────────────────── */
function vibezToggleFav(eventoId, btn) {
    if (!window.USER_AUTH) {
        window.location.href = window.LOGIN_URL || '/login';
        return;
    }
    var csrf = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
    fetch('/api/favoritos/toggle', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify({ evento_id: eventoId })
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
        if (!data.success) return;
        var esFav = data.favorito;
        /* Actualizar todos los botones de este evento en la página */
        document.querySelectorAll('[data-fav-id="' + eventoId + '"]').forEach(function (b) {
            b.classList.toggle('activo', esFav);
            var svg = b.querySelector('svg path');
            if (svg) svg.setAttribute('fill', esFav ? 'var(--magenta)' : 'currentColor');
        });
        /* También el botón que llamó a la función si no tiene data-fav-id */
        if (btn) {
            btn.classList.toggle('activo', esFav);
            var svg = btn.querySelector('svg path');
            if (svg) svg.setAttribute('fill', esFav ? 'var(--magenta)' : 'currentColor');
        }
        /* Actualizar array local */
        if (!window.FAVORITOS_IDS) window.FAVORITOS_IDS = [];
        var idx = window.FAVORITOS_IDS.indexOf(eventoId);
        if (esFav && idx === -1) window.FAVORITOS_IDS.push(eventoId);
        if (!esFav && idx !== -1) window.FAVORITOS_IDS.splice(idx, 1);

        vibezToast(esFav ? '♡ Guardado en favoritos' : '✕ Eliminado de favoritos');
    })
    .catch(function () { vibezToast('Error al guardar favorito.'); });
}

/* ── Mapa Leaflet (home compacto) ────────────────────────────── */
function vibezInitMap() {
    var container = document.getElementById('vibez-map');
    if (!container || typeof L === 'undefined') return;
    if (container._leaflet_id) return;

    var map = L.map(container, {
        center: [41.385, 2.176], zoom: 13,
        zoomControl: true, scrollWheelZoom: false
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    var ahora = Date.now();

    (window.EVENTOS_DATA || []).forEach(function (e) {
        if (!e.coords || !e.coords[0] || e.haTerminado) return;

        var cls = 'vibez-pin';
        if (e.estaOcurriendo) cls += ' happening';
        else if (e.featured)  cls += ' featured';

        var size   = e.featured || e.estaOcurriendo ? 52 : 38;
        var anchor = size / 2;

        var icon = L.divIcon({
            className: '',
            html: '<div class="' + cls + '">' + (e.estaOcurriendo ? '▶' : (e.featured ? '★' : '')) + '</div>',
            iconSize:   [size, size],
            iconAnchor: [anchor, anchor]
        });

        var marker = L.marker(e.coords, { icon: icon }).addTo(map);

        /* Popup con detalle del evento */
        var popupHtml = '<div class="vibez-map-popup">'
            + '<img src="' + e.img + '" alt="' + e.titulo + '" style="width:100%;height:110px;object-fit:cover;margin-bottom:0;">'
            + '<div style="padding:12px;">'
            + (e.estaOcurriendo ? '<div style="display:inline-flex;align-items:center;gap:5px;background:var(--magenta);color:var(--cream);padding:3px 8px;border-radius:999px;font-size:9px;font-family:\'Archivo Narrow\',sans-serif;font-weight:700;text-transform:uppercase;margin-bottom:8px;"><span style="width:5px;height:5px;border-radius:50%;background:var(--cream);"></span>EN CURSO</div>' : '')
            + '<div style="font-family:\'Anton\',sans-serif;font-size:16px;line-height:1;margin-bottom:6px;color:var(--ink);">' + e.titulo + '</div>'
            + '<div style="font-family:\'Archivo Narrow\',sans-serif;font-size:10px;color:var(--ink-dim);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:8px;">' + (e.fechaFmt || '') + ' · ' + (e.hora || '') + '</div>'
            + '<div style="font-family:\'Archivo Narrow\',sans-serif;font-size:10px;color:var(--ink-dim);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:12px;">' + (e.lugar || '') + '</div>'
            + '<div style="display:flex;align-items:center;justify-content:space-between;">'
            + '<span style="font-family:\'Anton\',sans-serif;font-size:20px;color:var(--magenta);">' + (e.precio || '') + '</span>'
            + '<button onclick="vibezOpenModal(' + e.id + ')" style="background:var(--magenta);color:var(--cream);border:none;padding:8px 16px;border-radius:999px;font-family:\'Archivo Narrow\',sans-serif;font-size:11px;font-weight:700;text-transform:uppercase;cursor:pointer;letter-spacing:0.05em;">Ver más →</button>'
            + '</div></div></div>';

        marker.bindPopup(popupHtml, {
            maxWidth: 240,
            className: 'vibez-leaflet-popup',
            closeButton: true
        });

        marker.on('click', function () { marker.openPopup(); });
    });
}

/* ── Mapa página completa (/mapa) ────────────────────────────── */
function vibezInitMapFull() {
    var container = document.getElementById('vibez-map-full');
    if (!container || typeof L === 'undefined') return;
    if (container._leaflet_id) return;

    var map = L.map(container, {
        center: [41.385, 2.176], zoom: 13,
        zoomControl: true, scrollWheelZoom: true
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    var panelEl  = document.getElementById('mapa-panel');
    var activeId = null;

    (window.EVENTOS_DATA || []).forEach(function (e) {
        if (!e.coords || !e.coords[0] || e.haTerminado) return;

        var cls = 'vibez-pin';
        if (e.estaOcurriendo) cls += ' happening';
        else if (e.featured)  cls += ' featured';

        var size   = e.featured || e.estaOcurriendo ? 52 : 38;
        var icon = L.divIcon({
            className: '',
            html: '<div class="' + cls + '">' + (e.estaOcurriendo ? '▶' : (e.featured ? '★' : '')) + '</div>',
            iconSize: [size, size], iconAnchor: [size / 2, size / 2]
        });

        var marker = L.marker(e.coords, { icon: icon }).addTo(map);
        marker.on('click', (function (ev) {
            return function () { vibezMapPanel(ev, panelEl, map); };
        }(e)));
    });
}

function vibezMapPanel(e, panelEl, map) {
    if (!panelEl) return;
    map.flyTo(e.coords, 15, { duration: 0.8 });

    var esFav = (window.FAVORITOS_IDS || []).includes(e.id);
    panelEl.innerHTML = '<div style="position:relative;">'
        + '<img src="' + e.img + '" alt="' + e.titulo + '" style="width:100%;height:200px;object-fit:cover;">'
        + '<button onclick="vibezMapCerrarPanel()" style="position:absolute;top:12px;right:12px;width:36px;height:36px;border-radius:50%;background:rgba(7,6,12,0.7);border:1px solid var(--ink-faint);color:var(--ink);cursor:pointer;font-size:16px;backdrop-filter:blur(8px);">×</button>'
        + '<div style="position:absolute;inset:0;background:linear-gradient(180deg,transparent 40%,rgba(7,6,12,0.9));"></div>'
        + '<div style="position:absolute;bottom:12px;left:16px;right:16px;">'
        + (e.estaOcurriendo ? '<div style="display:inline-flex;align-items:center;gap:5px;background:var(--magenta);color:var(--cream);padding:3px 8px;border-radius:999px;font-size:9px;font-family:\'Archivo Narrow\',sans-serif;font-weight:700;text-transform:uppercase;margin-bottom:6px;"><span style="width:5px;height:5px;border-radius:50%;background:var(--cream);"></span>EN CURSO</div><br>' : '')
        + '<div class="display" style="font-size:22px;line-height:1;color:var(--ink);">' + e.titulo + '</div>'
        + '</div></div>'
        + '<div style="padding:16px;">'
        + '<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px;">'
        + _mapaMetaRow('Fecha',    e.fechaFmt)
        + _mapaMetaRow('Hora',     e.hora)
        + _mapaMetaRow('Sala',     e.lugar)
        + _mapaMetaRow('Categoría', e.categoria)
        + '</div>'
        + '<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">'
        + '<span class="display" style="font-size:32px;color:var(--magenta);">' + e.precio + '</span>'
        + (e.cupos !== null && e.cupos < 50 ? '<span style="font-size:11px;color:var(--magenta);font-family:\'Archivo Narrow\',sans-serif;">Quedan ' + e.cupos + '</span>' : '')
        + '</div>'
        + '<button onclick="vibezOpenModal(' + e.id + ')" class="btn-primary" style="width:100%;padding:14px;border-radius:999px;font-size:15px;">Comprar entrada →</button>'
        + '<button onclick="vibezToggleFav(' + e.id + ',this)" data-fav-id="' + e.id + '" class="btn-ghost ' + (esFav ? 'activo' : '') + '" style="width:100%;padding:10px;border-radius:999px;font-size:13px;margin-top:8px;">'
        + '<svg width="14" height="14" viewBox="0 0 24 24" fill="' + (esFav ? 'var(--magenta)' : 'currentColor') + '" style="vertical-align:middle;margin-right:4px;"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>'
        + (esFav ? 'En favoritos' : 'Guardar') + '</button>'
        + '</div>';

    panelEl.style.display = 'block';
}

function _mapaMetaRow(label, val) {
    return '<div><div style="font-size:9px;color:var(--ink-dim);font-family:\'Archivo Narrow\',sans-serif;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:2px;">' + label + '</div>'
         + '<div style="font-size:13px;font-weight:600;">' + (val || '—') + '</div></div>';
}

function vibezMapCerrarPanel() {
    var p = document.getElementById('mapa-panel');
    if (p) p.style.display = 'none';
}

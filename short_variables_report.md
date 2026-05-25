# Short Variables Report

## admin-eventos.js
- Line 22: document.onclick = function (e) {
- Line 32: var i = 0;
- Line 35: formularios[i].onsubmit = function (e) {
- Line 38: var msg = formulario.getAttribute('data-confirm-msg') || 'Esta accion no se puede deshacer.';

## app-nav.js
- Line 33: var btn     = document.getElementById('navHamburger');
- Line 58: var btn     = document.getElementById('navHamburger');
- Line 73: document.onkeydown = function (e) {
- Line 89: .then(function (r) { return r.json(); })
- Line 130: var btn      = document.getElementById('navAvatarBtn');
- Line 154: document.onclick = function(e) {
- Line 165: var btn      = document.getElementById('navAvatarBtn');

## cupones.js
- Line 6: var el = document.createElement('textarea');
- Line 24: var btn = document.getElementById('btn-copy-' + cuponId);
- Line 36: var t = document.getElementById('cup-toast');
- Line 46: Array.from(document.getElementsByClassName('cup-filter-chip')).forEach(function(c) { c.classList.remove('active'); });

## empresa-candidaturas-detalle.js
- Line 13: var el = document.getElementById('candidaturas-json');
- Line 22: Array.from(document.getElementsByClassName('tab-estado')).forEach(function(btn) {
- Line 25: var sel = document.getElementsByClassName('filtro-select')[0];
- Line 31: var url = urlBase + (params.toString() ? '?' + params.toString() : '');
- Line 43: var res  = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
- Line 45: var doc  = new DOMParser().parseFromString(html, 'text/html');
- Line 59: document.getElementById('candidaturas-lista').onclick = function(e) {
- Line 75: var btn = document.getElementById('btn-cerrar-oferta');
- Line 79: var res  = await fetch(btn.dataset.url, {
- Line 111: var url   = '/empresa/candidaturas/' + id + '/estado';
- Line 114: var res  = await fetch(url, {
- Line 134: var cand = candidaturasData.find(function(c) { return c.id === id; });
- Line 182: var chips = cand.habilidades.split(',').map(function(h) { return '<span class="chip">' + esc(h.trim()) + '</span>'; }).join('');
- Line 187: var chips2 = cand.idiomas.split(',').map(function(i) { return '<span class="chip">' + esc(i.trim()) + '</span>'; }).join('');
- Line 204: var val = type === 'html' ? value : '<span>' + esc(value) + '</span>';

## entradas-confirmacion.js
- Line 25: document.querySelectorAll('[data-codigo]').forEach(function(el) {

## entradas-mis-entradas.js
- Line 15: Array.from(document.getElementsByClassName('qr-container')).forEach(function(el) {
- Line 40: ['cnt-dias','cnt-horas','cnt-min','cnt-seg'].forEach(function(id) {
- Line 90: Array.from(document.getElementsByClassName('me-filtro-btn')).forEach(function(btn) {

## favoritos.js
- Line 69: Array.from(botones).forEach(function(btn) {

## home.js
- Line 20: ['categoria', 'ubicacion'].forEach(function(id) {
- Line 21: var d = document.getElementById(id + '-dropdown');
- Line 22: var w = document.getElementById('wrapper-' + id);
- Line 40: Array.from(dropdown.getElementsByClassName('custom-select-option')).forEach(function(op) {
- Line 52: Array.from(document.getElementsByClassName('mood-chip')).forEach(function(c) {
- Line 61: for (var i = 0; i < chips.length; i++) {
- Line 123: Array.from(document.getElementsByClassName('custom-select-option')).forEach(function(op) {
- Line 126: ['categoria-dropdown', 'ubicacion-dropdown'].forEach(function(id) {
- Line 141: Array.from(document.getElementsByClassName('mood-chip')).forEach(function(c) {

## index.js
- Line 65: botonesTabs.forEach(function (b) { b.classList.remove('active'); });
- Line 66: panelesTabs.forEach(function (p) { p.classList.remove('active'); });

## login.js
- Line 221: for (var i = 0; i < claves.length; i++) {

## navbar.js
- Line 88: .then(function (r) { return r.json(); })

## perfil.js
- Line 64: reader.onload = function (e) {
- Line 122: .then(function (r) { return r.json(); })
- Line 143: const div = document.createElement('div');
- Line 204: .then(function(r) { return r.json(); })
- Line 209: Array.from(document.getElementsByClassName('mood-opcion')).forEach(function(b) {
- Line 270: var btn   = document.getElementById('btn-emoji-picker');
- Line 337: .then(function (r) { return r.json(); })

## register.js
- Line 92: .then(function (r) { return r.json(); })
- Line 317: var hoy  = new Date();
- Line 318: var nac  = new Date(fechaNacimiento);
- Line 401: for (var c = 0; c < camposLimpiar.length; c++) {
- Line 467: var hoy  = new Date();
- Line 468: var nac  = new Date(fechaNacimiento);
- Line 619: for (var i = 0; i < claves.length; i++) {

## social.js
- Line 50: Array.from(document.getElementsByClassName('soc-panel')).forEach(function (el) {
- Line 53: Array.from(document.getElementsByClassName('soc-nav-btn')).forEach(function (el) {
- Line 94: .then(function (res) { return res.json(); })
- Line 105: pubEventosAsistidos.forEach(function (ev) {
- Line 127: .then(function (res) { return res.json(); })
- Line 184: post.comentarios_preview.forEach(function (c) {
- Line 263: post.imagenes.forEach(function (img, idx) {
- Line 296: Array.from(dotsEl.getElementsByClassName('post-carousel-dot')).forEach(function (d, i) {
- Line 312: var idx = carouselState[postId];
- Line 320: var idx = carouselState[postId] || 0;
- Line 331: var btn = document.getElementById('like-btn-' + postId);
- Line 341: .then(function (res) { return res.json(); })
- Line 386: .then(function (res) { return res.json(); })
- Line 407: var btn = postEl ? postEl.getElementsByClassName('post-ver-mas-comentarios')[0] : null;
- Line 413: .then(function (res) { return res.json(); })
- Line 418: respuesta.datos.forEach(function (c) {
- Line 433: var btn = document.getElementById('pub-btn-publicar');
- Line 447: reader.onload = function (e) {
- Line 468: var btn         = document.getElementById('pub-btn-publicar');
- Line 476: Array.from(inputFotos.files).slice(0, 10).forEach(function (file, i) {
- Line 488: .then(function (res) { return res.json(); })
- Line 521: .then(function (res) { return res.json(); })
- Line 597: .then(function (res) { return res.json(); })
- Line 625: var ta = document.getElementById('chat-textarea');
- Line 655: .then(function (res) { return res.json(); })
- Line 669: respuesta.datos.forEach(function (msg) {
- Line 703: .then(function (res) { return res.json(); })
- Line 733: .then(function (res) { return res.json(); })
- Line 744: respuesta.datos.forEach(function (msg) {
- Line 789: .then(function (res) { return res.json(); })
- Line 835: .then(function (res) { return res.json(); })
- Line 848: respuesta.datos.forEach(function (sol) {
- Line 875: .then(function (res) { return res.json(); })
- Line 878: var el = document.getElementById('solicitud-' + solicitudId);
- Line 897: .then(function (res) { return res.json(); })
- Line 899: var el = document.getElementById('solicitud-' + solicitudId);
- Line 944: .then(function (res) { return res.json(); })
- Line 986: .then(function (res) { return res.json(); })
- Line 1012: .then(function (res) { return res.json(); })
- Line 1046: var div = document.createElement('div');
- Line 1058: var div       = document.createElement('div');
- Line 1091: var hoy   = new Date();

## trabajos-detalle.js
- Line 15: ['modal-eleccion', 'modal-formulario', 'modal-archivo', 'modal-exito'].forEach(function(mid) {
- Line 36: var tpl   = document.getElementById('exp-container').getElementsByClassName('exp-item')[0];
- Line 38: Array.from(clone.getElementsByTagName('input')).concat(Array.from(clone.getElementsByTagName('textarea'))).forEach(function(el) { el.value = ''; });
- Line 40: var btn = document.createElement('button');
- Line 52: var tpl   = document.getElementById('edu-container').getElementsByClassName('edu-item')[0];
- Line 54: Array.from(clone.getElementsByTagName('input')).forEach(function(el) { el.value = ''; });
- Line 56: var btn = document.createElement('button');
- Line 85: var dt = e.dataTransfer;
- Line 109: document.getElementById('form-cv').onsubmit = async function(e) {
- Line 111: var btn = document.getElementById('btn-enviar-cv');
- Line 116: var res = await fetch('/trabajos/' + _ofertaActual + '/postular', {
- Line 140: document.getElementById('form-archivo').onsubmit = async function(e) {
- Line 147: var btn = document.getElementById('btn-enviar-archivo');
- Line 152: var res = await fetch('/trabajos/' + _ofertaActual + '/postular-archivo', {

## trabajos-index.js
- Line 49: ['categoria', 'ciudad'].forEach(function(id) {
- Line 50: var d = document.getElementById(id + '-dropdown');
- Line 51: var w = document.getElementById('wrapper-' + id);
- Line 81: Array.from(dropdown.getElementsByClassName('custom-select-option')).forEach(function(op) {
- Line 114: .then(function(r) { return r.json(); })
- Line 160: Array.from(document.getElementsByClassName('custom-select-option')).forEach(function(op) {
- Line 164: ['categoria-dropdown', 'ciudad-dropdown'].forEach(function(id) {

## vibez-home.js
- Line 13: var nav = document.getElementById('vibez-home-nav');
- Line 25: var img = document.getElementById('hero-parallax-img');
- Line 39: ['cd-dias','cd-horas','cd-minutos','cd-segundos'].forEach(function (id, i) {
- Line 40: var el = document.getElementById(id);
- Line 64: var url = '/api/filtrar?categoria=' + encodeURIComponent(cat === 'Todo' ? '' : cat);
- Line 69: .then(function (r) { return r.json(); })
- Line 87: grid.innerHTML = eventos.map(function (e) {
- Line 109: var el = document.getElementById(id);
- Line 116: var e = eventos.find(function (ev) { return ev.id == eventoId; });
- Line 119: var set = function (id, val) {
- Line 120: var el = document.getElementById(id);
- Line 123: var setSrc = function (id, src) {
- Line 124: var el = document.getElementById(id);
- Line 152: var btn = document.getElementById('modal-comprar');
- Line 167: var qty = document.getElementById('modal-cantidad');
- Line 205: var txt = document.getElementById('modal-btn-seguir-texto');
- Line 214: .then(function (r) { return r.json(); })
- Line 224: var ids = window.SEGUIMIENTOS_IDS || [];
- Line 226: else { window.SEGUIMIENTOS_IDS = ids.filter(function (id) { return id !== empresaId; }); }
- Line 228: Array.from(document.getElementsByClassName('btn-seguir-home')).forEach(function (b) {
- Line 231: var s = b.getElementsByClassName('seguir-texto')[0];
- Line 254: var btn      = document.getElementById('modal-comprar');
- Line 264: .then(function (r) { return r.json(); })
- Line 282: var t = document.getElementById('vibez-toast');
- Line 307: .then(function (r) { return r.json(); })
- Line 312: Array.from(document.getElementsByClassName('activo')).concat(Array.from(document.getElementsByClassName('btn-ghost'))).forEach(function (b) {
- Line 315: var svg = b.getElementsByTagName('path')[0];
- Line 322: var svg = btn.getElementsByTagName('path')[0];
- Line 327: var idx = window.FAVORITOS_IDS.indexOf(eventoId);
- Line 342: var map = L.map(container, {
- Line 353: (window.EVENTOS_DATA || []).forEach(function (e) {
- Line 356: var cls = 'vibez-pin';
- Line 401: var map = L.map(container, {
- Line 412: (window.EVENTOS_DATA || []).forEach(function (e) {
- Line 415: var cls = 'vibez-pin';
- Line 427: marker.on('click', (function (ev) {
- Line 472: var p = document.getElementById('mapa-panel');


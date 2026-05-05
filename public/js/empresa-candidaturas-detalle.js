// Detalle y filtrado de candidaturas de empresa en VIBEZ.
var _cfg          = window.candidaturasPageData || {};
var _estadoActual = _cfg.estadoAct || '';
var _ordenActual  = _cfg.ordenAct  || 'reciente';
var _baseUrl      = _cfg.baseUrl   || '';
var estadoUrl     = _cfg.estadoUrl || '';
var csrf          = document.querySelector('meta[name="csrf-token"]').content;

var candidaturasData = [];

function _leerDatos() {
    var el = document.getElementById('candidaturas-json');
    candidaturasData = el ? JSON.parse(el.textContent) : [];
}
_leerDatos();

async function cargarCandidaturas(estado, orden) {
    _estadoActual = estado;
    _ordenActual  = orden || 'reciente';

    document.querySelectorAll('.tab-estado').forEach(function(btn) {
        btn.classList.toggle('activo', btn.dataset.estado === estado);
    });
    var sel = document.querySelector('.filtro-select');
    if (sel) sel.value = _ordenActual;

    var params = new URLSearchParams();
    if (estado) params.set('estado', estado);
    if (_ordenActual !== 'reciente') params.set('orden', _ordenActual);
    var url = _baseUrl + (params.toString() ? '?' + params.toString() : '');

    history.pushState({}, '', url);
    await _fetchLista(url);
}

async function _fetchLista(url) {
    var lista = document.getElementById('candidaturas-lista');
    lista.style.opacity      = '0.4';
    lista.style.pointerEvents = 'none';

    try {
        var res  = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        var html = await res.text();
        var doc  = new DOMParser().parseFromString(html, 'text/html');
        var nueva = doc.getElementById('candidaturas-lista');
        if (nueva) {
            lista.innerHTML = nueva.innerHTML;
            _leerDatos();
        }
    } catch(e) {
        console.error('Error cargando candidaturas', e);
    } finally {
        lista.style.opacity      = '';
        lista.style.pointerEvents = '';
    }
}

document.getElementById('candidaturas-lista').addEventListener('click', function(e) {
    var link = e.target.closest('a[href]');
    if (!link) return;
    var href = link.getAttribute('href');
    if (href && href.includes('page=')) {
        e.preventDefault();
        history.pushState({}, '', href);
        _fetchLista(href);
    }
});

window.addEventListener('popstate', function() {
    _fetchLista(window.location.href);
});

async function toggleOferta() {
    var btn = document.getElementById('btn-cerrar-oferta');
    btn.disabled = true;

    try {
        var res  = await fetch(btn.dataset.url, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        });
        var data = await res.json();
        if (!data.success) return;

        var activa = data.estado === 1;
        btn.dataset.estado = data.estado;

        btn.innerHTML = activa
            ? '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> Cerrar oferta'
            : '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Reabrir oferta';
        btn.className = btn.className.replace(/bg-\w+-500\/20 text-\w+-300 border border-\w+-500\/30 hover:bg-\w+-500\/30/, '')
            + (activa
                ? ' bg-red-500/20 text-red-300 border border-red-500/30 hover:bg-red-500/30'
                : ' bg-green-500/20 text-green-300 border border-green-500/30 hover:bg-green-500/30');

        var estadoSpan = document.getElementById('oferta-estado-badge');
        if (estadoSpan) {
            estadoSpan.textContent = activa ? '● Activa' : '○ Cerrada';
            estadoSpan.className   = activa ? 'text-green-400 font-semibold' : 'text-slate-500 font-semibold';
        }
    } catch(e) {
        console.error('Error al cambiar estado de oferta', e);
    } finally {
        btn.disabled = false;
    }
}

async function cambiarEstado(id, estado, selectEl) {
    var badge = document.getElementById('badge-' + id);
    var url   = '/empresa/candidaturas/' + id + '/estado';

    try {
        var res  = await fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
            body: JSON.stringify({ estado: estado }),
        });
        var data = await res.json();
        if (data.success) {
            badge.textContent = data.label;
            badge.className   = 'estado-badge ' + data.clases;
            selectEl.className = 'estado-select estado-' + estado;
        }
    } catch(e) {
        console.error('Error al actualizar estado', e);
    }
}

function verCv(id) {
    var cand = candidaturasData.find(function(c) { return c.id === id; });
    if (!cand) return;

    document.getElementById('cv-overlay').classList.add('abierto');
    document.getElementById('cv-spinner').classList.remove('hidden');
    document.getElementById('cv-content').classList.add('hidden');
    document.getElementById('cv-modal-nombre').textContent = cand.nombre;
    document.getElementById('cv-modal-sub').textContent    = cand.fecha
        ? 'Postulado el ' + cand.fecha
        : '';

    var downloadBtn = document.getElementById('cv-download-btn');
    if (cand.tiene_archivo) {
        downloadBtn.href = cand.descargar_url;
        downloadBtn.classList.remove('hidden');
    } else {
        downloadBtn.classList.add('hidden');
    }

    setTimeout(function() {
        document.getElementById('cv-spinner').classList.add('hidden');
        var content = document.getElementById('cv-content');
        content.innerHTML = buildCvHtml(cand);
        content.classList.remove('hidden');
    }, 250);
}

function buildCvHtml(cand) {
    var html = '';

    html += section('Información Personal',
        '<div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem .75rem">'
        + field('Email',    cand.email,    'text')
        + field('Teléfono', cand.telefono, 'text')
        + field('Ciudad',   cand.ciudad,   'text')
        + (cand.linkedin ? field('LinkedIn', '<a href="' + cand.linkedin + '" target="_blank" class="text-purple-600 hover:underline">' + cand.linkedin + '</a>', 'html') : '')
        + '</div>'
    );

    if (cand.perfil) {
        html += section('Perfil Profesional', '<p class="cv-section-body">' + esc(cand.perfil) + '</p>');
    }

    if (cand.carta) {
        html += section('Carta de Presentación', '<p class="cv-section-body">' + esc(cand.carta) + '</p>');
    }

    if (cand.habilidades) {
        var chips = cand.habilidades.split(',').map(function(h) { return '<span class="chip">' + esc(h.trim()) + '</span>'; }).join('');
        html += section('Habilidades', '<div>' + chips + '</div>');
    }

    if (cand.idiomas) {
        var chips2 = cand.idiomas.split(',').map(function(i) { return '<span class="chip">' + esc(i.trim()) + '</span>'; }).join('');
        html += section('Idiomas', '<div>' + chips2 + '</div>');
    }

    if (!cand.perfil && !cand.carta && !cand.habilidades && !cand.idiomas && !cand.email) {
        html += '<div class="cv-section"><p class="text-navy/40 text-sm text-center py-6">Este candidato subió su CV como archivo adjunto.</p></div>';
    }

    return html;
}

function section(title, body) {
    return '<div class="cv-section"><p class="cv-section-title">' + title + '</p>' + body + '</div>';
}

function field(label, value, type) {
    if (!value) return '';
    var val = type === 'html' ? value : '<span>' + esc(value) + '</span>';
    return '<div><p style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:rgba(26,26,46,.4);margin-bottom:.2rem">' + label + '</p>' + val + '</div>';
}

function esc(str) {
    return String(str)
        .replace(/&/g,'&amp;')
        .replace(/</g,'&lt;')
        .replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;');
}

function cerrarCvModal(e) {
    if (e.target === document.getElementById('cv-overlay')) {
        document.getElementById('cv-overlay').classList.remove('abierto');
    }
}
function cerrarCvModalBtn() {
    document.getElementById('cv-overlay').classList.remove('abierto');
}

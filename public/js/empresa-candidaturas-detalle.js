// Detalle y filtrado de candidaturas de empresa en VIBEZ.
var configuracion = window.candidaturasPageData || {};
var estadoActual  = configuracion.estadoAct || '';
var ordenActual   = configuracion.ordenAct  || 'reciente';
var urlBase       = configuracion.baseUrl   || '';
var estadoUrl     = configuracion.estadoUrl || '';
var csrf          = obtenerCsrf();

var candidaturasData = [];

function obtenerCsrf() {
    var metadatos = document.getElementsByTagName('meta');

    for (var indice = 0; indice < metadatos.length; indice++) {
        if (metadatos[indice].getAttribute('name') === 'csrf-token') {
            return metadatos[indice].getAttribute('content');
        }
    }

    return '';
}

function _leerDatos() {
    var nodoDatos = document.getElementById('candidaturas-json');
    candidaturasData = nodoDatos ? JSON.parse(nodoDatos.textContent) : [];
}
_leerDatos();

async function cargarCandidaturas(estado, orden) {
    estadoActual = estado;
    ordenActual  = orden || 'reciente';

    Array.from(document.getElementsByClassName('tab-estado')).forEach(function(botonEstado) {
        botonEstado.classList.toggle('activo', botonEstado.dataset.estado === estado);
    });
    var selectFiltro = document.getElementsByClassName('filtro-select')[0];
    if (selectFiltro) selectFiltro.value = ordenActual;

    var params = new URLSearchParams();
    if (estado) params.set('estado', estado);
    if (ordenActual !== 'reciente') params.set('orden', ordenActual);
    var url = urlBase + (params.toString() ? '?' + params.toString() : '');

    history.pushState({}, '', url);
    await obtenerLista(url);
}

async function obtenerLista(url) {
    var lista = document.getElementById('candidaturas-lista');
    lista.style.opacity      = '0.4';
    lista.style.pointerEvents = 'none';

    try {
        var respuesta = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        var htmlRespuesta = await respuesta.text();
        var documento = new DOMParser().parseFromString(htmlRespuesta, 'text/html');
        var nuevaLista = documento.getElementById('candidaturas-lista');
        if (nuevaLista) {
            lista.innerHTML = nuevaLista.innerHTML;
            _leerDatos();
        }
    } catch(errorCarga) {
        console.error('Error cargando candidaturas', errorCarga);
    } finally {
        lista.style.opacity      = '';
        lista.style.pointerEvents = '';
    }
}

document.getElementById('candidaturas-lista').onclick = function(eventoClic) {
    var link = eventoClic.target.closest('a[href]');
    if (!link) return;
    var href = link.getAttribute('href');
    if (href && href.includes('page=')) {
        eventoClic.preventDefault();
        history.pushState({}, '', href);
        obtenerLista(href);
    }
};

window.onpopstate = function() {
    obtenerLista(window.location.href);
};

async function toggleOferta() {
    var botonOferta = document.getElementById('btn-cerrar-oferta');
    botonOferta.disabled = true;

    try {
        var respuesta = await fetch(botonOferta.dataset.url, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        });
        var datosRespuesta = await respuesta.json();
        if (!datosRespuesta.success) return;

        var ofertaActiva = datosRespuesta.estado === 1;
        botonOferta.dataset.estado = datosRespuesta.estado;

        botonOferta.innerHTML = ofertaActiva
            ? '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> Cerrar oferta'
            : '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Reabrir oferta';
        botonOferta.className = botonOferta.className.replace(/bg-\w+-500\/20 text-\w+-300 border border-\w+-500\/30 hover:bg-\w+-500\/30/, '')
            + (ofertaActiva
                ? ' bg-red-500/20 text-red-300 border border-red-500/30 hover:bg-red-500/30'
                : ' bg-green-500/20 text-green-300 border border-green-500/30 hover:bg-green-500/30');

        var etiquetaEstado = document.getElementById('oferta-estado-badge');
        if (etiquetaEstado) {
            etiquetaEstado.textContent = ofertaActiva ? '● Activa' : '○ Cerrada';
            etiquetaEstado.className   = ofertaActiva ? 'text-green-400 font-semibold' : 'text-slate-500 font-semibold';
        }
    } catch(errorOferta) {
        console.error('Error al cambiar estado de oferta', errorOferta);
    } finally {
        botonOferta.disabled = false;
    }
}

async function cambiarEstado(id, estado, selectEstado) {
    var etiquetaEstado = document.getElementById('badge-' + id);
    var urlEstado      = '/empresa/candidaturas/' + id + '/estado';

    try {
        var respuesta = await fetch(urlEstado, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
            body: JSON.stringify({ estado: estado }),
        });
        var datosRespuesta = await respuesta.json();
        if (datosRespuesta.success) {
            etiquetaEstado.textContent = datosRespuesta.label;
            etiquetaEstado.className   = 'estado-badge ' + datosRespuesta.clases;
            selectEstado.className     = 'estado-select estado-' + estado;
        }
    } catch(errorEstado) {
        console.error('Error al actualizar estado', errorEstado);
    }
}

function verCv(id) {
    var candidatoSeleccionado = candidaturasData.find(function(candidato) { return candidato.id === id; });
    if (!candidatoSeleccionado) return;

    document.getElementById('cv-overlay').classList.add('abierto');
    document.getElementById('cv-spinner').classList.remove('hidden');
    document.getElementById('cv-content').classList.add('hidden');
    document.getElementById('cv-modal-nombre').textContent = candidatoSeleccionado.nombre;
    document.getElementById('cv-modal-sub').textContent    = candidatoSeleccionado.fecha
        ? 'Postulado el ' + candidatoSeleccionado.fecha
        : '';

    var downloadBtn = document.getElementById('cv-download-btn');
    if (candidatoSeleccionado.tiene_archivo) {
        downloadBtn.href = candidatoSeleccionado.descargar_url;
        downloadBtn.classList.remove('hidden');
    } else {
        downloadBtn.classList.add('hidden');
    }

    setTimeout(function() {
        document.getElementById('cv-spinner').classList.add('hidden');
        var content = document.getElementById('cv-content');
        content.innerHTML = buildCvHtml(candidatoSeleccionado);
        content.classList.remove('hidden');
    }, 250);
}

function buildCvHtml(candidato) {
    var html = '';

    html += section('Información Personal',
        '<div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem .75rem">'
        + field('Email',    candidato.email,    'text')
        + field('Teléfono', candidato.telefono, 'text')
        + field('Ciudad',   candidato.ciudad,   'text')
        + (candidato.linkedin ? field('LinkedIn', '<a href="' + candidato.linkedin + '" target="_blank" class="text-purple-600 hover:underline">' + candidato.linkedin + '</a>', 'html') : '')
        + '</div>'
    );

    if (candidato.perfil) {
        html += section('Perfil Profesional', '<p class="cv-section-body">' + esc(candidato.perfil) + '</p>');
    }

    if (candidato.carta) {
        html += section('Carta de Presentación', '<p class="cv-section-body">' + esc(candidato.carta) + '</p>');
    }

    if (candidato.habilidades) {
        var habilidadesHtml = candidato.habilidades.split(',').map(function(habilidad) { return '<span class="chip">' + esc(habilidad.trim()) + '</span>'; }).join('');
        html += section('Habilidades', '<div>' + habilidadesHtml + '</div>');
    }

    if (candidato.idiomas) {
        var idiomasHtml = candidato.idiomas.split(',').map(function(idioma) { return '<span class="chip">' + esc(idioma.trim()) + '</span>'; }).join('');
        html += section('Idiomas', '<div>' + idiomasHtml + '</div>');
    }

    if (!candidato.perfil && !candidato.carta && !candidato.habilidades && !candidato.idiomas && !candidato.email) {
        html += '<div class="cv-section"><p class="text-navy/40 text-sm text-center py-6">Este candidato subió su CV como archivo adjunto.</p></div>';
    }

    return html;
}

function section(title, body) {
    return '<div class="cv-section"><p class="cv-section-title">' + title + '</p>' + body + '</div>';
}

function field(label, value, type) {
    if (!value) return '';
    var contenidoCampo = type === 'html' ? value : '<span>' + esc(value) + '</span>';
    return '<div><p style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:rgba(26,26,46,.4);margin-bottom:.2rem">' + label + '</p>' + contenidoCampo + '</div>';
}

function esc(str) {
    return String(str)
        .replace(/&/g,'&amp;')
        .replace(/</g,'&lt;')
        .replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;');
}

function cerrarCvModal(eventoCierre) {
    if (eventoCierre.target === document.getElementById('cv-overlay')) {
        document.getElementById('cv-overlay').classList.remove('abierto');
    }
}
function cerrarCvModalBtn() {
    document.getElementById('cv-overlay').classList.remove('abierto');
}

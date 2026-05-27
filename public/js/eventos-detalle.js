/**
 * eventos-detalle.js — logica de la pagina de detalle de evento
 */
async function toggleSeguirPromotora(btn) {
    const empresaId = btn.dataset.empresaId;
    btn.classList.add('cargando');
    try {
        const res = await fetch(`/api/seguimientos/${empresaId}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        });
        const data = await res.json();
        if (data.success) {
            const texto = btn.querySelector('.btn-seguir-texto');
            if (data.siguiendo) {
                btn.classList.add('siguiendo');
                texto.textContent = 'Siguiendo';
            } else {
                btn.classList.remove('siguiendo');
                texto.textContent = 'Seguir';
            }
        }
    } catch (e) {
        console.error('Error al seguir promotora', e);
    } finally {
        btn.classList.remove('cargando');
    }
}

// ── Valoraciones de evento ─────────────────────────────────────
var estrellaPuntuacion = 0;

function seleccionarEstrella(valor) {
    estrellaPuntuacion = valor;
    document.getElementById('puntuacion-seleccionada').value = valor;
    pintarEstrellas(valor, true);
}

function resaltarEstrellas(valor) {
    pintarEstrellas(valor, false);
}

function restaurarEstrellas() {
    pintarEstrellas(estrellaPuntuacion, true);
}

function pintarEstrellas(valor, esSeleccion) {
    var estrellas = document.getElementsByClassName('estrella-selectable');
    for (var i = 0; i < estrellas.length; i++) {
        estrellas[i].style.color = i < valor
            ? '#f59e0b'
            : (esSeleccion ? 'rgba(245,241,234,0.2)' : 'rgba(245,241,234,0.1)');
    }
}

function enviarValoracionEvento(eventoId) {
    var puntuacion = parseInt(document.getElementById('puntuacion-seleccionada').value, 10);
    if (!puntuacion || puntuacion < 1 || puntuacion > 5) {
        vibezAlerta('Selecciona una puntuación', 'Elige entre 1 y 5 estrellas antes de enviar.', 'warning');
        return;
    }
    var comentario = document.getElementById('comentario-valoracion').value.trim();

    fetch('/api/valoraciones/eventos/' + eventoId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ puntuacion: puntuacion, comentario: comentario }),
    })
    .then(function (response) { return response.json(); })
    .then(function (data) {
        if (data.success) {
            // Ocultar el formulario
            var form = document.getElementById('form-valoracion-evento');
            if (form) { form.style.display = 'none'; }

            // Actualizar media numérica y estrellas del resumen
            document.getElementById('media-numerica').textContent = data.media;
            document.getElementById('total-valoraciones-texto').textContent =
                data.total + ' ' + (data.total === 1 ? 'valoración' : 'valoraciones');

            // Insertar la nueva reseña al inicio del contenedor (si tiene comentario)
            var contenedor = document.getElementById('contenedor-resenyas');
            if (data.valoracion.comentario && contenedor) {
                var div = document.createElement('div');
                div.style.cssText = 'border-bottom:1px solid var(--line);padding-bottom:1.1rem;';
                var inicialAutor = data.valoracion.autor ? data.valoracion.autor.charAt(0).toUpperCase() : '?';
                var estrellas = '';
                for (var i = 1; i <= 5; i++) { estrellas += i <= data.valoracion.puntuacion ? '★' : '☆'; }
                div.innerHTML =
                    '<div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">' +
                        '<div style="width:32px;height:32px;border-radius:50%;overflow:hidden;background:linear-gradient(135deg,#4e3a96,#7c3aed);display:flex;align-items:center;justify-content:center;flex-shrink:0;">' +
                            (data.valoracion.foto
                                ? '<img src="' + data.valoracion.foto + '" style="width:100%;height:100%;object-fit:cover;" alt="">'
                                : '<span style="color:#fff;font-size:12px;font-weight:900;font-family:Anton,sans-serif;">' + inicialAutor + '</span>'
                            ) +
                        '</div>' +
                        '<div style="flex:1;min-width:0;">' +
                            '<p style="font-family:Archivo,sans-serif;font-size:13px;font-weight:700;color:var(--ink);margin:0;">' + data.valoracion.autor + '</p>' +
                            '<div style="color:#f59e0b;font-size:0.8rem;">' + estrellas + '</div>' +
                        '</div>' +
                        '<span style="font-size:9px;color:rgba(245,241,234,0.25);">' + data.valoracion.fecha + '</span>' +
                    '</div>' +
                    '<p style="font-family:Archivo,sans-serif;font-size:13px;color:var(--ink-dim);line-height:1.6;margin:0;">' +
                        data.valoracion.comentario +
                    '</p>';
                contenedor.insertBefore(div, contenedor.firstChild);
            }

            showSuccessAlert('¡Gracias por tu valoración!', data.message);
        } else {
            vibezAlerta('No se pudo enviar', data.message, 'error');
        }
    })
    .catch(function (e) {
        console.error('Error al valorar evento:', e);
        vibezAlerta('Error', 'No se pudo enviar la valoración. Inténtalo de nuevo.', 'error');
    });
}

// Copia el código del cupón al portapapeles y cambia el texto del botón
function copiarCodigoCupon(codigo, id) {
    navigator.clipboard.writeText(codigo).then(function () {
        var btn = document.getElementById('btn-copiar-' + id);
        var textoOriginal = btn.textContent;
        btn.textContent = '¡Copiado!';
        btn.style.background = 'rgba(74,222,128,0.2)';
        btn.style.color = '#4ade80';
        // Volver al estado original después de 2 segundos
        setTimeout(function () {
            btn.textContent = textoOriginal;
            btn.style.background = 'rgba(168,85,247,0.2)';
            btn.style.color = '#c084fc';
        }, 2000);
    });
}

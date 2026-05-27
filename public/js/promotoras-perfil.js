/**
 * promotoras-perfil.js — logica perfil promotora
 */
/* ── Seguir promotora ── */
async function toggleSeguirPromotora(btn) {
    var empresaId = btn.dataset.empresaId;
    btn.classList.add('cargando');
    try {
        var res = await fetch('/api/seguimientos/' + empresaId + '/toggle', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        });
        var data = await res.json();
        if (data.success) {
            var texto = btn.querySelector('.btn-seguir-texto');
            if (data.siguiendo) {
                btn.classList.add('siguiendo');
                texto.textContent = 'Siguiendo';
            } else {
                btn.classList.remove('siguiendo');
                texto.textContent = 'Seguir';
            }
        }
    } catch (e) {
        console.error('Error al seguir promotora:', e);
    } finally {
        btn.classList.remove('cargando');
    }
}

/* ── Valoraciones empresa ── */
var estrellaPuntuacionEmpresa = 0;

function seleccionarEstrellaEmpresa(valor) {
    estrellaPuntuacionEmpresa = valor;
    document.getElementById('puntuacion-empresa').value = valor;
    pintarEstrellasEmpresa(valor, true);
}

function resaltarEstrellasEmpresa(valor) {
    pintarEstrellasEmpresa(valor, false);
}

function restaurarEstrellasEmpresa() {
    pintarEstrellasEmpresa(estrellaPuntuacionEmpresa, true);
}

function pintarEstrellasEmpresa(valor, esSeleccion) {
    var estrellas = document.getElementsByClassName('estrella-empresa');
    for (var i = 0; i < estrellas.length; i++) {
        estrellas[i].style.color = i < valor
            ? '#f59e0b'
            : (esSeleccion ? 'rgba(245,241,234,0.2)' : 'rgba(245,241,234,0.1)');
    }
}

function enviarValoracionEmpresa(empresaId) {
    var puntuacion = parseInt(document.getElementById('puntuacion-empresa').value, 10);
    if (!puntuacion || puntuacion < 1 || puntuacion > 5) {
        vibezAlerta('Selecciona una puntuación', 'Elige entre 1 y 5 estrellas antes de enviar.', 'warning');
        return;
    }
    var comentario = document.getElementById('comentario-empresa').value.trim();

    fetch('/api/valoraciones/empresas/' + empresaId, {
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
            // Ocultar formulario
            var form = document.getElementById('form-valoracion-empresa');
            if (form) { form.style.display = 'none'; }

            // Actualizar media y total en el resumen
            document.getElementById('media-numerica-empresa').textContent = data.media;
            document.getElementById('total-empresa-texto').textContent =
                data.total + ' ' + (data.total === 1 ? 'valoración' : 'valoraciones');

            // Actualizar también en el hero
            var heroTexto = document.getElementById('media-empresa-texto');
            if (heroTexto) {
                heroTexto.textContent = data.media + ' · ' + data.total + ' ' + (data.total === 1 ? 'reseña' : 'reseñas');
            }

            // Insertar la nueva reseña al inicio (si tiene comentario)
            var contenedor = document.getElementById('contenedor-resenyas-empresa');
            if (data.valoracion.comentario) {
                if (!contenedor) {
                    contenedor = document.createElement('div');
                    contenedor.id = 'contenedor-resenyas-empresa';
                    contenedor.style.cssText = 'display:flex;flex-direction:column;gap:1.1rem;';
                    document.getElementById('seccion-valoraciones-empresa').appendChild(contenedor);
                }
                var div = document.createElement('div');
                div.style.cssText = 'border-bottom:1px solid var(--line);padding-bottom:1.1rem;';
                var inicial = data.valoracion.autor ? data.valoracion.autor.charAt(0).toUpperCase() : '?';
                var estrellas = '';
                for (var i = 1; i <= 5; i++) { estrellas += i <= data.valoracion.puntuacion ? '★' : '☆'; }
                div.innerHTML =
                    '<div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">' +
                        '<div style="width:32px;height:32px;border-radius:50%;overflow:hidden;background:linear-gradient(135deg,#4e3a96,#7c3aed);display:flex;align-items:center;justify-content:center;flex-shrink:0;">' +
                            (data.valoracion.foto
                                ? '<img src="' + data.valoracion.foto + '" style="width:100%;height:100%;object-fit:cover;" alt="">'
                                : '<span style="color:#fff;font-size:12px;font-weight:900;">' + inicial + '</span>'
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
        console.error('Error al valorar empresa:', e);
        vibezAlerta('Error', 'No se pudo enviar la valoración. Inténtalo de nuevo.', 'error');
    });
}

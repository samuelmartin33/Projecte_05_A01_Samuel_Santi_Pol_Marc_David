/* ════════════════════════════════════════════════════════════════
   admin-canciones.js — Lógica AJAX del CRUD de canciones (Admin VIBEZ)

   Reglas de código:
   - Sin addEventListener (se usan handlers directos: onsubmit, onkeydown)
   - Sin querySelector/querySelectorAll
   - Variables con nombres descriptivos en español
════════════════════════════════════════════════════════════════ */

/* ── Variables globales ──────────────────────────────────────────── */
var modoEdicion = false;
var csrfToken   = document.getElementsByName('csrf-token')[0]
                      ? document.getElementsByName('csrf-token')[0].getAttribute('content')
                      : document.getElementById('csrf-meta').getAttribute('content');

/* Lee el CSRF del meta tag estándar de Laravel */
(function inicializarCsrf() {
    var metas = document.getElementsByTagName('meta');
    for (var i = 0; i < metas.length; i++) {
        if (metas[i].getAttribute('name') === 'csrf-token') {
            csrfToken = metas[i].getAttribute('content');
            break;
        }
    }
}());

/* ── Conectar el onsubmit del formulario al cargar ───────────────── */
window.onload = function () {
    var formulario = document.getElementById('form-cancion');
    if (formulario) {
        formulario.onsubmit = enviarFormularioCancion;
    }
};

/* ── Cerrar modal con Escape (sin addEventListener) ──────────────── */
document.onkeydown = function (evento) {
    if (evento.key === 'Escape') {
        cerrarModal();
    }
};

/* ── Abrir modal en modo CREAR ───────────────────────────────────── */
function abrirModalCrear() {
    modoEdicion = false;
    document.getElementById('modal-cancion-titulo').textContent = 'Nueva canción';
    document.getElementById('btn-guardar-cancion').textContent  = 'Crear canción';
    document.getElementById('cancion-id').value       = '';
    document.getElementById('cancion-titulo').value   = '';
    document.getElementById('cancion-artista').value  = '';
    document.getElementById('cancion-duracion').value = '';
    document.getElementById('cancion-precio').value   = '';
    document.getElementById('cancion-activa').checked = true;

    var checkboxesGenero = document.getElementsByClassName('chk-genero');
    for (var i = 0; i < checkboxesGenero.length; i++) {
        checkboxesGenero[i].checked = false;
    }

    // Limpiar portada al crear
    document.getElementById('portada-preview-wrap').style.display = 'none';
    document.getElementById('portada-preview-img').src = '';
    document.getElementById('portada-actual-label').textContent = '';
    document.getElementById('cancion-portada').value = '';

    // Limpiar audio al crear
    document.getElementById('audio-preview-wrap').style.display = 'none';
    document.getElementById('audio-preview').src = '';
    document.getElementById('cancion-audio').value = '';

    document.getElementById('modal-cancion-overlay').classList.add('abierto');
    document.getElementById('cancion-titulo').focus();
}

/* ── Abrir modal en modo EDITAR ──────────────────────────────────── */
function abrirModalEditar(idCancion) {
    modoEdicion = true;
    document.getElementById('modal-cancion-titulo').textContent = 'Editar canción';
    document.getElementById('btn-guardar-cancion').textContent  = 'Guardar cambios';

    fetch('/admin/canciones/' + idCancion, {
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(function (respuesta) { return respuesta.json(); })
    .then(function (datos) {
        document.getElementById('cancion-id').value       = datos.id;
        document.getElementById('cancion-titulo').value   = datos.titulo;
        document.getElementById('cancion-artista').value  = datos.artista;
        document.getElementById('cancion-duracion').value = datos.duracion_segundos;
        document.getElementById('cancion-precio').value   = datos.precio;
        document.getElementById('cancion-activa').checked = datos.activa;

        var checkboxesGenero = document.getElementsByClassName('chk-genero');
        for (var i = 0; i < checkboxesGenero.length; i++) {
            checkboxesGenero[i].checked = datos.generos.indexOf(checkboxesGenero[i].value) !== -1;
        }

        // Mostrar portada si la canción ya tiene imagen
        var portadaWrap  = document.getElementById('portada-preview-wrap');
        var portadaImg   = document.getElementById('portada-preview-img');
        var portadaLabel = document.getElementById('portada-actual-label');
        if (datos.portada_url) {
            portadaImg.src           = datos.portada_url;
            portadaLabel.textContent = 'Portada actual: ' + datos.portada_url.split('/').pop();
            portadaWrap.style.display = 'flex';
        } else {
            portadaWrap.style.display = 'none';
            portadaImg.src = '';
            portadaLabel.textContent = '';
        }
        document.getElementById('cancion-portada').value = '';

        // Mostrar reproductor si la canción ya tiene audio
        var audioWrap  = document.getElementById('audio-preview-wrap');
        var audioEl    = document.getElementById('audio-preview');
        var audioLabel = document.getElementById('audio-actual-label');
        if (datos.audio_url) {
            audioEl.src            = datos.audio_url;
            audioLabel.textContent = 'Audio actual: ' + datos.audio_url.split('/').pop();
            audioWrap.style.display = 'block';
        } else {
            audioWrap.style.display = 'none';
            audioEl.src = '';
        }
        document.getElementById('cancion-audio').value = '';

        document.getElementById('modal-cancion-overlay').classList.add('abierto');
        document.getElementById('cancion-titulo').focus();
    })
    .catch(function () {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo cargar la canción.',
            background: '#0d0a18',
            color: '#f5f1ea'
        });
    });
}

/* ── Cerrar modal ────────────────────────────────────────────────── */
function cerrarModal() {
    document.getElementById('modal-cancion-overlay').classList.remove('abierto');
}

/* ── Cerrar modal si se hace clic en el overlay (fuera del panel) ── */
function cerrarModalSiOverlay(evento) {
    if (evento.target === document.getElementById('modal-cancion-overlay')) {
        cerrarModal();
    }
}

/* ── Enviar formulario AJAX (crear o editar) ─────────────────────── */
function enviarFormularioCancion(evento) {
    evento.preventDefault();

    var idCancion  = document.getElementById('cancion-id').value;
    var urlDestino = modoEdicion ? '/admin/canciones/' + idCancion : '/admin/canciones';
    var datosForm  = new FormData(this);

    /* FormData omite checkboxes no marcados: garantizar activa=0 si está desmarcado */
    if (!document.getElementById('cancion-activa').checked) {
        datosForm.set('activa', '0');
    }

    /* Laravel necesita _method=PUT para simular PUT desde POST */
    if (modoEdicion) {
        datosForm.append('_method', 'PUT');
    }

    var botonGuardar = document.getElementById('btn-guardar-cancion');
    botonGuardar.disabled    = true;
    botonGuardar.textContent = 'Guardando…';

    fetch(urlDestino, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: datosForm
    })
    .then(function (respuesta) {
        if (!respuesta.ok) {
            return respuesta.json().then(function (datos) { throw datos; });
        }
        return respuesta.json();
    })
    .then(function (datos) {
        cerrarModal();
        Swal.fire({
            icon: 'success',
            title: datos.mensaje,
            background: '#0d0a18',
            color: '#f5f1ea',
            timer: 2000,
            showConfirmButton: false
        }).then(function () { recargarTabla(); });
    })
    .catch(function (error) {
        var mensaje = 'Error al guardar.';
        if (error && error.errors) {
            mensaje = Object.values(error.errors).map(function (arr) { return arr[0]; }).join('<br>');
        } else if (error && error.message) {
            mensaje = error.message;
        }
        Swal.fire({
            icon: 'error',
            title: 'Error de validación',
            html: mensaje,
            background: '#0d0a18',
            color: '#f5f1ea'
        });
    })
    .finally(function () {
        botonGuardar.disabled    = false;
        botonGuardar.textContent = modoEdicion ? 'Guardar cambios' : 'Crear canción';
    });
}

/* ── Confirmar y ejecutar desactivación ──────────────────────────── */
function confirmarDesactivar(idCancion, tituloCancion) {
    Swal.fire({
        title: '¿Desactivar canción?',
        html: 'La canción <strong>' + tituloCancion + '</strong> quedará inactiva y no aparecerá disponible.',
        icon: 'warning',
        background: '#0d0a18',
        color: '#f5f1ea',
        showCancelButton: true,
        confirmButtonText: 'Sí, desactivar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: 'rgba(245,241,234,0.10)'
    }).then(function (resultado) {
        if (!resultado.isConfirmed) { return; }

        fetch('/admin/canciones/' + idCancion, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: '_method=DELETE'
        })
        .then(function (respuesta) {
            if (!respuesta.ok) {
                return respuesta.json().then(function (datos) { throw datos; });
            }
            return respuesta.json();
        })
        .then(function () {
            Swal.fire({
                icon: 'success',
                title: 'Canción desactivada',
                background: '#0d0a18',
                color: '#f5f1ea',
                timer: 1800,
                showConfirmButton: false
            }).then(function () { recargarTabla(); });
        })
        .catch(function (error) {
            var mensaje = (error && error.mensaje) ? error.mensaje : 'No se pudo desactivar la canción.';
            Swal.fire({
                icon: 'error',
                title: 'No se puede desactivar',
                text: mensaje,
                background: '#0d0a18',
                color: '#f5f1ea'
            });
        });
    });
}

/* ── Previsualizar portada seleccionada ──────────────────────────── */
function previsualizarPortada(inputFile) {
    var wrap  = document.getElementById('portada-preview-wrap');
    var img   = document.getElementById('portada-preview-img');
    var label = document.getElementById('portada-actual-label');

    if (!inputFile.files || !inputFile.files[0]) {
        wrap.style.display = 'none';
        return;
    }

    var archivo = inputFile.files[0];
    var lector  = new FileReader();
    lector.onload = function (e) {
        img.src             = e.target.result;
        label.textContent   = archivo.name;
        wrap.style.display  = 'flex';
    };
    lector.readAsDataURL(archivo);
}

/* ── Recargar el tbody de la tabla vía AJAX ──────────────────────── */
function recargarTabla() {
    fetch('/admin/canciones', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(function (respuesta) { return respuesta.json(); })
    .then(function (datos) {
        document.getElementById('tabla-canciones-body').innerHTML = datos.html;
    });
}

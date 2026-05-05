var _ofertaActual = null;

function abrirPostulacion(ofertaId) {
    _ofertaActual = ofertaId;
    document.getElementById('oferta-id-form').value    = ofertaId;
    document.getElementById('oferta-id-archivo').value = ofertaId;
    _mostrarModal('modal-eleccion');
}

function _mostrarModal(id) {
    var overlay = document.getElementById('modal-overlay');
    overlay.classList.add('abierto');

    ['modal-eleccion', 'modal-formulario', 'modal-archivo', 'modal-exito'].forEach(function(mid) {
        document.getElementById(mid).style.display = 'none';
    });

    var target = document.getElementById(id);
    target.style.display = id === 'modal-formulario' ? 'flex' : 'block';
}

function cerrarModal() {
    document.getElementById('modal-overlay').classList.remove('abierto');
}

function cerrarAlClickarFuera(e) {
    if (e.target === document.getElementById('modal-overlay')) cerrarModal();
}

function mostrarFormulario()   { _mostrarModal('modal-formulario'); }
function mostrarSubirArchivo() { _mostrarModal('modal-archivo'); }
function volverAEleccion()     { _mostrarModal('modal-eleccion'); }

function agregarExperiencia() {
    var tpl   = document.querySelector('#exp-container .exp-item');
    var clone = tpl.cloneNode(true);
    clone.querySelectorAll('input, textarea').forEach(function(el) { el.value = ''; });
    if (!clone.querySelector('.btn-eliminar-item')) {
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn-eliminar-item';
        btn.title = 'Eliminar';
        btn.innerHTML = '&#10005;';
        btn.onclick = function() { this.closest('.exp-item').remove(); };
        clone.appendChild(btn);
    }
    document.getElementById('exp-container').appendChild(clone);
}

function agregarFormacion() {
    var tpl   = document.querySelector('#edu-container .edu-item');
    var clone = tpl.cloneNode(true);
    clone.querySelectorAll('input').forEach(function(el) { el.value = ''; });
    if (!clone.querySelector('.btn-eliminar-item')) {
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn-eliminar-item';
        btn.title = 'Eliminar';
        btn.innerHTML = '&#10005;';
        btn.onclick = function() { this.closest('.edu-item').remove(); };
        clone.appendChild(btn);
    }
    document.getElementById('edu-container').appendChild(clone);
}

function mostrarNombreArchivo(input) {
    var label = document.getElementById('file-name-label');
    if (input.files.length) {
        label.textContent = '📄 ' + input.files[0].name;
        label.classList.remove('hidden');
    }
}

function dragOver(e) {
    e.preventDefault();
    e.currentTarget.classList.add('drag-over');
}
function dragLeave(e) {
    e.currentTarget.classList.remove('drag-over');
}
function dropFile(e) {
    e.preventDefault();
    e.currentTarget.classList.remove('drag-over');
    var dt = e.dataTransfer;
    if (dt.files.length) {
        var input   = document.getElementById('cv-file-input');
        var allowed = ['application/pdf',
                       'application/msword',
                       'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!allowed.includes(dt.files[0].type)) {
            alert('Formato no permitido. Usa PDF, DOC o DOCX.');
            return;
        }
        var dTrans = new DataTransfer();
        dTrans.items.add(dt.files[0]);
        input.files = dTrans.files;
        mostrarNombreArchivo(input);
    }
}

function _erroresValidacion(data) {
    if (data.errors) {
        return Object.values(data.errors).flat().join('\n');
    }
    return data.message || 'Error al enviar. Revisa los campos e inténtalo de nuevo.';
}

document.getElementById('form-cv').onsubmit = async function(e) {
    e.preventDefault();
    var btn = document.getElementById('btn-enviar-cv');
    btn.disabled = true;
    btn.textContent = 'Enviando...';

    try {
        var res = await fetch('/trabajos/' + _ofertaActual + '/postular', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: new FormData(this),
        });
        var data = await res.json();
        if (data.success) {
            _mostrarModal('modal-exito');
            this.reset();
        } else {
            alert(_erroresValidacion(data));
        }
    } catch(err) {
        alert('Error de conexión. Inténtalo de nuevo.');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Enviar candidatura';
    }
};

document.getElementById('form-archivo').onsubmit = async function(e) {
    e.preventDefault();
    var fileInput = document.getElementById('cv-file-input');
    if (!fileInput.files.length) {
        alert('Por favor, selecciona un archivo CV antes de enviar.');
        return;
    }
    var btn = document.getElementById('btn-enviar-archivo');
    btn.disabled = true;
    btn.textContent = 'Enviando...';

    try {
        var res = await fetch('/trabajos/' + _ofertaActual + '/postular-archivo', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: new FormData(this),
        });
        var data = await res.json();
        if (data.success) {
            _mostrarModal('modal-exito');
            this.reset();
            document.getElementById('file-name-label').classList.add('hidden');
        } else {
            alert(_erroresValidacion(data));
        }
    } catch(err) {
        alert('Error de conexión. Inténtalo de nuevo.');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Enviar candidatura';
    }
};

/**
 * fiesta-crear.js — Validación y lógica del formulario de crear evento Fiesta.
 * Sin toggle de categorías (Fiesta siempre fija), sin checkbox de gratuito.
 */

var fpInicio = null;
var fpFin    = null;

function iniciarFiestaCrear() {
    iniciarFlatpickr();
    iniciarUpload();
}

// ── Flatpickr ─────────────────────────────────────────────────────────────────

function iniciarFlatpickr() {
    var config = {
        enableTime: true, time_24hr: true,
        dateFormat: 'Y-m-d H:i', altInput: true, altFormat: 'd/m/Y H:i',
        locale: 'es', disableMobile: true,
    };
    fpInicio = flatpickr('#fecha_inicio', Object.assign({}, config, {
        onChange: function(dates) {
            if (dates[0] && fpFin) fpFin.set('minDate', dates[0]);
            validarFechaInicio();
        }
    }));
    fpFin = flatpickr('#fecha_fin', Object.assign({}, config));
    if (fpInicio && fpInicio.selectedDates[0]) fpFin.set('minDate', fpInicio.selectedDates[0]);
}

// ── Upload imagen ─────────────────────────────────────────────────────────────

function iniciarUpload() {
    var fileInput  = document.getElementById('imagen_portada_input');
    var zona       = document.getElementById('upload-zona');
    var preview    = document.getElementById('upload-preview');
    var previewImg = document.getElementById('imagen-preview-img');
    var nombreSpan = document.getElementById('upload-nombre');

    fileInput.onchange = function() {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
                nombreSpan.textContent = fileInput.files[0].name;
            };
            reader.readAsDataURL(this.files[0]);
        } else {
            preview.style.display = 'none';
        }
    };
    zona.ondragover  = function(e) { e.preventDefault(); zona.classList.add('dragover'); };
    zona.ondragleave = function()  { zona.classList.remove('dragover'); };
    zona.ondrop      = function()  { zona.classList.remove('dragover'); };
}

// ── Utilidad de errores ───────────────────────────────────────────────────────

function mostrarError(id, msg) {
    var el = document.getElementById(id);
    if (!el) return;
    el.textContent = msg;
    el.style.display = msg ? 'block' : 'none';
}

// ── Validaciones individuales (onblur) ────────────────────────────────────────

function validarTitulo() {
    var val = document.querySelector('[name="titulo"]').value.trim();
    mostrarError('error-titulo', val ? '' : 'El título del evento es obligatorio.');
    return !!val;
}

function validarFechaInicio() {
    var val = document.getElementById('fecha_inicio').value;
    mostrarError('error-fecha-inicio', val ? '' : 'La fecha de inicio es obligatoria.');
    return !!val;
}

function validarUbicacion() {
    var val = document.querySelector('[name="ubicacion_nombre"]').value.trim();
    mostrarError('error-ubicacion', val ? '' : 'El nombre del lugar es obligatorio.');
    return !!val;
}

function validarPrecio() {
    var precio = parseFloat(document.getElementById('precio_base_input').value);
    if (isNaN(precio) || precio < 10) {
        mostrarError('error-precio', 'El precio mínimo para eventos Fiesta es 10 €.');
        return false;
    }
    mostrarError('error-precio', '');
    return true;
}

function validarCamarero() {
    var val = document.getElementById('camarero_id_select').value;
    mostrarError('error-camarero', val ? '' : 'Debes asignar un camarero al evento de Fiesta.');
    return !!val;
}

// ── Envío con validación completa ─────────────────────────────────────────────

function validarYEnviarFiesta() {
    var t  = validarTitulo();
    var f  = validarFechaInicio();
    var u  = validarUbicacion();
    var p  = validarPrecio();
    var ca = validarCamarero();

    if (t && f && u && p && ca) {
        document.querySelector('.form-crear-evento').submit();
    }
}

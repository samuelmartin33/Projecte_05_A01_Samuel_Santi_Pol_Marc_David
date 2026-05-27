/**
 * empresa-eventos-form.js
 * Lógica del formulario de crear/editar evento para empresa:
 * - Inicialización de Flatpickr con vinculación inicio→fin
 * - Toggle del campo precio (gratuito/pago)
 * - Preview de imagen de portada con drag & drop
 * - Actualización de borde al seleccionar categorías
 */

// ── Flatpickr fecha/hora ──────────────────────────────────────────────────────
var fpConfig = {
    enableTime:    true,
    time_24hr:     true,
    dateFormat:    'Y-m-d H:i',
    altInput:      true,
    altFormat:     'd/m/Y H:i',
    locale:        'es',
    disableMobile: true,
};

var fpInicio = flatpickr('#fecha_inicio', Object.assign({}, fpConfig, {
    onChange: function(dates) {
        if (dates[0]) fpFin.set('minDate', dates[0]);
    }
}));
var fpFin = flatpickr('#fecha_fin', Object.assign({}, fpConfig, {
    onChange: function() {}
}));

/* Aplicar minDate si ya hay fecha de inicio cargada (edición) */
if (fpInicio.selectedDates[0]) {
    fpFin.set('minDate', fpInicio.selectedDates[0]);
}

/**
 * Actualiza el borde de la etiqueta de categoría al marcar/desmarcar.
 * @param {HTMLLabelElement} label - Etiqueta con checkbox dentro
 */
function actualizarBordeCat(label) {
    var input = label.querySelector('input');
    label.style.borderColor = input.checked ? 'rgba(168,85,247,0.7)' : 'rgba(245,241,234,0.14)';
}

// ── Precio ────────────────────────────────────────────────────────────────────

/**
 * Activa/desactiva el campo de precio según el checkbox de evento gratuito.
 */
function togglePrecio() {
    var checkbox   = document.getElementById('es_gratuito');
    var precioWrap = document.getElementById('precio-wrap');
    var precioInput = document.getElementById('precio_base_input');

    if (checkbox.checked) {
        precioWrap.classList.add('desactivado');
        precioInput.value = '0';
    } else {
        precioWrap.classList.remove('desactivado');
    }
}

/* Sincronizar al cargar */
togglePrecio();

// ── Preview imagen de portada ─────────────────────────────────────────────────
var fileInput  = document.getElementById('imagen_portada_input');
var zona       = document.getElementById('upload-zona');
var preview    = document.getElementById('upload-preview');
var previewImg = document.getElementById('imagen-preview-img');
var nombreSpan = document.getElementById('upload-nombre');

/* Muestra la vista previa al seleccionar un archivo */
fileInput.onchange = function() {
    if (this.files && this.files[0]) {
        var file   = this.files[0];
        var reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src        = e.target.result;
            preview.style.display = 'block';
            nombreSpan.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
};

/* Feedback visual de drag & drop */
zona.ondragover = function(e) { e.preventDefault(); zona.classList.add('dragover'); };
zona.ondragleave = function()  { zona.classList.remove('dragover'); };
zona.ondrop      = function()  { zona.classList.remove('dragover'); };

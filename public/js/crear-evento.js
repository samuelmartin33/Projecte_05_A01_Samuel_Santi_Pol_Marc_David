/**
 * crear-evento.js — Lógica y validación del formulario de crear evento (empresa)
 *
 * Patrón del proyecto: funciones globales llamadas desde atributos inline del HTML.
 * Sin addEventListener. Mensajes de error inline bajo cada campo.
 */

var FIESTA_ID = null;
var fpInicio  = null;
var fpFin     = null;

/** Punto de entrada — se llama desde el HTML */
function iniciarCrearEvento(fiestaId) {
    FIESTA_ID = fiestaId || null;
    iniciarFlatpickr();
    iniciarUpload();
    togglePrecio();
    verificarFiesta();
}

// ── Utilidad de mensajes de error ─────────────────────────────────────────────

/** Muestra u oculta el mensaje de error de un campo */
function mostrarError(id, msg) {
    var el = document.getElementById(id);
    if (!el) return;
    el.textContent = msg;
    el.style.display = msg ? 'block' : 'none';
}

// ── Validaciones por campo (se llaman desde onblur) ───────────────────────────

function validarTitulo() {
    var val = document.querySelector('[name="titulo"]').value.trim();
    mostrarError('error-titulo', val ? '' : 'El título del evento es obligatorio.');
    return !!val;
}

function validarCategorias() {
    var cats = document.querySelectorAll('[name="categorias[]"]:checked');
    mostrarError('error-categorias', cats.length === 0 ? 'Selecciona al menos una categoría.' : '');
    return cats.length > 0;
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
    var esGratuito = document.getElementById('es_gratuito').checked;
    if (esGratuito) { mostrarError('error-precio', ''); return true; }

    var precio   = parseFloat(document.getElementById('precio_base_input').value);
    var cbFiesta = FIESTA_ID ? document.querySelector('[name="categorias[]"][value="' + FIESTA_ID + '"]') : null;
    var esFiesta = cbFiesta && cbFiesta.checked;

    if (isNaN(precio) || precio < 0) {
        mostrarError('error-precio', 'El precio no puede ser negativo.');
        return false;
    }
    if (esFiesta && precio < 10) {
        mostrarError('error-precio', 'Los eventos de Fiesta tienen un precio mínimo de 10 €.');
        return false;
    }
    mostrarError('error-precio', '');
    return true;
}

function validarCamarero() {
    var cbFiesta = FIESTA_ID ? document.querySelector('[name="categorias[]"][value="' + FIESTA_ID + '"]') : null;
    if (!cbFiesta || !cbFiesta.checked) { mostrarError('error-camarero', ''); return true; }
    var val = document.getElementById('camarero_id_select').value;
    mostrarError('error-camarero', val ? '' : 'Debes asignar un camarero al evento de Fiesta.');
    return !!val;
}

// ── Envío con validación completa ─────────────────────────────────────────────

/** Valida todos los campos y envía el formulario solo si todo es correcto. */
function validarYEnviar() {
    // Ejecutar todas las validaciones para mostrar todos los errores a la vez
    var t  = validarTitulo();
    var c  = validarCategorias();
    var f  = validarFechaInicio();
    var u  = validarUbicacion();
    var p  = validarPrecio();
    var ca = validarCamarero();

    if (t && c && f && u && p && ca) {
        document.querySelector('.form-crear-evento').submit();
    }
}

// ── Categorías ────────────────────────────────────────────────────────────────

function actualizarBordeCat(label) {
    var input = label.querySelector('input');
    label.style.borderColor = input.checked ? 'rgba(168,85,247,0.7)' : 'rgba(245,241,234,0.14)';
    validarCategorias();
    verificarFiesta();
}

// ── Lógica Fiesta ─────────────────────────────────────────────────────────────

function verificarFiesta() {
    if (!FIESTA_ID) return;
    var cb = document.querySelector('input[name="categorias[]"][value="' + FIESTA_ID + '"]');
    if (!cb) return;
    if (cb.checked) {
        activarRestriccionesFiesta();
    } else {
        desactivarRestriccionesFiesta();
    }
}

function activarRestriccionesFiesta() {
    document.getElementById('fiesta-extras').style.display = 'block';

    var gratuito = document.getElementById('es_gratuito');
    gratuito.checked  = false;
    gratuito.disabled = true;
    gratuito.closest('label').style.opacity       = '0.35';
    gratuito.closest('label').style.pointerEvents = 'none';
    document.getElementById('precio-wrap').classList.remove('desactivado');

    var precio = document.getElementById('precio_base_input');
    precio.min         = 10;
    precio.placeholder = 'Mín. 10,00 €';

    var edad = document.getElementById('edad_minima_input');
    edad.value         = 18;
    edad.readOnly      = true;
    edad.style.opacity = '0.6';
}

function desactivarRestriccionesFiesta() {
    document.getElementById('fiesta-extras').style.display = 'none';
    mostrarError('error-camarero', '');

    var gratuito = document.getElementById('es_gratuito');
    gratuito.disabled = false;
    gratuito.closest('label').style.opacity       = '1';
    gratuito.closest('label').style.pointerEvents = '';

    var precio = document.getElementById('precio_base_input');
    precio.min         = 0;
    precio.placeholder = '0.00';

    togglePrecio();

    var edad = document.getElementById('edad_minima_input');
    edad.readOnly      = false;
    edad.style.opacity = '1';
}

// ── Precio ────────────────────────────────────────────────────────────────────

function togglePrecio() {
    var gratuito    = document.getElementById('es_gratuito');
    var precioWrap  = document.getElementById('precio-wrap');
    var precioInput = document.getElementById('precio_base_input');

    if (gratuito.checked) {
        precioWrap.classList.add('desactivado');
        precioInput.value = '0';
        mostrarError('error-precio', '');
    } else {
        precioWrap.classList.remove('desactivado');
    }
}

// ── Flatpickr ─────────────────────────────────────────────────────────────────

function iniciarFlatpickr() {
    var config = {
        enableTime:    true,
        time_24hr:     true,
        dateFormat:    'Y-m-d H:i',
        altInput:      true,
        altFormat:     'd/m/Y H:i',
        locale:        'es',
        disableMobile: true,
    };

    fpInicio = flatpickr('#fecha_inicio', Object.assign({}, config, {
        onChange: function(dates) {
            if (dates[0] && fpFin) fpFin.set('minDate', dates[0]);
            validarFechaInicio();
        }
    }));

    fpFin = flatpickr('#fecha_fin', Object.assign({}, config));

    if (fpInicio && fpInicio.selectedDates[0]) {
        fpFin.set('minDate', fpInicio.selectedDates[0]);
    }
}

// ── Imagen / upload ───────────────────────────────────────────────────────────

function iniciarUpload() {
    var fileInput  = document.getElementById('imagen_portada_input');
    var zona       = document.getElementById('upload-zona');
    var preview    = document.getElementById('upload-preview');
    var previewImg = document.getElementById('imagen-preview-img');
    var nombreSpan = document.getElementById('upload-nombre');

    fileInput.onchange = function() {
        if (this.files && this.files[0]) {
            var file   = this.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
                nombreSpan.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    };

    zona.ondragover  = function(e) { e.preventDefault(); zona.classList.add('dragover'); };
    zona.ondragleave = function()  { zona.classList.remove('dragover'); };
    zona.ondrop      = function()  { zona.classList.remove('dragover'); };
}

/* ── Filtros AJAX para /eventos ─────────────────────────────────────────
   Todas las interacciones de filtro actualizan el grid sin recargar la página.
   Uso desde HTML: oninput, onclick en triggers ev-csel.
──────────────────────────────────────────────────────────────────────── */

var _evTimer = null;

/* ── Búsqueda de texto: debounce 350ms ── */
function buscarEventos(valor) {
    clearTimeout(_evTimer);
    _evTimer = setTimeout(_evFetch, 350);
}

/* ── Orden: actualiza UI y lanza fetch ── */
function pickEvOrden(val, label) {
    document.getElementById('ev-orden-val').value = val;
    document.getElementById('ev-orden-label').textContent = label;
    document.getElementById('ev-csel-orden').classList.remove('open');
    document.querySelectorAll('#ev-csel-orden .ev-csel-opt').forEach(function (li) {
        li.classList.toggle('selected', li.textContent.trim() === label);
    });
    _evFetch();
}

/* ── Toggle dropdown ev-csel ── */
function toggleEvCsel(id) {
    var el = document.getElementById(id);
    document.querySelectorAll('.ev-csel.open').forEach(function (a) {
        if (a.id !== id) a.classList.remove('open');
    });
    el.classList.toggle('open');
}

/* Cierra cualquier ev-csel abierto al clicar fuera */
document.onclick = function (e) {
    if (!e.target.closest('.ev-csel')) {
        document.querySelectorAll('.ev-csel.open').forEach(function (el) {
            el.classList.remove('open');
        });
    }
});

/* Intercepta el submit nativo del formulario y lo convierte en AJAX */
function _evFormSubmit(e) {
    e.preventDefault();
    _evFetch();
    return false;
}

/* ── Fetch principal ── */
function _evFetch() {
    var params = new URLSearchParams();

    var inputBuscar = document.querySelector('[name="buscar"]');
    var buscar = inputBuscar ? inputBuscar.value.trim() : '';
    if (buscar) params.set('buscar', buscar);

    var orden = document.getElementById('ev-orden-val');
    if (orden && orden.value && orden.value !== 'nuevo') params.set('orden', orden.value);

    var desde = document.getElementById('ev-fecha-desde');
    if (desde && desde.value) params.set('fecha_desde', desde.value);

    var hasta = document.getElementById('ev-fecha-hasta');
    if (hasta && hasta.value) params.set('fecha_hasta', hasta.value);

    var contenedor = document.getElementById('ev-resultado');
    if (!contenedor) return;

    contenedor.style.opacity   = '0.45';
    contenedor.style.transition = 'opacity 0.15s';

    fetch('/eventos?' + params.toString(), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
        contenedor.innerHTML     = data.html;
        contenedor.style.opacity = '1';

        /* Contador */
        var contador = document.querySelector('.ev-count');
        if (contador) {
            contador.textContent = data.total + (data.total === 1 ? ' evento' : ' eventos');
        }

        /* Mostrar/ocultar botón limpiar */
        var limpiar = document.getElementById('ev-limpiar');
        if (limpiar) {
            var hayFiltros = buscar
                || (orden && orden.value && orden.value !== 'nuevo')
                || (desde && desde.value)
                || (hasta && hasta.value);
            limpiar.style.display = hayFiltros ? '' : 'none';
        }
    })
    .catch(function () {
        contenedor.style.opacity = '1';
    });
}

/* ── Flatpickr para filtros de fecha ── */
/* Se inicializa al cargar el script (DOM ya disponible, script al final del body) */
var _fpOpts = {
    locale:      'es',
    dateFormat:  'Y-m-d',
    disableMobile: true,
    onClose: function () { _evFetch(); }
};

/* Inicializar selectores de rango de fecha si existen */
var _fpDesde = document.getElementById('ev-fecha-desde');
var _fpHasta = document.getElementById('ev-fecha-hasta');
if (_fpDesde) flatpickr(_fpDesde, _fpOpts);
if (_fpHasta) flatpickr(_fpHasta, _fpOpts);

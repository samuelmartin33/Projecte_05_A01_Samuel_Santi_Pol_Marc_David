/* ════════════════════════════════════════════════════════════════
   admin-bonos.js — Lógica AJAX del panel de bonos (Admin VIBEZ)

   Reglas de código:
   - Sin addEventListener → onchange, onsubmit, onkeydown directos
   - Sin querySelector/querySelectorAll → getElementById / getElementsByClassName
   - Variables con nombres descriptivos en español
════════════════════════════════════════════════════════════════ */

/* ── Leer CSRF ───────────────────────────────────────────────────── */
var csrfTokenBonos = '';

(function leerCsrf() {
    var metas = document.getElementsByTagName('meta');
    for (var i = 0; i < metas.length; i++) {
        if (metas[i].getAttribute('name') === 'csrf-token') {
            csrfTokenBonos = metas[i].getAttribute('content');
            break;
        }
    }
}());

/* ── Conectar eventos al cargar ──────────────────────────────────── */
window.onload = function () {
    var selectEvento  = document.getElementById('filtro-evento');
    var selectEmpresa = document.getElementById('filtro-empresa');
    var formularioTipo = document.getElementById('form-tipo');

    if (selectEvento)  { selectEvento.onchange  = filtrarBonos; }
    if (selectEmpresa) { selectEmpresa.onchange = filtrarBonos; }
    if (formularioTipo) { formularioTipo.onsubmit = enviarFormularioTipo; }
};

/* ── Cerrar modal con Escape ─────────────────────────────────────── */
document.onkeydown = function (evento) {
    if (evento.key === 'Escape') { cerrarModal(); }
};

/* ── Filtrar bonos via AJAX ──────────────────────────────────────── */
function filtrarBonos() {
    var eventoId  = document.getElementById('filtro-evento').value;
    var empresaId = document.getElementById('filtro-empresa').value;

    var parametros = new URLSearchParams();
    if (eventoId)  { parametros.set('evento_id',  eventoId); }
    if (empresaId) { parametros.set('empresa_id', empresaId); }

    fetch('/admin/bonos?' + parametros.toString(), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfTokenBonos,
            'Accept': 'application/json'
        }
    })
    .then(function (respuesta) { return respuesta.json(); })
    .then(function (datos) {
        actualizarCards(datos.resumen, datos.total_vendidos, datos.total_ingresos);
        document.getElementById('tbody-bonos-activos').innerHTML = datos.html_bonos_activos;
    })
    .catch(function () {
        Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudieron cargar los datos.', background: '#0d0a18', color: '#f5f1ea' });
    });
}

/* ── Actualizar metric cards ─────────────────────────────────────── */
function actualizarCards(resumen, totalVendidos, totalIngresos) {
    // Vaciar el contenedor de cards de tipos
    var contenedorCards = document.getElementById('cards-tipos');
    if (!contenedorCards) { return; }

    var html = '';
    for (var i = 0; i < resumen.length; i++) {
        var item = resumen[i];
        html +=
            '<div class="bono-card">' +
            '<div class="bono-card-label">Bono ' + item.cantidad_bebidas + ' bebidas</div>' +
            '<div class="bono-card-valor">' + item.total_vendidos + '</div>' +
            '<div class="bono-card-sub">' + item.ingresos + ' € · ' + item.precio + ' € / bono</div>' +
            '</div>';
    }
    contenedorCards.innerHTML = html;

    // Actualizar totales globales
    var totalVendidosEl = document.getElementById('total-vendidos');
    var totalIngresosEl = document.getElementById('total-ingresos');
    if (totalVendidosEl) { totalVendidosEl.textContent = totalVendidos; }
    if (totalIngresosEl) { totalIngresosEl.textContent = totalIngresos + ' €'; }
}

/* ── Descargar PDF ───────────────────────────────────────────────── */
function descargarPdfBonos() {
    var eventoId  = document.getElementById('filtro-evento').value;
    var empresaId = document.getElementById('filtro-empresa').value;
    var parametros = new URLSearchParams();
    if (eventoId)  { parametros.set('evento_id',  eventoId); }
    if (empresaId) { parametros.set('empresa_id', empresaId); }
    window.location.href = '/admin/bonos/pdf?' + parametros.toString();
}

/* ══════════════════════════════════════════════════════════════
   CRUD DE TIPOS DE BONO
══════════════════════════════════════════════════════════════ */

var modoEdicionTipo = false;

/* ── Abrir modal para crear tipo ─────────────────────────────────── */
function abrirModalCrearTipo() {
    modoEdicionTipo = false;
    document.getElementById('modal-tipo-titulo').textContent = 'Nuevo tipo de bono';
    document.getElementById('btn-guardar-tipo').textContent  = 'Crear tipo';
    document.getElementById('tipo-id').value              = '';
    document.getElementById('tipo-cantidad').value        = '';
    document.getElementById('tipo-precio').value          = '';
    document.getElementById('tipo-descripcion').value     = '';
    document.getElementById('tipo-activo').checked        = true;
    document.getElementById('modal-tipo-overlay').classList.add('abierto');
    document.getElementById('tipo-cantidad').focus();
}

/* ── Abrir modal para editar tipo ────────────────────────────────── */
function abrirModalEditarTipo(idTipo) {
    modoEdicionTipo = true;
    document.getElementById('modal-tipo-titulo').textContent = 'Editar tipo de bono';
    document.getElementById('btn-guardar-tipo').textContent  = 'Guardar cambios';

    fetch('/admin/bonos-tipos/' + idTipo, {
        headers: { 'X-CSRF-TOKEN': csrfTokenBonos, 'Accept': 'application/json' }
    })
    .then(function (r) { return r.json(); })
    .then(function (datos) {
        document.getElementById('tipo-id').value          = datos.id;
        document.getElementById('tipo-cantidad').value    = datos.cantidad_bebidas;
        document.getElementById('tipo-precio').value      = datos.precio;
        document.getElementById('tipo-descripcion').value = datos.descripcion || '';
        document.getElementById('tipo-activo').checked   = datos.activo;
        document.getElementById('modal-tipo-overlay').classList.add('abierto');
        document.getElementById('tipo-cantidad').focus();
    })
    .catch(function () {
        Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo cargar el tipo.', background: '#0d0a18', color: '#f5f1ea' });
    });
}

/* ── Cerrar modal ────────────────────────────────────────────────── */
function cerrarModal() {
    var overlay = document.getElementById('modal-tipo-overlay');
    if (overlay) { overlay.classList.remove('abierto'); }
}
function cerrarModalSiOverlay(evento) {
    if (evento.target === document.getElementById('modal-tipo-overlay')) { cerrarModal(); }
}

/* ── Enviar formulario (crear/editar tipo) ───────────────────────── */
function enviarFormularioTipo(evento) {
    evento.preventDefault();

    var idTipo     = document.getElementById('tipo-id').value;
    var urlDestino = modoEdicionTipo ? '/admin/bonos-tipos/' + idTipo : '/admin/bonos-tipos';
    var datosForm  = new FormData(this);

    if (!document.getElementById('tipo-activo').checked) {
        datosForm.set('activo', '0');
    }
    if (modoEdicionTipo) { datosForm.append('_method', 'PUT'); }

    var boton = document.getElementById('btn-guardar-tipo');
    boton.disabled    = true;
    boton.textContent = 'Guardando…';

    fetch(urlDestino, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfTokenBonos, 'Accept': 'application/json' },
        body: datosForm
    })
    .then(function (r) {
        if (!r.ok) { return r.json().then(function (d) { throw d; }); }
        return r.json();
    })
    .then(function (datos) {
        cerrarModal();
        Swal.fire({ icon: 'success', title: datos.mensaje, background: '#0d0a18', color: '#f5f1ea', timer: 2000, showConfirmButton: false })
            .then(function () { recargarTablaTipos(); });
    })
    .catch(function (error) {
        var mensaje = 'Error al guardar.';
        if (error && error.errors) {
            mensaje = Object.values(error.errors).map(function (arr) { return arr[0]; }).join('<br>');
        } else if (error && error.message) {
            mensaje = error.message;
        }
        Swal.fire({ icon: 'error', title: 'Error', html: mensaje, background: '#0d0a18', color: '#f5f1ea' });
    })
    .finally(function () {
        boton.disabled    = false;
        boton.textContent = modoEdicionTipo ? 'Guardar cambios' : 'Crear tipo';
    });
}

/* ── Toggle activo/inactivo ──────────────────────────────────────── */
function toggleActivoTipo(idTipo) {
    fetch('/admin/bonos-tipos/' + idTipo + '/toggle', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfTokenBonos, 'Accept': 'application/json' }
    })
    .then(function (r) { return r.json(); })
    .then(function () { recargarTablaTipos(); })
    .catch(function () {
        Swal.fire({ icon: 'error', title: 'Error al cambiar estado', background: '#0d0a18', color: '#f5f1ea' });
    });
}

/* ── Eliminar tipo ───────────────────────────────────────────────── */
function confirmarEliminarTipo(idTipo) {
    Swal.fire({
        title: '¿Eliminar tipo de bono?',
        text: 'Solo se puede eliminar si no tiene compras asociadas.',
        icon: 'warning',
        background: '#0d0a18',
        color: '#f5f1ea',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: 'rgba(245,241,234,0.10)'
    }).then(function (resultado) {
        if (!resultado.isConfirmed) { return; }

        fetch('/admin/bonos-tipos/' + idTipo, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfTokenBonos, 'Accept': 'application/json', 'Content-Type': 'application/x-www-form-urlencoded' },
            body: '_method=DELETE'
        })
        .then(function (r) {
            if (!r.ok) { return r.json().then(function (d) { throw d; }); }
            return r.json();
        })
        .then(function () {
            Swal.fire({ icon: 'success', title: 'Tipo eliminado', background: '#0d0a18', color: '#f5f1ea', timer: 1500, showConfirmButton: false })
                .then(function () { recargarTablaTipos(); });
        })
        .catch(function (error) {
            var mensaje = (error && error.mensaje) ? error.mensaje : 'No se pudo eliminar el tipo.';
            Swal.fire({ icon: 'error', title: 'No se puede eliminar', text: mensaje, background: '#0d0a18', color: '#f5f1ea' });
        });
    });
}

/* ── Recargar tabla de tipos via AJAX ────────────────────────────── */
function recargarTablaTipos() {
    fetch('/admin/bonos-tipos', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(function (r) { return r.json(); })
    .then(function (datos) {
        var cuerpo = document.getElementById('tbody-tipos');
        if (cuerpo) { cuerpo.innerHTML = datos.html; }
    });
}

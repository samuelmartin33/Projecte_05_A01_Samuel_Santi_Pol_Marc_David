/**
 * empresa-fiesta.js — Lógica AJAX del CRUD de eventos Fiesta (panel empresa)
 *
 * Patrón del proyecto: funciones globales llamadas desde atributos inline del HTML
 * (onclick, oninput, onchange). Sin addEventListener.
 */

/** Carga los eventos al iniciar la página */
function iniciarFiesta() {
    cargarEventos();
}

/** Lee los filtros activos y lanza la petición AJAX */
function filtrarEventos() {
    const nombre    = document.getElementById('filtro-nombre').value;
    const fecha     = document.getElementById('filtro-fecha').value;
    const ubicacion = document.getElementById('filtro-ubicacion').value;
    cargarEventos(nombre, fecha, ubicacion);
}

/** Llama al endpoint /listar y renderiza la tabla con el resultado */
function cargarEventos(nombre, fecha, ubicacion) {
    nombre    = nombre    ?? '';
    fecha     = fecha     ?? '';
    ubicacion = ubicacion ?? '';

    const params = new URLSearchParams({ nombre, fecha, ubicacion });

    fetch('/empresa/fiesta/listar?' + params, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(r) { return r.json(); })
    .then(function(eventos) { renderizarTabla(eventos); });
}

/** Genera las filas HTML de la tabla según el array de eventos recibido */
function renderizarTabla(eventos) {
    const tbody = document.getElementById('tabla-cuerpo');

    if (eventos.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="vacio">No hay eventos que coincidan con los filtros.</td></tr>';
        return;
    }

    var filas = '';
    for (var i = 0; i < eventos.length; i++) {
        var e = eventos[i];
        var camarero = e.camarero ? e.camarero : '<span style="color:#f59e0b">Sin asignar</span>';
        var aforo    = e.aforo_actual + ' / ' + (e.aforo_maximo ? e.aforo_maximo : '∞');
        var titulo   = e.titulo.replace(/'/g, "\\'");

        filas += '<tr>'
            + '<td><strong>' + e.titulo + '</strong></td>'
            + '<td>' + e.fecha_inicio + '</td>'
            + '<td>' + (e.ubicacion_nombre ?? '—') + '</td>'
            + '<td><span class="badge-fiesta">' + e.precio_base + '</span></td>'
            + '<td>' + aforo + '</td>'
            + '<td>' + camarero + '</td>'
            + '<td>'
            +   '<button class="btn-accion" onclick="abrirModalEditar(' + e.id + ')">Editar</button>'
            +   '<button class="btn-accion danger" onclick="eliminarEvento(' + e.id + ', \'' + titulo + '\')">Eliminar</button>'
            + '</td>'
            + '</tr>';
    }

    tbody.innerHTML = filas;
}

/** Abre el modal con el formulario en blanco para crear un evento nuevo */
function abrirModalCrear() {
    document.getElementById('modal-titulo').textContent = 'Nuevo evento Fiesta';
    document.getElementById('form-evento').reset();
    document.getElementById('campo-id').value = '';
    document.getElementById('modal-overlay').classList.add('activo');
}

/** Carga los datos de un evento via AJAX y abre el modal en modo edición */
function abrirModalEditar(id) {
    fetch('/empresa/fiesta/' + id, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(r) { return r.json(); })
    .then(function(e) {
        document.getElementById('modal-titulo').textContent            = 'Editar evento Fiesta';
        document.getElementById('campo-id').value                     = e.id;
        document.getElementById('campo-titulo').value                 = e.titulo;
        document.getElementById('campo-descripcion').value            = e.descripcion ?? '';
        document.getElementById('campo-fecha-inicio').value           = e.fecha_inicio;
        document.getElementById('campo-fecha-fin').value              = e.fecha_fin ?? '';
        document.getElementById('campo-ubicacion-nombre').value       = e.ubicacion_nombre ?? '';
        document.getElementById('campo-ubicacion-direccion').value    = e.ubicacion_direccion ?? '';
        document.getElementById('campo-precio').value                 = e.precio_base;
        document.getElementById('campo-aforo').value                  = e.aforo_maximo ?? '';
        document.getElementById('campo-camarero').value               = e.camarero_id ?? '';
        document.getElementById('modal-overlay').classList.add('activo');
    });
}

/** Cierra el modal solo si el click fue sobre el fondo oscuro, no sobre el contenido */
function cerrarModalSiFondo(event) {
    if (event.target === document.getElementById('modal-overlay')) {
        cerrarModal();
    }
}

/** Cierra el modal */
function cerrarModal() {
    document.getElementById('modal-overlay').classList.remove('activo');
}

/** Envía el formulario (POST) para crear o actualizar un evento */
function guardarEvento() {
    var id       = document.getElementById('campo-id').value;
    var esEdicion = id !== '';
    var url      = esEdicion ? '/empresa/fiesta/' + id + '/actualizar' : '/empresa/fiesta';
    var form     = document.getElementById('form-evento');
    var datos    = new FormData(form);
    var token    = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: datos,
    })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        if (res.ok) {
            cerrarModal();
            cargarEventos();
            Swal.fire({
                icon: 'success',
                title: res.mensaje,
                timer: 2000,
                showConfirmButton: false,
                background: '#12101e',
                color: '#f5f1ea'
            });
        } else {
            var errores = '';
            if (res.errors) {
                var lista = Object.values(res.errors);
                for (var i = 0; i < lista.length; i++) {
                    errores += lista[i][0] + '\n';
                }
            }
            Swal.fire({
                icon: 'error',
                title: 'Revisa el formulario',
                text: errores || 'Ha ocurrido un error.',
                background: '#12101e',
                color: '#f5f1ea'
            });
        }
    });
}

/** Muestra confirmación y elimina un evento via DELETE AJAX */
function eliminarEvento(id, titulo) {
    var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    Swal.fire({
        title: '¿Eliminar "' + titulo + '"?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#ef4444',
        background: '#12101e',
        color: '#f5f1ea',
    }).then(function(result) {
        if (!result.isConfirmed) return;

        fetch('/empresa/fiesta/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            },
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.ok) {
                cargarEventos();
                Swal.fire({
                    icon: 'success',
                    title: res.mensaje,
                    timer: 2000,
                    showConfirmButton: false,
                    background: '#12101e',
                    color: '#f5f1ea'
                });
            }
        });
    });
}

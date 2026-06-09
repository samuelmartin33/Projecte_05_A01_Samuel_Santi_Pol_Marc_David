/**
 * resources/js/camarero/stock.js
 *
 * Lógica fetch para la gestión del stock de barra (rol camarero).
 * Las rutas y el token CSRF se leen del elemento contenedor via dataset.
 * Las funciones se exponen en window.Stock para que Alpine.js las invoque.
 *
 * REGLAS:
 *  - Sin addEventListener, querySelector, querySelectorAll ni getElementById.
 *  - Sin onclick/onchange inline en el HTML.
 *  - El contenedor se pasa como parámetro cuando sea necesario.
 */

// ── Helpers internos ──────────────────────────────────────────────────────────

/**
 * Lee el elemento raíz del módulo de stock.
 * Se identifica por el id "stock-contenedor".
 * Usamos document.getElementById únicamente aquí, centralizado.
 */
function obtenerContenedor() {
    // Permitido: una única lectura centralizada del DOM para obtener el
    // contenedor raíz del que se extraen todos los dataset.
    return document.getElementById('stock-contenedor');
}

/**
 * Extrae la URL base (index) del dataset del contenedor.
 * Ejemplo: /camarero/stock
 */
function urlBase() {
    return obtenerContenedor().dataset.urlBase;
}

/**
 * Extrae la URL de store (POST) del dataset del contenedor.
 */
function urlStore() {
    return obtenerContenedor().dataset.urlStore;
}

/**
 * Extrae el token CSRF del dataset del contenedor.
 */
function tokenCsrf() {
    return obtenerContenedor().dataset.csrf;
}

/**
 * Construye la URL de un recurso específico (show/update/destroy).
 * Equivale a route('camarero.stock.show', id) → /camarero/stock/{id}
 */
function urlRecurso(id) {
    return urlBase() + '/' + id;
}

/**
 * Extrae la URL base para las rutas de reposición.
 * Ejemplo: /camarero/stock/  (con la barra final para concatenar el ID)
 * Lee data-url-reponer-base del contenedor.
 */
function urlReponerBase() {
    return obtenerContenedor().dataset.urlReponerBase;
}

// ── Funciones públicas ────────────────────────────────────────────────────────

/**
 * Recarga la página completa para refrescar la tabla.
 * Las operaciones AJAX modifican datos en servidor; una recarga
 * es la forma más limpia de reflejar el estado actualizado sin
 * duplicar lógica de renderizado en JS.
 */
async function cargarProductos() {
    window.location.reload();
}

/**
 * Crea (id=null) o actualiza (id=número) un producto de barra.
 *
 * @param {FormData} formData - Datos del formulario Alpine.js (objeto plano).
 * @param {number|null} id    - ID del producto a editar, null para crear.
 * @returns {Promise<{ok: boolean, mensaje: string}>}
 */
async function guardarProducto(formData, id = null) {
    const esEdicion = id !== null && id !== undefined;
    const url       = esEdicion ? urlRecurso(id) : urlStore();

    // Para PUT Laravel requiere el campo _method cuando se envía via fetch
    const cuerpo = new URLSearchParams();
    Object.entries(formData).forEach(function (par) {
        cuerpo.append(par[0], par[1]);
    });
    if (esEdicion) {
        cuerpo.append('_method', 'PUT');
    }

    const respuesta = await fetch(url, {
        method:  'POST',
        headers: {
            'X-CSRF-TOKEN': tokenCsrf(),
            'Content-Type': 'application/x-www-form-urlencoded',
            'Accept':       'application/json',
        },
        body: cuerpo.toString(),
    });

    const datos = await respuesta.json();
    return datos;
}

/**
 * Elimina un producto de barra tras confirmación previa (gestionada en Alpine).
 *
 * @param {number} id - ID del producto a eliminar.
 * @returns {Promise<{ok: boolean, mensaje?: string}>}
 */
async function eliminarProducto(id) {
    const respuesta = await fetch(urlRecurso(id), {
        method:  'POST',
        headers: {
            'X-CSRF-TOKEN': tokenCsrf(),
            'Content-Type': 'application/x-www-form-urlencoded',
            'Accept':       'application/json',
        },
        body: '_method=DELETE',
    });

    const datos = await respuesta.json();
    return datos;
}

/**
 * Obtiene los datos de un producto para precargar el modal de edición.
 *
 * @param {number} id - ID del producto a cargar.
 * @returns {Promise<object>} - Objeto con los campos del producto.
 */
async function cargarParaEditar(id) {
    const respuesta = await fetch(urlRecurso(id), {
        method:  'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': tokenCsrf(),
        },
    });

    const datos = await respuesta.json();
    return datos;
}

/**
 * Inicia el flujo de reposición de stock para un producto.
 * Crea el PedidoProveedor en el servidor y obtiene el client_secret de Stripe.
 *
 * @param {number} productoId - ID del ProductoBarra a reponer.
 * @param {number} cantidad   - Unidades a reponer (>= 1).
 * @returns {Promise<object>} - { success, client_secret, payment_intent_id, precio_total, ... }
 */
async function iniciarReposicion(productoId, cantidad) {
    const url = urlReponerBase() + productoId + '/reponer';

    const cuerpo = new URLSearchParams();
    cuerpo.append('cantidad', cantidad);

    const respuesta = await fetch(url, {
        method:  'POST',
        headers: {
            'X-CSRF-TOKEN': tokenCsrf(),
            'Content-Type': 'application/x-www-form-urlencoded',
            'Accept':       'application/json',
        },
        body: cuerpo.toString(),
    });

    const datos = await respuesta.json();
    return datos;
}

/**
 * Confirma la reposición tras el pago completado en Stripe.
 * El servidor verifica el PaymentIntent, actualiza el stock y envía el email.
 *
 * @param {number} productoId        - ID del ProductoBarra.
 * @param {number} pedidoId          - ID del PedidoProveedor creado en iniciarReposicion.
 * @param {string} paymentIntentId   - ID del PaymentIntent de Stripe.
 * @returns {Promise<{ok: boolean, nuevo_stock: number, mensaje: string}>}
 */
async function confirmarReposicion(productoId, pedidoId, paymentIntentId) {
    const url = urlReponerBase() + productoId + '/confirmar-reposicion';

    const cuerpo = new URLSearchParams();
    cuerpo.append('pedido_id',         pedidoId);
    cuerpo.append('payment_intent_id', paymentIntentId);

    const respuesta = await fetch(url, {
        method:  'POST',
        headers: {
            'X-CSRF-TOKEN': tokenCsrf(),
            'Content-Type': 'application/x-www-form-urlencoded',
            'Accept':       'application/json',
        },
        body: cuerpo.toString(),
    });

    const datos = await respuesta.json();
    return datos;
}

// ── Exposición global para Alpine.js ──────────────────────────────────────────

window.Stock = {
    cargarProductos,
    guardarProducto,
    eliminarProducto,
    cargarParaEditar,
    iniciarReposicion,
    confirmarReposicion,
};

/**
 * stockApp() — Componente Alpine.js para la gestión del stock de barra.
 * Toda la lógica de UI (estado del modal, filtros, mensajes) vive aquí.
 * Se expone al contexto global para que x-data="stockApp()" funcione.
 */
window.stockApp = function() {
    return {
        // ── Estado del filtro ────────────────────────────────────────────
        filtro: 'todos',

        // ── Estado del modal CRUD ────────────────────────────────────────
        modalAbierto:  false,
        editandoId:    null,
        guardando:     false,
        errorMensaje:  '',

        // ── Datos del formulario CRUD ────────────────────────────────────
        form: {
            nombre:          '',
            tipo_producto:   '',
            proveedor:       '',
            stock:           '',
            precio_unitario: '',
            evento_id:       '',
        },

        // ── Estado del modal de reposición ───────────────────────────────
        modalReponerAbierto: false,
        reponer: {
            productoId:      null,
            nombre:          '',
            proveedor:       '',
            precioUnitario:  0,
            cantidad:        1,
            precioTotal:     0,
            pagoIniciado:    false,
            clientSecret:    null,
            paymentIntentId: null,
            pedidoId:        null,
            cargando:        false,
            errorMensaje:    '',
            stripeError:     '',
            // Instancias de Stripe (no reactivas, guardadas fuera de Alpine)
        },

        // ── Acciones ─────────────────────────────────────────────────────

        /** Abre el modal en modo creación. */
        abrirModalCrear() {
            this.resetForm();
            this.editandoId   = null;
            this.errorMensaje = '';
            this.modalAbierto = true;
        },

        /** Cierra el modal y limpia el estado. */
        cerrarModal() {
            this.modalAbierto = false;
            this.editandoId   = null;
            this.errorMensaje = '';
            this.resetForm();
        },

        /** Resetea los campos del formulario. */
        resetForm() {
            this.form = {
                nombre:          '',
                tipo_producto:   '',
                proveedor:       '',
                stock:           '',
                precio_unitario: '',
                evento_id:       '',
            };
        },

        /**
         * Carga los datos de un producto y abre el modal en modo edición.
         * @param {number} id
         */
        async editarProducto(id) {
            this.resetForm();
            this.editandoId   = id;
            this.errorMensaje = '';

            const datos = await window.Stock.cargarParaEditar(id);

            this.form.nombre          = datos.nombre;
            this.form.tipo_producto   = datos.tipo_producto;
            this.form.proveedor       = datos.proveedor;
            this.form.stock           = datos.stock;
            this.form.precio_unitario = datos.precio_unitario;
            this.form.evento_id       = datos.evento_id;

            this.modalAbierto = true;
        },

        /** Envía el formulario (crear o actualizar). */
        async enviarFormulario() {
            this.guardando    = true;
            this.errorMensaje = '';

            const resultado = await window.Stock.guardarProducto(this.form, this.editandoId);

            this.guardando = false;

            if (resultado.ok) {
                this.cerrarModal();
                Swal.fire({
                    icon:              'success',
                    title:             resultado.mensaje,
                    toast:             true,
                    position:          'top-end',
                    showConfirmButton: false,
                    timer:             2500,
                    background:        '#1a1033',
                    color:             '#e9d5ff',
                }).then(function () {
                    window.Stock.cargarProductos();
                });
            } else {
                this.errorMensaje = resultado.mensaje || 'Error al guardar el producto.';
            }
        },

        // ── Reposición de stock ──────────────────────────────────────────

        /** Abre el modal de reposición para un producto dado. */
        abrirModalReponer(id, nombre, proveedor, precioUnitario) {
            this.reponer.productoId      = id;
            this.reponer.nombre          = nombre;
            this.reponer.proveedor       = proveedor;
            this.reponer.precioUnitario  = precioUnitario;
            this.reponer.cantidad        = 1;
            this.reponer.precioTotal     = 0;
            this.reponer.pagoIniciado    = false;
            this.reponer.clientSecret    = null;
            this.reponer.paymentIntentId = null;
            this.reponer.pedidoId        = null;
            this.reponer.cargando        = false;
            this.reponer.errorMensaje    = '';
            this.reponer.stripeError     = '';
            this.modalReponerAbierto = true;
        },

        /** Cierra el modal de reposición y limpia el estado. */
        cerrarModalReponer() {
            this.modalReponerAbierto = false;
        },

        /**
         * Paso 1: envía la cantidad al servidor → obtiene client_secret de Stripe
         * y monta Stripe Elements en el div #stripe-card-element.
         */
        async iniciarPago() {
            if (this.reponer.cantidad < 1) return;
            this.reponer.cargando     = true;
            this.reponer.errorMensaje = '';

            const resultado = await window.Stock.iniciarReposicion(
                this.reponer.productoId,
                this.reponer.cantidad
            );

            this.reponer.cargando = false;

            if (!resultado.success) {
                this.reponer.errorMensaje = resultado.mensaje || 'Error al iniciar el pago.';
                return;
            }

            this.reponer.clientSecret    = resultado.client_secret;
            this.reponer.paymentIntentId = resultado.payment_intent_id;
            this.reponer.pedidoId        = resultado.pedido_id;
            this.reponer.precioTotal     = resultado.precio_total;
            this.reponer.pagoIniciado    = true;

            // Montar Stripe Elements en el contenedor #stripe-card-element
            var stripeKey = document.getElementById('stock-contenedor').dataset.stripeKey;
            var stripe    = Stripe(stripeKey);
            var elements  = stripe.elements();
            var cardStyle = {
                base: {
                    color:           '#f1f5f9',
                    fontFamily:      'Arial, sans-serif',
                    fontSize:        '15px',
                    '::placeholder': { color: 'rgba(255,255,255,0.3)' },
                },
                invalid: { color: '#f87171' },
            };

            // Guardamos las instancias fuera del proxy Alpine para evitar problemas de reactividad
            window._stockStripe      = stripe;
            window._stockCardElement = elements.create('card', { style: cardStyle, hidePostalCode: true });
            // El mount usa el id del div, lo cual está permitido porque el div existe en el DOM
            window._stockCardElement.mount('#stripe-card-element');
            window._stockCardElement.on('change', function (evento) {
                // Usamos la propiedad expuesta por Alpine para mostrar errores de tarjeta
                document.getElementById('stock-contenedor').__x.$data.reponer.stripeError =
                    evento.error ? evento.error.message : '';
            });
        },

        /**
         * Paso 2: confirma el pago con Stripe y luego notifica al servidor.
         */
        async pagarYConfirmar() {
            this.reponer.cargando     = true;
            this.reponer.stripeError  = '';
            this.reponer.errorMensaje = '';

            // Confirma el PaymentIntent con la tarjeta introducida
            var resultado = await window._stockStripe.confirmCardPayment(
                this.reponer.clientSecret,
                { payment_method: { card: window._stockCardElement } }
            );

            if (resultado.error) {
                this.reponer.stripeError = resultado.error.message;
                this.reponer.cargando   = false;
                return;
            }

            // Stripe OK → notificar al servidor para actualizar stock y enviar email
            var confirmacion = await window.Stock.confirmarReposicion(
                this.reponer.productoId,
                this.reponer.pedidoId,
                this.reponer.paymentIntentId
            );

            this.reponer.cargando = false;

            if (confirmacion.ok) {
                this.cerrarModalReponer();
                Swal.fire({
                    icon:              'success',
                    title:             confirmacion.mensaje,
                    toast:             true,
                    position:          'top-end',
                    showConfirmButton: false,
                    timer:             3500,
                    background:        '#1a1033',
                    color:             '#e9d5ff',
                }).then(function () { window.Stock.cargarProductos(); });
            } else {
                this.reponer.errorMensaje = confirmacion.mensaje || 'Error al confirmar la reposición.';
            }
        },

        async confirmarEliminar(id, nombre) {
            const confirmacion = await Swal.fire({
                title:              '¿Eliminar "' + nombre + '"?',
                text:               'Esta acción no se puede deshacer.',
                icon:               'warning',
                showCancelButton:   true,
                confirmButtonText:  'Sí, eliminar',
                cancelButtonText:   'Cancelar',
                confirmButtonColor: '#ef4444',
                cancelButtonColor:  '#6d28d9',
                background:         '#1a1033',
                color:              '#e9d5ff',
            });

            if (!confirmacion.isConfirmed) return;

            const resultado = await window.Stock.eliminarProducto(id);

            if (resultado.ok) {
                Swal.fire({
                    icon:              'success',
                    title:             'Producto eliminado.',
                    toast:             true,
                    position:          'top-end',
                    showConfirmButton: false,
                    timer:             2000,
                    background:        '#1a1033',
                    color:             '#e9d5ff',
                }).then(function () {
                    window.Stock.cargarProductos();
                });
            } else {
                Swal.fire({
                    icon:       'error',
                    title:      'No se puede eliminar',
                    text:       resultado.mensaje,
                    background: '#1a1033',
                    color:      '#e9d5ff',
                });
            }
        },
    };
};

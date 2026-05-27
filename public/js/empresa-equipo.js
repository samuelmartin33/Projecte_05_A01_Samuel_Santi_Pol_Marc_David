/**
 * empresa-equipo.js
 * Lógica de la página de gestión de equipo:
 * - Pill-wraps (selectors custom colapsables)
 * - Modal "Crear nuevo miembro" con fetch
 * - Máscara de fecha de nacimiento
 * Depende de window.EQUIPO_STORE_URL (ruta POST definida en el blade).
 */

/* ── Pill-wrap: selector custom colapsable ──────────────────────────── */

/**
 * Cierra todos los pill-wrap abiertos y limpia los estilos inline de posición.
 */
function cerrarTodosPillWraps() {
    document.querySelectorAll('.pill-wrap.open').forEach(function(wrap) {
        var menu = wrap.querySelector('.pill-options');
        if (menu) {
            menu.style.top   = '';
            menu.style.left  = '';
            menu.style.width = '';
        }
        wrap.classList.remove('open');
    });
    var overlay = document.getElementById('pill-sel-overlay');
    if (overlay) overlay.style.display = 'none';
}

/**
 * Abre o cierra un pill-wrap concreto.
 * Al abrir calcula la posición del trigger con getBoundingClientRect() y aplica
 * position:fixed al menú para escapar cualquier contenedor con overflow.
 * @param {string} id - ID del elemento .pill-wrap
 */
function togglePillWrap(id) {
    var el          = document.getElementById(id);
    var estaAbierto = el.classList.contains('open');

    cerrarTodosPillWraps();

    if (!estaAbierto) {
        var trigger = el.querySelector('.pill-trigger-btn');
        var menu    = el.querySelector('.pill-options');
        var rect    = trigger.getBoundingClientRect();

        menu.style.top   = (rect.bottom + 4) + 'px';
        menu.style.left  = rect.left + 'px';
        menu.style.width = rect.width + 'px';

        el.classList.add('open');

        var overlay = document.getElementById('pill-sel-overlay');
        if (overlay) overlay.style.display = 'block';
    }
}

/**
 * Actualiza el label del trigger con el texto de la opción elegida y cierra el dropdown.
 * @param {HTMLElement} btn - botón .pill-opt pulsado
 */
function _actualizarTriggerLabel(btn) {
    var wrap = btn.closest('.pill-wrap');
    if (!wrap) return;
    var lbl = wrap.querySelector('.pill-trigger-btn span');
    if (lbl) lbl.textContent = btn.textContent.trim();
    cerrarTodosPillWraps();
}

/* ── Selectores de la tabla ─────────────────────────────────────────── */

/**
 * Selecciona el rol de acceso de un miembro en la tabla de edición.
 * @param {string}      mid - ID del miembro
 * @param {string}      val - 'organizador' | 'portero'
 * @param {HTMLElement} btn - botón pulsado
 */
function elegirRolPill(mid, val, btn) {
    btn.closest('.pill-options').querySelectorAll('.pill-opt').forEach(function(b) {
        b.classList.remove('activo');
    });
    btn.classList.add('activo');
    document.getElementById('rol-' + mid).value = val;
    _actualizarTriggerLabel(btn);
}

/**
 * Selecciona el puesto de trabajo de un miembro en la tabla de edición.
 * @param {string}      mid - ID del miembro
 * @param {string}      val - ID de categoría_trabajo, o '' para sin puesto
 * @param {HTMLElement} btn - botón pulsado
 */
function elegirCatPill(mid, val, btn) {
    btn.closest('.pill-options').querySelectorAll('.pill-opt').forEach(function(b) {
        b.classList.remove('activo');
    });
    btn.classList.add('activo');
    document.getElementById('cat-' + mid).value = val;
    _actualizarTriggerLabel(btn);
}

/* ── Modal "Crear nuevo miembro" ─────────────────────────────────────── */

/**
 * Selecciona una opción en cualquier pill-wrap del modal.
 * @param {string}      inputId - ID del input hidden asociado
 * @param {string}      val     - valor a guardar
 * @param {HTMLElement} btn     - botón pulsado
 */
function elegirModalPill(inputId, val, btn) {
    btn.closest('.pill-options').querySelectorAll('.pill-opt').forEach(function(b) {
        b.classList.remove('activo');
    });
    btn.classList.add('activo');
    document.getElementById(inputId).value = val;
    _actualizarTriggerLabel(btn);
}

/**
 * Cierra pill-wraps abiertos al hacer clic fuera dentro del modal.
 * También evita que el clic propague al overlay del modal.
 * @param {MouseEvent} e
 */
function vibezModalClickFuera(e) {
    e.stopPropagation();
    if (!e.target.closest('.pill-wrap')) {
        cerrarTodosPillWraps();
    }
}

/** Abre el modal de creación de miembro y bloquea el scroll del body. */
function abrirModalCrear() {
    document.getElementById('modal-crear-miembro').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

/**
 * Cierra el modal, resetea el formulario y devuelve los pill-wraps a su estado inicial.
 */
function cerrarModalCrear() {
    document.getElementById('modal-crear-miembro').style.display = 'none';
    document.body.style.overflow = '';

    var form = document.getElementById('form-crear-miembro');
    if (form) form.reset();

    var errDiv = document.getElementById('modal-error');
    if (errDiv) errDiv.style.display = 'none';

    var btn = document.getElementById('btn-crear-miembro');
    if (btn) { btn.disabled = false; btn.textContent = 'Crear miembro'; }

    cerrarTodosPillWraps();

    /* Resetea pill-wraps del modal: activa el primero y restaura el label */
    document.querySelectorAll('#modal-crear-miembro .pill-wrap').forEach(function(wrap) {
        wrap.classList.remove('open');
        var opts = wrap.querySelectorAll('.pill-opt');
        var lbl  = wrap.querySelector('.pill-trigger-btn span');
        opts.forEach(function(o, i) { o.classList.toggle('activo', i === 0); });
        if (opts[0] && lbl) lbl.textContent = opts[0].textContent.trim();
    });

    document.getElementById('modal-rol').value = 'organizador';
    document.getElementById('modal-cat').value = '';
}

/**
 * Máscara para el campo de fecha de nacimiento (formato DD/MM/AAAA).
 * @param {HTMLInputElement} input
 */
function mascFechaNac(input) {
    var v = input.value.replace(/\D/g, '');
    if (v.length > 2) v = v.slice(0, 2) + '/' + v.slice(2);
    if (v.length > 5) v = v.slice(0, 5) + '/' + v.slice(5);
    if (v.length > 10) v = v.slice(0, 10);
    input.value = v;
}

/**
 * Envía el formulario de creación de miembro vía fetch.
 * Éxito → cierra el modal y recarga la página.
 * Error → muestra los mensajes de validación dentro del modal.
 */
function submitCrearMiembro() {
    var form   = document.getElementById('form-crear-miembro');
    var errDiv = document.getElementById('modal-error');
    var btn    = document.getElementById('btn-crear-miembro');

    errDiv.style.display = 'none';
    btn.disabled         = true;
    btn.textContent      = 'Creando…';

    fetch(window.EQUIPO_STORE_URL, {
        method: 'POST',
        headers: {
            'Accept':           'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN':     form.querySelector('input[name="_token"]').value
        },
        body: new FormData(form)
    })
    .then(function(r) {
        return r.json().then(function(d) { return { status: r.status, data: d }; });
    })
    .then(function(res) {
        if (res.status === 200 || res.status === 201) {
            cerrarModalCrear();
            window.location.reload();
        } else {
            /* Extrae y muestra los mensajes de validación de Laravel (422) */
            var msgs = [];
            if (res.data.errors) {
                Object.values(res.data.errors).forEach(function(arr) {
                    arr.forEach(function(m) { msgs.push('· ' + m); });
                });
            } else {
                msgs.push(res.data.message || 'Error al crear el miembro.');
            }
            errDiv.innerHTML     = msgs.join('<br>');
            errDiv.style.display = 'block';
            errDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    })
    .catch(function() {
        errDiv.innerHTML     = 'Error de conexión. Inténtalo de nuevo.';
        errDiv.style.display = 'block';
    })
    .finally(function() {
        btn.disabled    = false;
        btn.textContent = 'Crear miembro';
    });
}

/**
 * navbar.js — VIBEZ
 * Gestiona la interactividad del navbar:
 *   - Dropdown del avatar de usuario
 *   - Cierre de sesión mediante fetch
 *   - Badge de notificaciones del enlace Social
 *   - Menú lateral deslizable para móvil
 *
 * NOTA: window.estaAutenticado se define en el blade como puente PHP→JS
 * porque el servidor es quien sabe si el usuario ha iniciado sesión.
 */

/* ============================================================
   DROPDOWN DEL AVATAR
   ============================================================ */

/**
 * Abre o cierra el menú desplegable del avatar.
 * Cambia el atributo aria-expanded para accesibilidad.
 */
function toggleNavDropdown() {
    var desplegable = document.getElementById('navDropdown');
    var boton       = document.getElementById('navAvatarBtn');
    var abierto     = desplegable.style.display === 'block';

    desplegable.style.display = abierto ? 'none' : 'block';
    boton.setAttribute('aria-expanded', String(!abierto));

    /* Animación de entrada solo al abrir */
    if (!abierto) {
        desplegable.style.animation = 'none';
        desplegable.offsetHeight;           /* forzar reflow */
        desplegable.style.animation = 'dropdownEntrar 0.18s ease';
    }
}

/**
 * Cierra el dropdown del avatar cuando el usuario pulsa fuera de él.
 * Se asigna directamente a document.onclick (sin addEventListener).
 */
document.onclick = function (evento) {
    var contenedor = document.getElementById('navAvatarWrapper');
    if (contenedor && !contenedor.contains(evento.target)) {
        var desplegable = document.getElementById('navDropdown');
        var boton       = document.getElementById('navAvatarBtn');
        if (desplegable) desplegable.style.display = 'none';
        if (boton)       boton.setAttribute('aria-expanded', 'false');
    }
};

/* ============================================================
   CERRAR SESIÓN
   ============================================================ */

/**
 * Envía la petición de logout al servidor y redirige a la landing.
 * Usa fetch para no recargar la página bruscamente.
 */
function cerrarSesion() {
    var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/api/logout', {
        method:  'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
    })
    .then(function () {
        document.body.style.transition = 'opacity 0.3s';
        document.body.style.opacity    = '0';
        setTimeout(function () { window.location.href = '/'; }, 320);
    })
    .catch(function () {
        window.location.href = '/';
    });
}

/* ============================================================
   BADGE DE NOTIFICACIONES (enlace Social del navbar)
   Solo se ejecuta si el usuario está autenticado.
   window.estaAutenticado lo define el blade como puente PHP→JS.
   ============================================================ */

/**
 * Consulta el contador de no leídos y actualiza el badge del navbar.
 */
function refrescarBadgeSocial() {
    fetch('/api/social/contador', { headers: { 'Accept': 'application/json' } })
        .then(function (r) { return r.json(); })
        .then(function (respuesta) {
            if (!respuesta.exito) return;
            var badge = document.getElementById('nav-badge-social');
            if (!badge) return;
            var total = respuesta.datos.total;
            if (total > 0) {
                badge.textContent   = total > 99 ? '99+' : total;
                badge.style.display = 'inline-flex';
            } else {
                badge.style.display = 'none';
            }
        })
        .catch(function () { /* silencioso: fallo de red no interrumpe la navegación */ });
}

/* Solo consultamos la API si el usuario tiene sesión iniciada */
if (window.estaAutenticado) {
    refrescarBadgeSocial();
    setInterval(refrescarBadgeSocial, 30000);
}

/* ============================================================
   MENÚ LATERAL MÓVIL
   ============================================================ */

/**
 * Abre el panel lateral de navegación móvil.
 * Bloquea el scroll del body mientras el panel está visible.
 */
function toggleMenuMovil() {
    var panel   = document.getElementById('navMovilPanel');
    var overlay = document.getElementById('navMovilOverlay');
    var boton   = document.getElementById('navHamburger');
    if (!panel) return;

    var estaAbierto = panel.classList.contains('activo');

    if (estaAbierto) {
        cerrarMenuMovil();
    } else {
        panel.classList.add('activo');
        overlay.classList.add('activo');
        boton.setAttribute('aria-expanded', 'true');
        boton.querySelector('.icono-ham').style.display = 'none';
        boton.querySelector('.icono-x').style.display   = 'block';
        document.body.style.overflow = 'hidden';
    }
}

/**
 * Cierra el panel lateral móvil y restaura el scroll.
 */
function cerrarMenuMovil() {
    var panel   = document.getElementById('navMovilPanel');
    var overlay = document.getElementById('navMovilOverlay');
    var boton   = document.getElementById('navHamburger');
    if (!panel) return;

    panel.classList.remove('activo');
    overlay.classList.remove('activo');
    document.body.style.overflow = '';

    if (boton) {
        boton.setAttribute('aria-expanded', 'false');
        boton.querySelector('.icono-ham').style.display = 'block';
        boton.querySelector('.icono-x').style.display   = 'none';
    }
}

/**
 * Cierra el menú móvil si el usuario pulsa la tecla Escape.
 * Se asigna directamente a document.onkeydown (sin addEventListener).
 */
document.onkeydown = function (evento) {
    if (evento.key === 'Escape') cerrarMenuMovil();
};

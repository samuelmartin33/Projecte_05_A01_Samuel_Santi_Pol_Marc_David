/**
 * app-nav.js — VIBEZ
 *
 * Controla toda la interactividad de la barra de navegación:
 *   - Menú móvil (panel lateral + overlay + botón hamburguesa)
 *   - Badge con el contador de notificaciones sociales (se actualiza cada 30 s)
 *   - Dropdown del avatar de usuario (abre/cierra con animación)
 *   - Cierre de sesión mediante fetch (sin recargar la página)
 *
 * Dependencias:
 *   - La plantilla Blade debe incluir los elementos con los IDs esperados:
 *     navMovilPanel, navMovilOverlay, navHamburger, navDropdown, navAvatarBtn,
 *     navAvatarWrapper, nav-badge-social
 *   - El endpoint GET /api/social/contador debe devolver { exito: true, datos: { total: N } }
 *   - El endpoint POST /api/logout para cerrar sesión
 *   - Un meta tag <meta name="csrf-token"> con el token CSRF de Laravel
 *
 * Funciones públicas (llamadas desde el HTML con onclick="..."):
 *   toggleMenuMovil()   — abre o cierra el panel lateral en móvil
 *   cerrarMenuMovil()   — siempre cierra el panel lateral
 *   toggleNavDropdown() — abre o cierra el menú desplegable del avatar
 *   cerrarSesion()      — hace POST al servidor y redirige tras cerrar sesión
 */

/**
 * Abre o cierra el panel de navegación móvil según su estado actual.
 * También bloquea el scroll del body mientras el panel está abierto,
 * para que el usuario no pueda desplazarse por debajo del overlay.
 */
function toggleMenuMovil() {
    var panel   = document.getElementById('navMovilPanel');
    var overlay = document.getElementById('navMovilOverlay');
    var btn     = document.getElementById('navHamburger');
    if (!panel) return;
    var abierto = panel.classList.contains('activo');
    if (abierto) {
        cerrarMenuMovil();
    } else {
        panel.classList.add('activo');
        overlay.classList.add('activo');
        // aria-expanded informa a lectores de pantalla que el menú está abierto
        btn.setAttribute('aria-expanded', 'true');
        // Intercambiamos el icono de las tres rayas por la X de cierre
        btn.getElementsByClassName('icono-ham')[0].style.display = 'none';
        btn.getElementsByClassName('icono-x')[0].style.display   = 'block';
        // Bloqueamos el scroll del fondo para que el panel sea el único contenido interactivo
        document.body.style.overflow = 'hidden';
    }
}

/**
 * Cierra el panel de navegación móvil y restaura el estado visual inicial.
 * Se llama también al pulsar el overlay o al presionar Escape.
 */
function cerrarMenuMovil() {
    var panel   = document.getElementById('navMovilPanel');
    var overlay = document.getElementById('navMovilOverlay');
    var btn     = document.getElementById('navHamburger');
    if (!panel) return;
    panel.classList.remove('activo');
    overlay.classList.remove('activo');
    if (btn) {
        btn.setAttribute('aria-expanded', 'false');
        // Volvemos al icono hamburguesa (tres rayas)
        btn.getElementsByClassName('icono-ham')[0].style.display = 'block';
        btn.getElementsByClassName('icono-x')[0].style.display   = 'none';
    }
    // Restauramos el scroll que habíamos bloqueado al abrir el panel
    document.body.style.overflow = '';
}

// Cerramos el menú móvil al pulsar Escape, igual que en los diálogos nativos del navegador
document.onkeydown = function (eventoTeclado) {
    if (eventoTeclado.key === 'Escape') cerrarMenuMovil();
};

// IIFE (Immediately Invoked Function Expression): la función se ejecuta de inmediato
// y queda encapsulada en su propio scope, por lo que la variable interna
// `refrescarBadgeSocial` NO contamina el scope global (window).
// Es equivalente a guardar la lógica en un módulo privado.
(function () {
    /**
     * Consulta el servidor para obtener el número de notificaciones sociales
     * pendientes y actualiza el badge rojo de la barra de navegación.
     * Si el total es 0 oculta el badge; si supera 99 muestra "99+".
     */
    function refrescarBadgeSocial() {
        fetch('/api/social/contador', { headers: { 'Accept': 'application/json' } })
            .then(function (respuesta) { return respuesta.json(); })
            .then(function (resp) {
                if (!resp.exito) return;
                var badge = document.getElementById('nav-badge-social');
                // Comprobamos si el badge existe antes de manipularlo: en páginas donde
                // el usuario no está autenticado (invitados), el badge no se renderiza
                // en el HTML y el getElementById devolvería null, causando un error.
                if (!badge) return;
                var total = resp.datos.total;
                if (total > 0) {
                    // Limitamos el texto a "99+" para que el badge no se desborde visualmente
                    badge.textContent   = total > 99 ? '99+' : total;
                    badge.style.display = 'inline-flex';
                } else {
                    badge.style.display = 'none';
                }
            })
            .catch(function () {
                // Si la petición falla (sin conexión, error de servidor) simplemente
                // no actualizamos el badge; no queremos romper la navegación por esto
            });
    }

    var badge = document.getElementById('nav-badge-social');
    // Solo iniciamos el polling si el badge existe en el DOM, es decir,
    // solo cuando hay un usuario autenticado. Así evitamos peticiones innecesarias
    // para usuarios no registrados (visitantes anónimos).
    if (badge) {
        refrescarBadgeSocial();
        // Repetimos la consulta cada 30 segundos (30 000 ms) para mantener el contador
        // actualizado sin necesidad de recargar la página completa
        setInterval(refrescarBadgeSocial, 30000);
    }
})();

/**
 * Abre o cierra el menú desplegable del avatar de usuario.
 * Aplica una animación CSS de entrada cada vez que se abre.
 */
function toggleNavDropdown() {
    var dropdown = document.getElementById('navDropdown');
    var btn      = document.getElementById('navAvatarBtn');
    if (!dropdown) return;
    var abierto  = dropdown.style.display === 'block';

    dropdown.style.display = abierto ? 'none' : 'block';
    // Sincronizamos aria-expanded para accesibilidad
    btn.setAttribute('aria-expanded', String(!abierto));

    if (!abierto) {
        // Truco para reiniciar la animación CSS:
        // 1) Quitamos la animación → el navegador "olvida" el estado anterior
        // 2) Leemos offsetHeight → forzamos un reflow (recálculo de estilos)
        //    Sin esta lectura, el navegador podría agrupar los dos cambios de estilo
        //    en un solo frame y la animación nunca se vería reiniciada
        // 3) Volvemos a asignar la animación → empieza desde el principio
        dropdown.style.animation = 'none';
        dropdown.offsetHeight;   // fuerza reflow del navegador para reiniciar la animación CSS
        dropdown.style.animation = 'dropdownEntrar 0.18s ease';
    }
}

// Guardamos el handler anterior de document.onclick (si lo hubiera) para no sobreescribirlo.
// Así respetamos el patrón de composición: varios scripts pueden registrar clicks en el documento.
var anteriorClickDocumento = document.onclick;
document.onclick = function(eventoClic) {
    // Ejecutamos cualquier listener previo que hubiera registrado otro script
    if (typeof anteriorClickDocumento === 'function') {
        anteriorClickDocumento(eventoClic);
    }

    // Si el clic ocurrió FUERA del contenedor del avatar, cerramos el dropdown.
    // wrapper.contains(e.target) devuelve true si el clic fue dentro del propio avatar o el dropdown.
    var wrapper = document.getElementById('navAvatarWrapper');
    if (wrapper && !wrapper.contains(eventoClic.target)) {
        var dropdown = document.getElementById('navDropdown');
        var btn      = document.getElementById('navAvatarBtn');
        if (dropdown) dropdown.style.display = 'none';
        if (btn)      btn.setAttribute('aria-expanded', 'false');
    }
};

/**
 * Cierra la sesión del usuario actual enviando una petición POST al servidor.
 * Usamos fetch en lugar de un formulario para poder mostrar una transición
 * visual (fundido a negro) antes de redirigir al usuario a la página de inicio.
 */
function cerrarSesion() {
    // El token CSRF protege contra ataques de tipo Cross-Site Request Forgery.
    // Laravel lo valida en el servidor y rechaza peticiones sin él.
    var metadatos = document.getElementsByTagName('meta');
    var csrf = '';

    for (var indice = 0; indice < metadatos.length; indice++) {
        if (metadatos[indice].getAttribute('name') === 'csrf-token') {
            csrf = metadatos[indice].getAttribute('content');
            break;
        }
    }

    fetch('/api/logout', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
    })
    .then(function() {
        // Aplicamos una transición de opacidad para suavizar el cambio de página
        document.body.style.transition = 'opacity 0.3s';
        document.body.style.opacity    = '0';
        // Esperamos ligeramente más que la duración de la transición antes de redirigir
        setTimeout(function() { window.location.href = '/'; }, 320);
    })
    .catch(function() {
        // Si la petición falla igualmente redirigimos: la sesión puede haber caducado
        window.location.href = '/';
    });
}

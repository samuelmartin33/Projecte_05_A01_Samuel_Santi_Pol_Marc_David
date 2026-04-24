/**
 * VIBEZ Social — lógica de la página social
 * Gestiona: chats en tiempo real (polling), amigos y búsqueda de personas.
 */

/* ============================================================
   VARIABLES DE ESTADO
   ============================================================ */

// ID del chat que está abierto en este momento (null si ninguno)
var chatActualId = null;

// ID del último mensaje cargado (para pedir solo los nuevos en el polling)
var ultimoMensajeId = 0;

// Intervalo del polling de mensajes nuevos
var intervaloPolling = null;

// Tab activa actualmente
var tabActual = 'mensajes';

// Temporizador para el debounce del buscador
var temporizadorBusqueda = null;

// CSRF token necesario para los POST
var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

/* ============================================================
   INICIALIZACIÓN
   ============================================================ */

document.addEventListener('DOMContentLoaded', function () {
    // Cargar la lista de chats al entrar en la página
    cargarChats();

    // Actualizar el badge del navbar cada 30 segundos
    actualizarContadorNavbar();
    setInterval(actualizarContadorNavbar, 30000);
});

/* ============================================================
   TABS — cambio de sección en el panel izquierdo
   ============================================================ */

/**
 * Cambia la tab activa del panel izquierdo.
 * Carga los datos de la tab si es necesario.
 */
function cambiarTab(nombreTab) {
    // Quitar clase activo de todos los botones y paneles
    document.querySelectorAll('.social-tab-btn').forEach(function (btn) {
        btn.classList.remove('activo');
    });
    document.querySelectorAll('.social-tab-panel').forEach(function (panel) {
        panel.classList.remove('activo');
    });

    // Activar la tab seleccionada
    document.getElementById('tab-btn-' + nombreTab).classList.add('activo');
    document.getElementById('tab-' + nombreTab).classList.add('activo');

    tabActual = nombreTab;

    // Cargar datos según la tab
    if (nombreTab === 'mensajes') {
        cargarChats();
    } else if (nombreTab === 'amigos') {
        cargarAmigos();
        cargarSolicitudes();
    }
}

/* ============================================================
   CHATS — lista de conversaciones
   ============================================================ */

/**
 * Carga la lista de conversaciones del usuario y la renderiza.
 */
function cargarChats() {
    fetch('/api/social/chats', {
        headers: { 'Accept': 'application/json' }
    })
    .then(function (res) { return res.json(); })
    .then(function (respuesta) {
        var contenedor = document.getElementById('lista-chats');

        // Ocultar skeletons
        var skeleton = document.getElementById('skeleton-chats');
        if (skeleton) skeleton.style.display = 'none';

        if (!respuesta.exito || respuesta.datos.length === 0) {
            contenedor.innerHTML = '<p class="social-vacio-texto">Aún no tienes conversaciones.<br>¡Habla con tus amigos!</p>';
            return;
        }

        var html = '';
        respuesta.datos.forEach(function (chat) {
            var amigo        = chat.amigo;
            var ultimoMsj    = chat.ultimo_mensaje;
            var noLeidos     = chat.no_leidos;
            var nombreAmigo  = amigo.nombre + ' ' + amigo.apellido1;
            var avatarHtml   = construirAvatar(amigo.foto_url, amigo.nombre, amigo.apellido1, 'md');

            // Vista previa del último mensaje
            var vistaPrevia = '';
            if (ultimoMsj) {
                var prefijo  = ultimoMsj.es_mio ? 'Tú: ' : '';
                var texto    = ultimoMsj.contenido.length > 38
                    ? ultimoMsj.contenido.substring(0, 38) + '…'
                    : ultimoMsj.contenido;
                vistaPrevia  = prefijo + texto;
            } else {
                vistaPrevia  = 'Sin mensajes aún';
            }

            // Badge de no leídos
            var badgeHtml = noLeidos > 0
                ? '<span class="chat-item-badge">' + noLeidos + '</span>'
                : '';

            // Hora del último mensaje
            var horaHtml = ultimoMsj
                ? '<span class="chat-item-hora">' + formatearHora(ultimoMsj.fecha) + '</span>'
                : '';

            html += '<div class="chat-item" onclick="abrirChat(' + amigo.id + ', \'' + escaparTexto(nombreAmigo) + '\', \'' + escaparTexto(amigo.foto_url || '') + '\')" data-chat-id="' + chat.chat_id + '">'
                  +   '<div class="chat-item-avatar">' + avatarHtml + '</div>'
                  +   '<div class="chat-item-info">'
                  +     '<div class="chat-item-fila-top">'
                  +       '<span class="chat-item-nombre' + (noLeidos > 0 ? ' negrita' : '') + '">' + escaparHtml(nombreAmigo) + '</span>'
                  +       horaHtml
                  +     '</div>'
                  +     '<div class="chat-item-fila-bot">'
                  +       '<span class="chat-item-preview' + (noLeidos > 0 ? ' negrita' : '') + '">' + escaparHtml(vistaPrevia) + '</span>'
                  +       badgeHtml
                  +     '</div>'
                  +   '</div>'
                  + '</div>';
        });

        contenedor.innerHTML = html;
    })
    .catch(function () {
        document.getElementById('lista-chats').innerHTML =
            '<p class="social-error-texto">No se pudo cargar los chats.</p>';
    });
}

/* ============================================================
   CHAT ABIERTO — mensajes en tiempo real
   ============================================================ */

/**
 * Abre (o crea) el chat con un amigo.
 * Llama al servidor para obtener el chat_id y luego carga los mensajes.
 */
function abrirChat(amigoId, nombreAmigo, fotoUrl) {
    // Detener polling anterior si había uno
    detenerPolling();

    fetch('/api/social/chats/abrir', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept':        'application/json',
            'X-CSRF-TOKEN':  csrfToken,
        },
        body: JSON.stringify({ amigo_id: amigoId }),
    })
    .then(function (res) { return res.json(); })
    .then(function (respuesta) {
        if (!respuesta.exito) {
            alert(respuesta.mensaje || 'No se pudo abrir el chat.');
            return;
        }

        var chatId = respuesta.datos.chat_id;
        var amigo  = respuesta.datos.amigo;

        chatActualId = chatId;
        ultimoMensajeId = 0;

        // Mostrar la ventana de chat y ocultar el estado vacío
        document.getElementById('chat-vacio').style.display    = 'none';
        document.getElementById('chat-ventana').style.display  = 'flex';

        // En móvil: ocultar el sidebar y mostrar el chat
        document.getElementById('social-sidebar').classList.add('oculto-movil');
        document.getElementById('area-chat').classList.add('activo-movil');

        // Rellenar la cabecera del chat con los datos del amigo
        var cabecera = document.getElementById('chat-amigo-info');
        var avatarCabecera = construirAvatar(amigo.foto_url, amigo.nombre, amigo.apellido1, 'sm');
        cabecera.innerHTML =
              '<div class="chat-cab-avatar">' + avatarCabecera + '</div>'
            + '<div class="chat-cab-datos">'
            +   '<p class="chat-cab-nombre">' + escaparHtml(amigo.nombre + ' ' + amigo.apellido1) + '</p>'
            +   (amigo.mood ? '<p class="chat-cab-mood">' + escaparHtml(amigo.mood) + '</p>' : '')
            + '</div>';

        // Cargar los mensajes del chat
        cargarMensajes(chatId);

        // Iniciar polling cada 3 segundos para recibir mensajes nuevos
        iniciarPolling(chatId);

        // Enfocar el textarea
        setTimeout(function () {
            document.getElementById('chat-textarea').focus();
        }, 150);
    })
    .catch(function () {
        alert('Error al abrir el chat. Inténtalo de nuevo.');
    });
}

/**
 * Carga todos los mensajes de un chat y los renderiza.
 * Hace scroll automático hasta el último mensaje.
 */
function cargarMensajes(chatId) {
    document.getElementById('cargando-mensajes').style.display = 'flex';

    fetch('/api/social/chats/' + chatId + '/mensajes', {
        headers: { 'Accept': 'application/json' }
    })
    .then(function (res) { return res.json(); })
    .then(function (respuesta) {
        document.getElementById('cargando-mensajes').style.display = 'none';

        if (!respuesta.exito) return;

        var area = document.getElementById('chat-mensajes');
        area.innerHTML = '';

        if (respuesta.datos.length === 0) {
            area.innerHTML = '<p class="chat-sin-mensajes">¡Sé el primero en escribir! 👋</p>';
            return;
        }

        var fechaAnterior = null;

        respuesta.datos.forEach(function (msg) {
            // Separador de fecha cuando cambia el día
            var fechaMensaje = msg.fecha ? msg.fecha.substring(0, 10) : null;
            if (fechaMensaje && fechaMensaje !== fechaAnterior) {
                area.appendChild(crearSeparadorFecha(msg.fecha));
                fechaAnterior = fechaMensaje;
            }

            area.appendChild(crearBurbujaMensaje(msg));

            // Guardar el ID más alto para el polling
            if (msg.id > ultimoMensajeId) {
                ultimoMensajeId = msg.id;
            }
        });

        // Scroll al final
        scrollAlFinal(area);
    })
    .catch(function () {
        document.getElementById('cargando-mensajes').style.display = 'none';
    });
}

/**
 * Envía el mensaje escrito en el textarea.
 * Añade la burbuja al instante (optimistic update) sin esperar respuesta.
 */
function enviarMensaje() {
    var textarea  = document.getElementById('chat-textarea');
    var contenido = textarea.value.trim();

    if (!contenido || !chatActualId) return;

    // Deshabilitar el botón mientras se envía
    var btnEnviar = document.getElementById('btn-enviar-mensaje');
    btnEnviar.disabled = true;

    fetch('/api/social/chats/' + chatActualId + '/mensajes', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept':        'application/json',
            'X-CSRF-TOKEN':  csrfToken,
        },
        body: JSON.stringify({ contenido: contenido }),
    })
    .then(function (res) { return res.json(); })
    .then(function (respuesta) {
        btnEnviar.disabled = false;

        if (!respuesta.exito) {
            alert('No se pudo enviar el mensaje.');
            return;
        }

        // Limpiar el textarea y restaurar altura
        textarea.value = '';
        textarea.style.height = 'auto';

        var area = document.getElementById('chat-mensajes');

        // Quitar el mensaje de "sin mensajes" si existía
        var sinMensajes = area.querySelector('.chat-sin-mensajes');
        if (sinMensajes) sinMensajes.remove();

        // Añadir la burbuja del mensaje enviado
        area.appendChild(crearBurbujaMensaje(respuesta.datos));

        // Actualizar el último ID conocido
        if (respuesta.datos.id > ultimoMensajeId) {
            ultimoMensajeId = respuesta.datos.id;
        }

        scrollAlFinal(area);
    })
    .catch(function () {
        btnEnviar.disabled = false;
        alert('Error al enviar el mensaje. Comprueba tu conexión.');
    });
}

/**
 * Inicia el polling: comprueba mensajes nuevos cada 3 segundos.
 */
function iniciarPolling(chatId) {
    detenerPolling();

    intervaloPolling = setInterval(function () {
        if (!chatActualId) {
            detenerPolling();
            return;
        }

        fetch('/api/social/chats/' + chatId + '/nuevos?desde=' + ultimoMensajeId, {
            headers: { 'Accept': 'application/json' }
        })
        .then(function (res) { return res.json(); })
        .then(function (respuesta) {
            if (!respuesta.exito || respuesta.datos.length === 0) return;

            var area = document.getElementById('chat-mensajes');

            // Quitar el mensaje de "sin mensajes" si existía
            var sinMensajes = area.querySelector('.chat-sin-mensajes');
            if (sinMensajes) sinMensajes.remove();

            var eraFinal = estaEnElFinal(area);
            var fechaAnterior = obtenerUltimaFechaEnArea(area);

            respuesta.datos.forEach(function (msg) {
                // Separador si cambia el día
                var fechaMensaje = msg.fecha ? msg.fecha.substring(0, 10) : null;
                if (fechaMensaje && fechaMensaje !== fechaAnterior) {
                    area.appendChild(crearSeparadorFecha(msg.fecha));
                    fechaAnterior = fechaMensaje;
                }

                area.appendChild(crearBurbujaMensaje(msg));

                if (msg.id > ultimoMensajeId) {
                    ultimoMensajeId = msg.id;
                }
            });

            // Solo hacer scroll automático si el usuario ya estaba al final
            if (eraFinal) scrollAlFinal(area);
        })
        .catch(function () { /* silencioso: reintentará en el siguiente ciclo */ });

    }, 3000);
}

/** Detiene el polling de mensajes. */
function detenerPolling() {
    if (intervaloPolling) {
        clearInterval(intervaloPolling);
        intervaloPolling = null;
    }
}

/** Cierra el chat abierto y vuelve al panel lateral (especialmente en móvil). */
function cerrarChat() {
    detenerPolling();
    chatActualId = null;

    document.getElementById('chat-vacio').style.display   = 'flex';
    document.getElementById('chat-ventana').style.display = 'none';

    // En móvil: volver a mostrar el sidebar
    document.getElementById('social-sidebar').classList.remove('oculto-movil');
    document.getElementById('area-chat').classList.remove('activo-movil');
}

/* ============================================================
   AMIGOS — lista y solicitudes
   ============================================================ */

/**
 * Carga la lista de amigos aceptados y la renderiza.
 */
function cargarAmigos() {
    fetch('/api/social/amigos', {
        headers: { 'Accept': 'application/json' }
    })
    .then(function (res) { return res.json(); })
    .then(function (respuesta) {
        var contenedor = document.getElementById('lista-amigos');

        var skeleton = document.getElementById('skeleton-amigos');
        if (skeleton) skeleton.style.display = 'none';

        if (!respuesta.exito || respuesta.datos.length === 0) {
            contenedor.innerHTML = '<p class="social-vacio-texto">Aún no tienes amigos.<br>¡Usa "Descubrir" para encontrar gente!</p>';
            return;
        }

        var html = '';
        respuesta.datos.forEach(function (amigo) {
            var nombre = amigo.nombre + ' ' + amigo.apellido1;
            var avatar = construirAvatar(amigo.foto_url, amigo.nombre, amigo.apellido1, 'sm');

            html += '<div class="amigo-item">'
                  +   '<div class="amigo-item-avatar">' + avatar + '</div>'
                  +   '<div class="amigo-item-info">'
                  +     '<p class="amigo-item-nombre">' + escaparHtml(nombre) + '</p>'
                  +     (amigo.mood ? '<p class="amigo-item-mood">' + escaparHtml(amigo.mood) + '</p>' : '')
                  +   '</div>'
                  +   '<button class="btn-chat-amigo" onclick="abrirChat(' + amigo.id + ', \'' + escaparTexto(nombre) + '\', \'' + escaparTexto(amigo.foto_url || '') + '\')" title="Enviar mensaje">'
                  +     '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>'
                  +   '</button>'
                  + '</div>';
        });

        contenedor.innerHTML = html;
    })
    .catch(function () {
        document.getElementById('lista-amigos').innerHTML =
            '<p class="social-error-texto">No se pudo cargar los amigos.</p>';
    });
}

/**
 * Carga las solicitudes de amistad pendientes recibidas.
 * Muestra u oculta la sección según haya o no solicitudes.
 */
function cargarSolicitudes() {
    fetch('/api/social/solicitudes', {
        headers: { 'Accept': 'application/json' }
    })
    .then(function (res) { return res.json(); })
    .then(function (respuesta) {
        var seccion    = document.getElementById('seccion-solicitudes');
        var contenedor = document.getElementById('lista-solicitudes');

        if (!respuesta.exito || respuesta.datos.length === 0) {
            seccion.style.display = 'none';
            return;
        }

        seccion.style.display = 'block';

        var html = '';
        respuesta.datos.forEach(function (sol) {
            var nombre = sol.nombre + ' ' + sol.apellido1;
            var avatar = construirAvatar(sol.foto_url, sol.nombre, sol.apellido1, 'sm');

            html += '<div class="solicitud-item" id="solicitud-' + sol.id + '">'
                  +   '<div class="solicitud-avatar">' + avatar + '</div>'
                  +   '<div class="solicitud-info">'
                  +     '<p class="solicitud-nombre">' + escaparHtml(nombre) + '</p>'
                  +     '<p class="solicitud-sub">Quiere ser tu amigo</p>'
                  +   '</div>'
                  +   '<div class="solicitud-acciones">'
                  +     '<button class="btn-aceptar" onclick="aceptarSolicitud(' + sol.id + ')" title="Aceptar">✓</button>'
                  +     '<button class="btn-rechazar" onclick="rechazarSolicitud(' + sol.id + ')" title="Rechazar">✕</button>'
                  +   '</div>'
                  + '</div>';
        });

        contenedor.innerHTML = html;
    })
    .catch(function () { /* silencioso */ });
}

/**
 * Acepta una solicitud de amistad.
 * Elimina el elemento del DOM y recarga la lista de amigos.
 */
function aceptarSolicitud(solicitudId) {
    fetch('/api/social/solicitudes/' + solicitudId + '/aceptar', {
        method: 'POST',
        headers: {
            'Accept':       'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
    })
    .then(function (res) { return res.json(); })
    .then(function (respuesta) {
        if (!respuesta.exito) {
            alert(respuesta.mensaje);
            return;
        }

        // Eliminar la solicitud del DOM con animación
        var elementoSolicitud = document.getElementById('solicitud-' + solicitudId);
        if (elementoSolicitud) {
            elementoSolicitud.classList.add('fadeOut');
            setTimeout(function () {
                elementoSolicitud.remove();
                // Recargar la lista de amigos para incluir al nuevo
                cargarAmigos();
                cargarSolicitudes();
                actualizarContadorNavbar();
            }, 300);
        }
    })
    .catch(function () {
        alert('No se pudo aceptar la solicitud. Inténtalo de nuevo.');
    });
}

/**
 * Rechaza una solicitud de amistad.
 */
function rechazarSolicitud(solicitudId) {
    fetch('/api/social/solicitudes/' + solicitudId + '/rechazar', {
        method: 'POST',
        headers: {
            'Accept':       'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
    })
    .then(function (res) { return res.json(); })
    .then(function (respuesta) {
        var elementoSolicitud = document.getElementById('solicitud-' + solicitudId);
        if (elementoSolicitud) {
            elementoSolicitud.classList.add('fadeOut');
            setTimeout(function () {
                elementoSolicitud.remove();
                cargarSolicitudes();
                actualizarContadorNavbar();
            }, 300);
        }
    })
    .catch(function () {
        alert('No se pudo rechazar la solicitud.');
    });
}

/* ============================================================
   DESCUBRIR — buscar personas nuevas
   ============================================================ */

/**
 * Busca personas por nombre o email con debounce de 350ms.
 */
function buscarPersonas(query) {
    clearTimeout(temporizadorBusqueda);

    var contenedor = document.getElementById('resultados-descubrir');

    if (query.length < 2) {
        contenedor.innerHTML = '<p class="social-vacio-texto" style="padding:20px 16px">Escribe al menos 2 caracteres para buscar</p>';
        return;
    }

    // Mostrar indicador de búsqueda
    contenedor.innerHTML = '<p class="social-vacio-texto" style="padding:16px">Buscando…</p>';

    temporizadorBusqueda = setTimeout(function () {
        fetch('/api/social/usuarios/buscar?q=' + encodeURIComponent(query), {
            headers: { 'Accept': 'application/json' }
        })
        .then(function (res) { return res.json(); })
        .then(function (respuesta) {
            if (!respuesta.exito || respuesta.datos.length === 0) {
                contenedor.innerHTML = '<p class="social-vacio-texto" style="padding:16px">No se encontraron usuarios con "' + escaparHtml(query) + '"</p>';
                return;
            }

            var html = '';
            respuesta.datos.forEach(function (persona) {
                var nombre = persona.nombre + ' ' + persona.apellido1;
                var avatar = construirAvatar(persona.foto_url, persona.nombre, persona.apellido1, 'sm');

                html += '<div class="descubrir-item" id="descubrir-' + persona.id + '">'
                      +   '<div class="descubrir-avatar">' + avatar + '</div>'
                      +   '<div class="descubrir-info">'
                      +     '<p class="descubrir-nombre">' + escaparHtml(nombre) + '</p>'
                      +   '</div>'
                      +   '<button class="btn-enviar-solicitud" id="btn-sol-' + persona.id + '" onclick="enviarSolicitudDescubrir(' + persona.id + ', this)">'
                      +     'Añadir'
                      +   '</button>'
                      + '</div>';
            });

            contenedor.innerHTML = html;
        })
        .catch(function () {
            contenedor.innerHTML = '<p class="social-error-texto">Error al buscar. Inténtalo de nuevo.</p>';
        });
    }, 350);
}

/**
 * Envía solicitud de amistad desde la sección Descubrir.
 * Cambia el estado del botón para evitar doble envío.
 */
function enviarSolicitudDescubrir(receptorId, boton) {
    boton.disabled    = true;
    boton.textContent = 'Enviando…';

    fetch('/api/social/solicitud', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept':        'application/json',
            'X-CSRF-TOKEN':  csrfToken,
        },
        body: JSON.stringify({ receptor_id: receptorId }),
    })
    .then(function (res) { return res.json(); })
    .then(function (respuesta) {
        if (respuesta.exito) {
            boton.textContent  = 'Enviado ✓';
            boton.classList.add('enviado');
        } else {
            boton.disabled     = false;
            boton.textContent  = 'Añadir';
            alert(respuesta.mensaje);
        }
    })
    .catch(function () {
        boton.disabled    = false;
        boton.textContent = 'Añadir';
        alert('Error al enviar la solicitud.');
    });
}

/* ============================================================
   BADGE DEL NAVBAR
   ============================================================ */

/**
 * Consulta el contador de no leídos y solicitudes pendientes.
 * Actualiza el badge del enlace Social en el navbar.
 */
function actualizarContadorNavbar() {
    fetch('/api/social/contador', {
        headers: { 'Accept': 'application/json' }
    })
    .then(function (res) { return res.json(); })
    .then(function (respuesta) {
        if (!respuesta.exito) return;

        var total  = respuesta.datos.total;
        var badge  = document.getElementById('nav-badge-social');
        if (!badge) return;

        if (total > 0) {
            badge.textContent    = total > 99 ? '99+' : total;
            badge.style.display  = 'inline-flex';
        } else {
            badge.style.display  = 'none';
        }

        // Actualizar también los badges internos de las tabs
        actualizarBadgesInternos(respuesta.datos);
    })
    .catch(function () { /* silencioso */ });
}

/**
 * Actualiza los badges de las tabs (Mensajes y Amigos) con los contadores.
 */
function actualizarBadgesInternos(datos) {
    var badgeMensajes = document.getElementById('badge-mensajes');
    if (badgeMensajes) {
        if (datos.mensajes > 0) {
            badgeMensajes.textContent   = datos.mensajes;
            badgeMensajes.style.display = 'inline-flex';
        } else {
            badgeMensajes.style.display = 'none';
        }
    }

    var badgeSolicitudes = document.getElementById('badge-solicitudes');
    if (badgeSolicitudes) {
        if (datos.solicitudes > 0) {
            badgeSolicitudes.textContent   = datos.solicitudes;
            badgeSolicitudes.style.display = 'inline-flex';
        } else {
            badgeSolicitudes.style.display = 'none';
        }
    }
}

/* ============================================================
   HELPERS DE INTERFAZ
   ============================================================ */

/**
 * Construye el HTML de un elemento de mensaje (burbuja).
 * Distingue entre mensajes propios (derecha) y del otro (izquierda).
 */
function crearBurbujaMensaje(msg) {
    var div = document.createElement('div');
    div.className = 'mensaje-fila ' + (msg.es_mio ? 'mio' : 'suyo');
    div.dataset.id = msg.id;

    var hora = msg.fecha ? formatearHora(msg.fecha) : '';

    div.innerHTML = '<div class="mensaje-burbuja">'
                  +   '<p class="mensaje-texto">' + escaparHtml(msg.contenido).replace(/\n/g, '<br>') + '</p>'
                  +   '<span class="mensaje-hora">' + hora + '</span>'
                  + '</div>';

    return div;
}

/**
 * Crea un separador visual de fecha entre mensajes de días distintos.
 */
function crearSeparadorFecha(fechaStr) {
    var div  = document.createElement('div');
    div.className = 'chat-separador-fecha';
    div.textContent = formatearFechaCompleta(fechaStr);
    return div;
}

/**
 * Construye el HTML de un avatar de usuario.
 * Si tiene foto usa la imagen; si no, muestra las iniciales sobre gradiente morado.
 * tamaño: 'sm' (32px) | 'md' (40px)
 */
function construirAvatar(fotoUrl, nombre, apellido, tamaño) {
    var clase  = 'avatar-' + (tamaño || 'sm');
    var inicia = obtenerIniciales(nombre, apellido);

    if (fotoUrl) {
        return '<img src="' + escaparHtml(fotoUrl) + '" alt="' + escaparHtml(nombre) + '" class="' + clase + ' avatar-img">';
    }

    return '<div class="' + clase + ' avatar-iniciales">' + inicia + '</div>';
}

/** Devuelve las iniciales de un nombre y apellido. */
function obtenerIniciales(nombre, apellido) {
    var ini1 = nombre  ? nombre.charAt(0).toUpperCase()  : '';
    var ini2 = apellido ? apellido.charAt(0).toUpperCase() : '';
    return ini1 + ini2;
}

/**
 * Formatea una fecha ISO a hora (HH:MM).
 */
function formatearHora(fechaStr) {
    if (!fechaStr) return '';
    try {
        var fecha = new Date(fechaStr.replace(' ', 'T'));
        return fecha.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
    } catch (e) {
        return '';
    }
}

/**
 * Formatea una fecha ISO a texto legible: "Hoy", "Ayer" o "dd/mm/yyyy".
 */
function formatearFechaCompleta(fechaStr) {
    if (!fechaStr) return '';
    try {
        var fecha = new Date(fechaStr.replace(' ', 'T'));
        var hoy   = new Date();

        // Comparar solo la parte de fecha
        var eraHoy  = fecha.toDateString() === hoy.toDateString();
        var ayer    = new Date(hoy); ayer.setDate(hoy.getDate() - 1);
        var eraAyer = fecha.toDateString() === ayer.toDateString();

        if (eraHoy)  return 'Hoy';
        if (eraAyer) return 'Ayer';
        return fecha.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
    } catch (e) {
        return '';
    }
}

/**
 * Escapa caracteres HTML para prevenir XSS.
 */
function escaparHtml(texto) {
    if (!texto) return '';
    return String(texto)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

/**
 * Escapa un texto para usarlo dentro de un atributo onclick entre comillas simples.
 */
function escaparTexto(texto) {
    if (!texto) return '';
    return String(texto).replace(/\\/g, '\\\\').replace(/'/g, "\\'");
}

/**
 * Comprueba si el área de mensajes está desplazada hasta el final (±50px).
 */
function estaEnElFinal(area) {
    return area.scrollHeight - area.scrollTop - area.clientHeight < 50;
}

/**
 * Hace scroll hasta el último mensaje del área.
 */
function scrollAlFinal(area) {
    area.scrollTop = area.scrollHeight;
}

/**
 * Obtiene la fecha del último separador de fecha presente en el área,
 * para no duplicar separadores en el polling.
 */
function obtenerUltimaFechaEnArea(area) {
    var separadores = area.querySelectorAll('.chat-separador-fecha');
    if (separadores.length === 0) return null;
    // Devolvemos el atributo data-fecha del último separador si existe
    var ultimo = separadores[separadores.length - 1];
    return ultimo.dataset.fecha || null;
}

/**
 * Ajusta la altura del textarea al contenido (máximo 120px).
 */
function ajustarAlturaTextarea(textarea) {
    textarea.style.height = 'auto';
    textarea.style.height = Math.min(textarea.scrollHeight, 120) + 'px';
}

/**
 * Envía el mensaje al pulsar Enter (sin Shift).
 * Shift+Enter inserta salto de línea.
 */
function manejarTeclaEnvio(event) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        enviarMensaje();
    }
}

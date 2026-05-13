/**
 * perfil.js — VIBEZ
 *
 * Solo contiene lógica que NO se puede hacer con formularios HTML simples:
 *   1. previsualizarFoto()   → muestra preview local antes de subir la foto
 *   2. buscarAmigos()        → búsqueda dinámica sin recargar la página (AJAX)
 *   3. enviarSolicitud()     → envía solicitud desde los resultados de búsqueda
 *
 * Todo lo demás (guardar datos, guardar mood, aceptar/rechazar amigos)
 * usa formularios HTML normales con action + method + @csrf.
 *
 * Dependencias:
 *   - Un meta tag <meta name="csrf-token"> con el token CSRF de Laravel.
 *   - Los endpoints GET /api/amigos/buscar?q=... y POST /api/amigos/solicitud.
 *   - Los elementos del DOM con IDs: avatarPreview, btnGuardarFoto,
 *     resultadosBusqueda, btn-sol-{id}.
 *
 * Funciones públicas (llamadas desde atributos oninput/onclick en el HTML):
 *   previsualizarFoto(input)          — previsualiza foto antes de subirla
 *   buscarAmigos(valor)               — lanza búsqueda con debounce de 350 ms
 *   enviarSolicitud(receptorId, btn)  — POST de solicitud de amistad
 */

/* ─── Helper: leer el token CSRF del meta tag ─── */
/**
 * Obtiene el token CSRF que Laravel inyecta en el meta tag de la plantilla.
 * Este token es obligatorio en todas las peticiones POST/PUT/DELETE para que
 * el servidor pueda verificar que la petición proviene del propio sitio.
 *
 * @returns {string} El valor del token CSRF
 */
function getCsrf() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
}

/* ============================================================
   PREVIEW DE FOTO
   ============================================================ */

/**
 * Muestra una previsualización local de la imagen seleccionada.
 * NO sube nada al servidor todavía — eso lo hace el formulario al pulsar "Guardar foto".
 *
 * @param {HTMLInputElement} input - El input[type=file] con la imagen elegida
 */
function previsualizarFoto(input) {
    const archivo = input.files[0];
    if (!archivo) return;

    // Validación de tamaño en el cliente (5 MB) para feedback inmediato.
    // El servidor también valida, pero hacerlo aquí evita una petición innecesaria
    // y da al usuario una respuesta instantánea sin esperar la subida.
    if (archivo.size > 5 * 1024 * 1024) {
        alert('La imagen no puede superar 5 MB.');
        input.value = '';
        return;
    }

    // FileReader es una API del navegador que permite leer archivos locales
    // sin necesidad de subirlos al servidor. Genera una URL en formato base64
    // (Data URL) que podemos usar directamente como src de una imagen.
    const reader = new FileReader();
    reader.onload = function (e) {
        // Reemplazamos el contenido del avatar con la imagen en base64.
        // e.target.result contiene la cadena "data:image/jpeg;base64,/9j/4AA..."
        const avatar = document.getElementById('avatarPreview');
        avatar.innerHTML = '<img src="' + e.target.result + '" alt="foto" style="width:100%;height:100%;object-fit:cover;">';
    };
    reader.readAsDataURL(archivo);

    // Mostramos el botón "Guardar foto" para que el usuario confirme la subida.
    // Está oculto por defecto para evitar que el usuario lo pulse sin haber seleccionado foto.
    const btnGuardar = document.getElementById('btnGuardarFoto');
    if (btnGuardar) {
        btnGuardar.style.display = 'block';
    }
}

/* ============================================================
   BÚSQUEDA DINÁMICA DE AMIGOS
   Necesita AJAX porque los resultados aparecen sin recargar la página.
   ============================================================ */

// Variable en el scope del módulo que guarda el ID del último temporizador de búsqueda.
// Al cancelarla con clearTimeout antes de crear una nueva, implementamos el patrón debounce.
let _buscarTimeout = null;

/**
 * Busca usuarios con un retraso de 350 ms (debounce) para no llamar al servidor
 * en cada tecla pulsada, sino solo cuando el usuario deja de escribir.
 *
 * Sin debounce, si el usuario escribe "Maria" (5 teclas) se harían 5 peticiones
 * al servidor: "M", "Ma", "Mar", "Mari", "Maria". Con debounce solo se hace 1.
 *
 * @param {string} valor - Texto escrito en el campo de búsqueda
 */
function buscarAmigos(valor) {
    // Cancelar el temporizador anterior: si el usuario sigue escribiendo,
    // el setTimeout anterior no se habrá ejecutado todavía y lo descartamos
    clearTimeout(_buscarTimeout);

    const contenedor = document.getElementById('resultadosBusqueda');

    // Con menos de 2 caracteres no buscamos para evitar devolver casi todos los usuarios
    // y reducir la carga innecesaria en el servidor
    if (valor.length < 2) {
        contenedor.style.display = 'none';
        contenedor.innerHTML = '';
        return;
    }

    // Creamos un nuevo temporizador: si el usuario no escribe nada más en 350 ms,
    // se ejecutará la función de búsqueda
    _buscarTimeout = setTimeout(function () {
        // encodeURIComponent convierte caracteres especiales para que sean seguros en una URL.
        // Ejemplo: "ñ" → "%C3%B1", "á" → "%C3%A1", "&" → "%26".
        // Sin esto, un nombre como "José & Ana" rompería la URL del servidor.
        fetch('/api/amigos/buscar?q=' + encodeURIComponent(valor), {
            headers: { 'Accept': 'application/json' },
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            contenedor.innerHTML = '';

            if (!data.data || data.data.length === 0) {
                contenedor.innerHTML = '<div class="perfil-busqueda-item" style="color:rgba(15,23,42,0.4);justify-content:center">Sin resultados</div>';
                contenedor.style.display = 'block';
                return;
            }

            // Por cada usuario encontrado creamos dinámicamente un elemento con botón para añadir.
            // Usamos createElement en lugar de innerHTML para evitar XSS si los datos
            // del servidor contuviesen HTML malicioso.
            data.data.forEach(function (usuario) {
                // Generamos las iniciales como alternativa visual si el usuario no tiene foto
                const iniciales = (usuario.nombre?.[0] ?? '').toUpperCase() + (usuario.apellido1?.[0] ?? '').toUpperCase();

                const avatarHTML = usuario.foto_url
                    ? '<div class="perfil-solicitud-avatar"><img src="' + usuario.foto_url + '" alt=""></div>'
                    : '<div class="perfil-solicitud-avatar">' + iniciales + '</div>';

                const div = document.createElement('div');
                div.className = 'perfil-busqueda-item';
                div.innerHTML = avatarHTML +
                    '<div style="flex:1;min-width:0">' +
                        '<p style="font-weight:600;font-size:.85rem;color:#0f172a;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">' +
                            usuario.nombre + ' ' + (usuario.apellido1 ?? '') +
                        '</p>' +
                    '</div>' +
                    // El ID único del botón (`btn-sol-{id}`) nos permite localizarlo
                    // desde enviarSolicitud para actualizarlo tras la respuesta del servidor
                    '<button class="btn-enviar-solicitud" id="btn-sol-' + usuario.id + '" ' +
                            'onclick="enviarSolicitud(' + usuario.id + ', this)">' +
                        '+ Añadir' +
                    '</button>';

                contenedor.appendChild(div);
            });

            contenedor.style.display = 'block';
        })
        .catch(function () {
            // Si la búsqueda falla (sin conexión, error 500...) simplemente
            // no mostramos resultados; no es necesario alertar al usuario por esto
        });
    }, 350);
}

/* ============================================================
   ENVIAR SOLICITUD DE AMISTAD
   Está aquí (AJAX) porque el botón "Añadir" se genera dinámicamente
   en los resultados de búsqueda, por lo que no puede ser un formulario fijo.
   ============================================================ */

/**
 * Envía la solicitud de amistad al servidor y actualiza el botón para indicar
 * que ya fue enviada, impidiendo que el usuario la envíe una segunda vez.
 *
 * @param {number}      receptorId - ID del usuario al que queremos añadir como amigo
 * @param {HTMLElement} btn        - Referencia al botón pulsado (para desactivarlo tras el envío)
 */
/* ============================================================
   MOOD — selección sin recarga de página
   ============================================================ */

/**
 * Envía el mood elegido (o cadena vacía para borrarlo) al servidor via AJAX
 * y actualiza la UI sin recargar la página.
 *
 * @param {string}           valor - Emoji + texto del mood, o '' para quitarlo
 * @param {HTMLElement|null} btn   - Botón pulsado (para marcar activo), o null al quitar
 */
function seleccionarMood(valor, btn) {
    fetch('/perfil/mood', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept':       'application/json',
            'X-CSRF-TOKEN': getCsrf(),
        },
        body: JSON.stringify({ mood: valor }),
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (!data.success) return;

        // Actualizar estado visual de los botones del grid
        document.querySelectorAll('.mood-opcion').forEach(function(b) {
            b.classList.remove('mood-opcion--activo');
        });
        if (valor && btn) {
            btn.classList.add('mood-opcion--activo');
        }

        // Mostrar u ocultar el badge del mood activo
        var badge  = document.getElementById('mood-activo');
        var texto  = document.getElementById('mood-activo-texto');
        if (valor) {
            texto.textContent  = valor;
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }

        // Limpiar el área de mood personalizado (input + emoji seleccionado)
        var inputPersonalizado = document.getElementById('mood-personalizado');
        if (inputPersonalizado) inputPersonalizado.value = '';
        _emojiPersonalizado = '';
        var spanEmoji = document.getElementById('emoji-seleccionado');
        if (spanEmoji) { spanEmoji.textContent = '🙂'; spanEmoji.style.opacity = '0.4'; }

        // Actualizar el emoji en el badge del avatar y en el dropdown
        var emoji    = valor ? ([...valor][0] || '') : '';
        var navBadge = document.querySelector('.nav-mood-badge');
        if (navBadge) {
            if (emoji) {
                navBadge.textContent   = emoji;
                navBadge.style.display = '';
            } else {
                navBadge.style.display = 'none';
            }
        }
        var dropdownMood = document.querySelector('.nav-dropdown-mood');
        if (dropdownMood) {
            dropdownMood.textContent   = emoji;
            dropdownMood.style.display = emoji ? '' : 'none';
        }

        // Mostrar alerta inline durante 3 segundos
        var alerta = document.getElementById('mood-alerta');
        if (alerta) {
            alerta.textContent  = data.message;
            alerta.style.display = 'block';
            setTimeout(function() { alerta.style.display = 'none'; }, 3000);
        }
    })
    .catch(function() { /* fallo silencioso */ });
}

/**
 * Combina el emoji seleccionado con el texto del input y lo envía via AJAX.
 * El emoji es obligatorio.
 */
function enviarMoodPersonalizado() {
    var input = document.getElementById('mood-personalizado');
    var texto = input ? input.value.trim() : '';

    if (!_emojiPersonalizado) {
        var btn   = document.getElementById('btn-emoji-picker');
        var aviso = document.getElementById('mood-emoji-aviso');
        if (btn)   { btn.style.borderColor = '#f87171'; setTimeout(function () { btn.style.borderColor = 'rgba(124,58,237,0.3)'; }, 1500); }
        if (aviso) { aviso.style.display = 'block'; setTimeout(function () { aviso.style.display = 'none'; }, 1500); }
        return;
    }

    if (!texto) return;

    seleccionarMood(_emojiPersonalizado + ' ' + texto, null);
}

/* ============================================================
   EMOJI PICKER
   ============================================================ */

var _emojiPersonalizado = ''; // emoji seleccionado para el mood personalizado

function toggleEmojiPicker(event) {
    event.stopPropagation();
    var panel = document.getElementById('emoji-picker-panel');
    if (!panel) return;
    panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
}

/**
 * Marca el emoji como seleccionado para el mood personalizado (no lo inserta en el input).
 * El emoji se combinará con el texto del input al guardar.
 *
 * @param {string} emoji
 */
function insertarEmoji(emoji) {
    _emojiPersonalizado = emoji;

    var span = document.getElementById('emoji-seleccionado');
    if (span) {
        span.textContent  = emoji;
        span.style.opacity = '1';
    }

    document.getElementById('emoji-picker-panel').style.display = 'none';
    var input = document.getElementById('mood-personalizado');
    if (input) input.focus();
}

// Cerrar el panel al hacer clic fuera de él
document.addEventListener('click', function () {
    var panel = document.getElementById('emoji-picker-panel');
    if (panel) panel.style.display = 'none';
});

function enviarSolicitud(receptorId, btn) {
    // Deshabilitamos el botón inmediatamente para prevenir el doble envío:
    // si el usuario hace doble clic rápido, la segunda pulsación no tendrá efecto
    // porque el botón ya estará deshabilitado desde la primera
    btn.disabled    = true;
    btn.textContent = '...';  // indicador visual de que la petición está en curso

    fetch('/api/amigos/solicitud', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept':       'application/json',
            'X-CSRF-TOKEN': getCsrf(),
        },
        body: JSON.stringify({ receptor_id: receptorId }),
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
        // Actualizamos el texto del botón según la respuesta del servidor:
        // éxito → muestra un tick y queda deshabilitado; error → muestra el mensaje
        btn.textContent = data.success ? '✓ Enviado' : '⚠ ' + (data.message ?? 'Error');
        btn.classList.add('btn-enviado');
        // Mantenemos el botón deshabilitado tras el éxito para que el usuario
        // no pueda enviar la misma solicitud de amistad dos veces
        btn.disabled = true;
    })
    .catch(function () {
        // Si hay error de red, volvemos a habilitar el botón para que el usuario
        // pueda intentarlo de nuevo
        btn.textContent = 'Error';
        btn.disabled    = false;
    });
}

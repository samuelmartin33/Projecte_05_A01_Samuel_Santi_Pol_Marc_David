/**
 * favoritos.js — VIBEZ
 *
 * Gestiona el sistema de favoritos de eventos, tanto en las tarjetas del listado
 * como en la página de detalle de un evento.
 *
 * Patrón de comunicación PHP → JS:
 *   La plantilla Blade escribe un objeto global `window.vibezFavoritosConfig`
 *   con los datos que este script necesita (si el usuario está autenticado,
 *   la URL de login, etc.). Este patrón es la forma segura de pasar variables
 *   de servidor a JavaScript sin incrustar PHP directamente en el .js.
 *
 * Patrón "soft toggle" (toggle suave):
 *   En lugar de eliminar el registro de favorito de la base de datos, el servidor
 *   lo marca como activo (1) o inactivo (0). Esto preserva el historial y evita
 *   problemas de concurrencia al alternar rápidamente el estado.
 *
 * Dependencias:
 *   - `window.vibezFavoritosConfig` debe estar definido en el HTML antes de
 *     cargar este script (normalmente en un bloque <script> de la plantilla Blade).
 *   - Un meta tag <meta name="csrf-token"> con el token CSRF de Laravel.
 *   - El endpoint POST /api/favoritos/toggle en el servidor.
 *
 * Funciones públicas (llamadas desde onclick en el HTML):
 *   crearBotonFavorito(eventoId, esFavorito)  — genera el HTML del botón corazón
 *   toggleFavorito(event, boton)              — alterna favorito desde tarjetas del listado
 *   toggleFavoritoDetalle(btn)                — alterna favorito desde la página de detalle
 */

// Leemos la configuración que PHP escribió en el objeto global.
// Si por algún motivo el objeto no existe (página cargada sin la plantilla correcta)
// usamos un objeto vacío como fallback para evitar errores de acceso a null.
var FAVORITOS_CFG = window.vibezFavoritosConfig || {};

// Convertimos el valor a booleano explícito; si la clave no existe será false (usuario invitado)
var USER_AUTHENTICATED = Boolean(FAVORITOS_CFG.userAuthenticated);

// URL de login para redirigir al usuario si intenta marcar favorito sin estar autenticado
var LOGIN_URL = FAVORITOS_CFG.loginUrl || '/login';

function obtenerCsrfToken() {
    var metadatos = document.getElementsByTagName('meta');

    for (var indice = 0; indice < metadatos.length; indice++) {
        if (metadatos[indice].getAttribute('name') === 'csrf-token') {
            return metadatos[indice].getAttribute('content');
        }
    }

    return '';
}

/**
 * Actualiza el estado visual de un botón de favorito (corazón) individualmente.
 * Se usa como función auxiliar llamada por las funciones de sincronización.
 *
 * @param {HTMLElement} btn        - El botón cuyo estado visual queremos actualizar
 * @param {boolean}     esFavorito - true si el evento ahora es favorito, false si no
 */
function actualizarBotonFavorito(btn, esFavorito) {
    // Guardamos el estado en el data-attribute para que toggleFavorito pueda leerlo después
    btn.dataset.favorito = esFavorito ? '1' : '0';
    // classList.toggle con segundo argumento añade o quita la clase según el booleano
    btn.classList.toggle('activo', esFavorito);
    // aria-pressed comunica el estado del botón a tecnologías de asistencia (lectores de pantalla)
    btn.setAttribute('aria-pressed', esFavorito ? 'true' : 'false');
}

/**
 * Sincroniza TODOS los botones de favorito de un mismo evento en la página.
 * Puede haber múltiples tarjetas del mismo evento visibles a la vez (p. ej.
 * en un slider y en el listado), y todos deben reflejar el mismo estado.
 *
 * @param {number}  eventoId   - ID del evento cuyos botones queremos sincronizar
 * @param {boolean} esFavorito - Nuevo estado del favorito
 */
function sincronizarBotonesFavorito(eventoId, esFavorito) {
    // Seleccionamos todos los botones que tengan la clase btn-favorito-card
    // y filtramos por el eventoId.
    var botones = document.getElementsByClassName('btn-favorito-card');
    Array.from(botones).forEach(function(btn) {
        if (btn.dataset.eventoId === String(eventoId)) {
            actualizarBotonFavorito(btn, esFavorito);
        }
    });
}

/**
 * Genera el HTML completo del botón corazón de favorito para insertarlo
 * dinámicamente en tarjetas de evento generadas por JavaScript.
 * Devuelve cadena vacía si el usuario no está autenticado (los invitados no ven el botón).
 *
 * @param {number}  eventoId   - ID del evento al que pertenece el botón
 * @param {boolean} esFavorito - Si el evento ya es favorito del usuario
 * @returns {string} HTML del botón, o '' si el usuario no está autenticado
 */
function crearBotonFavorito(eventoId, esFavorito) {
    // No mostramos el botón a usuarios no autenticados para no confundirlos:
    // si lo pulsasen serían redirigidos al login, lo que sería una UX confusa en este contexto
    if (!USER_AUTHENTICATED) {
        return '';
    }

    return '<button type="button" class="btn-favorito-card' + (esFavorito ? ' activo' : '') + '"'
        + ' data-evento-id="' + eventoId + '"'
        + ' data-favorito="' + (esFavorito ? '1' : '0') + '"'
        + ' aria-label="Marcar favorito"'
        + ' aria-pressed="' + (esFavorito ? 'true' : 'false') + '"'
        + ' onclick="toggleFavorito(event, this)">'
        + '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">'
        + '<path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>'
        + '</svg>'
        + '</button>';
}

/**
 * Alterna el estado de favorito de un evento al pulsar el botón corazón
 * en las tarjetas del listado general. Gestiona la comunicación con el servidor
 * y actualiza visualmente todos los botones de ese evento en la página.
 *
 * @param {MouseEvent}  event - Evento del click (necesario para detener la propagación)
 * @param {HTMLElement} boton - El botón de corazón que fue pulsado
 */
function toggleFavorito(event, boton) {
    // Detenemos la propagación del click para que no dispare el onclick de la tarjeta
    // que llevaría al usuario a la página de detalle del evento involuntariamente
    event.stopPropagation();

    // Si el usuario no está autenticado lo redirigimos al login
    if (!USER_AUTHENTICATED) {
        window.location.href = LOGIN_URL;
        return;
    }

    // `dataset.loading` actúa como guarda (mutex) para prevenir el doble clic:
    // si el usuario pulsa dos veces muy rápido, la segunda pulsación se ignora
    // mientras la primera petición al servidor aún está en curso
    if (boton.dataset.loading === '1') {
        return;
    }

    var eventoId = parseInt(boton.dataset.eventoId, 10);
    if (!eventoId) {
        return;
    }

    // Activamos la guarda de carga y añadimos la clase visual de "cargando"
    boton.dataset.loading = '1';
    boton.classList.add('cargando');

    fetch('/api/favoritos/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            // El token CSRF evita que sitios externos puedan hacer peticiones en nombre del usuario
            'X-CSRF-TOKEN': obtenerCsrfToken()
        },
        body: JSON.stringify({ evento_id: eventoId })
    })
    .then(function(response) {
        // HTTP 401 Unauthorized: la sesión expiró en el servidor aunque el cliente
        // creyera estar autenticado. Redirigimos al login para que vuelva a identificarse.
        if (response.status === 401) {
            window.location.href = LOGIN_URL;
            return null;
        }

        if (!response.ok) {
            throw new Error('No se pudo actualizar favoritos');
        }

        return response.json();
    })
    .then(function(data) {
        if (!data) {
            return;
        }

        // El servidor aplica el patrón soft toggle: devuelve el nuevo valor del campo
        // `favorito` (0 ó 1) sin borrar el registro. Nosotros convertimos ese valor
        // a booleano y sincronizamos todos los botones de este evento en la página.
        sincronizarBotonesFavorito(eventoId, Boolean(data.favorito));
    })
    .catch(function(error) {
        console.error(error);
        alert('No se pudo actualizar favoritos. Intenta de nuevo.');
    })
    .finally(function() {
        // Liberamos la guarda de carga siempre, tanto si la petición tuvo éxito como si falló
        boton.dataset.loading = '0';
        boton.classList.remove('cargando');
    });
}

/**
 * Actualiza el estado visual del botón de favorito en la página de detalle del evento.
 * Además de actualizar el icono, también cambia el texto descriptivo junto al corazón.
 *
 * @param {HTMLElement} btn        - El botón de favorito en la página de detalle
 * @param {boolean}     esFavorito - Nuevo estado del favorito
 */
function actualizarBotonFavoritoDetalle(btn, esFavorito) {
    btn.dataset.favorito = esFavorito ? '1' : '0';
    btn.classList.toggle('activo', esFavorito);
    btn.setAttribute('aria-pressed', esFavorito ? 'true' : 'false');

    // Actualizamos también el texto visible junto al icono (si existe en el HTML)
    var texto = document.getElementById('btn-favorito-detalle-texto');
    if (texto) {
        texto.textContent = esFavorito ? 'En favoritos' : 'Guardar en favoritos';
    }
}

/**
 * Alterna el estado de favorito desde la página de detalle de un evento.
 * Versión más defensiva que toggleFavorito: incluye más comprobaciones y logs
 * porque esta página es más crítica (el usuario está viendo el evento en detalle).
 *
 * @param {HTMLElement} btn - El botón de favorito de la página de detalle
 */
function toggleFavoritoDetalle(btn) {
    console.log('toggleFavoritoDetalle llamada con btn:', btn);

    if (!btn) {
        console.error('toggleFavoritoDetalle: btn es null o undefined');
        return;
    }

    // Reutilizamos la misma guarda de doble clic que en toggleFavorito
    if (btn.dataset.loading === '1') {
        console.log('toggleFavoritoDetalle: ya está cargando, ignorando');
        return;
    }

    var eventoId = parseInt(btn.dataset.eventoId, 10);
    console.log('Evento ID extraído:', eventoId, 'Atributo:', btn.dataset.eventoId);

    if (isNaN(eventoId) || eventoId <= 0) {
        console.error('toggleFavoritoDetalle: evento_id inválido', eventoId);
        alert('Error: No se pudo determinar el ID del evento.');
        return;
    }

    btn.dataset.loading = '1';
    btn.classList.add('cargando');

    var csrfToken = obtenerCsrfToken();
    // Verificamos explícitamente que el meta tag existe porque sin CSRF el servidor
    // rechazará la petición con un error 419 (CSRF token mismatch)
    if (!csrfToken) {
        console.error('toggleFavoritoDetalle: token CSRF no encontrado');
        btn.dataset.loading = '0';
        btn.classList.remove('cargando');
        alert('Error: Token CSRF no disponible.');
        return;
    }

    console.log('Iniciando fetch a /api/favoritos/toggle con evento_id:', eventoId);

    fetch('/api/favoritos/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ evento_id: eventoId })
    })
    .then(function(response) {
        console.log('Respuesta recibida, status:', response.status);

        // 401 Unauthorized: la sesión expiró; redirigimos al login
        if (response.status === 401) {
            console.log('Usuario no autenticado, redirigiendo a login');
            window.location.href = LOGIN_URL;
            return null;
        }

        if (!response.ok) {
            throw new Error('HTTP error! status: ' + response.status);
        }

        return response.json();
    })
    .then(function(data) {
        console.log('Datos recibidos:', data);

        if (!data) {
            console.log('No hay datos en respuesta');
            return;
        }

        // El servidor confirma el nuevo estado (soft toggle) y actualizamos la UI
        actualizarBotonFavoritoDetalle(btn, Boolean(data.favorito));
        console.log('Botón actualizado, favorito:', data.favorito);
    })
    .catch(function(error) {
        console.error('Error en toggleFavoritoDetalle:', error);
        alert('No se pudo actualizar favoritos. Intenta de nuevo.\n\nError: ' + error.message);
    })
    .finally(function() {
        // Liberamos la guarda siempre para que el botón vuelva a ser usable
        btn.dataset.loading = '0';
        btn.classList.remove('cargando');
        console.log('toggleFavoritoDetalle finalizado');
    });
}

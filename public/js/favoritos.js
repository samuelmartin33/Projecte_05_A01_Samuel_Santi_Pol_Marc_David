var FAVORITOS_CFG = window.vibezFavoritosConfig || {};
var USER_AUTHENTICATED = Boolean(FAVORITOS_CFG.userAuthenticated);
var LOGIN_URL = FAVORITOS_CFG.loginUrl || '/login';

function actualizarBotonFavorito(btn, esFavorito) {
    btn.dataset.favorito = esFavorito ? '1' : '0';
    btn.classList.toggle('activo', esFavorito);
    btn.setAttribute('aria-pressed', esFavorito ? 'true' : 'false');
}

function sincronizarBotonesFavorito(eventoId, esFavorito) {
    var selector = '[data-evento-id="' + eventoId + '"]';
    document.querySelectorAll(selector).forEach(function(btn) {
        actualizarBotonFavorito(btn, esFavorito);
    });
}

function crearBotonFavorito(eventoId, esFavorito) {
    if (!USER_AUTHENTICATED) {
        return '';
    }

    return '<button type="button" class="btn-favorito-card' + (esFavorito ? ' activo' : '') + '"'
        + ' data-evento-id="' + eventoId + '"'
        + ' data-favorito="' + (esFavorito ? '1' : '0') + '"'
        + ' aria-label="Marcar favorito"'
        + ' aria-pressed="' + (esFavorito ? 'true' : 'false') + '"'
        + ' onclick="toggleFavorito(event, this)">'
        + '<svg viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">'
        + '<path d="M8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-1.066 2.08-.163 4.905 2.54 7.568L8 15l4.06-4.379c2.703-2.663 3.606-5.488 2.54-7.568-1.114-2.175-4.2-2.772-5.883-1.042L8 2.748z"/>'
        + '</svg>'
        + '</button>';
}

function toggleFavorito(event, boton) {
    event.stopPropagation();

    if (!USER_AUTHENTICATED) {
        window.location.href = LOGIN_URL;
        return;
    }

    if (boton.dataset.loading === '1') {
        return;
    }

    var eventoId = parseInt(boton.dataset.eventoId, 10);
    if (!eventoId) {
        return;
    }

    boton.dataset.loading = '1';
    boton.classList.add('cargando');

    fetch('/api/favoritos/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ evento_id: eventoId })
    })
    .then(function(response) {
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

        sincronizarBotonesFavorito(eventoId, Boolean(data.favorito));
    })
    .catch(function(error) {
        console.error(error);
        alert('No se pudo actualizar favoritos. Intenta de nuevo.');
    })
    .finally(function() {
        boton.dataset.loading = '0';
        boton.classList.remove('cargando');
    });
}

/**
 * Actualizar el estado visual del botón de favoritos en la página de detalle
 */
function actualizarBotonFavoritoDetalle(btn, esFavorito) {
    btn.dataset.favorito = esFavorito ? '1' : '0';
    btn.classList.toggle('activo', esFavorito);
    btn.setAttribute('aria-pressed', esFavorito ? 'true' : 'false');

    var texto = document.getElementById('btn-favorito-detalle-texto');
    if (texto) {
        texto.textContent = esFavorito ? 'En favoritos' : 'Guardar en favoritos';
    }
}

/**
 * Toggle favorito desde la página de detalle de evento
 */
function toggleFavoritoDetalle(btn) {
    console.log('toggleFavoritoDetalle llamada con btn:', btn);
    
    if (!btn) {
        console.error('toggleFavoritoDetalle: btn es null o undefined');
        return;
    }

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

    var csrfToken = document.querySelector('meta[name="csrf-token"]');
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
            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
        },
        body: JSON.stringify({ evento_id: eventoId })
    })
    .then(function(response) {
        console.log('Respuesta recibida, status:', response.status);
        
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

        actualizarBotonFavoritoDetalle(btn, Boolean(data.favorito));
        console.log('Botón actualizado, favorito:', data.favorito);
    })
    .catch(function(error) {
        console.error('Error en toggleFavoritoDetalle:', error);
        alert('No se pudo actualizar favoritos. Intenta de nuevo.\n\nError: ' + error.message);
    })
    .finally(function() {
        btn.dataset.loading = '0';
        btn.classList.remove('cargando');
        console.log('toggleFavoritoDetalle finalizado');
    });
}

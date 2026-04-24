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
        + '<svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none">'
        + '<path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-6.716-4.35-9.243-8.242C.71 9.65 2.503 5.25 6.375 5.25c2.106 0 3.14 1.115 3.812 2.19.672-1.075 1.706-2.19 3.813-2.19 3.872 0 5.664 4.4 3.617 7.508C18.716 16.65 12 21 12 21z"/>'
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

// Comportamiento dinámico de la página principal de VIBEZ.
var HOME_CFG = window.vibezHomeConfig || {};

function toggleDropdown(id) {
    var dropdown = document.getElementById(id + '-dropdown');
    var wrapper  = document.getElementById('wrapper-' + id);
    var overlay  = document.getElementById('overlay-dropdowns');
    var estaAbierto = dropdown.style.display === 'block';

    cerrarTodosDropdowns();

    if (!estaAbierto) {
        dropdown.style.display = 'block';
        wrapper.classList.add('abierto');
        overlay.style.display = 'block';
    }
}

function cerrarTodosDropdowns() {
    ['categoria', 'ubicacion'].forEach(function(id) {
        var d = document.getElementById(id + '-dropdown');
        var w = document.getElementById('wrapper-' + id);
        if (d) d.style.display = 'none';
        if (w) w.classList.remove('abierto');
    });
    var overlay = document.getElementById('overlay-dropdowns');
    if (overlay) overlay.style.display = 'none';
}

function seleccionarFiltro(filtroId, valor, texto, event) {
    event.stopPropagation();

    var inputHidden = document.getElementById('filtro-' + filtroId);
    var display = document.getElementById(filtroId + '-display');
    if (inputHidden) inputHidden.value = valor;
    if (display) display.textContent = texto;

    var dropdown = document.getElementById(filtroId + '-dropdown');
    if (dropdown) {
        dropdown.querySelectorAll('.custom-select-option').forEach(function(op) {
            op.classList.remove('seleccionado');
        });
        if (event.target && event.target.classList) {
            event.target.classList.add('seleccionado');
        }
    }

    cerrarTodosDropdowns();

    /* Sincronizar mood chips si el filtro cambiado es de categoría */
    if (filtroId === 'categoria') {
        document.querySelectorAll('.mood-chip').forEach(function(c) {
            c.classList.remove('activo');
        });
        /* Activar el chip que coincida con el valor seleccionado */
        var chipActivo = valor === ''
            ? document.getElementById('mood-chip-todos')
            : document.querySelector('.mood-chip[onclick*="' + valor + '"]');
        if (chipActivo) chipActivo.classList.add('activo');
    }

    aplicarFiltros();
}

function aplicarFiltros() {
    var categoria = document.getElementById('filtro-categoria').value;
    var ubicacion = document.getElementById('filtro-ubicacion').value;
    var favoritos = document.getElementById('filtro-favoritos').value;

    document.getElementById('cargando').classList.remove('hidden');
    document.getElementById('grid-resultados').classList.add('hidden');
    document.getElementById('sin-resultados').classList.add('hidden');

    var seccionEventos = document.getElementById('seccion-eventos');
    if (seccionEventos) seccionEventos.style.display = 'none';

    fetch('/api/filtrar?categoria=' + encodeURIComponent(categoria) + '&ubicacion=' + encodeURIComponent(ubicacion) + '&favoritos=' + encodeURIComponent(favoritos))
        .then(function(respuesta) { return respuesta.json(); })
        .then(function(datos) {

            document.getElementById('contador-resultados').textContent = datos.eventos.length;
            document.getElementById('cargando').classList.add('hidden');

            if (datos.eventos.length === 0) {
                document.getElementById('sin-resultados').classList.remove('hidden');
                return;
            }

            var htmlGrid = '';
            datos.eventos.forEach(function(evento) {
                htmlGrid += crearTarjetaEvento(evento);
            });

            document.getElementById('grid-resultados-inner').innerHTML = htmlGrid;
            document.getElementById('grid-resultados').classList.remove('hidden');
        })
        .catch(function(error) {
            console.error('Error al filtrar:', error);
            document.getElementById('cargando').classList.add('hidden');
            if (seccionEventos) seccionEventos.style.display = '';
        });
}

function limpiarFiltros() {
    document.getElementById('filtro-categoria').value = '';
    document.getElementById('filtro-ubicacion').value = '';
    document.getElementById('filtro-favoritos').value = '0';
    document.getElementById('btn-solo-favoritos').classList.remove('activo');

    document.getElementById('categoria-display').textContent = 'Todas';
    document.getElementById('ubicacion-display').textContent = 'Todas las ciudades';

    document.querySelectorAll('.custom-select-dropdown .custom-select-option').forEach(function(op) {
        op.classList.remove('seleccionado');
    });
    ['categoria-dropdown', 'ubicacion-dropdown'].forEach(function(id) {
        var primera = document.querySelector('#' + id + ' .custom-select-option');
        if (primera) primera.classList.add('seleccionado');
    });

    document.getElementById('grid-resultados').classList.add('hidden');
    document.getElementById('sin-resultados').classList.add('hidden');

    var seccionEventos = document.getElementById('seccion-eventos');
    if (seccionEventos) seccionEventos.style.display = '';

    document.getElementById('contador-resultados').textContent = Number(HOME_CFG.totalEventos || 0);

    /* Resetear también los mood chips */
    document.querySelectorAll('.mood-chip').forEach(function(c) {
        c.classList.remove('activo');
    });
    var chipTodos = document.getElementById('mood-chip-todos');
    if (chipTodos) chipTodos.classList.add('activo');
}

function toggleSoloFavoritos() {
    var cfg = window.vibezFavoritosConfig || {};
    if (!cfg.userAuthenticated) {
        window.location.href = cfg.loginUrl || '/login';
        return;
    }

    var btn   = document.getElementById('btn-solo-favoritos');
    var input = document.getElementById('filtro-favoritos');

    if (input.value === '1') {
        input.value = '0';
        btn.classList.remove('activo');
    } else {
        input.value = '1';
        btn.classList.add('activo');
    }

    aplicarFiltros();
}

function irADetalle(tipo, id) {
    window.location.href = tipo === 'evento' ? '/eventos/' + id : '/trabajos/' + id;
}

function crearTarjetaEvento(evento) {
    var fecha = new Date(evento.fecha_inicio).toLocaleDateString('es-ES', {
        day: 'numeric', month: 'short', year: 'numeric'
    });
    var imagen = evento.portada || ('https://picsum.photos/seed/evento-' + evento.id + '/600/400');
    var ubicacionHtml = evento.ubicacion_nombre
        ? '<p class="card-meta"><svg class="icono-meta" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>' + evento.ubicacion_nombre + '</p>'
        : '';

    return '<article class="card-evento" onclick="irADetalle(\'evento\',' + evento.id + ')">'
        + '<div class="card-imagen-wrap">'
        + crearBotonFavorito(evento.id, Boolean(evento.is_favorito))
        + '<img src="' + imagen + '" alt="' + evento.titulo + '" class="card-imagen" onerror="this.src=\'https://picsum.photos/seed/fallback-' + evento.id + '/600/400\'">'
        + '<span class="badge-categoria" data-cat="' + evento.categoria + '">' + evento.categoria + '</span>'
        + '<span class="badge-precio ' + (evento.es_gratuito ? 'badge-gratis' : '') + '">' + evento.precio_formateado + '</span>'
        + '</div>'
        + '<div class="card-cuerpo">'
        + '<h3 class="card-titulo">' + evento.titulo + '</h3>'
        + '<p class="card-meta"><svg class="icono-meta" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>' + fecha + '</p>'
        + ubicacionHtml
        + '<p class="card-organizador">' + evento.organizador + '</p>'
        + '</div></article>';
}

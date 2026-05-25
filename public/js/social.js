/**
 * VIBEZ Social — lógica principal
 * Layout tipo Instagram: feed por defecto, bottom nav, chats y amigos como paneles.
 */

/* ============================================================
   VARIABLES DE ESTADO
   ============================================================ */

var chatActualId    = null;
var amigoActualId   = null;
var ultimoMensajeId = 0;
var intervaloPolling = null;
var panelActual     = 'feed';

var pubPagina           = 1;
var pubHayMas           = false;
var pubCargando         = false;
var pubEventosAsistidos = [];

var carouselState = {}; // { postId: currentIndex }
var likeEnProceso = {};

/* ── Helper: modal de aviso VIBEZ (sustituye alert nativo) ── */
function vibezAlerta(titulo, texto, tipo) {
    Swal.fire({
        title:              titulo || 'Aviso',
        text:               texto  || '',
        icon:               tipo   || 'error',
        background:         '#0d0820',
        color:              '#f5f1ea',
        confirmButtonColor: '#7c3aed',
        confirmButtonText:  'Entendido',
    });
}

/* ── Extrae el primer mensaje de error legible de la respuesta del servidor ── */
function parsearErrorServidor(respuesta, porDefecto) {
    if (respuesta.mensaje) return respuesta.mensaje;
    if (respuesta.errors) {
        var primero = Object.values(respuesta.errors)[0];
        return Array.isArray(primero) ? primero[0] : String(primero);
    }
    if (respuesta.message) return respuesta.message;
    return porDefecto || 'Error desconocido.';
}

var temporizadorBusqueda = null;

var historialHistorias  = [];  // grupos de historias cargados [{ usuario, historias }]
var visorGrupoIdx       = 0;   // índice del grupo actual en el visor
var visorHistoriaIdx    = 0;   // índice de la historia dentro del grupo
var visorTimer          = null; // setTimeout del avance automático
var eventoFiltroActual  = null; // id del evento actualmente filtrado

var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

/* ============================================================
   INICIALIZACIÓN
   ============================================================ */

window.onload = function () {
    irA('feed');
    actualizarContadorNavbar();
    setInterval(actualizarContadorNavbar, 30000);
};

/* ============================================================
   NAVEGACIÓN — bottom nav
   ============================================================ */

function irA(panel) {
    // Detener polling si salimos de chats
    if (panelActual === 'chats' && panel !== 'chats') {
        detenerPolling();
    }

    // Desactivar todos los paneles y botones
    document.querySelectorAll('.soc-panel').forEach(function (el) {
        el.classList.remove('activo');
    });
    document.querySelectorAll('.soc-nav-btn').forEach(function (el) {
        el.classList.remove('activo');
    });

    // Activar el panel y botón correspondiente
    document.getElementById('panel-' + panel).classList.add('activo');
    document.getElementById('nav-btn-' + panel).classList.add('activo');

    panelActual = panel;

    // Cargar datos del panel
    if (panel === 'chats') {
        cargarChats();
    } else if (panel === 'amigos') {
        cargarAmigos();
        cargarSolicitudes();
    } else if (panel === 'feed') {
        iniciarFeed();
    } else if (panel === 'eventos') {
        cargarEventosConContenido();
    }
}

/* ============================================================
   FEED DE PUBLICACIONES
   ============================================================ */

function iniciarFeed() {
    pubPagina   = 1;
    pubHayMas   = false;
    pubCargando = false;
    document.getElementById('feed-lista').innerHTML           = '';
    document.getElementById('feed-cargar-mas').style.display = 'none';
    document.getElementById('feed-vacio').style.display      = 'none';

    cargarEventosAsistidos();
    cargarFeedPosts(1);
    cargarHistorias();
}

function cargarEventosAsistidos() {
    fetch('/api/social/mis-eventos-asistidos', {
        headers: { 'Accept': 'application/json' }
    })
    .then(function (res) { return res.json(); })
    .then(function (respuesta) {
        pubEventosAsistidos = respuesta.datos || [];

        // Los botones se muestran siempre (el post no requiere evento)
        var acciones = document.getElementById('topbar-acciones-feed');
        if (acciones) {
            acciones.style.display = 'flex';
        }

        // Rellenar select del modal de publicación
        var selectModal = document.getElementById('pub-select-evento');
        if (selectModal) {
            var optsPost = '<option value="">Sin etiqueta</option>';
            pubEventosAsistidos.forEach(function (ev) {
                optsPost += '<option value="' + ev.id + '">' + escaparHtml(ev.titulo) + '</option>';
            });
            selectModal.innerHTML = optsPost;
        }

        // Rellenar select del modal de historia
        var selectHist = document.getElementById('hist-select-evento');
        if (selectHist) {
            var optsHist = '<option value="">Sin etiqueta</option>';
            pubEventosAsistidos.forEach(function (ev) {
                optsHist += '<option value="' + ev.id + '">' + escaparHtml(ev.titulo) + '</option>';
            });
            selectHist.innerHTML = optsHist;
        }
    })
    .catch(function () {});
}

function cargarFeedPosts(pagina) {
    if (pubCargando) return;
    pubCargando = true;

    var cargandoEl = document.getElementById('feed-cargando');
    cargandoEl.style.display = 'flex';
    document.getElementById('feed-vacio').style.display = 'none';

    fetch('/api/social/posts?pagina=' + pagina, {
        headers: { 'Accept': 'application/json' }
    })
    .then(function (res) { return res.json(); })
    .then(function (respuesta) {
        cargandoEl.style.display = 'none';
        pubCargando = false;

        if (!respuesta.exito || respuesta.datos.length === 0) {
            if (pagina === 1) {
                document.getElementById('feed-vacio').style.display = 'block';
            }
            document.getElementById('feed-cargar-mas').style.display = 'none';
            return;
        }

        var lista = document.getElementById('feed-lista');
        respuesta.datos.forEach(function (post) {
            lista.insertAdjacentHTML('beforeend', renderizarPost(post));
            if (post.imagenes && post.imagenes.length > 1) {
                inicializarTouchCarrusel(post.id);
            }
        });

        pubPagina = pagina;
        pubHayMas = respuesta.meta.hay_mas;
        document.getElementById('feed-cargar-mas').style.display = pubHayMas ? 'block' : 'none';
    })
    .catch(function () {
        document.getElementById('feed-cargando').style.display = 'none';
        pubCargando = false;
    });
}

function cargarMasPosts() {
    if (!pubHayMas || pubCargando) return;
    cargarFeedPosts(pubPagina + 1);
}

/* ── Renderizado de posts (estilo Instagram) ── */

function renderizarPost(post) {
    var avatarHtml    = construirAvatar(post.autor.foto_url, post.autor.nombre, post.autor.apellido1, 'sm');
    var autorNombre   = escaparHtml(post.autor.nombre + ' ' + post.autor.apellido1);
    var eventoLabel   = post.evento ? escaparHtml(post.evento.titulo) : '';
    var eventoTagHtml = post.evento
        ? '<span class="post-evento-tag" onclick="irAFiltroEvento(' + post.evento.id + ')">🎫 ' + eventoLabel + '</span>'
        : '';
    var fechaLabel    = formatearFechaRelativa(post.fecha);
    var visiBadge     = post.visibilidad === 2
        ? '<span style="font-size:0.6rem;color:rgba(245,241,234,0.35);margin-left:4px;" title="Solo amigos">🔒</span>'
        : '';

    // Imágenes (imagen única o carrusel)
    var imagenesHtml = renderizarImagenes(post);

    // Descripción (con nombre del autor en negrita, como Instagram)
    var capcionHtml = '';
    if (post.descripcion) {
        capcionHtml = '<p class="post-caption"><strong>' + autorNombre + '</strong>'
                    + escaparHtml(post.descripcion).replace(/\n/g, '<br>') + '</p>';
    }

    // Comentarios preview
    var comentariosHtml = '';
    if (post.comentarios_preview && post.comentarios_preview.length > 0) {
        post.comentarios_preview.forEach(function (c) {
            comentariosHtml += renderizarComentario(c);
        });
    }

    // "Ver todos"
    var verMasHtml = '';
    if (post.total_comentarios > post.comentarios_preview.length) {
        verMasHtml = '<button class="post-ver-mas-comentarios" onclick="cargarTodosComentarios(' + post.id + ')">'
                   + 'Ver los ' + post.total_comentarios + ' comentarios'
                   + '</button>';
    }

    // Input mi avatar + comentario
    var miAvatar = construirAvatar(null, 'Yo', '', 'sm');

    // Likes
    var likeClass   = post.yo_di_like ? ' liked' : '';
    var likeCountTx = post.total_likes > 0 ? post.total_likes + (post.total_likes === 1 ? ' Me gusta' : ' Me gusta') : '';
    var svgOutline  = '<svg class="like-icon-outline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>';
    var svgFilled   = '<svg class="like-icon-filled" viewBox="0 0 24 24" fill="currentColor"><path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"/></svg>';
    var likesHtml   = '<div class="post-likes">'
                    +   '<button class="post-like-btn' + likeClass + '" id="like-btn-' + post.id + '" onclick="toggleLike(' + post.id + ')" aria-label="Me gusta">'
                    +     svgOutline + svgFilled
                    +   '</button>'
                    +   '<span class="post-like-count" id="like-count-' + post.id + '">' + likeCountTx + '</span>'
                    + '</div>';

    return '<article class="post-card" id="pub-post-' + post.id + '" data-post-id="' + post.id + '">'
         +   '<div class="post-head">'
         +     '<div class="post-head-left">'
         +       '<div class="post-avatar">' + avatarHtml + '</div>'
         +       '<div class="post-head-meta">'
         +         '<span class="post-autor">' + autorNombre + visiBadge + '</span>'
         +         eventoTagHtml
         +       '</div>'
         +     '</div>'
         +   '</div>'
         +   imagenesHtml
         +   likesHtml
         +   '<div class="post-body">'
         +     capcionHtml
         +     '<div class="post-comments" id="pub-comentarios-' + post.id + '">'
         +       comentariosHtml
         +     '</div>'
         +     verMasHtml
         +     '<div class="post-add-comment">'
         +       '<div>' + miAvatar + '</div>'
         +       '<input type="text" class="post-comment-input" id="pub-com-input-' + post.id + '"'
         +         ' placeholder="Añade un comentario…" maxlength="500"'
         +         ' onkeydown="manejarTeclaComentario(event,' + post.id + ')">'
         +       '<button class="post-btn-comentar" onclick="enviarComentario(' + post.id + ')">Publicar</button>'
         +     '</div>'
         +   '</div>'
         + '</article>';
}

/* ── Renderizado de imágenes: imagen única o carrusel ── */

function renderizarImagenes(post) {
    if (!post.imagenes || post.imagenes.length === 0) return '';

    if (post.imagenes.length === 1) {
        return '<div class="post-img-single">'
             + '<div class="post-img-wrap" onclick="abrirLightbox(\'' + escaparTexto(post.imagenes[0].url) + '\')">'
             + '<img src="' + escaparHtml(post.imagenes[0].url) + '" alt="Foto del evento" loading="lazy">'
             + '</div></div>';
    }

    // Carrusel para 2+ imágenes
    carouselState[post.id] = 0;

    var svgPrev = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">'
                + '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>';
    var svgNext = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">'
                + '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>';

    var slides = '';
    var dots   = '';
    post.imagenes.forEach(function (img, idx) {
        slides += '<div class="post-carousel-slide">'
               + '<img src="' + escaparHtml(img.url) + '" alt="Foto del evento" loading="lazy"'
               + ' onclick="abrirLightbox(\'' + escaparTexto(img.url) + '\')">'
               + '</div>';
        dots += '<span class="post-carousel-dot' + (idx === 0 ? ' activo' : '') + '"'
              + ' onclick="irASlide(' + post.id + ',' + idx + ')"></span>';
    });

    return '<div class="post-carousel" id="carousel-' + post.id + '">'
         + '<div class="post-carousel-track" id="carousel-track-' + post.id + '">' + slides + '</div>'
         + '<button class="post-carousel-btn post-carousel-prev" style="display:none"'
         +   ' onclick="carouselPrev(' + post.id + ')" aria-label="Anterior">' + svgPrev + '</button>'
         + '<button class="post-carousel-btn post-carousel-next"'
         +   ' onclick="carouselNext(' + post.id + ')" aria-label="Siguiente">' + svgNext + '</button>'
         + '<div class="post-carousel-dots" id="carousel-dots-' + post.id + '">' + dots + '</div>'
         + '</div>';
}

/* ── Navegación del carrusel ── */

function irASlide(postId, idx) {
    var track = document.getElementById('carousel-track-' + postId);
    if (!track) return;
    var total = track.children.length;
    idx = Math.max(0, Math.min(idx, total - 1));

    track.style.transform = 'translateX(-' + (idx * 100) + '%)';
    carouselState[postId] = idx;

    // Actualizar dots
    var dotsEl = document.getElementById('carousel-dots-' + postId);
    if (dotsEl) {
        dotsEl.querySelectorAll('.post-carousel-dot').forEach(function (d, i) {
            d.classList.toggle('activo', i === idx);
        });
    }

    // Mostrar/ocultar botones
    var carousel = document.getElementById('carousel-' + postId);
    if (carousel) {
        var btnPrev = carousel.querySelector('.post-carousel-prev');
        var btnNext = carousel.querySelector('.post-carousel-next');
        if (btnPrev) btnPrev.style.display = idx === 0         ? 'none' : 'flex';
        if (btnNext) btnNext.style.display = idx === total - 1 ? 'none' : 'flex';
    }
}

function carouselPrev(postId) {
    var idx = carouselState[postId];
    if (idx === undefined || idx === 0) return;
    irASlide(postId, idx - 1);
}

function carouselNext(postId) {
    var track = document.getElementById('carousel-track-' + postId);
    if (!track) return;
    var idx = carouselState[postId] || 0;
    if (idx >= track.children.length - 1) return;
    irASlide(postId, idx + 1);
}

/* ── Touch / drag en el carrusel ── */

function inicializarTouchCarrusel(postId) {
    var carousel = document.getElementById('carousel-' + postId);
    if (!carousel) return;

    var startX   = 0;
    var activo   = false;

    function onStart(x) { startX = x; activo = true; }
    function onEnd(x) {
        if (!activo) return;
        activo = false;
        var diff = startX - x;
        if (Math.abs(diff) > 45) {
            if (diff > 0) carouselNext(postId);
            else          carouselPrev(postId);
        }
    }

    // Touch (móvil)
    carousel.addEventListener('touchstart', function (e) { onStart(e.touches[0].clientX); },           { passive: true });
    carousel.addEventListener('touchend',   function (e) { onEnd(e.changedTouches[0].clientX); },      { passive: true });

    // Mouse drag (desktop)
    carousel.addEventListener('mousedown',  function (e) { onStart(e.clientX); });
    carousel.addEventListener('mouseup',    function (e) { onEnd(e.clientX); });
    carousel.addEventListener('mouseleave', function ()  { activo = false; });
}

function toggleLike(postId) {
    if (likeEnProceso[postId]) return;
    likeEnProceso[postId] = true;

    var btn = document.getElementById('like-btn-' + postId);
    if (btn) btn.classList.add('cargando');

    fetch('/api/social/posts/' + postId + '/like', {
        method:  'POST',
        headers: {
            'Accept':       'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
    })
    .then(function (res) { return res.json(); })
    .then(function (respuesta) {
        if (!respuesta.exito) return;
        var boton  = document.getElementById('like-btn-' + postId);
        var cuenta = document.getElementById('like-count-' + postId);
        if (boton)  boton.classList.toggle('liked', respuesta.liked);
        if (cuenta) cuenta.textContent = respuesta.total_likes > 0
            ? respuesta.total_likes + ' Me gusta'
            : '';
    })
    .catch(function () {})
    .finally(function () {
        var boton = document.getElementById('like-btn-' + postId);
        if (boton) boton.classList.remove('cargando');
        delete likeEnProceso[postId];
    });
}

function renderizarComentario(c) {
    var autor = escaparHtml(c.autor.nombre + ' ' + c.autor.apellido1);
    var hora  = formatearHora(c.fecha);

    // Renderizar respuestas ya existentes
    var respuestasHtml = '';
    if (c.respuestas && c.respuestas.length > 0) {
        c.respuestas.forEach(function (r) {
            var rAutor = escaparHtml(r.autor.nombre + ' ' + r.autor.apellido1);
            respuestasHtml += '<div class="post-comment-reply">'
                + '<p><strong>' + rAutor + '</strong> ' + escaparHtml(r.contenido) + '</p>'
                + '<p class="post-comment-time">' + formatearHora(r.fecha) + '</p>'
                + '</div>';
        });
    }

    return '<div class="post-comment" id="pub-com-' + c.id + '">'
         +   '<div class="post-comment-body">'
         +     '<p><strong>' + autor + '</strong> ' + escaparHtml(c.contenido) + '</p>'
         +     '<div style="display:flex;align-items:center;gap:4px;">'
         +       '<p class="post-comment-time">' + hora + '</p>'
         +       '<button class="post-comment-responder"'
         +         ' onclick="mostrarInputRespuesta(' + c.id + ')">Responder</button>'
         +     '</div>'
         +   '</div>'
         +   '<div class="post-comment-replies" id="replies-' + c.id + '">' + respuestasHtml + '</div>'
         +   '<div id="reply-input-wrap-' + c.id + '" style="display:none">'
         +     '<div class="post-reply-input-wrap">'
         +       '<input type="text" class="post-reply-input" id="reply-input-' + c.id + '"'
         +         ' placeholder="Responde a ' + autor + '…" maxlength="500"'
         +         ' onkeydown="manejarTeclaRespuesta(event,' + c.id + ')">'
         +       '<button class="post-btn-comentar"'
         +         ' onclick="enviarRespuesta(' + c.id + ')">OK</button>'
         +     '</div>'
         +   '</div>'
         + '</div>';
}

function enviarComentario(postId) {
    var inputEl   = document.getElementById('pub-com-input-' + postId);
    var contenido = inputEl.value.trim();
    if (!contenido) return;

    inputEl.disabled = true;

    fetch('/api/social/posts/' + postId + '/comentarios', {
        method:  'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept':       'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({ contenido: contenido }),
    })
    .then(function (res) { return res.json(); })
    .then(function (respuesta) {
        inputEl.disabled = false;
        inputEl.value    = '';
        if (respuesta.exito) {
            var seccion = document.getElementById('pub-comentarios-' + postId);
            seccion.insertAdjacentHTML('beforeend', renderizarComentario(respuesta.datos));
        }
    })
    .catch(function () { inputEl.disabled = false; });
}

function manejarTeclaComentario(event, postId) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        enviarComentario(postId);
    }
}

function cargarTodosComentarios(postId) {
    var btn = document.querySelector('#pub-post-' + postId + ' .post-ver-mas-comentarios');
    if (btn) btn.style.display = 'none';

    fetch('/api/social/posts/' + postId + '/comentarios', {
        headers: { 'Accept': 'application/json' }
    })
    .then(function (res) { return res.json(); })
    .then(function (respuesta) {
        if (!respuesta.exito) return;
        var seccion = document.getElementById('pub-comentarios-' + postId);
        seccion.innerHTML = '';
        respuesta.datos.forEach(function (c) {
            seccion.insertAdjacentHTML('beforeend', renderizarComentario(c));
        });
    });
}

/* ── Modal: nueva publicación ── */

function abrirModalPublicacion() {
    document.getElementById('pub-modal-overlay').style.display    = 'flex';
    document.getElementById('pub-select-evento').value            = '';
    document.getElementById('pub-textarea-desc').value            = '';
    document.getElementById('pub-select-visibilidad').value       = '1';
    document.getElementById('pub-input-fotos').value              = '';
    document.getElementById('pub-preview-grid').innerHTML         = '';
    var label = document.getElementById('pub-upload-label');
    if (label) label.textContent = 'Haz clic o arrastra tus fotos aquí';
    var area  = document.getElementById('pub-upload-area');
    if (area) area.classList.remove('con-fotos');
    var btn = document.getElementById('pub-btn-publicar');
    btn.disabled    = false;
    btn.textContent = 'Publicar';
}

function cerrarModalPublicacion() {
    document.getElementById('pub-modal-overlay').style.display = 'none';
}

function previsualizarFotos(input) {
    var grid   = document.getElementById('pub-preview-grid');
    var label  = document.getElementById('pub-upload-label');
    var area   = document.getElementById('pub-upload-area');
    grid.innerHTML = '';

    var archivos = Array.from(input.files).slice(0, 10);

    if (archivos.length === 0) {
        if (label) label.textContent = 'Haz clic o arrastra tus fotos aquí';
        if (area) area.classList.remove('con-fotos');
        return;
    }

    // Actualizar texto del área con el conteo
    if (label) label.textContent = archivos.length + (archivos.length === 1 ? ' foto seleccionada' : ' fotos seleccionadas');
    if (area) area.classList.add('con-fotos');

    archivos.forEach(function (file) {
        var reader = new FileReader();
        reader.onload = function (e) {
            grid.insertAdjacentHTML('beforeend',
                '<div class="soc-preview-thumb"><img src="' + e.target.result + '" alt="preview"></div>'
            );
        };
        reader.readAsDataURL(file);
    });
}

// Gestiona el drop de archivos sobre el área de upload
function soltar(event) {
    event.preventDefault();
    var area  = document.getElementById('pub-upload-area');
    var input = document.getElementById('pub-input-fotos');
    area.classList.remove('dragover');

    if (event.dataTransfer && event.dataTransfer.files.length > 0) {
        // Asignar los archivos al input para que publicarPost() los lea
        try {
            // DataTransfer permite reemplazar la propiedad files del input
            var dt = new DataTransfer();
            Array.from(event.dataTransfer.files).slice(0, 10).forEach(function (f) {
                if (f.type.match(/^image\//)) dt.items.add(f);
            });
            input.files = dt.files;
            previsualizarFotos(input);
        } catch (e) {
            // Fallback para navegadores sin soporte DataTransfer
            vibezAlerta('Navegador no compatible', 'Usa el botón para seleccionar las fotos.', 'info');
        }
    }
}

function publicarPost() {
    var eventoId     = document.getElementById('pub-select-evento').value;
    var descripcion  = document.getElementById('pub-textarea-desc').value.trim();
    var visibilidad  = document.getElementById('pub-select-visibilidad').value || '1';
    var inputFotos   = document.getElementById('pub-input-fotos');

    if (!inputFotos.files || inputFotos.files.length === 0) {
        vibezAlerta('Falta la foto', 'Añade al menos una foto para publicar.', 'warning');
        return;
    }

    /* Validación de tamaño antes de enviar (límite servidor: 20MB por archivo) */
    var archivosGrandes = Array.from(inputFotos.files).filter(function (f) { return f.size > 20 * 1024 * 1024; });
    if (archivosGrandes.length > 0) {
        vibezAlerta('Foto demasiado grande', 'Cada imagen debe pesar menos de 20MB.', 'warning');
        return;
    }

    var btn         = document.getElementById('pub-btn-publicar');
    btn.disabled    = true;
    btn.textContent = 'Publicando…';

    var formData = new FormData();
    if (eventoId) formData.append('evento_id', eventoId);
    formData.append('visibilidad', visibilidad);
    if (descripcion) formData.append('descripcion', descripcion);
    Array.from(inputFotos.files).slice(0, 10).forEach(function (file, i) {
        formData.append('imagenes[' + i + ']', file);
    });

    fetch('/api/social/posts', {
        method:  'POST',
        headers: {
            'Accept':       'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: formData,
    })
    .then(function (res) { return res.json(); })
    .then(function (respuesta) {
        btn.disabled    = false;
        btn.textContent = 'Publicar';
        if (respuesta.exito) {
            cerrarModalPublicacion();
            var lista    = document.getElementById('feed-lista');
            var nuevoPst = respuesta.datos;
            lista.insertAdjacentHTML('afterbegin', renderizarPost(nuevoPst));
            if (nuevoPst.imagenes && nuevoPst.imagenes.length > 1) {
                inicializarTouchCarrusel(nuevoPst.id);
            }
            document.getElementById('feed-vacio').style.display = 'none';
        } else {
            vibezAlerta('Error al publicar', parsearErrorServidor(respuesta, 'No se pudo publicar.'), 'error');
        }
    })
    .catch(function () {
        btn.disabled    = false;
        btn.textContent = 'Publicar';
        vibezAlerta('Error de conexión', 'No se pudo conectar con el servidor. Comprueba tu conexión.', 'error');
    });
}

function abrirLightbox(url) {
    window.open(url, '_blank');
}

/* ============================================================
   CHATS
   ============================================================ */

function cargarChats() {
    fetch('/api/social/chats', {
        headers: { 'Accept': 'application/json' }
    })
    .then(function (res) { return res.json(); })
    .then(function (respuesta) {
        var contenedor = document.getElementById('lista-chats');
        var skeleton   = document.getElementById('skeleton-chats');
        if (skeleton) skeleton.style.display = 'none';

        if (!respuesta.exito || respuesta.datos.length === 0) {
            contenedor.innerHTML = '<p class="soc-vacio">Aún no tienes conversaciones.<br>Habla con tus amigos desde la sección Amigos.</p>';
            return;
        }

        var html = '';
        respuesta.datos.forEach(function (chat) {
            var ultimoMsj = chat.ultimo_mensaje;
            var noLeidos  = chat.no_leidos;
            var nombre, avatarHtml, onclickAttr;

            if (chat.tipo === 'grupo') {
                /* ── Crew / grupo ── */
                var grupo    = chat.grupo;
                nombre       = escaparHtml(grupo.nombre);
                /* Dos iniciales del primer y segundo miembro para el stack de avatares */
                var m0 = grupo.miembros && grupo.miembros[0] ? grupo.miembros[0] : null;
                var m1 = grupo.miembros && grupo.miembros[1] ? grupo.miembros[1] : null;
                var i0 = m0 ? obtenerIniciales(m0.nombre, m0.apellido1) : '?';
                var i1 = m1 ? obtenerIniciales(m1.nombre, m1.apellido1) : '+';
                avatarHtml = '<div class="grupo-avatars-stack">'
                           + '<div class="avatar-mini">' + i0 + '</div>'
                           + '<div class="avatar-mini">' + i1 + '</div>'
                           + '</div>';
                onclickAttr = 'abrirChatGrupo(' + grupo.id + ',\'' + escaparTexto(grupo.nombre) + '\')';
            } else {
                /* ── DM ── */
                var amigo   = chat.amigo;
                nombre      = escaparHtml(amigo.nombre + ' ' + amigo.apellido1);
                avatarHtml  = construirAvatar(amigo.foto_url, amigo.nombre, amigo.apellido1, 'md');
                onclickAttr = 'abrirChat(' + amigo.id + ',\'' + escaparTexto(amigo.nombre + ' ' + amigo.apellido1) + '\',\'' + escaparTexto(amigo.foto_url || '') + '\')';
            }

            var vistaPrevia = ultimoMsj
                ? (ultimoMsj.es_mio ? 'Tú: ' : '') + (ultimoMsj.contenido.length > 38
                    ? ultimoMsj.contenido.substring(0, 38) + '…'
                    : ultimoMsj.contenido)
                : 'Sin mensajes aún';

            var badgeHtml = noLeidos > 0
                ? '<span class="chat-item-badge">' + noLeidos + '</span>'
                : '';
            var horaHtml = ultimoMsj
                ? '<span class="chat-item-hora">' + formatearHora(ultimoMsj.fecha) + '</span>'
                : '';

            html += '<div class="chat-item" onclick="' + onclickAttr + '" data-chat-id="' + chat.chat_id + '">'
                  +   '<div class="chat-item-avatar">' + avatarHtml + '</div>'
                  +   '<div class="chat-item-info">'
                  +     '<div class="chat-item-fila-top">'
                  +       '<span class="chat-item-nombre' + (noLeidos > 0 ? ' negrita' : '') + '">' + nombre + '</span>'
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
            '<p class="soc-vacio">No se pudo cargar los chats.</p>';
    });
}

function abrirChat(amigoId, nombreAmigo, fotoUrl) {
    // Si ya estamos en el chat con este amigo no recargar
    if (amigoId === amigoActualId) {
        mostrarVentanaChat();
        return;
    }

    detenerPolling();

    // Asegurar que estamos en el panel de chats
    if (panelActual !== 'chats') irA('chats');

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
            vibezAlerta('Error', respuesta.mensaje || 'No se pudo abrir el chat.', 'error');
            return;
        }

        var chatId = respuesta.datos.chat_id;
        var amigo  = respuesta.datos.amigo;

        chatActualId    = chatId;
        amigoActualId   = amigoId;
        ultimoMensajeId = 0;

        // Rellenar cabecera
        var avatarCab = construirAvatar(amigo.foto_url, amigo.nombre, amigo.apellido1, 'sm');
        document.getElementById('chat-amigo-info').innerHTML =
              '<div class="chat-cab-avatar">' + avatarCab + '</div>'
            + '<div class="chat-cab-datos">'
            +   '<p class="chat-cab-nombre">' + escaparHtml(amigo.nombre + ' ' + amigo.apellido1) + '</p>'
            +   (amigo.mood ? '<p class="chat-cab-mood">' + escaparHtml(amigo.mood) + '</p>' : '')
            + '</div>';

        mostrarVentanaChat();
        cargarMensajes(chatId);
        iniciarPolling(chatId);

        setTimeout(function () {
            var ta = document.getElementById('chat-textarea');
            if (ta) ta.focus();
        }, 150);
    })
    .catch(function () {
        vibezAlerta('Error de conexión', 'No se pudo abrir el chat. Inténtalo de nuevo.', 'error');
    });
}

/* ── Abrir un chat de grupo directamente por ID ── */
function abrirChatGrupo(grupoId, nombreGrupo) {
    if (chatActualId === grupoId) {
        mostrarVentanaChat();
        return;
    }

    detenerPolling();

    if (panelActual !== 'chats') irA('chats');

    chatActualId    = grupoId;
    amigoActualId   = null;
    ultimoMensajeId = 0;

    /* Cabecera con icono de grupo */
    document.getElementById('chat-amigo-info').innerHTML =
          '<div class="chat-cab-avatar">'
        + '  <div class="avatar-sm avatar-iniciales" style="background:linear-gradient(135deg,#7c3aed,#a855f7)">'
        + '    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px">'
        + '      <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>'
        + '    </svg>'
        + '  </div>'
        + '</div>'
        + '<div class="chat-cab-datos">'
        +   '<p class="chat-cab-nombre">' + escaparHtml(nombreGrupo) + '</p>'
        + '</div>';

    mostrarVentanaChat();
    cargarMensajes(grupoId);
    iniciarPolling(grupoId);

    setTimeout(function () {
        var ta = document.getElementById('chat-textarea');
        if (ta) ta.focus();
    }, 150);
}

/* ── Modal: crear crew ── */

function abrirModalCrearCrew() {
    document.getElementById('crew-modal-overlay').style.display = 'flex';
    document.getElementById('crew-input-nombre').value = '';
    document.getElementById('crew-btn-crear').disabled    = false;
    document.getElementById('crew-btn-crear').textContent = 'Crear crew';

    /* Cargar lista de amigos como checkboxes */
    var lista = document.getElementById('crew-lista-amigos');
    lista.innerHTML = '<p class="soc-vacio" style="padding:12px 0">Cargando…</p>';

    fetch('/api/social/amigos', { headers: { 'Accept': 'application/json' } })
    .then(function (res) { return res.json(); })
    .then(function (respuesta) {
        if (!respuesta.exito || respuesta.datos.length === 0) {
            lista.innerHTML = '<p class="soc-vacio" style="padding:12px 0">Aún no tienes amigos.</p>';
            return;
        }
        var html = '';
        respuesta.datos.forEach(function (amigo) {
            var iniciales = obtenerIniciales(amigo.nombre, amigo.apellido1);
            var avatar = amigo.foto_url
                ? '<img src="' + escaparHtml(amigo.foto_url) + '" style="width:30px;height:30px;border-radius:50%;object-fit:cover" alt="">'
                : '<div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#a855f7);display:grid;place-items:center;font-size:11px;font-weight:700;color:#fff">' + escaparHtml(iniciales) + '</div>';
            html += '<label class="amigo-check-item">'
                  +   '<input type="checkbox" name="crew-miembro" value="' + amigo.id + '">'
                  +   avatar
                  +   '<span class="amigo-check-nombre">' + escaparHtml(amigo.nombre + ' ' + amigo.apellido1) + '</span>'
                  + '</label>';
        });
        lista.innerHTML = html;
    })
    .catch(function () {
        lista.innerHTML = '<p class="soc-vacio" style="padding:12px 0">No se pudo cargar.</p>';
    });
}

function cerrarModalCrearCrew() {
    document.getElementById('crew-modal-overlay').style.display = 'none';
}

function crearCrew() {
    var nombre = document.getElementById('crew-input-nombre').value.trim();
    if (!nombre) {
        vibezAlerta('Falta el nombre', 'Ponle un nombre a tu crew.', 'warning');
        return;
    }

    var checkboxes = document.querySelectorAll('input[name="crew-miembro"]:checked');
    if (checkboxes.length === 0) {
        vibezAlerta('Sin miembros', 'Selecciona al menos un amigo.', 'warning');
        return;
    }

    var miembroIds = [];
    checkboxes.forEach(function (cb) { miembroIds.push(parseInt(cb.value, 10)); });

    var btn = document.getElementById('crew-btn-crear');
    btn.disabled    = true;
    btn.textContent = 'Creando…';

    fetch('/api/social/chats/grupo', {
        method:  'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept':       'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({ nombre: nombre, miembro_ids: miembroIds }),
    })
    .then(function (res) { return res.json(); })
    .then(function (respuesta) {
        btn.disabled    = false;
        btn.textContent = 'Crear crew';
        if (respuesta.exito) {
            cerrarModalCrearCrew();
            cargarChats();
            abrirChatGrupo(respuesta.datos.chat_id, respuesta.datos.nombre);
        } else {
            vibezAlerta('Error', respuesta.mensaje || 'No se pudo crear el crew.', 'error');
        }
    })
    .catch(function () {
        btn.disabled    = false;
        btn.textContent = 'Crear crew';
        vibezAlerta('Error de conexión', 'No se pudo crear el crew.', 'error');
    });
}

function esDesktop() {
    return window.innerWidth >= 900;
}

function mostrarVentanaChat() {
    var emptyState = document.getElementById('chat-vacio-desktop');
    if (emptyState) emptyState.style.display = 'none';

    if (!esDesktop()) {
        document.getElementById('chats-lista-view').classList.remove('activo');
        document.getElementById('chats-ventana-view').classList.add('activo');
    }
}

function cargarMensajes(chatId) {
    var cargandoEl = document.getElementById('cargando-mensajes');
    cargandoEl.style.display = 'flex';

    fetch('/api/social/chats/' + chatId + '/mensajes', {
        headers: { 'Accept': 'application/json' }
    })
    .then(function (res) { return res.json(); })
    .then(function (respuesta) {
        cargandoEl.style.display = 'none';
        if (!respuesta.exito) return;

        var area = document.getElementById('chat-mensajes');
        area.innerHTML = '';

        if (respuesta.datos.length === 0) {
            area.innerHTML = '<p class="chat-sin-mensajes">¡Sé el primero en escribir! 👋</p>';
            return;
        }

        var fechaAnterior = null;
        respuesta.datos.forEach(function (msg) {
            var fechaMensaje = msg.fecha ? msg.fecha.substring(0, 10) : null;
            if (fechaMensaje && fechaMensaje !== fechaAnterior) {
                area.appendChild(crearSeparadorFecha(msg.fecha));
                fechaAnterior = fechaMensaje;
            }
            area.appendChild(crearBurbujaMensaje(msg));
            if (msg.id > ultimoMensajeId) ultimoMensajeId = msg.id;
        });

        scrollAlFinal(area);
    })
    .catch(function () {
        document.getElementById('cargando-mensajes').style.display = 'none';
    });
}

function enviarMensaje() {
    var textarea  = document.getElementById('chat-textarea');
    var contenido = textarea.value.trim();
    if (!contenido || !chatActualId) return;

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
        if (!respuesta.exito) { vibezAlerta('Error', 'No se pudo enviar el mensaje.', 'error'); return; }

        textarea.value        = '';
        textarea.style.height = 'auto';

        var area       = document.getElementById('chat-mensajes');
        var sinMensajes = area.querySelector('.chat-sin-mensajes');
        if (sinMensajes) sinMensajes.remove();

        area.appendChild(crearBurbujaMensaje(respuesta.datos));
        if (respuesta.datos.id > ultimoMensajeId) ultimoMensajeId = respuesta.datos.id;
        scrollAlFinal(area);
    })
    .catch(function () {
        btnEnviar.disabled = false;
        vibezAlerta('Error de conexión', 'No se pudo enviar el mensaje.', 'error');
    });
}

function iniciarPolling(chatId) {
    detenerPolling();
    intervaloPolling = setInterval(function () {
        if (!chatActualId) { detenerPolling(); return; }

        fetch('/api/social/chats/' + chatId + '/nuevos?desde=' + ultimoMensajeId, {
            headers: { 'Accept': 'application/json' }
        })
        .then(function (res) { return res.json(); })
        .then(function (respuesta) {
            if (!respuesta.exito || respuesta.datos.length === 0) return;

            var area      = document.getElementById('chat-mensajes');
            var sinMensajes = area.querySelector('.chat-sin-mensajes');
            if (sinMensajes) sinMensajes.remove();

            var eraFinal      = estaEnElFinal(area);
            var fechaAnterior = obtenerUltimaFechaEnArea(area);

            respuesta.datos.forEach(function (msg) {
                var fechaMensaje = msg.fecha ? msg.fecha.substring(0, 10) : null;
                if (fechaMensaje && fechaMensaje !== fechaAnterior) {
                    area.appendChild(crearSeparadorFecha(msg.fecha));
                    fechaAnterior = fechaMensaje;
                }
                area.appendChild(crearBurbujaMensaje(msg));
                if (msg.id > ultimoMensajeId) ultimoMensajeId = msg.id;
            });

            if (eraFinal) scrollAlFinal(area);
        })
        .catch(function () {});
    }, 3000);
}

function detenerPolling() {
    if (intervaloPolling) {
        clearInterval(intervaloPolling);
        intervaloPolling = null;
    }
}

function cerrarChat() {
    detenerPolling();
    chatActualId  = null;
    amigoActualId = null;

    var emptyState = document.getElementById('chat-vacio-desktop');
    if (emptyState) emptyState.style.display = '';

    if (!esDesktop()) {
        document.getElementById('chats-ventana-view').classList.remove('activo');
        document.getElementById('chats-lista-view').classList.add('activo');
    }
}

/* ============================================================
   AMIGOS
   ============================================================ */

function cargarAmigos() {
    fetch('/api/social/amigos', {
        headers: { 'Accept': 'application/json' }
    })
    .then(function (res) { return res.json(); })
    .then(function (respuesta) {
        var contenedor = document.getElementById('lista-amigos');
        var skeleton   = document.getElementById('skeleton-amigos');
        if (skeleton) skeleton.style.display = 'none';

        if (!respuesta.exito || respuesta.datos.length === 0) {
            contenedor.innerHTML = '<p class="soc-vacio">Aún no tienes amigos.<br>Usa el botón + para encontrar gente.</p>';
            return;
        }

        var html = '';
        respuesta.datos.forEach(function (amigo) {
            var nombre = amigo.nombre + ' ' + amigo.apellido1;
            var avatar = construirAvatar(amigo.foto_url, amigo.nombre, amigo.apellido1, 'md');

            html += '<div class="amigo-item">'
                  +   '<div class="amigo-item-avatar">' + avatar + '</div>'
                  +   '<div class="amigo-item-info">'
                  +     '<p class="amigo-item-nombre">' + escaparHtml(nombre) + '</p>'
                  +     (amigo.mood ? '<p class="amigo-item-mood">' + escaparHtml(amigo.mood) + '</p>' : '')
                  +   '</div>'
                  +   '<button class="btn-chat-amigo" onclick="abrirChatDesdeAmigos(' + amigo.id + ',\'' + escaparTexto(nombre) + '\',\'' + escaparTexto(amigo.foto_url || '') + '\')" title="Enviar mensaje">'
                  +     '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>'
                  +   '</button>'
                  + '</div>';
        });

        contenedor.innerHTML = html;
    })
    .catch(function () {
        document.getElementById('lista-amigos').innerHTML =
            '<p class="soc-vacio">No se pudo cargar los amigos.</p>';
    });
}

function abrirChatDesdeAmigos(amigoId, nombre, foto) {
    irA('chats');
    // Pequeño delay para que el panel cambie antes de abrir el chat
    setTimeout(function () { abrirChat(amigoId, nombre, foto); }, 50);
}

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
            var avatar = construirAvatar(sol.foto_url, sol.nombre, sol.apellido1, 'md');

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
    .catch(function () {});
}

function aceptarSolicitud(solicitudId) {
    fetch('/api/social/solicitudes/' + solicitudId + '/aceptar', {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
    })
    .then(function (res) { return res.json(); })
    .then(function (respuesta) {
        if (!respuesta.exito) { vibezAlerta('Error', respuesta.mensaje, 'error'); return; }
        var el = document.getElementById('solicitud-' + solicitudId);
        if (el) {
            el.classList.add('fadeOut');
            setTimeout(function () {
                el.remove();
                cargarAmigos();
                cargarSolicitudes();
                actualizarContadorNavbar();
            }, 300);
        }
    })
    .catch(function () { vibezAlerta('Error de conexión', 'No se pudo aceptar la solicitud.', 'error'); });
}

function rechazarSolicitud(solicitudId) {
    fetch('/api/social/solicitudes/' + solicitudId + '/rechazar', {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
    })
    .then(function (res) { return res.json(); })
    .then(function (respuesta) {
        var el = document.getElementById('solicitud-' + solicitudId);
        if (el) {
            el.classList.add('fadeOut');
            setTimeout(function () {
                el.remove();
                cargarSolicitudes();
                actualizarContadorNavbar();
            }, 300);
        }
    })
    .catch(function () { vibezAlerta('Error de conexión', 'No se pudo rechazar la solicitud.', 'error'); });
}

/* ── Modal: añadir amigo ── */

function abrirModalAnadirAmigo() {
    document.getElementById('modal-amigo-overlay').style.display = 'flex';
    document.getElementById('input-anadir-amigo').value          = '';
    document.getElementById('resultados-anadir-amigo').innerHTML =
        '<p class="soc-vacio-small">Escribe al menos 2 caracteres para buscar</p>';
    setTimeout(function () {
        document.getElementById('input-anadir-amigo').focus();
    }, 200);
}

function cerrarModalAnadirAmigo() {
    document.getElementById('modal-amigo-overlay').style.display = 'none';
}

function buscarParaAnadir(query) {
    clearTimeout(temporizadorBusqueda);

    var contenedor = document.getElementById('resultados-anadir-amigo');

    if (query.length < 2) {
        contenedor.innerHTML = '<p class="soc-vacio-small">Escribe al menos 2 caracteres para buscar</p>';
        return;
    }

    contenedor.innerHTML = '<p class="soc-vacio-small">Buscando…</p>';

    temporizadorBusqueda = setTimeout(function () {
        fetch('/api/social/usuarios/buscar?q=' + encodeURIComponent(query), {
            headers: { 'Accept': 'application/json' }
        })
        .then(function (res) { return res.json(); })
        .then(function (respuesta) {
            if (!respuesta.exito || respuesta.datos.length === 0) {
                contenedor.innerHTML = '<p class="soc-vacio-small">No se encontraron usuarios.</p>';
                return;
            }

            var html = '<div style="padding:0 0 4px">';
            respuesta.datos.forEach(function (persona) {
                var nombre = persona.nombre + ' ' + persona.apellido1;
                var avatar = construirAvatar(persona.foto_url, persona.nombre, persona.apellido1, 'sm');

                html += '<div class="descubrir-item" id="descubrir-' + persona.id + '">'
                      +   '<div>' + avatar + '</div>'
                      +   '<div class="descubrir-info">'
                      +     '<p class="descubrir-nombre">' + escaparHtml(nombre) + '</p>'
                      +   '</div>'
                      +   '<button class="btn-enviar-solicitud" id="btn-sol-' + persona.id + '" onclick="enviarSolicitudAnadir(' + persona.id + ', this)">Añadir</button>'
                      + '</div>';
            });
            html += '</div>';
            contenedor.innerHTML = html;
        })
        .catch(function () {
            contenedor.innerHTML = '<p class="soc-vacio-small">Error al buscar. Inténtalo de nuevo.</p>';
        });
    }, 350);
}

function enviarSolicitudAnadir(receptorId, boton) {
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
            boton.textContent = 'Enviado ✓';
            boton.classList.add('enviado');
        } else {
            boton.disabled    = false;
            boton.textContent = 'Añadir';
            vibezAlerta('No se pudo enviar', respuesta.mensaje, 'error');
        }
    })
    .catch(function () {
        boton.disabled    = false;
        boton.textContent = 'Añadir';
        vibezAlerta('Error de conexión', 'Error al enviar la solicitud.', 'error');
    });
}

/* ============================================================
   BADGE DEL NAVBAR
   ============================================================ */

function actualizarContadorNavbar() {
    fetch('/api/social/contador', {
        headers: { 'Accept': 'application/json' }
    })
    .then(function (res) { return res.json(); })
    .then(function (respuesta) {
        if (!respuesta.exito) return;

        var total = respuesta.datos.total;
        var badge = document.getElementById('nav-badge-social');
        if (badge) {
            badge.textContent   = total > 99 ? '99+' : total;
            badge.style.display = total > 0 ? 'inline-flex' : 'none';
        }

        actualizarBadgesInternos(respuesta.datos);
    })
    .catch(function () {});
}

function actualizarBadgesInternos(datos) {
    var bMensajes = document.getElementById('badge-mensajes');
    if (bMensajes) {
        bMensajes.textContent   = datos.mensajes;
        bMensajes.style.display = datos.mensajes > 0 ? 'inline-flex' : 'none';
    }
    var bSolicitudes = document.getElementById('badge-solicitudes');
    if (bSolicitudes) {
        bSolicitudes.textContent   = datos.solicitudes;
        bSolicitudes.style.display = datos.solicitudes > 0 ? 'inline-flex' : 'none';
    }
}

/* ============================================================
   HELPERS DE INTERFAZ
   ============================================================ */

function crearBurbujaMensaje(msg) {
    var div = document.createElement('div');
    div.className  = 'mensaje-fila ' + (msg.es_mio ? 'mio' : 'suyo');
    div.dataset.id = msg.id;
    var hora = msg.fecha ? formatearHora(msg.fecha) : '';
    /* Nombre del remitente visible en mensajes ajenos (útil en grupos) */
    var remitente = (!msg.es_mio && msg.autor)
        ? '<span class="mensaje-remitente">' + escaparHtml(msg.autor) + '</span>'
        : '';
    div.innerHTML = '<div class="mensaje-burbuja">'
                  +   remitente
                  +   '<p class="mensaje-texto">' + escaparHtml(msg.contenido).replace(/\n/g, '<br>') + '</p>'
                  +   '<span class="mensaje-hora">' + hora + '</span>'
                  + '</div>';
    return div;
}

function crearSeparadorFecha(fechaStr) {
    var div       = document.createElement('div');
    div.className = 'chat-separador-fecha';
    div.dataset.fecha = fechaStr ? fechaStr.substring(0, 10) : '';
    div.textContent   = formatearFechaCompleta(fechaStr);
    return div;
}

function construirAvatar(fotoUrl, nombre, apellido, tamaño) {
    var clase  = 'avatar-' + (tamaño || 'sm');
    var inicia = obtenerIniciales(nombre, apellido);
    if (fotoUrl) {
        return '<img src="' + escaparHtml(fotoUrl) + '" alt="' + escaparHtml(nombre || '') + '" class="' + clase + ' avatar-img">';
    }
    return '<div class="' + clase + ' avatar-iniciales">' + inicia + '</div>';
}

function obtenerIniciales(nombre, apellido) {
    return ((nombre  ? nombre.charAt(0).toUpperCase()  : '')
          + (apellido ? apellido.charAt(0).toUpperCase() : ''));
}

function formatearHora(fechaStr) {
    if (!fechaStr) return '';
    try {
        return new Date(fechaStr.replace(' ', 'T'))
            .toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
    } catch (e) { return ''; }
}

function formatearFechaCompleta(fechaStr) {
    if (!fechaStr) return '';
    try {
        var fecha = new Date(fechaStr.replace(' ', 'T'));
        var hoy   = new Date();
        var ayer  = new Date(hoy); ayer.setDate(hoy.getDate() - 1);
        if (fecha.toDateString() === hoy.toDateString())  return 'Hoy';
        if (fecha.toDateString() === ayer.toDateString()) return 'Ayer';
        return fecha.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
    } catch (e) { return ''; }
}

function formatearFechaRelativa(fechaStr) {
    if (!fechaStr) return '';
    try {
        var fecha   = new Date(fechaStr.replace(' ', 'T'));
        var diffMs  = Date.now() - fecha.getTime();
        var diffMin = Math.floor(diffMs / 60000);
        var diffH   = Math.floor(diffMin / 60);
        var diffD   = Math.floor(diffH / 24);

        if (diffMin < 1)  return 'ahora';
        if (diffMin < 60) return 'hace ' + diffMin + 'm';
        if (diffH   < 24) return 'hace ' + diffH + 'h';
        if (diffD   < 7)  return 'hace ' + diffD + 'd';
        return fecha.toLocaleDateString('es-ES', { day: '2-digit', month: 'short' });
    } catch (e) { return ''; }
}

function escaparHtml(texto) {
    if (!texto) return '';
    return String(texto)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function escaparTexto(texto) {
    if (!texto) return '';
    return String(texto).replace(/\\/g, '\\\\').replace(/'/g, "\\'");
}

function estaEnElFinal(area) {
    return area.scrollHeight - area.scrollTop - area.clientHeight < 50;
}

function scrollAlFinal(area) {
    area.scrollTop = area.scrollHeight;
}

function obtenerUltimaFechaEnArea(area) {
    var separadores = area.querySelectorAll('.chat-separador-fecha');
    if (!separadores.length) return null;
    return separadores[separadores.length - 1].dataset.fecha || null;
}

function ajustarAlturaTextarea(textarea) {
    textarea.style.height = 'auto';
    textarea.style.height = Math.min(textarea.scrollHeight, 120) + 'px';
}

function manejarTeclaEnvio(event) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        enviarMensaje();
    }
}

/* ============================================================
   RESPUESTAS A COMENTARIOS
   ============================================================ */

function mostrarInputRespuesta(comentarioId) {
    // Ocultar otros inputs de respuesta abiertos
    document.querySelectorAll('[id^="reply-input-wrap-"]').forEach(function (el) {
        el.style.display = 'none';
    });
    var wrap = document.getElementById('reply-input-wrap-' + comentarioId);
    if (wrap) {
        wrap.style.display = 'block';
        var input = document.getElementById('reply-input-' + comentarioId);
        if (input) input.focus();
    }
}

function enviarRespuesta(comentarioPadreId) {
    var inputEl   = document.getElementById('reply-input-' + comentarioPadreId);
    var contenido = inputEl ? inputEl.value.trim() : '';
    if (!contenido) return;

    // Subir hasta .post-card para obtener el postId
    var comentarioEl = document.getElementById('pub-com-' + comentarioPadreId);
    var postCard     = comentarioEl ? comentarioEl.closest('.post-card') : null;
    var postId       = postCard ? postCard.dataset.postId : null;
    if (!postId) return;

    inputEl.disabled = true;

    fetch('/api/social/posts/' + postId + '/comentarios', {
        method:  'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept':       'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({ contenido: contenido, padre_id: comentarioPadreId }),
    })
    .then(function (res) { return res.json(); })
    .then(function (respuesta) {
        inputEl.disabled = false;
        inputEl.value    = '';
        if (respuesta.exito) {
            var r     = respuesta.datos;
            var autor = escaparHtml(r.autor.nombre + ' ' + r.autor.apellido1);
            var html  = '<div class="post-comment-reply">'
                      + '<p><strong>' + autor + '</strong> ' + escaparHtml(r.contenido) + '</p>'
                      + '<p class="post-comment-time">' + formatearHora(r.fecha) + '</p>'
                      + '</div>';
            var repliesEl = document.getElementById('replies-' + comentarioPadreId);
            if (repliesEl) repliesEl.insertAdjacentHTML('beforeend', html);
            var wrap = document.getElementById('reply-input-wrap-' + comentarioPadreId);
            if (wrap) wrap.style.display = 'none';
        }
    })
    .catch(function () { inputEl.disabled = false; });
}

function manejarTeclaRespuesta(event, comentarioId) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        enviarRespuesta(comentarioId);
    }
}

/* ============================================================
   HISTORIAS — carga y barra
   ============================================================ */

function cargarHistorias() {
    fetch('/api/social/historias', { headers: { 'Accept': 'application/json' } })
    .then(function (res) { return res.json(); })
    .then(function (respuesta) {
        if (!respuesta.exito) return;
        historialHistorias = respuesta.datos || [];
        renderizarBarraHistorias('historias-barra');
    })
    .catch(function () {});
}

function renderizarBarraHistorias(contenedorId) {
    var barra = document.getElementById(contenedorId);
    if (!barra) return;
    barra.innerHTML = '';

    // Buscar si el usuario tiene su propio grupo en el feed
    var miGrupoIdx = -1;
    historialHistorias.forEach(function (grupo, idx) {
        if (grupo.es_mio) miGrupoIdx = idx;
    });

    // Círculo "Tu historia": si tiene historias abre el visor, si no abre el modal de crear
    var yoImg, yoClick, yoClase;
    if (miGrupoIdx >= 0) {
        var miGrupo = historialHistorias[miGrupoIdx];
        var mu = miGrupo.usuario;
        yoImg   = mu.foto_url
            ? '<img class="historia-circulo-img" src="' + escaparHtml(mu.foto_url) + '" alt="">'
            : '<div class="historia-circulo-iniciales">' + escaparHtml((mu.nombre ? mu.nombre[0] : '') + (mu.apellido1 ? mu.apellido1[0] : '')) + '</div>';
        yoClick = 'abrirVisorHistorias(' + miGrupoIdx + ')';
        yoClase = 'historia-circulo historia-circulo-yo con-historias';
    } else {
        yoImg   = '<div class="historia-circulo-iniciales">+</div>';
        yoClick = 'abrirModalHistoria()';
        yoClase = 'historia-circulo historia-circulo-yo';
    }

    var yoHtml = '<div class="' + yoClase + '" onclick="' + yoClick + '" title="Tu historia">'
               + '  <div class="historia-circulo-ring">' + yoImg + '</div>'
               + '  <span class="historia-circulo-nombre">Tu historia</span>'
               + '</div>';
    barra.insertAdjacentHTML('beforeend', yoHtml);

    // Un círculo por grupo de amigos (omitir el propio)
    historialHistorias.forEach(function (grupo, grupoIdx) {
        if (grupo.es_mio) return;
        var todasVistas = grupo.historias.every(function (h) { return h.ha_visto; });
        var u    = grupo.usuario;
        var img  = u.foto_url
            ? '<img class="historia-circulo-img" src="' + escaparHtml(u.foto_url) + '" alt="">'
            : '<div class="historia-circulo-iniciales">'
              + escaparHtml((u.nombre ? u.nombre[0] : '') + (u.apellido1 ? u.apellido1[0] : ''))
              + '</div>';
        var html = '<div class="historia-circulo' + (todasVistas ? ' visto' : '') + '"'
                 + ' onclick="abrirVisorHistorias(' + grupoIdx + ')" title="' + escaparHtml(u.nombre) + '">'
                 + '  <div class="historia-circulo-ring">' + img + '</div>'
                 + '  <span class="historia-circulo-nombre">' + escaparHtml(u.nombre) + '</span>'
                 + '</div>';
        barra.insertAdjacentHTML('beforeend', html);
    });
}

/* ── Modal de nueva historia ── */

function abrirModalHistoria() {
    document.getElementById('hist-modal-overlay').style.display = 'flex';
    document.getElementById('hist-input-texto').value   = '';
    document.getElementById('hist-select-evento').value = '';
    document.getElementById('hist-input-foto').value    = '';
    document.getElementById('hist-preview-wrap').style.display = 'none';
    document.getElementById('hist-upload-area').style.display  = 'flex';
    var btn = document.getElementById('hist-btn-publicar');
    btn.disabled    = false;
    btn.textContent = 'Compartir';
}

function cerrarModalHistoria() {
    document.getElementById('hist-modal-overlay').style.display = 'none';
}

function previsualizarHistoria(input) {
    if (!input.files || !input.files[0]) return;
    var reader = new FileReader();
    reader.onload = function (e) {
        document.getElementById('hist-preview-img').src             = e.target.result;
        document.getElementById('hist-preview-wrap').style.display  = 'block';
        document.getElementById('hist-upload-area').style.display   = 'none';
    };
    reader.readAsDataURL(input.files[0]);
}

function publicarHistoria() {
    var inputFoto = document.getElementById('hist-input-foto');
    if (!inputFoto.files || !inputFoto.files[0]) {
        vibezAlerta('Falta la foto', 'Selecciona una foto para tu historia.', 'warning');
        return;
    }

    /* Validación de tamaño antes de enviar (límite servidor: 20MB) */
    if (inputFoto.files[0].size > 20 * 1024 * 1024) {
        vibezAlerta('Foto demasiado grande', 'La imagen debe pesar menos de 20MB.', 'warning');
        return;
    }

    var btn = document.getElementById('hist-btn-publicar');
    btn.disabled    = true;
    btn.textContent = 'Publicando…';

    var formData = new FormData();
    formData.append('foto', inputFoto.files[0]);
    var texto    = document.getElementById('hist-input-texto').value.trim();
    var eventoId = document.getElementById('hist-select-evento').value;
    if (texto)    formData.append('texto', texto);
    if (eventoId) formData.append('evento_id', eventoId);

    fetch('/api/social/historias', {
        method:  'POST',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body:    formData,
    })
    .then(function (res) { return res.json(); })
    .then(function (respuesta) {
        btn.disabled    = false;
        btn.textContent = 'Compartir';
        if (respuesta.exito) {
            cerrarModalHistoria();
            cargarHistorias();
        } else {
            vibezAlerta('Error al publicar', parsearErrorServidor(respuesta, 'No se pudo publicar la historia.'), 'error');
        }
    })
    .catch(function () {
        btn.disabled    = false;
        btn.textContent = 'Compartir';
        vibezAlerta('Error de conexión', 'No se pudo conectar con el servidor.', 'error');
    });
}

/* ── Visor de historias ── */

function abrirVisorHistorias(grupoIdx) {
    if (!historialHistorias[grupoIdx]) return;
    visorGrupoIdx    = grupoIdx;
    visorHistoriaIdx = 0;
    document.getElementById('visor-overlay').style.display = 'flex';
    document.body.style.overflow = 'hidden';
    mostrarHistoriaActual();
}

function cerrarVisorHistorias() {
    clearTimeout(visorTimer);
    document.getElementById('visor-overlay').style.display = 'none';
    document.body.style.overflow = '';
}

function mostrarHistoriaActual() {
    clearTimeout(visorTimer);
    var grupo    = historialHistorias[visorGrupoIdx];
    var historia = grupo ? grupo.historias[visorHistoriaIdx] : null;
    if (!historia) { cerrarVisorHistorias(); return; }

    document.getElementById('visor-foto').src = historia.media_url;

    var u = grupo.usuario;
    var avatarHtml = construirAvatar(u.foto_url, u.nombre, u.apellido1, 'sm');
    document.getElementById('visor-autor').innerHTML =
        '<div>' + avatarHtml + '</div>'
      + '<div>'
      + '  <div class="visor-autor-nombre">' + escaparHtml(u.nombre + ' ' + (u.apellido1 || '')) + '</div>'
      + '  <div class="visor-autor-tiempo">' + formatearFechaRelativa(historia.fecha_creacion) + '</div>'
      + '</div>';

    var tagEl = document.getElementById('visor-evento-tag');
    if (historia.evento) {
        tagEl.textContent   = '🎫 ' + historia.evento.titulo;
        tagEl.style.display = 'inline-block';
    } else {
        tagEl.style.display = 'none';
    }

    var textoEl = document.getElementById('visor-texto');
    if (historia.texto) {
        textoEl.textContent   = historia.texto;
        textoEl.style.display = 'block';
    } else {
        textoEl.style.display = 'none';
    }

    // Barra de progreso
    var total    = grupo.historias.length;
    var progHtml = '';
    for (var i = 0; i < total; i++) {
        var cls = i < visorHistoriaIdx ? 'completo' : (i === visorHistoriaIdx ? 'activo' : '');
        progHtml += '<div class="visor-progress-seg ' + cls + '">'
                  + '<div class="visor-progress-seg-fill"></div>'
                  + '</div>';
    }
    document.getElementById('visor-progress').innerHTML = progHtml;

    // Disparar animación CSS del segmento activo
    var segActivo = document.querySelector('.visor-progress-seg.activo .visor-progress-seg-fill');
    if (segActivo) {
        segActivo.style.transition = 'none';
        segActivo.style.width      = '0%';
        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                segActivo.style.transition = 'width 5s linear';
                segActivo.style.width      = '100%';
            });
        });
    }

    // Registrar vista en el servidor
    fetch('/api/social/historias/' + historia.id + '/vista', {
        method:  'POST',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
    }).catch(function () {});

    // Avance automático a los 5 segundos
    visorTimer = setTimeout(visorSiguiente, 5000);
}

function visorSiguiente() {
    clearTimeout(visorTimer);
    var grupo = historialHistorias[visorGrupoIdx];
    if (!grupo) { cerrarVisorHistorias(); return; }

    visorHistoriaIdx++;
    if (visorHistoriaIdx >= grupo.historias.length) {
        visorGrupoIdx++;
        visorHistoriaIdx = 0;
        if (visorGrupoIdx >= historialHistorias.length) {
            cerrarVisorHistorias();
            return;
        }
    }
    mostrarHistoriaActual();
}

function visorAnterior() {
    clearTimeout(visorTimer);
    visorHistoriaIdx--;
    if (visorHistoriaIdx < 0) {
        visorGrupoIdx--;
        if (visorGrupoIdx < 0) {
            // Ya estamos en la primera historia del primer grupo
            visorGrupoIdx    = 0;
            visorHistoriaIdx = 0;
        } else {
            var grupoAnterior = historialHistorias[visorGrupoIdx];
            visorHistoriaIdx  = grupoAnterior ? grupoAnterior.historias.length - 1 : 0;
        }
    }
    mostrarHistoriaActual();
}

/* ============================================================
   PANEL EVENTOS — filtro por evento
   ============================================================ */

function cargarEventosConContenido() {
    var lista    = document.getElementById('lista-eventos-contenido');
    var skeleton = document.getElementById('skeleton-eventos');
    var vacio    = document.getElementById('eventos-vacio');

    if (skeleton) skeleton.style.display = 'block';
    if (vacio)    vacio.style.display    = 'none';

    fetch('/api/social/eventos-con-contenido', {
        headers: { 'Accept': 'application/json' }
    })
    .then(function (res) { return res.json(); })
    .then(function (respuesta) {
        if (skeleton) skeleton.style.display = 'none';

        if (!respuesta.exito || !respuesta.datos.length) {
            if (vacio) vacio.style.display = 'block';
            return;
        }

        var html = '';
        respuesta.datos.forEach(function (ev) {
            var portada = ev.portada_url
                ? '<img class="evento-filtro-portada" src="' + escaparHtml(ev.portada_url) + '" alt="">'
                : '<div class="evento-filtro-portada" style="background:rgba(124,58,237,0.15)"></div>';
            var meta = [];
            if (ev.total_posts     > 0) meta.push(ev.total_posts     + ' publicaciones');
            if (ev.total_historias > 0) meta.push(ev.total_historias + ' historias');

            html += '<div class="evento-filtro-card" onclick="abrirFiltroEvento(' + ev.id + ',\'' + escaparTexto(ev.titulo) + '\')">'
                  + portada
                  + '<div class="evento-filtro-info">'
                  +   '<div class="evento-filtro-titulo">' + escaparHtml(ev.titulo) + '</div>'
                  +   '<div class="evento-filtro-meta">' + escaparHtml(meta.join(' · ')) + '</div>'
                  + '</div>'
                  + '<div class="evento-filtro-arrow">'
                  +   '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">'
                  +     '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>'
                  +   '</svg>'
                  + '</div>'
                  + '</div>';
        });

        if (lista) lista.innerHTML = html;
    })
    .catch(function () {
        if (skeleton) skeleton.style.display = 'none';
        if (vacio)    vacio.style.display    = 'block';
    });
}

function abrirFiltroEvento(eventoId, titulo) {
    eventoFiltroActual = eventoId;

    // Actualizar título del topbar
    var tituloEl = document.getElementById('titulo-panel-eventos');
    if (tituloEl) tituloEl.textContent = titulo || 'Evento';

    // Mostrar botón volver, ocultar listado, mostrar detalle
    var btnVolver = document.getElementById('btn-volver-eventos');
    if (btnVolver) btnVolver.style.display = 'flex';

    document.getElementById('eventos-lista-view').classList.remove('activo');
    document.getElementById('eventos-detalle-view').classList.add('activo');

    // Cargar contenido del evento
    cargarFeedPorEvento(eventoId);
}

function irAFiltroEvento(eventoId) {
    // Navegar al panel de eventos y abrir el filtro del evento indicado
    irA('eventos');
    // Pequeño delay para que el panel esté activo antes de abrir el detalle
    setTimeout(function () {
        var evento = null;
        // Buscar el titulo en la lista ya cargada si existe
        var card = document.querySelector('.evento-filtro-card[onclick*="' + eventoId + '"]');
        var titulo = card
            ? card.querySelector('.evento-filtro-titulo').textContent
            : 'Evento';
        abrirFiltroEvento(eventoId, titulo);
    }, 100);
}

function volverListadoEventos() {
    eventoFiltroActual = null;

    var btnVolver = document.getElementById('btn-volver-eventos');
    if (btnVolver) btnVolver.style.display = 'none';

    var tituloEl = document.getElementById('titulo-panel-eventos');
    if (tituloEl) tituloEl.textContent = 'Por evento';

    document.getElementById('eventos-detalle-view').classList.remove('activo');
    document.getElementById('eventos-lista-view').classList.add('activo');
}

function cargarFeedPorEvento(eventoId) {
    var postsEl    = document.getElementById('eventos-detalle-posts');
    var barraEl    = document.getElementById('historias-evento-barra');
    var cargandoEl = document.getElementById('eventos-detalle-cargando');
    var vacioEl    = document.getElementById('eventos-detalle-vacio');

    if (postsEl)    postsEl.innerHTML    = '';
    if (barraEl)    barraEl.innerHTML    = '';
    if (vacioEl)    vacioEl.style.display    = 'none';
    if (cargandoEl) cargandoEl.style.display = 'flex';

    fetch('/api/social/evento/' + eventoId, {
        headers: { 'Accept': 'application/json' }
    })
    .then(function (res) { return res.json(); })
    .then(function (respuesta) {
        if (cargandoEl) cargandoEl.style.display = 'none';

        if (!respuesta.exito) {
            if (vacioEl) vacioEl.style.display = 'block';
            return;
        }

        // Renderizar historias del evento en la barra
        if (barraEl && respuesta.historias && respuesta.historias.length > 0) {
            var histHtml = '';
            respuesta.historias.forEach(function (h, idx) {
                var u   = h.usuario;
                var img = u.foto_url
                    ? '<img class="historia-circulo-img" src="' + escaparHtml(u.foto_url) + '" alt="">'
                    : '<div class="historia-circulo-iniciales">'
                      + escaparHtml((u.nombre ? u.nombre[0] : '') + (u.apellido1 ? u.apellido1[0] : ''))
                      + '</div>';
                histHtml += '<div class="historia-circulo"'
                          + ' onclick="abrirVisorDesdeEvento(' + idx + ',\'' + eventoId + '\')">'
                          + '  <div class="historia-circulo-ring">' + img + '</div>'
                          + '  <span class="historia-circulo-nombre">' + escaparHtml(u.nombre) + '</span>'
                          + '</div>';
            });
            barraEl.innerHTML = histHtml;

            // Guardar las historias del evento para el visor
            window._historiasEvento = respuesta.historias;
        }

        // Renderizar posts
        if (!respuesta.posts || respuesta.posts.length === 0) {
            if (vacioEl) vacioEl.style.display = 'block';
            return;
        }

        var html = '';
        respuesta.posts.forEach(function (post) {
            html += renderizarPost(post);
        });
        if (postsEl) {
            postsEl.innerHTML = html;
            // Inicializar carruseles
            respuesta.posts.forEach(function (post) {
                if (post.imagenes && post.imagenes.length > 1) {
                    inicializarTouchCarrusel(post.id);
                }
            });
        }
    })
    .catch(function () {
        if (cargandoEl) cargandoEl.style.display = 'none';
        if (vacioEl)    vacioEl.style.display    = 'block';
    });
}

function abrirVisorDesdeEvento(idx) {
    // Construir grupos temporales para el visor a partir de las historias del evento
    var historias = window._historiasEvento || [];
    if (!historias[idx]) return;

    // Agrupar por usuario para el visor
    var grupos = [];
    var mapa   = {};
    historias.forEach(function (h) {
        var uid = h.usuario.id;
        if (!mapa[uid]) {
            mapa[uid] = grupos.length;
            grupos.push({ usuario: h.usuario, es_mio: false, historias: [] });
        }
        grupos[mapa[uid]].historias.push(h);
    });

    // Guardar en historialHistorias temporal y abrir visor
    var backupHistorial = historialHistorias;
    historialHistorias  = grupos;

    // Encontrar el grupo que contiene la historia en idx
    var grupoTarget  = 0;
    var contadorHist = 0;
    for (var g = 0; g < grupos.length; g++) {
        if (contadorHist + grupos[g].historias.length > idx) {
            grupoTarget = g;
            break;
        }
        contadorHist += grupos[g].historias.length;
    }

    abrirVisorHistorias(grupoTarget);

    // Restaurar historialHistorias al cerrar
    var origCerrar = cerrarVisorHistorias;
    cerrarVisorHistorias = function () {
        origCerrar();
        historialHistorias  = backupHistorial;
        cerrarVisorHistorias = origCerrar;
    };
}

/**
 * VIBEZ — perfil.js
 *
 * Solo contiene lógica que NO se puede hacer con formularios HTML simples:
 *   1. previsualizarFoto()   → muestra preview local antes de subir la foto
 *   2. buscarAmigos()        → búsqueda dinámica sin recargar la página (AJAX)
 *   3. enviarSolicitud()     → envía solicitud desde los resultados de búsqueda
 *
 * Todo lo demás (guardar datos, guardar mood, aceptar/rechazar amigos)
 * usa formularios HTML normales con action + method + @csrf.
 */

/* ─── Helper: leer el token CSRF del meta tag ─── */
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

    // Validación de tamaño en el cliente (5 MB) para feedback inmediato
    if (archivo.size > 5 * 1024 * 1024) {
        alert('La imagen no puede superar 5 MB.');
        input.value = '';
        return;
    }

    // FileReader lee el archivo localmente y genera una URL temporal (base64)
    const reader = new FileReader();
    reader.onload = function (e) {
        // Reemplazamos el contenido del avatar con la imagen en base64
        const avatar = document.getElementById('avatarPreview');
        avatar.innerHTML = '<img src="' + e.target.result + '" alt="foto" style="width:100%;height:100%;object-fit:cover;">';
    };
    reader.readAsDataURL(archivo);

    // Mostramos el botón "Guardar foto" para que el usuario confirme la subida
    const btnGuardar = document.getElementById('btnGuardarFoto');
    if (btnGuardar) {
        btnGuardar.style.display = 'block';
    }
}

/* ============================================================
   BÚSQUEDA DINÁMICA DE AMIGOS
   Necesita AJAX porque los resultados aparecen sin recargar la página.
   ============================================================ */

let _buscarTimeout = null;  // guardamos el temporizador para cancelarlo si el usuario sigue escribiendo

/**
 * Busca usuarios con un retraso de 350 ms (debounce) para no llamar al servidor
 * en cada tecla pulsada, sino solo cuando el usuario deja de escribir.
 *
 * @param {string} valor - Texto escrito en el campo de búsqueda
 */
function buscarAmigos(valor) {
    // Cancelar la búsqueda anterior si el usuario sigue escribiendo
    clearTimeout(_buscarTimeout);

    const contenedor = document.getElementById('resultadosBusqueda');

    // Si hay menos de 2 caracteres no buscamos (evita resultados masivos)
    if (valor.length < 2) {
        contenedor.style.display = 'none';
        contenedor.innerHTML = '';
        return;
    }

    // Esperamos 350 ms antes de lanzar la petición
    _buscarTimeout = setTimeout(function () {
        // encodeURIComponent convierte caracteres especiales para la URL (ej: ñ → %C3%B1)
        fetch('/api/amigos/buscar?q=' + encodeURIComponent(valor), {
            headers: { 'Accept': 'application/json' },
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            contenedor.innerHTML = '';

            // Si no hay resultados mostramos un mensaje
            if (!data.data || data.data.length === 0) {
                contenedor.innerHTML = '<div class="perfil-busqueda-item" style="color:rgba(15,23,42,0.4);justify-content:center">Sin resultados</div>';
                contenedor.style.display = 'block';
                return;
            }

            // Por cada usuario encontrado creamos un elemento con botón para añadir
            data.data.forEach(function (usuario) {
                const iniciales = (usuario.nombre?.[0] ?? '').toUpperCase() + (usuario.apellido1?.[0] ?? '').toUpperCase();

                // Si tiene foto mostramos la imagen, si no mostramos las iniciales
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
                    '<button class="btn-enviar-solicitud" id="btn-sol-' + usuario.id + '" ' +
                            'onclick="enviarSolicitud(' + usuario.id + ', this)">' +
                        '+ Añadir' +
                    '</button>';

                contenedor.appendChild(div);
            });

            contenedor.style.display = 'block';
        })
        .catch(function () {
            // En caso de error de red no hacemos nada (la búsqueda simplemente no aparece)
        });
    }, 350);
}

/* ============================================================
   ENVIAR SOLICITUD DE AMISTAD
   Está aquí (AJAX) porque el botón "Añadir" se genera dinámicamente
   en los resultados de búsqueda, por lo que no puede ser un formulario fijo.
   ============================================================ */

/**
 * Envía la solicitud de amistad al servidor y actualiza el botón.
 *
 * @param {number}      receptorId - ID del usuario al que queremos añadir
 * @param {HTMLElement} btn        - Referencia al botón pulsado (para desactivarlo)
 */
function enviarSolicitud(receptorId, btn) {
    btn.disabled    = true;
    btn.textContent = '...';

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
        // Actualizamos el texto del botón según la respuesta del servidor
        btn.textContent = data.success ? '✓ Enviado' : '⚠ ' + (data.message ?? 'Error');
        btn.classList.add('btn-enviado');
        btn.disabled = true;
    })
    .catch(function () {
        btn.textContent = 'Error';
        btn.disabled    = false;
    });
}

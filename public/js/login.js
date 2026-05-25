/**
 * login.js — VIBEZ
 * Validación y envío del formulario de inicio de sesión.
 *
 * Reglas del proyecto:
 *   - Eventos directos desde atributos onsubmit/onclick del blade
 *   - Solo getElementById para acceder al DOM
 *   - Variables y comentarios en castellano
 */

/* ============================================================
   MOSTRAR / OCULTAR CONTRASEÑA
   ============================================================ */

/**
 * Alterna la visibilidad del campo contraseña entre 'text' y 'password'.
 * Llamado desde onclick="togglePassword('password', this)" en el blade.
 */
function togglePassword(inputId, boton) {
    var campo     = document.getElementById(inputId);
    var mostrando = campo.type === 'text';
    campo.type    = mostrando ? 'password' : 'text';
    boton.getElementsByClassName('eye-open')[0].style.display   = mostrando ? ''     : 'none';
    boton.getElementsByClassName('eye-closed')[0].style.display = mostrando ? 'none' : '';
    boton.setAttribute('aria-label', mostrando ? 'Mostrar contraseña' : 'Ocultar contraseña');
}

/* ============================================================
   EFECTO RIPPLE (onda desde el punto del clic)
   ============================================================ */

/**
 * Genera una onda circular animada desde el punto de clic.
 * Llamado desde onclick="rippleBtn(event, this)" en el blade.
 */
function rippleBtn(evento, boton) {
    var rect   = boton.getBoundingClientRect();
    var tamano = Math.max(rect.width, rect.height);
    var onda   = document.createElement('span');
    onda.classList.add('ripple');
    onda.style.cssText = 'width:'  + tamano + 'px;height:' + tamano + 'px;' +
                         'left:'  + (evento.clientX - rect.left - tamano / 2) + 'px;' +
                         'top:'   + (evento.clientY - rect.top  - tamano / 2) + 'px';
    boton.appendChild(onda);
    setTimeout(function () { onda.remove(); }, 700);
}

/* ============================================================
   UTILIDADES DE VALIDACIÓN Y UI
   ============================================================ */

/** Comprueba si el email tiene formato válido. */
function esEmailValido(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email);
}

/**
 * Marca un campo con error: borde rojo + mensaje visible.
 * La clase has-error y los estilos de .field-error están en style.css.
 */
function mostrarErrorCampo(fieldId, errorId, mensaje) {
    var campo = document.getElementById(fieldId);
    var error = document.getElementById(errorId);
    if (campo) campo.classList.add('has-error');
    if (error) { error.textContent = mensaje; error.classList.add('visible'); }
}

/** Elimina el error de un campo y oculta el mensaje. */
function limpiarErrorCampo(fieldId, errorId) {
    var campo = document.getElementById(fieldId);
    var error = document.getElementById(errorId);
    if (campo) campo.classList.remove('has-error');
    if (error) { error.textContent = ''; error.classList.remove('visible'); }
}

/**
 * Muestra la alerta global encima del formulario.
 * tipo puede ser 'error', 'warning' o 'success'.
 */
function mostrarAlerta(mensaje, tipo) {
    var tipoAlerta = tipo || 'error';
    var alerta     = document.getElementById('alert-global');
    alerta.textContent = mensaje;
    alerta.className   = 'alert alert-' + tipoAlerta + ' visible';
}

/** Oculta la alerta global. */
function ocultarAlerta() {
    document.getElementById('alert-global').className = 'alert alert-error';
}

/**
 * Sacude el elemento para señalar un error de validación.
 * La animación @keyframes shake está definida en style.css.
 */
function sacudirElemento(el) {
    el.classList.add('shake');
    el.onanimationend = function () {
        el.classList.remove('shake');
        el.onanimationend = null;
    };
}

/* ============================================================
   VALIDACIÓN EN TIEMPO REAL (al salir de cada campo)
   Conectado via onblur="validarXxx()" en el blade.
   ============================================================ */

/** Valida el campo email al perder el foco. */
function validarEmail() {
    var email = document.getElementById('email').value.trim();
    limpiarErrorCampo('field-email', 'error-email');
    if (!email) {
        mostrarErrorCampo('field-email', 'error-email', 'El email es obligatorio');
    } else if (!esEmailValido(email)) {
        mostrarErrorCampo('field-email', 'error-email', 'Introduce un email válido');
    }
}

/** Valida el campo contraseña al perder el foco. */
function validarContrasena() {
    var contrasena = document.getElementById('password').value;
    limpiarErrorCampo('field-password', 'error-password');
    if (!contrasena) {
        mostrarErrorCampo('field-password', 'error-password', 'La contraseña es obligatoria');
    } else if (contrasena.length < 8) {
        mostrarErrorCampo('field-password', 'error-password', 'Mínimo 8 caracteres');
    }
}

/* ============================================================
   ENVÍO DEL FORMULARIO
   Conectado via onsubmit="iniciarSesion(event)" en el blade.
   ============================================================ */

/**
 * Valida el formulario de login en el cliente y, si es correcto,
 * envía la petición POST a /api/login via fetch.
 * En caso de éxito redirige a /home; en caso de error muestra feedback.
 */
function iniciarSesion(evento) {
    evento.preventDefault();

    var email      = document.getElementById('email').value.trim();
    var contrasena = document.getElementById('password').value;
    var recuerdame = document.getElementById('remember') ? document.getElementById('remember').checked : false;
    var boton      = document.getElementById('submitBtn');
    var formulario = document.getElementById('loginForm');
    var esValido   = true;

    /* Limpiar estado anterior */
    limpiarErrorCampo('field-email', 'error-email');
    limpiarErrorCampo('field-password', 'error-password');
    ocultarAlerta();

    /* Validar email */
    if (!email) {
        mostrarErrorCampo('field-email', 'error-email', 'El email es obligatorio');
        esValido = false;
    } else if (!esEmailValido(email)) {
        mostrarErrorCampo('field-email', 'error-email', 'Introduce un email válido');
        esValido = false;
    }

    /* Validar contraseña */
    if (!contrasena) {
        mostrarErrorCampo('field-password', 'error-password', 'La contraseña es obligatoria');
        esValido = false;
    } else if (contrasena.length < 8) {
        mostrarErrorCampo('field-password', 'error-password', 'Mínimo 8 caracteres');
        esValido = false;
    }

    if (!esValido) {
        sacudirElemento(formulario);
        return;
    }

    boton.classList.add('loading');

    var metadatos = document.getElementsByTagName('meta');
    var csrf = '';

    for (var indice = 0; indice < metadatos.length; indice++) {
        if (metadatos[indice].getAttribute('name') === 'csrf-token') {
            csrf = metadatos[indice].getAttribute('content');
            break;
        }
    }

    fetch('/api/login', {
        method:  'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept':       'application/json',
            'X-CSRF-TOKEN': csrf,
        },
        body: JSON.stringify({ email: email, password: contrasena, remember: recuerdame }),
    })
    .then(function (respuesta) { return respuesta.json(); })
    .then(function (datos) {
        if (datos.success) {
            /* Éxito: mostrar confirmación verde y redirigir */
            boton.innerHTML = '<span class="btn-text success-check">' +
                '<svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="white" ' +
                'stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">' +
                '<polyline points="20 6 9 17 4 12"></polyline></svg>' +
                '¡Sesión iniciada!</span>';
            boton.classList.remove('loading');
            boton.style.background = 'linear-gradient(135deg,#22C55E,#16A34A)';
            setTimeout(function () {
                document.body.style.transition = 'opacity 0.35s ease';
                document.body.style.opacity    = '0';
                setTimeout(function () {
                    var params      = new URLSearchParams(window.location.search);
                    var redirectUrl = params.get('redirect');
                    var destino     = '/home';
                    if (redirectUrl) {
                        try {
                            var parsed = new URL(redirectUrl);
                            if (parsed.origin === window.location.origin) {
                                destino = parsed.pathname + parsed.search + parsed.hash;
                            }
                        } catch (ex) { /* URL inválida, usar /home */ }
                    }
                    window.location.href = destino;
                }, 360);
            }, 750);
        } else {
            /* Error del servidor */
            boton.classList.remove('loading');

            /* Cuenta pendiente de verificación */
            if (datos.unverified) {
                mostrarAlerta(datos.message, 'warning');
                return;
            }

            /* Errores de campo individuales (de validación PHP) */
            if (datos.errors) {
                var claves = Object.keys(datos.errors);
                for (var indiceClave = 0; indiceClave < claves.length; indiceClave++) {
                    mostrarErrorCampo(
                        'field-' + claves[indiceClave],
                        'error-' + claves[indiceClave],
                        datos.errors[claves[indiceClave]][0]
                    );
                }
            }

            mostrarAlerta(datos.message || 'Credenciales incorrectas. Inténtalo de nuevo.');
            sacudirElemento(formulario);
        }
    })
    .catch(function () {
        boton.classList.remove('loading');
        mostrarAlerta('Error de conexión. Verifica tu red e inténtalo de nuevo.');
    });
}

/* ============================================================
   FORMULARIO RECUPERAR CONTRASEÑA (forgot-password)
   ============================================================ */

/** Valida el campo email al perder el foco en el form de recuperación. */
function validarEmailRecupera() {
    var email = document.getElementById('email').value.trim();
    limpiarErrorCampo('field-email', 'error-email');
    if (!email) {
        mostrarErrorCampo('field-email', 'error-email', 'El correo es obligatorio');
    } else if (!esEmailValido(email)) {
        mostrarErrorCampo('field-email', 'error-email', 'Introduce un correo válido (ej: nombre@dominio.com)');
    }
}

/**
 * Valida el formulario de recuperación antes de enviarlo.
 * Si hay errores los muestra y cancela el submit.
 * Si es válido activa el estado cargando en el botón.
 */
function enviarFormRecupera(evento) {
    var email      = document.getElementById('email').value.trim();
    var formulario = document.getElementById('forgot-form');
    var boton      = document.getElementById('btn-recupera');
    var esValido   = true;

    limpiarErrorCampo('field-email', 'error-email');

    if (!email) {
        mostrarErrorCampo('field-email', 'error-email', 'El correo es obligatorio');
        esValido = false;
    } else if (!esEmailValido(email)) {
        mostrarErrorCampo('field-email', 'error-email', 'Introduce un correo válido (ej: nombre@dominio.com)');
        esValido = false;
    }

    if (!esValido) {
        evento.preventDefault();
        sacudirElemento(formulario);
        return;
    }

    boton.classList.add('loading');
}

/* ============================================================
   AUTENTICACIÓN CON GOOGLE (Google Identity Services)
   ============================================================ */

/**
 * Inicializa el botón de Google Identity Services (GIS).
 *
 * GIS carga su librería con async+defer, por lo que este callback
 * se ejecuta justo cuando la librería termina de cargarse.
 * Lee el client_id del atributo data-client-id del contenedor del botón
 * para no hardcodear el valor aquí.
 */
function iniciarGoogleAuth() {
    var btnContenedor = document.getElementById('google-signin-btn');
    if (!btnContenedor) { return; }

    var clientId = btnContenedor.getAttribute('data-client-id');
    if (!clientId) { return; }

    google.accounts.id.initialize({
        client_id: clientId,
        callback:  manejarGoogleCredential,
        ux_mode:   'popup',
    });

    google.accounts.id.renderButton(btnContenedor, {
        theme: 'outline',
        size:  'large',
        type:  'standard',
        text:  'signin_with',
        shape: 'rectangular',
        width: 300,
    });
}

/* Google GIS llama a window.onGoogleLibraryLoad cuando su script ha cargado. */
window.onGoogleLibraryLoad = iniciarGoogleAuth;

/**
 * Callback que recibe Google tras la aprobación del usuario.
 * Envía el credential (JWT) al backend y redirige si el login es correcto.
 */
function manejarGoogleCredential(respuestaGoogle) {
    var csrf = '';
    var metas = document.getElementsByTagName('meta');
    for (var i = 0; i < metas.length; i++) {
        if (metas[i].getAttribute('name') === 'csrf-token') {
            csrf = metas[i].getAttribute('content');
            break;
        }
    }

    fetch('/api/google-auth', {
        method:  'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept':       'application/json',
            'X-CSRF-TOKEN': csrf,
        },
        body: JSON.stringify({ credential: respuestaGoogle.credential }),
    })
    .then(function (r) { return r.json(); })
    .then(function (datos) {
        if (datos.success) {
            document.body.style.transition = 'opacity 0.35s ease';
            document.body.style.opacity    = '0';
            setTimeout(function () { window.location.href = '/home'; }, 360);
        } else {
            mostrarAlerta(datos.message || 'No se pudo iniciar sesión con Google. Inténtalo de nuevo.');
        }
    })
    .catch(function () {
        mostrarAlerta('Error de conexión al iniciar sesión con Google.');
    });
}

/* ============================================================
   AUTENTICACIÓN CON APPLE (Sign in with Apple — JS SDK)
   ============================================================ */

/**
 * Abre el popup de Apple Sign In y envía el id_token al backend.
 *
 * IMPORTANTE — requisito de Apple:
 *   Apple exige que el dominio registrado en el Service ID use HTTPS.
 *   En localhost (WAMP sin HTTPS) el popup no se abrirá. Para probarlo
 *   necesitas un dominio real con certificado SSL (o usar ngrok).
 *
 * Llamado desde onclick="iniciarSesionApple(event, this)" en el blade.
 */
function iniciarSesionApple(evento, boton) {
    var clientId = boton.getAttribute('data-apple-client-id') || '';
    if (!clientId) {
        mostrarAlerta('Apple Sign-In no está configurado en este servidor.', 'warning');
        return;
    }

    /* AppleID.auth se carga desde el SDK de Apple (appleid.auth.js). */
    if (typeof AppleID === 'undefined' || !AppleID.auth) {
        mostrarAlerta('El SDK de Apple no ha cargado. Recarga la página e inténtalo de nuevo.', 'warning');
        return;
    }

    AppleID.auth.init({
        clientId:    clientId,
        scope:       'name email',
        redirectURI: window.location.origin,
        usePopup:    true,
    });

    AppleID.auth.signIn()
        .then(function (respuestaApple) {
            manejarRespuestaApple(respuestaApple);
        })
        .catch(function (error) {
            /* El usuario cerró el popup: no mostramos error. */
            if (error && error.error !== 'popup_closed_by_user') {
                mostrarAlerta('No se pudo iniciar sesión con Apple. Inténtalo de nuevo.');
            }
        });
}

/**
 * Procesa la respuesta del SDK de Apple y la envía al backend.
 * Apple solo devuelve nombre y email en el PRIMER login; después solo devuelve id_token.
 */
function manejarRespuestaApple(respuestaApple) {
    var idToken  = respuestaApple.authorization.id_token;
    var userName = '';

    /* El objeto 'user' solo llega la primera vez que el usuario aprueba. */
    if (respuestaApple.user) {
        var nombre   = respuestaApple.user.name || {};
        var partes   = [nombre.firstName || '', nombre.lastName || ''];
        userName = partes.join(' ').trim();
    }

    var csrf = '';
    var metas = document.getElementsByTagName('meta');
    for (var i = 0; i < metas.length; i++) {
        if (metas[i].getAttribute('name') === 'csrf-token') {
            csrf = metas[i].getAttribute('content');
            break;
        }
    }

    fetch('/api/apple-auth', {
        method:  'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept':       'application/json',
            'X-CSRF-TOKEN': csrf,
        },
        body: JSON.stringify({ id_token: idToken, user_name: userName }),
    })
    .then(function (r) { return r.json(); })
    .then(function (datos) {
        if (datos.success) {
            document.body.style.transition = 'opacity 0.35s ease';
            document.body.style.opacity    = '0';
            setTimeout(function () { window.location.href = '/home'; }, 360);
        } else {
            mostrarAlerta(datos.message || 'No se pudo iniciar sesión con Apple. Inténtalo de nuevo.');
        }
    })
    .catch(function () {
        mostrarAlerta('Error de conexión al iniciar sesión con Apple.');
    });
}

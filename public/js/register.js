/**
 * register.js — VIBEZ
 * Validación y envío del formulario de registro.
 * También gestiona Google Identity Services y flatpickr.
 *
 * Reglas del proyecto:
 *   - Eventos directos desde atributos onsubmit/onclick/onchange del blade
 *   - Solo getElementById para acceder al DOM
 *   - Variables y comentarios en castellano
 *
 * NOTA: Este script se carga al final del <body> (en @section('scripts')),
 * por lo que el DOM ya está construido al ejecutarse. flatpickr se inicializa
 * aquí directamente, sin necesidad de esperar ningún evento de carga.
 */

/* ============================================================
   INICIALIZACIÓN DE FLATPICKR
   El script CDN de flatpickr se carga antes que este archivo,
   así que ya está disponible al ejecutarse estas líneas.
   ============================================================ */

flatpickr(document.getElementById('fecha_nacimiento'), {
    locale:        'es',
    dateFormat:    'Y-m-d',
    altInput:      false,
    maxDate:       'today',
    disableMobile: false,
    /** Limpia el error al seleccionar una fecha */
    onChange: function () {
        limpiarErrorCampo('field-fecha_nacimiento', 'error-fecha_nacimiento');
    },
    /** Valida el campo al cerrar el calendario (equivalente a onblur) */
    onClose: function () {
        validarFechaNacimiento();
    },
});

/* ============================================================
   GOOGLE IDENTITY SERVICES
   El SDK llama window.onGoogleLibraryLoad automáticamente tras cargar.
   El client_id se lee del data-attribute del div en el blade.
   ============================================================ */

/** Renderiza el botón oficial de Google cuando el SDK está listo. */
window.onGoogleLibraryLoad = function () {
    var botonGoogle = document.getElementById('google-signin-btn');
    if (!botonGoogle) return;

    var clientId = botonGoogle.dataset.clientId;
    if (!clientId) return;

    google.accounts.id.initialize({
        client_id:             clientId,
        callback:              window.handleGoogleCredential,
        auto_select:           false,
        cancel_on_tap_outside: true,
    });

    /* Ajustar el ancho al contenedor padre */
    var contenedor = botonGoogle.closest('.google-btn-wrapper');
    var ancho      = contenedor ? contenedor.offsetWidth : 200;

    google.accounts.id.renderButton(botonGoogle, {
        theme:          'outline',
        size:           'large',
        width:          Math.max(ancho, 200),
        text:           'continue_with',
        shape:          'rectangular',
        logo_alignment: 'left',
        locale:         'es',
    });
};

/**
 * Recibe la credencial JWT de Google y la envía a /api/auth/google
 * para verificación en el servidor. En caso de éxito, redirige al home.
 */
window.handleGoogleCredential = function (respuestaGoogle) {
    var alerta = document.getElementById('alert-global');
    var metadatos = document.getElementsByTagName('meta');
    var csrf   = '';

    for (var indice = 0; indice < metadatos.length; indice++) {
        if (metadatos[indice].getAttribute('name') === 'csrf-token') {
            csrf = metadatos[indice].getAttribute('content');
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
    .then(function (respuesta) { return respuesta.json(); })
    .then(function (datos) {
        if (datos.success) {
            document.body.style.transition = 'opacity 0.35s ease';
            document.body.style.opacity    = '0';
            setTimeout(function () { window.location.href = '/home'; }, 360);
        } else {
            mostrarAlerta(datos.message || 'Error al iniciar sesión con Google.');
        }
    })
    .catch(function () {
        mostrarAlerta('Error de conexión. Inténtalo de nuevo.');
    });
};

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
   HINT DEL TIPO DE CUENTA
   Conectado via onchange="cambiarTipoCuenta(this)" en el blade.
   ============================================================ */

/**
 * Actualiza el texto de ayuda bajo el selector de tipo de cuenta.
 * Las empresas necesitan aprobación del admin; los clientes tienen acceso inmediato.
 */
function cambiarTipoCuenta(selector) {
    var campo = document.getElementById('field-tipo_cuenta');
    if (campo) campo.classList.toggle('has-value', selector.value !== '');
    var pista = document.getElementById('hint-tipo_cuenta');
    if (!pista) return;
    if (selector.value === 'empresa') {
        pista.textContent = 'Requiere aprobación del administrador.';
        pista.style.color = '#D97706';
    } else if (selector.value === 'cliente') {
        pista.textContent = 'Acceso inmediato tras el registro.';
        pista.style.color = '#059669';
    } else {
        pista.textContent = '';
    }
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
   La fecha usa flatpickr onClose (ver inicialización arriba).
   ============================================================ */

/** Valida el campo nombre al perder el foco. */
function validarNombre() {
    var nombre = document.getElementById('nombre').value.trim();
    limpiarErrorCampo('field-nombre', 'error-nombre');
    if (!nombre) {
        mostrarErrorCampo('field-nombre', 'error-nombre', 'El nombre es obligatorio');
    } else if (nombre.length < 2) {
        mostrarErrorCampo('field-nombre', 'error-nombre', 'Mínimo 2 caracteres');
    }
}

/** Valida el primer apellido al perder el foco. */
function validarApellido1() {
    var apellido1 = document.getElementById('apellido1').value.trim();
    limpiarErrorCampo('field-apellido1', 'error-apellido1');
    if (!apellido1) {
        mostrarErrorCampo('field-apellido1', 'error-apellido1', 'El primer apellido es obligatorio');
    } else if (apellido1.length < 2) {
        mostrarErrorCampo('field-apellido1', 'error-apellido1', 'Mínimo 2 caracteres');
    }
}

/** Valida el segundo apellido al perder el foco. */
function validarApellido2() {
    var apellido2 = document.getElementById('apellido2').value.trim();
    limpiarErrorCampo('field-apellido2', 'error-apellido2');
    if (!apellido2) {
        mostrarErrorCampo('field-apellido2', 'error-apellido2', 'El segundo apellido es obligatorio');
    } else if (apellido2.length < 2) {
        mostrarErrorCampo('field-apellido2', 'error-apellido2', 'Mínimo 2 caracteres');
    }
}

/** Valida el email al perder el foco. */
function validarEmail() {
    var email = document.getElementById('email').value.trim();
    limpiarErrorCampo('field-email', 'error-email');
    if (!email) {
        mostrarErrorCampo('field-email', 'error-email', 'El email es obligatorio');
    } else if (!esEmailValido(email)) {
        mostrarErrorCampo('field-email', 'error-email', 'Introduce un email válido');
    }
}

/**
 * Valida la contraseña al perder el foco.
 * También re-valida la confirmación si ya tiene contenido,
 * para que el error de "no coinciden" se actualice al instante.
 */
function validarContrasena() {
    var contrasena = document.getElementById('password').value;
    limpiarErrorCampo('field-password', 'error-password');
    if (!contrasena) {
        mostrarErrorCampo('field-password', 'error-password', 'La contraseña es obligatoria');
    } else if (contrasena.length < 8) {
        mostrarErrorCampo('field-password', 'error-password', 'Mínimo 8 caracteres');
    }
    /* Si la confirmación ya tiene valor, actualizarla también */
    var confirmacion = document.getElementById('password_confirmation');
    if (confirmacion && confirmacion.value) {
        validarConfirmacion();
    }
}

/** Valida que la confirmación de contraseña coincida al perder el foco. */
function validarConfirmacion() {
    var contrasena   = document.getElementById('password').value;
    var confirmacion = document.getElementById('password_confirmation').value;
    limpiarErrorCampo('field-password_confirmation', 'error-password_confirmation');
    if (!confirmacion) {
        mostrarErrorCampo('field-password_confirmation', 'error-password_confirmation', 'Confirma tu contraseña');
    } else if (contrasena && contrasena !== confirmacion) {
        mostrarErrorCampo('field-password_confirmation', 'error-password_confirmation', 'Las contraseñas no coinciden');
    }
}

/** Valida la fecha de nacimiento (llamada también por flatpickr onClose). */
function validarFechaNacimiento() {
    var fechaNacimiento = document.getElementById('fecha_nacimiento').value;
    limpiarErrorCampo('field-fecha_nacimiento', 'error-fecha_nacimiento');
    if (!fechaNacimiento) {
        mostrarErrorCampo('field-fecha_nacimiento', 'error-fecha_nacimiento', 'La fecha de nacimiento es obligatoria');
        return;
    }
    var hoy  = new Date();
    var nac  = new Date(fechaNacimiento);
    var edad = hoy.getFullYear() - nac.getFullYear() -
        (hoy < new Date(hoy.getFullYear(), nac.getMonth(), nac.getDate()) ? 1 : 0);
    if (edad < 14) {
        mostrarErrorCampo('field-fecha_nacimiento', 'error-fecha_nacimiento', 'Debes tener al menos 14 años');
    } else if (edad > 120) {
        mostrarErrorCampo('field-fecha_nacimiento', 'error-fecha_nacimiento', 'Fecha no válida');
    }
}

/** Valida el teléfono al perder el foco. */
function validarTelefono() {
    var telefono = document.getElementById('telefono').value.trim();
    limpiarErrorCampo('field-telefono', 'error-telefono');
    if (!telefono) {
        mostrarErrorCampo('field-telefono', 'error-telefono', 'El teléfono es obligatorio');
    } else if (!/^\+?[\d\s\-]{7,20}$/.test(telefono)) {
        mostrarErrorCampo('field-telefono', 'error-telefono', 'Introduce un teléfono válido');
    }
}

/** Valida el teléfono de empresa (campo opcional — solo si se ha rellenado). */
function validarTelefonoEmpresa() {
    var campo = document.getElementById('telefono_empresa');
    var error = document.getElementById('error-telefono_empresa');
    if (!campo || !error) return;
    limpiarErrorCampo('field-telefono_empresa', 'error-telefono_empresa');
    var valor = campo.value.trim();
    if (valor && !/^\+?[\d\s\-]{7,20}$/.test(valor)) {
        mostrarErrorCampo('field-telefono_empresa', 'error-telefono_empresa', 'Introduce un teléfono válido');
    }
}

/** Valida el tipo de cuenta al cambiar la selección. */
function validarTipoCuenta() {
    var tipoCuenta = document.getElementById('tipo_cuenta').value;
    limpiarErrorCampo('field-tipo_cuenta', 'error-tipo_cuenta');
    if (!tipoCuenta) {
        mostrarErrorCampo('field-tipo_cuenta', 'error-tipo_cuenta', 'Selecciona el tipo de cuenta');
    }
}

/* ============================================================
   ENVÍO DEL FORMULARIO
   Conectado via onsubmit="registrar(event)" en el blade.
   ============================================================ */

/**
 * Valida todos los campos del formulario de registro y, si son correctos,
 * envía la petición POST a /api/register via fetch.
 *   - Si la respuesta es 'active': redirige al home directamente.
 *   - Si la respuesta es 'pending': muestra mensaje de aprobación pendiente.
 *   - En caso de error: muestra los mensajes de cada campo.
 */
function registrar(evento) {
    evento.preventDefault();

    /* Recoger valores de los campos */
    var nombre          = document.getElementById('nombre').value.trim();
    var apellido1       = document.getElementById('apellido1').value.trim();
    var apellido2       = document.getElementById('apellido2').value.trim();
    var email           = document.getElementById('email').value.trim();
    var contrasena      = document.getElementById('password').value;
    var confirmacion    = document.getElementById('password_confirmation').value;
    var fechaNacimiento = document.getElementById('fecha_nacimiento').value;
    var telefono        = document.getElementById('telefono').value.trim();
    var tipoCuenta      = document.getElementById('tipo_cuenta').value;
    var boton           = document.getElementById('submitBtn');
    var formulario      = document.getElementById('registerForm');
    var esValido        = true;

    /* Limpiar errores anteriores */
    var camposLimpiar = [
        ['field-nombre',                'error-nombre'],
        ['field-apellido1',             'error-apellido1'],
        ['field-apellido2',             'error-apellido2'],
        ['field-email',                 'error-email'],
        ['field-password',              'error-password'],
        ['field-password_confirmation', 'error-password_confirmation'],
        ['field-fecha_nacimiento',      'error-fecha_nacimiento'],
        ['field-telefono',              'error-telefono'],
        ['field-tipo_cuenta',           'error-tipo_cuenta'],
        ['field-acepta_terminos',       'error-acepta_terminos'],
    ];
    for (var indiceCampo = 0; indiceCampo < camposLimpiar.length; indiceCampo++) {
        limpiarErrorCampo(camposLimpiar[indiceCampo][0], camposLimpiar[indiceCampo][1]);
    }
    ocultarAlerta();

    /* Validar nombre */
    if (!nombre) {
        mostrarErrorCampo('field-nombre', 'error-nombre', 'El nombre es obligatorio');
        esValido = false;
    } else if (nombre.length < 2) {
        mostrarErrorCampo('field-nombre', 'error-nombre', 'Mínimo 2 caracteres');
        esValido = false;
    }

    /* Validar primer apellido */
    if (!apellido1) {
        mostrarErrorCampo('field-apellido1', 'error-apellido1', 'El primer apellido es obligatorio');
        esValido = false;
    } else if (apellido1.length < 2) {
        mostrarErrorCampo('field-apellido1', 'error-apellido1', 'Mínimo 2 caracteres');
        esValido = false;
    }

    /* Validar segundo apellido — solo obligatorio para clientes */
    if (tipoCuenta !== 'empresa') {
        if (!apellido2) {
            mostrarErrorCampo('field-apellido2', 'error-apellido2', 'El segundo apellido es obligatorio');
            esValido = false;
        } else if (apellido2.length < 2) {
            mostrarErrorCampo('field-apellido2', 'error-apellido2', 'Mínimo 2 caracteres');
            esValido = false;
        }
    }

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

    /* Validar confirmación de contraseña */
    if (!confirmacion) {
        mostrarErrorCampo('field-password_confirmation', 'error-password_confirmation', 'Confirma tu contraseña');
        esValido = false;
    } else if (contrasena !== confirmacion) {
        mostrarErrorCampo('field-password_confirmation', 'error-password_confirmation', 'Las contraseñas no coinciden');
        esValido = false;
    }

    /* Validar fecha de nacimiento — mínimo 14 años solo para clientes */
    if (!fechaNacimiento) {
        mostrarErrorCampo('field-fecha_nacimiento', 'error-fecha_nacimiento', 'La fecha de nacimiento es obligatoria');
        esValido = false;
    } else if (tipoCuenta !== 'empresa') {
        var hoy  = new Date();
        var nac  = new Date(fechaNacimiento);
        var edad = hoy.getFullYear() - nac.getFullYear() -
            (hoy < new Date(hoy.getFullYear(), nac.getMonth(), nac.getDate()) ? 1 : 0);
        if (edad < 14) {
            mostrarErrorCampo('field-fecha_nacimiento', 'error-fecha_nacimiento', 'Debes tener al menos 14 años');
            esValido = false;
        } else if (edad > 120) {
            mostrarErrorCampo('field-fecha_nacimiento', 'error-fecha_nacimiento', 'Fecha no válida');
            esValido = false;
        }
    }

    /* Validar teléfono */
    if (!telefono) {
        mostrarErrorCampo('field-telefono', 'error-telefono', 'El teléfono es obligatorio');
        esValido = false;
    } else if (!/^\+?[\d\s\-]{7,20}$/.test(telefono)) {
        mostrarErrorCampo('field-telefono', 'error-telefono', 'Introduce un teléfono válido');
        esValido = false;
    }

    /* Validar tipo de cuenta */
    if (!tipoCuenta) {
        mostrarErrorCampo('field-tipo_cuenta', 'error-tipo_cuenta', 'Selecciona el tipo de cuenta');
        esValido = false;
    }

    /* Validar campos de empresa cuando corresponda */
    if (tipoCuenta === 'empresa') {
        var nombreEmpresaEl  = document.getElementById('nombre_empresa');
        var nifCifEl         = document.getElementById('nif_cif');
        var tipoPromotorEl   = document.getElementById('tipo_promotor');

        if (!nombreEmpresaEl || !nombreEmpresaEl.value.trim()) {
            mostrarErrorCampo('field-nombre_empresa', 'error-nombre_empresa', 'El nombre de empresa es obligatorio');
            esValido = false;
        }
        if (!nifCifEl || !nifCifEl.value.trim()) {
            mostrarErrorCampo('field-nif_cif', 'error-nif_cif', 'El NIF/CIF es obligatorio');
            esValido = false;
        } else if (!/^[A-Za-z0-9]{7,9}$/.test(nifCifEl.value.trim())) {
            mostrarErrorCampo('field-nif_cif', 'error-nif_cif', 'Formato inválido (ej: B12345678)');
            esValido = false;
        }
        if (!tipoPromotorEl || !tipoPromotorEl.value) {
            mostrarErrorCampo('field-tipo_promotor', 'error-tipo_promotor', 'Selecciona el tipo de promotor');
            esValido = false;
        }

        /* Teléfono empresa es opcional, pero si se rellena debe tener formato válido */
        var telefonoEmpresaEl = document.getElementById('telefono_empresa');
        if (telefonoEmpresaEl && telefonoEmpresaEl.value.trim()) {
            if (!/^\+?[\d\s\-]{7,20}$/.test(telefonoEmpresaEl.value.trim())) {
                mostrarErrorCampo('field-telefono_empresa', 'error-telefono_empresa', 'Introduce un teléfono válido');
                esValido = false;
            }
        }
    }

    /* Validar aceptación de términos */
    var checkTerminos = document.getElementById('acepta_terminos');
    if (!checkTerminos || !checkTerminos.checked) {
        mostrarErrorCampo('field-acepta_terminos', 'error-acepta_terminos', 'Debes aceptar las condiciones para continuar');
        esValido = false;
    }

    if (!esValido) {
        sacudirElemento(formulario);
        return;
    }

    /* Enviar al servidor */
    boton.classList.add('loading');

    var metadatos = document.getElementsByTagName('meta');
    var csrf = '';

    for (var indice = 0; indice < metadatos.length; indice++) {
        if (metadatos[indice].getAttribute('name') === 'csrf-token') {
            csrf = metadatos[indice].getAttribute('content');
            break;
        }
    }

    /* Construir cuerpo de la petición: campos comunes + empresa si aplica */
    var cuerpo = {
        nombre:                nombre,
        apellido1:             apellido1,
        apellido2:             apellido2,
        email:                 email,
        password:              contrasena,
        password_confirmation: confirmacion,
        fecha_nacimiento:      fechaNacimiento,
        telefono:              telefono,
        tipo_cuenta:           tipoCuenta,
    };

    if (tipoCuenta === 'empresa') {
        var nombreEmpresaEl   = document.getElementById('nombre_empresa');
        var razonSocialEl     = document.getElementById('razon_social');
        var nifCifEl          = document.getElementById('nif_cif');
        var tipoPromotorEl    = document.getElementById('tipo_promotor');
        var telefonoEmpresaEl = document.getElementById('telefono_empresa');
        var descripcionEl     = document.getElementById('descripcion');
        var sitioWebEl        = document.getElementById('sitio_web');
        cuerpo.nombre_empresa   = nombreEmpresaEl   ? nombreEmpresaEl.value.trim()   : '';
        cuerpo.razon_social     = razonSocialEl     ? razonSocialEl.value.trim()     : '';
        cuerpo.nif_cif          = nifCifEl          ? nifCifEl.value.trim()          : '';
        cuerpo.tipo_promotor    = tipoPromotorEl    ? tipoPromotorEl.value           : '';
        cuerpo.telefono_empresa = telefonoEmpresaEl ? telefonoEmpresaEl.value.trim() : '';
        cuerpo.descripcion      = descripcionEl     ? descripcionEl.value.trim()     : '';
        cuerpo.sitio_web        = sitioWebEl        ? sitioWebEl.value.trim()        : '';
    }

    fetch('/api/register', {
        method:  'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept':       'application/json',
            'X-CSRF-TOKEN': csrf,
        },
        body: JSON.stringify(cuerpo),
    })
    .then(function (respuesta) { return respuesta.json(); })
    .then(function (datos) {
        if (datos.success && datos.status === 'active') {
            /* Cliente registrado: acceso inmediato, redirigir al home */
            document.body.style.transition = 'opacity 0.35s ease';
            document.body.style.opacity    = '0';
            setTimeout(function () { window.location.href = '/home'; }, 360);
            return;
        }

        if (datos.success && datos.status === 'pending') {
            /* Empresa registrada: pendiente de aprobación por el admin */
            boton.classList.remove('loading');
            formulario.style.display = 'none';

            /* Ocultar fila de botones (id="btnRow" en el blade) */
            var filaBotones = document.getElementById('btnRow');
            if (filaBotones) filaBotones.remove();

            /* Reemplazar cabecera del formulario con mensaje de espera */
            var cabeceraForm   = document.getElementById('formHeader');
            var panelPendiente = document.createElement('div');
            panelPendiente.innerHTML =
                '<div class="pending-inline">' +
                '<div class="pending-inline-icon">' +
                '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" ' +
                'stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">' +
                '<path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/>' +
                '<path d="M12 6v6l4 2"/></svg></div>' +
                '<h2 class="pending-inline-title">Solicitud enviada</h2>' +
                '<p class="pending-inline-text">Tu cuenta está <strong>pendiente de aprobación</strong> ' +
                'por el administrador. Recibirás un correo cuando esté activa.</p>' +
                '<a href="/login" class="pending-back-link">← Volver al login</a>' +
                '</div>';
            if (cabeceraForm) cabeceraForm.replaceWith(panelPendiente);
            return;
        }

        /* Error del servidor */
        boton.classList.remove('loading');
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
        mostrarAlerta(datos.message || 'No se pudo crear la cuenta. Revisa los datos.');
        sacudirElemento(formulario);
    })
    .catch(function () {
        boton.classList.remove('loading');
        mostrarAlerta('Error de conexión. Verifica tu red e inténtalo de nuevo.');
    });
}

/* ============================================================
   TABS CLIENTE / EMPRESA
   ============================================================ */

/**
 * Cambia el tab activo (Cliente / Empresa) y ajusta los campos visibles.
 * Llamado desde onclick="seleccionarTab('empresa', this)" en el blade.
 * @param {string}      tipo  - 'cliente' | 'empresa'
 * @param {HTMLElement} elTab - Elemento tab pulsado
 */
function seleccionarTab(tipo, elTab) {
    var tabs = document.querySelectorAll('.auth-tab');
    for (var i = 0; i < tabs.length; i++) tabs[i].classList.remove('active');
    elTab.classList.add('active');

    var select = document.getElementById('tipo_cuenta');
    select.value = tipo;
    cambiarTipoCuenta(select);

    var esEmpresa = tipo === 'empresa';

    var empresaWrap = document.getElementById('empresa-field-wrap');
    if (empresaWrap) empresaWrap.style.display = esEmpresa ? 'block' : 'none';

    var campoApellido2 = document.getElementById('field-apellido2');
    if (campoApellido2) campoApellido2.style.display = esEmpresa ? 'none' : 'flex';

    var moodPick = document.querySelector('.auth-mood-pick');
    if (moodPick) moodPick.style.display = esEmpresa ? 'none' : 'block';
}

/* Auto-seleccionar tab empresa si la URL viene con ?tipo=empresa */
(function () {
    var params = new URLSearchParams(window.location.search);
    if (params.get('tipo') === 'empresa') {
        var tabEmpresa = document.querySelector('[data-tipo="empresa"]');
        if (tabEmpresa) seleccionarTab('empresa', tabEmpresa);
    }
})();

/* Inicializar tab "cliente" por defecto */
(function () {
    var select = document.getElementById('tipo_cuenta');
    if (select) { select.value = 'cliente'; cambiarTipoCuenta(select); }
})();

/* ============================================================
   SELECTOR CUSTOM DE TIPO DE PROMOTOR
   ============================================================ */

/** Abre o cierra el selector de tipo de promotor. */
function toggleTpCsel() {
    var menu    = document.getElementById('tp-csel-menu');
    var trigger = document.getElementById('tp-csel-trigger');
    var arrow   = document.getElementById('tp-csel-arrow');
    var open    = menu.classList.contains('tp-csel-open');
    menu.classList.toggle('tp-csel-open', !open);
    trigger.classList.toggle('tp-csel-active', !open);
    arrow.style.transform = open ? 'rotate(0deg)' : 'rotate(180deg)';
}

/**
 * Selecciona un tipo de promotor y cierra el dropdown.
 * @param {string} val   - Valor interno
 * @param {string} label - Texto visible
 */
function pickTpCsel(val, label) {
    document.getElementById('tipo_promotor').value       = val;
    document.getElementById('tp-csel-label').textContent = label;
    document.getElementById('tp-csel-label').style.color = '';
    var menu    = document.getElementById('tp-csel-menu');
    var trigger = document.getElementById('tp-csel-trigger');
    var arrow   = document.getElementById('tp-csel-arrow');
    if (menu)    menu.classList.remove('tp-csel-open');
    if (trigger) trigger.classList.remove('tp-csel-active');
    if (arrow)   arrow.style.transform = 'rotate(0deg)';
    var error = document.getElementById('error-tipo_promotor');
    if (error) error.textContent = '';

    /* Cerrar al hacer clic fuera */
    document.onclick = function(e) {
        var csel = document.getElementById('tp-csel');
        if (csel && !csel.contains(e.target)) {
            var m = document.getElementById('tp-csel-menu');
            var t = document.getElementById('tp-csel-trigger');
            var a = document.getElementById('tp-csel-arrow');
            if (m) { m.classList.remove('tp-csel-open'); t.classList.remove('tp-csel-active'); a.style.transform = 'rotate(0deg)'; }
        }
    };
}

/**
 * Valida el formato del campo NIF/CIF al perder el foco.
 * @returns {boolean}
 */
function validarNifCif() {
    var campo = document.getElementById('nif_cif');
    var error = document.getElementById('error-nif_cif');
    if (!campo || !error) return true;
    var val = campo.value.trim();
    if (!val) { error.textContent = 'El NIF/CIF es obligatorio'; return false; }
    if (!/^[A-Za-z0-9]{7,9}$/.test(val)) { error.textContent = 'Formato inválido (ej: B12345678)'; return false; }
    error.textContent = '';
    return true;
}

/**
 * Valida que se haya seleccionado el tipo de promotor.
 * @returns {boolean}
 */
function validarTipoPromotor() {
    var campo = document.getElementById('tipo_promotor');
    var error = document.getElementById('error-tipo_promotor');
    if (!campo || !error) return true;
    if (!campo.value) { error.textContent = 'Selecciona el tipo de promotor'; return false; }
    error.textContent = '';
    return true;
}

/* ============================================================
   BARRA DE FORTALEZA DE CONTRASEÑA
   ============================================================ */

/**
 * Actualiza la barra visual de fortaleza al escribir la contraseña.
 * @param {string} valor - Valor actual del campo contraseña
 */
function actualizarFortaleza(valor) {
    var barra    = document.getElementById('strength-bar');
    var etiqueta = document.getElementById('strength-label');
    if (!barra) return;
    var f = 0;
    if (valor.length >= 8)          f += 25;
    if (/[A-Z]/.test(valor))        f += 25;
    if (/[0-9]/.test(valor))        f += 25;
    if (/[^A-Za-z0-9]/.test(valor)) f += 25;
    barra.style.width = f + '%';
    var colores = { 25: '#ef4444', 50: '#f59e0b', 75: '#3b82f6', 100: '' };
    var niveles = { 25: 'Débil',   50: 'Regular', 75: 'Fuerte',  100: 'Excelente' };
    barra.style.background = colores[f] || '';
    etiqueta.textContent   = f > 0 ? (niveles[f] || '') : '';
}

/* ============================================================
   AUTENTICACIÓN CON APPLE (Sign in with Apple — JS SDK)
   ============================================================ */

/**
 * Abre el popup de Apple Sign In y envía el id_token al backend.
 * Ver login.js para la documentación completa del flujo.
 * Llamado desde onclick="iniciarSesionApple(event, this)" en el blade.
 */
function iniciarSesionApple(evento, boton) {
    var clientId = boton.getAttribute('data-apple-client-id') || '';
    if (!clientId) {
        mostrarAlerta('Apple Sign-In no está configurado en este servidor.', 'warning');
        return;
    }

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
            if (error && error.error !== 'popup_closed_by_user') {
                mostrarAlerta('No se pudo iniciar sesión con Apple. Inténtalo de nuevo.');
            }
        });
}

/**
 * Procesa la respuesta del SDK de Apple y la envía al backend.
 */
function manejarRespuestaApple(respuestaApple) {
    var idToken  = respuestaApple.authorization.id_token;
    var userName = '';

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

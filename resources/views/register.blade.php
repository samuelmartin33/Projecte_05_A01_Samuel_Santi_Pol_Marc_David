@extends('layouts.app')

@push('estilos')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('title', 'Crear cuenta — VIBEZ')
@section('html-class', 'auth-page')
@section('body-class', 'grain')

@section('content')

<div class="auth-shell auth-shell-3col">

    {{-- ════════════════════════════════════════════════════
         PANEL IZQUIERDO — marca VIBEZ + beneficios
    ════════════════════════════════════════════════════ --}}
    <aside class="auth-side">

        {{-- Marca de agua "VBZ" gigante (decorativa) --}}
        <div class="auth-side-watermark" aria-hidden="true">
            <span>VBZ</span>
        </div>

        {{-- Fila superior: logo pill + enlace volver --}}
        <div class="auth-side-top">
            <a href="{{ route('welcome') }}" class="auth-logo" aria-label="VIBEZ — Inicio">
                <img src="{{ asset('images/logo_vibez_white.png') }}" alt="VIBEZ">
                <span class="display">VIBEZ</span>
            </a>
            @if(request('origen') === 'home')
                <a href="{{ route('home') }}" class="mono auth-back">← Volver al home</a>
            @else
                <a href="{{ route('welcome') }}" class="mono auth-back">← Volver</a>
            @endif
        </div>

        {{-- Contenido editorial (empujado al fondo del panel) --}}
        <div class="auth-side-content">

            <h1 class="display auth-side-title">
                Bienvenido<br><em>a la lista</em>.
            </h1>
            <p class="auth-side-sub">
                Crea tu pase VIBEZ y desbloquea entradas anticipadas, descuentos exclusivos y eventos solo para miembros.
            </p>
            <ul class="auth-side-bullets">
                <li><span class="bullet-num">01</span> Pre-venta 48h antes que nadie</li>
                <li><span class="bullet-num">02</span> Cupones y entradas con descuento</li>
                <li><span class="bullet-num">03</span> QR digital · sin imprimir nada</li>
                <li><span class="bullet-num">04</span> Bolsa de trabajo de la escena</li>
            </ul>
        </div>

        <div class="auth-side-bottom mono">
            VIBEZ · NIGHT EDITION {{ date('Y') }} · BCN
        </div>

    </aside>

    {{-- ════════════════════════════════════════════════════
         PANEL DERECHO — formulario de registro
    ════════════════════════════════════════════════════ --}}
    <main class="auth-main">

        {{-- Stickers decorativos --}}
        <div class="deco-sticker deco-1">NEW · MEMBER</div>
        <div class="deco-numbers" aria-hidden="true">{{ now()->format('d') }}<br>{{ now()->format('m') }}</div>

        <div class="auth-form-wrap auth-form-wide">

            <p class="mono auth-step">02 · Registro</p>

            {{-- Aviso especial cuando viene del CTA "Soy promotor" del home --}}
            @if(request('origen') === 'home')
            <div style="background:rgba(168,85,247,0.12);border:1px solid rgba(168,85,247,0.35);padding:12px 16px;margin-bottom:20px;font-family:'Archivo Narrow',sans-serif;font-size:13px;color:rgba(245,241,234,0.8);display:flex;align-items:flex-start;gap:10px;">
                <span style="color:var(--magenta);font-size:16px;flex-shrink:0;">ℹ</span>
                <span>Estás creando una cuenta nueva de empresa. <strong style="color:var(--ink);">Tu sesión actual no se cierra</strong> — puedes
                <a href="{{ route('home') }}" style="color:var(--magenta);text-decoration:underline;">volver al home</a> cuando quieras.</span>
            </div>
            @endif

            {{-- Cabecera (id="formHeader" usado por register.js en estado pendiente) --}}
            <div id="formHeader">
                <h2 class="display auth-title">
                    @if(request('tipo') === 'empresa')
                        Crea tu <em>cuenta de promotor</em>.
                    @else
                        Crea tu <em>pase</em>.
                    @endif
                </h2>
                <p class="auth-sub">
                    ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
                </p>
            </div>

            {{-- Alerta global --}}
            <div id="alert-global" class="alert alert-error" role="alert"></div>

            {{-- Tabs de tipo de cuenta: Soy raver / Soy promotor --}}
            <div class="auth-tabs">
                <button
                    type="button"
                    class="auth-tab active"
                    data-tipo="cliente"
                    onclick="seleccionarTab('cliente', this)"
                >
                    <span class="auth-tab-num">01</span>
                    <span class="auth-tab-label">Soy raver</span>
                    <span class="auth-tab-sub">Acceso inmediato</span>
                </button>
                <button
                    type="button"
                    class="auth-tab"
                    data-tipo="empresa"
                    onclick="seleccionarTab('empresa', this)"
                >
                    <span class="auth-tab-num">02</span>
                    <span class="auth-tab-label">Soy promotor</span>
                    <span class="auth-tab-sub">Requiere aprobación</span>
                </button>
            </div>

            {{-- Formulario --}}
            <form id="registerForm" novalidate autocomplete="off" onsubmit="registrar(event)">
                <div class="auth-form">

                    {{-- Fila 1 (3 col): Nombre + Apellidos --}}
                    <div class="auth-grid-3">
                        <div class="auth-field" id="field-nombre">
                            <label class="auth-label" for="nombre">Nombre</label>
                            <input
                                type="text"
                                id="nombre"
                                name="nombre"
                                placeholder="Tu nombre"
                                autocomplete="given-name"
                                inputmode="text"
                                onblur="validarNombre()"
                            >
                            <span class="field-error" id="error-nombre" role="alert"></span>
                        </div>

                        <div class="auth-field" id="field-apellido1">
                            <label class="auth-label" for="apellido1">Primer apellido</label>
                            <input
                                type="text"
                                id="apellido1"
                                name="apellido1"
                                placeholder="Primer apellido"
                                autocomplete="family-name"
                                inputmode="text"
                                onblur="validarApellido1()"
                            >
                            <span class="field-error" id="error-apellido1" role="alert"></span>
                        </div>

                        <div class="auth-field" id="field-apellido2">
                            <label class="auth-label" for="apellido2">Segundo apellido</label>
                            <input
                                type="text"
                                id="apellido2"
                                name="apellido2"
                                placeholder="Segundo apellido"
                                autocomplete="additional-name"
                                inputmode="text"
                                onblur="validarApellido2()"
                            >
                            <span class="field-error" id="error-apellido2" role="alert"></span>
                        </div>
                    </div>

                    {{-- Fila 2 (3 col): Email + Fecha nacimiento + Teléfono --}}
                    <div class="auth-grid-3">
                        <div class="auth-field" id="field-email">
                            <label class="auth-label" for="email">Correo electrónico</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="tu@email.com"
                                autocomplete="email"
                                inputmode="email"
                                onblur="validarEmail()"
                            >
                            <span class="field-error" id="error-email" role="alert"></span>
                        </div>

                        <div class="auth-field" id="field-fecha_nacimiento">
                            <label class="auth-label" for="fecha_nacimiento">Fecha de nacimiento</label>
                            <input
                                type="text"
                                id="fecha_nacimiento"
                                name="fecha_nacimiento"
                                placeholder="DD/MM/AAAA"
                                readonly
                            >
                            <span class="field-error" id="error-fecha_nacimiento" role="alert"></span>
                        </div>

                        <div class="auth-field" id="field-telefono">
                            <label class="auth-label" for="telefono">Teléfono</label>
                            <input
                                type="tel"
                                id="telefono"
                                name="telefono"
                                placeholder="+34 600 000 000"
                                autocomplete="tel"
                                inputmode="tel"
                                onblur="validarTelefono()"
                            >
                            <span class="field-error" id="error-telefono" role="alert"></span>
                        </div>
                    </div>

                    {{-- Fila 3 (2 col): Contraseña + Confirmar --}}
                    <div class="auth-grid-2">
                        <div class="auth-field" id="field-password">
                            <label class="auth-label" for="password">Contraseña</label>
                            <div class="auth-input-wrap">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="Mínimo 8 caracteres"
                                    autocomplete="new-password"
                                    onblur="validarContrasena()"
                                    oninput="actualizarFortaleza(this.value)"
                                >
                                <button
                                    type="button"
                                    class="auth-eye"
                                    onclick="togglePassword('password', this)"
                                    aria-label="Mostrar contraseña"
                                    tabindex="-1"
                                >
                                    <svg class="eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/>
                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <svg class="eye-closed" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                                    </svg>
                                </button>
                            </div>
                            {{-- Barra de fortaleza --}}
                            <div class="auth-strength">
                                <div class="auth-strength-track">
                                    <div class="auth-strength-bar" id="strength-bar"></div>
                                </div>
                                <span class="auth-strength-label" id="strength-label"></span>
                            </div>
                            <span class="field-error" id="error-password" role="alert"></span>
                        </div>

                        <div class="auth-field" id="field-password_confirmation">
                            <label class="auth-label" for="password_confirmation">Confirmar contraseña</label>
                            <div class="auth-input-wrap">
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder="Repite la contraseña"
                                    autocomplete="new-password"
                                    onblur="validarConfirmacion()"
                                >
                                <button
                                    type="button"
                                    class="auth-eye"
                                    onclick="togglePassword('password_confirmation', this)"
                                    aria-label="Mostrar contraseña"
                                    tabindex="-1"
                                >
                                    <svg class="eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/>
                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <svg class="eye-closed" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                                    </svg>
                                </button>
                            </div>
                            <span class="field-error" id="error-password_confirmation" role="alert"></span>
                        </div>
                    </div>

                    {{-- Select oculto sincronizado con los tabs --}}
                    <div id="field-tipo_cuenta" style="display:none" aria-hidden="true">
                        <select
                            id="tipo_cuenta"
                            name="tipo_cuenta"
                            onchange="cambiarTipoCuenta(this)"
                            onblur="validarTipoCuenta()"
                        >
                            <option value="" disabled selected hidden></option>
                            <option value="cliente">Cliente</option>
                            <option value="empresa">Empresa</option>
                        </select>
                    </div>
                    <span class="field-error" id="error-tipo_cuenta" role="alert"></span>
                    <p id="hint-tipo_cuenta" style="font-family:'Archivo',sans-serif;font-size:12px;margin-top:-4px;min-height:18px;color:rgba(245,241,234,0.45)"></p>

                    {{-- Campos empresa: solo visibles para promotores --}}
                    <div id="empresa-field-wrap" style="display:none">

                        {{-- Nombre empresa --}}
                        <div class="auth-field" id="field-nombre_empresa" style="margin-bottom:16px">
                            <label class="auth-label" for="nombre_empresa">Empresa / Sala</label>
                            <input
                                type="text"
                                id="nombre_empresa"
                                name="nombre_empresa"
                                placeholder="Razzmatazz S.L."
                                autocomplete="organization"
                            >
                            <span class="field-error" id="error-nombre_empresa" role="alert"></span>
                        </div>

                        {{-- Razón social (nombre legal oficial) --}}
                        <div class="auth-field" id="field-razon_social" style="margin-bottom:16px">
                            <label class="auth-label" for="razon_social">Razón social <span style="opacity:.5;font-size:11px">(opcional)</span></label>
                            <input
                                type="text"
                                id="razon_social"
                                name="razon_social"
                                placeholder="Vibez Events, S.L."
                                autocomplete="organization"
                            >
                            <span class="field-error" id="error-razon_social" role="alert"></span>
                        </div>

                        {{-- NIF / CIF --}}
                        <div class="auth-field" id="field-nif_cif" style="margin-bottom:16px">
                            <label class="auth-label" for="nif_cif">NIF / CIF</label>
                            <input
                                type="text"
                                id="nif_cif"
                                name="nif_cif"
                                placeholder="B12345678"
                                maxlength="9"
                                oninput="this.value=this.value.toUpperCase()"
                                onblur="validarNifCif()"
                            >
                            <span class="field-error" id="error-nif_cif" role="alert"></span>
                        </div>

                        {{-- Tipo de promotor --}}
                        <div class="auth-field" id="field-tipo_promotor" style="margin-bottom:16px">
                            <label class="auth-label">Tipo de promotor</label>
                            <input type="hidden" id="tipo_promotor" name="tipo_promotor">
                            <div class="tp-csel" id="tp-csel">
                                <div class="tp-csel-trigger" id="tp-csel-trigger" onclick="toggleTpCsel()">
                                    <span id="tp-csel-label">Selecciona una opción</span>
                                    <span class="tp-csel-arrow" id="tp-csel-arrow">▾</span>
                                </div>
                                <ul class="tp-csel-menu" id="tp-csel-menu">
                                    <li class="tp-csel-opt" onclick="pickTpCsel('sala_club','Sala / Club nocturno')">Sala / Club nocturno</li>
                                    <li class="tp-csel-opt" onclick="pickTpCsel('promotora','Promotora de eventos')">Promotora de eventos</li>
                                    <li class="tp-csel-opt" onclick="pickTpCsel('festival','Festival')">Festival</li>
                                    <li class="tp-csel-opt" onclick="pickTpCsel('artista','Artista / DJ')">Artista / DJ</li>
                                    <li class="tp-csel-opt" onclick="pickTpCsel('autonomo','Autónomo')">Autónomo</li>
                                    <li class="tp-csel-opt" onclick="pickTpCsel('otro','Otro')">Otro</li>
                                </ul>
                            </div>
                            <span class="field-error" id="error-tipo_promotor" role="alert"></span>
                        </div>

                        {{-- Teléfono de empresa --}}
                        <div class="auth-field" id="field-telefono_empresa" style="margin-bottom:16px">
                            <label class="auth-label" for="telefono_empresa">Teléfono de empresa <span style="opacity:.5;font-size:11px">(opcional)</span></label>
                            <input
                                type="tel"
                                id="telefono_empresa"
                                name="telefono_empresa"
                                placeholder="+34 900 000 000"
                                onblur="validarTelefonoEmpresa()"
                            >
                            <span class="field-error" id="error-telefono_empresa" role="alert"></span>
                        </div>

                        {{-- Descripción (opcional) --}}
                        <div class="auth-field" style="margin-bottom:16px">
                            <label class="auth-label" for="descripcion">Descripción <span style="opacity:.5;font-size:11px">(opcional)</span></label>
                            <textarea
                                id="descripcion"
                                name="descripcion"
                                placeholder="Breve descripción de vuestra empresa y tipo de eventos que organizáis"
                                maxlength="500"
                                rows="3"
                                class="form-textarea"
                            ></textarea>
                        </div>

                        {{-- Sitio web (opcional) --}}
                        <div class="auth-field" style="margin-bottom:0">
                            <label class="auth-label" for="sitio_web">Sitio web <span style="opacity:.5;font-size:11px">(opcional)</span></label>
                            <input
                                type="url"
                                id="sitio_web"
                                name="sitio_web"
                                placeholder="https://vuestra-web.com"
                            >
                        </div>

                    </div>

                    {{-- Géneros musicales — chips opcionales --}}
                    

                    {{-- Aceptar términos --}}
                    <label class="auth-check auth-check-tall" id="field-acepta_terminos">
                        <input type="checkbox" id="acepta_terminos" name="acepta_terminos" required
                               onchange="limpiarErrorCampo('field-acepta_terminos','error-acepta_terminos')">
                        <span class="auth-check-box"></span>
                        <span>Acepto los <a href="{{ route('terminos') }}">Términos de uso</a> y la <a href="{{ route('privacidad') }}">Política de privacidad</a>. Soy mayor de 16 años.</span>
                    </label>
                    <span class="field-error" id="error-acepta_terminos" role="alert"></span>

                    {{-- Fila botones (id="btnRow" usado por JS en estado pendiente) --}}
                    <div id="btnRow">
                        <button
                            type="submit"
                            class="btn-primary auth-btn-main"
                            id="submitBtn"
                            onclick="rippleBtn(event, this)"
                        >
                            <span class="btn-text">Crear mi pase VIBEZ →</span>
                            <span class="btn-spinner" aria-hidden="true">
                                <span class="spinner-ring"></span>
                            </span>
                        </button>

                        <div class="auth-divider"><span>o continúa con</span></div>

                        <div class="auth-socials" style="display:flex;flex-direction:column;gap:10px;margin-top:4px">
                            {{-- Google (Socialite — flujo server-side redirect) --}}
                            <a href="{{ route('auth.google') }}" class="auth-social" style="justify-content:center">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                </svg>
                                Continuar con Google
                            </a>
                            <button
                                type="button"
                                class="auth-social"
                                id="apple-signin-btn"
                                data-apple-client-id="{{ config('services.apple.client_id') }}"
                                onclick="iniciarSesionApple(event, this)"
                            >
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16.4 1.5c.1 1.2-.4 2.4-1.2 3.3-.9 1-2.2 1.7-3.4 1.6-.1-1.2.5-2.4 1.2-3.2.9-1 2.3-1.7 3.4-1.7zm3.6 16.4c-.7 1.6-1 2.3-1.9 3.7-1.2 1.9-2.9 4.3-5 4.3-1.9 0-2.4-1.2-5-1.2-2.5 0-3.1 1.2-5 1.2-2.1 0-3.7-2.2-4.9-4.1C-1 18.3-1.4 13.5.7 10.6c1.4-2 3.7-3.2 5.8-3.2 2.2 0 3.6 1.2 5.4 1.2 1.8 0 2.9-1.2 5.4-1.2 1.9 0 3.9 1 5.3 2.8-4.7 2.6-3.9 9.3-2.6 7.7z"/></svg>
                                Continuar con Apple
                            </button>
                        </div>
                    </div>

                </div>
            </form>

            <p class="auth-fineprint mono">
                Verificaremos tu email. ¡Empieza la fiesta inmediatamente!
            </p>

        </div>{{-- /auth-form-wrap --}}

    </main>

</div>{{-- /auth-shell --}}

@endsection

@section('scripts')
    <script src="https://appleid.cdn-apple.com/appleauth/static/jsapi/appleid/1/en_US/appleid.auth.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script src="{{ asset('js/register.js') }}"></script>
    <script>
        /* Inicializar tabs: "cliente" arranca activo, sincronizar el select */
        (function () {
            var select = document.getElementById('tipo_cuenta');
            if (select) { select.value = 'cliente'; cambiarTipoCuenta(select); }
        })();

        /* Cambia el tab activo y sincroniza el select oculto */
        function seleccionarTab(tipo, elTab) {
            var tabs = document.querySelectorAll('.auth-tab');
            for (var i = 0; i < tabs.length; i++) tabs[i].classList.remove('active');
            elTab.classList.add('active');

            var select = document.getElementById('tipo_cuenta');
            select.value = tipo;
            cambiarTipoCuenta(select);

            var esEmpresa = tipo === 'empresa';

            /* Mostrar/ocultar bloque campos empresa */
            var empresaWrap = document.getElementById('empresa-field-wrap');
            if (empresaWrap) empresaWrap.style.display = esEmpresa ? 'block' : 'none';

            /* Ocultar segundo apellido para empresas (no requerido) */
            var campoApellido2 = document.getElementById('field-apellido2');
            if (campoApellido2) campoApellido2.style.display = esEmpresa ? 'none' : 'flex';

            /* Ocultar géneros musicales cuando es promotor (no relevante para empresas) */
            var moodPick = document.querySelector('.auth-mood-pick');
            if (moodPick) moodPick.style.display = esEmpresa ? 'none' : 'block';
        }

        /* Valida el campo NIF/CIF */
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

        /* Custom dropdown — tipo promotor */
        function toggleTpCsel() {
            var menu    = document.getElementById('tp-csel-menu');
            var trigger = document.getElementById('tp-csel-trigger');
            var arrow   = document.getElementById('tp-csel-arrow');
            var open    = menu.classList.contains('tp-csel-open');
            menu.classList.toggle('tp-csel-open', !open);
            trigger.classList.toggle('tp-csel-active', !open);
            arrow.style.transform = open ? 'rotate(0deg)' : 'rotate(180deg)';
        }
        function pickTpCsel(val, label) {
            document.getElementById('tipo_promotor').value = val;
            document.getElementById('tp-csel-label').textContent = label;
            document.getElementById('tp-csel-label').style.color = '';
            var menu    = document.getElementById('tp-csel-menu');
            var trigger = document.getElementById('tp-csel-trigger');
            var arrow   = document.getElementById('tp-csel-arrow');
            menu.classList.remove('tp-csel-open');
            trigger.classList.remove('tp-csel-active');
            arrow.style.transform = 'rotate(0deg)';
            var error = document.getElementById('error-tipo_promotor');
            if (error) error.textContent = '';
        }
        document.onclick = function(e) {
            var csel = document.getElementById('tp-csel');
            if (csel && !csel.contains(e.target)) {
                var menu    = document.getElementById('tp-csel-menu');
                var trigger = document.getElementById('tp-csel-trigger');
                var arrow   = document.getElementById('tp-csel-arrow');
                if (menu) { menu.classList.remove('tp-csel-open'); trigger.classList.remove('tp-csel-active'); arrow.style.transform = 'rotate(0deg)'; }
            }
        };

        /* Valida el dropdown tipo_promotor */
        function validarTipoPromotor() {
            var campo = document.getElementById('tipo_promotor');
            var error = document.getElementById('error-tipo_promotor');
            if (!campo || !error) return true;
            if (!campo.value) { error.textContent = 'Selecciona el tipo de promotor'; return false; }
            error.textContent = '';
            return true;
        }

        /* Barra de fortaleza de la contraseña */
        function actualizarFortaleza(valor) {
            var barra    = document.getElementById('strength-bar');
            var etiqueta = document.getElementById('strength-label');
            if (!barra) return;
            var f = 0;
            if (valor.length >= 8)          f += 25;
            if (/[A-Z]/.test(valor))         f += 25;
            if (/[0-9]/.test(valor))         f += 25;
            if (/[^A-Za-z0-9]/.test(valor)) f += 25;
            barra.style.width = f + '%';
            var colores = { 25: '#ef4444', 50: '#f59e0b', 75: '#3b82f6', 100: '' };
            var niveles = { 25: 'Débil', 50: 'Regular', 75: 'Fuerte', 100: 'Excelente' };
            barra.style.background = colores[f] || '';
            etiqueta.textContent   = f > 0 ? (niveles[f] || '') : '';
        }

        /* ── Auto-seleccionar tab si viene con ?tipo=empresa ─── */
        (function () {
            var params = new URLSearchParams(window.location.search);
            if (params.get('tipo') === 'empresa') {
                var tabEmpresa = document.querySelector('[data-tipo="empresa"]');
                if (tabEmpresa) seleccionarTab('empresa', tabEmpresa);
            }
        })();
    </script>
@endsection

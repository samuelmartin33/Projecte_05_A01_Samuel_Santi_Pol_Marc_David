@extends('layouts.app')

@section('title', 'Iniciar sesión — VIBEZ')
@section('html-class', 'auth-page')
@section('body-class', 'auth-page')

@section('content')

{{-- Aurora mesh gradient: 4 blobs con animación independiente --}}
<div class="auth-bg">
    <div class="aurora-blob"></div>
    <div class="aurora-blob"></div>
    <div class="aurora-blob"></div>
    <div class="aurora-blob"></div>
</div>

<div class="auth-wrapper page-transition">

    {{-- ============================================================
         PANEL IZQUIERDO: Arte — SVG animado + branding
         ============================================================ --}}
    <div class="art-panel">
        <div class="art-content">
            <div class="brand-name">VIBEZ</div>
            <div class="brand-tagline">Conecta · Vibra · Vive</div>

            {{-- Ilustración SVG: blobs orgánicos flotantes --}}
            <svg class="art-svg" viewBox="0 0 440 440" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">

                {{-- Blob grande de fondo: violeta profundo --}}
                <g class="blob-1">
                    <path d="M218,72 C282,52 350,98 362,168 C374,238 336,308 272,334 C208,360 136,338 100,274 C64,210 72,136 116,100 C160,64 154,92 218,72Z"
                          fill="#5B21B6" opacity="0.10"/>
                </g>

                {{-- Blob mediano: violeta principal, más opaco --}}
                <g class="blob-2">
                    <path d="M212,130 C255,108 302,138 312,182 C322,226 298,268 258,280 C218,292 174,270 158,228 C142,186 169,152 212,130Z"
                          fill="#7C3AED" opacity="0.22"/>
                </g>

                {{-- Blob lila: esquina inferior izquierda --}}
                <g class="blob-3">
                    <path d="M108,296 C130,278 162,287 170,314 C178,341 158,364 134,362 C110,360 94,340 97,316 C100,292 86,314 108,296Z"
                          fill="#C4B5FD" opacity="0.55"/>
                </g>

                {{-- Blob pequeño: esquina superior derecha --}}
                <g class="blob-4">
                    <path d="M324,72 C346,58 370,70 374,96 C378,122 360,142 336,140 C312,138 298,120 302,98 C306,76 302,86 324,72Z"
                          fill="#7C3AED" opacity="0.38"/>
                </g>

                {{-- Blob acento: inferior derecha --}}
                <g class="blob-5">
                    <path d="M292,334 C314,318 342,326 348,352 C354,378 334,396 310,393 C286,390 272,374 276,352 C280,330 270,350 292,334Z"
                          fill="#5B21B6" opacity="0.28"/>
                </g>

                {{-- Orbs: círculos flotantes con pulso --}}
                <circle class="orb-1" cx="332" cy="206" r="17"  fill="#C4B5FD" opacity="0.6"/>
                <circle class="orb-2" cx="86"  cy="182" r="11"  fill="#7C3AED" opacity="0.38"/>
                <circle class="orb-3" cx="208" cy="382" r="8"   fill="#EDE9FE" opacity="0.75"/>
                <circle class="orb-1" cx="358" cy="324" r="10"  fill="#5B21B6" opacity="0.28"/>
                <circle class="orb-2" cx="62"  cy="290" r="6"   fill="#C4B5FD" opacity="0.5"/>
                <circle class="orb-3" cx="174" cy="74"  r="14"  fill="#7C3AED" opacity="0.18"/>
                <circle class="orb-1" cx="150" cy="352" r="5"   fill="#C4B5FD" opacity="0.45"/>

                {{-- Líneas decorativas con guiones --}}
                <path d="M118,118 Q220,82 322,130"
                      stroke="#C4B5FD" stroke-width="1.2"
                      stroke-dasharray="5,5" opacity="0.28" fill="none"/>
                <path d="M140,308 Q218,268 298,312"
                      stroke="#7C3AED" stroke-width="1"
                      opacity="0.18" fill="none"/>

                {{-- Pequeños destellos/puntos de luz --}}
                <circle cx="268" cy="116" r="3" fill="#EDE9FE" opacity="0.8"/>
                <circle cx="168" cy="246" r="2" fill="#C4B5FD" opacity="0.6"/>
                <circle cx="338" cy="168" r="4" fill="#7C3AED" opacity="0.25"/>
                <circle cx="90"  cy="234" r="3" fill="#5B21B6" opacity="0.2"/>
            </svg>
        </div>
    </div>

    {{-- ============================================================
         PANEL DERECHO: Formulario — sin caja contenedora
         ============================================================ --}}
    <div class="form-panel">

        <div class="form-header">
            <h1 class="form-title">Bienvenido de nuevo</h1>
            <p class="form-subtitle">Accede a tu cuenta para continuar</p>
        </div>

        {{-- Alerta global: errores del backend --}}
        <div id="alert-global" class="alert alert-error" role="alert"></div>

        {{-- Formulario — novalidate: usamos nuestra propia validación JS --}}
        <form id="loginForm" novalidate autocomplete="off" onsubmit="iniciarSesion(event)">

            <div class="field-group">

                {{-- Campo email --}}
                <div class="field" id="field-email">
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder=" "
                        autocomplete="email"
                        inputmode="email"
                    >
                    <label for="email">Correo electrónico</label>
                    <span class="field-error" id="error-email" role="alert"></span>
                </div>

                {{-- Campo contraseña --}}
                <div class="field" id="field-password">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder=" "
                        autocomplete="current-password"
                    >
                    <label for="password">Contraseña</label>
                    <button type="button" class="toggle-password" onclick="togglePassword('password', this)" aria-label="Mostrar contraseña" tabindex="-1">
                        <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <svg class="eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display:none"><path d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                    </button>
                    <span class="field-error" id="error-password" role="alert"></span>
                </div>

            </div>

            {{-- Botón submit con ripple y spinner --}}
            <button type="submit" class="btn-primary" id="submitBtn" onclick="rippleBtn(event, this)">
                <span class="btn-text">Iniciar sesión</span>
                <span class="btn-spinner" aria-hidden="true">
                    <span class="spinner-ring"></span>
                </span>
            </button>

        </form>

        {{-- Enlace hacia register --}}
        <p class="form-switch">
            ¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate</a>
        </p>

    </div>
</div>

@endsection

@section('scripts')
    <script src="{{ asset('js/login.js') }}"></script>
<script>
function togglePassword(inputId, btn) {
    const input   = document.getElementById(inputId);
    const showing = input.type === 'text';
    input.type    = showing ? 'password' : 'text';
    btn.querySelector('.eye-open').style.display   = showing ? ''     : 'none';
    btn.querySelector('.eye-closed').style.display = showing ? 'none' : '';
    btn.setAttribute('aria-label', showing ? 'Mostrar contraseña' : 'Ocultar contraseña');
}

/** Efecto ripple desde el punto del click */
function rippleBtn(e, btn) {
    const rect   = btn.getBoundingClientRect();
    const size   = Math.max(rect.width, rect.height);
    const ripple = document.createElement('span');
    ripple.classList.add('ripple');
    ripple.style.cssText = `width:${size}px;height:${size}px;left:${e.clientX - rect.left - size/2}px;top:${e.clientY - rect.top - size/2}px`;
    btn.appendChild(ripple);
    setTimeout(() => ripple.remove(), 700);
}

function isValidEmail(e) { return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(e); }

function mostrarErrorCampo(fieldId, errorId, msg) {
    document.getElementById(fieldId)?.classList.add('has-error');
    const el = document.getElementById(errorId);
    if (el) { el.textContent = msg; el.classList.add('visible'); }
}

function limpiarErrorCampo(fieldId, errorId) {
    document.getElementById(fieldId)?.classList.remove('has-error');
    const el = document.getElementById(errorId);
    if (el) { el.textContent = ''; el.classList.remove('visible'); }
}

function mostrarAlerta(msg, tipo = 'error') {
    const el = document.getElementById('alert-global');
    el.textContent = msg;
    el.className   = `alert alert-${tipo} visible`;
}

function sacudirElemento(el) {
    el.classList.add('shake');
    el.onanimationend = function() {
        el.classList.remove('shake');
        el.onanimationend = null;
    };
}

/** Submit del formulario de login */
async function iniciarSesion(e) {
    e.preventDefault();
    const email    = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const btn      = document.getElementById('submitBtn');
    const form     = document.getElementById('loginForm');
    let valid      = true;

    limpiarErrorCampo('field-email', 'error-email');
    limpiarErrorCampo('field-password', 'error-password');
    document.getElementById('alert-global').className = 'alert alert-error';

    if (!email)                  { mostrarErrorCampo('field-email', 'error-email', 'El email es obligatorio');        valid = false; }
    else if (!isValidEmail(email)) { mostrarErrorCampo('field-email', 'error-email', 'Introduce un email válido');  valid = false; }

    if (!password)               { mostrarErrorCampo('field-password', 'error-password', 'La contraseña es obligatoria'); valid = false; }
    else if (password.length < 8){ mostrarErrorCampo('field-password', 'error-password', 'Mínimo 8 caracteres');    valid = false; }

    if (!valid) { sacudirElemento(form); return; }

    btn.classList.add('loading');
    try {
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const res  = await fetch('/api/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ email, password }),
        });
        const data = await res.json();

        if (data.success) {
            btn.innerHTML = `<span class="btn-text success-check"><svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>¡Sesión iniciada!</span>`;
            btn.classList.remove('loading');
            btn.style.background = 'linear-gradient(135deg,#22C55E,#16A34A)';
            setTimeout(() => {
                document.body.style.transition = 'opacity 0.35s ease';
                document.body.style.opacity    = '0';
                setTimeout(() => { window.location.href = '/home'; }, 360);
            }, 750);
        } else {
            btn.classList.remove('loading');
            if (data.unverified) { mostrarAlerta(data.message, 'warning'); return; }
            if (data.errors) Object.entries(data.errors).forEach(([f, m]) => mostrarErrorCampo(`field-${f}`, `error-${f}`, m[0]));
            mostrarAlerta(data.message || 'Credenciales incorrectas. Inténtalo de nuevo.');
            sacudirElemento(form);
        }
    } catch (err) {
        btn.classList.remove('loading');
        mostrarAlerta('Error de conexión. Verifica tu red e inténtalo de nuevo.');
        console.error('[VIBEZ] Error en login:', err);
    }
}
</script>
@endsection

@extends('layouts.app')

@section('title', 'Iniciar sesión — VIBEZ')
@section('html-class', 'auth-page')
@section('body-class', 'grain')

@section('content')

<div class="auth-shell">

    {{-- ════════════════════════════════════════════════════
         PANEL IZQUIERDO — marca VIBEZ + branding editorial
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
            <a href="{{ route('welcome') }}" class="mono auth-back">← Volver</a>
        </div>

        {{-- Contenido editorial (empujado al fondo del panel) --}}
        <div class="auth-side-content">
            <div class="mono auth-kicker">
                <span class="kicker-line"></span>
                Tu acceso a la escena
            </div>
            <h1 class="display auth-side-title">
                Esta noche<br><em>se rompe</em>.
            </h1>
            <p class="auth-side-sub">
                Eventos, trabajo y comunidad para los que viven la noche de verdad.
            </p>
            <div class="auth-side-pills">
                <span class="auth-pill"><span class="dot"></span> 200+ eventos esta semana</span>
                <span class="auth-pill">Madrid · Barcelona · Valencia</span>
            </div>
        </div>

        <div class="auth-side-bottom mono">
            VIBEZ · NIGHT EDITION {{ date('Y') }} · BCN
        </div>

    </aside>

    {{-- ════════════════════════════════════════════════════
         PANEL DERECHO — formulario de acceso
    ════════════════════════════════════════════════════ --}}
    <main class="auth-main">

        {{-- Stickers decorativos --}}
        <div class="deco-sticker deco-1">VIP · ACCESS</div>
        <div class="deco-sticker deco-2">★ MEMBER</div>
        <div class="deco-numbers" aria-hidden="true">{{ now()->format('d') }}<br>{{ now()->format('m') }}</div>

        <div class="auth-form-wrap">

            <p class="mono auth-step">01 · Acceso</p>

            <h2 class="display auth-title">
                Entra a la <em>fiesta</em>.
            </h2>

            <p class="auth-sub">
                ¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate gratis</a>
            </p>

            {{-- Alerta global: errores del backend --}}
            <div id="alert-global" class="alert alert-error" role="alert"></div>

            {{-- Errores flash de redirección OAuth (Google Socialite) --}}
            @if(session('error'))
                <div class="alert alert-error visible" role="alert">{{ session('error') }}</div>
            @endif

            {{-- Formulario — validación por login.js --}}
            <form id="loginForm" novalidate autocomplete="off" onsubmit="iniciarSesion(event)">
                <div class="auth-form">

                    {{-- Email --}}
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

                    {{-- Contraseña --}}
                    <div class="auth-field" id="field-password">
                        <label class="auth-label" for="password">Contraseña</label>
                        <div class="auth-input-wrap">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="••••••••"
                                autocomplete="current-password"
                                onblur="validarContrasena()"
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
                        <span class="field-error" id="error-password" role="alert"></span>
                    </div>

                    {{-- Recuérdame + olvidé contraseña --}}
                    <div class="auth-row-between">
                        <label class="auth-check">
                            <input type="checkbox" id="remember" name="remember" checked>
                            <span class="auth-check-box"></span>
                            <span>Recuérdame 30 días</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="auth-link-small">¿Olvidaste tu contraseña?</a>
                    </div>

                    {{-- Botón de envío --}}
                    <button
                        type="submit"
                        class="btn-primary auth-btn-main"
                        id="submitBtn"
                        onclick="rippleBtn(event, this)"
                    >
                        <span class="btn-text">Entrar a VIBEZ →</span>
                        <span class="btn-spinner" aria-hidden="true">
                            <span class="spinner-ring"></span>
                        </span>
                    </button>

                </div>
            </form>

            {{-- Divisor + acceso social --}}
            <div class="auth-divider"><span class="mono">o continúa con</span></div>

            {{-- Google (Socialite — flujo server-side redirect) --}}
            <a href="{{ route('auth.google') }}" class="auth-social-google">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Continuar con Google
            </a>

            <p class="auth-fineprint mono">
                Al entrar aceptas nuestros <a href="{{ route('terminos') }}">Términos</a> y la <a href="{{ route('privacidad') }}">Política de privacidad</a>
            </p>

        </div>{{-- /auth-form-wrap --}}

    </main>

</div>{{-- /auth-shell --}}

@endsection

@section('scripts')
    <script src="{{ asset('js/login.js') }}"></script>
@endsection

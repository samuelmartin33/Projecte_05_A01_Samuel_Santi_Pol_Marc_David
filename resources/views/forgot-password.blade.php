@extends('layouts.app')

@section('title', 'Recuperar contraseña — VIBEZ')
@section('html-class', 'auth-page')
@section('body-class', 'grain')

@section('content')

<div class="auth-shell">

    <aside class="auth-side">
        <div class="auth-side-watermark" aria-hidden="true"><span>VBZ</span></div>
        <div class="auth-side-top">
            <a href="{{ route('welcome') }}" class="auth-logo" aria-label="VIBEZ — Inicio">
                <img src="{{ asset('images/logo_vibez_white.png') }}" alt="VIBEZ">
                <span class="display">VIBEZ</span>
            </a>
            <a href="{{ route('login') }}" class="mono auth-back">← Volver</a>
        </div>
        <div class="auth-side-content">
            <div class="mono auth-kicker">
                <span class="kicker-line"></span>
                Recupera tu acceso
            </div>
            <h1 class="display auth-side-title">
                No te<br><em>quedes</em><br>fuera.
            </h1>
            <p class="auth-side-sub">
                Te enviaremos un enlace para restablecer tu contraseña en menos de un minuto.
            </p>
        </div>
        <div class="auth-side-bottom mono">
            VIBEZ · NIGHT EDITION {{ date('Y') }} · BCN
        </div>
    </aside>

    <main class="auth-main">

        <div class="deco-sticker deco-1">VIP · ACCESS</div>
        <div class="deco-sticker deco-2">★ MEMBER</div>
        <div class="deco-numbers" aria-hidden="true">{{ now()->format('d') }}<br>{{ now()->format('m') }}</div>

        <div class="auth-form-wrap">

            <p class="mono auth-step">02 · Recuperación</p>

            <h2 class="display auth-title">
                ¿Olvidaste tu <em>clave</em>?
            </h2>

            <p class="auth-sub">
                Introduce tu correo y te enviamos el enlace de recuperación.
            </p>

            @if (session('status'))
                <div class="alert" style="background:rgba(74,222,128,0.1);border:1px solid rgba(74,222,128,0.3);color:#4ade80;padding:14px 18px;margin-bottom:20px;font-family:'Archivo Narrow',sans-serif;font-size:13px;letter-spacing:0.04em;">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" novalidate>
                @csrf
                <div class="auth-form">

                    <div class="auth-field" id="field-email">
                        <label class="auth-label" for="email">Correo electrónico</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="tu@email.com"
                            autocomplete="email"
                            inputmode="email"
                            required
                        >
                        @error('email')
                            <span class="field-error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn-primary auth-btn-main" onclick="rippleBtn(event, this)">
                        <span class="btn-text">Enviar enlace de recuperación →</span>
                        <span class="btn-spinner" aria-hidden="true"><span class="spinner-ring"></span></span>
                    </button>

                    <p style="text-align:center;margin-top:6px;">
                        <a href="{{ route('login') }}" class="auth-link-small">Volver al inicio de sesión</a>
                    </p>

                </div>
            </form>

            <p class="auth-fineprint mono">
                Al usar VIBEZ aceptas nuestros <a href="{{ route('terminos') }}">Términos</a> y la <a href="{{ route('privacidad') }}">Política de privacidad</a>
            </p>

        </div>

    </main>

</div>

@endsection

@section('scripts')
    <script src="{{ asset('js/login.js') }}"></script>
@endsection

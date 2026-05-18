@extends('layouts.app')

@section('title', 'Nueva contraseña — VIBEZ')
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
                Nueva contraseña
            </div>
            <h1 class="display auth-side-title">
                Vuelve<br>a la <em>escena</em>.
            </h1>
            <p class="auth-side-sub">
                Elige una contraseña segura. Mínimo 8 caracteres.
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

            <p class="mono auth-step">03 · Nueva clave</p>

            <h2 class="display auth-title">
                Crea tu nueva <em>contraseña</em>.
            </h2>

            @if ($errors->has('token'))
                <div class="alert" style="background:rgba(252,165,165,0.1);border:1px solid rgba(252,165,165,0.3);color:#fca5a5;padding:14px 18px;margin-bottom:20px;font-family:'Archivo Narrow',sans-serif;font-size:13px;letter-spacing:0.04em;">
                    {{ $errors->first('token') }}
                    <a href="{{ route('password.request') }}" style="color:#fca5a5;text-decoration:underline;margin-left:8px;">Solicitar nuevo enlace →</a>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" novalidate>
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="auth-form">

                    {{-- Nueva contraseña --}}
                    <div class="auth-field" id="field-password">
                        <label class="auth-label" for="password">Nueva contraseña</label>
                        <div class="auth-input-wrap">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Mínimo 8 caracteres"
                                autocomplete="new-password"
                                required
                                minlength="8"
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
                        @error('password')
                            <span class="field-error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Confirmar contraseña --}}
                    <div class="auth-field" id="field-password_confirmation">
                        <label class="auth-label" for="password_confirmation">Confirmar contraseña</label>
                        <div class="auth-input-wrap">
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="Repite la contraseña"
                                autocomplete="new-password"
                                required
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
                        @error('password_confirmation')
                            <span class="field-error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn-primary auth-btn-main" onclick="rippleBtn(event, this)">
                        <span class="btn-text">Guardar nueva contraseña →</span>
                        <span class="btn-spinner" aria-hidden="true"><span class="spinner-ring"></span></span>
                    </button>

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

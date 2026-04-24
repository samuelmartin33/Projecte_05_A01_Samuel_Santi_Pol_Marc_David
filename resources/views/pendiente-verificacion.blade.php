@extends('layouts.app')

@section('title', 'Cuenta pendiente — VIBEZ')
@section('html-class', 'auth-page')
@section('body-class', 'auth-page')

@section('content')

<div class="auth-bg">
    <div class="aurora-blob"></div>
    <div class="aurora-blob"></div>
    <div class="aurora-blob"></div>
    <div class="aurora-blob"></div>
</div>

<div class="pending-wrapper">
    <div class="pending-card">

        <div class="pending-icon">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none"
                 stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </svg>
        </div>

        <h1 class="pending-title">Cuenta pendiente de verificación</h1>

        <p class="pending-text">
            Tu registro se ha completado correctamente.
            El administrador revisará tu solicitud y,
            cuando sea verificada, recibirás un correo electrónico de confirmación.
        </p>

        <p class="pending-subtext">
            Si crees que ha habido un error, contacta con el equipo de VIBEZ.
        </p>

        <hr class="pending-divider">

        <a href="{{ route('login') }}" class="pending-back-link">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Volver al inicio de sesión
        </a>

    </div>
</div>

@endsection

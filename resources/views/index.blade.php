@extends('layouts.app')

@section('title', 'Dashboard — VIBEZ')


@section('content')

{{-- Aurora mesh gradient: mismo sistema visual --}}
<div class="auth-bg">
    <div class="aurora-blob"></div>
    <div class="aurora-blob"></div>
    <div class="aurora-blob"></div>
    <div class="aurora-blob"></div>
</div>

<div class="index-wrapper page-transition">

    {{-- ============================================================
         GLASS CARD — Glassmorphism aplicado solo en el dashboard
         backdrop-filter: blur() definido en el CSS de .glass-card
         ============================================================ --}}
    <div class="glass-card">

        {{-- Avatar con inicial del campo 'nombre' de la tabla usuarios --}}
        <div class="index-avatar">
            {{ strtoupper(mb_substr(auth()->user()->nombre, 0, 1)) }}
        </div>

        {{-- Saludo personalizado --}}
        <h1 class="index-greeting">
            Hola, <span>{{ auth()->user()->nombre }}</span>
        </h1>

        {{-- Badge con punto verde pulsante --}}
        <div class="session-badge">Sesión activa</div>

        {{-- Datos del usuario autenticado de la tabla 'usuarios' --}}
        <div class="user-info">
            <div class="info-row">
                <span class="info-label">Nombre</span>
                <span>{{ auth()->user()->nombre }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span>{{ auth()->user()->email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">ID</span>
                <span>#{{ auth()->user()->id }}</span>
            </div>
            @if(auth()->user()->es_admin)
            <div class="info-row">
                <span class="info-label">Rol</span>
                <span class="role-admin">⚡ Administrador</span>
            </div>
            @endif
        </div>

        <p class="index-note">
            Autenticación verificada correctamente.<br>
            El circuito <strong>login → sesión → dashboard</strong> funciona.
        </p>

        {{-- Botón logout: hace POST /api/logout por AJAX --}}
        <button class="btn-logout" id="logoutBtn">
            Cerrar sesión
        </button>

    </div>
</div>

@endsection

@section('scripts')
    <script src="{{ asset('js/index.js') }}"></script>
@endsection

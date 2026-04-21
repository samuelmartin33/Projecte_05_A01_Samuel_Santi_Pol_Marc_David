@extends('layouts.app')

@section('title', 'Panel Admin — VIBEZ')

@section('content')

<div class="auth-bg">
    <div class="aurora-blob"></div>
    <div class="aurora-blob"></div>
    <div class="aurora-blob"></div>
    <div class="aurora-blob"></div>
</div>

<div class="index-wrapper page-transition">
    <div class="glass-card">

        {{-- Avatar --}}
        <div class="index-avatar">
            {{ strtoupper(mb_substr(auth()->user()->nombre, 0, 1)) }}
        </div>

        {{-- Saludo --}}
        <h1 class="index-greeting">
            Panel de Administración
        </h1>
        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">
            Bienvenido, <strong>{{ auth()->user()->nombre }}</strong>
        </p>

        {{-- Verificación de rol en Blade --}}
        @if(auth()->user()->isAdmin())
            <div style="
                background: rgba(124,58,237,0.12);
                border: 1px solid rgba(124,58,237,0.3);
                border-radius: 12px;
                padding: 1rem 1.25rem;
                margin-bottom: 1.5rem;
                font-size: 0.85rem;
                color: #c4b5fd;
            ">
                Tienes acceso completo al sistema.
            </div>
        @endif

        {{-- Estadísticas básicas --}}
        @isset($stats)
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
            <div style="text-align:center; padding: 1rem; background: rgba(255,255,255,0.05); border-radius: 12px;">
                <div style="font-size: 1.8rem; font-weight: 700; color: #c4b5fd;">{{ $stats['total_usuarios'] }}</div>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Usuarios</div>
            </div>
            <div style="text-align:center; padding: 1rem; background: rgba(255,255,255,0.05); border-radius: 12px;">
                <div style="font-size: 1.8rem; font-weight: 700; color: #c4b5fd;">{{ $stats['admins'] }}</div>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Admins</div>
            </div>
            <div style="text-align:center; padding: 1rem; background: rgba(255,255,255,0.05); border-radius: 12px;">
                <div style="font-size: 1.8rem; font-weight: 700; color: #c4b5fd;">{{ $stats['usuarios_activos'] }}</div>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Activos</div>
            </div>
        </div>
        @endisset

        {{-- Ejemplo hasRole() con múltiples roles --}}
        @if(auth()->user()->hasRole('admin'))
            <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1.5rem;">
                <code style="background: rgba(255,255,255,0.08); padding: 2px 6px; border-radius: 4px;">
                    hasRole('admin') → true
                </code>
            </p>
        @endif

        {{-- Botón de logout --}}
        <button id="logoutBtn" class="btn-primary" style="margin-top: 0.5rem;">
            Cerrar sesión
        </button>

    </div>
</div>

@endsection

@section('scripts')
    <script src="{{ asset('js/index.js') }}"></script>
@endsection

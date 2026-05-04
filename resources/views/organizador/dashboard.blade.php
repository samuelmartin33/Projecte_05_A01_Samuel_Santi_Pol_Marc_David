@extends('layouts.app')

@section('title', 'Panel Organizador — VIBEZ')

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

        <h1 class="index-greeting">
            Panel de Organizador
        </h1>
        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">
            Bienvenido, <strong>{{ auth()->user()->nombre }}</strong>
        </p>

        {{-- Empresa a la que pertenece este organizador --}}
        @if(auth()->user()->isOrganizador() && auth()->user()->organizador?->empresa)
            <div style="
                background: rgba(124,58,237,0.12);
                border: 1px solid rgba(124,58,237,0.3);
                border-radius: 12px;
                padding: 1rem 1.25rem;
                margin-bottom: 1.5rem;
                font-size: 0.85rem;
                color: #c4b5fd;
            ">
                Organizas eventos para:
                <strong>{{ auth()->user()->organizador->empresa->nombre_empresa }}</strong>
            </div>
        @endif

        {{-- Acciones disponibles --}}
        <div style="
            background: rgba(255,255,255,0.04);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            color: var(--text-muted);
        ">
            Desde aquí podrás crear y gestionar eventos, vender entradas y consultar estadísticas.
        </div>

        <button id="logoutBtn" class="btn-primary" style="margin-top: 0.5rem;">
            Cerrar sesión
        </button>

    </div>
</div>

@endsection

@section('scripts')
    <script src="{{ asset('js/index.js') }}"></script>
@endsection

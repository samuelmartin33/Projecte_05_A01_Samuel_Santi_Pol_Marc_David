@extends('layouts.app')

@section('title', 'Panel Empresa — VIBEZ')

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
            Panel de Empresa
        </h1>
        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">
            Bienvenido, <strong>{{ auth()->user()->nombre }}</strong>
        </p>

        {{-- Nombre de la empresa --}}
        @if(auth()->user()->isEmpresa() && auth()->user()->empresa)
            <div style="
                background: rgba(124,58,237,0.12);
                border: 1px solid rgba(124,58,237,0.3);
                border-radius: 12px;
                padding: 1rem 1.25rem;
                margin-bottom: 1.5rem;
                font-size: 0.85rem;
                color: #c4b5fd;
            ">
                Empresa:
                <strong>{{ auth()->user()->empresa->nombre_empresa }}</strong>
                @if(auth()->user()->empresa->nif_cif)
                    &nbsp;·&nbsp; NIF/CIF: {{ auth()->user()->empresa->nif_cif }}
                @endif
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
            Desde aquí podrás gestionar cupones, patrocinios, ofertas de trabajo y visualizar estadísticas de tu empresa.
        </div>

        <button id="logoutBtn" class="btn-primary" style="margin-top: 0.5rem;">
            Cerrar sesión
        </button>

    </div>
</div>

@endsection

@section('scripts')
    @vite(['resources/js/index.js'])
@endsection

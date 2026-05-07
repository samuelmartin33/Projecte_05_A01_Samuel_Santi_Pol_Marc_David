@extends('admin.layouts.dashboard')

@section('title', 'Admin | Dashboard')

@section('content')
    <section class="dashboard-page">
        <div class="card dashboard-search-card">
            <div class="dashboard-search-inner">
                <div class="dashboard-search-field">
                    <svg class="dashboard-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="M20 20l-3.5-3.5"></path>
                    </svg>
                    <input
                        type="text"
                        class="dashboard-search-input"
                        placeholder="Buscar eventos, usuarios..."
                        aria-label="Buscar eventos, usuarios"
                    >
                </div>
            </div>
        </div>

        <section class="card dashboard-metrics-card" aria-label="Métricas del panel">
            <div class="dashboard-metrics-grid">
                <article class="dashboard-metric-item">
                    <span class="dashboard-metric-label">Eventos activos</span>
                    <span class="dashboard-metric-value">{{ $eventosActivos }}</span>
                </article>
                <article class="dashboard-metric-item">
                    <span class="dashboard-metric-label">Usuarios activos</span>
                    <span class="dashboard-metric-value">{{ $usuariosActivos }}</span>
                </article>
                <article class="dashboard-metric-item">
                    <span class="dashboard-metric-label">Empresas pendientes</span>
                    <span class="dashboard-metric-value dashboard-metric-value--danger">{{ $empresasPendientes }}</span>
                </article>
                
                <article class="dashboard-metric-item">
                    <span class="dashboard-metric-label">Pagos</span>
                    <span class="dashboard-metric-value">{{ $totalPagos }}</span>
                </article>
            </div>
        </section>

        <section class="card dashboard-actions-card" aria-label="Acciones rápidas">
            <div class="dashboard-actions-grid">
                <a class="dashboard-action-item" href="{{ route('admin.eventos.create') }}">
                    <span>Crear evento</span>
                </a>
                <a class="dashboard-action-item" href="{{ route('admin.usuarios.create') }}">
                    <span>Crear usuario</span>
                </a>
                <a class="dashboard-action-item" href="{{ route('admin.empresas.index') }}">
                    <span>Gestionar empresas</span>
                </a>
                <a class="dashboard-action-item" href="{{ route('admin.categorias.index') }}">
                    <span>Gestionar categorías</span>
                </a>
            </div>
        </section>
    </section>
@endsection

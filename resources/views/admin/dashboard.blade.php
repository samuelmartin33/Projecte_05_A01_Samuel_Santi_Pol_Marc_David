@extends('admin.layouts.dashboard')

@section('title', 'Admin | Dashboard')

@section('content')
    <section class="dashboard-page">
        <h2 class="dashboard-section-title">Datos</h2>
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
                    <span class="dashboard-metric-label">Pedidos</span>
                    <span class="dashboard-metric-value">{{ $totalPedidos }}</span>
                </article>
                <article class="dashboard-metric-item">
                    <span class="dashboard-metric-label">Categorías</span>
                    <span class="dashboard-metric-value">{{ $totalCategorias }}</span>
                </article>
                <article class="dashboard-metric-item">
                    <span class="dashboard-metric-label">Pagos</span>
                    <span class="dashboard-metric-value">{{ $totalPagos }}</span>
                </article>
            </div>
        </section>

        <h2 class="dashboard-section-title">Acciones Rápidas</h2>
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

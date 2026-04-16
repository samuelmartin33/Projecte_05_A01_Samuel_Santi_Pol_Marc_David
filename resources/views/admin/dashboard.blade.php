@extends('admin.layouts.dashboard')

@section('title', 'Admin | Dashboard')

@section('content')
    <header class="admin-header">
        <div>
            <h1>Dashboard inicial</h1>
            <p>Primera version del panel. Actualmente solo se administra el modulo de eventos.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('admin.eventos.index') }}">Gestionar eventos</a>
    </header>

    <section class="card quick-actions">
        <h2>Acciones rapidas</h2>
        <div class="quick-actions-grid">
            <a class="quick-action-item" href="{{ route('admin.eventos.create') }}">Crear evento</a>
            <span class="quick-action-item disabled">Crear usuario (proximamente)</span>
            <span class="quick-action-item disabled">Crear empresa (proximamente)</span>
            <span class="quick-action-item disabled">Crear pedido (proximamente)</span>
            <span class="quick-action-item disabled">Registrar pago (proximamente)</span>
        </div>
    </section>

    <section class="dashboard-cards">
        <article class="card stat-card">
            <h2>Total eventos</h2>
            <p class="stat-number">{{ $totalEventos }}</p>
        </article>

        <article class="card stat-card">
            <h2>Eventos activos</h2>
            <p class="stat-number">{{ $eventosActivos }}</p>
        </article>

        <article class="card stat-card muted">
            <h2>Usuarios</h2>
            <p class="soon">Proximamente</p>
        </article>

        <article class="card stat-card muted">
            <h2>Empresas</h2>
            <p class="soon">Proximamente</p>
        </article>

        <article class="card stat-card muted">
            <h2>Pedidos</h2>
            <p class="soon">Proximamente</p>
        </article>

        <article class="card stat-card muted">
            <h2>Pagos</h2>
            <p class="soon">Proximamente</p>
        </article>
    </section>
@endsection

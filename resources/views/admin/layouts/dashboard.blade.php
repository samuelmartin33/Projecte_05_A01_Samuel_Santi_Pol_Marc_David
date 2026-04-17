<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link rel="stylesheet" href="{{ asset('css/admin-eventos.css') }}">
</head>
<body>
<div class="dashboard-wrap">
    <aside class="dashboard-sidebar" id="sidebar">
        <div class="brand">
            <strong>Vibez Admin</strong>
        </div>

        <nav class="menu">
            <a href="{{ route('admin.dashboard') }}" class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Inicio</a>
            <a href="{{ route('admin.eventos.index') }}" class="menu-link {{ request()->routeIs('admin.eventos.*') ? 'active' : '' }}">Eventos</a>
            <span class="menu-link disabled">Usuarios (proximamente)</span>
            <span class="menu-link disabled">Empresas (proximamente)</span>
            <span class="menu-link disabled">Pedidos (proximamente)</span>
            <span class="menu-link disabled">Pagos (proximamente)</span>
        </nav>
    </aside>

    <div class="dashboard-main">
        <main class="admin-shell">
            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/admin-eventos.js') }}"></script>
</body>
</html>

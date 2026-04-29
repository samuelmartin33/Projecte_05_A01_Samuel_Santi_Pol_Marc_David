<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/admin-eventos.css'])
    @else
        <link rel="stylesheet" href="{{ asset('css/admin-eventos.css') }}">
    @endif
</head>
<body>
<div class="dashboard-wrap">
    <aside class="dashboard-sidebar" id="sidebar">
        <div class="brand">
            <span>VIBEZ</span>
        </div>

        {{-- Botón hamburguesa (solo visible en móvil, controlado por CSS) --}}
        <button class="menu-toggle" id="menuToggle" aria-label="Abrir menú" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav class="menu" id="mainMenu">
            <a href="{{ route('admin.dashboard') }}" class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Inicio</a>
            <a href="{{ route('admin.eventos.index') }}" class="menu-link {{ request()->routeIs('admin.eventos.*') ? 'active' : '' }}">Eventos</a>
            <a href="{{ route('admin.empresas.index') }}" class="menu-link {{ request()->routeIs('admin.empresas.*') ? 'active' : '' }}">Empresas</a>
            <a href="{{ route('admin.usuarios.index') }}" class="menu-link {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">Usuarios</a>
            <a href="{{ route('admin.pedidos.index') }}" class="menu-link {{ request()->routeIs('admin.pedidos.*') ? 'active' : '' }}">Pedidos</a>
            <a href="{{ route('admin.pagos.index') }}" class="menu-link {{ request()->routeIs('admin.pagos.*') ? 'active' : '' }}">Pagos</a>
        </nav>
    </aside>

    <div class="dashboard-main">
        <main class="admin-shell">
            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/js/admin-eventos.js'])
@else
    <script src="{{ asset('js/admin-eventos.js') }}"></script>
@endif


</body>
</html>

<!DOCTYPE html>
<html lang="es" class="@yield('html-class', '')">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title')@yield('title')@else @yield('titulo', 'VIBEZ') — Descubre tu próximo evento @endif</title>

    {{-- Fuente Inter --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- CSS y JS compilados por Vite (cuando está corriendo npm run dev / build) --}}
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/css/style.css', 'resources/js/app.js'])
    @else
        {{-- Fallback CDN: Tailwind v4 (procesa clases utility en tiempo real) --}}
        <script src="https://cdn.tailwindcss.com"></script>
        {{-- Estilos personalizados VIBEZ servidos como archivo estático --}}
        <link rel="stylesheet" href="/css/vibez.css">
    @endif

    {{-- Espacio para estilos específicos de cada página (ej: Leaflet en el detalle) --}}
    @stack('estilos')
    @yield('extra-css')
</head>
<body class="min-h-screen flex flex-col @yield('body-class', '')">

    {{-- ═══════════════════════════════════════════════════════
         CABECERA PRINCIPAL DE VIBEZ
         Fija en la parte superior, con logo y navegación
    ═══════════════════════════════════════════════════════ --}}
    @if(!View::hasSection('content'))
    <header class="nav-vibez sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">

            {{-- Logo y nombre de la app --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <div class="logo-isotipo">V</div>
                <span class="text-white font-black text-xl tracking-tight">VIBEZ</span>
            </a>

            {{-- Navegación central --}}
            <nav class="hidden md:flex items-center gap-6">
                <a href="{{ route('home') }}"
                   class="nav-link {{ request()->routeIs('home') ? 'nav-link-activo' : '' }}">
                    Explorar
                </a>
                <a href="{{ route('trabajos.index') }}"
                   class="nav-link {{ request()->routeIs('trabajos.index') ? 'nav-link-activo' : '' }}">
                    Bolsa de Trabajo
                </a>
            </nav>

            {{-- Botones de acción --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="btn-nav-ghost">Entrar</a>
                <a href="{{ route('register') }}" class="btn-nav-solido">Registro</a>
            </div>

        </div>
    </header>
    @endif

    {{-- ═══════════════════════════════════════════════════════
         CONTENIDO PRINCIPAL — cada vista rellena este bloque
    ═══════════════════════════════════════════════════════ --}}
    <main class="flex-1">
        @yield('contenido')
        @yield('content')
    </main>

    {{-- ═══════════════════════════════════════════════════════
         PIE DE PÁGINA
    ═══════════════════════════════════════════════════════ --}}
    @if(!View::hasSection('content'))
    <footer class="footer-vibez">
        <div class="max-w-7xl mx-auto px-6 py-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <div class="logo-isotipo text-sm w-7 h-7">V</div>
                <span class="font-bold text-white">VIBEZ</span>
            </div>
            <p class="text-white/50 text-sm">
                &copy; {{ date('Y') }} VIBEZ — Plataforma de eventos para jóvenes
            </p>
            <div class="flex gap-5 text-white/60 text-sm">
                <a href="#" class="hover:text-white transition-colors">Privacidad</a>
                <a href="#" class="hover:text-white transition-colors">Contacto</a>
            </div>
        </div>
    </footer>
    @endif

    {{-- Espacio para scripts específicos de cada página (ej: Leaflet, AJAX) --}}
    @stack('scripts')
    @yield('scripts')
</body>
</html>

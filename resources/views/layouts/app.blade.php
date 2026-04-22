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
            <nav class="hidden md:flex items-center gap-8">
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
                @auth
                    <div class="nav-user-info hidden sm:flex items-center gap-2">
                        <div class="nav-avatar">{{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}</div>
                        <span class="text-white/70 text-sm font-medium">{{ Auth::user()->nombre }}</span>
                    </div>
                    <div class="nav-divider"></div>
                    <button onclick="cerrarSesion()" class="btn-nav-logout">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
                        </svg>
                        Cerrar sesión
                    </button>
                @else
                    <a href="{{ route('login') }}" class="btn-nav-ghost">Entrar</a>
                    <a href="{{ route('register') }}" class="btn-nav-solido">Registro</a>
                @endauth
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
        <div class="max-w-7xl mx-auto px-6 py-12 flex flex-col items-center justify-center text-center gap-6">
            <div class="flex items-center gap-2">
                <div class="logo-isotipo text-sm w-8 h-8">V</div>
                <span class="font-bold text-white text-lg tracking-tight">VIBEZ</span>
            </div>
            <p class="text-white/40 text-sm max-w-md">
                &copy; {{ date('Y') }} VIBEZ — La plataforma definitiva de eventos para jóvenes. 
                Descubre, crea y conecta.
            </p>
            <div class="flex gap-8 text-white/50 text-sm font-medium">
                <a href="#" class="hover:text-white transition-colors">Privacidad</a>
                <a href="#" class="hover:text-white transition-colors">Términos</a>
                <a href="#" class="hover:text-white transition-colors">Contacto</a>
            </div>
        </div>
    </footer>
    @endif

    {{-- Espacio para scripts específicos de cada página (ej: Leaflet, AJAX) --}}
    @stack('scripts')
    @yield('scripts')

    @auth
    <script>
    function cerrarSesion() {
        fetch('/api/logout', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        }).then(function() {
            window.location.href = '/';
        });
    }
    </script>
    @endauth
</body>
</html>

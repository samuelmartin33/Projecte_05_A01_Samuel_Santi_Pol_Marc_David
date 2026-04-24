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
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        {{-- Fallback CDN: Tailwind v4 (procesa clases utility en tiempo real) --}}
        <script src="https://cdn.tailwindcss.com"></script>
        {{-- Estilos personalizados VIBEZ servidos como archivo estático --}}
        <link rel="stylesheet" href="/css/vibez.css">
    @endif

    @if (request()->routeIs('login') || request()->routeIs('register'))
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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

            {{-- Logo corporativo --}}
            <a href="{{ route('home') }}" class="nav-logo-link group">
                <img src="{{ asset('images/logo_vibez_white.png') }}"
                     alt="VIBEZ"
                     class="nav-logo-img">
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
                @auth
                <a href="{{ route('social') }}"
                   class="nav-link nav-social-link {{ request()->routeIs('social') ? 'nav-link-activo' : '' }}">
                    Social
                    <span class="nav-badge-social" id="nav-badge-social" style="display:none">0</span>
                </a>
                @endauth
            </nav>

            {{-- Botones de acción: guest → login/registro | auth → avatar --}}
            <div class="flex items-center gap-3">
                @guest
                    <a href="{{ route('login') }}" class="btn-nav-ghost">Entrar</a>
                    <a href="{{ route('register') }}" class="btn-nav-solido">Registro</a>
                @else
                    {{-- Avatar con dropdown de perfil --}}
                    <div class="nav-avatar-wrapper" id="navAvatarWrapper">

                        {{-- Botón circular con foto o iniciales --}}
                        {{-- position:relative para poder colocar el badge de mood encima --}}
                        <div style="position:relative;display:inline-block">
                            <button class="nav-avatar" id="navAvatarBtn"
                                    onclick="toggleNavDropdown()"
                                    aria-haspopup="true" aria-expanded="false">
                                @if(Auth::user()->foto_url)
                                    <img src="{{ Auth::user()->foto_url }}"
                                         alt="{{ Auth::user()->nombre }}"
                                         class="nav-avatar-img">
                                @else
                                    <span class="nav-avatar-iniciales">
                                        {{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}{{ strtoupper(substr(Auth::user()->apellido1 ?? '', 0, 1)) }}
                                    </span>
                                @endif
                            </button>

                            {{-- Badge de mood: solo el emoji, flotante en la esquina del avatar --}}
                            @if(Auth::user()->mood)
                                <span class="nav-mood-badge" title="{{ Auth::user()->mood }}">
                                    {{-- Extraemos solo el emoji (primera palabra antes del espacio) --}}
                                    {{ explode(' ', Auth::user()->mood, 2)[0] }}
                                </span>
                            @endif
                        </div>

                        {{-- Dropdown --}}
                        <div class="nav-dropdown" id="navDropdown" style="display:none">

                            {{-- Cabecera: nombre y mood completo si lo tiene --}}
                            <div class="nav-dropdown-header">
                                <p class="nav-dropdown-nombre">{{ Auth::user()->nombre }} {{ Auth::user()->apellido1 }}</p>
                                <p class="nav-dropdown-email">{{ Auth::user()->email }}</p>
                                @if(Auth::user()->mood)
                                    <p class="nav-dropdown-mood">{{ Auth::user()->mood }}</p>
                                @endif
                            </div>

                            <div class="nav-dropdown-divider"></div>

                            {{-- Perfil --}}
                            <a href="{{ route('perfil') }}" class="nav-dropdown-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Mi perfil
                            </a>

                            {{-- Amigos --}}
                            <a href="{{ route('perfil') }}#amigos" class="nav-dropdown-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Amigos
                            </a>

                            {{-- Panel de administración: solo visible para admins --}}
                            @if(Auth::user()->es_admin)
                                <a href="{{ route('admin.dashboard') }}" class="nav-dropdown-item" style="color:#7c3aed;font-weight:700">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Panel Admin
                                </a>
                            @endif

                            <div class="nav-dropdown-divider"></div>

                            {{-- Cerrar sesión --}}
                            <button class="nav-dropdown-item nav-dropdown-logout"
                                    onclick="cerrarSesion()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Cerrar sesión
                            </button>

                        </div>
                    </div>
                @endguest
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
            <div class="flex items-center">
                <img src="{{ asset('images/logo_vibez.png') }}"
                     alt="VIBEZ"
                     class="footer-logo-img">
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

    @auth
    {{-- ── Badge Social en el navbar: se actualiza en todas las páginas ── --}}
    <script>
    (function () {
        // Consulta el contador cada 30 segundos y actualiza el badge del navbar
        function refrescarBadgeSocial() {
            fetch('/api/social/contador', { headers: { 'Accept': 'application/json' } })
                .then(function (r) { return r.json(); })
                .then(function (resp) {
                    if (!resp.exito) return;
                    var badge = document.getElementById('nav-badge-social');
                    if (!badge) return;
                    var total = resp.datos.total;
                    if (total > 0) {
                        badge.textContent   = total > 99 ? '99+' : total;
                        badge.style.display = 'inline-flex';
                    } else {
                        badge.style.display = 'none';
                    }
                })
                .catch(function () { /* silencioso */ });
        }

        // Primera consulta al cargar la página
        refrescarBadgeSocial();
        // Refresco periódico cada 30 segundos
        setInterval(refrescarBadgeSocial, 30000);
    })();
    </script>

    {{-- ── Scripts globales de navegación (navbar avatar, logout) ── --}}
    <script>
    /**
     * Abre/cierra el dropdown del avatar de usuario en la navbar.
     * Gestiona el aria-expanded para accesibilidad.
     */
    function toggleNavDropdown() {
        const dropdown = document.getElementById('navDropdown');
        const btn      = document.getElementById('navAvatarBtn');
        const abierto  = dropdown.style.display === 'block';

        dropdown.style.display = abierto ? 'none' : 'block';
        btn.setAttribute('aria-expanded', String(!abierto));

        // Animar entrada
        if (!abierto) {
            dropdown.style.animation = 'none';
            dropdown.offsetHeight;  // reflow
            dropdown.style.animation = 'dropdownEntrar 0.18s ease';
        }
    }

    /** Cierra el dropdown si se pulsa fuera */
    document.addEventListener('click', function(e) {
        const wrapper = document.getElementById('navAvatarWrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            const dropdown = document.getElementById('navDropdown');
            const btn      = document.getElementById('navAvatarBtn');
            if (dropdown) dropdown.style.display = 'none';
            if (btn)      btn.setAttribute('aria-expanded', 'false');
        }
    });

    /**
     * Cierra la sesión del usuario mediante AJAX.
     * Redirige a la landing tras el logout.
     */
    function cerrarSesion() {
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('/api/logout', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        })
        .then(() => {
            document.body.style.transition = 'opacity 0.3s';
            document.body.style.opacity    = '0';
            setTimeout(() => { window.location.href = '/'; }, 320);
        })
        .catch(() => { window.location.href = '/'; });
    }
    </script>
    @endauth
</body>
</html>

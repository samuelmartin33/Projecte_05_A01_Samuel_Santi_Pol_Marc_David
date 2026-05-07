<!DOCTYPE html>
<html lang="es" class="@yield('html-class', '')">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title')@yield('title')@else @yield('titulo', 'VIBEZ') — Descubre tu próximo evento @endif</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo_vibez.png') }}">

    {{-- Fuentes editoriales VIBEZ --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Bebas+Neue&family=Archivo:wght@400;500;600;700;800;900&family=Archivo+Narrow:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/css/app-static.css">

    @if (request()->routeIs('login') || request()->routeIs('register'))
        <link rel="stylesheet" href="{{ asset('css/auth-vibez.css') }}">
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
    <header class="sticky top-0 z-50 bg-paper border-b border-ink/15">
        <div class="max-w-7xl mx-auto px-6 sm:px-10 flex items-center justify-between h-14">

            @php $esEmpresa = Auth::check() && Auth::user()->isEmpresa(); @endphp

            {{-- Wordmark --}}
            <a href="{{ $esEmpresa ? route('empresa.home') : route('home') }}"
               class="font-display font-black text-2xl tracking-brutal text-ink
                      hover:text-lilac transition-colors duration-100 select-none">
                VIBEZ
            </a>

            {{-- Navegación central (desktop) --}}
            <nav class="hidden md:flex items-center gap-8">
                @if($esEmpresa)
                    <a href="{{ route('empresa.home') }}"
                       class="font-mono text-xs uppercase tracking-widest transition-colors duration-100
                              {{ request()->routeIs('empresa.home') ? 'text-ink' : 'text-muted hover:text-ink' }}">
                        Panel
                    </a>
                    <a href="{{ route('empresa.candidaturas.ofertas') }}"
                       class="font-mono text-xs uppercase tracking-widest transition-colors duration-100
                              {{ request()->routeIs('empresa.candidaturas.*') ? 'text-ink' : 'text-muted hover:text-ink' }}">
                        Candidaturas
                    </a>
                @else
                    <a href="{{ route('home') }}"
                       class="font-mono text-xs uppercase tracking-widest transition-colors duration-100
                              {{ request()->routeIs('home') ? 'text-ink' : 'text-muted hover:text-ink' }}">
                        Explorar
                    </a>
                    <a href="{{ route('trabajos.index') }}"
                       class="font-mono text-xs uppercase tracking-widest transition-colors duration-100
                              {{ request()->routeIs('trabajos.index') ? 'text-ink' : 'text-muted hover:text-ink' }}">
                        Trabajo
                    </a>
                    @auth
                    <a href="{{ route('social') }}"
                       class="font-mono text-xs uppercase tracking-widest transition-colors duration-100 relative
                              {{ request()->routeIs('social') ? 'text-ink' : 'text-muted hover:text-ink' }}">
                        Social
                        <span class="nav-badge-social" id="nav-badge-social" style="display:none">0</span>
                    </a>
                    @endauth
                @endif
            </nav>

            {{-- Botones acción --}}
            <div class="flex items-center gap-3">
                @guest
                    <a href="{{ route('login') }}"
                       class="hidden sm:block font-mono text-xs uppercase tracking-widest
                              text-ink/55 hover:text-ink transition-colors duration-100">
                        Entrar
                    </a>
                    <a href="{{ route('register') }}"
                       class="btn-ink font-mono text-xs uppercase tracking-widest px-5 py-2.5">
                        <span>Registro &nbsp;→</span>
                    </a>
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

                            @if($esEmpresa)
                                {{-- Panel empresa --}}
                                <a href="{{ route('empresa.home') }}" class="nav-dropdown-item" style="color:#7c3aed;font-weight:700">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    Panel Empresa
                                </a>
                                <a href="{{ route('empresa.candidaturas.ofertas') }}" class="nav-dropdown-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Revisar Currículums
                                </a>
                            @else
                                {{-- Mis entradas (solo usuarios normales) --}}
                                @if(!Auth::user()->isAdmin())
                                <a href="{{ route('entradas.mis-entradas') }}" class="nav-dropdown-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                    </svg>
                                    Mis entradas
                                </a>
                                @endif

                                {{-- Amigos --}}
                                <a href="{{ route('perfil') }}#amigos" class="nav-dropdown-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Amigos
                                </a>
                            @endif

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

                {{-- Botón hamburguesa: solo visible en móvil (CSS controla visibilidad) --}}
                <button class="nav-hamburger" id="navHamburger"
                        onclick="toggleMenuMovil()"
                        aria-label="Abrir menú" aria-expanded="false">
                    <svg class="icono-ham" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg class="icono-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display:none">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

        </div>
    </header>

    {{-- ═══════════════════════════════════════════════════════
         MENÚ MÓVIL — panel lateral deslizable
    ═══════════════════════════════════════════════════════ --}}

    {{-- Fondo oscuro que aparece detrás del panel --}}
    <div class="nav-movil-overlay" id="navMovilOverlay" onclick="cerrarMenuMovil()"></div>

    {{-- Panel lateral que entra desde la izquierda --}}
    <nav class="nav-movil-panel" id="navMovilPanel" aria-label="Menú de navegación móvil">

        {{-- Cabecera del panel --}}
        <div class="nav-movil-cabecera">
            <img src="{{ asset('images/logo_vibez_white.png') }}" alt="VIBEZ" class="nav-movil-logo">
            <button class="nav-movil-cerrar" onclick="cerrarMenuMovil()" aria-label="Cerrar menú">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Info del usuario autenticado --}}
        @auth
        <div class="nav-movil-usuario">
            <div class="nav-movil-avatar-sm">
                @if(Auth::user()->foto_url)
                    <img src="{{ Auth::user()->foto_url }}" alt="{{ Auth::user()->nombre }}">
                @else
                    {{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}{{ strtoupper(substr(Auth::user()->apellido1 ?? '', 0, 1)) }}
                @endif
            </div>
            <div>
                <p class="nav-movil-usuario-nombre">{{ Auth::user()->nombre }} {{ Auth::user()->apellido1 }}</p>
                <p class="nav-movil-usuario-email">{{ Auth::user()->email }}</p>
            </div>
        </div>
        @endauth

        <div class="nav-movil-divisor"></div>

        @if($esEmpresa)
            {{-- Empresa: enlaces propios --}}
            <a href="{{ route('empresa.home') }}"
               class="nav-movil-link {{ request()->routeIs('empresa.home') ? 'nav-movil-activo' : '' }}"
               onclick="cerrarMenuMovil()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Panel
            </a>
            <a href="{{ route('empresa.candidaturas.ofertas') }}"
               class="nav-movil-link {{ request()->routeIs('empresa.candidaturas.*') ? 'nav-movil-activo' : '' }}"
               onclick="cerrarMenuMovil()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Candidaturas
            </a>
        @else
            {{-- Usuarios y visitantes: explorar, bolsa y social --}}
            <a href="{{ route('home') }}"
               class="nav-movil-link {{ request()->routeIs('home') ? 'nav-movil-activo' : '' }}"
               onclick="cerrarMenuMovil()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Explorar
            </a>
            <a href="{{ route('trabajos.index') }}"
               class="nav-movil-link {{ request()->routeIs('trabajos.index') ? 'nav-movil-activo' : '' }}"
               onclick="cerrarMenuMovil()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Bolsa de Trabajo
            </a>
            @auth
            <a href="{{ route('social') }}"
               class="nav-movil-link {{ request()->routeIs('social') ? 'nav-movil-activo' : '' }}"
               onclick="cerrarMenuMovil()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                Social
            </a>
            @endauth
        @endif

        <div class="nav-movil-divisor"></div>

        @auth
        {{-- Perfil y cerrar sesión --}}
        <a href="{{ route('perfil') }}" class="nav-movil-link" onclick="cerrarMenuMovil()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Mi perfil
        </a>

        <button class="nav-movil-link nav-movil-logout" onclick="cerrarSesion()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Cerrar sesión
        </button>
        @endauth

        {{-- Acceso para invitados --}}
        @guest
        <div class="nav-movil-divisor"></div>
        <a href="{{ route('login') }}" class="nav-movil-link" onclick="cerrarMenuMovil()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            Entrar
        </a>
        <a href="{{ route('register') }}" class="nav-movil-link" onclick="cerrarMenuMovil()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Registro
        </a>
        @endguest

    </nav>

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
    <footer class="bg-ink text-paper border-t border-ink">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 py-8 border-b border-paper/10">
                <div>
                    <span class="font-display font-black text-2xl tracking-brutal select-none">VIBEZ</span>
                    <p class="font-mono text-xs uppercase tracking-widest text-paper/35 mt-1">La plataforma de tu escena</p>
                </div>
                <nav class="flex flex-wrap gap-6 sm:gap-8 font-mono text-xs uppercase tracking-widest text-paper/35">
                    <a href="{{ route('home') }}" class="hover:text-paper transition-colors duration-100">Explorar</a>
                    <a href="{{ route('trabajos.index') }}" class="hover:text-paper transition-colors duration-100">Trabajo</a>
                    <a href="#" class="hover:text-paper transition-colors duration-100">Privacidad</a>
                    <a href="#" class="hover:text-paper transition-colors duration-100">Contacto</a>
                </nav>
            </div>
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 py-5">
                <p class="font-mono text-xs text-paper/25">&copy; {{ date('Y') }} VIBEZ — Todos los derechos reservados.</p>
                <p class="font-mono text-xs text-paper/20">{{ now()->format('d.m.y — H:i') }}</p>
            </div>
        </div>
    </footer>
    @endif

    {{-- Espacio para scripts específicos de cada página (ej: Leaflet, AJAX) --}}
    @stack('scripts')
    @yield('scripts')

    {{-- Lógica global de navegación: menú móvil, badge social, dropdown avatar y logout.
         Las funciones viven en app-nav.js para evitar duplicar código en el HTML.
         El archivo se carga al final del <body> (defer implícito) para no bloquear el render. --}}
    <script src="{{ asset('js/app-nav.js') }}"></script>
</body>
</html>

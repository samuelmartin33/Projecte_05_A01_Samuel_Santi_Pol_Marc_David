<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/app-static.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-eventos.css') }}">
</head>
<body>

{{-- ═══════════════════════════════════════════════════════
     CABECERA PRINCIPAL DE VIBEZ
     Misma cabecera que en el home
═══════════════════════════════════════════════════════ --}}
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
            @elseif(Auth::check() && Auth::user()->es_admin)
                <a href="{{ route('admin.dashboard') }}"
                   class="font-mono text-xs uppercase tracking-widest transition-colors duration-100
                          {{ request()->routeIs('admin.dashboard') ? 'text-ink' : 'text-muted hover:text-ink' }}">
                    Inicio
                </a>
                <a href="{{ route('admin.eventos.index') }}"
                   class="font-mono text-xs uppercase tracking-widest transition-colors duration-100
                          {{ request()->routeIs('admin.eventos.*') ? 'text-ink' : 'text-muted hover:text-ink' }}">
                    Eventos
                </a>
                <a href="{{ route('admin.empresas.index') }}"
                   class="font-mono text-xs uppercase tracking-widest transition-colors duration-100
                          {{ request()->routeIs('admin.empresas.*') ? 'text-ink' : 'text-muted hover:text-ink' }}">
                    Empresas
                </a>
                <a href="{{ route('admin.usuarios.index') }}"
                   class="font-mono text-xs uppercase tracking-widest transition-colors duration-100
                          {{ request()->routeIs('admin.usuarios.*') ? 'text-ink' : 'text-muted hover:text-ink' }}">
                    Usuarios
                </a>
                <a href="{{ route('admin.categorias.index') }}"
                   class="font-mono text-xs uppercase tracking-widest transition-colors duration-100
                          {{ request()->routeIs('admin.categorias.*') ? 'text-ink' : 'text-muted hover:text-ink' }}">
                    Categorías
                </a>
                <a href="{{ route('admin.pedidos.index') }}"
                   class="font-mono text-xs uppercase tracking-widest transition-colors duration-100
                          {{ request()->routeIs('admin.pedidos.*') ? 'text-ink' : 'text-muted hover:text-ink' }}">
                    Pedidos
                </a>
                <a href="{{ route('admin.pagos.index') }}"
                   class="font-mono text-xs uppercase tracking-widest transition-colors duration-100
                          {{ request()->routeIs('admin.pagos.*') ? 'text-ink' : 'text-muted hover:text-ink' }}">
                    Pagos
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

                        @if(Auth::check() && Auth::user()->es_admin)
                            <a href="{{ route('home') }}" class="nav-dropdown-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3 12l2-3m0 0l7-4 7 4M5 9v7a1 1 0 001 1h12a1 1 0 001-1V9m-9 13l-4-4m0 0l-2-2m2 2l2-2m-2 2l4 4m0 0l2 2m-2-2l-2 2"/>
                                </svg>
                                Volver al inicio
                            </a>
                        @elseif($esEmpresa)
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

<div class="dashboard-wrap">
    <div class="dashboard-main">
        <main class="admin-shell">
            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/app-nav.js') }}"></script>
<script src="{{ asset('js/admin-eventos.js') }}"></script>


</body>
</html>

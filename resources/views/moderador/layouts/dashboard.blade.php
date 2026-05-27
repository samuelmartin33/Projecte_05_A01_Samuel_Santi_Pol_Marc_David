<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Moderador — VIBEZ')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/admin-eventos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-vibez.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-forms.css') }}">
    @stack('estilos')
</head>
<body>

<div class="adm-shell">

    {{-- ═══════════════════════════════════════════════════════
         SIDEBAR MODERADOR
    ═══════════════════════════════════════════════════════ --}}
    <aside class="adm-side">

        {{-- Logo --}}
        <a href="{{ route('moderador.dashboard') }}" class="adm-side-logo">
            <img src="{{ asset('images/logo_vibez_white.png') }}" alt="VIBEZ">
            <span>VIBEZ</span>
        </a>

        {{-- Tipo de panel --}}
        <div class="adm-side-badge">Panel · Moderador</div>

        {{-- Navegación principal --}}
        <nav class="adm-nav-group">

            <a href="{{ route('moderador.dashboard') }}"
               class="adm-nav-item {{ request()->routeIs('moderador.dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                    <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('moderador.posts.index') }}"
               class="adm-nav-item {{ request()->routeIs('moderador.posts.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z"/>
                    <line x1="9" y1="11" x2="15" y2="11"/><line x1="9" y1="15" x2="15" y2="15"/>
                </svg>
                Publicaciones
            </a>

            <a href="{{ route('moderador.historias.index') }}"
               class="adm-nav-item {{ request()->routeIs('moderador.historias.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                Historias
            </a>

            <a href="{{ route('moderador.comentarios.index') }}"
               class="adm-nav-item {{ request()->routeIs('moderador.comentarios.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                </svg>
                Comentarios
            </a>

            <a href="{{ route('moderador.usuarios.index') }}"
               class="adm-nav-item {{ request()->routeIs('moderador.usuarios.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <line x1="18" y1="8" x2="23" y2="13"/><line x1="23" y1="8" x2="18" y2="13"/>
                </svg>
                Usuarios
            </a>

        </nav>

        <div class="adm-side-divider"></div>

        {{-- Botón hamburguesa: solo visible en móvil (≤860px) --}}
        <button id="adm-hamburger" onclick="toggleAdmNav()" aria-label="Menú de navegación" aria-expanded="false">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <line x1="3" y1="6" x2="21" y2="6"/>
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>

        {{-- Footer: avatar del usuario con dropdown --}}
        <div class="adm-side-foot" id="admSideFoot" onclick="toggleAdmDropdown()">

            @if(Auth::user()->foto_url)
                <img src="{{ Auth::user()->foto_url }}" alt=""
                     style="width:36px;height:36px;border-radius:50%;object-fit:cover;flex-shrink:0;">
            @else
                <div class="adm-side-avatar">
                    {{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}{{ strtoupper(substr(Auth::user()->apellido1 ?? '', 0, 1)) }}
                </div>
            @endif

            <div style="min-width:0;flex:1">
                <div class="adm-side-foot-name">{{ Auth::user()->nombre }} {{ Auth::user()->apellido1 }}</div>
                <div class="adm-side-foot-role">Moderador</div>
            </div>

            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 style="width:13px;height:13px;flex-shrink:0;color:var(--adm-ink-dim)">
                <path d="M18 15l-6-6-6 6"/>
            </svg>

            {{-- Dropdown --}}
            <div class="adm-side-dropdown" id="admDropdown" style="display:none"
                 onclick="event.stopPropagation()">

                <a href="{{ route('perfil') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Mi perfil
                </a>

                <a href="{{ route('home') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 12l9-9 9 9M5 10v10h4v-6h6v6h4V10"/>
                    </svg>
                    Ir al inicio
                </a>

                <div class="adm-dropdown-divider"></div>

                <button class="adm-logout" onclick="cerrarSesion()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Cerrar sesión
                </button>

            </div>
        </div>

    </aside>

    {{-- ═══════════════════════════════════════════════════════
         ÁREA PRINCIPAL
    ═══════════════════════════════════════════════════════ --}}
    <main class="adm-main">
        @yield('content')
    </main>

</div>{{-- /adm-shell --}}

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/app-nav.js') }}"></script>
<script src="{{ asset('js/admin-eventos.js') }}"></script>

<script src="{{ asset('js/moderador-dashboard.js') }}"></script>
{{-- JS en public/js/moderador-dashboard.js --}}

@stack('scripts')
</body>
</html>

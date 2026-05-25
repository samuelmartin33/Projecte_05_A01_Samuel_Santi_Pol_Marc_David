<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin — VIBEZ')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/admin-eventos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-vibez.css') }}">
    @stack('estilos')
</head>
<body>

@php $esEmpresa = Auth::check() && Auth::user()->isEmpresa(); @endphp

<div class="adm-shell">

    {{-- ═══════════════════════════════════════════════════════
         SIDEBAR
    ═══════════════════════════════════════════════════════ --}}
    <aside class="adm-side">

        {{-- Logo --}}
        <a href="{{ $esEmpresa ? route('empresa.home') : route('admin.dashboard') }}"
           class="adm-side-logo">
            <img src="{{ asset('images/logo_vibez_white.png') }}" alt="VIBEZ">
            <span>VIBEZ</span>
        </a>

        {{-- Tipo de panel --}}
        <div class="adm-side-badge">
            {{ $esEmpresa ? 'Panel · Empresa' : 'Panel · Admin' }}
        </div>

        {{-- Navegación principal --}}
        <nav class="adm-nav-group">
            @if($esEmpresa)

                <a href="{{ route('empresa.home') }}"
                   class="adm-nav-item {{ request()->routeIs('empresa.home') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                    </svg>
                    Panel
                </a>

                <a href="{{ route('empresa.candidaturas.ofertas') }}"
                   class="adm-nav-item {{ request()->routeIs('empresa.candidaturas.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Candidaturas
                </a>

            @else

                <a href="{{ route('admin.dashboard') }}"
                   class="adm-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.eventos.index') }}"
                   class="adm-nav-item {{ request()->routeIs('admin.eventos.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    Eventos
                    @if(isset($eventosActivos))
                        <span class="adm-nav-count">{{ $eventosActivos }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.usuarios.index') }}"
                   class="adm-nav-item {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    Usuarios
                </a>

                <a href="{{ route('admin.empresas.index') }}"
                   class="adm-nav-item {{ request()->routeIs('admin.empresas.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16"/>
                        <path d="M3 21h18"/>
                        <path d="M9 9h1m-1 4h1m4-4h1m-1 4h1"/>
                    </svg>
                    Empresas
                    @if(isset($empresasPendientes) && $empresasPendientes > 0)
                        <span class="adm-nav-count">{{ $empresasPendientes }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.categorias.index') }}"
                   class="adm-nav-item {{ request()->routeIs('admin.categorias.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                        <line x1="7" y1="7" x2="7.01" y2="7"/>
                    </svg>
                    Categorías
                </a>

                <a href="{{ route('admin.pedidos.index') }}"
                   class="adm-nav-item {{ request()->routeIs('admin.pedidos.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M2 9V7a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v2a2 2 0 0 0 0 4v2a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-2a2 2 0 0 0 0-4z"/>
                        <line x1="13" y1="5" x2="13" y2="19"/>
                    </svg>
                    Pedidos
                </a>

                <a href="{{ route('admin.pagos.index') }}"
                   class="adm-nav-item {{ request()->routeIs('admin.pagos.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="1" x2="12" y2="23"/>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                    Pagos
                </a>

                     <a href="{{ route('admin.cupones.index') }}"
                         class="adm-nav-item {{ request()->routeIs('admin.cupones.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                    Cupones
                </a>

                <a href="{{ route('admin.facturacion.index') }}"
                   class="adm-nav-item {{ request()->routeIs('admin.facturacion.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                    </svg>
                    Facturación
                </a>

            @endif
        </nav>

        <div class="adm-side-divider"></div>

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
                <div class="adm-side-foot-role">{{ $esEmpresa ? 'Empresa' : 'Admin' }}</div>
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

<script>
    /* Abre/cierra el dropdown del sidebar */
    function toggleAdmDropdown() {
        var d = document.getElementById('admDropdown');
        d.style.display = d.style.display === 'none' ? 'block' : 'none';
    }
    /* Cierra al hacer clic fuera */
    document.addEventListener('click', function (e) {
        var foot = document.getElementById('admSideFoot');
        var drop = document.getElementById('admDropdown');
        if (drop && foot && !foot.contains(e.target)) {
            drop.style.display = 'none';
        }
    });
</script>

@stack('scripts')
</body>
</html>

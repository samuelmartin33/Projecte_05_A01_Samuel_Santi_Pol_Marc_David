{{--
    Componente: <x-nav />
    Uso: Navbar editorial VIBEZ — barra superior lila/paper con diseño Swiss.
    Props opcionales: ninguna. Detecta rol via Auth.
--}}
@php $esEmpresa = Auth::check() && Auth::user()->isEmpresa(); @endphp

<header class="sticky top-0 z-50 bg-paper border-b border-ink/15">
    <div class="max-w-7xl mx-auto px-6 sm:px-10 flex items-center justify-between h-14">

        {{-- Logotipo — wordmark tipográfico --}}
        <a href="{{ $esEmpresa ? route('empresa.home') : route('home') }}"
           class="font-display font-black text-2xl tracking-brutal text-ink
                  hover:text-lilac transition-colors duration-100 select-none">
            VIBEZ
        </a>

        {{-- Navegación central (desktop) --}}
        <nav class="hidden md:flex items-center gap-8">
            @if($esEmpresa)
                <a href="{{ route('empresa.home') }}"
                   class="font-mono text-xs uppercase tracking-widest
                          {{ request()->routeIs('empresa.home') ? 'text-ink' : 'text-muted hover:text-ink' }}
                          transition-colors duration-100">
                    Panel
                </a>
                <a href="{{ route('empresa.candidaturas.ofertas') }}"
                   class="font-mono text-xs uppercase tracking-widest
                          {{ request()->routeIs('empresa.candidaturas.*') ? 'text-ink' : 'text-muted hover:text-ink' }}
                          transition-colors duration-100">
                    Candidaturas
                </a>
            @else
                <a href="{{ route('home') }}"
                   class="font-mono text-xs uppercase tracking-widest
                          {{ request()->routeIs('home') ? 'text-ink' : 'text-muted hover:text-ink' }}
                          transition-colors duration-100">
                    Explorar
                </a>
                <a href="{{ route('trabajos.index') }}"
                   class="font-mono text-xs uppercase tracking-widest
                          {{ request()->routeIs('trabajos.index') ? 'text-ink' : 'text-muted hover:text-ink' }}
                          transition-colors duration-100">
                    Trabajo
                </a>
                @auth
                <a href="{{ route('social') }}"
                   class="font-mono text-xs uppercase tracking-widest
                          {{ request()->routeIs('social') ? 'text-ink' : 'text-muted hover:text-ink' }}
                          transition-colors duration-100 relative">
                    Social
                    <span class="nav-badge-social" id="nav-badge-social" style="display:none">0</span>
                </a>
                @endauth
            @endif
        </nav>

        {{-- Botones de acción --}}
        <div class="flex items-center gap-3">
            @guest
                <a href="{{ route('login') }}"
                   class="hidden sm:block font-mono text-xs uppercase tracking-widest text-ink/60
                          hover:text-ink transition-colors duration-100">
                    Entrar
                </a>
                <a href="{{ route('register') }}"
                   class="bg-ink text-paper font-mono text-xs uppercase tracking-widest
                          px-5 py-2.5 hover:bg-plum transition-colors duration-75">
                    Registro &nbsp;→
                </a>
            @else
                {{-- Avatar con dropdown --}}
                <div class="nav-avatar-wrapper" id="navAvatarWrapper">
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
                        @if(Auth::user()->mood)
                            <span class="nav-mood-badge" title="{{ Auth::user()->mood }}">
                                {{ explode(' ', Auth::user()->mood, 2)[0] }}
                            </span>
                        @endif
                    </div>

                    <div class="nav-dropdown" id="navDropdown" style="display:none">
                        <div class="nav-dropdown-header">
                            <p class="nav-dropdown-nombre">{{ Auth::user()->nombre }} {{ Auth::user()->apellido1 }}</p>
                            <p class="nav-dropdown-email">{{ Auth::user()->email }}</p>
                            @if(Auth::user()->mood)
                                <p class="nav-dropdown-mood">{{ Auth::user()->mood }}</p>
                            @endif
                        </div>
                        <div class="nav-dropdown-divider"></div>

                        <a href="{{ route('perfil') }}" class="nav-dropdown-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Mi perfil
                        </a>

                        @if($esEmpresa)
                            <a href="{{ route('empresa.home') }}" class="nav-dropdown-item" style="color:#4E3A96;font-weight:700">
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
                                Candidaturas
                            </a>
                        @else
                            @if(!Auth::user()->isAdmin())
                            <a href="{{ route('entradas.mis-entradas') }}" class="nav-dropdown-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                </svg>
                                Mis entradas
                            </a>
                            @endif
                            <a href="{{ route('perfil') }}#amigos" class="nav-dropdown-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Amigos
                            </a>
                        @endif

                        @if(Auth::user()->es_admin)
                            <a href="{{ route('admin.dashboard') }}" class="nav-dropdown-item" style="color:#4E3A96;font-weight:700">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Panel Admin
                            </a>
                        @endif

                        <div class="nav-dropdown-divider"></div>

                        <button class="nav-dropdown-item nav-dropdown-logout" onclick="cerrarSesion()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Cerrar sesión
                        </button>
                    </div>
                </div>
            @endguest

            {{-- Hamburguesa (mobile) --}}
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

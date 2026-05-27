

<header id="vibez-home-nav" style="position:sticky;top:0;z-index:50;background:rgba(7,6,12,0.92);backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px);border-bottom:1px solid var(--line);transition:all 0.3s ease;">
  <div class="nav-inner-pad" style="max-width:1480px;margin:0 auto;padding:18px 32px;display:flex;align-items:center;justify-content:space-between;gap:16px;">

    {{-- Logo pill con glow morado --}}
    <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:12px;text-decoration:none;color:var(--ink);flex-shrink:0;">
      <div style="position:relative;padding:6px;display:flex;align-items:center;background:linear-gradient(135deg,rgba(168,85,247,0.18),rgba(124,58,237,0.08));border:1px solid rgba(168,85,247,0.45);border-radius:999px;box-shadow:0 0 24px rgba(168,85,247,0.35),inset 0 0 12px rgba(168,85,247,0.12);">
        <img src="{{ asset('images/logo_vibez_white.png') }}" alt="VIBEZ" class="vibez-logo-img" style="height:58px;width:auto;object-fit:contain;filter:drop-shadow(0 0 12px rgba(168,85,247,0.7));">
      </div>
    </a>

    {{-- Navegación central (solo desktop, oculta en móvil) --}}
    <nav id="vibez-nav-desktop">
      @auth
        @php
          $esAdmin       = Auth::user()->es_admin;
          $esEmpresa     = Auth::user()->isEmpresa();
          $esPortero     = Auth::user()->isPortero();
          $esOrganizador = !$esPortero && Auth::user()->isOrganizador();
          if ($esPortero) {
            // Portero ve los mismos enlaces que un cliente normal
            $navLinks = array_filter([
              ['Para ti',     route('home'),                        'home'],
              ['Eventos',     route('eventos.index'),               'eventos.index'],
              ['Mis tickets', route('entradas.mis-entradas'),       'entradas.mis-entradas'],
              ['Bolsa',       route('trabajos.index'),              'trabajos.index'],
              ['Social',      route('social'),                      'social'],
            ]);
          } elseif ($esEmpresa) {
            $navLinks = [
              ['Panel',          route('empresa.home'),                 'empresa.home'],
              ['Equipo',         route('empresa.equipo.index'),         'empresa.equipo.*'],
              ['Candidaturas',   route('empresa.candidaturas.ofertas'), 'empresa.candidaturas.*'],
              ['Administración', route('empresa.facturacion.index'),    'empresa.facturacion.*'],
              ['Perfil Fiscal',  route('empresa.perfil-fiscal'),        'empresa.perfil-fiscal'],
              ['Crear evento',   route('empresa.eventos.create'),       'empresa.eventos.create'],
              ['Crear oferta',   route('empresa.ofertas.create'),       'empresa.ofertas.create'],
            ];
          } else {
            $navLinks = array_filter([
              ['Para ti',     route('home'),                        'home'],
              ['Eventos',     route('eventos.index'),               'eventos.index'],
              ['Calendario',  route('calendario'),                  'calendario'],
              !$esAdmin ? ['Mis tickets', route('entradas.mis-entradas'), 'entradas.mis-entradas'] : null,
              ['Bolsa',       route('trabajos.index'),              'trabajos.index'],
              ['Social',      route('social'),                      'social'],
              $esOrganizador ? ['Mis horas', route('horas.index'), 'horas.index'] : null,
            ]);
          }
        @endphp
      @else
        @php
          $navLinks = [
            ['Explorar',         route('home'),            'home'],
            ['Eventos',          route('eventos.index'),   'eventos.index'],
            ['Calendario',       route('calendario'),      'calendario'],
            ['Bolsa de trabajo', route('trabajos.index'),  'trabajos.index'],
          ];
        @endphp
      @endauth
      @foreach($navLinks as [$label, $url, $ruta])
        @php $activo = $ruta !== '' && request()->routeIs($ruta); @endphp
        <a href="{{ $url }}" class="mono"
           style="font-size:11px;color:{{ $activo ? 'var(--ink)' : 'var(--ink-dim)' }};text-decoration:none;padding-bottom:4px;border-bottom:1.5px solid {{ $activo ? 'var(--magenta)' : 'transparent' }};transition:color 0.2s;">
          {{ $label }}
        </a>
      @endforeach
      {{-- Enlace Premium: solo para usuarios normales (no empresa, no admin, no portero) --}}
      @auth
      @if(!Auth::user()->isEmpresa() && !Auth::user()->isAdmin() && !Auth::user()->isPortero())
        @php $premiumActivo = request()->routeIs('premium*'); @endphp
        <a href="{{ route('premium') }}" class="mono"
           style="font-size:11px;text-decoration:none;padding-bottom:4px;transition:color 0.2s;
                  {{ $premiumActivo
                      ? 'color:var(--magenta);border-bottom:1.5px solid var(--magenta);'
                      : 'color:rgba(168,85,247,0.7);border-bottom:1.5px solid transparent;' }}">
          {{ Auth::user()->es_premium ? '✦ Premium' : 'Premium' }}
        </a>
      @endif
      @endauth
    </nav>

    {{-- Área derecha: usuario autenticado o botones de acceso --}}
    @auth
      @php
        $u = Auth::user();
        $notifSinLeer = \App\Models\Notificacion::where('usuario_id', $u->id)
            ->where('estado', 1)->where('leida', 0)->count();
        // Cupones vigentes: solo se cargan si el usuario es premium
        $cuponesNav = $u->es_premium
            ? \App\Models\Cupon::vigentes()->with('eventos')->orderBy('fecha_fin')->get()
            : collect();
      @endphp
      <div id="navAvatarWrapper" style="display:flex;align-items:center;gap:12px;position:relative;">

        {{-- Icono de cupones Premium: solo visible para usuarios con membresía activa --}}
        @if($u->es_premium)
        <div style="position:relative;">
          <button id="navCuponesBtn" onclick="toggleCuponesDropdown()"
                  title="Mis cupones Premium"
                  style="width:38px;height:38px;border-radius:50%;background:rgba(168,85,247,0.1);border:1px solid rgba(168,85,247,0.4);color:#c084fc;cursor:pointer;display:flex;align-items:center;justify-content:center;position:relative;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            @if($cuponesNav->isNotEmpty())
            <span style="position:absolute;top:-4px;right:-4px;min-width:18px;height:18px;border-radius:999px;background:linear-gradient(135deg,#7c3aed,#a855f7);font-size:10px;font-family:'Archivo Narrow',sans-serif;font-weight:700;color:#fff;display:flex;align-items:center;justify-content:center;padding:0 4px;border:2px solid #07060c;">
              {{ $cuponesNav->count() > 9 ? '9+' : $cuponesNav->count() }}
            </span>
            @endif
          </button>

          {{-- Dropdown de cupones --}}
          <div id="navCuponesDropdown" style="display:none;position:absolute;top:calc(100% + 10px);right:0;background:rgba(13,10,24,0.97);backdrop-filter:blur(20px);border:1px solid rgba(168,85,247,0.25);border-radius:14px;padding:8px;min-width:320px;max-width:360px;box-shadow:0 20px 50px rgba(0,0,0,0.6);z-index:200;">

            {{-- Cabecera --}}
            <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 12px 10px;border-bottom:1px solid rgba(245,241,234,0.08);">
              <div style="display:flex;align-items:center;gap:7px;">
                <span style="color:#a855f7;font-size:0.85rem;">✦</span>
                <span style="font-family:'Archivo Narrow',sans-serif;font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:rgba(245,241,234,0.5);">Mis Cupones Premium</span>
              </div>
              <a href="{{ route('cupones.index') }}" onclick="document.getElementById('navCuponesDropdown').style.display='none'"
                 style="color:#a855f7;font-size:11px;font-family:'Archivo Narrow',sans-serif;text-decoration:none;">Ver todos</a>
            </div>

            {{-- Lista de cupones --}}
            <div style="max-height:340px;overflow-y:auto;padding:4px 0;">
              @if($cuponesNav->isEmpty())
                <p style="padding:20px 12px;text-align:center;font-family:'Archivo Narrow',sans-serif;font-size:13px;color:rgba(245,241,234,0.35);">No hay cupones activos ahora mismo</p>
              @else
                @foreach($cuponesNav as $cup)
                <div style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:8px;transition:background 0.15s;"
                     onmouseover="this.style.background='rgba(124,58,237,0.1)'"
                     onmouseout="this.style.background='transparent'">
                  {{-- Porcentaje --}}
                  <div style="flex-shrink:0;width:44px;height:44px;border-radius:8px;background:linear-gradient(135deg,#7c3aed,#a855f7);display:flex;align-items:center;justify-content:center;">
                    @if($cup->valor_descuento == 0)
                      <span style="color:#fff;font-size:1rem;">✦</span>
                    @else
                      <span style="font-size:0.85rem;font-weight:900;color:#fff;font-family:'Anton',sans-serif;line-height:1;">
                        {{ number_format($cup->valor_descuento, 0) }}%
                      </span>
                    @endif
                  </div>
                  {{-- Info --}}
                  <div style="flex:1;min-width:0;">
                    <p style="font-weight:700;color:#f5f1ea;margin:0 0 1px;font-size:0.85rem;font-family:'Syne',sans-serif;letter-spacing:0.03em;">{{ $cup->codigo }}</p>
                    @if($cup->descripcion)
                      <p style="color:rgba(245,241,234,0.45);font-size:0.72rem;margin:0 0 2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $cup->descripcion }}</p>
                    @endif
                    <p style="color:rgba(245,241,234,0.28);font-size:0.68rem;margin:0;font-family:'Archivo Narrow',sans-serif;">
                      Hasta {{ $cup->fecha_fin->locale('es')->isoFormat('D MMM YYYY') }}
                    </p>
                  </div>
                  {{-- Botón copiar --}}
                  <button id="nav-btn-cup-{{ $cup->id }}"
                          onclick="copiarCuponNav('{{ $cup->codigo }}', {{ $cup->id }})"
                          style="flex-shrink:0;background:rgba(168,85,247,0.15);border:1.5px solid rgba(168,85,247,0.35);color:#e9d5ff;font-family:'Archivo Narrow',sans-serif;font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;padding:5px 10px;border-radius:6px;cursor:pointer;transition:background 0.18s,color 0.18s;white-space:nowrap;">
                    Copiar
                  </button>
                </div>
                @endforeach
              @endif
            </div>

          </div>
        </div>
        @endif

        {{-- Campana de notificaciones con badge y dropdown --}}
        <div style="position:relative;">
          <button id="navBellBtn" onclick="toggleNotifDropdown()"
                  style="width:38px;height:38px;border-radius:50%;background:rgba(245,241,234,0.04);border:1px solid var(--ink-faint);color:var(--ink);cursor:pointer;display:flex;align-items:center;justify-content:center;position:relative;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
              <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
            </svg>
            <span id="notifBadge"
                  style="position:absolute;top:-4px;right:-4px;min-width:18px;height:18px;border-radius:999px;background:var(--magenta);box-shadow:0 0 8px rgba(168,85,247,0.7);font-size:10px;font-family:'Archivo Narrow',sans-serif;font-weight:700;color:#fff;display:{{ $notifSinLeer > 0 ? 'flex' : 'none' }};align-items:center;justify-content:center;padding:0 4px;border:2px solid #07060c;">
              {{ $notifSinLeer > 9 ? '9+' : $notifSinLeer }}
            </span>
          </button>

          {{-- Dropdown de notificaciones --}}
          <div id="navNotifDropdown" style="display:none;position:absolute;top:calc(100% + 10px);right:0;background:rgba(13,10,24,0.97);backdrop-filter:blur(20px);border:1px solid var(--line);border-radius:14px;padding:8px;min-width:320px;max-width:360px;box-shadow:0 20px 50px rgba(0,0,0,0.6);z-index:200;">
            <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 12px 10px;border-bottom:1px solid rgba(245,241,234,0.08);">
              <span style="font-family:'Archivo Narrow',sans-serif;font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:rgba(245,241,234,0.5);">Notificaciones</span>
              <button onclick="leerTodasNotificaciones()" style="background:none;border:none;color:var(--magenta);font-size:11px;font-family:'Archivo Narrow',sans-serif;cursor:pointer;padding:0;">Marcar leídas</button>
            </div>
            <div id="notifLista" style="max-height:320px;overflow-y:auto;">
              <p style="padding:20px 12px;text-align:center;font-family:'Archivo Narrow',sans-serif;font-size:13px;color:rgba(245,241,234,0.35);">Cargando...</p>
            </div>
          </div>
        </div>

        {{-- Avatar + nombre (el nombre se oculta en móvil con CSS) --}}
        <button id="navAvatarBtn" onclick="toggleNavDropdown()"
                class="nav-avatar-btn"
                style="display:flex;align-items:center;gap:10px;background:rgba(168,85,247,0.08);border:1px solid rgba(168,85,247,0.4);border-radius:999px;padding:4px 14px 4px 4px;cursor:pointer;color:var(--ink);"
                aria-haspopup="true" aria-expanded="false">
          @if($u->foto_url)
            <img src="{{ $u->foto_url }}" alt="{{ $u->nombre }}" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
          @else
            <span style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--morado),var(--magenta));display:flex;align-items:center;justify-content:center;font-family:'Anton',sans-serif;font-size:13px;color:var(--cream);">
              {{ strtoupper(substr($u->nombre, 0, 1)) }}{{ strtoupper(substr($u->apellido1 ?? '', 0, 1)) }}
            </span>
          @endif
          <span class="mono nav-avatar-nombre" style="font-size:11px;">{{ $u->nombre }}</span>
        </button>

        {{-- Dropdown de perfil --}}
        <div id="navDropdown" style="display:none;position:absolute;top:calc(100% + 10px);right:0;background:rgba(13,10,24,0.95);backdrop-filter:blur(20px);border:1px solid var(--line);border-radius:14px;padding:8px;min-width:220px;box-shadow:0 20px 50px rgba(0,0,0,0.5);z-index:100;">
          @if($esPortero)
            {{-- Ítems normales de cliente --}}
            @foreach([
              ['Mi perfil',   route('perfil')],
              ['Mis tickets', route('entradas.mis-entradas')],
              ['Favoritos',   route('perfil.favoritos')],
            ] as [$item, $url])
              <a href="{{ $url }}" onclick="document.getElementById('navDropdown').style.display='none'"
                 style="display:block;padding:10px 14px;color:var(--ink);text-decoration:none;font-size:13px;border-radius:8px;font-family:'Archivo Narrow',sans-serif;text-transform:uppercase;letter-spacing:0.08em;">
                {{ $item }}
              </a>
            @endforeach

            {{-- Divisor + botones extra de portero --}}
            <div style="margin:6px 0;height:1px;background:rgba(245,241,234,0.08);"></div>
            @php
              $estiloPorteroExtra = 'display:flex;align-items:center;gap:10px;padding:9px 14px;text-decoration:none;font-size:12px;border-radius:8px;font-family:\'Archivo Narrow\',sans-serif;text-transform:uppercase;letter-spacing:0.10em;font-weight:700;color:var(--ink);transition:background 0.15s;';
            @endphp
            <a href="{{ route('empresa.validacion.index') }}"
               onclick="document.getElementById('navDropdown').style.display='none'"
               style="{{ $estiloPorteroExtra }}"
               onmouseover="this.style.background='rgba(168,85,247,0.12)'"
               onmouseout="this.style.background='transparent'">
              <span style="width:26px;height:26px;border-radius:7px;background:rgba(168,85,247,0.15);border:1px solid rgba(168,85,247,0.30);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="12" height="12" fill="none" stroke="#c084fc" stroke-width="2.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
              </span>
              Validar entradas
            </a>
            <a href="{{ route('horas.index') }}"
               onclick="document.getElementById('navDropdown').style.display='none'"
               style="{{ $estiloPorteroExtra }}"
               onmouseover="this.style.background='rgba(168,85,247,0.12)'"
               onmouseout="this.style.background='transparent'">
              <span style="width:26px;height:26px;border-radius:7px;background:rgba(168,85,247,0.15);border:1px solid rgba(168,85,247,0.30);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="12" height="12" fill="none" stroke="#c084fc" stroke-width="2.5" viewBox="0 0 24 24">
                  <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
              </span>
              Mis horas
            </a>
          @elseif($esEmpresa)
            @foreach([
              ['Mi empresa',      route('empresa.home')],
              ['Equipo',          route('empresa.equipo.index')],
              ['Candidaturas',    route('empresa.candidaturas.ofertas')],
              ['Administración',  route('empresa.facturacion.index')],
              ['Perfil Fiscal',   route('empresa.perfil-fiscal')],
              ['Crear evento',    route('empresa.eventos.create')],
              ['Crear oferta',    route('empresa.ofertas.create')],
            ] as [$item, $url])
              <a href="{{ $url }}" onclick="document.getElementById('navDropdown').style.display='none'"
                 style="display:block;padding:10px 14px;color:var(--ink);text-decoration:none;font-size:13px;border-radius:8px;font-family:'Archivo Narrow',sans-serif;text-transform:uppercase;letter-spacing:0.08em;">
                {{ $item }}
              </a>
            @endforeach
          @else
            @foreach([
              ['Mi perfil',   route('perfil')],
              ['Mis tickets', route('entradas.mis-entradas')],
              ['Favoritos',   route('perfil.favoritos')],
            ] as [$item, $url])
              <a href="{{ $url }}" onclick="document.getElementById('navDropdown').style.display='none'"
                 style="display:block;padding:10px 14px;color:var(--ink);text-decoration:none;font-size:13px;border-radius:8px;font-family:'Archivo Narrow',sans-serif;text-transform:uppercase;letter-spacing:0.08em;">
                {{ $item }}
              </a>
            @endforeach
            {{-- Mis horas: solo organizadores (no porteros, ya tienen su propio bloque) --}}
            @if($esOrganizador)
              <a href="{{ route('horas.index') }}" onclick="document.getElementById('navDropdown').style.display='none'"
                 style="display:block;padding:10px 14px;color:var(--ink);text-decoration:none;font-size:13px;border-radius:8px;font-family:'Archivo Narrow',sans-serif;text-transform:uppercase;letter-spacing:0.08em;">
                Mis horas
              </a>
            @endif
            {{-- Enlace Premium con color diferenciado --}}
            <a href="{{ route('premium') }}" onclick="document.getElementById('navDropdown').style.display='none'"
               style="display:block;padding:10px 14px;text-decoration:none;font-size:13px;border-radius:8px;font-family:'Archivo Narrow',sans-serif;text-transform:uppercase;letter-spacing:0.08em;
                      {{ $u->es_premium ? 'color:#a855f7;font-weight:700;' : 'color:rgba(168,85,247,0.8);' }}">
              {{ $u->es_premium ? '✦ Premium activo' : '★ Hazte Premium' }}
            </a>
            @if($u->es_admin)
              <a href="{{ route('admin.dashboard') }}" style="display:block;padding:10px 14px;color:var(--magenta-2);text-decoration:none;font-size:13px;border-radius:8px;font-family:'Archivo Narrow',sans-serif;text-transform:uppercase;letter-spacing:0.08em;font-weight:700;">
                Panel Admin
              </a>
            @endif
          @endif
          <button onclick="cerrarSesion()" style="display:block;width:100%;padding:10px 14px;color:var(--magenta);background:none;border:none;text-align:left;font-size:13px;border-radius:8px;font-family:'Archivo Narrow',sans-serif;text-transform:uppercase;letter-spacing:0.08em;cursor:pointer;">
            — Cerrar sesión
          </button>
        </div>

      </div>
    @else
      <div style="display:flex;align-items:center;gap:12px;">
        {{-- "Entrar" se oculta en móvil para ahorrar espacio (aparece en el menú móvil) --}}
        <a href="{{ route('login') }}" class="mono nav-guest-entrar"
           style="background:transparent;border:1px solid var(--ink-faint);color:var(--ink);padding:9px 18px;border-radius:999px;font-size:11px;text-decoration:none;">
          Entrar
        </a>
        <a href="{{ route('register') }}" class="btn-primary"
           style="padding:10px 20px;border-radius:999px;font-size:13px;text-decoration:none;">
          Registro
        </a>
      </div>
    @endauth

    {{-- Botón hamburguesa — solo visible en móvil (≤768px) --}}
    <button id="vibez-nav-hamburger" onclick="toggleNavMobile()" aria-label="Abrir menú" aria-expanded="false">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
        <line x1="3" y1="6" x2="21" y2="6"/>
        <line x1="3" y1="12" x2="21" y2="12"/>
        <line x1="3" y1="18" x2="21" y2="18"/>
      </svg>
    </button>

  </div>

  {{-- ─── Panel de menú móvil ─────────────────────────────────
       Oculto por defecto; se muestra al pulsar el hamburguesa.
       Vive dentro del <header> para que sea sticky junto al nav.
  ──────────────────────────────────────────────────────────── --}}
  <div id="vibez-mobile-menu">

    {{-- Links de navegación principal --}}
    @foreach($navLinks as [$label, $url, $ruta])
      @php $activo = $ruta !== '' && request()->routeIs($ruta); @endphp
      <a href="{{ $url }}" class="mob-nav-link {{ $activo ? 'activo' : '' }}">{{ $label }}</a>
    @endforeach

    @auth
      {{-- Links de usuario (solo para usuarios normales; empresa/portero ya los tienen en $navLinks) --}}
      @if(!$esEmpresa && !$esPortero)
        <div class="mob-nav-divider"></div>
        <a href="{{ route('perfil') }}" class="mob-nav-link">Mi perfil</a>
        <a href="{{ route('perfil.favoritos') }}" class="mob-nav-link">Favoritos</a>
        {{-- Enlace Premium: solo para usuarios normales (no empresa, no admin, no portero) --}}
        <a href="{{ route('premium') }}" class="mob-nav-link"
           style="color:{{ $u->es_premium ? '#a855f7' : 'rgba(168,85,247,0.8)' }};{{ $u->es_premium ? 'font-weight:700;' : '' }}">
          {{ $u->es_premium ? '✦ Premium activo' : '★ Hazte Premium' }}
        </a>
        @if($u->es_admin)
          <a href="{{ route('admin.dashboard') }}" class="mob-nav-link" style="color:var(--magenta-2);">Panel Admin</a>
        @endif
      @endif
      <div class="mob-nav-divider"></div>
      <button onclick="cerrarSesion()" class="mob-nav-link peligro">— Cerrar sesión</button>
    @else
      <div class="mob-nav-divider"></div>
      <a href="{{ route('login') }}" class="mob-nav-link">Entrar</a>
      <a href="{{ route('register') }}" class="mob-nav-link" style="color:var(--magenta);">Registro</a>
    @endauth

  </div>

</header>

<script src="{{ asset('js/nav-hamburger.js') }}"></script>
{{-- JS en public/js/nav-hamburger.js --}}

@auth
<script src="{{ asset('js/nav-premium.js') }}"></script>
{{-- JS en public/js/nav-premium.js --}}
@endauth

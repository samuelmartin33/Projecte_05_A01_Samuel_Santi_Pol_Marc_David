<style>
  :root {
    --bg: #07060c; --bg-2: #0d0820; --ink: #f5f1ea;
    --ink-dim: rgba(245,241,234,0.55); --ink-faint: rgba(245,241,234,0.18);
    --morado: #7c3aed; --magenta: #a855f7; --magenta-2: #c084fc;
    --cream: #f5f1ea; --line: rgba(245,241,234,0.12);
  }
  #vibez-home-nav .mono {
    font-family: 'Archivo Narrow', sans-serif;
    text-transform: uppercase;
    letter-spacing: 0.18em;
    font-weight: 500;
  }
  #vibez-home-nav .btn-primary {
    background: var(--magenta);
    color: var(--cream);
    border: none;
    cursor: pointer;
    font-family: 'Anton', sans-serif;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    transition: all 0.2s ease;
    text-decoration: none;
  }
  #vibez-home-nav .btn-primary:hover { background: var(--cream); color: var(--bg); }
</style>
<header id="vibez-home-nav" style="position:sticky;top:0;z-index:50;background:rgba(7,6,12,0.92);backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px);border-bottom:1px solid var(--line);transition:all 0.3s ease;">
  <div style="max-width:1480px;margin:0 auto;padding:18px 32px;display:flex;align-items:center;justify-content:space-between;gap:24px;">

    {{-- Logo pill con glow morado --}}
    <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:12px;text-decoration:none;color:var(--ink);">
      <div style="position:relative;padding:6px;display:flex;align-items:center;background:linear-gradient(135deg,rgba(168,85,247,0.18),rgba(124,58,237,0.08));border:1px solid rgba(168,85,247,0.45);border-radius:999px;box-shadow:0 0 24px rgba(168,85,247,0.35),inset 0 0 12px rgba(168,85,247,0.12);">
        <img src="{{ asset('images/logo_vibez_white.png') }}" alt="VIBEZ" style="height:58px;width:auto;object-fit:contain;filter:drop-shadow(0 0 12px rgba(168,85,247,0.7));">
      </div>
    </a>

    {{-- Navegación central (desktop) --}}
    <nav style="display:flex;gap:28px;">
      @auth
        @php
          $esAdmin   = Auth::user()->es_admin;
          $esEmpresa = Auth::user()->isEmpresa();
          $esPortero = Auth::user()->isPortero();
          if ($esPortero) {
            $navLinks = [
              ['Validación QR', route('empresa.validacion.index'), 'empresa.validacion.*'],
            ];
          } elseif ($esEmpresa) {
            $navLinks = [
              ['Panel',          route('empresa.home'),                 'empresa.home'],
              ['Equipo',         route('empresa.equipo.index'),         'empresa.equipo.*'],
              ['Candidaturas',   route('empresa.candidaturas.ofertas'), 'empresa.candidaturas.*'],
              ['Administración', route('empresa.facturacion.index'),    'empresa.facturacion.*'],
              ['Crear evento',   route('empresa.eventos.create'),       'empresa.eventos.create'],
              ['Crear oferta',   route('empresa.ofertas.create'),       'empresa.ofertas.create'],
            ];
          } else {
            $navLinks = array_filter([
              ['Para ti',     route('home'),                        'home'],
              !$esAdmin ? ['Mis tickets', route('entradas.mis-entradas'), 'entradas.mis-entradas'] : null,
              ['Bolsa',       route('trabajos.index'),              'trabajos.index'],
              ['Social',      route('social'),                      'social'],
            ]);
          }
        @endphp
      @else
        @php
          $navLinks = [
            ['Explorar',         route('home'),           'home'],
            ['Bolsa de trabajo', route('trabajos.index'), 'trabajos.index'],
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
    </nav>

    {{-- Área derecha: usuario autenticado o botones de acceso --}}
    @auth
      @php $u = Auth::user(); @endphp
      <div id="navAvatarWrapper" style="display:flex;align-items:center;gap:12px;position:relative;">

        {{-- Campana de notificaciones --}}
        <button style="width:38px;height:38px;border-radius:50%;background:rgba(245,241,234,0.04);border:1px solid var(--ink-faint);color:var(--ink);cursor:pointer;display:flex;align-items:center;justify-content:center;position:relative;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
          </svg>
          <span style="position:absolute;top:6px;right:6px;width:8px;height:8px;border-radius:50%;background:var(--magenta);box-shadow:0 0 8px rgba(168,85,247,0.7);"></span>
        </button>

        {{-- Avatar + nombre --}}
        <button id="navAvatarBtn" onclick="toggleNavDropdown()"
                style="display:flex;align-items:center;gap:10px;background:rgba(168,85,247,0.08);border:1px solid rgba(168,85,247,0.4);border-radius:999px;padding:4px 14px 4px 4px;cursor:pointer;color:var(--ink);"
                aria-haspopup="true" aria-expanded="false">
          @if($u->foto_url)
            <img src="{{ $u->foto_url }}" alt="{{ $u->nombre }}" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
          @else
            <span style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--morado),var(--magenta));display:flex;align-items:center;justify-content:center;font-family:'Anton',sans-serif;font-size:13px;color:var(--cream);">
              {{ strtoupper(substr($u->nombre, 0, 1)) }}{{ strtoupper(substr($u->apellido1 ?? '', 0, 1)) }}
            </span>
          @endif
          <span class="mono" style="font-size:11px;">{{ $u->nombre }}</span>
        </button>

        {{-- Dropdown --}}
        <div id="navDropdown" style="display:none;position:absolute;top:calc(100% + 10px);right:0;background:rgba(13,10,24,0.95);backdrop-filter:blur(20px);border:1px solid var(--line);border-radius:14px;padding:8px;min-width:220px;box-shadow:0 20px 50px rgba(0,0,0,0.5);z-index:100;">
          @if($esPortero)
            <a href="{{ route('empresa.validacion.index') }}" onclick="document.getElementById('navDropdown').style.display='none'"
               style="display:block;padding:10px 14px;color:var(--ink);text-decoration:none;font-size:13px;border-radius:8px;font-family:'Archivo Narrow',sans-serif;text-transform:uppercase;letter-spacing:0.08em;">
              Validación QR
            </a>
          @elseif($esEmpresa)
            @foreach([
              ['Mi empresa',      route('empresa.home')],
              ['Equipo',          route('empresa.equipo.index')],
              ['Candidaturas',    route('empresa.candidaturas.ofertas')],
              ['Administración',  route('empresa.facturacion.index')],
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
              ['Favoritos',   route('perfil')],
            ] as [$item, $url])
              <a href="{{ $url }}" onclick="document.getElementById('navDropdown').style.display='none'"
                 style="display:block;padding:10px 14px;color:var(--ink);text-decoration:none;font-size:13px;border-radius:8px;font-family:'Archivo Narrow',sans-serif;text-transform:uppercase;letter-spacing:0.08em;">
                {{ $item }}
              </a>
            @endforeach
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
        <a href="{{ route('login') }}" class="mono"
           style="background:transparent;border:1px solid var(--ink-faint);color:var(--ink);padding:9px 18px;border-radius:999px;font-size:11px;text-decoration:none;">
          Entrar
        </a>
        <a href="{{ route('register') }}" class="btn-primary"
           style="padding:10px 20px;border-radius:999px;font-size:13px;text-decoration:none;">
          Registro
        </a>
      </div>
    @endauth

  </div>
</header>

<header id="vibez-home-nav" style="position:sticky;top:0;z-index:50;background:transparent;border-bottom:1px solid transparent;transition:all 0.3s ease;">
  <div style="max-width:1480px;margin:0 auto;padding:18px 32px;display:flex;align-items:center;justify-content:space-between;gap:24px;">

    {{-- Logo pill con glow morado --}}
    <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:12px;text-decoration:none;color:var(--ink);">
      <div style="position:relative;padding:6px 14px 6px 6px;display:flex;align-items:center;gap:10px;background:linear-gradient(135deg,rgba(168,85,247,0.18),rgba(124,58,237,0.08));border:1px solid rgba(168,85,247,0.45);border-radius:999px;box-shadow:0 0 24px rgba(168,85,247,0.35),inset 0 0 12px rgba(168,85,247,0.12);">
        <img src="{{ asset('images/logo_vibez.png') }}" alt="VIBEZ" style="height:44px;width:44px;object-fit:contain;filter:drop-shadow(0 0 12px rgba(168,85,247,0.7));">
        <span class="display" style="font-size:24px;letter-spacing:0.04em;color:var(--ink);text-shadow:0 0 18px rgba(168,85,247,0.6);">VIBEZ</span>
      </div>
    </a>

    {{-- Navegación central (desktop) --}}
    <nav style="display:flex;gap:28px;">
      @auth
        @php
          $navLinks = [
            ['Para ti',     route('home')],
            ['Mis tickets', route('entradas.mis-entradas')],
            ['Esta noche',  route('home')],
            ['Bolsa',       route('trabajos.index')],
            ['Social',      route('social')],
          ];
        @endphp
      @else
        @php
          $navLinks = [
            ['Explorar',       route('home')],
            ['Esta noche',     route('home')],
            ['Bolsa de trabajo', route('trabajos.index')],
          ];
        @endphp
      @endauth
      @foreach($navLinks as $i => [$label, $url])
        <a href="{{ $url }}" class="mono"
           style="font-size:11px;color:{{ $i === 0 ? 'var(--ink)' : 'var(--ink-dim)' }};text-decoration:none;padding-bottom:4px;border-bottom:1.5px solid {{ $i === 0 ? 'var(--magenta)' : 'transparent' }};transition:color 0.2s;">
          {{ $label }}
        </a>
      @endforeach
    </nav>

    {{-- Área derecha: usuario autenticado o botones de acceso --}}
    @auth
      @php $u = Auth::user(); @endphp
      <div style="display:flex;align-items:center;gap:12px;position:relative;">

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
          @foreach([
            ['Mi perfil', route('perfil')],
            ['Mis tickets', route('entradas.mis-entradas')],
            ['Favoritos', route('perfil')],
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

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
              ['Perfil Fiscal',  route('empresa.perfil-fiscal'),        'empresa.perfil-fiscal'],
              ['Crear evento',   route('empresa.eventos.create'),       'empresa.eventos.create'],
              ['Crear oferta',   route('empresa.ofertas.create'),       'empresa.ofertas.create'],
            ];
          } else {
            $navLinks = array_filter([
              ['Para ti',     route('home'),                        'home'],
              ['Eventos',     route('eventos.index'),               'eventos.index'],
              !$esAdmin ? ['Mis tickets', route('entradas.mis-entradas'), 'entradas.mis-entradas'] : null,
              ['Bolsa',       route('trabajos.index'),              'trabajos.index'],
              ['Social',      route('social'),                      'social'],
            ]);
          }
        @endphp
      @else
        @php
          $navLinks = [
            ['Explorar',         route('home'),            'home'],
            ['Eventos',          route('eventos.index'),   'eventos.index'],
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
    </nav>

    {{-- Área derecha: usuario autenticado o botones de acceso --}}
    @auth
      @php
        $u = Auth::user();
        $notifSinLeer = \App\Models\Notificacion::where('usuario_id', $u->id)
            ->where('estado', 1)->where('leida', 0)->count();
      @endphp
      <div id="navAvatarWrapper" style="display:flex;align-items:center;gap:12px;position:relative;">

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

@auth
<script>
/* ─── Notificaciones: campanita del nav ─────────────────────── */

var _notifAbierto = false;
var _csrf = document.querySelector('meta[name="csrf-token"]') ?
    document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

/* Abre/cierra el dropdown y carga las notificaciones si se abre */
function toggleNotifDropdown() {
    var drop = document.getElementById('navNotifDropdown');
    var avatarDrop = document.getElementById('navDropdown');

    // Cerrar el dropdown de avatar si estaba abierto
    if (avatarDrop) avatarDrop.style.display = 'none';

    _notifAbierto = !_notifAbierto;
    drop.style.display = _notifAbierto ? 'block' : 'none';

    if (_notifAbierto) cargarNotificaciones();
}

/* Cierra el dropdown al hacer clic fuera */
document.addEventListener('click', function(e) {
    var bell = document.getElementById('navBellBtn');
    var drop = document.getElementById('navNotifDropdown');
    if (!bell || !drop) return;
    if (!bell.contains(e.target) && !drop.contains(e.target)) {
        drop.style.display = 'none';
        _notifAbierto = false;
    }
});

/* Carga las notificaciones via AJAX y renderiza la lista */
function cargarNotificaciones() {
    fetch('/api/notificaciones', {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': _csrf }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        var lista = document.getElementById('notifLista');
        if (!lista) return;

        if (!data.notificaciones || data.notificaciones.length === 0) {
            lista.innerHTML = '<p style="padding:20px 12px;text-align:center;font-family:\'Archivo Narrow\',sans-serif;font-size:13px;color:rgba(245,241,234,0.35);">Sin notificaciones</p>';
            return;
        }

        var html = '';
        for (var i = 0; i < data.notificaciones.length; i++) {
            var n = data.notificaciones[i];
            var bg = n.leida ? 'transparent' : 'rgba(168,85,247,0.07)';
            var dot = n.leida ? '' : '<span style="width:7px;height:7px;border-radius:50%;background:var(--magenta);flex-shrink:0;margin-top:3px;"></span>';
            var tag = n.url ? 'a' : 'div';
            var href = n.url ? ' href="' + n.url + '"' : '';
            html += '<' + tag + href + ' onclick="leerNotificacion(' + n.id + ', this)"' +
                ' style="display:flex;gap:10px;align-items:flex-start;padding:10px 12px;border-radius:8px;background:' + bg + ';color:var(--ink);text-decoration:none;cursor:pointer;transition:background 0.15s;" ' +
                'onmouseover="this.style.background=\'rgba(245,241,234,0.05)\'" onmouseout="this.style.background=\'' + bg + '\'">' +
                '<span style="font-size:18px;flex-shrink:0;">' + n.icono + '</span>' +
                '<div style="flex:1;min-width:0;">' +
                '<p style="font-family:\'Archivo Narrow\',sans-serif;font-size:13px;font-weight:700;color:var(--ink);margin:0 0 2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + n.titulo + '</p>' +
                (n.mensaje ? '<p style="font-size:11px;color:rgba(245,241,234,0.5);margin:0 0 4px;line-height:1.3;">' + n.mensaje + '</p>' : '') +
                '<p style="font-size:10px;color:rgba(245,241,234,0.3);margin:0;font-family:\'Archivo Narrow\',sans-serif;">' + n.fecha + '</p>' +
                '</div>' + dot +
                '</' + tag + '>';
        }
        lista.innerHTML = html;

        /* Actualizar badge */
        actualizarBadge(data.sin_leer);

        /* Push Notification API: mostrar notificación nativa para no leídas */
        if (data.sin_leer > 0) solicitarPushPermiso(data.notificaciones, data.sin_leer);
    })
    .catch(function() {
        var lista = document.getElementById('notifLista');
        if (lista) lista.innerHTML = '<p style="padding:16px 12px;text-align:center;font-size:12px;color:rgba(245,241,234,0.3);">Error al cargar</p>';
    });
}

/* Marca una notificación como leída */
function leerNotificacion(id, el) {
    fetch('/api/notificaciones/' + id + '/leer', {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': _csrf }
    });
    // Eliminar el punto visual de no leída
    if (el) el.style.background = 'transparent';
    var dot = el ? el.querySelector('span[style*="border-radius:50%"]') : null;
    if (dot && dot.style.background.includes('magenta')) dot.style.display = 'none';
}

/* Marca todas como leídas */
function leerTodasNotificaciones() {
    fetch('/api/notificaciones/leer-todas', {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': _csrf }
    })
    .then(function() {
        actualizarBadge(0);
        cargarNotificaciones();
    });
}

/* Actualiza el badge numérico de la campanita */
function actualizarBadge(count) {
    var badge = document.getElementById('notifBadge');
    if (!badge) return;
    if (count <= 0) {
        badge.style.display = 'none';
    } else {
        badge.style.display = 'flex';
        badge.textContent = count > 9 ? '9+' : count;
    }
}

/* ─── Browser Notification API (Push nativo del SO) ────────── */

/* Solicita permiso y muestra notificación nativa si hay no leídas */
function solicitarPushPermiso(notificaciones, sinLeer) {
    if (!('Notification' in window)) return;
    if (Notification.permission === 'denied') return;

    var mostrar = function() {
        // Solo mostrar la más reciente no leída como notificación nativa
        for (var i = 0; i < notificaciones.length; i++) {
            if (!notificaciones[i].leida) {
                new Notification('VIBEZ — ' + notificaciones[i].titulo, {
                    body: notificaciones[i].mensaje || '',
                    icon: '/images/logo_vibez_white.png',
                    badge: '/images/logo_vibez_white.png',
                    tag: 'vibez-notif-' + notificaciones[i].id,
                });
                break;
            }
        }
    };

    if (Notification.permission === 'granted') {
        mostrar();
    } else if (Notification.permission === 'default') {
        Notification.requestPermission().then(function(permiso) {
            if (permiso === 'granted') mostrar();
        });
    }
}

/* Al cargar la página, pedir permiso si hay notificaciones sin leer */
(function initNotifEnCarga() {
    var badgeEl = document.getElementById('notifBadge');
    if (!badgeEl || badgeEl.style.display === 'none') return;
    // Hay notificaciones — cargar para el push sin abrir el dropdown
    if ('Notification' in window && Notification.permission !== 'denied') {
        fetch('/api/notificaciones', {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': _csrf }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.sin_leer > 0) solicitarPushPermiso(data.notificaciones, data.sin_leer);
        });
    }
})();
</script>
@endauth

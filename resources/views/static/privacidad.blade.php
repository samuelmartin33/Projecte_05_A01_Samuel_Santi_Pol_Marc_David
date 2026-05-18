@extends('layouts.app')
@section('titulo', 'Política de privacidad — VIBEZ')

@section('content')

<link rel="stylesheet" href="{{ asset('css/vibez-welcome.css') }}">
<style>
  .static-hero {
    padding: 100px 40px 72px;
    max-width: 1320px;
    margin: 0 auto;
    border-bottom: 1px solid var(--line);
    position: relative; z-index: 1;
  }
  .static-body {
    max-width: 1320px;
    margin: 0 auto;
    padding: 0 40px 120px;
    position: relative; z-index: 1;
  }
  .static-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
  }
  .static-item {
    padding: 40px 0;
    border-bottom: 1px solid var(--line);
  }
  .static-item:nth-child(odd)  { padding-right: 60px; border-right: 1px solid var(--line); }
  .static-item:nth-child(even) { padding-left: 60px; }
  .static-label {
    font-family: 'JetBrains Mono', monospace;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.16em;
    color: var(--morado-2);
    margin-bottom: 14px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .static-label::before {
    content: '';
    width: 20px;
    height: 1px;
    background: var(--morado-2);
    display: inline-block;
    flex-shrink: 0;
  }
  .static-text {
    font-family: 'Archivo', sans-serif;
    font-size: 15px;
    color: var(--ink-dim);
    line-height: 1.75;
    margin: 0;
  }
  .static-back {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    color: var(--morado-2);
    font-family: 'JetBrains Mono', monospace;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.14em;
    text-decoration: none;
    margin-top: 56px;
    border-bottom: 1px solid rgba(168,85,247,0.4);
    padding-bottom: 3px;
    transition: color 0.2s, border-color 0.2s;
  }
  .static-back:hover { color: var(--ink); border-color: var(--ink); }
  @media (max-width: 820px) {
    .static-hero { padding: 72px 20px 48px; }
    .static-body { padding: 0 20px 80px; }
    .static-grid { grid-template-columns: 1fr; }
    .static-item:nth-child(odd)  { padding-right: 0; border-right: none; }
    .static-item:nth-child(even) { padding-left: 0; }
  }
</style>

{{-- Orbs de ambiente --}}
<div class="amb amb-1" aria-hidden="true"></div>
<div class="amb amb-2" aria-hidden="true"></div>
<div class="amb amb-3" aria-hidden="true"></div>

{{-- Nav welcome --}}
<header class="nav">
    <a href="{{ route('welcome') }}" class="logo" aria-label="VIBEZ — Inicio">
        <img src="{{ asset('images/logo_vibez_white.png') }}" alt="VIBEZ">
        <span>VIBEZ</span>
    </a>

    <nav class="nav-links" aria-label="Navegación principal">
        <a href="{{ route('welcome') }}">Inicio</a>
        <a href="{{ route('trabajos.index') }}">Bolsa de trabajo</a>
    </nav>

    <div class="nav-cta">
        @auth
            <a href="{{ Auth::user()->isEmpresa() ? route('empresa.home') : route('home') }}" class="btn-pri">Mi cuenta</a>
        @else
            <a href="{{ route('login') }}" class="btn-ghost">Entrar</a>
            <a href="{{ route('register') }}" class="btn-pri">Crear cuenta</a>
        @endauth
        <button class="burger"
                onclick="document.getElementById('menu-movil').classList.add('open')"
                aria-label="Abrir menú">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="6" x2="21" y2="6"/>
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>
    </div>
</header>

{{-- Menú móvil --}}
<div class="mobile-menu" id="menu-movil" role="dialog" aria-modal="true">
    <button class="close"
            onclick="document.getElementById('menu-movil').classList.remove('open')"
            aria-label="Cerrar menú">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"/>
            <line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
    </button>
    <a href="{{ route('welcome') }}" onclick="document.getElementById('menu-movil').classList.remove('open')">Inicio</a>
    <a href="{{ route('trabajos.index') }}" onclick="document.getElementById('menu-movil').classList.remove('open')">Bolsa de trabajo</a>
    <div class="actions">
        @auth
            <a href="{{ Auth::user()->isEmpresa() ? route('empresa.home') : route('home') }}" class="btn-pri">Mi cuenta</a>
        @else
            <a href="{{ route('login') }}" class="btn-ghost">Entrar</a>
            <a href="{{ route('register') }}" class="btn-pri">Crear cuenta</a>
        @endauth
    </div>
</div>

{{-- Hero --}}
<div class="static-hero">
    <div class="mono" style="font-size:11px;color:var(--morado-3);margin-bottom:20px;display:flex;align-items:center;gap:12px;">
        <span style="width:36px;height:1px;background:var(--morado-2);display:inline-block;"></span>
        Legal · VIBEZ
    </div>

    <div style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:32px;">
        <h1 class="display" style="font-size:clamp(72px,11vw,160px);margin:0;line-height:0.85;color:var(--ink);text-shadow:0 0 60px rgba(168,85,247,0.35);">
            PRIVACI<em style="color:var(--morado-2);font-family:'Bebas Neue',sans-serif;">DAD</em>.
        </h1>
        <div style="max-width:360px;">
            <p style="font-family:'Archivo',sans-serif;font-size:15px;color:var(--ink-dim);line-height:1.7;margin:0;">
                Nos tomamos tu privacidad en serio. Aquí encontrarás toda la información sobre cómo VIBEZ trata tus datos personales de forma transparente y conforme al RGPD.
            </p>
            <p class="mono" style="font-size:10px;color:rgba(245,241,234,0.28);margin-top:16px;">
                Última actualización: {{ date('d / m / Y') }}
            </p>
        </div>
    </div>
</div>

{{-- Contenido --}}
<div class="static-body">
    <div class="static-grid">
        @foreach([
            ['Responsable del tratamiento',
             'VIBEZ S.L., con domicilio en Barcelona (España). Para cualquier cuestión relacionada con la privacidad de tus datos, puedes contactarnos en privacidad@vibez.es.'],
            ['Datos que recogemos',
             'Nombre, dirección de correo electrónico, contraseña cifrada y, opcionalmente, foto de perfil. Para compras: datos de pago procesados por pasarelas externas certificadas. Nunca almacenamos números de tarjeta.'],
            ['Finalidad del tratamiento',
             'Gestionar tu cuenta, procesar compras de entradas, enviarte confirmaciones y, con tu consentimiento expreso, comunicaciones sobre eventos de tu interés.'],
            ['Base legal',
             'Ejecución del contrato (art. 6.1.b RGPD) para la gestión de la cuenta y las compras. Interés legítimo para la seguridad interna y la prevención del fraude.'],
            ['Conservación de datos',
             'Mientras tu cuenta esté activa o sea necesario para cumplir obligaciones legales. Hasta 5 años tras la última compra para documentos con efectos fiscales.'],
            ['Tus derechos',
             'Tienes derecho de acceso, rectificación, supresión, oposición, portabilidad y limitación. Ejércelos escribiendo a privacidad@vibez.es adjuntando copia de tu DNI.'],
            ['Cookies',
             'Usamos cookies propias de sesión y analítica de rendimiento. Consulta nuestra Política de Cookies para conocer todas las categorías y cómo gestionarlas.'],
            ['Seguridad',
             'Las contraseñas se almacenan con hash bcrypt. Las comunicaciones van cifradas con TLS. Realizamos auditorías periódicas de seguridad.'],
        ] as [$titulo, $texto])
        <div class="static-item">
            <div class="static-label">{{ $titulo }}</div>
            <p class="static-text">{{ $texto }}</p>
        </div>
        @endforeach
    </div>

    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('welcome') }}" class="static-back">
        ← Volver
    </a>
</div>

{{-- Footer welcome --}}
<footer>
    <div class="foot-row">
        <div>
            <a href="{{ route('welcome') }}" class="logo" style="display:inline-flex;margin-bottom:14px;">
                <img src="{{ asset('images/logo_vibez_white.png') }}" alt="VIBEZ">
                <span>VIBEZ</span>
            </a>
            <p style="color:var(--ink-dim);font-size:13px;line-height:1.5;max-width:280px;">
                La plataforma de la escena. Eventos, conciertos y festivales en BCN, Madrid, Valencia e Ibiza.
            </p>
        </div>
        <div>
            <h4>Plataforma</h4>
            <a href="{{ route('home') }}">Explorar</a>
            <a href="{{ route('trabajos.index') }}">Bolsa de trabajo</a>
            <a href="{{ route('register') }}">Crear cuenta</a>
            <a href="{{ route('login') }}">Entrar</a>
        </div>
        <div>
            <h4>Empresa</h4>
            <a href="#">Sobre VIBEZ</a>
            <a href="#">Prensa</a>
            <a href="#">Contacto</a>
        </div>
        <div>
            <h4>Legal</h4>
            <a href="{{ route('terminos') }}">Términos</a>
            <a href="{{ route('privacidad') }}">Privacidad</a>
            <a href="{{ route('cookies') }}">Cookies</a>
            <a href="{{ route('devoluciones') }}">Devoluciones</a>
        </div>
    </div>
    <div class="foot-bottom">
        <span>© {{ date('Y') }} VIBEZ · BCN</span>
        <span>Edición 428 · Made for ravers</span>
    </div>
</footer>

@endsection

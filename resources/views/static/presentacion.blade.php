@extends('layouts.app')

@section('titulo', 'Presentación del Proyecto — VIBEZ')

@push('estilos')
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="{{ asset('css/vibez-home.css') }}">

<link rel="stylesheet" href="{{ asset('css/static-presentacion.css') }}">
{{-- CSS extraido --}}
@endpush

@section('content')

{{-- Nav idéntico al original pero sin links de navegación --}}


<header id="vibez-home-nav" style="position:sticky;top:0;z-index:50;background:rgba(7,6,12,0.92);backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px);border-bottom:1px solid var(--line);transition:all 0.3s ease;">
  <div class="nav-inner-pad" style="max-width:1480px;margin:0 auto;padding:18px 32px;display:flex;align-items:center;justify-content:space-between;gap:16px;">

    {{-- Logo pill igual que el nav original --}}
    <a href="{{ route('welcome') }}" style="display:flex;align-items:center;gap:12px;text-decoration:none;color:var(--ink);flex-shrink:0;">
      <div style="position:relative;padding:6px;display:flex;align-items:center;background:linear-gradient(135deg,rgba(168,85,247,0.18),rgba(124,58,237,0.08));border:1px solid rgba(168,85,247,0.45);border-radius:999px;box-shadow:0 0 24px rgba(168,85,247,0.35),inset 0 0 12px rgba(168,85,247,0.12);">
        <img src="{{ asset('images/logo_vibez_white.png') }}" alt="VIBEZ" class="vibez-logo-img" style="height:58px;width:auto;object-fit:contain;filter:drop-shadow(0 0 12px rgba(168,85,247,0.7));">
      </div>
    </a>

    {{-- Solo botón de acceso, sin links de navegación --}}
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

  </div>
</header>

<div id="pres-tooltip" class="pres-tooltip">Copiado al portapapeles</div>

{{-- ─── Hero ─── --}}
<div class="pres-hero">
    <div class="pres-badge">DAW · Ciclo Formativo · Curso 2025–2026</div>
    <h1 class="pres-titulo">
        Bienvenido a<br><span>VIBEZ</span>
    </h1>
    <p class="pres-subtitulo">
        Plataforma de gestión de eventos para jóvenes. Accede con tus credenciales, compra tu entrada y valida el QR en la puerta de la presentación.
    </p>
</div>

{{-- ─── Contenido principal ─── --}}
<div class="pres-seccion">

    {{-- ── 1. Credenciales ── --}}
    <p class="pres-seccion-titulo">01 · Tus credenciales de acceso</p>
    <p style="font-family:'Archivo Narrow',sans-serif;font-size:14px;color:rgba(245,241,234,0.45);margin-bottom:24px;margin-top:-12px;">
        Haz clic sobre cualquier campo para copiarlo automáticamente.
    </p>

    <div class="pres-creds-grid">
        {{-- Usuario 1 --}}
        <div class="pres-cred-card">
            <div class="pres-cred-num">USUARIO 01</div>
            <div class="pres-cred-nombre">Alberto<br>de Santos</div>
            <div class="pres-cred-campo">
                <div class="pres-cred-label">Correo</div>
                <div class="pres-cred-valor" onclick="copiarText('alberto.desantos@fje.edu')">alberto.desantos@fje.edu</div>
            </div>
            <div class="pres-cred-campo">
                <div class="pres-cred-label">Contraseña</div>
                <div class="pres-cred-valor" onclick="copiarText('qwe123QWE')">qwe123QWE</div>
            </div>
        </div>

        {{-- Usuario 2 --}}
        <div class="pres-cred-card">
            <div class="pres-cred-num">USUARIO 02</div>
            <div class="pres-cred-nombre">Agnes<br>Plans</div>
            <div class="pres-cred-campo">
                <div class="pres-cred-label">Correo</div>
                <div class="pres-cred-valor" onclick="copiarText('agnes.plans@fje.edu')">agnes.plans@fje.edu</div>
            </div>
            <div class="pres-cred-campo">
                <div class="pres-cred-label">Contraseña</div>
                <div class="pres-cred-valor" onclick="copiarText('qwe123QWE')">qwe123QWE</div>
            </div>
        </div>

        {{-- Usuario 3 --}}
        <div class="pres-cred-card">
            <div class="pres-cred-num">USUARIO 03</div>
            <div class="pres-cred-nombre">Fatima<br>Martinez</div>
            <div class="pres-cred-campo">
                <div class="pres-cred-label">Correo</div>
                <div class="pres-cred-valor" onclick="copiarText('fatima.martinez@fje.edu')">fatima.martinez@fje.edu</div>
            </div>
            <div class="pres-cred-campo">
                <div class="pres-cred-label">Contraseña</div>
                <div class="pres-cred-valor" onclick="copiarText('qwe123QWE')">qwe123QWE</div>
            </div>
        </div>
    </div>

    <div class="pres-divider"></div>

    {{-- ── 2. Pasos ── --}}
    <p class="pres-seccion-titulo">02 · Cómo participar</p>

    <div class="pres-pasos">
        <div class="pres-paso">
            <div class="pres-paso-num">1</div>
            <div>
                <div class="pres-paso-titulo">Inicia sesión en VIBEZ</div>
                <div class="pres-paso-desc">
                    Usa las credenciales que tienes arriba. Accede a
                    <strong style="color:rgba(245,241,234,0.75);">https://g3.daw2j23.es/login</strong>
                </div>
            </div>
        </div>
        <div class="pres-paso">
            <div class="pres-paso-num">2</div>
            <div>
                <div class="pres-paso-titulo">Encuentra el evento de la presentación</div>
                <div class="pres-paso-desc">
                    Busca el evento
                    <strong style="color:rgba(245,241,234,0.75);">"VIBEZ DAW — Presentación del Proyecto"</strong>
                    en el catálogo o
                    @if($evento)
                        <a href="{{ route('eventos.detalle', $evento->id) }}"
                           style="color:#a855f7;text-decoration:none;">haz clic aquí para ir directamente</a>.
                    @else
                        encuéntralo en la lista de eventos.
                    @endif
                </div>
            </div>
        </div>
        <div class="pres-paso">
            <div class="pres-paso-num">3</div>
            <div>
                <div class="pres-paso-titulo">Compra tu entrada con la tarjeta de prueba</div>
                <div class="pres-paso-desc">
                    Usa la tarjeta de test de Stripe que encontrarás a continuación. El proceso es idéntico al de una compra real.
                </div>
            </div>
        </div>
        <div class="pres-paso">
            <div class="pres-paso-num">4</div>
            <div>
                <div class="pres-paso-titulo">Revisa tu correo</div>
                <div class="pres-paso-desc">
                    Recibirás un email de confirmación con tu código QR de entrada. Guárdalo o accede desde
                    <strong style="color:rgba(245,241,234,0.75);">Mis entradas</strong> en tu perfil.
                </div>
            </div>
        </div>
        <div class="pres-paso">
            <div class="pres-paso-num">5</div>
            <div>
                <div class="pres-paso-titulo">Presenta el QR el día de la presentación</div>
                <div class="pres-paso-desc">
                    El personal validará tu QR en la entrada. Sin entrada válida no se permite el acceso. Puedes mostrarlo desde el móvil.
                </div>
            </div>
        </div>
    </div>

    <div class="pres-divider"></div>

    {{-- ── 3. Tarjeta Stripe ── --}}
    <p class="pres-seccion-titulo">03 · Tarjeta de pago de prueba</p>
    <p style="font-family:'Archivo Narrow',sans-serif;font-size:14px;color:rgba(245,241,234,0.45);margin-bottom:24px;margin-top:-12px;">
        Entorno de pruebas de Stripe. Ningún cargo real.
    </p>

    <div class="pres-stripe-card">
        <div class="pres-stripe-visual">
            <div class="pres-stripe-num" onclick="copiarText('4242424242424242')"
                 style="cursor:pointer;transition:opacity 0.15s;"
                 onmouseover="this.style.opacity='0.75'" onmouseout="this.style.opacity='1'">
                4242 4242 4242 4242
            </div>
            <div class="pres-stripe-meta">
                <div>
                    <span>CADUCIDAD</span>
                    <strong>12/29</strong>
                </div>
                <div>
                    <span>CVC</span>
                    <strong>123</strong>
                </div>
                <div>
                    <span>CP</span>
                    <strong>08001</strong>
                </div>
            </div>
        </div>
        <div class="pres-stripe-info">
            <h3>Tarjeta Stripe Test</h3>
            <p>
                Usa el número <strong style="color:rgba(245,241,234,0.8);font-family:'Courier New',monospace;">4242 4242 4242 4242</strong> en el formulario de pago.
                Pon cualquier fecha de caducidad futura, cualquier CVC de 3 dígitos y cualquier código postal.
                El pago se procesará como real pero <strong style="color:#6ee7b7;">sin ningún cargo verdadero</strong>.
                Haz clic sobre el número para copiarlo.
            </p>
        </div>
    </div>

    {{-- ── 4. Confirmación email ── --}}
    <div class="pres-info-final">
        <div class="pres-info-final-icon">✉</div>
        <div>
            <h3>Recibirás tu entrada por correo</h3>
            <p>
                Una vez completado el pago, recibirás automáticamente un email con tu
                <strong style="color:rgba(245,241,234,0.75);">código QR de entrada</strong>.
                Este código es personal e intransferible. El día de la presentación, preséntalo al personal para que lo validen con el lector QR de la plataforma.
            </p>
        </div>
    </div>

    {{-- ── Botón ir al evento ── --}}
    @if($evento)
    <div style="text-align:center;padding:32px 0;">
        <a href="{{ route('eventos.detalle', $evento->id) }}" class="pres-btn-evento">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l19-9-9 19-2-8-8-2z"/></svg>
            Ir al evento
        </a>
    </div>
    @endif

    {{-- ── Pie ── --}}
    <div class="pres-divider"></div>
    <p style="text-align:center;font-family:'Archivo Narrow',sans-serif;font-size:12px;color:rgba(245,241,234,0.2);letter-spacing:0.08em;">
        VIBEZ · CFGS DAW · Jesuïtes Bellvitge · Curso 2025–2026
    </p>

</div>

@include('partials.home.footer')

@endsection

@push('scripts')
<script src="{{ asset('js/static-presentacion.js') }}"></script>
{{-- JS en public/js/static-presentacion.js --}}
@endpush

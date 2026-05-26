@extends('layouts.app')

@section('titulo', 'Presentación del Proyecto — VIBEZ')

@push('estilos')
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="{{ asset('css/vibez-home.css') }}">
<style>
body { background: #07060c; }

/* ─── Hero ─── */
.pres-hero {
    text-align: center;
    padding: 72px 24px 48px;
}
.pres-badge {
    display: inline-block;
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    color: rgba(168,85,247,0.9);
    border: 1px solid rgba(168,85,247,0.3);
    border-radius: 999px;
    padding: 5px 14px;
    margin-bottom: 24px;
}
.pres-titulo {
    font-family: 'Bebas Neue', sans-serif;
    font-size: clamp(3rem, 8vw, 6rem);
    color: #f5f1ea;
    line-height: 0.92;
    margin-bottom: 20px;
    letter-spacing: 0.01em;
}
.pres-titulo span {
    background: linear-gradient(135deg, #7c3aed, #a855f7, #c084fc);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.pres-subtitulo {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 17px;
    color: rgba(245,241,234,0.55);
    max-width: 520px;
    margin: 0 auto 16px;
    line-height: 1.5;
}

/* ─── Sección ─── */
.pres-seccion {
    max-width: 860px;
    margin: 0 auto;
    padding: 0 24px 80px;
}
.pres-seccion-titulo {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: rgba(168,85,247,0.75);
    margin-bottom: 20px;
}

/* ─── Cards de credenciales ─── */
.pres-creds-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 16px;
    margin-bottom: 48px;
}
.pres-cred-card {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(245,241,234,0.08);
    border-radius: 16px;
    padding: 24px;
    position: relative;
    overflow: hidden;
}
.pres-cred-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, #7c3aed, #a855f7);
    border-radius: 16px 16px 0 0;
}
.pres-cred-num {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 13px;
    letter-spacing: 0.15em;
    color: rgba(168,85,247,0.6);
    margin-bottom: 10px;
}
.pres-cred-nombre {
    font-family: 'Anton', sans-serif;
    font-size: 20px;
    color: #f5f1ea;
    margin-bottom: 16px;
    line-height: 1.1;
}
.pres-cred-campo { margin-bottom: 10px; }
.pres-cred-label {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 9px;
    font-weight: 600;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: rgba(245,241,234,0.35);
    margin-bottom: 4px;
}
.pres-cred-valor {
    font-family: 'Archivo', sans-serif;
    font-size: 13px;
    color: rgba(245,241,234,0.85);
    background: rgba(124,58,237,0.1);
    border: 1px solid rgba(124,58,237,0.2);
    border-radius: 6px;
    padding: 6px 10px;
    word-break: break-all;
    cursor: pointer;
    transition: background 0.15s;
}
.pres-cred-valor:hover { background: rgba(124,58,237,0.2); }

/* ─── Pasos ─── */
.pres-pasos {
    display: flex;
    flex-direction: column;
    gap: 0;
    margin-bottom: 48px;
}
.pres-paso {
    display: flex;
    gap: 20px;
    align-items: flex-start;
    padding: 20px 0;
    border-bottom: 1px solid rgba(245,241,234,0.06);
}
.pres-paso:last-child { border-bottom: none; }
.pres-paso-num {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #7c3aed, #a855f7);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Bebas Neue', sans-serif;
    font-size: 17px;
    color: #f5f1ea;
    flex-shrink: 0;
    margin-top: 2px;
    box-shadow: 0 4px 14px rgba(124,58,237,0.35);
}
.pres-paso-titulo {
    font-family: 'Anton', sans-serif;
    font-size: 17px;
    color: #f5f1ea;
    margin-bottom: 5px;
    letter-spacing: 0.01em;
}
.pres-paso-desc {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 14px;
    color: rgba(245,241,234,0.5);
    line-height: 1.5;
}

/* ─── Tarjeta Stripe ─── */
.pres-stripe-card {
    background: rgba(99,91,255,0.08);
    border: 1px solid rgba(99,91,255,0.25);
    border-radius: 16px;
    padding: 28px 32px;
    margin-bottom: 48px;
    display: flex;
    align-items: center;
    gap: 28px;
    flex-wrap: wrap;
}
.pres-stripe-visual {
    background: linear-gradient(135deg, #1a1a3e 0%, #2d1b69 100%);
    border: 1px solid rgba(99,91,255,0.4);
    border-radius: 12px;
    padding: 18px 22px;
    min-width: 220px;
    font-family: 'Courier New', monospace;
    flex-shrink: 0;
}
.pres-stripe-num {
    font-size: 18px;
    color: #f5f1ea;
    letter-spacing: 0.22em;
    margin-bottom: 12px;
    font-weight: 600;
}
.pres-stripe-meta { display: flex; gap: 20px; }
.pres-stripe-meta span {
    font-size: 11px;
    color: rgba(245,241,234,0.45);
    letter-spacing: 0.1em;
}
.pres-stripe-meta strong {
    display: block;
    font-size: 14px;
    color: rgba(245,241,234,0.85);
}
.pres-stripe-info { flex: 1; }
.pres-stripe-info h3 {
    font-family: 'Anton', sans-serif;
    font-size: 18px;
    color: #f5f1ea;
    margin-bottom: 8px;
}
.pres-stripe-info p {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 14px;
    color: rgba(245,241,234,0.5);
    line-height: 1.6;
}

/* ─── Info final ─── */
.pres-info-final {
    background: rgba(16,185,129,0.07);
    border: 1px solid rgba(16,185,129,0.2);
    border-radius: 14px;
    padding: 24px 28px;
    margin-bottom: 48px;
    display: flex;
    gap: 16px;
    align-items: flex-start;
}
.pres-info-final-icon { font-size: 24px; flex-shrink: 0; margin-top: 2px; }
.pres-info-final h3 {
    font-family: 'Anton', sans-serif;
    font-size: 17px;
    color: #6ee7b7;
    margin-bottom: 6px;
}
.pres-info-final p {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 14px;
    color: rgba(245,241,234,0.5);
    line-height: 1.6;
    margin: 0;
}

/* ─── Botón evento ─── */
.pres-btn-evento {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 16px 36px;
    background: linear-gradient(135deg, #7c3aed, #a855f7);
    color: #f5f1ea;
    border: none;
    border-radius: 999px;
    font-family: 'Anton', sans-serif;
    font-size: 17px;
    letter-spacing: 0.04em;
    text-decoration: none;
    text-transform: uppercase;
    box-shadow: 0 4px 24px rgba(124,58,237,0.45);
    transition: box-shadow 0.2s, transform 0.15s;
    cursor: pointer;
}
.pres-btn-evento:hover {
    box-shadow: 0 6px 32px rgba(124,58,237,0.65);
    transform: translateY(-1px);
}

/* ─── Divider ─── */
.pres-divider {
    height: 1px;
    background: rgba(245,241,234,0.06);
    margin: 40px 0;
}

/* ─── Tooltip copiado ─── */
.pres-tooltip {
    position: fixed;
    bottom: 32px;
    left: 50%;
    transform: translateX(-50%) translateY(10px);
    background: rgba(124,58,237,0.95);
    color: #f5f1ea;
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 13px;
    padding: 10px 22px;
    border-radius: 999px;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.2s, transform 0.2s;
    z-index: 999;
    white-space: nowrap;
}
.pres-tooltip.visible {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
}

/* ─── Responsive móvil ─── */
@media (max-width: 640px) {
    /* Hero */
    .pres-hero {
        padding: 48px 20px 32px;
    }
    .pres-titulo {
        font-size: clamp(2.6rem, 14vw, 4rem);
        line-height: 0.9;
        margin-bottom: 16px;
    }
    .pres-subtitulo {
        font-size: 15px;
    }

    /* Sección */
    .pres-seccion {
        padding: 0 16px 60px;
    }

    /* Credenciales: una columna, más compactas */
    .pres-creds-grid {
        grid-template-columns: 1fr;
        gap: 12px;
        margin-bottom: 32px;
    }
    .pres-cred-card {
        padding: 20px 16px;
    }
    .pres-cred-nombre {
        font-size: 18px;
        margin-bottom: 12px;
    }
    /* Valor copiable más alto para facilitar el toque con el dedo */
    .pres-cred-valor {
        padding: 10px 12px;
        font-size: 14px;
    }

    /* Pasos */
    .pres-paso {
        padding: 16px 0;
        gap: 14px;
    }
    .pres-paso-num {
        width: 32px;
        height: 32px;
        font-size: 15px;
        flex-shrink: 0;
    }
    .pres-paso-titulo {
        font-size: 15px;
    }
    .pres-paso-desc {
        font-size: 13px;
    }

    /* Tarjeta Stripe: apilada */
    .pres-stripe-card {
        flex-direction: column;
        padding: 20px 16px;
        gap: 16px;
    }
    .pres-stripe-visual {
        min-width: unset;
        width: 100%;
        padding: 16px;
    }
    .pres-stripe-num {
        font-size: 15px;
        letter-spacing: 0.15em;
        margin-bottom: 10px;
    }
    .pres-stripe-meta {
        gap: 14px;
    }
    .pres-stripe-info h3 {
        font-size: 16px;
    }
    .pres-stripe-info p {
        font-size: 13px;
    }

    /* Info final */
    .pres-info-final {
        padding: 18px 16px;
        gap: 12px;
    }
    .pres-info-final h3 {
        font-size: 15px;
    }
    .pres-info-final p {
        font-size: 13px;
    }

    /* Botón evento */
    .pres-btn-evento {
        font-size: 15px;
        padding: 14px 28px;
        width: 100%;
        justify-content: center;
    }

    /* Tooltip abajo centrado */
    .pres-tooltip {
        font-size: 12px;
        padding: 9px 18px;
        bottom: 20px;
    }

    /* Divider spacing */
    .pres-divider {
        margin: 28px 0;
    }
}
</style>
@endpush

@section('content')

{{-- Nav idéntico al original pero sin links de navegación --}}
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
  @media (max-width: 768px) {
    .nav-inner-pad { padding: 12px 16px !important; }
    .vibez-logo-img { height: 42px !important; }
  }
</style>

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
<script>
/* Copia el texto al portapapeles y muestra un tooltip */
function copiarText(text) {
    navigator.clipboard.writeText(text).then(function() {
        var tooltip = document.getElementById('pres-tooltip');
        tooltip.classList.add('visible');
        setTimeout(function() {
            tooltip.classList.remove('visible');
        }, 1800);
    });
}
</script>
@endpush

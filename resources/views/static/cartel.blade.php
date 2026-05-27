<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>VIBEZ — Cartell Presentació</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Archivo+Narrow:ital,wght@0,400;0,600;0,700;1,400&family=Archivo:wght@400;500;700&family=Bebas+Neue&display=swap" rel="stylesheet">

</head>
<body>

<div class="poster">
    <div class="deco-line"></div>
    <div class="glow-top"></div>
    <div class="glow-bottom"></div>

    {{-- ─── Header ─── --}}
    <div class="poster-header">
        <div class="logo-wrap">
            <div class="logo-isotip">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M12 2L15.5 9H22L16.5 13.5L18.5 21L12 17L5.5 21L7.5 13.5L2 9H8.5L12 2Z" fill="white"/>
                </svg>
            </div>
            <div class="logo-text">VIBEZ</div>
        </div>
        <div class="header-badge">Proyecto Final · CFGS DAW</div>
    </div>

    {{-- ─── Hero ─── --}}
    <div class="poster-hero">
        <div class="hero-eyebrow">Ciclo Formativo de Grado Superior · DAW · 2025–2026</div>
        <div class="hero-title">
            LA PLATAFORMA<br>
            <span class="gradient-text">DE EVENTOS</span><br>
            PARA JÓVENES
        </div>
        <div class="hero-subtitle">
            Escanea el QR, accede a la plataforma y compra tu entrada para nuestra presentación final.
        </div>
    </div>

    <div class="divider-line"></div>

    {{-- ─── QR + Steps ─── --}}
    <div class="poster-body">

        <div class="qr-wrap">
            <div class="qr-border">
                <div class="qr-corner tl"></div>
                <div class="qr-corner tr"></div>
                <div class="qr-corner bl"></div>
                <div class="qr-corner br"></div>
                <img
                    src="https://api.qrserver.com/v1/create-qr-code/?data=https%3A%2F%2Fg3.daw2j23.es%2Fpresentacion&size=400x400&bgcolor=07060c&color=f5f1ea&margin=10&ecc=M"
                    alt="QR Presentación VIBEZ"
                >
            </div>
            <div class="qr-label">Escanea para acceder</div>
        </div>

        <div class="poster-steps">
            <div class="step-title-main">Cómo participar</div>

            <div class="step-item">
                <div class="step-num">1</div>
                <div class="step-text">
                    <strong>Escanea el QR o accede a la web</strong>
                    <span>Encontrarás tus credenciales personalizadas de acceso</span>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num">2</div>
                <div class="step-text">
                    <strong>Inicia sesión en VIBEZ</strong>
                    <span>Usa el correo y la contraseña asignados</span>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num">3</div>
                <div class="step-text">
                    <strong>Compra tu entrada de la presentación</strong>
                    <span>Busca el evento "VIBEZ DAW" y usa la tarjeta de test de abajo</span>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num">4</div>
                <div class="step-text">
                    <strong>Revisa el correo y guarda el QR</strong>
                    <span>Recibirás tu entrada digital en el correo electrónico</span>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num">5</div>
                <div class="step-text">
                    <strong>Presenta el QR en la entrada</strong>
                    <span>El personal validará el código. Sin entrada no se puede acceder</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── Stripe ─── --}}
    <div class="stripe-row">
        <div class="stripe-label">Tarjeta<br>de prueba</div>
        <div class="stripe-num">4242 4242 4242 4242</div>
        <div class="stripe-hint">Caducidad: 12/29 · CVC: 123<br>Ningún cargo real</div>
    </div>

    {{-- ─── Footer ─── --}}
    <div class="poster-footer">
        <div class="footer-team">Samuel · Santi · Pol · Marc · David</div>
        <div class="footer-escola">
            Jesuïtes Bellvitge · L'Hospitalet<br>
            Curso 2025–2026
        </div>
    </div>
</div>

<button class="btn-print" onclick="window.print()">Imprimir / Guardar PDF</button>
<!-- Recuerda activar "Gráficos de fondo" en el diálogo de impresión -->

</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>VIBEZ — Cartell Presentació</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Archivo+Narrow:ital,wght@0,400;0,600;0,700;1,400&family=Archivo:wght@400;500;700&family=Bebas+Neue&display=swap" rel="stylesheet">
<style>
/* Força tots els colors en impressió/PDF */
*, *::before, *::after {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
    color-adjust: exact !important;
}

@page {
    size: A4 portrait;
    margin: 0;
}

html, body {
    background: #07060c !important;
    font-family: 'Archivo', sans-serif;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
}

/* ─── A4 poster container ─── */
.poster {
    width: 794px;
    min-height: 1123px;
    background: #07060c !important;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    padding: 60px 64px 48px;
    border: 1px solid rgba(245,241,234,0.05);
}

/* ─── Glow blobs ─── */
.glow-top {
    position: absolute;
    top: -120px;
    right: -80px;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(124,58,237,0.3) 0%, transparent 65%) !important;
    border-radius: 50%;
    pointer-events: none;
}
.glow-bottom {
    position: absolute;
    bottom: -80px;
    left: -60px;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(168,85,247,0.2) 0%, transparent 65%) !important;
    border-radius: 50%;
    pointer-events: none;
}

/* ─── Línia decorativa superior ─── */
.deco-line {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, transparent, #7c3aed 30%, #a855f7 60%, transparent) !important;
}

/* ─── Header ─── */
.poster-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 48px;
    position: relative;
    z-index: 1;
}
.logo-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
}
.logo-isotip {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #7c3aed, #a855f7) !important;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.logo-text {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 28px;
    color: #f5f1ea !important;
    letter-spacing: 0.06em;
}
.header-badge {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 9px;
    font-weight: 600;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: #a855f7 !important;
    border: 1px solid rgba(168,85,247,0.4) !important;
    border-radius: 999px;
    padding: 5px 12px;
}

/* ─── Títol principal ─── */
.poster-hero {
    position: relative;
    z-index: 1;
    margin-bottom: 36px;
}
.hero-eyebrow {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    color: rgba(245,241,234,0.4) !important;
    margin-bottom: 14px;
}
.hero-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 96px;
    line-height: 0.88;
    color: #f5f1ea !important;
    letter-spacing: 0.01em;
    margin-bottom: 20px;
}
/* Text degradat: usa color sòlid com a fallback garantit en PDF */
.hero-title .gradient-text {
    color: #a855f7 !important;
    /* Gradient visible en pantalla */
    background: linear-gradient(135deg, #7c3aed 0%, #a855f7 50%, #c084fc 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: #a855f7;
}
.hero-subtitle {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 17px;
    color: rgba(245,241,234,0.55) !important;
    line-height: 1.4;
    max-width: 500px;
}

/* ─── Divisor ─── */
.divider-line {
    height: 1px;
    background: linear-gradient(90deg, rgba(124,58,237,0.7), rgba(168,85,247,0.4), transparent) !important;
    margin: 28px 0;
    position: relative;
    z-index: 1;
}

/* ─── Cos: QR + instruccions ─── */
.poster-body {
    display: flex;
    gap: 40px;
    align-items: flex-start;
    margin-bottom: 32px;
    position: relative;
    z-index: 1;
    flex: 1;
}

/* ─── QR ─── */
.qr-wrap {
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
}
.qr-border {
    padding: 10px;
    background: rgba(255,255,255,0.04) !important;
    border: 1px solid rgba(168,85,247,0.4) !important;
    border-radius: 14px;
    position: relative;
}
.qr-border img {
    display: block;
    border-radius: 6px;
    width: 200px;
    height: 200px;
    background: #07060c !important;
}
.qr-corner {
    position: absolute;
    width: 14px;
    height: 14px;
    border-color: #a855f7 !important;
    border-style: solid;
}
.qr-corner.tl { top: -1px; left: -1px; border-width: 2px 0 0 2px; border-radius: 4px 0 0 0; }
.qr-corner.tr { top: -1px; right: -1px; border-width: 2px 2px 0 0; border-radius: 0 4px 0 0; }
.qr-corner.bl { bottom: -1px; left: -1px; border-width: 0 0 2px 2px; border-radius: 0 0 0 4px; }
.qr-corner.br { bottom: -1px; right: -1px; border-width: 0 2px 2px 0; border-radius: 0 0 4px 0; }
.qr-label {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: #a855f7 !important;
    text-align: center;
}

/* ─── Instruccions ─── */
.poster-steps { flex: 1; }
.step-title-main {
    font-family: 'Anton', sans-serif;
    font-size: 20px;
    color: #f5f1ea !important;
    margin-bottom: 22px;
    letter-spacing: 0.02em;
}
.step-item {
    display: flex;
    gap: 14px;
    align-items: flex-start;
    margin-bottom: 16px;
}
.step-num {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: linear-gradient(135deg, #7c3aed, #a855f7) !important;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Bebas Neue', sans-serif;
    font-size: 14px;
    color: #f5f1ea !important;
    flex-shrink: 0;
}
.step-text strong {
    display: block;
    font-family: 'Archivo', sans-serif;
    font-size: 13px;
    font-weight: 700;
    color: #f5f1ea !important;
    margin-bottom: 2px;
}
.step-text span {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 12px;
    color: rgba(245,241,234,0.45) !important;
    line-height: 1.45;
}

/* ─── Targeta Stripe ─── */
.stripe-row {
    background: rgba(99,91,255,0.12) !important;
    border: 1px solid rgba(99,91,255,0.35) !important;
    border-radius: 12px;
    padding: 16px 22px;
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 28px;
    position: relative;
    z-index: 1;
}
.stripe-label {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 9px;
    font-weight: 600;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: #818cf8 !important;
    flex-shrink: 0;
    line-height: 1.5;
}
.stripe-num {
    font-family: 'Courier New', monospace;
    font-size: 22px;
    font-weight: 700;
    color: #f5f1ea !important;
    letter-spacing: 0.2em;
    flex: 1;
    text-align: center;
}
.stripe-hint {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 11px;
    color: rgba(245,241,234,0.35) !important;
    flex-shrink: 0;
    line-height: 1.6;
}

/* ─── Footer ─── */
.poster-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    z-index: 1;
    padding-top: 18px;
    border-top: 1px solid rgba(245,241,234,0.08) !important;
}
.footer-team {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 11px;
    color: rgba(245,241,234,0.28) !important;
    letter-spacing: 0.08em;
}
.footer-escola {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 11px;
    color: rgba(168,85,247,0.55) !important;
    letter-spacing: 0.06em;
    text-align: right;
    line-height: 1.5;
}

/* ─── Botó imprimir (no apareix en PDF) ─── */
.btn-print {
    position: fixed;
    bottom: 24px;
    right: 24px;
    padding: 12px 24px;
    background: linear-gradient(135deg, #7c3aed, #a855f7);
    color: #f5f1ea;
    border: none;
    border-radius: 999px;
    font-family: 'Archivo', sans-serif;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 4px 20px rgba(124,58,237,0.5);
    transition: box-shadow 0.2s;
    z-index: 1000;
}
.btn-print:hover { box-shadow: 0 6px 28px rgba(124,58,237,0.7); }

@media print {
    html, body {
        background: #07060c !important;
        padding: 0 !important;
    }
    .btn-print { display: none !important; }
    .poster {
        width: 100% !important;
        min-height: 100vh !important;
        border: none !important;
    }
}
</style>
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

<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VIBEZ — Descubre tu próximo evento</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/logo_vibez.png')); ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;700;900&family=Fraunces:ital,opsz,wght@1,9..144,300;1,9..144,400&family=JetBrains+Mono:wght@400;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <style type="text/tailwindcss">
        @theme {
            --font-display: 'Archivo', sans-serif;
            --font-sans:    'Space Grotesk', sans-serif;
            --font-mono:    'JetBrains Mono', monospace;
            --font-serif:   'Fraunces', serif;

            --color-paper: #F7F5FF;
            --color-ink:   #1B1430;
            --color-lilac: #8B78CC;
            --color-plum:  #4E3A96;
            --color-muted: #ACA4C4;
            --color-dusk:  #E9E3FF;

            --tracking-tightest: -0.04em;
            --tracking-brutal:   -0.06em;
        }

        @layer base {
            *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
            body {
                @apply text-ink font-sans antialiased;
                background-color: #F7F5FF;
                background-image: radial-gradient(circle, rgba(139,120,204,0.18) 1.5px, transparent 1.5px);
                background-size: 28px 28px;
            }
            ::selection { background: #8B78CC; color: #F7F5FF; }
        }

        @layer utilities {
            .text-mega {
                font-size: clamp(4.5rem, 20vw, 16rem);
                line-height: 0.85;
                letter-spacing: -0.055em;
            }
        }

        /* ── Animaciones de entrada ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        /* ── Orbs flotantes de fondo ── */
        @keyframes orbA {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50%       { transform: translate(30px, -20px) scale(1.06); }
        }
        @keyframes orbB {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50%       { transform: translate(-22px, 26px) scale(1.04); }
        }
        @keyframes orbC {
            0%, 100% { transform: translate(0, 0); }
            33%       { transform: translate(14px, -18px); }
            66%       { transform: translate(-10px, 10px); }
        }

        /* ── Marquee ── */
        @keyframes ticker {
            from { transform: translateX(0); }
            to   { transform: translateX(-50%); }
        }

        /* ── Shimmer en el wordmark ── */
        @keyframes wordmarkShimmer {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* ── Parpadeo suave del separador lila ── */
        @keyframes pulse-line {
            0%, 100% { opacity: 0.25; }
            50%       { opacity: 0.6; }
        }

        /* ── Clases de stagger de entrada ── */
        .anim-1 { animation: fadeUp 0.7s cubic-bezier(0.16,1,0.3,1) both; }
        .anim-2 { animation: fadeUp 0.7s 0.12s cubic-bezier(0.16,1,0.3,1) both; }
        .anim-3 { animation: fadeUp 0.7s 0.24s cubic-bezier(0.16,1,0.3,1) both; }
        .anim-4 { animation: fadeUp 0.7s 0.36s cubic-bezier(0.16,1,0.3,1) both; }
        .anim-5 { animation: fadeUp 0.7s 0.48s cubic-bezier(0.16,1,0.3,1) both; }
        .anim-fade { animation: fadeIn 1s 0.1s ease both; }

        /* ── Wordmark con gradiente animado ── */
        .wordmark-gradient {
            background: linear-gradient(
                270deg,
                #8B78CC 0%, #4E3A96 30%, #1B1430 55%, #4E3A96 80%, #8B78CC 100%
            );
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: wordmarkShimmer 6s ease infinite;
        }

        /* ── Botón primario: relleno desde abajo ── */
        .btn-ink {
            position: relative;
            overflow: hidden;
            isolation: isolate;
            background: #1B1430;
            color: #F7F5FF;
        }
        .btn-ink::after {
            content: '';
            position: absolute; inset: 0;
            background: #4E3A96;
            transform: translateY(102%);
            transition: transform 0.22s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 0;
        }
        .btn-ink:hover::after { transform: translateY(0); }
        .btn-ink > span { position: relative; z-index: 1; }

        /* ── Botón ghost: relleno dusk desde abajo ── */
        .btn-ghost-lila {
            position: relative;
            overflow: hidden;
            isolation: isolate;
            border: 1.5px solid rgba(27, 20, 48, 0.3);
            color: #1B1430;
        }
        .btn-ghost-lila::after {
            content: '';
            position: absolute; inset: 0;
            background: #E9E3FF;
            transform: translateY(102%);
            transition: transform 0.22s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 0;
        }
        .btn-ghost-lila:hover::after { transform: translateY(0); }
        .btn-ghost-lila:hover { border-color: #8B78CC; }
        .btn-ghost-lila > span { position: relative; z-index: 1; }

        /* ── Feature card: hover lift ── */
        .feature-card {
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .feature-card:hover { transform: translateY(-4px); }

        /* ── Separador lila pulsante ── */
        .sep-pulse {
            animation: pulse-line 3s ease-in-out infinite;
        }

        /* ── Orbs fijos de fondo ── */
        .orb {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }
        .orb-1 {
            width: 600px; height: 600px;
            background: #8B78CC;
            opacity: 0.22;
            filter: blur(80px);
            top: -180px; left: -180px;
            animation: orbA 16s ease-in-out infinite;
        }
        .orb-2 {
            width: 500px; height: 500px;
            background: #4E3A96;
            opacity: 0.18;
            filter: blur(70px);
            bottom: -120px; right: -140px;
            animation: orbB 20s ease-in-out infinite;
        }
        .orb-3 {
            width: 340px; height: 340px;
            background: #C4B5FD;
            opacity: 0.35;
            filter: blur(55px);
            top: 35%; left: 48%;
            animation: orbC 12s ease-in-out infinite;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-paper text-ink overflow-x-hidden relative">

    
    <div class="orb orb-1" aria-hidden="true"></div>
    <div class="orb orb-2" aria-hidden="true"></div>
    <div class="orb orb-3" aria-hidden="true"></div>

    
    <div class="relative z-10 border-b border-lilac/25 px-6 sm:px-10 py-3
                flex items-center justify-between shrink-0 anim-fade">
        <span class="font-mono text-xs uppercase tracking-widest text-muted select-none">
            VIBEZ &nbsp;·&nbsp; Est. 2025
        </span>
        <?php if(auth()->guard()->check()): ?>
            <a href="<?php echo e(route('home')); ?>"
               class="font-mono text-xs uppercase tracking-widest text-ink/50
                      hover:text-lilac transition-colors duration-150">
                Ir a la app &nbsp;→
            </a>
        <?php else: ?>
            <a href="/login"
               class="font-mono text-xs uppercase tracking-widest text-ink/50
                      hover:text-lilac transition-colors duration-150">
                Iniciar sesión &nbsp;→
            </a>
        <?php endif; ?>
    </div>

    
    <div class="relative z-10 border-b border-lilac/25 px-4 sm:px-8 overflow-hidden shrink-0 anim-1">
        <p class="wordmark-gradient font-display font-black text-mega leading-none py-2 sm:py-4 select-none"
           aria-label="VIBEZ">
            VIBEZ
        </p>
    </div>

    
    <div class="relative z-10 border-b border-lilac/25 bg-dusk overflow-hidden shrink-0 py-2.5 anim-2">
        <div style="display:inline-flex; white-space:nowrap; animation: ticker 28s linear infinite;">
            <?php
                $items = ['FESTIVALES', 'CONCIERTOS', 'EXPOSICIONES', 'BOLSA DE TRABAJO', 'ENTRADAS QR', 'COMUNIDAD', 'EVENTOS EN VIVO', 'NETWORKING'];
                $ticker = implode(' &nbsp;/&nbsp; ', $items) . ' &nbsp;/&nbsp; ';
            ?>
            <span class="font-mono text-xs uppercase tracking-widest text-plum px-4"><?php echo str_repeat($ticker, 4); ?></span>
        </div>
    </div>

    
    <div class="relative z-10 flex-1 grid grid-cols-1 md:grid-cols-12 border-b border-lilac/20">

        
        <div class="md:col-span-7 border-b md:border-b-0 md:border-r border-lilac/20
                    p-8 sm:p-10 lg:p-14 flex flex-col justify-between gap-10 anim-3">
            <div>
                <h1 class="font-display font-black text-4xl sm:text-5xl lg:text-6xl
                           tracking-tightest leading-[0.92] uppercase text-ink">
                    Descubre tu<br>próximo
                </h1>
                
                <p class="font-serif italic font-light
                          text-4xl sm:text-5xl lg:text-6xl
                          leading-[0.92] mt-1"
                   style="color: #8B78CC;">
                    evento.
                </p>

                <div class="mt-8 h-px sep-pulse" style="background: #8B78CC;"></div>

                <p class="mt-5 font-mono text-xs uppercase tracking-widest text-muted">
                    Eventos &nbsp;·&nbsp; Entradas QR &nbsp;·&nbsp; Trabajo &nbsp;·&nbsp; Comunidad
                </p>
            </div>

            <?php if(auth()->guard()->check()): ?>
                <div>
                    <p class="font-mono text-xs uppercase tracking-widest text-muted mb-4">
                        — Sesión activa
                    </p>
                    <a href="<?php echo e(route('home')); ?>"
                       class="btn-ink inline-block font-mono text-xs uppercase tracking-widest px-8 py-4">
                        <span>Explorar eventos &nbsp;→</span>
                    </a>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="md:col-span-5 p-8 sm:p-10 lg:p-14 flex flex-col justify-between gap-10 anim-4">

            <p class="font-sans text-base leading-relaxed text-ink/60 max-w-xs">
                La plataforma de eventos para jóvenes. Descubre lo que pasa en tu ciudad,
                compra entradas y conecta con gente de tu escena.
            </p>

            <?php if(auth()->guard()->guest()): ?>
                <div class="flex flex-col gap-3">
                    <a href="/register"
                       class="btn-ink font-mono text-xs uppercase tracking-widest
                              px-8 py-4 text-center block">
                        <span>Regístrate gratis &nbsp;→</span>
                    </a>
                    <a href="/login"
                       class="btn-ghost-lila font-mono text-xs uppercase tracking-widest
                              px-8 py-4 text-center block">
                        <span>Iniciar sesión</span>
                    </a>
                </div>
            <?php endif; ?>

            <?php if(auth()->guard()->check()): ?>
                <div class="space-y-1">
                    <p class="font-mono text-xs uppercase tracking-widest text-muted">Conectado como</p>
                    <p class="font-sans font-semibold text-ink">
                        <?php echo e(Auth::user()->nombre); ?> <?php echo e(Auth::user()->apellido1); ?>

                    </p>
                </div>
            <?php endif; ?>

            
            <div class="flex items-center gap-3">
                <div class="h-px flex-1 sep-pulse" style="background: linear-gradient(90deg, #8B78CC, #4E3A96);"></div>
                <span class="font-mono text-xs select-none" style="color: #8B78CC;">※</span>
                <div class="h-px flex-1 sep-pulse" style="background: linear-gradient(270deg, #8B78CC, #4E3A96);"></div>
            </div>
        </div>
    </div>

    
    <div class="relative z-10 grid grid-cols-2 md:grid-cols-4 shrink-0 anim-5">

        
        <div class="feature-card p-6 border-r border-lilac/20 border-b md:border-b-0"
             style="background: #E9E3FF;">
            <span class="font-mono text-xs" style="color: #8B78CC;">01</span>
            <p class="font-display font-black text-lg sm:text-xl tracking-tightest leading-none mt-2 uppercase text-ink">
                Eventos
            </p>
            <p class="font-sans text-xs mt-2 leading-relaxed" style="color: rgba(27,20,48,0.5);">
                Conciertos, festivales y más cerca de ti
            </p>
        </div>

        
        <div class="feature-card p-6 border-b md:border-b-0 md:border-r border-lilac/30"
             style="background: #8B78CC;">
            <span class="font-mono text-xs" style="color: #E9E3FF; opacity: 0.7;">02</span>
            <p class="font-display font-black text-lg sm:text-xl tracking-tightest leading-none mt-2 uppercase text-paper">
                Entradas
            </p>
            <p class="font-sans text-xs mt-2 leading-relaxed" style="color: rgba(247,245,255,0.65);">
                Compra y gestiona con código QR
            </p>
        </div>

        
        <div class="feature-card p-6 border-r border-plum/30"
             style="background: #4E3A96;">
            <span class="font-mono text-xs" style="color: #ACA4C4; opacity: 0.8;">03</span>
            <p class="font-display font-black text-lg sm:text-xl tracking-tightest leading-none mt-2 uppercase text-paper">
                Trabajo
            </p>
            <p class="font-sans text-xs mt-2 leading-relaxed" style="color: rgba(247,245,255,0.55);">
                Bolsa de empleo en cultura y ocio
            </p>
        </div>

        
        <div class="feature-card p-6"
             style="background: #1B1430;">
            <span class="font-mono text-xs" style="color: #ACA4C4; opacity: 0.7;">04</span>
            <p class="font-display font-black text-lg sm:text-xl tracking-tightest leading-none mt-2 uppercase text-paper">
                Social
            </p>
            <p class="font-sans text-xs mt-2 leading-relaxed" style="color: rgba(247,245,255,0.45);">
                Conecta con amigos y tu escena
            </p>
        </div>
    </div>

    
    <footer class="relative z-10 shrink-0" style="background: #1B1430;">
        <div class="px-6 sm:px-10 py-5 grid grid-cols-1 sm:grid-cols-3 items-center gap-4"
             style="border-top: 1px solid rgba(139,120,204,0.2);">
            <span class="font-display font-black text-xl tracking-brutal select-none wordmark-gradient">
                VIBEZ
            </span>
            <div class="flex gap-6 justify-start sm:justify-center
                        font-mono text-xs uppercase tracking-widest"
                 style="color: rgba(247,245,255,0.3);">
                <a href="#" class="hover:text-paper transition-colors duration-150">Privacidad</a>
                <a href="# " class="hover:text-paper transition-colors duration-150">Contacto</a>
            </div>
            <span class="font-mono text-xs text-left sm:text-right"
                  style="color: rgba(247,245,255,0.2);">
                © <?php echo e(date('Y')); ?> &nbsp;—&nbsp; <?php echo e(now()->format('d.m.y')); ?>

            </span>
        </div>
    </footer>

</body>
</html>
<?php /**PATH C:\wamp64\www\DAW2\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/welcome.blade.php ENDPATH**/ ?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VIBEZ — Descubre tu próximo evento</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900" rel="stylesheet" />

    <!-- Styles -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        * { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }

        body {
            background-color: #0f1a2e;
            color: #f8fafc;
            overflow-x: hidden;
        }

        /* Gradient text */
        .text-gradient {
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Gradient border button (ghost) */
        .btn-ghost {
            border: 1.5px solid rgba(124, 58, 237, 0.6);
            color: #f8fafc;
            transition: all 0.25s ease;
        }
        .btn-ghost:hover {
            border-color: #a855f7;
            background: rgba(124, 58, 237, 0.15);
            box-shadow: 0 0 16px rgba(168, 85, 247, 0.3);
        }

        /* CTA button */
        .btn-cta {
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            transition: all 0.25s ease;
        }
        .btn-cta:hover {
            transform: scale(1.05);
            box-shadow: 0 0 32px rgba(168, 85, 247, 0.55);
        }

        /* Background blobs */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.18;
            animation: float 8s ease-in-out infinite;
        }
        .blob-1 {
            width: 420px; height: 420px;
            background: #7c3aed;
            top: -80px; left: -100px;
            animation-delay: 0s;
        }
        .blob-2 {
            width: 350px; height: 350px;
            background: #a855f7;
            bottom: -60px; right: -80px;
            animation-delay: -4s;
        }
        .blob-3 {
            width: 220px; height: 220px;
            background: #6d28d9;
            top: 40%; left: 55%;
            animation-delay: -2s;
        }

        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-24px); }
        }

        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(32px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulseGlow {
            0%, 100% { filter: drop-shadow(0 0 8px rgba(168, 85, 247, 0.5))  scale(1); }
            50%       { filter: drop-shadow(0 0 22px rgba(168, 85, 247, 0.85)) scale(1.04); }
        }

        .hero-content {
            animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .logo-svg {
            animation: pulseGlow 3s ease-in-out infinite;
        }

        /* Feature pills */
        .pill {
            background: rgba(124, 58, 237, 0.15);
            border: 1px solid rgba(124, 58, 237, 0.35);
            backdrop-filter: blur(4px);
        }

        /* Noise texture overlay */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='1'/%3E%3C/svg%3E");
            opacity: 0.025;
            pointer-events: none;
            z-index: 0;
        }
    </style>
</head>
<body class="min-h-screen relative flex flex-col">

    <!-- Background blobs -->
    <div class="blob blob-1" aria-hidden="true"></div>
    <div class="blob blob-2" aria-hidden="true"></div>
    <div class="blob blob-3" aria-hidden="true"></div>

    <!-- Navigation -->
    <header class="relative z-10 flex items-center justify-between px-6 py-5 sm:px-10">
        <!-- Wordmark -->
        <span class="text-gradient text-2xl font-black tracking-tight select-none">VIBEZ</span>

        <!-- Auth link -->
        <a href="/login"
           class="btn-ghost text-sm font-semibold px-5 py-2 rounded-full">
            Iniciar sesión
        </a>
    </header>

    <!-- Hero -->
    <main class="relative z-10 flex-1 flex items-center justify-center px-6 py-16 sm:py-24">
        <div class="hero-content flex flex-col items-center text-center max-w-3xl mx-auto gap-8">

            <!-- SVG Logo: V with sound waves -->
            <div class="logo-svg" aria-label="VIBEZ logo">
                <svg width="120" height="100" viewBox="0 0 120 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="vgrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#7c3aed"/>
                            <stop offset="100%" stop-color="#a855f7"/>
                        </linearGradient>
                    </defs>

                    <!-- Sound waves left -->
                    <path d="M28 30 Q18 50 28 70" stroke="url(#vgrad)" stroke-width="3" stroke-linecap="round" fill="none" opacity="0.5"/>
                    <path d="M18 20 Q2  50 18 80"  stroke="url(#vgrad)" stroke-width="2" stroke-linecap="round" fill="none" opacity="0.28"/>

                    <!-- Sound waves right -->
                    <path d="M92 30 Q102 50 92 70" stroke="url(#vgrad)" stroke-width="3" stroke-linecap="round" fill="none" opacity="0.5"/>
                    <path d="M102 20 Q118 50 102 80" stroke="url(#vgrad)" stroke-width="2" stroke-linecap="round" fill="none" opacity="0.28"/>

                    <!-- Letter V -->
                    <path d="M34 18 L60 82 L86 18" stroke="url(#vgrad)" stroke-width="9" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                </svg>
            </div>

            <!-- Brand name -->
            <h1 class="text-gradient text-6xl sm:text-7xl font-black tracking-tighter leading-none -mt-2">
                VIBEZ
            </h1>

            <!-- Headline -->
            <p class="text-white text-3xl sm:text-4xl md:text-5xl font-bold leading-tight -mt-2">
                Descubre tu próximo<br>
                <span class="text-gradient">evento</span>
            </p>

            <!-- Subheadline -->
            <p class="text-slate-400 text-base sm:text-lg max-w-xl leading-relaxed">
                La plataforma de eventos para jóvenes. Descubre, crea, compra entradas
                y conecta con tu escena — todo en un solo lugar.
            </p>

            <!-- CTA -->
            <a href="/register"
               class="btn-cta text-white font-bold text-base sm:text-lg px-10 py-4 rounded-full shadow-lg mt-2">
                Regístrate gratis
            </a>

            <!-- Feature pills -->
            <div class="flex flex-wrap justify-center gap-3 mt-4">
                <span class="pill text-slate-300 text-xs font-medium px-4 py-1.5 rounded-full">🎟️ Entradas con QR</span>
                <span class="pill text-slate-300 text-xs font-medium px-4 py-1.5 rounded-full">🎉 Crea eventos</span>
                <span class="pill text-slate-300 text-xs font-medium px-4 py-1.5 rounded-full">🏷️ Cupones</span>
                <span class="pill text-slate-300 text-xs font-medium px-4 py-1.5 rounded-full">💼 Bolsa de trabajo</span>
                <span class="pill text-slate-300 text-xs font-medium px-4 py-1.5 rounded-full">👥 Social</span>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 text-center pb-8 text-slate-600 text-xs">
        &copy; {{ date('Y') }} VIBEZ — Projecte 5 · CFGS DAW
    </footer>

</body>
</html>

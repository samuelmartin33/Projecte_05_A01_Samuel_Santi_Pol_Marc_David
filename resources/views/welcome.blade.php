<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VIBEZ — Descubre tu próximo evento</title>

    <!-- Fuente Inter desde Bunny Fonts (sin Google) -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900" rel="stylesheet" />

    <!-- CSS y JS compilados por Vite -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen relative flex flex-col">

    <!-- Formas decorativas de fondo (detrás de todo) -->
    <div class="shape shape-1" aria-hidden="true"></div>
    <div class="shape shape-2" aria-hidden="true"></div>
    <div class="shape shape-3" aria-hidden="true"></div>

    <!-- ── CABECERA ─────────────────────────────────────────── -->
    <header class="header-purple relative z-10 flex items-center justify-between px-8 py-4 sm:px-14">

        <!-- nombre -->
        <div class="flex items-center gap-3">
            <span class="text-white text-xl font-black tracking-tight">VIBEZ</span>
        </div>

        <!-- Botón de login -->
        <a href="/login" class="btn-ghost text-sm font-semibold px-5 py-2 rounded-full">
            Iniciar sesión
        </a>

    </header>

    <!-- ── HERO ─────────────────────────────────────────────── -->
    <main class="relative z-10 flex-1 flex items-start justify-center px-6 pt-0 pb-8">
        <div class="hero-content flex flex-col items-center text-center max-w-2xl mx-auto gap-4">

            <!-- Logo grande -->
            <img src="{{ asset('images/logo_vibez.png') }}"
                 alt="VIBEZ logo" class="h-60 w-auto">

            <!-- Título principal -->
            <h1 class="text-3xl sm:text-4xl font-black tracking-tight leading-tight">
                Descubre tu próximo<br>
                <span class="text-gradient">evento</span>
            </h1>

            <!-- Subtítulo -->
            <p class="text-slate-500 text-sm max-w-md leading-relaxed font-light">
                La plataforma de eventos para jóvenes. Descubre, crea,
                compra entradas y conecta con tu escena.
            </p>

            <!-- Botón CTA -->
            <a href="/register"
               class="btn-cta font-semibold text-sm px-8 py-2.5 rounded-full">
                Regístrate gratis
            </a>

            <!-- Pills de características -->
            <div class="flex flex-wrap justify-center gap-2">
                <span class="pill text-xs font-medium px-4 py-1.5 rounded-full">🎟️ Entradas con QR</span>
                <span class="pill text-xs font-medium px-4 py-1.5 rounded-full">🎉 Crea eventos</span>
                <span class="pill text-xs font-medium px-4 py-1.5 rounded-full">🏷️ Cupones</span>
                <span class="pill text-xs font-medium px-4 py-1.5 rounded-full">💼 Bolsa de trabajo</span>
                <span class="pill text-xs font-medium px-4 py-1.5 rounded-full">👥 Social</span>
            </div>

        </div>
    </main>

    <!-- ── PIE DE PÁGINA ─────────────────────────────────────── -->
    <footer class="relative z-10 text-center pb-8 text-slate-400 text-xs">
        &copy; {{ date('Y') }} VIBEZ — Todos los derechos reservados.
    </footer>

</body>
</html>

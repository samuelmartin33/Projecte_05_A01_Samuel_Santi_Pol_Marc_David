<!DOCTYPE html>
<html lang="es" class="<?php echo $__env->yieldContent('html-class', ''); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php if (! empty(trim($__env->yieldContent('title')))): ?><?php echo $__env->yieldContent('title'); ?><?php else: ?> <?php echo $__env->yieldContent('titulo', 'VIBEZ'); ?> — Descubre tu próximo evento <?php endif; ?></title>

    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    
    <?php if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/css/style.css', 'resources/js/app.js']); ?>
    <?php else: ?>
        
        <script src="https://cdn.tailwindcss.com"></script>
        
        <link rel="stylesheet" href="/css/vibez.css">
    <?php endif; ?>

    
    <?php echo $__env->yieldPushContent('estilos'); ?>
    <?php echo $__env->yieldContent('extra-css'); ?>
</head>
<body class="min-h-screen flex flex-col <?php echo $__env->yieldContent('body-class', ''); ?>">

    
    <?php if(!View::hasSection('content')): ?>
    <header class="nav-vibez sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">

            
            <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-2 group">
                <div class="logo-isotipo">V</div>
                <span class="text-white font-black text-xl tracking-tight">VIBEZ</span>
            </a>

            
            <nav class="hidden md:flex items-center gap-8">
                <a href="<?php echo e(route('home')); ?>"
                   class="nav-link <?php echo e(request()->routeIs('home') ? 'nav-link-activo' : ''); ?>">
                    Explorar
                </a>
                <a href="<?php echo e(route('trabajos.index')); ?>"
                   class="nav-link <?php echo e(request()->routeIs('trabajos.index') ? 'nav-link-activo' : ''); ?>">
                    Bolsa de Trabajo
                </a>
            </nav>

            
            <div class="flex items-center gap-3">
                <?php if(auth()->guard()->check()): ?>
                    <div class="nav-user-info hidden sm:flex items-center gap-2">
                        <div class="nav-avatar"><?php echo e(strtoupper(substr(Auth::user()->nombre, 0, 1))); ?></div>
                        <span class="text-white/70 text-sm font-medium"><?php echo e(Auth::user()->nombre); ?></span>
                    </div>
                    <div class="nav-divider"></div>
                    <button onclick="cerrarSesion()" class="btn-nav-logout">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
                        </svg>
                        Cerrar sesión
                    </button>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="btn-nav-ghost">Entrar</a>
                    <a href="<?php echo e(route('register')); ?>" class="btn-nav-solido">Registro</a>
                <?php endif; ?>
            </div>

        </div>
    </header>
    <?php endif; ?>

    
    <main class="flex-1">
        <?php echo $__env->yieldContent('contenido'); ?>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    
    <?php if(!View::hasSection('content')): ?>
    <footer class="footer-vibez">
        <div class="max-w-7xl mx-auto px-6 py-12 flex flex-col items-center justify-center text-center gap-6">
            <div class="flex items-center gap-2">
                <div class="logo-isotipo text-sm w-8 h-8">V</div>
                <span class="font-bold text-white text-lg tracking-tight">VIBEZ</span>
            </div>
            <p class="text-white/40 text-sm max-w-md">
                &copy; <?php echo e(date('Y')); ?> VIBEZ — La plataforma definitiva de eventos para jóvenes. 
                Descubre, crea y conecta.
            </p>
            <div class="flex gap-8 text-white/50 text-sm font-medium">
                <a href="#" class="hover:text-white transition-colors">Privacidad</a>
                <a href="#" class="hover:text-white transition-colors">Términos</a>
                <a href="#" class="hover:text-white transition-colors">Contacto</a>
            </div>
        </div>
    </footer>
    <?php endif; ?>

    
    <?php echo $__env->yieldPushContent('scripts'); ?>
    <?php echo $__env->yieldContent('scripts'); ?>

    <?php if(auth()->guard()->check()): ?>
    
    <script>
    /**
     * Abre/cierra el dropdown del avatar de usuario en la navbar.
     * Gestiona el aria-expanded para accesibilidad.
     */
    function toggleNavDropdown() {
        const dropdown = document.getElementById('navDropdown');
        const btn      = document.getElementById('navAvatarBtn');
        const abierto  = dropdown.style.display === 'block';

        dropdown.style.display = abierto ? 'none' : 'block';
        btn.setAttribute('aria-expanded', String(!abierto));

        // Animar entrada
        if (!abierto) {
            dropdown.style.animation = 'none';
            dropdown.offsetHeight;  // reflow
            dropdown.style.animation = 'dropdownEntrar 0.18s ease';
        }
    }

    /** Cierra el dropdown si se pulsa fuera */
    document.addEventListener('click', function(e) {
        const wrapper = document.getElementById('navAvatarWrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            const dropdown = document.getElementById('navDropdown');
            const btn      = document.getElementById('navAvatarBtn');
            if (dropdown) dropdown.style.display = 'none';
            if (btn)      btn.setAttribute('aria-expanded', 'false');
        }
    });

    /**
     * Cierra la sesión del usuario mediante AJAX.
     * Redirige a la landing tras el logout.
     */
    function cerrarSesion() {
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('/api/logout', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        })
        .then(() => {
            document.body.style.transition = 'opacity 0.3s';
            document.body.style.opacity    = '0';
            setTimeout(() => { window.location.href = '/'; }, 320);
        })
        .catch(() => { window.location.href = '/'; });
    }
    </script>
    <?php endif; ?>
</body>
</html>
<?php /**PATH C:\wamp64\www\DAW2\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/layouts/app.blade.php ENDPATH**/ ?>
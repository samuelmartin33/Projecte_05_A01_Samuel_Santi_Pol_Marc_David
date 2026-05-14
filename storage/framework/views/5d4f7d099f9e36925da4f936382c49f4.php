<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>VIBEZ — Bienvenido</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/logo_vibez.png')); ?>">

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Bebas+Neue&family=Archivo:wght@400;500;600;700;800;900&family=Archivo+Narrow:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    
    <link rel="stylesheet" href="<?php echo e(asset('css/vibez-welcome.css')); ?>">
</head>
<body>


<div class="amb amb-1" aria-hidden="true"></div>
<div class="amb amb-2" aria-hidden="true"></div>
<div class="amb amb-3" aria-hidden="true"></div>


<header class="nav">
    <a href="<?php echo e(route('welcome')); ?>" class="logo" aria-label="VIBEZ — Inicio">
        <img src="<?php echo e(asset('images/logo_vibez_white.png')); ?>" alt="VIBEZ">
        <span>VIBEZ</span>
    </a>

    <nav class="nav-links" aria-label="Navegación principal">
        <a href="#features">Cómo funciona</a>
        <a href="#showcase">Eventos</a>
    </nav>

    <div class="nav-cta">
        <?php if(auth()->guard()->check()): ?>
            <a href="<?php echo e(Auth::user()->isEmpresa() ? route('empresa.home') : route('home')); ?>"
               class="btn-pri">Mi cuenta</a>
        <?php else: ?>
            <a href="<?php echo e(route('login')); ?>" class="btn-ghost">Entrar</a>
            <a href="<?php echo e(route('register')); ?>" class="btn-pri">Crear cuenta</a>
        <?php endif; ?>
        <button class="burger"
                onclick="document.getElementById('menu-movil').classList.add('open')"
                aria-label="Abrir menú" aria-expanded="false">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="6" x2="21" y2="6"/>
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>
    </div>
</header>


<div class="mobile-menu" id="menu-movil" role="dialog" aria-modal="true" aria-label="Menú de navegación">
    <button class="close"
            onclick="document.getElementById('menu-movil').classList.remove('open')"
            aria-label="Cerrar menú">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"/>
            <line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
    </button>
    <a href="#features" onclick="document.getElementById('menu-movil').classList.remove('open')">Cómo funciona</a>
    <a href="#showcase" onclick="document.getElementById('menu-movil').classList.remove('open')">Eventos</a>
    <div class="actions">
        <?php if(auth()->guard()->check()): ?>
            <a href="<?php echo e(Auth::user()->isEmpresa() ? route('empresa.home') : route('home')); ?>"
               class="btn-pri">Mi cuenta</a>
        <?php else: ?>
            <a href="<?php echo e(route('login')); ?>" class="btn-ghost">Entrar</a>
            <a href="<?php echo e(route('register')); ?>" class="btn-pri">Crear cuenta</a>
        <?php endif; ?>
    </div>
</div>


<section class="hero">

    
    <div>
        <div class="mono hero-kicker">▸ Edición #428 · <?php echo e(now()->locale('es')->isoFormat('MMMM YYYY')); ?></div>
        <h1>Esta noche<br><em>se rompe</em>.</h1>
        <p class="hero-sub">
            Eventos, fiestas, conciertos y festivales — todo lo que vive tu escena en un solo sitio.
            Compra entradas, descubre tu próxima noche y entra a la lista VIP.
        </p>
        <div class="hero-actions">
            <a href="<?php echo e(route('register')); ?>" class="btn-pri big">
                Crear cuenta
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <line x1="5" y1="12" x2="19" y2="12"/>
                    <polyline points="12 5 19 12 12 19"/>
                </svg>
            </a>
            <a href="<?php echo e(route('login')); ?>" class="btn-ghost">Ya tengo cuenta</a>
            <div class="hero-stat">
                <div class="num"><?php echo e($eventos->count() > 0 ? $eventos->count() : '12k'); ?>+</div>
                <div>eventos<br>disponibles</div>
            </div>
        </div>
    </div>

    
    <div class="hero-vis" aria-hidden="true">
        <div class="hero-vis-sticker">★ NEW · <?php echo e(date('Y')); ?></div>

        
        <div class="hero-card hero-card-1">
            <?php if($eventos->count() > 0): ?>
                <img src="<?php echo e($eventos[0]->url_portada ?? 'https://picsum.photos/seed/welcome-1/600/760'); ?>"
                     alt="<?php echo e($eventos[0]->titulo); ?>"
                     onerror="this.src='https://picsum.photos/seed/welcome-1/600/760'">
                <div class="hero-card-overlay"><span><?php echo e($eventos[0]->titulo); ?></span></div>
            <?php else: ?>
                <img src="https://picsum.photos/seed/welcome-1/600/760" alt="">
                <div class="hero-card-overlay"><span>Charlotte de Witte</span></div>
            <?php endif; ?>
        </div>

        
        <div class="hero-card hero-card-2">
            <?php if($eventos->count() > 1): ?>
                <img src="<?php echo e($eventos[1]->url_portada ?? 'https://picsum.photos/seed/welcome-2/500/620'); ?>"
                     alt="<?php echo e($eventos[1]->titulo); ?>"
                     onerror="this.src='https://picsum.photos/seed/welcome-2/500/620'">
                <div class="hero-card-overlay"><span><?php echo e($eventos[1]->titulo); ?></span></div>
            <?php else: ?>
                <img src="https://picsum.photos/seed/welcome-2/500/620" alt="">
                <div class="hero-card-overlay"><span>Primavera Sound</span></div>
            <?php endif; ?>
        </div>

        
        <div class="hero-card hero-card-3">
            <?php if($eventos->count() > 2): ?>
                <img src="<?php echo e($eventos[2]->url_portada ?? 'https://picsum.photos/seed/welcome-3/360/440'); ?>"
                     alt="<?php echo e($eventos[2]->titulo); ?>"
                     onerror="this.src='https://picsum.photos/seed/welcome-3/360/440'">
                <div class="hero-card-overlay"><span><?php echo e($eventos[2]->titulo); ?></span></div>
            <?php else: ?>
                <img src="https://picsum.photos/seed/welcome-3/360/440" alt="">
                <div class="hero-card-overlay"><span>Pacha · BCN</span></div>
            <?php endif; ?>
        </div>
    </div>

</section>


<div class="marquee" aria-hidden="true">
    <div class="marquee-track">
        <span>Esta noche se rompe<span class="star">✦</span></span>
        <span class="alt">BCN never sleeps<span class="star">✦</span></span>
        <span>Sound system on<span class="star">✦</span></span>
        <span class="alt">All night long<span class="star">✦</span></span>
        <span>Esta noche se rompe<span class="star">✦</span></span>
        <span class="alt">BCN never sleeps<span class="star">✦</span></span>
        <span>Sound system on<span class="star">✦</span></span>
        <span class="alt">All night long<span class="star">✦</span></span>
    </div>
</div>


<section class="features" id="features">
    <div class="section-head">
        <div class="mono">▸ Cómo funciona</div>
        <h2>Tu pase a la <em>noche</em></h2>
    </div>
    <div class="features-grid">
        <div class="feat-card">
            <div class="feat-num">01</div>
            <h3>Descubre</h3>
            <p>Más de 1.200 eventos por mes en BCN, Madrid, Valencia, Lisboa e Ibiza. Filtra por mood, género y aforo en tiempo real.</p>
        </div>
        <div class="feat-card">
            <div class="feat-num">02</div>
            <h3>Compra rápido</h3>
            <p>QR digital al instante. Sin colas, sin imprimir. Pago con tarjeta, Apple Pay y Bizum. Devolución hasta 24h antes.</p>
        </div>
        <div class="feat-card">
            <div class="feat-num">03</div>
            <h3>Pre-venta VIP</h3>
            <p>48h de acceso anticipado a sold-outs. Cupones exclusivos. Eventos solo para miembros y after-parties privadas.</p>
        </div>
    </div>
</section>


<section class="showcase" id="showcase">
    <div class="section-head">
        <div class="mono">▸ Esta semana</div>
        <h2>Lo que <em>todos</em> escuchan</h2>
    </div>
    <div class="show-grid">
        <?php $__empty_1 = true; $__currentLoopData = $eventos->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <a href="<?php echo e(route('eventos.detalle', $evento->id)); ?>" class="show-card">
                <img
                    src="<?php echo e($evento->url_portada); ?>"
                    alt="<?php echo e($evento->titulo); ?>"
                    onerror="this.src='https://picsum.photos/seed/show-<?php echo e($evento->id); ?>/400/520'"
                >
                <div class="show-info">
                    <div class="city">
                        <?php echo e($evento->ubicacion_nombre ?? 'BCN'); ?> · <?php echo e($evento->fecha_inicio->locale('es')->isoFormat('D MMM')); ?>

                    </div>
                    <div class="name"><?php echo e($evento->titulo); ?></div>
                </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            
            <a href="<?php echo e(route('register')); ?>" class="show-card">
                <img src="https://picsum.photos/seed/show-1/400/520" alt="">
                <div class="show-info"><div class="city">BCN · 09 May</div><div class="name">Charlotte de Witte</div></div>
            </a>
            <a href="<?php echo e(route('register')); ?>" class="show-card">
                <img src="https://picsum.photos/seed/show-2/400/520" alt="">
                <div class="show-info"><div class="city">BCN · 28 May</div><div class="name">Primavera Sound</div></div>
            </a>
            <a href="<?php echo e(route('register')); ?>" class="show-card">
                <img src="https://picsum.photos/seed/show-3/400/520" alt="">
                <div class="show-info"><div class="city">IBZ · 15 Jun</div><div class="name">Bad Bunny</div></div>
            </a>
            <a href="<?php echo e(route('register')); ?>" class="show-card">
                <img src="https://picsum.photos/seed/show-4/400/520" alt="">
                <div class="show-info"><div class="city">BCN · 22 May</div><div class="name">Dixon · Apolo</div></div>
            </a>
        <?php endif; ?>
    </div>
</section>


<section class="proof">
    <div class="proof-text">
        <div class="mono" style="color:var(--morado-3);font-size:11px;margin-bottom:14px;display:inline-block">▸ Únete a la lista</div>
        <h2>La <em>escena</em> ya está dentro.</h2>
        <p>Más de <?php echo e(number_format($statRavers, 0, ',', '.')); ?> ravers, <?php echo e($statPromotores); ?> promotores y <?php echo e($statEventos); ?> eventos activos. VIBEZ es la plataforma que mueve la noche de Iberia.</p>
        <a href="<?php echo e(route('register')); ?>" class="btn-pri big">
            Quiero entrar
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <line x1="5" y1="12" x2="19" y2="12"/>
                <polyline points="12 5 19 12 12 19"/>
            </svg>
        </a>
    </div>
    <div class="proof-stats">
        <div class="stat-card">
            <div class="stat-num" data-target="<?php echo e($statRavers); ?>" data-suffix="">0</div>
            <div class="stat-lbl">Ravers activos</div>
        </div>
        <div class="stat-card">
            <div class="stat-num" data-target="<?php echo e($statEventos); ?>" data-suffix="">0</div>
            <div class="stat-lbl">Eventos activos</div>
        </div>
        <div class="stat-card">
            <div class="stat-num" data-target="<?php echo e($statPromotores); ?>" data-suffix="">0</div>
            <div class="stat-lbl">Promotores</div>
        </div>
        <div class="stat-card">
            <div class="stat-num" data-target="<?php echo e($statSatisf); ?>" data-suffix="%">0</div>
            <div class="stat-lbl">Satisfacción</div>
        </div>
    </div>
</section>


<section class="final">
    <h2>La noche<br>te <em>espera</em>.</h2>
    <p>Crea tu pase VIBEZ en 30 segundos. Sin spam, sin compromisos. Solo música.</p>
    <div class="final-actions">
        <a href="<?php echo e(route('register')); ?>" class="btn-pri big">
            Crear mi pase
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <line x1="5" y1="12" x2="19" y2="12"/>
                <polyline points="12 5 19 12 12 19"/>
            </svg>
        </a>
        <a href="<?php echo e(route('login')); ?>" class="btn-ghost">Ya tengo cuenta</a>
    </div>
</section>


<footer>
    <div class="foot-row">
        <div>
            <a href="<?php echo e(route('welcome')); ?>" class="logo" style="display:inline-flex;margin-bottom:14px;">
                <img src="<?php echo e(asset('images/logo_vibez_white.png')); ?>" alt="VIBEZ">
                <span>VIBEZ</span>
            </a>
            <p style="color:var(--ink-dim);font-size:13px;line-height:1.5;max-width:320px;">
                La plataforma de la escena. Eventos, conciertos, festivales y bolsa de trabajo en BCN, Madrid, Valencia, Ibiza y Lisboa.
            </p>
        </div>
        <div>
            <h4>Plataforma</h4>
            <a href="<?php echo e(route('home')); ?>">Explorar</a>
            <a href="<?php echo e(route('trabajos.index')); ?>">Bolsa de trabajo</a>
            <a href="<?php echo e(route('register')); ?>">Crear cuenta</a>
            <a href="<?php echo e(route('login')); ?>">Entrar</a>
        </div>
        <div>
            <h4>Empresa</h4>
            <a href="#">Sobre VIBEZ</a>
            <a href="#">Prensa</a>
            <a href="#">Contacto</a>
        </div>
        <div>
            <h4>Legal</h4>
            <a href="#">Términos</a>
            <a href="#">Privacidad</a>
            <a href="#">Cookies</a>
            <a href="#">Devoluciones</a>
        </div>
    </div>
    <div class="foot-bottom">
        <span>© <?php echo e(date('Y')); ?> VIBEZ · BCN</span>
        <span>Edición 428 · Made for ravers</span>
    </div>
</footer>

<script>
function animarContadores() {
    var els = document.querySelectorAll('.stat-num[data-target]');
    for (var i = 0; i < els.length; i++) {
        (function(el) {
            var target  = parseInt(el.getAttribute('data-target'), 10);
            var suffix  = el.getAttribute('data-suffix') || '';
            var duration = 1600;
            var steps    = 60;
            var stepTime = duration / steps;
            var current  = 0;
            var increment = target / steps;
            var timer = setInterval(function() {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                el.textContent = Math.floor(current).toLocaleString('es-ES') + suffix;
            }, stepTime);
        })(els[i]);
    }
}

/* Lanzar cuando el elemento proof entra en pantalla */
var observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
        if (entry.isIntersecting) {
            animarContadores();
            observer.disconnect();
        }
    });
}, { threshold: 0.3 });

var proofSection = document.querySelector('.proof-stats');
if (proofSection) observer.observe(proofSection);
</script>
</body>
</html>
<?php /**PATH C:\wamp64\www\DAW2\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/welcome.blade.php ENDPATH**/ ?>
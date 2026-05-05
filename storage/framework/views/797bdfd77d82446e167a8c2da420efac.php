<?php $__env->startSection('titulo', 'Mi Empresa — VIBEZ'); ?>

<?php $__env->startPush('estilos'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/empresa-home.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('contenido'); ?>


<section class="hero-home">
    <div class="hero-particula hero-particula-1"></div>
    <div class="hero-particula hero-particula-2"></div>
    <div class="hero-particula hero-particula-3"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center relative z-10">

        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold mb-5"
             style="background:rgba(168,85,247,0.15);border:1px solid rgba(168,85,247,0.3);color:#c084fc;letter-spacing:0.06em;text-transform:uppercase;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            Panel de empresa
        </div>

        <h1 class="text-4xl sm:text-5xl font-black text-white tracking-tight leading-tight mb-3">
            <?php echo e($empresa->nombre_empresa ?? $usuario->nombre); ?>

        </h1>

        <p class="text-slate-400 text-lg max-w-xl mx-auto">
            Gestiona tus eventos, revisa tu equipo y haz crecer tu presencia en VIBEZ.
        </p>

        <div class="mt-8 flex flex-wrap justify-center gap-3">
            <a href="<?php echo e(route('empresa.eventos.create')); ?>" class="btn-crear-evento">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Crear evento
            </a>
            <a href="<?php echo e(route('empresa.ofertas.create')); ?>" class="btn-crear-evento" style="background:linear-gradient(135deg,#059669,#10b981);box-shadow:0 4px 20px rgba(16,185,129,0.35);">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Publicar oferta
            </a>
        </div>

    </div>
</section>


<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="empresa-stats-grid">
        <div class="empresa-stat-card">
            <div class="empresa-stat-numero"><?php echo e($eventos->count()); ?></div>
            <div class="empresa-stat-label">Eventos</div>
        </div>
        <div class="empresa-stat-card">
            <div class="empresa-stat-numero"><?php echo e($trabajadores->count()); ?></div>
            <div class="empresa-stat-label">Trabajadores</div>
        </div>
        <div class="empresa-stat-card">
            <div class="empresa-stat-numero"><?php echo e($ofertas->count()); ?></div>
            <div class="empresa-stat-label">Ofertas activas</div>
        </div>
    </div>
</section>


<?php if($empresa): ?>
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-6">
    <div class="seccion-empresa-titulo">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Información de la empresa
    </div>
    <p class="seccion-empresa-sub">Datos registrados de tu empresa</p>

    <div class="empresa-info-card">
        <div class="empresa-info-row">
            <span class="empresa-info-label">Nombre</span>
            <span class="empresa-info-value"><?php echo e($empresa->nombre_empresa); ?></span>
        </div>
        <?php if($empresa->razon_social): ?>
        <div class="empresa-info-row">
            <span class="empresa-info-label">Razón social</span>
            <span class="empresa-info-value"><?php echo e($empresa->razon_social); ?></span>
        </div>
        <?php endif; ?>
        <?php if($empresa->nif_cif): ?>
        <div class="empresa-info-row">
            <span class="empresa-info-label">NIF / CIF</span>
            <span class="empresa-info-value"><?php echo e($empresa->nif_cif); ?></span>
        </div>
        <?php endif; ?>
        <?php if($empresa->descripcion): ?>
        <div class="empresa-info-row">
            <span class="empresa-info-label">Descripción</span>
            <span class="empresa-info-value"><?php echo e($empresa->descripcion); ?></span>
        </div>
        <?php endif; ?>
        <?php if($empresa->sitio_web): ?>
        <div class="empresa-info-row">
            <span class="empresa-info-label">Sitio web</span>
            <span class="empresa-info-value">
                <a href="<?php echo e($empresa->sitio_web); ?>" target="_blank" style="color:var(--morado);text-decoration:underline;">
                    <?php echo e($empresa->sitio_web); ?>

                </a>
            </span>
        </div>
        <?php endif; ?>
        <?php if($empresa->telefono_contacto): ?>
        <div class="empresa-info-row">
            <span class="empresa-info-label">Teléfono</span>
            <span class="empresa-info-value"><?php echo e($empresa->telefono_contacto); ?></span>
        </div>
        <?php endif; ?>
        <?php if($empresa->direccion): ?>
        <div class="empresa-info-row">
            <span class="empresa-info-label">Dirección</span>
            <span class="empresa-info-value"><?php echo e($empresa->direccion); ?></span>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>


<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-10">
    <div class="seccion-empresa-titulo">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        Tus eventos
    </div>
    <p class="seccion-empresa-sub">
        <?php echo e($eventos->count()); ?> evento<?php echo e($eventos->count() !== 1 ? 's' : ''); ?> creado<?php echo e($eventos->count() !== 1 ? 's' : ''); ?> por tu empresa
    </p>

    <?php if($eventos->count() > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php $__currentLoopData = $eventos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <article class="evento-mini-card">
                    <a href="/eventos/<?php echo e($evento->id); ?>" style="text-decoration:none;color:inherit;">
                        <img src="<?php echo e($evento->url_portada); ?>"
                             alt="<?php echo e($evento->titulo); ?>"
                             class="evento-mini-img"
                             onerror="this.src='https://picsum.photos/seed/evento-<?php echo e($evento->id); ?>/600/400'">
                        <div class="evento-mini-body">
                            <h3 class="evento-mini-titulo"><?php echo e($evento->titulo); ?></h3>
                            <p class="evento-mini-meta">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <?php echo e(\Carbon\Carbon::parse($evento->fecha_inicio)->locale('es')->isoFormat('D MMM YYYY')); ?>

                            </p>
                            <?php if($evento->ubicacion_nombre): ?>
                            <p class="evento-mini-meta">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                <?php echo e($evento->ubicacion_nombre); ?>

                            </p>
                            <?php endif; ?>
                            <span style="display:inline-block;margin-top:0.4rem;font-size:0.75rem;font-weight:700;padding:0.25rem 0.65rem;border-radius:999px;background:#ede9fe;color:var(--morado);">
                                <?php echo e($evento->categoria?->nombre ?? 'Evento'); ?>

                            </span>
                        </div>
                    </a>
                    <div style="padding:0 1.15rem 1rem;">
                        <form action="<?php echo e(route('empresa.eventos.destroy', $evento->id)); ?>" method="POST"
                              onsubmit="return confirm('¿Seguro que quieres eliminar el evento «<?php echo e($evento->titulo); ?>»? Esta acción no se puede deshacer.')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn-eliminar-evento">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Eliminar
                            </button>
                        </form>
                    </div>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="empty-titulo">Aún no tienes eventos</p>
            <p class="empty-desc">Crea tu primer evento y llega a miles de jóvenes en VIBEZ.</p>
        </div>
    <?php endif; ?>
</section>


<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-10">
    <div class="seccion-empresa-titulo">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        Tu equipo
    </div>
    <p class="seccion-empresa-sub">
        <?php echo e($trabajadores->count()); ?> miembro<?php echo e($trabajadores->count() !== 1 ? 's' : ''); ?> en tu empresa
    </p>

    <?php if($trabajadores->count() > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php $__currentLoopData = $trabajadores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $usr = $trab->usuario; ?>
                <?php if($usr): ?>
                <div class="trabajador-card">
                    <div class="trabajador-avatar">
                        <?php if($usr->foto_url): ?>
                            <img src="<?php echo e($usr->foto_url); ?>" alt="<?php echo e($usr->nombre); ?>">
                        <?php else: ?>
                            <?php echo e(strtoupper(substr($usr->nombre, 0, 1))); ?><?php echo e(strtoupper(substr($usr->apellido1 ?? '', 0, 1))); ?>

                        <?php endif; ?>
                    </div>
                    <div>
                        <p class="trabajador-nombre"><?php echo e($usr->nombre); ?> <?php echo e($usr->apellido1); ?></p>
                        <p class="trabajador-email"><?php echo e($usr->email); ?></p>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="empty-titulo">Sin miembros en tu equipo</p>
            <p class="empty-desc">Los organizadores asignados a tu empresa aparecerán aquí.</p>
        </div>
    <?php endif; ?>
</section>


<section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
    <div class="seccion-empresa-titulo">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
        Acciones rápidas
    </div>
    <p class="seccion-empresa-sub">Gestiona tu empresa desde aquí</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <a href="<?php echo e(route('empresa.eventos.create')); ?>" class="accion-card">
            <div class="accion-icono">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <h3 class="accion-titulo">Crear evento</h3>
            <p class="accion-desc">Publica un nuevo evento y llega a tu público objetivo.</p>
        </a>

        <a href="<?php echo e(route('empresa.ofertas.create')); ?>" class="accion-card">
            <div class="accion-icono" style="background:linear-gradient(135deg,#059669,#10b981);">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="accion-titulo">Publicar oferta de trabajo</h3>
            <p class="accion-desc">Añade un nuevo puesto a la bolsa de trabajo de VIBEZ.</p>
        </a>

        <?php if($empresa): ?>
        <a href="<?php echo e(route('empresa.candidaturas.ofertas')); ?>" class="accion-card">
            <div class="accion-icono">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="accion-titulo">Revisar Currículums</h3>
            <p class="accion-desc">Ver todos los candidatos postulados a tus ofertas de trabajo.</p>
        </a>
        <?php endif; ?>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/empresa/home.blade.php ENDPATH**/ ?>
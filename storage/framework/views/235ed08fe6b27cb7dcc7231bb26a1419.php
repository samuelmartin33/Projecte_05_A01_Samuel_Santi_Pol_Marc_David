<?php $__env->startSection('titulo', 'Candidatos — ' . $oferta->titulo); ?>

<?php $__env->startPush('estilos'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/empresa-candidaturas-detalle.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('contenido'); ?>


<section class="cand-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        
        <a href="<?php echo e(route('empresa.candidaturas.ofertas')); ?>"
           class="inline-flex items-center gap-1.5 text-slate-400 hover:text-white text-sm font-medium transition-colors mb-5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Mis ofertas
        </a>

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold mb-3"
                     style="background:rgba(139,92,246,0.15);border:1px solid rgba(139,92,246,0.3);color:#c084fc;letter-spacing:.06em;text-transform:uppercase">
                    <?php echo e($oferta->categoria?->nombre ?? 'Oferta de trabajo'); ?>

                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white leading-tight">
                    <?php echo e($oferta->titulo); ?>

                </h1>
                <div class="flex flex-wrap gap-4 mt-2 text-sm text-slate-400">
                    <?php if($oferta->ubicacion): ?>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <?php echo e($oferta->ubicacion); ?>

                        </span>
                    <?php endif; ?>
                    <span class="text-green-400 font-semibold"><?php echo e($oferta->salario_formateado); ?></span>
                    <span id="oferta-estado-badge" class="<?php echo e($oferta->estado ? 'text-green-400' : 'text-slate-500'); ?> font-semibold">
                        <?php echo e($oferta->estado ? '● Activa' : '○ Cerrada'); ?>

                    </span>
                </div>
            </div>

            
            <div class="flex flex-col items-end gap-3">
                
                <div style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);border-radius:.875rem;padding:1rem 1.5rem;text-align:center;min-width:130px">
                    <p class="text-3xl font-black text-purple-300"><?php echo e($candidaturas->total()); ?></p>
                    <p class="text-slate-400 text-xs mt-0.5">candidatura<?php echo e($candidaturas->total() !== 1 ? 's' : ''); ?></p>
                </div>
                
                <button id="btn-cerrar-oferta"
                        onclick="toggleOferta()"
                        data-estado="<?php echo e($oferta->estado); ?>"
                        data-url="<?php echo e(route('empresa.candidaturas.cerrar-oferta', $oferta->id)); ?>"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold transition-all
                               <?php echo e($oferta->estado
                                    ? 'bg-red-500/20 text-red-300 border border-red-500/30 hover:bg-red-500/30'
                                    : 'bg-green-500/20 text-green-300 border border-green-500/30 hover:bg-green-500/30'); ?>">
                    <?php if($oferta->estado): ?>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cerrar oferta
                    <?php else: ?>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M5 13l4 4L19 7"/>
                        </svg>
                        Reabrir oferta
                    <?php endif; ?>
                </button>
            </div>
        </div>
    </div>
</section>


<?php
    $baseUrl   = route('empresa.candidaturas.detalle', $oferta->id);
    $ordenAct  = request('orden', 'reciente');
    $estadoAct = request('estado', '');
    $estados   = [
        ''  => ['label' => 'Todos',           'color' => ''],
        '1' => ['label' => 'Nuevos',          'color' => 'text-blue-600'],
        '2' => ['label' => 'Revisados',       'color' => 'text-amber-600'],
        '3' => ['label' => 'Preseleccionados','color' => 'text-green-600'],
        '4' => ['label' => 'Rechazados',      'color' => 'text-red-500'],
    ];
?>
<div class="sticky top-16 z-30 bg-white border-b border-navy/8 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <div class="flex flex-wrap items-center gap-2">

            <?php $__currentLoopData = $estados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button type="button"
                        data-estado="<?php echo e($val); ?>"
                        onclick="cargarCandidaturas('<?php echo e($val); ?>', _ordenActual)"
                        class="tab-estado <?php echo e($estadoAct == $val ? 'activo' : ''); ?>">
                    <?php echo e($info['label']); ?>

                </button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <div class="ml-auto flex items-center gap-2">
                <label class="text-navy/40 text-xs font-semibold uppercase tracking-wider">Ordenar:</label>
                <select class="filtro-select text-xs border border-navy/10 rounded-lg px-2 py-1.5 outline-none"
                        onchange="cargarCandidaturas(_estadoActual, this.value)">
                    <option value="reciente" <?php echo e($ordenAct === 'reciente' ? 'selected':''); ?>>Más reciente</option>
                    <option value="nombre"   <?php echo e($ordenAct === 'nombre'   ? 'selected':''); ?>>Nombre A–Z</option>
                    <option value="estado"   <?php echo e($ordenAct === 'estado'   ? 'selected':''); ?>>Por estado</option>
                </select>
            </div>

        </div>
    </div>
</div>


<?php
$candidaturasJson = $candidaturas->map(function($c) {
    return [
        'id'            => $c->id,
        'nombre'        => $c->nombreCompleto(),
        'email'         => $c->email_candidato ?? '',
        'telefono'      => $c->telefono_candidato ?? '',
        'ciudad'        => $c->ciudad_candidato ?? '',
        'linkedin'      => $c->linkedin_candidato ?? '',
        'perfil'        => $c->perfil_profesional ?? '',
        'habilidades'   => $c->habilidades ?? '',
        'idiomas'       => $c->idiomas ?? '',
        'carta'         => $c->carta_presentacion ?? '',
        'tiene_archivo' => $c->tieneArchivo(),
        'descargar_url' => route('empresa.candidaturas.descargar', $c->id),
        'fecha'         => \Carbon\Carbon::parse($c->fecha_creacion)->format('d/m/Y H:i'),
    ];
});
?>
<main id="candidaturas-lista" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
<script type="application/json" id="candidaturas-json"><?php echo json_encode($candidaturasJson, 15, 512) ?></script>

    <?php if($candidaturas->isEmpty()): ?>
        <div class="text-center py-20">
            <div class="w-16 h-16 bg-navy/5 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-navy/25" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h3 class="text-navy/50 font-bold text-lg">Sin candidaturas
                <?php if(request('estado')): ?> en este estado <?php endif; ?>
            </h3>
            <p class="text-navy/30 text-sm mt-1">Cuando alguien se postule aparecerá aquí.</p>
            <?php if(request('estado')): ?>
                <a href="<?php echo e(route('empresa.candidaturas.detalle', $oferta->id)); ?>"
                   class="mt-4 inline-block text-purple-600 text-sm font-semibold hover:underline">
                    Ver todas las candidaturas
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>

        <div class="bg-white rounded-2xl border border-navy/8 overflow-hidden shadow-sm">

            
            <div class="grid grid-cols-[2.5rem_1fr_auto] gap-4 px-5 py-3 bg-navy/3 border-b border-navy/8">
                <div></div>
                <p class="text-xs font-700 text-navy/40 uppercase tracking-wider font-bold">Candidato</p>
                <p class="text-xs font-700 text-navy/40 uppercase tracking-wider font-bold">Acciones</p>
            </div>

            <?php $__currentLoopData = $candidaturas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="cand-row" id="row-<?php echo e($cand->id); ?>">

                
                <div class="cand-avatar"><?php echo e($cand->iniciales()); ?></div>

                
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-0.5">
                        <span class="font-bold text-navy text-sm"><?php echo e($cand->nombreCompleto()); ?></span>

                        
                        <span id="badge-<?php echo e($cand->id); ?>" class="estado-badge <?php echo e($cand->estadoClases()); ?>">
                            <?php echo e($cand->estadoLabel()); ?>

                        </span>

                        <?php if($cand->tieneArchivo()): ?>
                            <span class="inline-flex items-center gap-1 text-xs text-green-600 font-semibold">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                                PDF adjunto
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="flex flex-wrap gap-x-3 gap-y-0.5 text-xs text-navy/45">
                        <?php if($cand->email_candidato): ?>
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <?php echo e($cand->email_candidato); ?>

                            </span>
                        <?php endif; ?>
                        <?php if($cand->telefono_candidato): ?>
                            <span><?php echo e($cand->telefono_candidato); ?></span>
                        <?php endif; ?>
                        <?php if($cand->ciudad_candidato): ?>
                            <span><?php echo e($cand->ciudad_candidato); ?></span>
                        <?php endif; ?>
                        <span class="text-navy/30">
                            <?php echo e(\Carbon\Carbon::parse($cand->fecha_creacion)->diffForHumans()); ?>

                        </span>
                    </div>
                </div>

                
                <div class="flex items-center gap-2 flex-shrink-0">

                    
                    <select class="estado-select estado-<?php echo e($cand->estado_candidatura); ?>"
                            id="sel-<?php echo e($cand->id); ?>"
                            onchange="cambiarEstado(<?php echo e($cand->id); ?>, this.value, this)"
                            title="Cambiar estado">
                        <option value="1" <?php echo e($cand->estado_candidatura == 1 ? 'selected':''); ?>>Nuevo</option>
                        <option value="2" <?php echo e($cand->estado_candidatura == 2 ? 'selected':''); ?>>Revisado</option>
                        <option value="3" <?php echo e($cand->estado_candidatura == 3 ? 'selected':''); ?>>Preseleccionado</option>
                        <option value="4" <?php echo e($cand->estado_candidatura == 4 ? 'selected':''); ?>>Rechazado</option>
                    </select>

                    
                    <button onclick="verCv(<?php echo e($cand->id); ?>)"
                            title="Ver CV completo"
                            class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 hover:bg-purple-100 flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>

                    
                    <?php if($cand->tieneArchivo()): ?>
                        <a href="<?php echo e(route('empresa.candidaturas.descargar', $cand->id)); ?>"
                           title="Descargar CV"
                           class="w-8 h-8 rounded-lg bg-green-50 text-green-600 hover:bg-green-100 flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <?php if($candidaturas->hasPages()): ?>
            <div class="mt-8 flex justify-center">
                <?php echo e($candidaturas->links()); ?>

            </div>
        <?php endif; ?>

    <?php endif; ?>
</main>


<div id="cv-overlay" class="cv-modal-overlay" onclick="cerrarCvModal(event)">
    <div class="cv-modal" id="cv-modal-box">
        
        <div class="flex items-center justify-between px-5 py-4 border-b border-navy/8 flex-shrink-0">
            <div>
                <h3 class="font-black text-navy text-lg" id="cv-modal-nombre">CV Candidato</h3>
                <p class="text-navy/40 text-xs" id="cv-modal-sub"></p>
            </div>
            <button onclick="cerrarCvModalBtn()" class="text-navy/30 hover:text-navy transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        
        <div id="cv-spinner" class="cv-modal-body flex items-center justify-center py-16">
            <div class="w-8 h-8 border-4 border-purple-200 border-t-purple-600 rounded-full animate-spin"></div>
        </div>

        
        <div id="cv-content" class="cv-modal-body hidden"></div>

        
        <div class="flex gap-2 px-5 py-3 border-t border-navy/8 flex-shrink-0">
            <a id="cv-download-btn" href="#" class="hidden btn-comprar text-sm px-4 py-2 rounded-lg font-semibold">
                Descargar PDF
            </a>
            <button onclick="cerrarCvModalBtn()" class="ml-auto text-sm px-4 py-2 text-navy/40 hover:text-navy transition-colors font-medium">
                Cerrar
            </button>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
window.candidaturasPageData = {
    estadoAct: '<?php echo e($estadoAct); ?>',
    ordenAct:  '<?php echo e($ordenAct); ?>',
    baseUrl:   '<?php echo e($baseUrl); ?>',
    estadoUrl: '<?php echo e(rtrim(route('empresa.candidaturas.ofertas'), '/')); ?>'
};
</script>
<script src="<?php echo e(asset('js/empresa-candidaturas-detalle.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/empresa/candidaturas/detalle.blade.php ENDPATH**/ ?>
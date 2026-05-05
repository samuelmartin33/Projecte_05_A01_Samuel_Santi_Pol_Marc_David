<?php $__env->startSection('titulo', 'Candidatos — ' . $oferta->titulo); ?>

<?php $__env->startPush('estilos'); ?>
<style>
    .cand-hero { background: linear-gradient(135deg,#0f0f23 0%,#1a1040 60%,#0f0f23 100%);
                 border-bottom: 1px solid rgba(139,92,246,0.2); }

    /* Tabla / lista de candidatos */
    .cand-row {
        display: grid;
        grid-template-columns: 2.5rem 1fr auto;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid rgba(26,26,46,0.06);
        transition: background .15s;
    }
    .cand-row:hover { background: #fafafa; }
    .cand-avatar {
        width: 2.5rem; height: 2.5rem;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 0.8rem; color: #fff;
        flex-shrink: 0;
        background: linear-gradient(135deg,#7c3aed,#a855f7);
    }

    /* Estado badge */
    .estado-badge {
        display: inline-flex; align-items: center; gap: 0.3rem;
        font-size: 0.7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.06em;
        padding: 0.2rem 0.65rem; border-radius: 999px;
    }

    /* Dropdown estado inline */
    .estado-select {
        font-size: 0.72rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.05em;
        border: none; border-radius: 999px;
        padding: 0.2rem 0.5rem; cursor: pointer;
        outline: none; appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath fill='%236b7280' d='M0 0l5 6 5-6z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.4rem center;
        padding-right: 1.4rem;
    }
    .estado-1 { background:#dbeafe; color:#1d4ed8; }
    .estado-2 { background:#fef3c7; color:#b45309; }
    .estado-3 { background:#dcfce7; color:#15803d; }
    .estado-4 { background:#fee2e2; color:#b91c1c; }

    /* Filtros */
    .tab-estado {
        padding: 0.4rem 1rem; border-radius: 0.6rem;
        font-size: 0.8rem; font-weight: 600;
        border: 1.5px solid rgba(26,26,46,0.1);
        background: #fff; color: #64748b;
        cursor: pointer; transition: all .15s; white-space: nowrap;
    }
    .tab-estado:hover  { border-color: #8b5cf6; color: #7c3aed; }
    .tab-estado.activo { border-color: #8b5cf6; background: #ede9fe; color: #6d28d9; font-weight: 700; }

    /* Modal CV */
    .cv-modal-overlay {
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.55);
        backdrop-filter: blur(4px);
        z-index: 200; display: none;
        align-items: center; justify-content: center;
        padding: 1rem;
    }
    .cv-modal-overlay.abierto { display: flex; }
    .cv-modal {
        background: #fff; border-radius: 1.25rem;
        box-shadow: 0 25px 60px rgba(0,0,0,0.2);
        width: 100%; max-width: 680px;
        max-height: 90vh;
        display: flex; flex-direction: column;
        animation: cvModalIn .2s ease;
    }
    @keyframes cvModalIn {
        from { opacity:0; transform:translateY(14px) scale(0.98); }
        to   { opacity:1; transform:translateY(0) scale(1); }
    }
    .cv-modal-body { overflow-y: auto; flex: 1; }
    .cv-section { padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(26,26,46,0.07); }
    .cv-section:last-child { border-bottom: none; }
    .cv-section-title {
        font-size: 0.7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.08em;
        color: rgba(26,26,46,0.4);
        margin-bottom: 0.5rem;
    }
    .cv-section-body {
        font-size: 0.9rem; color: #1a1a2e;
        line-height: 1.65;
        white-space: pre-wrap;
    }
    .chip {
        display: inline-block;
        background: #f1f5f9; color: #475569;
        border-radius: 0.5rem;
        padding: 0.2rem 0.6rem;
        font-size: 0.75rem; font-weight: 600;
        margin: 0.2rem 0.15rem;
    }
</style>
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
                    <span class="<?php echo e($oferta->estado ? 'text-green-400' : 'text-slate-500'); ?> font-semibold">
                        <?php echo e($oferta->estado ? '● Activa' : '○ Cerrada'); ?>

                    </span>
                </div>
            </div>

            
            <div style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);border-radius:.875rem;padding:1rem 1.5rem;text-align:center;min-width:130px">
                <p class="text-3xl font-black text-purple-300"><?php echo e($candidaturas->total()); ?></p>
                <p class="text-slate-400 text-xs mt-0.5">candidatura<?php echo e($candidaturas->total() !== 1 ? 's' : ''); ?></p>
            </div>
        </div>
    </div>
</section>


<div class="sticky top-16 z-30 bg-white border-b border-navy/8 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <form method="GET" action="<?php echo e(route('empresa.candidaturas.detalle', $oferta->id)); ?>"
              class="flex flex-wrap items-center gap-2">

            <?php
                $estados = [
                    ''  => ['label' => 'Todos',          'color' => ''],
                    '1' => ['label' => 'Nuevos',         'color' => 'text-blue-600'],
                    '2' => ['label' => 'Revisados',      'color' => 'text-amber-600'],
                    '3' => ['label' => 'Preseleccionados','color' => 'text-green-600'],
                    '4' => ['label' => 'Rechazados',     'color' => 'text-red-500'],
                ];
            ?>

            <?php $__currentLoopData = $estados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $count = $val === '' ? $candidaturas->total() : ($conteos[$val] ?? 0); ?>
                <button type="submit" name="estado" value="<?php echo e($val); ?>"
                        class="tab-estado <?php echo e(request('estado', '') == $val ? 'activo' : ''); ?>">
                    <?php echo e($info['label']); ?>

                    <?php if($count > 0): ?>
                        <span class="ml-1 text-xs font-black <?php echo e($info['color']); ?>"><?php echo e($count); ?></span>
                    <?php endif; ?>
                </button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <div class="ml-auto flex items-center gap-2">
                <label class="text-navy/40 text-xs font-semibold uppercase tracking-wider">Ordenar:</label>
                <select name="orden" class="filtro-select text-xs border border-navy/10 rounded-lg px-2 py-1.5 outline-none"
                        onchange="this.form.submit()">
                    <option value="reciente" <?php echo e(request('orden','reciente') === 'reciente' ? 'selected':''); ?>>Más reciente</option>
                    <option value="nombre"   <?php echo e(request('orden') === 'nombre'   ? 'selected':''); ?>>Nombre A–Z</option>
                    <option value="estado"   <?php echo e(request('orden') === 'estado'   ? 'selected':''); ?>>Por estado</option>
                </select>
            </div>

            <?php if(request('estado') !== null): ?>
                <input type="hidden" name="estado" value="<?php echo e(request('estado')); ?>">
            <?php endif; ?>
        </form>
    </div>
</div>


<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

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

                    
                    <?php if($cand->email_candidato): ?>
                        <a href="mailto:<?php echo e($cand->email_candidato); ?>?subject=Tu candidatura para <?php echo e(urlencode($oferta->titulo)); ?>"
                           title="Contactar por email"
                           class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
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
            <a id="cv-email-btn" href="#" class="text-sm px-4 py-2 rounded-lg border border-navy/15 text-navy/60 hover:bg-navy/5 font-semibold transition-colors">
                Contactar
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
// ── Datos de candidaturas (para modal CV) ────────────────────────────────────
const candidaturasData = <?php echo json_encode($candidaturas->map(function($c) {
    return [
        'id'               => $c->id, 'nombre'           => $c->nombreCompleto(), 'email'            => $c->email_candidato ?? '') ?>;

const estadoUrl = "<?php echo e(rtrim(route('empresa.candidaturas.ofertas'), '/')); ?>";
const csrf = document.querySelector('meta[name="csrf-token"]').content;

// ── Cambiar estado via AJAX ──────────────────────────────────────────────────
async function cambiarEstado(id, estado, selectEl) {
    const badge = document.getElementById('badge-' + id);
    const url   = '/empresa/candidaturas/' + id + '/estado';

    try {
        const res  = await fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
            body: JSON.stringify({ estado }),
        });
        const data = await res.json();
        if (data.success) {
            // Update badge
            badge.textContent = data.label;
            badge.className   = 'estado-badge ' + data.clases;
            // Update select color
            selectEl.className = 'estado-select estado-' + estado;
        }
    } catch(e) {
        console.error('Error al actualizar estado', e);
    }
}

// ── Modal CV ─────────────────────────────────────────────────────────────────
function verCv(id) {
    const cand = candidaturasData.find(c => c.id === id);
    if (!cand) return;

    document.getElementById('cv-overlay').classList.add('abierto');
    document.getElementById('cv-spinner').classList.remove('hidden');
    document.getElementById('cv-content').classList.add('hidden');
    document.getElementById('cv-modal-nombre').textContent = cand.nombre;
    document.getElementById('cv-modal-sub').textContent    = cand.fecha
        ? 'Postulado el ' + cand.fecha
        : '';

    const downloadBtn = document.getElementById('cv-download-btn');
    if (cand.tiene_archivo) {
        downloadBtn.href = cand.descargar_url;
        downloadBtn.classList.remove('hidden');
    } else {
        downloadBtn.classList.add('hidden');
    }

    const emailBtn = document.getElementById('cv-email-btn');
    emailBtn.href = cand.email
        ? 'mailto:' + cand.email + '?subject=Tu candidatura'
        : '#';

    // Render content
    setTimeout(function() {
        document.getElementById('cv-spinner').classList.add('hidden');
        const content = document.getElementById('cv-content');
        content.innerHTML = buildCvHtml(cand);
        content.classList.remove('hidden');
    }, 250);
}

function buildCvHtml(cand) {
    let html = '';

    // Personal info
    html += section('Información Personal', `
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem .75rem">
            ${field('Email',    cand.email,    'text')}
            ${field('Teléfono', cand.telefono, 'text')}
            ${field('Ciudad',   cand.ciudad,   'text')}
            ${cand.linkedin ? field('LinkedIn', '<a href="' + cand.linkedin + '" target="_blank" class="text-purple-600 hover:underline">' + cand.linkedin + '</a>', 'html') : ''}
        </div>
    `);

    if (cand.perfil) {
        html += section('Perfil Profesional', `<p class="cv-section-body">${esc(cand.perfil)}</p>`);
    }

    if (cand.carta) {
        html += section('Carta de Presentación', `<p class="cv-section-body">${esc(cand.carta)}</p>`);
    }

    if (cand.habilidades) {
        const chips = cand.habilidades.split(',').map(h => `<span class="chip">${esc(h.trim())}</span>`).join('');
        html += section('Habilidades', `<div>${chips}</div>`);
    }

    if (cand.idiomas) {
        const chips = cand.idiomas.split(',').map(i => `<span class="chip">${esc(i.trim())}</span>`).join('');
        html += section('Idiomas', `<div>${chips}</div>`);
    }

    if (!cand.perfil && !cand.carta && !cand.habilidades && !cand.idiomas && !cand.email) {
        html += `<div class="cv-section"><p class="text-navy/40 text-sm text-center py-6">Este candidato subió su CV como archivo adjunto.</p></div>`;
    }

    return html;
}

function section(title, body) {
    return `<div class="cv-section"><p class="cv-section-title">${title}</p>${body}</div>`;
}

function field(label, value, type) {
    if (!value) return '';
    const val = type === 'html' ? value : `<span>${esc(value)}</span>`;
    return `<div><p style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:rgba(26,26,46,.4);margin-bottom:.2rem">${label}</p>${val}</div>`;
}

function esc(str) {
    return String(str)
        .replace(/&/g,'&amp;')
        .replace(/</g,'&lt;')
        .replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;');
}

function cerrarCvModal(e) {
    if (e.target === document.getElementById('cv-overlay')) {
        document.getElementById('cv-overlay').classList.remove('abierto');
    }
}
function cerrarCvModalBtn() {
    document.getElementById('cv-overlay').classList.remove('abierto');
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views\empresa\candidaturas\detalle.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Social — VIBEZ'); ?>


<?php $__env->startSection('content'); ?>


<link rel="stylesheet" href="<?php echo e(asset('css/vibez-home.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('css/social.css')); ?>">


<style>
  .soc                                { height: calc(100vh - 94px); }
  @media (min-width: 900px)           { .soc { height: calc(100vh - 94px); } }
</style>


<?php echo $__env->make('partials.home.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<div class="soc" id="soc">

    
    <div class="soc-panel activo" id="panel-feed">

        
        <button id="btn-nueva-pub" style="display:none" onclick="abrirModalPublicacion()"></button>

        <div class="soc-scroll" id="feed-scroll">

            
            <div class="soc-hero-mini">
                <p class="soc-hero-kicker">VIBEZ Tribe · tu comunidad</p>
                <h1 class="soc-hero-titulo">Tu <em>tribu</em><br>en vivo.</h1>
                <p class="soc-hero-sub">Quién va a dónde, qué se está liando, cuál es el plan. La nightlife se vive mejor en grupo.</p>
            </div>

            
            <div class="soc-stories-row no-scrollbar">
                <?php $u = Auth::user(); ?>
                <div class="soc-story" onclick="abrirModalPublicacion()" title="Publicar">
                    <div class="soc-story-ring soc-story-ring--you">
                        <div class="soc-story-av-you">+</div>
                    </div>
                    <div class="soc-story-name">Tu story</div>
                </div>
                <?php $__currentLoopData = [['AM','@amigo1'],['XR','@xavi.r'],['LG','@laura.g'],['PB','@pablo_b'],['NF','@nuria_f'],['DR','@dani_r']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$ini,$handle]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="soc-story">
                    <div class="soc-story-ring">
                        <div class="soc-story-av"><?php echo e($ini); ?></div>
                    </div>
                    <div class="soc-story-name"><?php echo e($handle); ?></div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <div class="soc-feed-layout">

                
                <div class="soc-feed-col">
                    <div id="feed-lista"></div>

                    <div id="feed-cargando" class="soc-spinner-wrap">
                        <div class="soc-spinner"></div>
                    </div>

                    <p class="soc-vacio" id="feed-vacio" style="display:none">
                        Aún no hay publicaciones de los eventos a los que has asistido.<br>¡Sé el primero!
                    </p>

                    <p class="soc-cargar-mas" id="feed-cargar-mas" style="display:none"
                       onclick="cargarMasPosts()">Cargar más publicaciones</p>
                </div>

                
                <aside class="soc-sidebar-right">

                    
                    <div class="soc-side-card">
                        <h3 class="soc-side-title">Crews <em>activos</em></h3>
                        <div style="display:flex;flex-direction:column;gap:12px">
                            <div class="soc-crew">
                                <div class="soc-crew-avatars">
                                    <span>SM</span>
                                    <span style="background:linear-gradient(135deg,#ec4899,#a855f7)">LB</span>
                                    <span style="background:linear-gradient(135deg,#3b82f6,#8b5cf6)">DR</span>
                                    <span>+5</span>
                                </div>
                                <div>
                                    <h4 class="soc-crew-name">Los del jueves</h4>
                                    <p class="soc-crew-meta">8 miembros · va a Razzmatazz hoy</p>
                                </div>
                            </div>
                            <div class="soc-crew">
                                <div class="soc-crew-avatars">
                                    <span style="background:linear-gradient(135deg,#f59e0b,#ef4444)">MB</span>
                                    <span style="background:linear-gradient(135deg,#22d3ee,#a855f7)">OR</span>
                                    <span>+3</span>
                                </div>
                                <div>
                                    <h4 class="soc-crew-name">Primavera Squad</h4>
                                    <p class="soc-crew-meta">5 miembros · planea Primavera Sound</p>
                                </div>
                            </div>
                            <div class="soc-crew">
                                <div class="soc-crew-avatars">
                                    <span style="background:linear-gradient(135deg,#10b981,#a855f7)">NN</span>
                                    <span style="background:linear-gradient(135deg,#a855f7,#ec4899)">XN</span>
                                </div>
                                <div>
                                    <h4 class="soc-crew-name">Bakalao FC</h4>
                                    <p class="soc-crew-meta">2 miembros · italo disco only</p>
                                </div>
                            </div>
                        </div>
                        <button class="soc-side-btn-ghost" style="margin-top:18px">+ Crear crew</button>
                    </div>

                    
                    <div class="soc-side-card">
                        <h3 class="soc-side-title">Trending <em>esta noche</em></h3>
                        <div>
                            <div class="soc-trend soc-trend--hot">
                                <div class="soc-trend-rank">01</div>
                                <div class="soc-trend-info">
                                    <div class="soc-trend-name">Amnesia × Felicidad</div>
                                    <div class="soc-trend-meta">🔥 432 personas hablando</div>
                                </div>
                            </div>
                            <div class="soc-trend soc-trend--hot">
                                <div class="soc-trend-rank">02</div>
                                <div class="soc-trend-info">
                                    <div class="soc-trend-name">Bad Bunny · Pacha</div>
                                    <div class="soc-trend-meta">🔥 287 hablando</div>
                                </div>
                            </div>
                            <div class="soc-trend">
                                <div class="soc-trend-rank">03</div>
                                <div class="soc-trend-info">
                                    <div class="soc-trend-name">Noche Ítalo Disco · Apolo</div>
                                    <div class="soc-trend-meta">194 hablando</div>
                                </div>
                            </div>
                            <div class="soc-trend">
                                <div class="soc-trend-rank">04</div>
                                <div class="soc-trend-info">
                                    <div class="soc-trend-name">Primavera Sound</div>
                                    <div class="soc-trend-meta">156 hablando</div>
                                </div>
                            </div>
                            <div class="soc-trend">
                                <div class="soc-trend-rank">05</div>
                                <div class="soc-trend-info">
                                    <div class="soc-trend-name">Jazz Casa Fuster</div>
                                    <div class="soc-trend-meta">82 hablando</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="soc-side-card">
                        <h3 class="soc-side-title">Sugeridos para <em>seguir</em></h3>
                        <div style="display:flex;flex-direction:column;gap:2px">
                            <div class="soc-sugerido">
                                <div class="avatar-sm avatar-iniciales" style="background:linear-gradient(135deg,#22d3ee,#a855f7)">ER</div>
                                <div class="soc-sugerido-info">
                                    <div class="soc-sugerido-nombre">@elraval</div>
                                    <div class="soc-sugerido-meta">12 amigos en común</div>
                                </div>
                                <button class="soc-side-btn-pri">Seguir</button>
                            </div>
                            <div class="soc-sugerido">
                                <div class="avatar-sm avatar-iniciales" style="background:linear-gradient(135deg,#7c3aed,#a855f7)">OD</div>
                                <div class="soc-sugerido-info">
                                    <div class="soc-sugerido-nombre">@oriol_dj</div>
                                    <div class="soc-sugerido-meta">DJ residente · Apolo</div>
                                </div>
                                <button class="soc-side-btn-pri">Seguir</button>
                            </div>
                            <div class="soc-sugerido">
                                <div class="avatar-sm avatar-iniciales" style="background:linear-gradient(135deg,#ec4899,#a855f7)">MB</div>
                                <div class="soc-sugerido-info">
                                    <div class="soc-sugerido-nombre">@martabcn</div>
                                    <div class="soc-sugerido-meta">Primavera Sound · día 2</div>
                                </div>
                                <button class="soc-side-btn-pri">Seguir</button>
                            </div>
                        </div>
                    </div>

                </aside>
            </div>

        </div>
    </div>

    
    <div class="soc-panel" id="panel-chats">

        <div class="chats-col-izq">

            <header class="soc-topbar">
                <h1 class="soc-topbar-titulo">Mensajes</h1>
            </header>

            <div class="soc-subpanel activo" id="chats-lista-view">
                <div id="lista-chats" class="soc-list">
                    <div id="skeleton-chats">
                        <div class="soc-skeleton"></div>
                        <div class="soc-skeleton"></div>
                        <div class="soc-skeleton"></div>
                    </div>
                </div>
            </div>

        </div>

        <div class="soc-subpanel" id="chats-ventana-view">

            <div class="soc-chat-empty" id="chat-vacio-desktop">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <p>Selecciona una conversación para empezar</p>
            </div>

            <div class="soc-chat-head" id="chat-cabecera">
                <button class="soc-btn-volver" onclick="cerrarChat()" title="Volver">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <div id="chat-amigo-info" class="chat-amigo-info"></div>
            </div>

            <div class="soc-chat-msgs" id="chat-mensajes">
                <div class="soc-spinner-wrap" id="cargando-mensajes" style="display:none">
                    <div class="soc-spinner"></div>
                </div>
            </div>

            <div class="soc-chat-input-barra">
                <textarea id="chat-textarea" class="soc-chat-textarea"
                          placeholder="Escribe un mensaje…" rows="1"
                          onkeydown="manejarTeclaEnvio(event)"
                          oninput="ajustarAlturaTextarea(this)"></textarea>
                <button class="soc-chat-btn-enviar" id="btn-enviar-mensaje"
                        onclick="enviarMensaje()" title="Enviar">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                    </svg>
                </button>
            </div>
        </div>

    </div>

    
    <div class="soc-panel" id="panel-amigos">

        <header class="soc-topbar">
            <h1 class="soc-topbar-titulo">Amigos</h1>
            <button class="soc-topbar-btn" onclick="abrirModalAnadirAmigo()" title="Añadir amigo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </button>
        </header>

        <div class="soc-scroll">

            <div id="seccion-solicitudes" style="display:none">
                <p class="soc-section-label">Solicitudes recibidas</p>
                <div id="lista-solicitudes"></div>
                <div class="soc-divider"></div>
            </div>

            <p class="soc-section-label">Mis amigos</p>
            <div id="lista-amigos" class="soc-list">
                <div id="skeleton-amigos">
                    <div class="soc-skeleton"></div>
                    <div class="soc-skeleton"></div>
                </div>
            </div>

        </div>
    </div>

    
    <nav class="soc-bottom-nav">

        <div class="soc-sidebar-header">
            <span class="soc-sidebar-titulo">VIBEZ <span>Social</span></span>
        </div>

        <button class="soc-nav-btn activo" onclick="irA('feed')" id="nav-btn-feed">
            <div class="soc-nav-icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                    <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                </svg>
            </div>
            <span class="soc-nav-label">Publicaciones</span>
        </button>

        <button class="soc-nav-btn" onclick="irA('chats')" id="nav-btn-chats">
            <div class="soc-nav-icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span class="soc-nav-badge" id="badge-mensajes" style="display:none">0</span>
            </div>
            <span class="soc-nav-label">Mensajes</span>
        </button>

        <button class="soc-nav-btn" onclick="irA('amigos')" id="nav-btn-amigos">
            <div class="soc-nav-icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="soc-nav-badge" id="badge-solicitudes" style="display:none">0</span>
            </div>
            <span class="soc-nav-label">Amigos</span>
        </button>

    </nav>

    
    <div class="soc-modal-overlay" id="pub-modal-overlay" style="display:none"
         onclick="cerrarModalPublicacion()">
        <div class="soc-modal" onclick="event.stopPropagation()">

            <div class="soc-modal-head">
                <h3 class="soc-modal-titulo">Nueva publicación</h3>
                <button class="soc-modal-cerrar" onclick="cerrarModalPublicacion()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="soc-modal-body">
                <label class="soc-field-label">Evento *</label>
                <select id="pub-select-evento" class="soc-select">
                    <option value="">Selecciona un evento…</option>
                </select>

                <label class="soc-field-label">Descripción (opcional)</label>
                <textarea id="pub-textarea-desc" class="soc-textarea"
                          placeholder="Cuenta cómo fue el evento…"
                          maxlength="1000" rows="3"></textarea>

                
                <label class="soc-field-label">¿Quién puede verlo?</label>
                <select id="pub-select-visibilidad" class="soc-select">
                    <option value="1">🌍 Todos</option>
                    <option value="2">🔒 Solo mis amigos</option>
                </select>

                <label class="soc-field-label">Fotos * (mínimo 1, máximo 10)</label>
                <div class="soc-upload-area"
                     onclick="document.getElementById('pub-input-fotos').click()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p>Haz clic o arrastra tus fotos aquí</p>
                </div>
                <input type="file" id="pub-input-fotos" accept="image/*" multiple style="display:none"
                       onchange="previsualizarFotos(this)">
                <div class="soc-preview-grid" id="pub-preview-grid"></div>
            </div>

            <div class="soc-modal-foot">
                <button class="soc-btn-secondary" onclick="cerrarModalPublicacion()">Cancelar</button>
                <button class="soc-btn-primary" id="pub-btn-publicar" onclick="publicarPost()">
                    Publicar
                </button>
            </div>

        </div>
    </div>

    
    <div class="soc-modal-overlay" id="modal-amigo-overlay" style="display:none"
         onclick="cerrarModalAnadirAmigo()">
        <div class="soc-modal" onclick="event.stopPropagation()">

            <div class="soc-modal-head">
                <h3 class="soc-modal-titulo">Añadir amigo</h3>
                <button class="soc-modal-cerrar" onclick="cerrarModalAnadirAmigo()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="soc-modal-body">
                <div class="soc-search-wrap">
                    <svg class="soc-search-icono" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
                    </svg>
                    <input type="text" id="input-anadir-amigo" class="soc-search-input"
                           placeholder="Buscar por nombre o email…"
                           oninput="buscarParaAnadir(this.value)"
                           autocomplete="off">
                </div>
                <div id="resultados-anadir-amigo" class="soc-list">
                    <p class="soc-vacio-small">Escribe al menos 2 caracteres para buscar</p>
                </div>
            </div>

        </div>
    </div>

</div>


<script>
    window.miUsuarioId = <?php echo e(Auth::id()); ?>;
</script>
<script src="<?php echo e(asset('js/social.js')); ?>"></script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/social/index.blade.php ENDPATH**/ ?>
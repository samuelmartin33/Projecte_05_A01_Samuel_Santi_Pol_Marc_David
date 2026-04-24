<?php $__env->startSection('title', 'Social'); ?>

<?php $__env->startSection('contenido'); ?>


<div class="social-pagina" id="social-pagina">

    
    <aside class="social-sidebar" id="social-sidebar">

        
        <div class="social-sidebar-header">
            <h1 class="social-titulo-panel">Social</h1>
        </div>

        
        <div class="social-tabs-nav">
            <button class="social-tab-btn activo" onclick="cambiarTab('mensajes')" id="tab-btn-mensajes">
                Mensajes
                <span class="social-tab-badge" id="badge-mensajes" style="display:none">0</span>
            </button>
            <button class="social-tab-btn" onclick="cambiarTab('amigos')" id="tab-btn-amigos">
                Amigos
                <span class="social-tab-badge" id="badge-solicitudes" style="display:none">0</span>
            </button>
            <button class="social-tab-btn" onclick="cambiarTab('descubrir')" id="tab-btn-descubrir">
                Descubrir
            </button>
        </div>

        
        <div class="social-tab-panel activo" id="tab-mensajes">
            <div id="lista-chats" class="social-lista">
                
                <div class="social-cargando" id="skeleton-chats">
                    <div class="social-skeleton-item"></div>
                    <div class="social-skeleton-item"></div>
                    <div class="social-skeleton-item"></div>
                </div>
            </div>
        </div>

        
        <div class="social-tab-panel" id="tab-amigos">
            
            <div id="seccion-solicitudes" style="display:none">
                <p class="social-seccion-titulo">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    Solicitudes recibidas
                </p>
                <div id="lista-solicitudes"></div>
                <div class="social-divisor"></div>
            </div>

            
            <p class="social-seccion-titulo" id="titulo-amigos">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Mis amigos
            </p>
            <div id="lista-amigos" class="social-lista">
                <div class="social-cargando" id="skeleton-amigos">
                    <div class="social-skeleton-item"></div>
                    <div class="social-skeleton-item"></div>
                </div>
            </div>
        </div>

        
        <div class="social-tab-panel" id="tab-descubrir">
            <div class="social-buscador-wrap">
                <svg class="social-buscador-icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
                </svg>
                <input type="text"
                       id="input-descubrir"
                       class="social-buscador-input"
                       placeholder="Buscar personas por nombre o email…"
                       oninput="buscarPersonas(this.value)"
                       autocomplete="off">
            </div>
            <div id="resultados-descubrir" class="social-lista">
                <p class="social-vacio-texto" style="padding:20px 16px">
                    Escribe al menos 2 caracteres para buscar
                </p>
            </div>
        </div>

    </aside>

    
    <section class="social-chat-area" id="area-chat">

        
        <div class="social-chat-vacio" id="chat-vacio">
            <div class="social-chat-vacio-contenido">
                <div class="social-chat-vacio-icono">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h2 class="social-chat-vacio-titulo">Tus mensajes</h2>
                <p class="social-chat-vacio-sub">Selecciona una conversación o empieza una nueva desde tu lista de amigos</p>
            </div>
        </div>

        
        <div class="social-chat-ventana" id="chat-ventana" style="display:none">

            
            <div class="social-chat-cabecera" id="chat-cabecera">
                
                <button class="chat-btn-volver" onclick="cerrarChat()" title="Volver">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                
                <div id="chat-amigo-info" class="chat-amigo-info"></div>
            </div>

            
            <div class="chat-mensajes-area" id="chat-mensajes">
                <div class="social-cargando-mensajes" id="cargando-mensajes">
                    <div class="spinner-chat"></div>
                </div>
            </div>

            
            <div class="chat-input-barra">
                <textarea id="chat-textarea"
                          class="chat-textarea"
                          placeholder="Escribe un mensaje…"
                          rows="1"
                          onkeydown="manejarTeclaEnvio(event)"
                          oninput="ajustarAlturaTextarea(this)"></textarea>
                <button class="chat-btn-enviar" id="btn-enviar-mensaje" onclick="enviarMensaje()" title="Enviar">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                    </svg>
                </button>
            </div>

        </div>

    </section>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('extra-css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/social.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    
    window.miUsuarioId = <?php echo e(Auth::id()); ?>;
</script>
<script src="<?php echo e(asset('js/social.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\LARAVEL\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/social/index.blade.php ENDPATH**/ ?>
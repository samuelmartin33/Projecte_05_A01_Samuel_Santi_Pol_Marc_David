@extends('layouts.app')

@section('title', 'Social')

@section('contenido')

<div class="soc" id="soc">

    {{-- ══════════════════════════════════════
         PANEL: Publicaciones (por defecto)
         ══════════════════════════════════════ --}}
    <div class="soc-panel activo" id="panel-feed">

        <header class="soc-topbar">
            <h1 class="soc-topbar-titulo">Social</h1>
            <button class="soc-topbar-btn" id="btn-nueva-pub" style="display:none"
                    onclick="abrirModalPublicacion()" title="Nueva publicación">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="18" height="18" rx="3"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v8m-4-4h8"/>
                </svg>
            </button>
        </header>

        <div class="soc-scroll" id="feed-scroll">
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

    </div>

    {{-- ══════════════════════════════════════
         PANEL: Mensajes
         ══════════════════════════════════════ --}}
    <div class="soc-panel" id="panel-chats">

        {{-- En desktop: columna izquierda (header + lista). En móvil: display:contents (transparente) --}}
        <div class="chats-col-izq">

            <header class="soc-topbar">
                <h1 class="soc-topbar-titulo">Mensajes</h1>
            </header>

            {{-- Sub-vista: lista de conversaciones --}}
            <div class="soc-subpanel activo" id="chats-lista-view">
                <div id="lista-chats" class="soc-list">
                    <div id="skeleton-chats">
                        <div class="soc-skeleton"></div>
                        <div class="soc-skeleton"></div>
                        <div class="soc-skeleton"></div>
                    </div>
                </div>
            </div>

        </div>{{-- /chats-col-izq --}}

        {{-- Sub-vista: chat abierto --}}
        <div class="soc-subpanel" id="chats-ventana-view">

            {{-- Estado vacío: visible en desktop cuando no hay chat seleccionado --}}
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

    {{-- ══════════════════════════════════════
         PANEL: Amigos
         ══════════════════════════════════════ --}}
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

            {{-- Solicitudes pendientes --}}
            <div id="seccion-solicitudes" style="display:none">
                <p class="soc-section-label">Solicitudes recibidas</p>
                <div id="lista-solicitudes"></div>
                <div class="soc-divider"></div>
            </div>

            {{-- Lista de amigos --}}
            <p class="soc-section-label">Mis amigos</p>
            <div id="lista-amigos" class="soc-list">
                <div id="skeleton-amigos">
                    <div class="soc-skeleton"></div>
                    <div class="soc-skeleton"></div>
                </div>
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════════
         BOTTOM NAVIGATION
         ══════════════════════════════════════ --}}
    <nav class="soc-bottom-nav">

        {{-- Cabecera del sidebar (solo visible en desktop) --}}
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

    {{-- ══════════════════════════════════════
         MODAL: Nueva publicación
         ══════════════════════════════════════ --}}
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

    {{-- ══════════════════════════════════════
         MODAL: Añadir amigo
         ══════════════════════════════════════ --}}
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

</div>{{-- /soc --}}

@endsection

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/social.css') }}">
@endsection

@section('scripts')
<script>
    window.miUsuarioId = {{ Auth::id() }};
</script>
<script src="{{ asset('js/social.js') }}"></script>
@endsection

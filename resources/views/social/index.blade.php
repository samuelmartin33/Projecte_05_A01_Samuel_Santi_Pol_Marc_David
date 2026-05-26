@extends('layouts.app')

@section('title', 'Social — VIBEZ')

{{-- Usamos @section('content') como la home para activar el modo oscuro completo --}}
@section('content')

{{-- ── Estilos base (dark theme) ── --}}
<link rel="stylesheet" href="{{ asset('css/vibez-home.css') }}">
<link rel="stylesheet" href="{{ asset('css/social.css') }}">

{{-- Altura del wrapper social = viewport menos la altura real del nav sticky --}}
{{-- Desktop: padding 18px*2 + logo 70px + border 1px ≈ 107px               --}}
{{-- Móvil (≤768px): padding 12px*2 + logo 54px + border 1px ≈ 79px         --}}
<style>
  .soc { height: calc(100vh - 107px); }
  @media (max-width: 768px) { .soc { height: calc(100vh - 79px); } }
</style>

{{-- ════ NAV ════ --}}
@include('partials.home.nav')

{{-- ════════════════════════════════════════════════════
     WRAPPER PRINCIPAL
════════════════════════════════════════════════════ --}}
<div class="soc" id="soc">

    {{-- ══════════════════════════════════════
         PANEL: Publicaciones (por defecto)
         ══════════════════════════════════════ --}}
    <div class="soc-panel activo" id="panel-feed">

        {{-- Topbar con botones de acción --}}
        <header class="soc-topbar">
            <h1 class="soc-topbar-titulo">VIBEZ</h1>
            {{-- Botones de acción: siempre visibles para usuarios autenticados --}}
            <div class="soc-topbar-acciones" id="topbar-acciones-feed" style="display:flex">

                {{-- Botón nueva historia --}}
                <button class="soc-topbar-btn" id="btn-nueva-historia"
                        onclick="abrirModalHistoria()" title="Nueva historia">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <circle cx="12" cy="13" r="3"/>
                    </svg>
                </button>

                {{-- Botón nuevo post --}}
                <button class="soc-topbar-btn" id="btn-nueva-pub"
                        onclick="abrirModalPublicacion()" title="Nueva publicación">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="3"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v8m-4-4h8"/>
                    </svg>
                </button>

            </div>
        </header>

        <div class="soc-scroll" id="feed-scroll">

            {{-- ── HERO ── --}}
            <div class="soc-hero-mini">
                <p class="soc-hero-kicker">VIBEZ Tribe · tu comunidad</p>
                <h1 class="soc-hero-titulo">Tu <em>tribu</em><br>en vivo.</h1>
                <p class="soc-hero-sub">Quién va a dónde, qué se está liando, cuál es el plan. La nightlife se vive mejor en grupo.</p>
            </div>

            {{-- ── BARRA DE HISTORIAS (renderizada por JS) ── --}}
            <div class="historias-barra" id="historias-barra">
                <div class="historia-circulo historia-circulo-skeleton">
                    <div class="historia-circulo-ring"></div>
                </div>
                <div class="historia-circulo historia-circulo-skeleton">
                    <div class="historia-circulo-ring"></div>
                </div>
                <div class="historia-circulo historia-circulo-skeleton">
                    <div class="historia-circulo-ring"></div>
                </div>
            </div>

            {{-- ── FEED + SIDEBAR ── --}}
            <div class="soc-feed-layout">

                {{-- COLUMNA FEED --}}
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

                {{-- SIDEBAR DERECHO (solo desktop) --}}
                <aside class="soc-sidebar-right">

                    {{-- Crews activos --}}
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

                    {{-- Trending esta noche --}}
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

                    {{-- Promotoras que sigues --}}
                    <div class="soc-side-card" id="soc-promotoras-card">
                        <h3 class="soc-side-title">Promotoras que <em>sigues</em></h3>
                        <div id="soc-promotoras-lista" style="display:flex;flex-direction:column;gap:10px;">
                            <p class="soc-vacio" style="font-size:0.8rem;padding:4px 0;">Cargando…</p>
                        </div>
                    </div>

                    {{-- Sugeridos para seguir --}}
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

    {{-- ══════════════════════════════════════
         PANEL: Mensajes
         ══════════════════════════════════════ --}}
    <div class="soc-panel" id="panel-chats">

        <div class="chats-col-izq">

            <header class="soc-topbar">
                <h1 class="soc-topbar-titulo">Mensajes</h1>
                <button class="soc-topbar-btn" onclick="abrirModalCrearCrew()" title="Crear crew">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </button>
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

    {{-- ══════════════════════════════════════
         PANEL: Por evento
         ══════════════════════════════════════ --}}
    <div class="soc-panel" id="panel-eventos">

        <header class="soc-topbar">
            <button class="soc-btn-volver" id="btn-volver-eventos" style="display:none"
                    onclick="volverListadoEventos()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <h1 class="soc-topbar-titulo" id="titulo-panel-eventos">Por evento</h1>
        </header>

        {{-- Sub-panel: listado de eventos con contenido --}}
        <div class="soc-subpanel activo" id="eventos-lista-view">
            <div class="soc-scroll">
                <p class="soc-section-label">Tus eventos con contenido</p>
                <div id="lista-eventos-contenido" class="soc-list">
                    <div id="skeleton-eventos">
                        <div class="soc-skeleton"></div>
                        <div class="soc-skeleton"></div>
                    </div>
                </div>
                <p class="soc-vacio" id="eventos-vacio" style="display:none">
                    Aún no hay publicaciones o historias en tus eventos.
                </p>
            </div>
        </div>

        {{-- Sub-panel: detalle filtrado por un evento concreto --}}
        <div class="soc-subpanel" id="eventos-detalle-view">
            <div class="soc-scroll" id="eventos-detalle-scroll">
                {{-- Historias del evento --}}
                <div class="historias-barra" id="historias-evento-barra"></div>
                {{-- Posts del evento --}}
                <div id="eventos-detalle-posts"></div>
                <div class="soc-spinner-wrap" id="eventos-detalle-cargando" style="display:none">
                    <div class="soc-spinner"></div>
                </div>
                <p class="soc-vacio" id="eventos-detalle-vacio" style="display:none">
                    No hay publicaciones para este evento todavía.
                </p>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════
         BOTTOM NAVIGATION / SIDEBAR
         ══════════════════════════════════════ --}}
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

        <button class="soc-nav-btn" onclick="irA('eventos')" id="nav-btn-eventos">
            <div class="soc-nav-icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15 5v2m-6-2v2M5 9h14M3 7h18v14a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                </svg>
            </div>
            <span class="soc-nav-label">Eventos</span>
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
                <label class="soc-field-label">Etiquetar evento (opcional)</label>
                <select id="pub-select-evento" class="soc-select">
                    <option value="">Sin etiqueta</option>
                </select>

                <label class="soc-field-label">Descripción (opcional)</label>
                <textarea id="pub-textarea-desc" class="soc-textarea"
                          placeholder="Cuenta cómo fue el evento…"
                          maxlength="1000" rows="3"></textarea>

                {{-- ── Visibilidad ── --}}
                <label class="soc-field-label">¿Quién puede verlo?</label>
                <select id="pub-select-visibilidad" class="soc-select">
                    <option value="1">🌍 Todos</option>
                    <option value="2">🔒 Solo mis amigos</option>
                </select>

                <label class="soc-field-label">Fotos * (mínimo 1, máximo 10)</label>
                <div class="soc-upload-area" id="pub-upload-area"
                     onclick="document.getElementById('pub-input-fotos').click()"
                     ondragover="event.preventDefault(); this.classList.add('dragover')"
                     ondragleave="this.classList.remove('dragover')"
                     ondrop="soltar(event)">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p id="pub-upload-label">Haz clic o arrastra tus fotos aquí</p>
                </div>
                <input type="file" id="pub-input-fotos" accept="image/jpeg,image/png,image/webp" multiple style="display:none"
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
         MODAL: Nueva historia
         ══════════════════════════════════════ --}}
    <div class="soc-modal-overlay" id="hist-modal-overlay" style="display:none"
         onclick="cerrarModalHistoria()">
        <div class="soc-modal" onclick="event.stopPropagation()">

            <div class="soc-modal-head">
                <h3 class="soc-modal-titulo">Nueva historia</h3>
                <button class="soc-modal-cerrar" onclick="cerrarModalHistoria()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="soc-modal-body">

                {{-- Preview de la foto --}}
                <div class="hist-preview-wrap" id="hist-preview-wrap" style="display:none">
                    <img id="hist-preview-img" src="" alt="Preview">
                </div>

                {{-- Upload --}}
                <div class="soc-upload-area" id="hist-upload-area"
                     onclick="document.getElementById('hist-input-foto').click()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <circle cx="12" cy="13" r="3"/>
                    </svg>
                    <p>Haz clic para añadir una foto</p>
                </div>
                <input type="file" id="hist-input-foto" accept="image/*" style="display:none"
                       onchange="previsualizarHistoria(this)">

                {{-- Texto opcional --}}
                <label class="soc-field-label">Texto (opcional)</label>
                <input type="text" id="hist-input-texto" class="soc-select"
                       placeholder="Escribe algo…" maxlength="200">

                {{-- Etiquetar evento --}}
                <label class="soc-field-label">Etiquetar evento (opcional)</label>
                <select id="hist-select-evento" class="soc-select">
                    <option value="">Sin etiqueta</option>
                </select>

            </div>

            <div class="soc-modal-foot">
                <button class="soc-btn-secondary" onclick="cerrarModalHistoria()">Cancelar</button>
                <button class="soc-btn-primary" id="hist-btn-publicar" onclick="publicarHistoria()">
                    Compartir
                </button>
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════════
         MODAL: Crear crew (grupo de chat)
         ══════════════════════════════════════ --}}
    <div class="soc-modal-overlay" id="crew-modal-overlay" style="display:none"
         onclick="cerrarModalCrearCrew()">
        <div class="soc-modal" onclick="event.stopPropagation()">

            <div class="soc-modal-head">
                <h3 class="soc-modal-titulo">Crear crew</h3>
                <button class="soc-modal-cerrar" onclick="cerrarModalCrearCrew()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="soc-modal-body">

                <label class="soc-field-label">Nombre del crew</label>
                <input type="text" id="crew-input-nombre" class="soc-select"
                       placeholder="Ej: Los del viernes…" maxlength="100">

                <label class="soc-field-label" style="margin-top:14px">Añadir amigos</label>
                <div id="crew-lista-amigos" class="amigo-check-lista">
                    <p class="soc-vacio" style="padding:12px 0">Cargando amigos…</p>
                </div>

            </div>

            <div class="soc-modal-foot">
                <button class="soc-btn-secondary" onclick="cerrarModalCrearCrew()">Cancelar</button>
                <button class="soc-btn-primary" id="crew-btn-crear" onclick="crearCrew()">
                    Crear crew
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

    {{-- ══════════════════════════════════════
         VISOR DE HISTORIAS (fullscreen overlay)
         ══════════════════════════════════════ --}}
    <div class="visor-overlay" id="visor-overlay" style="display:none">

        {{-- Barra de progreso (1 segmento por historia del grupo actual) --}}
        <div class="visor-progress" id="visor-progress"></div>

        {{-- Cabecera: avatar + nombre + evento + cerrar --}}
        <div class="visor-head">
            <div class="visor-autor" id="visor-autor"></div>
            <div class="visor-evento-tag" id="visor-evento-tag" style="display:none"></div>
            <button class="visor-cerrar" onclick="cerrarVisorHistorias()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Foto central --}}
        <div class="visor-foto-wrap" id="visor-foto-wrap">
            <img id="visor-foto" src="" alt="">
        </div>

        {{-- Texto opcional encima de la foto --}}
        <div class="visor-texto" id="visor-texto" style="display:none"></div>

        {{-- Zonas de toque para navegar --}}
        <div class="visor-zona-izq" onclick="visorAnterior()"></div>
        <div class="visor-zona-der" onclick="visorSiguiente()"></div>

    </div>

</div>{{-- /soc --}}

{{-- ── Scripts ── --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.miUsuarioId = {{ Auth::id() }};
</script>
<script src="{{ asset('js/social.js') }}"></script>
<script>
(async function cargarPromotorasSocial() {
    const lista = document.getElementById('soc-promotoras-lista');
    if (!lista) return;
    try {
        const res  = await fetch('/api/seguimientos/promotoras', {
            headers: { 'Accept': 'application/json' }
        });
        const data = await res.json();
        const promotoras = data.promotoras ?? [];

        if (promotoras.length === 0) {
            lista.innerHTML = '<p class="soc-vacio" style="font-size:0.8rem;padding:4px 0;">Aún no sigues ninguna promotora.</p>';
            return;
        }

        lista.innerHTML = promotoras.map(p => {
            const inicialLetra = p.nombre ? p.nombre.charAt(0).toUpperCase() : '?';
            const logoHtml = p.logo_url
                ? `<img src="${p.logo_url}" alt="${p.nombre}" style="width:36px;height:36px;border-radius:50%;object-fit:cover;flex-shrink:0;">`
                : `<div style="width:36px;height:36px;border-radius:50%;background:#a855f7;display:flex;align-items:center;justify-content:center;font-weight:900;color:#fff;font-size:0.9rem;flex-shrink:0;">${inicialLetra}</div>`;

            const proximoEvento = (p.proximos_eventos && p.proximos_eventos.length > 0)
                ? `<span style="color:#a855f7;font-size:0.72rem;">${p.proximos_eventos[0].titulo}</span>`
                : '';

            return `<div style="display:flex;align-items:center;gap:10px;">
                ${logoHtml}
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:700;color:#f5f1ea;font-size:0.82rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${p.nombre}</div>
                    ${proximoEvento}
                </div>
                <button class="soc-side-btn-ghost"
                        style="font-size:0.7rem;padding:4px 10px;flex-shrink:0;"
                        data-empresa-id="${p.id}"
                        onclick="toggleSeguirSocial(this)">✓</button>
            </div>`;
        }).join('');
    } catch (e) {
        lista.innerHTML = '<p class="soc-vacio" style="font-size:0.8rem;">Error al cargar promotoras.</p>';
    }
})();

async function toggleSeguirSocial(btn) {
    const empresaId = btn.dataset.empresaId;
    btn.disabled = true;
    try {
        const res  = await fetch(`/api/seguimientos/${empresaId}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        });
        const data = await res.json();
        if (data.success && !data.siguiendo) {
            const fila = btn.closest('div[style*="display:flex"]');
            if (fila) {
                fila.style.transition = 'opacity 0.25s';
                fila.style.opacity   = '0';
                setTimeout(() => fila.remove(), 250);
            }
        }
    } catch (e) {
        console.error('Error', e);
    } finally {
        btn.disabled = false;
    }
}
</script>

@endsection

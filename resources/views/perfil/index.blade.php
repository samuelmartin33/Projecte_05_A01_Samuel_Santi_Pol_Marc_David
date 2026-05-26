@extends('layouts.app')

@section('titulo', 'Mi Perfil — VIBEZ')

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
@endpush

@section('content')

@include('partials.home.nav')

{{-- ════════════════════════════════════════════════════
     HERO DE PERFIL
     Muestra avatar (con preview de foto), nombre, bio y mood
════════════════════════════════════════════════════ --}}
<section class="perfil-hero">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex flex-col sm:flex-row items-center sm:items-end gap-6">

        {{-- ── Foto de perfil con formulario simple ── --}}
        {{-- enctype="multipart/form-data" es obligatorio para enviar archivos --}}
        <form id="formFoto" action="{{ route('perfil.foto') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="position:relative; display:inline-block; flex-shrink:0;">
                {{-- Avatar: al hacer clic abre el selector de archivo --}}
                <div class="perfil-avatar-wrap" onclick="document.getElementById('inputFoto').click()">
                    <div class="perfil-avatar" id="avatarPreview">
                        @if($usuario->foto_url)
                            <img src="{{ $usuario->foto_url }}" alt="{{ $usuario->nombre }}">
                        @else
                            <span class="perfil-avatar-iniciales">
                                {{ strtoupper(substr($usuario->nombre,0,1)) }}{{ strtoupper(substr($usuario->apellido1 ?? '',0,1)) }}
                            </span>
                        @endif
                    </div>
                    <div class="perfil-avatar-overlay">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    {{-- Input oculto; cuando el usuario elige un archivo se llama previsualizarFoto() --}}
                    <input type="file" id="inputFoto" name="foto" accept="image/*" style="display:none"
                           onchange="previsualizarFoto(this)">
                </div>

                {{-- Botón guardar foto: oculto hasta que el usuario seleccione una imagen --}}
                <button type="submit" id="btnGuardarFoto" class="btn-perfil-guardar btn-foto-hero" style="display:none">
                    Guardar foto
                </button>
            </div>
        </form>

        {{-- ── Nombre, email, bio y mood ── --}}
        <div class="flex-1">
            <h1 class="text-2xl sm:text-3xl font-black text-white">
                {{ $usuario->nombre }} {{ $usuario->apellido1 }}
            </h1>
            <p class="text-white/50 text-sm mt-1">{{ $usuario->email }}</p>

            {{-- Biografía visible en el hero para que los amigos la vean --}}
            @if($usuario->biografia)
                <p class="perfil-bio-hero">{{ $usuario->biografia }}</p>
            @endif

        </div>

    </div>
</section>

{{-- ════════════════════════════════════════════════════
     ALERTA FLASH + CONTENIDO PRINCIPAL
════════════════════════════════════════════════════ --}}
<div class="perfil-page-wrap">
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
    @if(session('info'))
        <div class="perfil-alerta perfil-alerta-ok">
            ℹ {{ session('info') }}
        </div>
    @endif
    @if(session('exito'))
        <div class="perfil-alerta perfil-alerta-ok">
            ✓ {{ session('exito') }}
        </div>
    @endif

    {{-- Errores de validación (Laravel los guarda en $errors tras redirigir) --}}
    @if($errors->any())
        <div class="perfil-alerta perfil-alerta-error">
            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif
</div>

{{-- ════════════════════════════════════════════════════
     CONTENIDO PRINCIPAL
════════════════════════════════════════════════════ --}}
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col gap-6">

        {{-- ══ TARJETA: Datos personales ══ --}}
        {{-- Formulario normal: action + method + @csrf. Sin AJAX, fácil de entender. --}}
        <div class="perfil-card">
            <h2 class="perfil-card-titulo">Datos personales</h2>
            <p class="perfil-card-sub">Edita tu información y pulsa "Guardar"</p>

            <form action="{{ route('perfil.actualizar') }}" method="POST" novalidate>
                @csrf

                <div class="perfil-grid-2">
                    <div class="perfil-field">
                        <label for="nombre">Nombre</label>
                        {{-- old('nombre', ...) recupera el valor anterior si la validación falla --}}
                        <input type="text" id="nombre" name="nombre"
                               value="{{ old('nombre', $usuario->nombre) }}" required>
                    </div>
                    <div class="perfil-field">
                        <label for="apellido1">Primer apellido</label>
                        <input type="text" id="apellido1" name="apellido1"
                               value="{{ old('apellido1', $usuario->apellido1) }}" required>
                    </div>
                </div>

                <div class="perfil-grid-2">
                    <div class="perfil-field">
                        <label for="apellido2">Segundo apellido</label>
                        <input type="text" id="apellido2" name="apellido2"
                               value="{{ old('apellido2', $usuario->apellido2) }}">
                    </div>
                    <div class="perfil-field">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" id="telefono" name="telefono"
                               value="{{ old('telefono', $usuario->telefono) }}">
                    </div>
                </div>

                <div class="perfil-field">
                    <label for="fecha_nacimiento">Fecha de nacimiento</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                           value="{{ old('fecha_nacimiento', $usuario->fecha_nacimiento) }}">
                </div>

                {{-- La bio es pública: la ven los amigos en la cabecera del perfil --}}
                <div class="perfil-field">
                    <label for="biografia">Biografía <span class="perfil-badge-publica">Pública</span></label>
                    <textarea id="biografia" name="biografia" rows="3"
                              placeholder="Cuéntanos algo sobre ti...">{{ old('biografia', $usuario->biografia) }}</textarea>
                    <span class="perfil-field-hint">Máx. 500 caracteres · Visible para todos tus amigos</span>
                </div>

                <button type="submit" class="btn-perfil-guardar">
                    Guardar cambios
                </button>
            </form>
        </div>

        {{-- ══ TARJETA: Promotoras que sigues ══ --}}
        <div class="perfil-card">
            <h2 class="perfil-card-titulo">Promotoras que sigues</h2>
            <p class="perfil-card-sub">Sigue a promotoras desde la ficha de cualquier evento</p>

            @if($promotoras->isEmpty())
                <p style="color:rgba(245,241,234,0.4);font-size:0.9rem;margin-top:1rem;">
                    Aún no sigues ninguna promotora. Entra en un evento y pulsa "Seguir".
                </p>
            @else
                <div style="display:flex;flex-direction:column;gap:16px;margin-top:1rem;">
                    @foreach($promotoras as $empresa)
                    <div style="display:flex;align-items:center;gap:14px;padding:12px;background:rgba(124,58,237,0.08);border:1px solid rgba(124,58,237,0.2);">
                        {{-- Logo --}}
                        <div style="width:44px;height:44px;flex-shrink:0;border-radius:50%;overflow:hidden;background:#a855f7;display:flex;align-items:center;justify-content:center;">
                            @if($empresa->logo_url)
                                <img src="{{ $empresa->logo_url }}" alt="{{ $empresa->nombre_empresa }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <span style="font-weight:900;color:#fff;font-size:1rem;">{{ strtoupper(substr($empresa->nombre_empresa,0,1)) }}</span>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div style="flex:1;min-width:0;">
                            <p style="font-weight:700;color:#f5f1ea;margin:0;font-size:0.95rem;">{{ $empresa->nombre_empresa }}</p>
                            @if($empresa->descripcion)
                                <p style="color:rgba(245,241,234,0.5);font-size:0.78rem;margin:2px 0 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $empresa->descripcion }}</p>
                            @endif
                            @if($empresa->eventos->isNotEmpty())
                                <p style="color:#a855f7;font-size:0.75rem;margin:4px 0 0;">
                                    Próximo: {{ $empresa->eventos->first()->titulo }}
                                    · {{ $empresa->eventos->first()->fecha_fmt }}
                                </p>
                            @endif
                        </div>

                        {{-- Botón dejar de seguir --}}
                        <button class="btn-seguir-perfil siguiendo"
                                data-empresa-id="{{ $empresa->id }}"
                                onclick="toggleSeguirPerfil(this)">
                            Siguiendo
                        </button>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ══ TARJETA: Estado de ánimo ══ --}}
        <div class="perfil-card">
            <h2 class="perfil-card-titulo">Mi estado de ánimo</h2>
            <p class="perfil-card-sub">Elige cómo te sientes · Visible para tus amigos</p>

            <div id="mood-alerta" class="perfil-alerta perfil-alerta-ok" style="display:none; margin-top:0.75rem;"></div>

            <div id="mood-activo" style="{{ $usuario->mood ? 'display:flex' : 'display:none' }}; align-items:center; gap:0.75rem; margin-top:0.75rem; padding:0.6rem 0.9rem; border-radius:0.5rem; background:rgba(124,58,237,0.15); border:1px solid rgba(124,58,237,0.35);">
                <span id="mood-activo-texto" style="font-size:0.9rem; color:#c084fc; flex:1;">{{ $usuario->mood }}</span>
                <button type="button" onclick="seleccionarMood('', null)" style="background:transparent; border:none; color:rgba(245,241,234,0.4); cursor:pointer; font-size:0.8rem; padding:0; line-height:1;" title="Quitar estado">✕ Quitar</button>
            </div>

            <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:0.5rem; margin-top:1rem;">
                @foreach(['🥳 De fiesta', '🎵 Escuchando música', '🌙 Noche de salida', '🔥 Con ganas de juerga', '🍻 Tomando algo', '🕺 Bailando', '😎 Tranquilo/a', '😴 Descansando', '💤 Sin planes', '🎶 Modo techno'] as $opcion)
                    <button type="button"
                            class="mood-opcion {{ $usuario->mood === $opcion ? 'mood-opcion--activo' : '' }}"
                            onclick="seleccionarMood('{{ $opcion }}', this)">
                        {{ $opcion }}
                    </button>
                @endforeach
            </div>

            <div style="margin-top:1rem; position:relative;">
                <div style="display:flex; gap:0.5rem; align-items:center;">
                    <button type="button" id="btn-emoji-picker"
                            onclick="toggleEmojiPicker(event)"
                            title="Seleccionar emoji"
                            style="flex-shrink:0; background:rgba(124,58,237,0.15); border:1.5px solid rgba(124,58,237,0.3); border-radius:0.5rem; padding:0.4rem 0.6rem; cursor:pointer; font-size:1.15rem; line-height:1;">
                        <span id="emoji-seleccionado" style="opacity:0.4;">🙂</span>
                    </button>
                    <input type="text" id="mood-personalizado"
                           placeholder="Escribe tu propio estado..."
                           maxlength="96"
                           style="flex:1;"
                           onkeydown="if(event.key==='Enter'){enviarMoodPersonalizado();}">
                    <button type="button" onclick="enviarMoodPersonalizado()" class="btn-perfil-guardar" style="white-space:nowrap; padding:0 1rem;">
                        Guardar
                    </button>
                </div>

                <p id="mood-emoji-aviso" style="display:none; margin:0.35rem 0 0; font-size:0.78rem; color:#f87171;">
                    Selecciona un emoji antes de guardar.
                </p>

                <div id="emoji-picker-panel"
                     onclick="event.stopPropagation()"
                     style="display:none; position:absolute; bottom:calc(100% + 0.4rem); left:0; z-index:200; background:#0d0a18; border:1px solid rgba(124,58,237,0.35); border-radius:0.75rem; padding:0.75rem; width:100%; max-width:340px; box-shadow:0 8px 32px rgba(0,0,0,0.6);">
                    <div style="display:grid; grid-template-columns:repeat(8,1fr); gap:0.2rem;">
                        @foreach(['😀','😎','😴','🥳','😊','🤩','😤','🥺','🤔','😅','🙄','😏','🥰','😂','😭','🤯','🔥','💜','✨','⚡','🌙','🌈','💫','🎉','🎵','🎶','🕺','💃','🍻','☕','🍕','🎮','📚','🏃','🤙','👑','🦋','🌸'] as $emoji)
                            <button type="button"
                                    onclick="insertarEmoji('{{ $emoji }}')"
                                    class="emoji-btn">{{ $emoji }}</button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

</div>
</div>

@endsection

@section('scripts')
    <script src="{{ asset('js/perfil.js') }}"></script>
    <style>
    .btn-seguir-perfil {
        padding: 6px 14px;
        border: 1.5px solid rgba(168,85,247,0.6);
        background: rgba(168,85,247,0.15);
        color: #e9d5ff;
        font-family: 'Syne', sans-serif;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        cursor: pointer;
        white-space: nowrap;
        flex-shrink: 0;
        transition: background 0.18s, opacity 0.18s;
    }
    .btn-seguir-perfil:hover { background: rgba(220,38,38,0.15); border-color: #ef4444; color: #fca5a5; }
    .btn-seguir-perfil.cargando { opacity: 0.5; pointer-events: none; }
    </style>
    <script>
    async function toggleSeguirPerfil(btn) {
        const empresaId = btn.dataset.empresaId;
        btn.classList.add('cargando');
        try {
            const res = await fetch(`/api/seguimientos/${empresaId}/toggle`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            });
            const data = await res.json();
            if (data.success && !data.siguiendo) {
                // Animar y quitar la fila
                const fila = btn.closest('div[style*="display:flex"]');
                if (fila) {
                    fila.style.transition = 'opacity 0.3s';
                    fila.style.opacity = '0';
                    setTimeout(() => fila.remove(), 300);
                }
            }
        } catch (e) {
            console.error('Error al dejar de seguir', e);
        } finally {
            btn.classList.remove('cargando');
        }
    }
    </script>
@endsection

@extends('layouts.app')

@section('titulo', 'Mi Perfil — VIBEZ')

@section('contenido')

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

            {{-- Mood actual (visible para todos, excepto admin y empresa) --}}
            @if($usuario->mood && !$usuario->isAdmin() && !$usuario->isEmpresa())
                <span class="perfil-mood-hero">{{ $usuario->mood }}</span>
            @endif
        </div>

    </div>
</section>

{{-- ════════════════════════════════════════════════════
     ALERTA FLASH
     Aparece solo si el formulario redirigió con un mensaje
════════════════════════════════════════════════════ --}}
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
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
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- ── COLUMNA IZQUIERDA (2/3) ── --}}
    <div class="lg:col-span-2 flex flex-col gap-6">

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

        {{-- ══ TARJETA: Estado de ánimo (Mood) ══ --}}
        {{-- Solo para usuarios estándar y organizadores, no admin ni empresa --}}
        @if(!$usuario->isAdmin() && !$usuario->isEmpresa())
        <div class="perfil-card">
            <h2 class="perfil-card-titulo">Estado de ánimo</h2>
            <p class="perfil-card-sub">
                Visible para <strong>todos</strong> (amigos o no) y aparece en la barra de navegación
            </p>

            <form action="{{ route('perfil.mood') }}" method="POST">
                @csrf

                <div class="perfil-mood-grid">
                    {{-- Lista de opciones de mood --}}
                    @php
                        $moods = [
                            ''                    => '— Sin estado —',
                            '🤕 De resaca'        => '🤕 De resaca',
                            '🥳 De fiesta'        => '🥳 De fiesta',
                            '🍺 Bebiendo cerveza' => '🍺 Bebiendo cerveza',
                            '🍷 Bebiendo vino'    => '🍷 Bebiendo vino',
                            '❤️ Enamorado/a'      => '❤️ Enamorado/a',
                            '💃 Bailando'         => '💃 Bailando',
                            '🎵 Escuchando música'=> '🎵 Escuchando música',
                            '😎 Modo casual'      => '😎 Modo casual',
                            '💪 En el gym'        => '💪 En el gym',
                            '😴 Durmiendo'        => '😴 Durmiendo',
                            '🍕 Comiendo'         => '🍕 Comiendo',
                            '✈️ De viaje'         => '✈️ De viaje',
                            '🎮 Gaming'           => '🎮 Gaming',
                            '☀️ Tomando el sol'   => '☀️ Tomando el sol',
                            '🤙 Con los amigos'   => '🤙 Con los amigos',
                        ];
                    @endphp

                    @foreach($moods as $valor => $etiqueta)
                        @if($valor === '')
                            {{-- Opción "Sin estado" siempre va primero --}}
                            <label class="perfil-mood-opcion {{ $usuario->mood === null ? 'perfil-mood-activo' : '' }}">
                                <input type="radio" name="mood" value=""
                                       {{ $usuario->mood === null ? 'checked' : '' }}>
                                <span>{{ $etiqueta }}</span>
                            </label>
                        @else
                            <label class="perfil-mood-opcion {{ $usuario->mood === $valor ? 'perfil-mood-activo' : '' }}">
                                <input type="radio" name="mood" value="{{ $valor }}"
                                       {{ $usuario->mood === $valor ? 'checked' : '' }}>
                                <span>{{ $etiqueta }}</span>
                            </label>
                        @endif
                    @endforeach
                </div>

                <button type="submit" class="btn-perfil-guardar" style="margin-top:16px">
                    Guardar estado
                </button>
            </form>
        </div>
        @endif

    </div>

    {{-- ── COLUMNA DERECHA (1/3) ── --}}
    <div class="flex flex-col gap-6">

        {{-- ══ TARJETA: Amigos (oculta para admin) ══ --}}
        @if(!$usuario->isAdmin())
        <div class="perfil-card" id="amigos">
            <h2 class="perfil-card-titulo">Amigos</h2>

            {{-- Buscador dinámico: necesita AJAX para mostrar resultados sin recargar --}}
            <div class="perfil-field">
                <label for="buscarAmigo">Buscar por nombre o email</label>
                <input type="text" id="buscarAmigo" placeholder="Escribe al menos 2 caracteres..."
                       oninput="buscarAmigos(this.value)">
            </div>

            {{-- Contenedor donde JS inyecta los resultados --}}
            <div id="resultadosBusqueda" style="display:none" class="perfil-busqueda-lista"></div>

            {{-- ── Solicitudes de amistad pendientes ── --}}
            @if($solicitudesPendientes->count() > 0)
                <div class="perfil-solicitudes-wrap">
                    <h3 class="perfil-solicitudes-titulo">
                        Solicitudes recibidas
                        <span class="perfil-badge-count">{{ $solicitudesPendientes->count() }}</span>
                    </h3>

                    @foreach($solicitudesPendientes as $solicitud)
                        <div class="perfil-solicitud-item">
                            {{-- Avatar del que envió la solicitud --}}
                            <div class="perfil-solicitud-avatar">
                                @if($solicitud->solicitante->foto_url)
                                    <img src="{{ $solicitud->solicitante->foto_url }}" alt="">
                                @else
                                    {{ strtoupper(substr($solicitud->solicitante->nombre,0,1)) }}
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-sm truncate" style="color:#0f172a">
                                    {{ $solicitud->solicitante->nombre }} {{ $solicitud->solicitante->apellido1 }}
                                </p>
                            </div>

                            {{-- Botones como formularios separados (aceptar / rechazar) --}}
                            <div class="flex gap-1">
                                {{-- Formulario para ACEPTAR --}}
                                <form action="{{ route('amigos.aceptar', $solicitud->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    <button type="submit" class="perfil-btn-aceptar" title="Aceptar">✓</button>
                                </form>

                                {{-- Formulario para RECHAZAR --}}
                                <form action="{{ route('amigos.rechazar', $solicitud->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    <button type="submit" class="perfil-btn-rechazar" title="Rechazar">✕</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- ── Lista de amigos aceptados ── --}}
            <div class="perfil-amigos-lista">
                @if($amigos->count() > 0)
                    <h3 class="perfil-solicitudes-titulo mt-4">
                        Tus amigos ({{ $amigos->count() }})
                    </h3>
                    @foreach($amigos as $amigo)
                        <div class="perfil-solicitud-item">
                            <div class="perfil-solicitud-avatar">
                                @if($amigo->foto_url)
                                    <img src="{{ $amigo->foto_url }}" alt="">
                                @else
                                    {{ strtoupper(substr($amigo->nombre,0,1)) }}
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-sm truncate" style="color:#0f172a">
                                    {{ $amigo->nombre }} {{ $amigo->apellido1 }}
                                </p>
                                {{-- Mostrar mood del amigo si lo tiene --}}
                                @if($amigo->mood)
                                    <p class="text-xs" style="color:rgba(15,23,42,0.45)">{{ $amigo->mood }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-sm text-center py-4" style="color:rgba(15,23,42,0.4)">
                        Aún no tienes amigos en VIBEZ. ¡Busca a alguien!
                    </p>
                @endif
            </div>

        </div>
        @endif

    </div>

</div>

@endsection

@section('scripts')
    <script src="{{ asset('js/perfil.js') }}"></script>
@endsection

@extends('admin.layouts.dashboard')

@section('title', 'Canciones — Admin VIBEZ')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/admin-canciones.css') }}">
@endpush

@section('content')

{{-- ── Cabecera ──────────────────────────────────────────────────── --}}
<header class="admin-header">
    <div>
        <h1>Canciones</h1>
        <p>Gestiona el catálogo musical de VIBEZ</p>
    </div>
    <button class="btn btn-primary" onclick="abrirModalCrear()">+ Nueva canción</button>
</header>

{{-- ── Tabla ─────────────────────────────────────────────────────── --}}
<section class="card">
    <table class="tabla-eventos">
        <thead>
            <tr>
                <th>Portada</th>
                <th>Título</th>
                <th>Artista</th>
                <th>Géneros</th>
                <th>Duración</th>
                <th>Precio</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tabla-canciones-body">
            @include('admin.canciones._tabla', ['canciones' => $canciones])
        </tbody>
    </table>
</section>

{{-- ══════════════════════════════════════════════════════════════════
     MODAL CREAR / EDITAR
══════════════════════════════════════════════════════════════════ --}}
<div id="modal-cancion-overlay" onclick="cerrarModalSiOverlay(event)">
    <div id="modal-cancion" role="dialog" aria-modal="true" aria-labelledby="modal-cancion-titulo">

        <button class="modal-close-btn" onclick="cerrarModal()" aria-label="Cerrar">✕</button>
        <h2 id="modal-cancion-titulo">Nueva canción</h2>

        <form id="form-cancion" novalidate enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="cancion-id" value="">

            <div class="modal-campo">
                <label for="cancion-titulo">
                    Título <span class="campo-requerido">*</span>
                </label>
                <input type="text" id="cancion-titulo" name="titulo"
                       maxlength="255" placeholder="Ej. Blinding Lights" required>
            </div>

            <div class="modal-campo">
                <label for="cancion-artista">
                    Artista <span class="campo-requerido">*</span>
                </label>
                <input type="text" id="cancion-artista" name="artista"
                       maxlength="255" placeholder="Ej. The Weeknd" required>
            </div>

            <div class="modal-grid-2">
                <div class="modal-campo">
                    <label for="cancion-duracion">
                        Duración (seg.) <span class="campo-requerido">*</span>
                    </label>
                    <input type="number" id="cancion-duracion" name="duracion_segundos"
                           min="1" step="1" placeholder="200" required>
                </div>
                <div class="modal-campo">
                    <label for="cancion-precio">
                        Precio (€) <span class="campo-requerido">*</span>
                    </label>
                    <input type="number" id="cancion-precio" name="precio"
                           min="0" step="0.01" placeholder="1.99" required>
                </div>
            </div>

            <div class="modal-campo">
                <label>Géneros <span class="campo-requerido">*</span></label>
                <div class="generos-grid">
                    @foreach(['Pop','Rock','Electrónica','Reggaeton','House','Techno','R&B','Otro'] as $genero)
                        <label class="genero-check-label">
                            <input type="checkbox" name="generos[]"
                                   value="{{ $genero }}" class="chk-genero">
                            {{ $genero }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="modal-campo">
                <label class="check-activa-label">
                    <input type="checkbox" id="cancion-activa" name="activa" value="1">
                    Canción activa
                </label>
            </div>

            <div class="modal-campo">
                <label for="cancion-portada">Portada (jpg, png, webp — máx. 5 MB)</label>
                <input type="file" id="cancion-portada" name="portada_archivo"
                       accept=".jpg,.jpeg,.png,.webp"
                       onchange="previsualizarPortada(this)">
                <div id="portada-preview-wrap" style="display:none;margin-top:8px;align-items:center;gap:12px;">
                    <img id="portada-preview-img" alt="Portada"
                         style="width:64px;height:64px;object-fit:cover;border:1px solid rgba(245,241,234,0.12);">
                    <p id="portada-actual-label" style="font-size:0.72rem;color:rgba(245,241,234,0.4);margin:0;"></p>
                </div>
            </div>

            <div class="modal-campo">
                <label for="cancion-audio">Archivo de audio (mp3, wav, ogg — máx. 30 MB)</label>
                <input type="file" id="cancion-audio" name="audio_archivo"
                       accept=".mp3,.wav,.ogg,.m4a,.aac">
                <div id="audio-preview-wrap" style="display:none;margin-top:8px;">
                    <audio id="audio-preview" controls style="width:100%;height:36px;"></audio>
                    <p id="audio-actual-label" style="font-size:0.72rem;color:rgba(245,241,234,0.4);margin-top:4px;"></p>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal()">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-primary" id="btn-guardar-cancion">
                    Guardar
                </button>
            </div>
        </form>

    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/admin-canciones.js') }}"></script>
@endpush

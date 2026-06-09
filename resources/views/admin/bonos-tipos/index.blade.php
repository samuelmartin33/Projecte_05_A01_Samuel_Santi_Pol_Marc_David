@extends('admin.layouts.dashboard')

@section('title', 'Tipos de Bono — Admin VIBEZ')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/admin-bonos.css') }}">
@endpush

@section('content')

{{-- ── Cabecera ──────────────────────────────────────────────────── --}}
<header class="admin-header">
    <div>
        <h1>Tipos de Bono</h1>
        <p>Gestiona los tipos de bonos disponibles para eventos Fiesta</p>
    </div>
    <div class="bonos-acciones">
        <a href="{{ route('admin.bonos.index') }}" class="btn btn-secondary">
            ← Volver al panel
        </a>
        <button class="btn-pink" onclick="abrirModalCrearTipo()">
            + Nuevo tipo
        </button>
    </div>
</header>

{{-- ── Tabla ─────────────────────────────────────────────────────── --}}
<section class="card">
    <table class="tabla-eventos">
        <thead>
            <tr>
                <th>Bebidas</th>
                <th>Precio</th>
                <th>Descripción</th>
                <th>Compras</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tbody-tipos">
            @include('admin.bonos-tipos._tabla', ['tipos' => $tipos])
        </tbody>
    </table>
</section>

{{-- ══════════════════════════════════════════════════════════════════
     MODAL CREAR / EDITAR TIPO
══════════════════════════════════════════════════════════════════ --}}
<div id="modal-tipo-overlay" onclick="cerrarModalSiOverlay(event)">
    <div id="modal-tipo" role="dialog" aria-modal="true" aria-labelledby="modal-tipo-titulo">

        <button class="modal-tipo-close" onclick="cerrarModal()" aria-label="Cerrar">✕</button>
        <h2 id="modal-tipo-titulo">Nuevo tipo de bono</h2>

        <form id="form-tipo" novalidate>
            @csrf
            <input type="hidden" id="tipo-id" value="">

            <div class="tipo-campo-grid2">
                <div class="tipo-campo">
                    <label for="tipo-cantidad">
                        Nº de bebidas <span class="campo-req">*</span>
                    </label>
                    <input type="number" id="tipo-cantidad" name="cantidad_bebidas"
                           min="1" step="1" placeholder="5" required>
                </div>
                <div class="tipo-campo">
                    <label for="tipo-precio">
                        Precio (€) <span class="campo-req">*</span>
                    </label>
                    <input type="number" id="tipo-precio" name="precio"
                           min="0" step="0.01" placeholder="15.00" required>
                </div>
            </div>

            <div class="tipo-campo">
                <label for="tipo-descripcion">Descripción</label>
                <textarea id="tipo-descripcion" name="descripcion"
                          rows="2" placeholder="Ej. Bono estándar para fiestas"></textarea>
            </div>

            <div class="tipo-campo">
                <label class="tipo-campo-check">
                    <input type="checkbox" id="tipo-activo" name="activo" value="1">
                    Tipo activo (disponible para compra)
                </label>
            </div>

            <div class="tipo-modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal()">
                    Cancelar
                </button>
                <button type="submit" class="btn-pink" id="btn-guardar-tipo">
                    Crear tipo
                </button>
            </div>
        </form>

    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/admin-bonos.js') }}"></script>
@endpush

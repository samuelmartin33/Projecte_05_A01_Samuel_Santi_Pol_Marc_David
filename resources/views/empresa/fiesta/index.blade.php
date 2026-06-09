@extends('layouts.app')

@section('titulo', 'Eventos Fiesta — VIBEZ')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/empresa-fiesta.css') }}">
@endpush

@section('contenido')
<div class="container mx-auto px-4 py-8" style="max-width: 1100px;">

    {{-- Cabecera --}}
    <div class="fiesta-header">
        <div style="font-size: 2.5rem;">🎉</div>
        <div>
            <h1>Eventos Fiesta</h1>
            <p>Gestiona tus eventos de tipo Fiesta con acceso exclusivo para mayores de 18 años.</p>
        </div>
    </div>

    {{-- Barra de filtros + botón crear --}}
    <div class="filtros-bar">
        <input type="text" id="filtro-nombre"    placeholder="Buscar por nombre..."    oninput="filtrarEventos()">
        <input type="date" id="filtro-fecha"                                            onchange="filtrarEventos()">
        <input type="text" id="filtro-ubicacion" placeholder="Buscar por ubicación..." oninput="filtrarEventos()">
        <button class="btn-nuevo" onclick="abrirModalCrear()">+ Nuevo evento</button>
    </div>

    {{-- Tabla de eventos (se rellena via AJAX) --}}
    <div style="overflow-x: auto;">
        <table class="fiesta-tabla">
            <thead>
                <tr>
                    <th>Evento</th>
                    <th>Fecha</th>
                    <th>Ubicación</th>
                    <th>Precio</th>
                    <th>Aforo</th>
                    <th>Camarero</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-cuerpo">
                <tr><td colspan="7" class="vacio">Cargando eventos...</td></tr>
            </tbody>
        </table>
    </div>
</div>

{{-- Modal crear / editar --}}
<div class="modal-overlay" id="modal-overlay" onclick="cerrarModalSiFondo(event)">
    <div class="modal-box">
        <h2 id="modal-titulo">Nuevo evento Fiesta</h2>

        <form id="form-evento" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="campo-id" name="id" value="">

            <div class="campo-grupo">
                <label>Nombre del evento *</label>
                <input type="text" name="titulo" id="campo-titulo" required>
            </div>

            <div class="campo-grupo">
                <label>Descripción</label>
                <textarea name="descripcion" id="campo-descripcion" rows="3"></textarea>
            </div>

            <div class="dos-col">
                <div class="campo-grupo">
                    <label>Fecha de inicio *</label>
                    <input type="datetime-local" name="fecha_inicio" id="campo-fecha-inicio" required>
                </div>
                <div class="campo-grupo">
                    <label>Fecha de fin</label>
                    <input type="datetime-local" name="fecha_fin" id="campo-fecha-fin">
                </div>
            </div>

            <div class="campo-grupo">
                <label>Nombre del lugar *</label>
                <input type="text" name="ubicacion_nombre" id="campo-ubicacion-nombre" required>
            </div>

            <div class="campo-grupo">
                <label>Dirección</label>
                <input type="text" name="ubicacion_direccion" id="campo-ubicacion-direccion">
            </div>

            <div class="dos-col">
                <div class="campo-grupo">
                    <label>Precio entrada (€) * <small class="aviso-camarero">Mín. 10 €</small></label>
                    <input type="number" name="precio_base" id="campo-precio" step="0.01" min="10" required>
                </div>
                <div class="campo-grupo">
                    <label>Aforo máximo</label>
                    <input type="number" name="aforo_maximo" id="campo-aforo" min="1">
                </div>
            </div>

            <div class="campo-grupo">
                <label>Camarero asignado * <small class="aviso-camarero">Obligatorio para eventos Fiesta</small></label>
                <select name="camarero_id" id="campo-camarero" required>
                    <option value="">— Selecciona un camarero —</option>
                    @foreach($camareros as $camarero)
                        <option value="{{ $camarero->id }}">
                            {{ $camarero->usuario->nombre }} {{ $camarero->usuario->apellido1 }}
                        </option>
                    @endforeach
                </select>
                @if($camareros->isEmpty())
                    <p class="aviso-camarero">⚠ No tienes camareros contratados. Publica una oferta con categoría Camarero/a primero.</p>
                @endif
            </div>

            <div class="campo-grupo">
                <label>Imagen de portada</label>
                <input type="file" name="imagen_portada" id="campo-imagen" accept="image/*">
            </div>

            <div class="modal-pie">
                <button type="button" class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
                <button type="button" class="btn-guardar" onclick="guardarEvento()">Guardar evento</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/empresa-fiesta.js') }}"></script>
    <script>iniciarFiesta();</script>
@endpush

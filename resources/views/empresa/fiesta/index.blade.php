@extends('layouts.app')

@section('titulo', 'Eventos Fiesta — VIBEZ')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/empresa-fiesta.css') }}">
@endpush

@section('content')

@include('partials.home.nav')

{{-- Hero --}}
<div class="fiesta-hero">
    <div style="max-width:1480px;margin:0 auto;">
        <span class="fiesta-hero-badge">🎉 Área Fiesta</span>
        <h1>Eventos Fiesta</h1>
        <p>Gestiona tus eventos de acceso exclusivo para mayores de 18 años.</p>
    </div>
</div>

{{-- Filtros --}}
<div class="filtros-wrap">
    <input type="text" id="filtro-nombre"    placeholder="Buscar por nombre..."    oninput="filtrarEventos()">
    <input type="date" id="filtro-fecha"                                            onchange="filtrarEventos()">
    <input type="text" id="filtro-ubicacion" placeholder="Buscar por ubicación..." oninput="filtrarEventos()">
    <a href="{{ route('empresa.fiesta.crear') }}" class="btn-nuevo">+ Nuevo evento</a>
</div>

{{-- Tabla --}}
<div style="max-width:1480px;margin:0 auto;padding:0 32px 48px;">
    @if(session('success'))
        <div style="background:rgba(168,85,247,0.08);border:1px solid rgba(168,85,247,0.3);padding:12px 16px;margin:20px 0;color:#c084fc;font-family:'Archivo Narrow',sans-serif;font-size:0.875rem;">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div style="overflow-x:auto;margin-top:24px;">
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

{{-- Modal editar --}}
<div class="modal-overlay" id="modal-overlay" onclick="cerrarModalSiFondo(event)">
    <div class="modal-box">

        <div class="modal-cabecera">
            <h2 id="modal-titulo">Editar evento Fiesta</h2>
            <button class="modal-cerrar" onclick="cerrarModal()">✕</button>
        </div>

        <div class="modal-cuerpo">
            <form id="form-evento" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="campo-id" name="id" value="">

                <div class="campo-grupo">
                    <label>Nombre del evento</label>
                    <input type="text" name="titulo" id="campo-titulo">
                </div>

                <div class="campo-grupo">
                    <label>Descripción</label>
                    <textarea name="descripcion" id="campo-descripcion" rows="3"></textarea>
                </div>

                <div class="dos-col">
                    <div class="campo-grupo">
                        <label>Fecha de inicio</label>
                        <input type="datetime-local" name="fecha_inicio" id="campo-fecha-inicio">
                    </div>
                    <div class="campo-grupo">
                        <label>Fecha de fin</label>
                        <input type="datetime-local" name="fecha_fin" id="campo-fecha-fin">
                    </div>
                </div>

                <div class="campo-grupo">
                    <label>Nombre del lugar</label>
                    <input type="text" name="ubicacion_nombre" id="campo-ubicacion-nombre">
                </div>

                <div class="campo-grupo">
                    <label>Dirección</label>
                    <input type="text" name="ubicacion_direccion" id="campo-ubicacion-direccion">
                </div>

                <div class="dos-col">
                    <div class="campo-grupo">
                        <label>Precio (€) — Mín. 10 €</label>
                        <input type="number" name="precio_base" id="campo-precio" step="0.01" min="10">
                    </div>
                    <div class="campo-grupo">
                        <label>Aforo máximo</label>
                        <input type="number" name="aforo_maximo" id="campo-aforo" min="1">
                    </div>
                </div>

                <div class="campo-grupo">
                    <label>Camarero asignado</label>
                    <select name="camarero_id" id="campo-camarero">
                        <option value="">— Selecciona un camarero —</option>
                        @foreach($camareros as $camarero)
                            <option value="{{ $camarero->id }}">
                                {{ $camarero->usuario->nombre }} {{ $camarero->usuario->apellido1 }}
                            </option>
                        @endforeach
                    </select>
                    @if($camareros->isEmpty())
                        <p class="aviso-camarero">⚠ No tienes camareros contratados.</p>
                    @endif
                </div>

                <div class="campo-grupo">
                    <label>Nueva imagen de portada (opcional)</label>
                    <input type="file" name="imagen_portada" id="campo-imagen" accept="image/*">
                </div>
            </form>
        </div>

        <div class="modal-pie">
            <button type="button" class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
            <button type="button" class="btn-guardar" onclick="guardarEvento()">Guardar cambios</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/empresa-fiesta.js') }}"></script>
    <script>iniciarFiesta();</script>
@endpush

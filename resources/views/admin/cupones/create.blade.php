@extends('admin.layouts.dashboard')
@section('title', 'Admin | Crear Cupón')

@section('content')
    <header class="admin-header">
        <div>
            <h1>Crear Cupón</h1>
            <p>Define el código, el descuento y los eventos donde aplica.</p>
        </div>
        <a class="btn btn-secondary" href="{{ route('admin.cupones.index') }}">← Volver</a>
    </header>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <section class="card">
        <form method="POST" action="{{ route('admin.cupones.store') }}" class="evento-form">
            @csrf
            <div class="grid-form">

                {{-- Código --}}
                <label>
                    Código del cupón *
                    <input type="text" name="codigo" value="{{ old('codigo') }}"
                           placeholder="Ej: VIBEZ10" maxlength="50"
                           style="text-transform:uppercase" required>
                </label>

                {{-- Descuento --}}
                <label>
                    Descuento (%) *
                    <input type="number" name="valor_descuento"
                           value="{{ old('valor_descuento', 10) }}"
                           min="0" max="100" step="0.01" required>
                </label>

                {{-- Descripción --}}
                <label class="full">
                    Descripción
                    <input type="text" name="descripcion"
                           value="{{ old('descripcion') }}"
                           placeholder="Ej: 10% de descuento en todas las entradas del festival"
                           maxlength="255">
                </label>

                {{-- Fechas --}}
                <label>
                    Fecha inicio *
                    <input type="datetime-local" name="fecha_inicio"
                           value="{{ old('fecha_inicio') }}" required>
                </label>

                <label>
                    Fecha fin *
                    <input type="datetime-local" name="fecha_fin"
                           value="{{ old('fecha_fin') }}" required>
                </label>

                {{-- Límites --}}
                <label>
                    Límite total de usos
                    <input type="number" name="limite_usos_total"
                           value="{{ old('limite_usos_total') }}"
                           min="1" placeholder="Vacío = ilimitado">
                </label>

                <label>
                    Límite de usos por usuario
                    <input type="number" name="limite_usos_por_usuario"
                           value="{{ old('limite_usos_por_usuario', 1) }}"
                           min="1">
                </label>

                {{-- Estado --}}
                <label>
                    Estado *
                    <select name="estado" required>
                        <option value="1" {{ old('estado', '1') == '1' ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ old('estado') == '0' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </label>

                {{-- Eventos vinculados --}}
                <div class="full" style="display:flex;flex-direction:column;gap:0.5rem;">
                    <span style="font-size:0.92rem;font-weight:600;color:#e8e4f0;">
                        Eventos donde aplica
                        <small style="color:rgba(245,241,234,0.4);font-weight:400;">
                            (vacío = todos los eventos)
                        </small>
                    </span>
                    <div style="max-height:260px;overflow-y:auto;border:1px solid rgba(255,255,255,0.1);
                                border-radius:8px;padding:8px;background:rgba(0,0,0,0.2);">
                        @foreach($eventos as $ev)
                            <label style="display:flex;align-items:center;gap:10px;padding:8px 10px;
                                          cursor:pointer;border-radius:6px;transition:background 0.15s;"
                                   onmouseover="this.style.background='rgba(124,58,237,0.12)'"
                                   onmouseout="this.style.background='transparent'">
                                <input type="checkbox" name="eventos[]" value="{{ $ev->id }}"
                                       {{ in_array($ev->id, old('eventos', [])) ? 'checked' : '' }}
                                       style="width:16px;height:16px;accent-color:#7c3aed;flex-shrink:0;">
                                <span style="font-size:0.88rem;font-weight:500;">
                                    {{ $ev->titulo }}
                                    <small style="color:rgba(245,241,234,0.4);margin-left:6px;">
                                        {{ optional($ev->fecha_inicio)->format('d/m/Y') }}
                                    </small>
                                </span>
                            </label>
                        @endforeach
                        @if($eventos->isEmpty())
                            <p style="color:rgba(245,241,234,0.4);font-size:0.85rem;padding:8px;">
                                No hay eventos activos.
                            </p>
                        @endif
                    </div>
                </div>

            </div>

            <div class="form-actions" style="margin-top:1.5rem;">
                <button type="submit" class="btn btn-primary">Crear cupón</button>
                <a href="{{ route('admin.cupones.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </section>
@endsection

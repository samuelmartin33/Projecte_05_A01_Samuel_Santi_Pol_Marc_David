@extends('admin.layouts.dashboard')
@section('title', 'Admin | Editar Cupón')

@section('content')
    <header class="admin-header">
        <div>
            <h1>Editar Cupón — <code style="font-family:monospace;color:var(--morado-2)">{{ $cupon->codigo }}</code></h1>
            <p>Modifica los datos del cupón de descuento.</p>
        </div>
        <a class="btn btn-secondary" href="{{ route('admin.cupones.index') }}">← Volver</a>
    </header>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    {{-- Stat del cupón --}}
    <div style="display:flex;gap:12px;margin-bottom:1.2rem;flex-wrap:wrap;">
        <div class="card" style="padding:16px 24px;display:flex;flex-direction:column;gap:4px;min-width:130px;">
            <span style="font-size:0.75rem;color:var(--morado-2);text-transform:uppercase;letter-spacing:0.08em;">Usos actuales</span>
            <span style="font-size:2rem;font-weight:800;color:var(--morado-2);">{{ $cupon->usos_actuales }}</span>
        </div>
        <div class="card" style="padding:16px 24px;display:flex;flex-direction:column;gap:4px;min-width:130px;">
            <span style="font-size:0.75rem;color:var(--morado-2);text-transform:uppercase;letter-spacing:0.08em;">Estado actual</span>
            @if($cupon->is_valido)
                <span class="estado activo" style="width:fit-content;margin-top:4px;">Activo</span>
            @elseif($cupon->expirado)
                <span class="estado inactivo" style="width:fit-content;margin-top:4px;">Expirado</span>
            @elseif($cupon->agotado)
                <span class="estado inactivo" style="width:fit-content;margin-top:4px;">Agotado</span>
            @else
                <span class="estado inactivo" style="width:fit-content;margin-top:4px;">Inactivo</span>
            @endif
        </div>
        <div class="card" style="padding:16px 24px;display:flex;flex-direction:column;gap:4px;min-width:130px;">
            <span style="font-size:0.75rem;color:var(--morado-2);text-transform:uppercase;letter-spacing:0.08em;">Eventos</span>
            <span style="font-size:2rem;font-weight:800;color:var(--morado-2);">
                {{ $cupon->eventos->count() ?: '∞' }}
            </span>
        </div>
    </div>

    <section class="card">
        <form method="POST" action="{{ route('admin.cupones.update', $cupon->id) }}" class="evento-form">
            @csrf
            @method('PUT')
            <div class="grid-form">

                <label>
                    Código del cupón *
                    <input type="text" name="codigo"
                           value="{{ old('codigo', $cupon->codigo) }}"
                           placeholder="Ej: VIBEZ10" maxlength="50"
                           style="text-transform:uppercase" required>
                </label>

                <label>
                    Descuento (%) *
                    <input type="number" name="valor_descuento"
                           value="{{ old('valor_descuento', $cupon->valor_descuento) }}"
                           min="0" max="100" step="0.01" required>
                </label>

                <label class="full">
                    Descripción
                    <input type="text" name="descripcion"
                           value="{{ old('descripcion', $cupon->descripcion) }}"
                           maxlength="255">
                </label>

                <label>
                    Fecha inicio *
                    <input type="datetime-local" name="fecha_inicio"
                           value="{{ old('fecha_inicio', optional($cupon->fecha_inicio)->format('Y-m-d\TH:i')) }}"
                           required>
                </label>

                <label>
                    Fecha fin *
                    <input type="datetime-local" name="fecha_fin"
                           value="{{ old('fecha_fin', optional($cupon->fecha_fin)->format('Y-m-d\TH:i')) }}"
                           required>
                </label>

                <label>
                    Límite total de usos
                    <input type="number" name="limite_usos_total"
                           value="{{ old('limite_usos_total', $cupon->limite_usos_total) }}"
                           min="1" placeholder="Vacío = ilimitado">
                </label>

                <label>
                    Límite de usos por usuario
                    <input type="number" name="limite_usos_por_usuario"
                           value="{{ old('limite_usos_por_usuario', $cupon->limite_usos_por_usuario) }}"
                           min="1">
                </label>

                <label>
                    Estado *
                    <select name="estado" required>
                        <option value="1" {{ old('estado', $cupon->estado) == '1' ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ old('estado', $cupon->estado) == '0' ? 'selected' : '' }}>Inactivo</option>
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
                            @php
                                $seleccionados = old('eventos', $eventosSeleccionados);
                            @endphp
                            <label style="display:flex;align-items:center;gap:10px;padding:8px 10px;
                                          cursor:pointer;border-radius:6px;transition:background 0.15s;"
                                   onmouseover="this.style.background='rgba(124,58,237,0.12)'"
                                   onmouseout="this.style.background='transparent'">
                                <input type="checkbox" name="eventos[]" value="{{ $ev->id }}"
                                       {{ in_array($ev->id, $seleccionados) ? 'checked' : '' }}
                                       style="width:16px;height:16px;accent-color:#7c3aed;flex-shrink:0;">
                                <span style="font-size:0.88rem;font-weight:500;">
                                    {{ $ev->titulo }}
                                    <small style="color:rgba(245,241,234,0.4);margin-left:6px;">
                                        {{ optional($ev->fecha_inicio)->format('d/m/Y') }}
                                    </small>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

            </div>

            <div class="form-actions" style="margin-top:1.5rem;">
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
                <a href="{{ route('admin.cupones.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </section>
@endsection

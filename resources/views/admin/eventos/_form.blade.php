@if ($errors->any())
    <div class="alert error">
        <strong>Revisa los campos:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid-form">
    <div>
        <label class="form-label">Organizador</label>
        @php $orgAct = old('organizador_id', $evento->organizador_id ?? ''); @endphp
        <input type="hidden" id="inp-adm-org" name="organizador_id" value="{{ $orgAct }}">
        <div class="ev-csel" id="ev-adm-org">
            <div class="ev-csel-trigger" onclick="cselToggle('ev-adm-org')">
                <span id="ev-adm-org-label" class="{{ $orgAct ? '' : 'ev-csel-placeholder' }}">
                    @if($orgAct)
                        @php $orgSel = $organizadores->firstWhere('id', $orgAct); @endphp
                        {{ $orgSel?->usuario?->nombre }} {{ $orgSel?->usuario?->apellido1 }} (#{{ $orgAct }})
                    @else
                        Selecciona
                    @endif
                </span>
                <span class="ev-csel-arrow">▾</span>
            </div>
            <ul class="ev-csel-menu">
                <li class="ev-csel-opt {{ !$orgAct ? 'selected' : '' }}"
                    onclick="cselPick('ev-adm-org','inp-adm-org','','Selecciona',this)">Selecciona</li>
                @foreach ($organizadores as $organizador)
                    @php $orgLabel = ($organizador->usuario?->nombre ?? '') . ' ' . ($organizador->usuario?->apellido1 ?? '') . ' (#' . $organizador->id . ')'; @endphp
                    <li class="ev-csel-opt {{ $orgAct == $organizador->id ? 'selected' : '' }}"
                        onclick="cselPick('ev-adm-org','inp-adm-org','{{ $organizador->id }}','{{ addslashes($orgLabel) }}',this)">
                        {{ $orgLabel }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <label class="full">
        Categorías <small style="font-weight:normal;text-transform:none;letter-spacing:0;">(selecciona una o más)</small>
        <div class="cat-chips-wrap">
            @php $selectedCats = old('categorias', $evento->categorias->pluck('id')->toArray() ?? []); @endphp
            @foreach ($categorias as $categoria)
                <label class="cat-chip">
                    <input type="checkbox" name="categorias[]" value="{{ $categoria->id }}"
                           @checked(in_array($categoria->id, $selectedCats))>
                    <span class="cat-chip-label">{{ $categoria->nombre }}</span>
                </label>
            @endforeach
        </div>
    </label>

    <div>
        <label class="form-label">Tipo evento</label>
        @php $tipoEvAdm = old('tipo_evento', $evento->tipo_evento ?? 1); @endphp
        <input type="hidden" id="inp-adm-tipo-ev" name="tipo_evento" value="{{ $tipoEvAdm }}">
        <div class="ev-csel" id="ev-adm-tipo-ev">
            <div class="ev-csel-trigger" onclick="cselToggle('ev-adm-tipo-ev')">
                <span id="ev-adm-tipo-ev-label">{{ $tipoEvAdm == 2 ? 'Online' : 'Presencial' }}</span>
                <span class="ev-csel-arrow">▾</span>
            </div>
            <ul class="ev-csel-menu">
                <li class="ev-csel-opt {{ $tipoEvAdm != 2 ? 'selected' : '' }}"
                    onclick="cselPick('ev-adm-tipo-ev','inp-adm-tipo-ev','1','Presencial',this)">Presencial</li>
                <li class="ev-csel-opt {{ $tipoEvAdm == 2 ? 'selected' : '' }}"
                    onclick="cselPick('ev-adm-tipo-ev','inp-adm-tipo-ev','2','Online',this)">Online</li>
            </ul>
        </div>
    </div>

    <div>
        <label class="form-label">Estado</label>
        @php $estEvAdm = old('estado', $evento->estado ?? 1); @endphp
        <input type="hidden" id="inp-adm-est-ev" name="estado" value="{{ $estEvAdm }}">
        <div class="ev-csel" id="ev-adm-est-ev">
            <div class="ev-csel-trigger" onclick="cselToggle('ev-adm-est-ev')">
                <span id="ev-adm-est-ev-label">{{ $estEvAdm == 1 ? 'Activo' : 'Inactivo' }}</span>
                <span class="ev-csel-arrow">▾</span>
            </div>
            <ul class="ev-csel-menu">
                <li class="ev-csel-opt {{ $estEvAdm == 1 ? 'selected' : '' }}"
                    onclick="cselPick('ev-adm-est-ev','inp-adm-est-ev','1','Activo',this)">Activo</li>
                <li class="ev-csel-opt {{ $estEvAdm != 1 ? 'selected' : '' }}"
                    onclick="cselPick('ev-adm-est-ev','inp-adm-est-ev','0','Inactivo',this)">Inactivo</li>
            </ul>
        </div>
    </div>

    <label class="full">
        Titulo
        <input type="text" name="titulo" maxlength="300" value="{{ old('titulo', $evento->titulo) }}">
    </label>

    <label class="full">
        Descripcion
        <textarea name="descripcion" rows="4">{{ old('descripcion', $evento->descripcion) }}</textarea>
    </label>

    <div>
        <label class="form-label">Inicio</label>
        <input type="text" name="fecha_inicio" class="form-input adm-fp-datetime" value="{{ old('fecha_inicio', optional($evento->fecha_inicio)->format('Y-m-d H:i')) }}">
    </div>

    <div>
        <label class="form-label">Fin</label>
        <input type="text" name="fecha_fin" class="form-input adm-fp-datetime" value="{{ old('fecha_fin', optional($evento->fecha_fin)->format('Y-m-d H:i')) }}">
    </div>

    <label>
        Nombre ubicacion
        <input type="text" name="ubicacion_nombre" maxlength="300" value="{{ old('ubicacion_nombre', $evento->ubicacion_nombre) }}">
    </label>

    <label>
        Direccion
        <input type="text" name="ubicacion_direccion" maxlength="500" value="{{ old('ubicacion_direccion', $evento->ubicacion_direccion) }}">
    </label>

    <label>
        Latitud
        <input type="number" step="0.0000001" name="latitud" value="{{ old('latitud', $evento->latitud) }}">
    </label>

    <label>
        Longitud
        <input type="number" step="0.0000001" name="longitud" value="{{ old('longitud', $evento->longitud) }}">
    </label>

    <label>
        Precio base
        <input type="number" min="0" step="0.01" id="precio_base" name="precio_base" value="{{ old('precio_base', $evento->precio_base ?? 0) }}">
    </label>

    <label>
        Aforo maximo
        <input type="number" min="1" name="aforo_maximo" value="{{ old('aforo_maximo', $evento->aforo_maximo) }}">
    </label>

    <label>
        Aforo actual
        <input type="number" min="0" name="aforo_actual" value="{{ old('aforo_actual', $evento->aforo_actual ?? 0) }}">
    </label>

    <label>
        Edad minima
        <input type="number" min="0" max="120" name="edad_minima" value="{{ old('edad_minima', $evento->edad_minima) }}">
    </label>

    <label>
        URL externa
        <input type="url" maxlength="500" name="url_externa" value="{{ old('url_externa', $evento->url_externa) }}">
    </label>

    <label class="checkbox-wrap" for="es_gratuito">
        <input type="checkbox" id="es_gratuito" name="es_gratuito" value="1" @checked(old('es_gratuito', $evento->es_gratuito))>
        Es gratuito
    </label>

</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="{{ route('admin.eventos.index') }}" class="btn btn-secondary">Cancelar</a>
</div>

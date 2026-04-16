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
    <label>
        Organizador
        <select name="organizador_id">
            <option value="">Selecciona</option>
            @foreach ($organizadores as $organizador)
                <option value="{{ $organizador->id }}" @selected(old('organizador_id', $evento->organizador_id) == $organizador->id)>
                    Organizador #{{ $organizador->id }}
                </option>
            @endforeach
        </select>
    </label>

    <label>
        Categoria
        <select name="categoria_evento_id">
            <option value="">Selecciona</option>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}" @selected(old('categoria_evento_id', $evento->categoria_evento_id) == $categoria->id)>
                    {{ $categoria->nombre }}
                </option>
            @endforeach
        </select>
    </label>

    <label>
        Tipo evento
        <select name="tipo_evento">
            <option value="1" @selected(old('tipo_evento', $evento->tipo_evento ?? 1) == 1)>Presencial</option>
            <option value="2" @selected(old('tipo_evento', $evento->tipo_evento ?? 1) == 2)>Online</option>
        </select>
    </label>

    <label>
        Estado
        <select name="estado">
            <option value="1" @selected(old('estado', $evento->estado ?? 1) == 1)>Activo</option>
            <option value="0" @selected(old('estado', $evento->estado ?? 1) == 0)>Inactivo</option>
        </select>
    </label>

    <label class="full">
        Titulo
        <input type="text" name="titulo" maxlength="300" value="{{ old('titulo', $evento->titulo) }}">
    </label>

    <label class="full">
        Descripcion
        <textarea name="descripcion" rows="4">{{ old('descripcion', $evento->descripcion) }}</textarea>
    </label>

    <label>
        Inicio
        <input type="datetime-local" name="fecha_inicio" value="{{ old('fecha_inicio', optional($evento->fecha_inicio)->format('Y-m-d\TH:i')) }}">
    </label>

    <label>
        Fin
        <input type="datetime-local" name="fecha_fin" value="{{ old('fecha_fin', optional($evento->fecha_fin)->format('Y-m-d\TH:i')) }}">
    </label>

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

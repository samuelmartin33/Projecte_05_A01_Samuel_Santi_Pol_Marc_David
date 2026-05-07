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
    <label class="full">
        Nombre
        <input type="text" name="nombre" maxlength="191" value="{{ old('nombre', $categoria->nombre) }}">
    </label>

    <label class="full">
        Descripción
        <textarea name="descripcion" rows="4">{{ old('descripcion', $categoria->descripcion) }}</textarea>
    </label>

    <label>
        Estado
        <select name="estado">
            <option value="1" @selected(old('estado', $categoria->estado ?? 1) == 1)>Activo</option>
            <option value="0" @selected(old('estado', $categoria->estado ?? 1) == 0)>Inactivo</option>
        </select>
    </label>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="{{ route('admin.categorias.index') }}" class="btn btn-secondary">Cancelar</a>
</div>
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
        Usuario
        <select name="usuario_id">
            <option value="">Selecciona</option>
            @foreach ($usuarios as $usuario)
                <option value="{{ $usuario->id }}" @selected(old('usuario_id', $pedido->usuario_id) == $usuario->id)>
                    #{{ $usuario->id }} - {{ $usuario->nombre }} {{ $usuario->apellido1 }}
                </option>
            @endforeach
        </select>
    </label>

    <label>
        Total
        <input type="number" step="0.01" min="0" name="total" value="{{ old('total', $pedido->total ?? 0) }}">
    </label>

    <label>
        Descuento
        <input type="number" step="0.01" min="0" name="total_descuento" value="{{ old('total_descuento', $pedido->total_descuento ?? 0) }}">
    </label>

    <label>
        Total final
        <input type="number" step="0.01" min="0" name="total_final" value="{{ old('total_final', $pedido->total_final ?? 0) }}">
    </label>

    <label>
        Estado
        <select name="estado">
            <option value="1" @selected(old('estado', $pedido->estado ?? 1) == 1)>Activo</option>
            <option value="0" @selected(old('estado', $pedido->estado ?? 1) == 0)>Inactivo</option>
        </select>
    </label>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="{{ route('admin.pedidos.index') }}" class="btn btn-secondary">Cancelar</a>
</div>
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
        Pedido
        <select name="pedido_id">
            <option value="">Selecciona</option>
            @foreach ($pedidos as $pedidoItem)
                <option value="{{ $pedidoItem->id }}" @selected(old('pedido_id', $pago->pedido_id) == $pedidoItem->id)>
                    #{{ $pedidoItem->id }} - {{ $pedidoItem->usuario?->nombre }} {{ $pedidoItem->usuario?->apellido1 }}
                </option>
            @endforeach
        </select>
    </label>

    <label>
        Método pago
        <select name="metodo_pago">
            <option value="1" @selected(old('metodo_pago', $pago->metodo_pago ?? 1) == 1)>Tarjeta</option>
            <option value="2" @selected(old('metodo_pago', $pago->metodo_pago ?? 1) == 2)>Transferencia</option>
            <option value="3" @selected(old('metodo_pago', $pago->metodo_pago ?? 1) == 3)>PayPal</option>
            <option value="4" @selected(old('metodo_pago', $pago->metodo_pago ?? 1) == 4)>Efectivo</option>
        </select>
    </label>

    <label>
        Estado pago
        <select name="estado_pago">
            <option value="1" @selected(old('estado_pago', $pago->estado_pago ?? 1) == 1)>Pendiente</option>
            <option value="2" @selected(old('estado_pago', $pago->estado_pago ?? 1) == 2)>Completado</option>
            <option value="3" @selected(old('estado_pago', $pago->estado_pago ?? 1) == 3)>Fallido</option>
        </select>
    </label>

    <label>
        Importe
        <input type="number" step="0.01" min="0" name="importe" value="{{ old('importe', $pago->importe ?? 0) }}">
    </label>

    <label>
        Moneda
        <input type="text" name="moneda" maxlength="3" value="{{ old('moneda', $pago->moneda ?? 'EUR') }}">
    </label>

    <label>
        Fecha pago
        <input type="datetime-local" name="fecha_pago" value="{{ old('fecha_pago', optional($pago->fecha_pago)->format('Y-m-d\TH:i')) }}">
    </label>

    <label>
        Fecha reembolso
        <input type="datetime-local" name="fecha_reembolso" value="{{ old('fecha_reembolso', optional($pago->fecha_reembolso)->format('Y-m-d\TH:i')) }}">
    </label>

    <label>
        Importe reembolso
        <input type="number" step="0.01" min="0" name="importe_reembolso" value="{{ old('importe_reembolso', $pago->importe_reembolso) }}">
    </label>

    <label class="full">
        Motivo reembolso
        <textarea name="motivo_reembolso" rows="3">{{ old('motivo_reembolso', $pago->motivo_reembolso) }}</textarea>
    </label>

    <label>
        Estado
        <select name="estado">
            <option value="1" @selected(old('estado', $pago->estado ?? 1) == 1)>Activo</option>
            <option value="0" @selected(old('estado', $pago->estado ?? 1) == 0)>Inactivo</option>
        </select>
    </label>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="{{ route('admin.pagos.index') }}" class="btn btn-secondary">Cancelar</a>
</div>
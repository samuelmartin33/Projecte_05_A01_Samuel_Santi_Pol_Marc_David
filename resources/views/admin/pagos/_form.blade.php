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
    <div class="full">
        <label class="form-label">Pedido</label>
        @php $pedidoAct = old('pedido_id', $pago->pedido_id ?? ''); @endphp
        <input type="hidden" id="inp-adm-pedido" name="pedido_id" value="{{ $pedidoAct }}">
        <div class="ev-csel" id="ev-adm-pedido">
            <div class="ev-csel-trigger" onclick="cselToggle('ev-adm-pedido')">
                <span id="ev-adm-pedido-label" class="{{ $pedidoAct ? '' : 'ev-csel-placeholder' }}">
                    @if($pedidoAct)
                        @php $pedSel = $pedidos->firstWhere('id', $pedidoAct); @endphp
                        #{{ $pedidoAct }} - {{ $pedSel?->usuario?->nombre }} {{ $pedSel?->usuario?->apellido1 }}
                    @else
                        Selecciona
                    @endif
                </span>
                <span class="ev-csel-arrow">▾</span>
            </div>
            <ul class="ev-csel-menu">
                <li class="ev-csel-opt {{ !$pedidoAct ? 'selected' : '' }}"
                    onclick="cselPick('ev-adm-pedido','inp-adm-pedido','','Selecciona',this)">Selecciona</li>
                @foreach ($pedidos as $pedidoItem)
                    @php $pedLabel = '#' . $pedidoItem->id . ' - ' . ($pedidoItem->usuario?->nombre ?? '') . ' ' . ($pedidoItem->usuario?->apellido1 ?? ''); @endphp
                    <li class="ev-csel-opt {{ $pedidoAct == $pedidoItem->id ? 'selected' : '' }}"
                        onclick="cselPick('ev-adm-pedido','inp-adm-pedido','{{ $pedidoItem->id }}','{{ addslashes($pedLabel) }}',this)">
                        {{ $pedLabel }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div>
        <label class="form-label">Método pago</label>
        @php $metPagoAct = old('metodo_pago', $pago->metodo_pago ?? 1); @endphp
        @php $metLabels = [1=>'Tarjeta',2=>'Transferencia',3=>'PayPal',4=>'Efectivo']; @endphp
        <input type="hidden" id="inp-adm-met-pago" name="metodo_pago" value="{{ $metPagoAct }}">
        <div class="ev-csel" id="ev-adm-met-pago">
            <div class="ev-csel-trigger" onclick="cselToggle('ev-adm-met-pago')">
                <span id="ev-adm-met-pago-label">{{ $metLabels[$metPagoAct] ?? 'Tarjeta' }}</span>
                <span class="ev-csel-arrow">▾</span>
            </div>
            <ul class="ev-csel-menu">
                @foreach($metLabels as $val => $lab)
                    <li class="ev-csel-opt {{ $metPagoAct == $val ? 'selected' : '' }}"
                        onclick="cselPick('ev-adm-met-pago','inp-adm-met-pago','{{ $val }}','{{ $lab }}',this)">{{ $lab }}</li>
                @endforeach
            </ul>
        </div>
    </div>

    <div>
        <label class="form-label">Estado pago</label>
        @php $estPagoAct = old('estado_pago', $pago->estado_pago ?? 1); @endphp
        @php $estPagoLabels = [1=>'Pendiente',2=>'Completado',3=>'Fallido']; @endphp
        <input type="hidden" id="inp-adm-est-pago" name="estado_pago" value="{{ $estPagoAct }}">
        <div class="ev-csel" id="ev-adm-est-pago">
            <div class="ev-csel-trigger" onclick="cselToggle('ev-adm-est-pago')">
                <span id="ev-adm-est-pago-label">{{ $estPagoLabels[$estPagoAct] ?? 'Pendiente' }}</span>
                <span class="ev-csel-arrow">▾</span>
            </div>
            <ul class="ev-csel-menu">
                @foreach($estPagoLabels as $val => $lab)
                    <li class="ev-csel-opt {{ $estPagoAct == $val ? 'selected' : '' }}"
                        onclick="cselPick('ev-adm-est-pago','inp-adm-est-pago','{{ $val }}','{{ $lab }}',this)">{{ $lab }}</li>
                @endforeach
            </ul>
        </div>
    </div>

    <label>
        Importe
        <input type="number" step="0.01" min="0" name="importe" value="{{ old('importe', $pago->importe ?? 0) }}">
    </label>

    <label>
        Moneda
        <input type="text" name="moneda" maxlength="3" value="{{ old('moneda', $pago->moneda ?? 'EUR') }}">
    </label>

    <div>
        <label class="form-label">Fecha pago</label>
        <input type="text" name="fecha_pago" class="form-input adm-fp-datetime" value="{{ old('fecha_pago', optional($pago->fecha_pago)->format('Y-m-d H:i')) }}">
    </div>

    <div>
        <label class="form-label">Fecha reembolso</label>
        <input type="text" name="fecha_reembolso" class="form-input adm-fp-datetime" value="{{ old('fecha_reembolso', optional($pago->fecha_reembolso)->format('Y-m-d H:i')) }}">
    </div>

    <label>
        Importe reembolso
        <input type="number" step="0.01" min="0" name="importe_reembolso" value="{{ old('importe_reembolso', $pago->importe_reembolso) }}">
    </label>

    <label class="full">
        Motivo reembolso
        <textarea name="motivo_reembolso" rows="3">{{ old('motivo_reembolso', $pago->motivo_reembolso) }}</textarea>
    </label>

    <div>
        <label class="form-label">Estado</label>
        @php $estPagoGen = old('estado', $pago->estado ?? 1); @endphp
        <input type="hidden" id="inp-adm-est-gen" name="estado" value="{{ $estPagoGen }}">
        <div class="ev-csel" id="ev-adm-est-gen">
            <div class="ev-csel-trigger" onclick="cselToggle('ev-adm-est-gen')">
                <span id="ev-adm-est-gen-label">{{ $estPagoGen == 1 ? 'Activo' : 'Inactivo' }}</span>
                <span class="ev-csel-arrow">▾</span>
            </div>
            <ul class="ev-csel-menu">
                <li class="ev-csel-opt {{ $estPagoGen == 1 ? 'selected' : '' }}"
                    onclick="cselPick('ev-adm-est-gen','inp-adm-est-gen','1','Activo',this)">Activo</li>
                <li class="ev-csel-opt {{ $estPagoGen != 1 ? 'selected' : '' }}"
                    onclick="cselPick('ev-adm-est-gen','inp-adm-est-gen','0','Inactivo',this)">Inactivo</li>
            </ul>
        </div>
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="{{ route('admin.pagos.index') }}" class="btn btn-secondary">Cancelar</a>
</div>
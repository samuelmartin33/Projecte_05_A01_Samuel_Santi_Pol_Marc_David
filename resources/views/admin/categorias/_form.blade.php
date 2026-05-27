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

    <div>
        <label class="form-label">Estado</label>
        @php $estCatAdm = old('estado', $categoria->estado ?? 1); @endphp
        <input type="hidden" id="inp-adm-est-cat" name="estado" value="{{ $estCatAdm }}">
        <div class="ev-csel" id="ev-adm-est-cat">
            <div class="ev-csel-trigger" onclick="cselToggle('ev-adm-est-cat')">
                <span id="ev-adm-est-cat-label">{{ $estCatAdm == 1 ? 'Activo' : 'Inactivo' }}</span>
                <span class="ev-csel-arrow">▾</span>
            </div>
            <ul class="ev-csel-menu">
                <li class="ev-csel-opt {{ $estCatAdm == 1 ? 'selected' : '' }}"
                    onclick="cselPick('ev-adm-est-cat','inp-adm-est-cat','1','Activo',this)">Activo</li>
                <li class="ev-csel-opt {{ $estCatAdm != 1 ? 'selected' : '' }}"
                    onclick="cselPick('ev-adm-est-cat','inp-adm-est-cat','0','Inactivo',this)">Inactivo</li>
            </ul>
        </div>
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="{{ route('admin.categorias.index') }}" class="btn btn-secondary">Cancelar</a>
</div>
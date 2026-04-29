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
        <input type="text" name="nombre" maxlength="100" value="{{ old('nombre', $usuario->nombre) }}">
    </label>

    <label>
        Primer apellido
        <input type="text" name="apellido1" maxlength="150" value="{{ old('apellido1', $usuario->apellido1) }}">
    </label>

    <label>
        Segundo apellido
        <input type="text" name="apellido2" maxlength="150" value="{{ old('apellido2', $usuario->apellido2) }}">
    </label>

    <label class="full">
        Email
        <input type="email" name="email" maxlength="255" value="{{ old('email', $usuario->email) }}">
    </label>

    <label class="full">
        Contraseña {{ $usuario->exists ? '(dejar en blanco para conservar)' : '' }}
        <input type="password" name="password_hash" minlength="8" {{ $usuario->exists ? '' : 'required' }}>
    </label>

    <label>
        Tipo de cuenta
        <select name="tipo_cuenta">
            <option value="cliente" @selected(old('tipo_cuenta', $usuario->tipo_cuenta ?? 'cliente') === 'cliente')>Cliente</option>
            <option value="empresa" @selected(old('tipo_cuenta', $usuario->tipo_cuenta ?? 'cliente') === 'empresa')>Empresa</option>
        </select>
    </label>

    <label>
        Estado registro
        <select name="estado_registro">
            <option value="pendiente" @selected(old('estado_registro', $usuario->estado_registro ?? 'aprobado') === 'pendiente')>Pendiente</option>
            <option value="aprobado" @selected(old('estado_registro', $usuario->estado_registro ?? 'aprobado') === 'aprobado')>Aprobado</option>
            <option value="rechazado" @selected(old('estado_registro', $usuario->estado_registro ?? 'aprobado') === 'rechazado')>Rechazado</option>
        </select>
    </label>

    <label>
        Fecha de nacimiento
        <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $usuario->fecha_nacimiento) }}">
    </label>

    <label>
        Teléfono
        <input type="text" name="telefono" maxlength="20" value="{{ old('telefono', $usuario->telefono) }}">
    </label>

    <label class="full">
        Foto URL
        <input type="url" name="foto_url" maxlength="500" value="{{ old('foto_url', $usuario->foto_url) }}">
    </label>

    <label class="full">
        Biografía
        <textarea name="biografia" rows="4">{{ old('biografia', $usuario->biografia) }}</textarea>
    </label>

    <label class="checkbox-wrap" for="email_verificado">
        <input type="hidden" name="email_verificado" value="0">
        <input type="checkbox" id="email_verificado" name="email_verificado" value="1" @checked(old('email_verificado', $usuario->email_verificado))>
        Email verificado
    </label>

    <label class="checkbox-wrap" for="es_admin">
        <input type="hidden" name="es_admin" value="0">
        <input type="checkbox" id="es_admin" name="es_admin" value="1" @checked(old('es_admin', $usuario->es_admin))>
        Es administrador
    </label>

    <label>
        Estado
        <select name="estado">
            <option value="1" @selected(old('estado', $usuario->estado ?? 1) == 1)>Activo</option>
            <option value="0" @selected(old('estado', $usuario->estado ?? 1) == 0)>Inactivo</option>
        </select>
    </label>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
</div>
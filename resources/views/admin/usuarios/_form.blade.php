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

    <div>
        <label class="form-label">Tipo de cuenta</label>
        @php $tipoCuentaAdm = old('tipo_cuenta', $usuario->tipo_cuenta ?? 'cliente'); @endphp
        <input type="hidden" id="tipo_cuenta" name="tipo_cuenta" value="{{ $tipoCuentaAdm }}">
        <div class="ev-csel" id="ev-adm-tipo-cta">
            <div class="ev-csel-trigger" onclick="cselToggle('ev-adm-tipo-cta')">
                <span id="ev-adm-tipo-cta-label">{{ $tipoCuentaAdm === 'empresa' ? 'Empresa' : 'Cliente' }}</span>
                <span class="ev-csel-arrow">▾</span>
            </div>
            <ul class="ev-csel-menu">
                <li class="ev-csel-opt {{ $tipoCuentaAdm === 'cliente' ? 'selected' : '' }}"
                    onclick="cselPick('ev-adm-tipo-cta','tipo_cuenta','cliente','Cliente',this);vibezActualizarRoles()">Cliente</li>
                <li class="ev-csel-opt {{ $tipoCuentaAdm === 'empresa' ? 'selected' : '' }}"
                    onclick="cselPick('ev-adm-tipo-cta','tipo_cuenta','empresa','Empresa',this);vibezActualizarRoles()">Empresa</li>
            </ul>
        </div>
    </div>

    <div>
        <label class="form-label">Estado registro</label>
        @php $estRegAdm = old('estado_registro', $usuario->estado_registro ?? 'aprobado'); @endphp
        <input type="hidden" id="inp-adm-est-reg" name="estado_registro" value="{{ $estRegAdm }}">
        <div class="ev-csel" id="ev-adm-est-reg">
            <div class="ev-csel-trigger" onclick="cselToggle('ev-adm-est-reg')">
                <span id="ev-adm-est-reg-label">{{ $estRegAdm === 'pendiente' ? 'Pendiente' : ($estRegAdm === 'rechazado' ? 'Rechazado' : 'Aprobado') }}</span>
                <span class="ev-csel-arrow">▾</span>
            </div>
            <ul class="ev-csel-menu">
                <li class="ev-csel-opt {{ $estRegAdm === 'pendiente' ? 'selected' : '' }}"
                    onclick="cselPick('ev-adm-est-reg','inp-adm-est-reg','pendiente','Pendiente',this)">Pendiente</li>
                <li class="ev-csel-opt {{ $estRegAdm === 'aprobado' ? 'selected' : '' }}"
                    onclick="cselPick('ev-adm-est-reg','inp-adm-est-reg','aprobado','Aprobado',this)">Aprobado</li>
                <li class="ev-csel-opt {{ $estRegAdm === 'rechazado' ? 'selected' : '' }}"
                    onclick="cselPick('ev-adm-est-reg','inp-adm-est-reg','rechazado','Rechazado',this)">Rechazado</li>
            </ul>
        </div>
    </div>

    <div>
        <label class="form-label">Fecha de nacimiento</label>
        <input type="text" name="fecha_nacimiento" class="form-input adm-fp-date" value="{{ old('fecha_nacimiento', $usuario->fecha_nacimiento) }}">
    </div>

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

    <label class="checkbox-wrap" for="es_admin" id="wrap-es-admin">
        <input type="hidden" name="es_admin" value="0">
        <input type="checkbox" id="es_admin" name="es_admin" value="1"
               @checked(old('es_admin', $usuario->es_admin))
               onchange="vibezActualizarRoles()">
        Es administrador
    </label>

    <label class="checkbox-wrap" for="es_moderador" id="wrap-es-moderador">
        <input type="hidden" name="es_moderador" value="0">
        <input type="checkbox" id="es_moderador" name="es_moderador" value="1"
               @checked(old('es_moderador', $usuario->es_moderador ?? false))>
        Es moderador
    </label>

    <div>
        <label class="form-label">Estado</label>
        @php $estUsrAdm = old('estado', $usuario->estado ?? 1); @endphp
        <input type="hidden" id="inp-adm-est-usr" name="estado" value="{{ $estUsrAdm }}">
        <div class="ev-csel" id="ev-adm-est-usr">
            <div class="ev-csel-trigger" onclick="cselToggle('ev-adm-est-usr')">
                <span id="ev-adm-est-usr-label">{{ $estUsrAdm == 1 ? 'Activo' : 'Inactivo' }}</span>
                <span class="ev-csel-arrow">▾</span>
            </div>
            <ul class="ev-csel-menu">
                <li class="ev-csel-opt {{ $estUsrAdm == 1 ? 'selected' : '' }}"
                    onclick="cselPick('ev-adm-est-usr','inp-adm-est-usr','1','Activo',this)">Activo</li>
                <li class="ev-csel-opt {{ $estUsrAdm != 1 ? 'selected' : '' }}"
                    onclick="cselPick('ev-adm-est-usr','inp-adm-est-usr','0','Inactivo',this)">Inactivo</li>
            </ul>
        </div>
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
</div>

@push('scripts')
<script>
/**
 * Actualiza el estado de los checkboxes de rol según el tipo de cuenta y si es admin.
 * - Empresa: no puede ser admin ni moderador.
 * - Admin: no puede ser moderador al mismo tiempo.
 */
function vibezActualizarRoles() {
    var tipo      = document.getElementById('tipo_cuenta').value;
    var chkAdmin  = document.getElementById('es_admin');
    var chkMod    = document.getElementById('es_moderador');
    var wrapAdmin = document.getElementById('wrap-es-admin');
    var wrapMod   = document.getElementById('wrap-es-moderador');

    if (tipo === 'empresa') {
        /* Empresa: desactivar y desmarcar ambos roles */
        chkAdmin.checked  = false;
        chkAdmin.disabled = true;
        chkMod.checked    = false;
        chkMod.disabled   = true;
        wrapAdmin.style.opacity = '0.4';
        wrapAdmin.style.pointerEvents = 'none';
        wrapMod.style.opacity = '0.4';
        wrapMod.style.pointerEvents = 'none';
    } else {
        /* Cliente: habilitar admin */
        chkAdmin.disabled = false;
        wrapAdmin.style.opacity = '';
        wrapAdmin.style.pointerEvents = '';

        if (chkAdmin.checked) {
            /* Admin activo: deshabilitar moderador */
            chkMod.checked  = false;
            chkMod.disabled = true;
            wrapMod.style.opacity = '0.4';
            wrapMod.style.pointerEvents = 'none';
        } else {
            /* No es admin: habilitar moderador */
            chkMod.disabled = false;
            wrapMod.style.opacity = '';
            wrapMod.style.pointerEvents = '';
        }
    }
}

/* Sincronizar estado al cargar la página */
vibezActualizarRoles();
</script>
@endpush
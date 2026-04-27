<?php if($errors->any()): ?>
    <div class="alert error">
        <strong>Revisa los campos:</strong>
        <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<div class="grid-form">
    <label class="full">
        Nombre
        <input type="text" name="nombre" maxlength="100" value="<?php echo e(old('nombre', $usuario->nombre)); ?>">
    </label>

    <label>
        Primer apellido
        <input type="text" name="apellido1" maxlength="150" value="<?php echo e(old('apellido1', $usuario->apellido1)); ?>">
    </label>

    <label>
        Segundo apellido
        <input type="text" name="apellido2" maxlength="150" value="<?php echo e(old('apellido2', $usuario->apellido2)); ?>">
    </label>

    <label class="full">
        Email
        <input type="email" name="email" maxlength="255" value="<?php echo e(old('email', $usuario->email)); ?>">
    </label>

    <label class="full">
        Contraseña <?php echo e($usuario->exists ? '(dejar en blanco para conservar)' : ''); ?>

        <input type="password" name="password_hash" minlength="8" <?php echo e($usuario->exists ? '' : 'required'); ?>>
    </label>

    <label>
        Tipo de cuenta
        <select name="tipo_cuenta">
            <option value="cliente" <?php if(old('tipo_cuenta', $usuario->tipo_cuenta ?? 'cliente') === 'cliente'): echo 'selected'; endif; ?>>Cliente</option>
            <option value="empresa" <?php if(old('tipo_cuenta', $usuario->tipo_cuenta ?? 'cliente') === 'empresa'): echo 'selected'; endif; ?>>Empresa</option>
        </select>
    </label>

    <label>
        Estado registro
        <select name="estado_registro">
            <option value="pendiente" <?php if(old('estado_registro', $usuario->estado_registro ?? 'aprobado') === 'pendiente'): echo 'selected'; endif; ?>>Pendiente</option>
            <option value="aprobado" <?php if(old('estado_registro', $usuario->estado_registro ?? 'aprobado') === 'aprobado'): echo 'selected'; endif; ?>>Aprobado</option>
            <option value="rechazado" <?php if(old('estado_registro', $usuario->estado_registro ?? 'aprobado') === 'rechazado'): echo 'selected'; endif; ?>>Rechazado</option>
        </select>
    </label>

    <label>
        Fecha de nacimiento
        <input type="date" name="fecha_nacimiento" value="<?php echo e(old('fecha_nacimiento', $usuario->fecha_nacimiento)); ?>">
    </label>

    <label>
        Teléfono
        <input type="text" name="telefono" maxlength="20" value="<?php echo e(old('telefono', $usuario->telefono)); ?>">
    </label>

    <label class="full">
        Foto URL
        <input type="url" name="foto_url" maxlength="500" value="<?php echo e(old('foto_url', $usuario->foto_url)); ?>">
    </label>

    <label class="full">
        Biografía
        <textarea name="biografia" rows="4"><?php echo e(old('biografia', $usuario->biografia)); ?></textarea>
    </label>

    <label class="checkbox-wrap" for="email_verificado">
        <input type="hidden" name="email_verificado" value="0">
        <input type="checkbox" id="email_verificado" name="email_verificado" value="1" <?php if(old('email_verificado', $usuario->email_verificado)): echo 'checked'; endif; ?>>
        Email verificado
    </label>

    <label class="checkbox-wrap" for="es_admin">
        <input type="hidden" name="es_admin" value="0">
        <input type="checkbox" id="es_admin" name="es_admin" value="1" <?php if(old('es_admin', $usuario->es_admin)): echo 'checked'; endif; ?>>
        Es administrador
    </label>

    <label>
        Estado
        <select name="estado">
            <option value="1" <?php if(old('estado', $usuario->estado ?? 1) == 1): echo 'selected'; endif; ?>>Activo</option>
            <option value="0" <?php if(old('estado', $usuario->estado ?? 1) == 0): echo 'selected'; endif; ?>>Inactivo</option>
        </select>
    </label>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="<?php echo e(route('admin.usuarios.index')); ?>" class="btn btn-secondary">Cancelar</a>
</div><?php /**PATH C:\wamp64\www\DAW2\0616\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/admin/usuarios/_form.blade.php ENDPATH**/ ?>
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
    <label>
        Organizador
        <select name="organizador_id">
            <option value="">Selecciona</option>
            <?php $__currentLoopData = $organizadores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $organizador): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($organizador->id); ?>" <?php if(old('organizador_id', $evento->organizador_id) == $organizador->id): echo 'selected'; endif; ?>>
                    Organizador #<?php echo e($organizador->id); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </label>

    <label>
        Categoria
        <select name="categoria_evento_id">
            <option value="">Selecciona</option>
            <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($categoria->id); ?>" <?php if(old('categoria_evento_id', $evento->categoria_evento_id) == $categoria->id): echo 'selected'; endif; ?>>
                    <?php echo e($categoria->nombre); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </label>

    <label>
        Tipo evento
        <select name="tipo_evento">
            <option value="1" <?php if(old('tipo_evento', $evento->tipo_evento ?? 1) == 1): echo 'selected'; endif; ?>>Presencial</option>
            <option value="2" <?php if(old('tipo_evento', $evento->tipo_evento ?? 1) == 2): echo 'selected'; endif; ?>>Online</option>
        </select>
    </label>

    <label>
        Estado
        <select name="estado">
            <option value="1" <?php if(old('estado', $evento->estado ?? 1) == 1): echo 'selected'; endif; ?>>Activo</option>
            <option value="0" <?php if(old('estado', $evento->estado ?? 1) == 0): echo 'selected'; endif; ?>>Inactivo</option>
        </select>
    </label>

    <label class="full">
        Titulo
        <input type="text" name="titulo" maxlength="300" value="<?php echo e(old('titulo', $evento->titulo)); ?>">
    </label>

    <label class="full">
        Descripcion
        <textarea name="descripcion" rows="4"><?php echo e(old('descripcion', $evento->descripcion)); ?></textarea>
    </label>

    <label>
        Inicio
        <input type="datetime-local" name="fecha_inicio" value="<?php echo e(old('fecha_inicio', optional($evento->fecha_inicio)->format('Y-m-d\TH:i'))); ?>">
    </label>

    <label>
        Fin
        <input type="datetime-local" name="fecha_fin" value="<?php echo e(old('fecha_fin', optional($evento->fecha_fin)->format('Y-m-d\TH:i'))); ?>">
    </label>

    <label>
        Nombre ubicacion
        <input type="text" name="ubicacion_nombre" maxlength="300" value="<?php echo e(old('ubicacion_nombre', $evento->ubicacion_nombre)); ?>">
    </label>

    <label>
        Direccion
        <input type="text" name="ubicacion_direccion" maxlength="500" value="<?php echo e(old('ubicacion_direccion', $evento->ubicacion_direccion)); ?>">
    </label>

    <label>
        Latitud
        <input type="number" step="0.0000001" name="latitud" value="<?php echo e(old('latitud', $evento->latitud)); ?>">
    </label>

    <label>
        Longitud
        <input type="number" step="0.0000001" name="longitud" value="<?php echo e(old('longitud', $evento->longitud)); ?>">
    </label>

    <label>
        Precio base
        <input type="number" min="0" step="0.01" id="precio_base" name="precio_base" value="<?php echo e(old('precio_base', $evento->precio_base ?? 0)); ?>">
    </label>

    <label>
        Aforo maximo
        <input type="number" min="1" name="aforo_maximo" value="<?php echo e(old('aforo_maximo', $evento->aforo_maximo)); ?>">
    </label>

    <label>
        Aforo actual
        <input type="number" min="0" name="aforo_actual" value="<?php echo e(old('aforo_actual', $evento->aforo_actual ?? 0)); ?>">
    </label>

    <label>
        Edad minima
        <input type="number" min="0" max="120" name="edad_minima" value="<?php echo e(old('edad_minima', $evento->edad_minima)); ?>">
    </label>

    <label>
        URL externa
        <input type="url" maxlength="500" name="url_externa" value="<?php echo e(old('url_externa', $evento->url_externa)); ?>">
    </label>

    <label class="checkbox-wrap" for="es_gratuito">
        <input type="checkbox" id="es_gratuito" name="es_gratuito" value="1" <?php if(old('es_gratuito', $evento->es_gratuito)): echo 'checked'; endif; ?>>
        Es gratuito
    </label>

</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="<?php echo e(route('admin.eventos.index')); ?>" class="btn btn-secondary">Cancelar</a>
</div>
<?php /**PATH C:\wamp64\www\DAW2\0616\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/admin/eventos/_form.blade.php ENDPATH**/ ?>
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
        <input type="text" name="nombre" maxlength="191" value="<?php echo e(old('nombre', $categoria->nombre)); ?>">
    </label>

    <label class="full">
        Descripción
        <textarea name="descripcion" rows="4"><?php echo e(old('descripcion', $categoria->descripcion)); ?></textarea>
    </label>

    <label>
        Estado
        <select name="estado">
            <option value="1" <?php if(old('estado', $categoria->estado ?? 1) == 1): echo 'selected'; endif; ?>>Activo</option>
            <option value="0" <?php if(old('estado', $categoria->estado ?? 1) == 0): echo 'selected'; endif; ?>>Inactivo</option>
        </select>
    </label>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="<?php echo e(route('admin.categorias.index')); ?>" class="btn btn-secondary">Cancelar</a>
</div><?php /**PATH C:\wamp64\www\DAW2\0616\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/admin/categorias/_form.blade.php ENDPATH**/ ?>
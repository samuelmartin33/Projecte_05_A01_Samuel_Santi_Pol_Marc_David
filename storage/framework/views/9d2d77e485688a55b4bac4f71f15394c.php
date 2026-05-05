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
        Usuario
        <select name="usuario_id">
            <option value="">Selecciona</option>
            <?php $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($usuario->id); ?>" <?php if(old('usuario_id', $pedido->usuario_id) == $usuario->id): echo 'selected'; endif; ?>>
                    #<?php echo e($usuario->id); ?> - <?php echo e($usuario->nombre); ?> <?php echo e($usuario->apellido1); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </label>

    <label>
        Total
        <input type="number" step="0.01" min="0" name="total" value="<?php echo e(old('total', $pedido->total ?? 0)); ?>">
    </label>

    <label>
        Descuento
        <input type="number" step="0.01" min="0" name="total_descuento" value="<?php echo e(old('total_descuento', $pedido->total_descuento ?? 0)); ?>">
    </label>

    <label>
        Total final
        <input type="number" step="0.01" min="0" name="total_final" value="<?php echo e(old('total_final', $pedido->total_final ?? 0)); ?>">
    </label>

    <label>
        Estado
        <select name="estado">
            <option value="1" <?php if(old('estado', $pedido->estado ?? 1) == 1): echo 'selected'; endif; ?>>Activo</option>
            <option value="0" <?php if(old('estado', $pedido->estado ?? 1) == 0): echo 'selected'; endif; ?>>Inactivo</option>
        </select>
    </label>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="<?php echo e(route('admin.pedidos.index')); ?>" class="btn btn-secondary">Cancelar</a>
</div><?php /**PATH C:\wamp64\www\DAW2\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views\admin\pedidos\_form.blade.php ENDPATH**/ ?>
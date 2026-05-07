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
        Pedido
        <select name="pedido_id">
            <option value="">Selecciona</option>
            <?php $__currentLoopData = $pedidos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pedidoItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($pedidoItem->id); ?>" <?php if(old('pedido_id', $pago->pedido_id) == $pedidoItem->id): echo 'selected'; endif; ?>>
                    #<?php echo e($pedidoItem->id); ?> - <?php echo e($pedidoItem->usuario?->nombre); ?> <?php echo e($pedidoItem->usuario?->apellido1); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </label>

    <label>
        Método pago
        <select name="metodo_pago">
            <option value="1" <?php if(old('metodo_pago', $pago->metodo_pago ?? 1) == 1): echo 'selected'; endif; ?>>Tarjeta</option>
            <option value="2" <?php if(old('metodo_pago', $pago->metodo_pago ?? 1) == 2): echo 'selected'; endif; ?>>Transferencia</option>
            <option value="3" <?php if(old('metodo_pago', $pago->metodo_pago ?? 1) == 3): echo 'selected'; endif; ?>>PayPal</option>
            <option value="4" <?php if(old('metodo_pago', $pago->metodo_pago ?? 1) == 4): echo 'selected'; endif; ?>>Efectivo</option>
        </select>
    </label>

    <label>
        Estado pago
        <select name="estado_pago">
            <option value="1" <?php if(old('estado_pago', $pago->estado_pago ?? 1) == 1): echo 'selected'; endif; ?>>Pendiente</option>
            <option value="2" <?php if(old('estado_pago', $pago->estado_pago ?? 1) == 2): echo 'selected'; endif; ?>>Completado</option>
            <option value="3" <?php if(old('estado_pago', $pago->estado_pago ?? 1) == 3): echo 'selected'; endif; ?>>Fallido</option>
        </select>
    </label>

    <label>
        Importe
        <input type="number" step="0.01" min="0" name="importe" value="<?php echo e(old('importe', $pago->importe ?? 0)); ?>">
    </label>

    <label>
        Moneda
        <input type="text" name="moneda" maxlength="3" value="<?php echo e(old('moneda', $pago->moneda ?? 'EUR')); ?>">
    </label>

    <label>
        Fecha pago
        <input type="datetime-local" name="fecha_pago" value="<?php echo e(old('fecha_pago', optional($pago->fecha_pago)->format('Y-m-d\TH:i'))); ?>">
    </label>

    <label>
        Fecha reembolso
        <input type="datetime-local" name="fecha_reembolso" value="<?php echo e(old('fecha_reembolso', optional($pago->fecha_reembolso)->format('Y-m-d\TH:i'))); ?>">
    </label>

    <label>
        Importe reembolso
        <input type="number" step="0.01" min="0" name="importe_reembolso" value="<?php echo e(old('importe_reembolso', $pago->importe_reembolso)); ?>">
    </label>

    <label class="full">
        Motivo reembolso
        <textarea name="motivo_reembolso" rows="3"><?php echo e(old('motivo_reembolso', $pago->motivo_reembolso)); ?></textarea>
    </label>

    <label>
        Estado
        <select name="estado">
            <option value="1" <?php if(old('estado', $pago->estado ?? 1) == 1): echo 'selected'; endif; ?>>Activo</option>
            <option value="0" <?php if(old('estado', $pago->estado ?? 1) == 0): echo 'selected'; endif; ?>>Inactivo</option>
        </select>
    </label>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="<?php echo e(route('admin.pagos.index')); ?>" class="btn btn-secondary">Cancelar</a>
</div><?php /**PATH C:\wamp64\www\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/admin/pagos/_form.blade.php ENDPATH**/ ?>
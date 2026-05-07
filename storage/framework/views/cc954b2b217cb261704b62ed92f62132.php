<?php $__env->startSection('titulo', 'Mi Perfil — VIBEZ'); ?>

<?php $__env->startPush('estilos'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/perfil.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('contenido'); ?>


<section class="perfil-hero">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex flex-col sm:flex-row items-center sm:items-end gap-6">

        
        
        <form id="formFoto" action="<?php echo e(route('perfil.foto')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div style="position:relative; display:inline-block; flex-shrink:0;">
                
                <div class="perfil-avatar-wrap" onclick="document.getElementById('inputFoto').click()">
                    <div class="perfil-avatar" id="avatarPreview">
                        <?php if($usuario->foto_url): ?>
                            <img src="<?php echo e($usuario->foto_url); ?>" alt="<?php echo e($usuario->nombre); ?>">
                        <?php else: ?>
                            <span class="perfil-avatar-iniciales">
                                <?php echo e(strtoupper(substr($usuario->nombre,0,1))); ?><?php echo e(strtoupper(substr($usuario->apellido1 ?? '',0,1))); ?>

                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="perfil-avatar-overlay">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    
                    <input type="file" id="inputFoto" name="foto" accept="image/*" style="display:none"
                           onchange="previsualizarFoto(this)">
                </div>

                
                <button type="submit" id="btnGuardarFoto" class="btn-perfil-guardar btn-foto-hero" style="display:none">
                    Guardar foto
                </button>
            </div>
        </form>

        
        <div class="flex-1">
            <h1 class="text-2xl sm:text-3xl font-black text-white">
                <?php echo e($usuario->nombre); ?> <?php echo e($usuario->apellido1); ?>

            </h1>
            <p class="text-white/50 text-sm mt-1"><?php echo e($usuario->email); ?></p>

            
            <?php if($usuario->biografia): ?>
                <p class="perfil-bio-hero"><?php echo e($usuario->biografia); ?></p>
            <?php endif; ?>

        </div>

    </div>
</section>


<div class="perfil-page-wrap">
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
    <?php if(session('exito')): ?>
        <div class="perfil-alerta perfil-alerta-ok">
            ✓ <?php echo e(session('exito')); ?>

        </div>
    <?php endif; ?>

    
    <?php if($errors->any()): ?>
        <div class="perfil-alerta perfil-alerta-error">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo e($error); ?><br>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>


<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col gap-6">

        
        
        <div class="perfil-card">
            <h2 class="perfil-card-titulo">Datos personales</h2>
            <p class="perfil-card-sub">Edita tu información y pulsa "Guardar"</p>

            <form action="<?php echo e(route('perfil.actualizar')); ?>" method="POST" novalidate>
                <?php echo csrf_field(); ?>

                <div class="perfil-grid-2">
                    <div class="perfil-field">
                        <label for="nombre">Nombre</label>
                        
                        <input type="text" id="nombre" name="nombre"
                               value="<?php echo e(old('nombre', $usuario->nombre)); ?>" required>
                    </div>
                    <div class="perfil-field">
                        <label for="apellido1">Primer apellido</label>
                        <input type="text" id="apellido1" name="apellido1"
                               value="<?php echo e(old('apellido1', $usuario->apellido1)); ?>" required>
                    </div>
                </div>

                <div class="perfil-grid-2">
                    <div class="perfil-field">
                        <label for="apellido2">Segundo apellido</label>
                        <input type="text" id="apellido2" name="apellido2"
                               value="<?php echo e(old('apellido2', $usuario->apellido2)); ?>">
                    </div>
                    <div class="perfil-field">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" id="telefono" name="telefono"
                               value="<?php echo e(old('telefono', $usuario->telefono)); ?>">
                    </div>
                </div>

                <div class="perfil-field">
                    <label for="fecha_nacimiento">Fecha de nacimiento</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                           value="<?php echo e(old('fecha_nacimiento', $usuario->fecha_nacimiento)); ?>">
                </div>

                
                <div class="perfil-field">
                    <label for="biografia">Biografía <span class="perfil-badge-publica">Pública</span></label>
                    <textarea id="biografia" name="biografia" rows="3"
                              placeholder="Cuéntanos algo sobre ti..."><?php echo e(old('biografia', $usuario->biografia)); ?></textarea>
                    <span class="perfil-field-hint">Máx. 500 caracteres · Visible para todos tus amigos</span>
                </div>

                <button type="submit" class="btn-perfil-guardar">
                    Guardar cambios
                </button>
            </form>
        </div>

        
        <div class="perfil-card">
            <h2 class="perfil-card-titulo">Mi estado de ánimo</h2>
            <p class="perfil-card-sub">Elige cómo te sientes · Visible para tus amigos</p>

            <div id="mood-alerta" class="perfil-alerta perfil-alerta-ok" style="display:none; margin-top:0.75rem;"></div>

            <div id="mood-activo" style="<?php echo e($usuario->mood ? 'display:flex' : 'display:none'); ?>; align-items:center; gap:0.75rem; margin-top:0.75rem; padding:0.6rem 0.9rem; border-radius:0.5rem; background:rgba(124,58,237,0.15); border:1px solid rgba(124,58,237,0.35);">
                <span id="mood-activo-texto" style="font-size:0.9rem; color:#c084fc; flex:1;"><?php echo e($usuario->mood); ?></span>
                <button type="button" onclick="seleccionarMood('', null)" style="background:transparent; border:none; color:rgba(245,241,234,0.4); cursor:pointer; font-size:0.8rem; padding:0; line-height:1;" title="Quitar estado">✕ Quitar</button>
            </div>

            <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:0.5rem; margin-top:1rem;">
                <?php $__currentLoopData = ['🥳 De fiesta', '🎵 Escuchando música', '🌙 Noche de salida', '🔥 Con ganas de juerga', '🍻 Tomando algo', '🕺 Bailando', '😎 Tranquilo/a', '😴 Descansando', '💤 Sin planes', '🎶 Modo techno']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opcion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button type="button"
                            class="mood-opcion <?php echo e($usuario->mood === $opcion ? 'mood-opcion--activo' : ''); ?>"
                            onclick="seleccionarMood('<?php echo e($opcion); ?>', this)">
                        <?php echo e($opcion); ?>

                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div style="margin-top:1rem; position:relative;">
                <div style="display:flex; gap:0.5rem; align-items:center;">
                    <button type="button" id="btn-emoji-picker"
                            onclick="toggleEmojiPicker(event)"
                            title="Seleccionar emoji"
                            style="flex-shrink:0; background:rgba(124,58,237,0.15); border:1.5px solid rgba(124,58,237,0.3); border-radius:0.5rem; padding:0.4rem 0.6rem; cursor:pointer; font-size:1.15rem; line-height:1;">
                        <span id="emoji-seleccionado" style="opacity:0.4;">🙂</span>
                    </button>
                    <input type="text" id="mood-personalizado"
                           placeholder="Escribe tu propio estado..."
                           maxlength="96"
                           style="flex:1;"
                           onkeydown="if(event.key==='Enter'){enviarMoodPersonalizado();}">
                    <button type="button" onclick="enviarMoodPersonalizado()" class="btn-perfil-guardar" style="white-space:nowrap; padding:0 1rem;">
                        Guardar
                    </button>
                </div>

                <p id="mood-emoji-aviso" style="display:none; margin:0.35rem 0 0; font-size:0.78rem; color:#f87171;">
                    Selecciona un emoji antes de guardar.
                </p>

                <div id="emoji-picker-panel"
                     onclick="event.stopPropagation()"
                     style="display:none; position:absolute; bottom:calc(100% + 0.4rem); left:0; z-index:200; background:#0d0a18; border:1px solid rgba(124,58,237,0.35); border-radius:0.75rem; padding:0.75rem; width:100%; max-width:340px; box-shadow:0 8px 32px rgba(0,0,0,0.6);">
                    <div style="display:grid; grid-template-columns:repeat(8,1fr); gap:0.2rem;">
                        <?php $__currentLoopData = ['😀','😎','😴','🥳','😊','🤩','😤','🥺','🤔','😅','🙄','😏','🥰','😂','😭','🤯','🔥','💜','✨','⚡','🌙','🌈','💫','🎉','🎵','🎶','🕺','💃','🍻','☕','🍕','🎮','📚','🏃','🤙','👑','🦋','🌸']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emoji): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button type="button"
                                    onclick="insertarEmoji('<?php echo e($emoji); ?>')"
                                    class="emoji-btn"><?php echo e($emoji); ?></button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>

</div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="<?php echo e(asset('js/perfil.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/perfil/index.blade.php ENDPATH**/ ?>
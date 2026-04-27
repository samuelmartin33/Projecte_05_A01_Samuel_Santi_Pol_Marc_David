<?php $__env->startSection('titulo', 'Mi Perfil — VIBEZ'); ?>

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

            
            <?php if($usuario->mood): ?>
                <span class="perfil-mood-hero"><?php echo e($usuario->mood); ?></span>
            <?php endif; ?>
        </div>

    </div>
</section>


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


<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 grid grid-cols-1 lg:grid-cols-3 gap-6">

    
    <div class="lg:col-span-2 flex flex-col gap-6">

        
        
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
            <h2 class="perfil-card-titulo">Estado de ánimo</h2>
            <p class="perfil-card-sub">
                Visible para <strong>todos</strong> (amigos o no) y aparece en la barra de navegación
            </p>

            <form action="<?php echo e(route('perfil.mood')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="perfil-mood-grid">
                    
                    <?php
                        $moods = [
                            ''                    => '— Sin estado —',
                            '🤕 De resaca'        => '🤕 De resaca',
                            '🥳 De fiesta'        => '🥳 De fiesta',
                            '🍺 Bebiendo cerveza' => '🍺 Bebiendo cerveza',
                            '🍷 Bebiendo vino'    => '🍷 Bebiendo vino',
                            '❤️ Enamorado/a'      => '❤️ Enamorado/a',
                            '💃 Bailando'         => '💃 Bailando',
                            '🎵 Escuchando música'=> '🎵 Escuchando música',
                            '😎 Modo casual'      => '😎 Modo casual',
                            '💪 En el gym'        => '💪 En el gym',
                            '😴 Durmiendo'        => '😴 Durmiendo',
                            '🍕 Comiendo'         => '🍕 Comiendo',
                            '✈️ De viaje'         => '✈️ De viaje',
                            '🎮 Gaming'           => '🎮 Gaming',
                            '☀️ Tomando el sol'   => '☀️ Tomando el sol',
                            '🤙 Con los amigos'   => '🤙 Con los amigos',
                        ];
                    ?>

                    <?php $__currentLoopData = $moods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $valor => $etiqueta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($valor === ''): ?>
                            
                            <label class="perfil-mood-opcion <?php echo e($usuario->mood === null ? 'perfil-mood-activo' : ''); ?>">
                                <input type="radio" name="mood" value=""
                                       <?php echo e($usuario->mood === null ? 'checked' : ''); ?>>
                                <span><?php echo e($etiqueta); ?></span>
                            </label>
                        <?php else: ?>
                            <label class="perfil-mood-opcion <?php echo e($usuario->mood === $valor ? 'perfil-mood-activo' : ''); ?>">
                                <input type="radio" name="mood" value="<?php echo e($valor); ?>"
                                       <?php echo e($usuario->mood === $valor ? 'checked' : ''); ?>>
                                <span><?php echo e($etiqueta); ?></span>
                            </label>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <button type="submit" class="btn-perfil-guardar" style="margin-top:16px">
                    Guardar estado
                </button>
            </form>
        </div>

    </div>

    
    <div class="flex flex-col gap-6">

        
        <div class="perfil-card" id="amigos">
            <h2 class="perfil-card-titulo">Amigos</h2>

            
            <div class="perfil-field">
                <label for="buscarAmigo">Buscar por nombre o email</label>
                <input type="text" id="buscarAmigo" placeholder="Escribe al menos 2 caracteres..."
                       oninput="buscarAmigos(this.value)">
            </div>

            
            <div id="resultadosBusqueda" style="display:none" class="perfil-busqueda-lista"></div>

            
            <?php if($solicitudesPendientes->count() > 0): ?>
                <div class="perfil-solicitudes-wrap">
                    <h3 class="perfil-solicitudes-titulo">
                        Solicitudes recibidas
                        <span class="perfil-badge-count"><?php echo e($solicitudesPendientes->count()); ?></span>
                    </h3>

                    <?php $__currentLoopData = $solicitudesPendientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $solicitud): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="perfil-solicitud-item">
                            
                            <div class="perfil-solicitud-avatar">
                                <?php if($solicitud->solicitante->foto_url): ?>
                                    <img src="<?php echo e($solicitud->solicitante->foto_url); ?>" alt="">
                                <?php else: ?>
                                    <?php echo e(strtoupper(substr($solicitud->solicitante->nombre,0,1))); ?>

                                <?php endif; ?>
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-sm truncate" style="color:#0f172a">
                                    <?php echo e($solicitud->solicitante->nombre); ?> <?php echo e($solicitud->solicitante->apellido1); ?>

                                </p>
                            </div>

                            
                            <div class="flex gap-1">
                                
                                <form action="<?php echo e(route('amigos.aceptar', $solicitud->id)); ?>" method="POST" style="display:inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="perfil-btn-aceptar" title="Aceptar">✓</button>
                                </form>

                                
                                <form action="<?php echo e(route('amigos.rechazar', $solicitud->id)); ?>" method="POST" style="display:inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="perfil-btn-rechazar" title="Rechazar">✕</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            
            <div class="perfil-amigos-lista">
                <?php if($amigos->count() > 0): ?>
                    <h3 class="perfil-solicitudes-titulo mt-4">
                        Tus amigos (<?php echo e($amigos->count()); ?>)
                    </h3>
                    <?php $__currentLoopData = $amigos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $amigo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="perfil-solicitud-item">
                            <div class="perfil-solicitud-avatar">
                                <?php if($amigo->foto_url): ?>
                                    <img src="<?php echo e($amigo->foto_url); ?>" alt="">
                                <?php else: ?>
                                    <?php echo e(strtoupper(substr($amigo->nombre,0,1))); ?>

                                <?php endif; ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-sm truncate" style="color:#0f172a">
                                    <?php echo e($amigo->nombre); ?> <?php echo e($amigo->apellido1); ?>

                                </p>
                                
                                <?php if($amigo->mood): ?>
                                    <p class="text-xs" style="color:rgba(15,23,42,0.45)"><?php echo e($amigo->mood); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <p class="text-sm text-center py-4" style="color:rgba(15,23,42,0.4)">
                        Aún no tienes amigos en VIBEZ. ¡Busca a alguien!
                    </p>
                <?php endif; ?>
            </div>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <?php if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/js/perfil.js']); ?>
    <?php else: ?>
        <script src="<?php echo e(asset('js/perfil.js')); ?>"></script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\LARAVEL\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/perfil/index.blade.php ENDPATH**/ ?>
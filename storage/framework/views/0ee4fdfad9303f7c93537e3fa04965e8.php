<?php $__env->startSection('titulo', 'Explorar Eventos'); ?>

<?php $__env->startSection('contenido'); ?>


<section class="hero-home">

    
    <div class="hero-particula hero-particula-1"></div>
    <div class="hero-particula hero-particula-2"></div>
    <div class="hero-particula hero-particula-3"></div>
    <div class="hero-particula hero-particula-4"></div>
    <div class="hero-particula hero-particula-5"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center relative z-10">

        
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold mb-5"
             style="background:rgba(168,85,247,0.15);border:1px solid rgba(168,85,247,0.3);color:#c084fc;letter-spacing:0.06em;text-transform:uppercase;">
            🔥 La plataforma de la escena joven
        </div>

        <h1 class="text-4xl sm:text-5xl font-black text-white tracking-tight leading-tight">
            Tu próxima<br>
            <span class="text-gradient-claro">aventura empieza aquí</span>
        </h1>

        <p class="mt-5 text-white/60 text-base max-w-lg mx-auto leading-relaxed">
            Eventos, conciertos, festivales y trabajo — todo lo que vive tu escena, en un solo lugar.
        </p>

        
        <div class="flex flex-wrap justify-center gap-2 mt-7">
            <span class="pill text-xs font-semibold px-3.5 py-1.5 rounded-full cursor-pointer"
                  onclick="seleccionarFiltro('categoria', '1', '🎵 Música', {stopPropagation:function(){}})">🎵 Música</span>
            <span class="pill text-xs font-semibold px-3.5 py-1.5 rounded-full cursor-pointer"
                  onclick="seleccionarFiltro('categoria', '3', '⚽ Deporte', {stopPropagation:function(){}})">⚽ Deporte</span>
            <span class="pill text-xs font-semibold px-3.5 py-1.5 rounded-full cursor-pointer"
                  onclick="seleccionarFiltro('categoria', '2', '🎭 Cultura', {stopPropagation:function(){}})">🎭 Cultura</span>
            <span class="pill text-xs font-semibold px-3.5 py-1.5 rounded-full cursor-pointer"
                  onclick="seleccionarFiltro('categoria', '4', '🍕 Gastro', {stopPropagation:function(){}})">🍕 Gastro</span>
            <span class="pill text-xs font-semibold px-3.5 py-1.5 rounded-full cursor-pointer"
                  onclick="seleccionarFiltro('categoria', 'trabajo', '💼 Trabajo', {stopPropagation:function(){}})">💼 Trabajo</span>
        </div>

    </div>
</section>


<section class="barra-filtros sticky top-0 z-40">

    
    <div id="overlay-dropdowns"
         style="display:none;position:fixed;inset:0;z-index:200;"
         onclick="cerrarTodosDropdowns()"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex flex-wrap items-end gap-3">

        
        <p class="text-sm font-semibold mr-auto self-center"
           style="color:rgba(15,23,42,0.5)">
            <span id="contador-resultados"><?php echo e($eventos->count() + $ofertas->count()); ?></span>
            <span style="color:var(--morado)"> resultados</span>
        </p>

        
        <div class="filtro-grupo" style="position:relative;z-index:250;">
            <label class="filtro-label">Categoría</label>
            <div class="custom-select-wrapper"
                 id="wrapper-categoria"
                 onclick="toggleDropdown('categoria')">
                <span id="categoria-display" class="custom-select-display">Todas</span>
                <svg class="custom-select-arrow" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5">
                    <path d="M6 9l6 6 6-6"/>
                </svg>
                <div id="categoria-dropdown" class="custom-select-dropdown" style="display:none">
                    <div class="custom-select-option seleccionado"
                         onclick="seleccionarFiltro('categoria','','Todas',event)">Todas</div>
                    <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="custom-select-option"
                             onclick="seleccionarFiltro('categoria','<?php echo e($categoria->id); ?>','<?php echo e($categoria->nombre); ?>',event)">
                            <?php echo e($categoria->nombre); ?>

                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <div class="custom-select-option"
                         onclick="seleccionarFiltro('categoria','trabajo','💼 Bolsa de Trabajo',event)">
                        💼 Bolsa de Trabajo
                    </div>
                </div>
            </div>
            <input type="hidden" id="filtro-categoria" value="">
        </div>

        
        <div class="filtro-grupo" style="position:relative;z-index:240;">
            <label class="filtro-label">Ubicación</label>
            <div class="custom-select-wrapper"
                 id="wrapper-ubicacion"
                 onclick="toggleDropdown('ubicacion')">
                <span id="ubicacion-display" class="custom-select-display">Todas las ciudades</span>
                <svg class="custom-select-arrow" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5">
                    <path d="M6 9l6 6 6-6"/>
                </svg>
                <div id="ubicacion-dropdown" class="custom-select-dropdown" style="display:none">
                    <div class="custom-select-option seleccionado"
                         onclick="seleccionarFiltro('ubicacion','','Todas las ciudades',event)">
                        Todas las ciudades
                    </div>
                    <?php $__currentLoopData = $ubicaciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ubicacion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="custom-select-option"
                             onclick="seleccionarFiltro('ubicacion','<?php echo e($ubicacion); ?>','<?php echo e($ubicacion); ?>',event)">
                            <?php echo e($ubicacion); ?>

                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <input type="hidden" id="filtro-ubicacion" value="">
        </div>

        
        <div class="filtro-grupo">
            <span class="filtro-label" style="visibility:hidden">–</span>
            <button class="btn-limpiar" onclick="limpiarFiltros()">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
                Limpiar
            </button>
        </div>

    </div>
</section>


<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    
    <div id="cargando" class="hidden flex justify-center py-16">
        <div class="spinner"></div>
    </div>

    
    <div id="sin-resultados" class="hidden text-center py-20">
        <p class="text-5xl mb-3">🔍</p>
        <p class="font-bold text-lg" style="color:var(--navy)">Sin resultados para estos filtros</p>
        <p class="text-sm mt-1 mb-5" style="color:rgba(15,23,42,0.45)">Prueba a cambiar la categoría o la ciudad</p>
        <button class="btn-morado" onclick="limpiarFiltros()">Ver todo</button>
    </div>

    
    <div id="seccion-eventos">
        <div class="seccion-vibez-titulo">
            <span>🎉</span> Eventos
        </div>
        <p class="seccion-vibez-sub">
            <?php echo e($eventos->count()); ?> evento<?php echo e($eventos->count() !== 1 ? 's' : ''); ?> disponible<?php echo e($eventos->count() !== 1 ? 's' : ''); ?>

        </p>

        <div id="grid-eventos"
             class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            <?php $__currentLoopData = $eventos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <article class="card-evento"
                         onclick="irADetalle('evento', <?php echo e($evento->id); ?>)">

                    <div class="card-imagen-wrap">
                        <img src="<?php echo e($evento->url_portada); ?>"
                             alt="<?php echo e($evento->titulo); ?>"
                             class="card-imagen"
                             onerror="this.src='https://picsum.photos/seed/fallback-<?php echo e($evento->id); ?>/600/400'">

                        
                        <span class="badge-categoria"
                              data-cat="<?php echo e($evento->categoria?->nombre ?? 'Evento'); ?>">
                            <?php echo e($evento->categoria?->nombre ?? 'Evento'); ?>

                        </span>

                        <span class="badge-precio <?php echo e($evento->es_gratuito ? 'badge-gratis' : ''); ?>">
                            <?php echo e($evento->precio_formateado); ?>

                        </span>
                    </div>

                    <div class="card-cuerpo">
                        <h3 class="card-titulo"><?php echo e($evento->titulo); ?></h3>
                        <p class="card-meta">
                            <svg class="icono-meta" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <?php echo e(\Carbon\Carbon::parse($evento->fecha_inicio)->locale('es')->isoFormat('D MMM YYYY')); ?>

                        </p>
                        <?php if($evento->ubicacion_nombre): ?>
                            <p class="card-meta">
                                <svg class="icono-meta" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <?php echo e($evento->ubicacion_nombre); ?>

                            </p>
                        <?php endif; ?>
                        <?php if($evento->organizador?->empresa): ?>
                            <p class="card-organizador">
                                <?php echo e($evento->organizador->empresa->nombre_empresa); ?>

                            </p>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>
    </div>

    
    <?php if($ofertas->count() > 0): ?>
        <hr class="linea-divisora">

        <div id="seccion-trabajos">
            <div class="seccion-vibez-titulo">
                <span>💼</span> Bolsa de Trabajo
            </div>
            <p class="seccion-vibez-sub">
                <?php echo e($ofertas->count()); ?> oferta<?php echo e($ofertas->count() !== 1 ? 's' : ''); ?> de empleo en la escena de eventos
            </p>

            <div id="grid-trabajos"
                 class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

                <?php $__currentLoopData = $ofertas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $oferta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <article class="card-trabajo"
                             onclick="irADetalle('oferta', <?php echo e($oferta->id); ?>)">

                        <div class="card-trabajo-header">
                            <svg class="icono-trabajo" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="badge-trabajo">Trabajo</span>
                        </div>

                        <div class="card-cuerpo">
                            <h3 class="card-titulo"><?php echo e($oferta->titulo); ?></h3>
                            <?php if($oferta->organizador?->empresa): ?>
                                <p class="card-meta font-semibold" style="color:var(--morado)">
                                    <?php echo e($oferta->organizador->empresa->nombre_empresa); ?>

                                </p>
                            <?php endif; ?>
                            <?php if($oferta->ubicacion): ?>
                                <p class="card-meta">
                                    <svg class="icono-meta" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    <?php echo e($oferta->ubicacion); ?>

                                </p>
                            <?php endif; ?>
                            <p class="card-salario"><?php echo e($oferta->salario_formateado); ?></p>
                            <p class="card-meta" style="font-size:0.75rem">
                                <?php echo e($oferta->vacantes); ?> vacante<?php echo e($oferta->vacantes !== 1 ? 's' : ''); ?>

                            </p>
                        </div>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>
        </div>
    <?php endif; ?>

    
    <div id="grid-resultados" class="hidden">
        <div id="grid-resultados-inner"
             class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        </div>
    </div>

</section>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
<script>

/**
 * Abre o cierra el dropdown personalizado de un selector.
 * Usa un overlay invisible para detectar clics fuera del selector.
 */
function toggleDropdown(id) {
    var dropdown = document.getElementById(id + '-dropdown');
    var wrapper  = document.getElementById('wrapper-' + id);
    var overlay  = document.getElementById('overlay-dropdowns');
    var estaAbierto = dropdown.style.display === 'block';

    cerrarTodosDropdowns();

    if (!estaAbierto) {
        dropdown.style.display = 'block';
        wrapper.classList.add('abierto');
        overlay.style.display = 'block';
    }
}

/**
 * Cierra todos los custom selects y oculta el overlay.
 */
function cerrarTodosDropdowns() {
    ['categoria', 'ubicacion'].forEach(function(id) {
        var d = document.getElementById(id + '-dropdown');
        var w = document.getElementById('wrapper-' + id);
        if (d) d.style.display = 'none';
        if (w) w.classList.remove('abierto');
    });
    var overlay = document.getElementById('overlay-dropdowns');
    if (overlay) overlay.style.display = 'none';
}

/**
 * Selecciona una opción del custom select, actualiza el input hidden
 * y dispara el filtrado AJAX.
 * @param {string} filtroId  - 'categoria' o 'ubicacion'
 * @param {string} valor     - Valor a guardar en el input hidden
 * @param {string} texto     - Texto visible en el selector
 * @param {Event}  event     - Evento del click (para stopPropagation)
 */
function seleccionarFiltro(filtroId, valor, texto, event) {
    event.stopPropagation();

    // Actualizar input hidden y texto visible
    var inputHidden = document.getElementById('filtro-' + filtroId);
    var display = document.getElementById(filtroId + '-display');
    if (inputHidden) inputHidden.value = valor;
    if (display) display.textContent = texto;

    // Marcar la opción como seleccionada visualmente
    var dropdown = document.getElementById(filtroId + '-dropdown');
    if (dropdown) {
        dropdown.querySelectorAll('.custom-select-option').forEach(function(op) {
            op.classList.remove('seleccionado');
        });
        if (event.target && event.target.classList) {
            event.target.classList.add('seleccionado');
        }
    }

    cerrarTodosDropdowns();
    aplicarFiltros();
}

/**
 * Lee los filtros activos y hace fetch AJAX al endpoint /api/filtrar.
 * Muestra el grid unificado de AJAX ocultando las secciones estáticas.
 */
function aplicarFiltros() {
    var categoria = document.getElementById('filtro-categoria').value;
    var ubicacion = document.getElementById('filtro-ubicacion').value;

    // Mostrar spinner, ocultar secciones
    document.getElementById('cargando').classList.remove('hidden');
    document.getElementById('grid-resultados').classList.add('hidden');
    document.getElementById('sin-resultados').classList.add('hidden');

    var seccionEventos  = document.getElementById('seccion-eventos');
    var seccionTrabajos = document.getElementById('seccion-trabajos');
    if (seccionEventos)  seccionEventos.style.display  = 'none';
    if (seccionTrabajos) seccionTrabajos.style.display = 'none';

    fetch('/api/filtrar?categoria=' + encodeURIComponent(categoria) + '&ubicacion=' + encodeURIComponent(ubicacion))
        .then(function(respuesta) { return respuesta.json(); })
        .then(function(datos) {

            document.getElementById('contador-resultados').textContent = datos.total;
            document.getElementById('cargando').classList.add('hidden');

            if (datos.total === 0) {
                document.getElementById('sin-resultados').classList.remove('hidden');
                return;
            }

            // Construir HTML del grid combinado
            var htmlGrid = '';
            datos.eventos.forEach(function(evento) {
                htmlGrid += crearTarjetaEvento(evento);
            });
            datos.ofertas.forEach(function(oferta) {
                htmlGrid += crearTarjetaOferta(oferta);
            });

            document.getElementById('grid-resultados-inner').innerHTML = htmlGrid;
            document.getElementById('grid-resultados').classList.remove('hidden');
        })
        .catch(function(error) {
            console.error('Error al filtrar:', error);
            document.getElementById('cargando').classList.add('hidden');
            // Mostrar de nuevo las secciones estáticas
            if (seccionEventos)  seccionEventos.style.display  = '';
            if (seccionTrabajos) seccionTrabajos.style.display = '';
        });
}

/**
 * Resetea todos los filtros y vuelve a mostrar las secciones estáticas.
 */
function limpiarFiltros() {
    // Resetear inputs hidden
    document.getElementById('filtro-categoria').value = '';
    document.getElementById('filtro-ubicacion').value = '';

    // Resetear textos del custom select
    document.getElementById('categoria-display').textContent = 'Todas';
    document.getElementById('ubicacion-display').textContent = 'Todas las ciudades';

    // Resetear marcas de seleccionado
    document.querySelectorAll('.custom-select-dropdown .custom-select-option').forEach(function(op) {
        op.classList.remove('seleccionado');
    });
    // Marcar primera opción de cada dropdown como seleccionada
    ['categoria-dropdown', 'ubicacion-dropdown'].forEach(function(id) {
        var primera = document.querySelector('#' + id + ' .custom-select-option');
        if (primera) primera.classList.add('seleccionado');
    });

    // Ocultar el grid AJAX y mostrar las secciones estáticas
    document.getElementById('grid-resultados').classList.add('hidden');
    document.getElementById('sin-resultados').classList.add('hidden');

    var seccionEventos  = document.getElementById('seccion-eventos');
    var seccionTrabajos = document.getElementById('seccion-trabajos');
    if (seccionEventos)  seccionEventos.style.display  = '';
    if (seccionTrabajos) seccionTrabajos.style.display = '';

    // Actualizar contador con total real
    var totalEventos  = <?php echo e($eventos->count()); ?>;
    var totalOfertas  = <?php echo e($ofertas->count()); ?>;
    document.getElementById('contador-resultados').textContent = totalEventos + totalOfertas;
}

/**
 * Navega al detalle del evento o la oferta al hacer clic en una tarjeta.
 */
function irADetalle(tipo, id) {
    window.location.href = tipo === 'evento' ? '/eventos/' + id : '/trabajos/' + id;
}

/**
 * Genera el HTML de una tarjeta de evento para el grid AJAX.
 */
function crearTarjetaEvento(evento) {
    var fecha = new Date(evento.fecha_inicio).toLocaleDateString('es-ES', {
        day: 'numeric', month: 'short', year: 'numeric'
    });
    var imagen = evento.portada || ('https://picsum.photos/seed/evento-' + evento.id + '/600/400');
    var ubicacionHtml = evento.ubicacion_nombre
        ? '<p class="card-meta"><svg class="icono-meta" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>' + evento.ubicacion_nombre + '</p>'
        : '';

    return '<article class="card-evento" onclick="irADetalle(\'evento\',' + evento.id + ')">'
        + '<div class="card-imagen-wrap">'
        + '<img src="' + imagen + '" alt="' + evento.titulo + '" class="card-imagen" onerror="this.src=\'https://picsum.photos/seed/fallback-' + evento.id + '/600/400\'">'
        + '<span class="badge-categoria" data-cat="' + evento.categoria + '">' + evento.categoria + '</span>'
        + '<span class="badge-precio ' + (evento.es_gratuito ? 'badge-gratis' : '') + '">' + evento.precio_formateado + '</span>'
        + '</div>'
        + '<div class="card-cuerpo">'
        + '<h3 class="card-titulo">' + evento.titulo + '</h3>'
        + '<p class="card-meta"><svg class="icono-meta" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>' + fecha + '</p>'
        + ubicacionHtml
        + '<p class="card-organizador">' + evento.organizador + '</p>'
        + '</div></article>';
}

/**
 * Genera el HTML de una tarjeta de oferta de trabajo para el grid AJAX.
 */
function crearTarjetaOferta(oferta) {
    var ubicacionHtml = oferta.ubicacion_nombre
        ? '<p class="card-meta"><svg class="icono-meta" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>' + oferta.ubicacion_nombre + '</p>'
        : '';

    return '<article class="card-trabajo" onclick="irADetalle(\'oferta\',' + oferta.id + ')">'
        + '<div class="card-trabajo-header">'
        + '<svg class="icono-trabajo" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>'
        + '<span class="badge-trabajo">Trabajo</span>'
        + '</div>'
        + '<div class="card-cuerpo">'
        + '<h3 class="card-titulo">' + oferta.titulo + '</h3>'
        + '<p class="card-meta" style="font-weight:600;color:var(--morado)">' + oferta.organizador + '</p>'
        + ubicacionHtml
        + '<p class="card-salario">' + oferta.salario_formateado + '</p>'
        + '<p class="card-meta" style="font-size:0.75rem">' + oferta.vacantes + ' vacante' + (oferta.vacantes !== 1 ? 's' : '') + '</p>'
        + '</div></article>';
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\0616\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/home.blade.php ENDPATH**/ ?>
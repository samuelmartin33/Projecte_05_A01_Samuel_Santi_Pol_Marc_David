<?php $__env->startSection('titulo', 'Bolsa de Trabajo'); ?>

<?php $__env->startSection('contenido'); ?>


<section style="background:linear-gradient(160deg,#05000f 0%,#130228 35%,#0a0118 65%,#0f172a 100%);
                position:relative;overflow:hidden;padding:80px 0 60px;">

    
    <div style="position:absolute;width:500px;height:500px;
                background:radial-gradient(circle,rgba(168,85,247,0.2) 0%,transparent 65%);
                top:-120px;left:-80px;pointer-events:none;
                animation:flotar-orb 10s ease-in-out infinite alternate;"></div>

    
    <div style="position:absolute;width:400px;height:400px;
                background:radial-gradient(circle,rgba(16,185,129,0.12) 0%,transparent 65%);
                bottom:-60px;right:-40px;pointer-events:none;
                animation:flotar-orb 8s ease-in-out infinite alternate-reverse;"></div>

    
    <div class="hero-particula hero-particula-1"></div>
    <div class="hero-particula hero-particula-2"></div>
    <div class="hero-particula hero-particula-3"></div>
    <div class="hero-particula hero-particula-4"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative" style="z-index:1;">

        
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold mb-6"
             style="background:rgba(16,185,129,0.12);border:1px solid rgba(16,185,129,0.3);
                    color:#6ee7b7;letter-spacing:0.06em;text-transform:uppercase;">
            💼 Trabaja en la escena
        </div>

        <h1 class="text-4xl sm:text-5xl font-black text-white leading-tight max-w-2xl">
            Encuentra tu sitio<br>
            <span style="background:linear-gradient(135deg,#c084fc 0%,#f0abfc 40%,#6ee7b7 100%);
                         -webkit-background-clip:text;-webkit-text-fill-color:transparent;
                         background-clip:text;">
                en la escena
            </span>
        </h1>

        <p class="mt-4 max-w-xl leading-relaxed"
           style="color:rgba(255,255,255,0.55);font-size:1rem;">
            Fotógrafos, técnicos de sonido, relaciones públicas, camareros…
            Trabaja en los mejores eventos y festivales del país.
        </p>

        
        <div class="flex flex-wrap gap-8 mt-10">
            <div>
                <p class="text-3xl font-black text-white"><?php echo e($ofertas->count()); ?></p>
                <p style="color:rgba(255,255,255,0.4);font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.07em;">
                    Ofertas activas
                </p>
            </div>
            <div style="width:1px;background:rgba(255,255,255,0.1);"></div>
            <div>
                <p class="text-3xl font-black text-white"><?php echo e($ciudades->count()); ?></p>
                <p style="color:rgba(255,255,255,0.4);font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.07em;">
                    Ciudades
                </p>
            </div>
            <div style="width:1px;background:rgba(255,255,255,0.1);"></div>
            <div>
                <p class="text-3xl font-black text-white"><?php echo e($categoriasTrabajo->count()); ?></p>
                <p style="color:rgba(255,255,255,0.4);font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.07em;">
                    Categorías
                </p>
            </div>
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
            <span id="contador-resultados"><?php echo e($ofertas->count()); ?></span>
            <span style="color:var(--morado)"> oferta<?php echo e($ofertas->count() !== 1 ? 's' : ''); ?></span>
        </p>

        
        <div class="filtro-grupo" style="position:relative;z-index:250;">
            <label class="filtro-label">Categoría</label>
            <div class="custom-select-wrapper"
                 id="wrapper-categoria"
                 onclick="toggleDropdown('categoria')">
                <span id="categoria-display" class="custom-select-display">Todas las categorías</span>
                <svg class="custom-select-arrow" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5">
                    <path d="M6 9l6 6 6-6"/>
                </svg>
                <div id="categoria-dropdown" class="custom-select-dropdown" style="display:none">
                    <div class="custom-select-option seleccionado"
                         onclick="seleccionarFiltro('categoria','','Todas las categorías',event)">
                        Todas las categorías
                    </div>
                    <?php $__currentLoopData = $categoriasTrabajo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="custom-select-option"
                             onclick="seleccionarFiltro('categoria','<?php echo e($cat->id); ?>','<?php echo e($cat->nombre); ?>',event)">
                            <?php echo e($cat->nombre); ?>

                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <input type="hidden" id="filtro-categoria" value="">
        </div>

        
        <div class="filtro-grupo" style="position:relative;z-index:240;">
            <label class="filtro-label">Ciudad</label>
            <div class="custom-select-wrapper"
                 id="wrapper-ciudad"
                 onclick="toggleDropdown('ciudad')">
                <span id="ciudad-display" class="custom-select-display">Todas las ciudades</span>
                <svg class="custom-select-arrow" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5">
                    <path d="M6 9l6 6 6-6"/>
                </svg>
                <div id="ciudad-dropdown" class="custom-select-dropdown" style="display:none">
                    <div class="custom-select-option seleccionado"
                         onclick="seleccionarFiltro('ciudad','','Todas las ciudades',event)">
                        Todas las ciudades
                    </div>
                    <?php $__currentLoopData = $ciudades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ciudad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="custom-select-option"
                             onclick="seleccionarFiltro('ciudad','<?php echo e($ciudad); ?>','<?php echo e($ciudad); ?>',event)">
                            <?php echo e($ciudad); ?>

                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <input type="hidden" id="filtro-ciudad" value="">
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
        <p class="font-bold text-lg" style="color:var(--navy)">No hay ofertas para estos filtros</p>
        <p class="text-sm mt-1 mb-5" style="color:rgba(15,23,42,0.45)">Prueba con otra categoría o ciudad</p>
        <button class="btn-morado" onclick="limpiarFiltros()">Ver todas las ofertas</button>
    </div>

    
    <div id="grid-ofertas"
         class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <?php $__currentLoopData = $ofertas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $oferta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <article class="card-trabajo-grande"
                     onclick="irAOferta(<?php echo e($oferta->id); ?>)">

                
                <div class="ctg-header">
                    
                    <div class="ctg-icono">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="ctg-badge"><?php echo e($oferta->categoria?->nombre ?? 'General'); ?></span>
                        <p class="ctg-empresa">
                            <?php echo e($oferta->organizador?->empresa?->nombre_empresa ?? 'Empresa'); ?>

                        </p>
                    </div>
                </div>

                
                <h3 class="ctg-titulo"><?php echo e($oferta->titulo); ?></h3>

                
                <?php if($oferta->descripcion): ?>
                    <p class="ctg-desc">
                        <?php echo e(Str::limit($oferta->descripcion, 110)); ?>

                    </p>
                <?php endif; ?>

                
                <div class="ctg-datos">
                    <?php if($oferta->ubicacion): ?>
                        <span class="ctg-dato">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <?php echo e($oferta->ubicacion); ?>

                        </span>
                    <?php endif; ?>

                    <span class="ctg-dato">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <?php echo e($oferta->vacantes); ?> vacante<?php echo e($oferta->vacantes !== 1 ? 's' : ''); ?>

                    </span>

                    <?php if($oferta->fecha_inicio_trabajo): ?>
                        <span class="ctg-dato">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <?php echo e(\Carbon\Carbon::parse($oferta->fecha_inicio_trabajo)->locale('es')->isoFormat('D MMM YYYY')); ?>

                        </span>
                    <?php endif; ?>
                </div>

                
                <div class="ctg-footer">
                    <div>
                        <p class="ctg-salario-label">Salario</p>
                        <p class="ctg-salario"><?php echo e($oferta->salario_formateado); ?></p>
                    </div>
                    <button class="ctg-btn" onclick="irAOferta(<?php echo e($oferta->id); ?>)">
                        Ver oferta
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>

            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>

</section>


<section style="background:linear-gradient(135deg,#060012,#130228,#0f172a);padding:60px 0;margin-top:20px;">
    <div class="max-w-3xl mx-auto px-6 text-center">
        <p class="text-4xl mb-4">🏢</p>
        <h2 class="text-2xl sm:text-3xl font-black text-white mb-3">
            ¿Organizas eventos?
        </h2>
        <p style="color:rgba(255,255,255,0.5);font-size:0.95rem;max-width:480px;margin:0 auto 28px;">
            Publica tus ofertas de trabajo y encuentra al equipo perfecto para tus festivales, conciertos y eventos.
        </p>
        <a href="<?php echo e(route('home')); ?>"
           class="btn-morado inline-block px-8 py-3">
            Explorar la plataforma
        </a>
    </div>
</section>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('estilos'); ?>
<style>
/* Tarjeta grande de oferta de trabajo */
.card-trabajo-grande {
    background: white;
    border-radius: 20px;
    padding: 24px;
    cursor: pointer;
    border: 1.5px solid #ede9fe;
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05), 0 4px 20px rgba(0,0,0,0.03);
    display: flex; flex-direction: column; gap: 14px;
}
.card-trabajo-grande:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 40px rgba(124,58,237,0.18);
    border-color: var(--morado);
}

/* Cabecera de la tarjeta */
.ctg-header {
    display: flex; align-items: center; gap: 12px;
}
.ctg-icono {
    width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
    background: linear-gradient(135deg, #05000f, #1e1035);
    display: flex; align-items: center; justify-content: center;
    color: rgba(196,132,252,0.85);
    box-shadow: 0 0 10px rgba(168,85,247,0.2);
}
.ctg-icono svg { width: 22px; height: 22px; }
.ctg-badge {
    display: inline-block;
    background: linear-gradient(135deg, var(--morado), var(--morado-claro));
    color: white;
    font-size: 0.6rem; font-weight: 800;
    text-transform: uppercase; letter-spacing: 0.07em;
    padding: 3px 9px; border-radius: 999px;
    margin-bottom: 3px;
}
.ctg-empresa {
    font-size: 0.8rem; font-weight: 700; color: var(--morado);
    margin: 0;
}

/* Título de la tarjeta */
.ctg-titulo {
    font-size: 1.05rem; font-weight: 800; color: var(--navy);
    line-height: 1.3; margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Descripción corta */
.ctg-desc {
    font-size: 0.82rem; color: rgba(15,23,42,0.5);
    line-height: 1.55; margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Chips de datos clave */
.ctg-datos {
    display: flex; flex-wrap: wrap; gap: 6px;
}
.ctg-dato {
    display: inline-flex; align-items: center; gap: 5px;
    background: #f7f4ff;
    border: 1px solid #ede9fe;
    border-radius: 999px;
    padding: 4px 10px;
    font-size: 0.76rem; font-weight: 500; color: rgba(15,23,42,0.6);
}
.ctg-dato svg { color: var(--morado); flex-shrink: 0; }

/* Footer de la tarjeta */
.ctg-footer {
    display: flex; align-items: center; justify-content: space-between;
    margin-top: auto; padding-top: 14px;
    border-top: 1px solid #f0eeff;
}
.ctg-salario-label {
    font-size: 0.62rem; font-weight: 800;
    text-transform: uppercase; letter-spacing: 0.08em;
    color: rgba(15,23,42,0.35); margin: 0 0 2px;
}
.ctg-salario {
    font-size: 0.9rem; font-weight: 800;
    background: linear-gradient(135deg, #059669, #10b981);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text; margin: 0;
}
.ctg-btn {
    display: inline-flex; align-items: center; gap: 5px;
    background: linear-gradient(135deg, var(--morado), var(--morado-claro));
    color: white;
    padding: 8px 16px; border-radius: 999px;
    font-size: 0.8rem; font-weight: 700; border: none;
    cursor: pointer; white-space: nowrap;
    transition: opacity 0.15s, transform 0.15s, box-shadow 0.15s;
    box-shadow: 0 3px 12px rgba(124,58,237,0.35);
}
.ctg-btn:hover { opacity: 0.9; transform: scale(1.04); box-shadow: 0 6px 20px rgba(124,58,237,0.5); }
</style>
<?php $__env->stopPush(); ?>


<?php $__env->startPush('scripts'); ?>
<script>

/**
 * Abre o cierra el custom select del filtro.
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
    ['categoria', 'ciudad'].forEach(function(id) {
        var d = document.getElementById(id + '-dropdown');
        var w = document.getElementById('wrapper-' + id);
        if (d) d.style.display = 'none';
        if (w) w.classList.remove('abierto');
    });
    var overlay = document.getElementById('overlay-dropdowns');
    if (overlay) overlay.style.display = 'none';
}

/**
 * Selecciona una opción del filtro y dispara el filtrado AJAX.
 */
function seleccionarFiltro(filtroId, valor, texto, event) {
    event.stopPropagation();

    var inputHidden = document.getElementById('filtro-' + filtroId);
    var display = document.getElementById(filtroId + '-display');
    if (inputHidden) inputHidden.value = valor;
    if (display) display.textContent = texto;

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
 * Hace fetch al endpoint /api/filtrar-trabajos y actualiza el grid.
 */
function aplicarFiltros() {
    var categoria = document.getElementById('filtro-categoria').value;
    var ciudad    = document.getElementById('filtro-ciudad').value;

    document.getElementById('cargando').classList.remove('hidden');
    document.getElementById('grid-ofertas').classList.add('hidden');
    document.getElementById('sin-resultados').classList.add('hidden');

    fetch('/api/filtrar-trabajos?categoria=' + encodeURIComponent(categoria) + '&ciudad=' + encodeURIComponent(ciudad))
        .then(function(r) { return r.json(); })
        .then(function(datos) {

            // Actualizar contador
            var contador = document.getElementById('contador-resultados');
            contador.textContent = datos.total;

            document.getElementById('cargando').classList.add('hidden');

            if (datos.total === 0) {
                document.getElementById('sin-resultados').classList.remove('hidden');
                return;
            }

            // Reconstruir grid
            var html = '';
            datos.ofertas.forEach(function(oferta) {
                html += crearTarjetaOferta(oferta);
            });

            var grid = document.getElementById('grid-ofertas');
            grid.innerHTML = html;
            grid.classList.remove('hidden');
        })
        .catch(function(error) {
            console.error('Error al filtrar:', error);
            document.getElementById('cargando').classList.add('hidden');
            document.getElementById('grid-ofertas').classList.remove('hidden');
        });
}

/**
 * Resetea todos los filtros y muestra todas las ofertas.
 */
function limpiarFiltros() {
    document.getElementById('filtro-categoria').value = '';
    document.getElementById('filtro-ciudad').value    = '';
    document.getElementById('categoria-display').textContent = 'Todas las categorías';
    document.getElementById('ciudad-display').textContent    = 'Todas las ciudades';

    document.querySelectorAll('.custom-select-dropdown .custom-select-option').forEach(function(op) {
        op.classList.remove('seleccionado');
    });
    ['categoria-dropdown', 'ciudad-dropdown'].forEach(function(id) {
        var primera = document.querySelector('#' + id + ' .custom-select-option');
        if (primera) primera.classList.add('seleccionado');
    });

    aplicarFiltros();
}

/**
 * Navega al detalle de la oferta.
 */
function irAOferta(id) {
    window.location.href = '/trabajos/' + id;
}

/**
 * Genera el HTML de una tarjeta de oferta para el grid AJAX.
 */
function crearTarjetaOferta(oferta) {
    var fechaHtml = oferta.fecha_inicio
        ? '<span class="ctg-dato"><svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>' + oferta.fecha_inicio + '</span>'
        : '';

    return '<article class="card-trabajo-grande" onclick="irAOferta(' + oferta.id + ')">'
        + '<div class="ctg-header">'
        + '<div class="ctg-icono"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="width:22px;height:22px"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>'
        + '<div><span class="ctg-badge">' + oferta.categoria + '</span><p class="ctg-empresa">' + oferta.organizador + '</p></div>'
        + '</div>'
        + '<h3 class="ctg-titulo">' + oferta.titulo + '</h3>'
        + (oferta.descripcion ? '<p class="ctg-desc">' + oferta.descripcion + '</p>' : '')
        + '<div class="ctg-datos">'
        + '<span class="ctg-dato"><svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>' + oferta.ubicacion + '</span>'
        + '<span class="ctg-dato"><svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>' + oferta.vacantes + ' vacante' + (oferta.vacantes !== 1 ? 's' : '') + '</span>'
        + fechaHtml
        + '</div>'
        + '<div class="ctg-footer">'
        + '<div><p class="ctg-salario-label">Salario</p><p class="ctg-salario">' + oferta.salario_formateado + '</p></div>'
        + '<button class="ctg-btn" onclick="irAOferta(' + oferta.id + ')">Ver oferta <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></button>'
        + '</div>'
        + '</article>';
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/trabajos/index.blade.php ENDPATH**/ ?>
<?php $__env->startSection('titulo', $evento->titulo); ?>


<?php $__env->startPush('estilos'); ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        /* Altura fija para el mapa de Leaflet */
        #mapa-evento {
            height: 320px;
            border-radius: 16px;
            z-index: 1;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('contenido'); ?>


<div class="hero-detalle" style="background-image: url('<?php echo e($evento->url_portada); ?>')">
    
    <div class="hero-detalle-overlay">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

            
            <a href="<?php echo e(route('home')); ?>" class="btn-volver">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver
            </a>

            
            <span class="badge-categoria-hero mt-6 inline-block">
                <?php echo e($evento->categoria?->nombre ?? 'Evento'); ?>

            </span>

            
            <h1 class="text-3xl sm:text-5xl font-black text-white mt-3 leading-tight max-w-3xl">
                <?php echo e($evento->titulo); ?>

            </h1>

            
            <div class="flex flex-wrap gap-6 mt-6">

                
                <div class="dato-hero">
                    <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>
                        <?php echo e(\Carbon\Carbon::parse($evento->fecha_inicio)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY')); ?>

                        · <?php echo e(\Carbon\Carbon::parse($evento->fecha_inicio)->format('H:i')); ?>h
                    </span>
                </div>

                
                <?php if($evento->ubicacion_nombre): ?>
                    <div class="dato-hero">
                        <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                        <span><?php echo e($evento->ubicacion_nombre); ?></span>
                    </div>
                <?php endif; ?>

                
                <div class="dato-hero">
                    <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 9a2 2 0 10-4 0v5a2 2 0 01-2 2h6m-6-4h4m8 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-bold text-lg"><?php echo e($evento->precio_formateado); ?></span>
                </div>

            </div>

        </div>
    </div>
</div>


<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        
        <div class="lg:col-span-2 space-y-10">

            
            <section class="seccion-detalle">
                <h2 class="seccion-titulo">Sobre el evento</h2>
                <p class="text-navy/80 leading-relaxed text-base">
                    <?php echo e($evento->descripcion ?? 'No hay descripción disponible.'); ?>

                </p>
            </section>

            
            <section class="seccion-detalle">
                <h2 class="seccion-titulo">Información adicional</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">

                    
                    <?php if($evento->aforo_maximo): ?>
                        <div class="ficha-dato">
                            <span class="ficha-dato-label">Aforo máximo</span>
                            <span class="ficha-dato-valor"><?php echo e(number_format($evento->aforo_maximo)); ?> personas</span>
                        </div>
                    <?php endif; ?>

                    
                    <?php if($evento->aforo_maximo): ?>
                        <?php
                            $disponibles = $evento->aforo_maximo - $evento->aforo_actual;
                        ?>
                        <div class="ficha-dato">
                            <span class="ficha-dato-label">Disponibles</span>
                            <span class="ficha-dato-valor <?php echo e($disponibles < 50 ? 'text-red-600' : 'text-green-600'); ?>">
                                <?php echo e(number_format($disponibles)); ?>

                            </span>
                        </div>
                    <?php endif; ?>

                    
                    <?php if($evento->edad_minima): ?>
                        <div class="ficha-dato">
                            <span class="ficha-dato-label">Edad mínima</span>
                            <span class="ficha-dato-valor">+<?php echo e($evento->edad_minima); ?></span>
                        </div>
                    <?php endif; ?>

                    
                    <?php if($evento->fecha_fin): ?>
                        <div class="ficha-dato">
                            <span class="ficha-dato-label">Finaliza</span>
                            <span class="ficha-dato-valor">
                                <?php echo e(\Carbon\Carbon::parse($evento->fecha_fin)->format('H:i')); ?>h
                            </span>
                        </div>
                    <?php endif; ?>

                </div>
            </section>

            
            <?php if($evento->organizador?->empresa): ?>
                <section class="seccion-detalle">
                    <h2 class="seccion-titulo">Organiza</h2>
                    <div class="card-organizador-detalle">
                        
                        <div class="logo-empresa">
                            <?php if($evento->organizador->empresa->logo_url): ?>
                                <img src="<?php echo e($evento->organizador->empresa->logo_url); ?>"
                                     alt="<?php echo e($evento->organizador->empresa->nombre_empresa); ?>"
                                     class="w-full h-full object-cover">
                            <?php else: ?>
                                <span class="text-white font-black text-xl">
                                    <?php echo e(strtoupper(substr($evento->organizador->empresa->nombre_empresa, 0, 1))); ?>

                                </span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <p class="font-bold text-navy text-lg">
                                <?php echo e($evento->organizador->empresa->nombre_empresa); ?>

                            </p>
                            <?php if($evento->organizador->empresa->descripcion): ?>
                                <p class="text-navy/60 text-sm mt-1 line-clamp-2">
                                    <?php echo e($evento->organizador->empresa->descripcion); ?>

                                </p>
                            <?php endif; ?>
                            <?php if($evento->organizador->empresa->sitio_web): ?>
                                <a href="<?php echo e($evento->organizador->empresa->sitio_web); ?>"
                                   target="_blank"
                                   class="texto-enlace text-sm mt-2 inline-block">
                                    Visitar web →
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            
            <?php if($evento->imagenes->where('es_portada', 0)->count() > 0): ?>
                <section class="seccion-detalle">
                    <h2 class="seccion-titulo">Galería</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <?php $__currentLoopData = $evento->imagenes->where('es_portada', 0); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $imagen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="overflow-hidden rounded-xl aspect-video">
                                <img src="<?php echo e($imagen->imagen_url); ?>"
                                     alt="<?php echo e($imagen->descripcion ?? $evento->titulo); ?>"
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                     onerror="this.parentElement.remove()">
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </section>
            <?php endif; ?>

        </div>

        
        <div class="lg:col-span-1">
            <div class="lg:sticky lg:top-24 space-y-6">

                
                <div class="ficha-compra">

                    
                    <div class="text-center mb-6">
                        <p class="text-navy/50 text-sm uppercase tracking-widest font-semibold mb-1">Precio</p>
                        <p class="text-4xl font-black text-gradient">
                            <?php echo e($evento->precio_formateado); ?>

                        </p>
                        <?php if(!$evento->es_gratuito): ?>
                            <p class="text-navy/40 text-xs mt-1">por persona · IVA incluido</p>
                        <?php endif; ?>
                    </div>

                    
                    <?php if($evento->aforo_maximo): ?>
                        <?php
                            $porcentajeOcupacion = ($evento->aforo_actual / $evento->aforo_maximo) * 100;
                        ?>
                        <div class="mb-6">
                            <div class="flex justify-between text-xs text-navy/50 mb-1">
                                <span><?php echo e(number_format($evento->aforo_maximo - $evento->aforo_actual)); ?> entradas disponibles</span>
                                <span><?php echo e(round($porcentajeOcupacion)); ?>% ocupado</span>
                            </div>
                            <div class="barra-aforo-fondo">
                                <div class="barra-aforo-relleno <?php echo e($porcentajeOcupacion > 80 ? 'barra-aforo-critico' : ''); ?>"
                                     style="width: <?php echo e(min($porcentajeOcupacion, 100)); ?>%"></div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <button class="btn-comprar w-full"
                            onclick="abrirCompra(<?php echo e($evento->id); ?>)">
                        <?php echo e($evento->es_gratuito ? 'Reservar entrada gratuita' : 'Comprar entrada'); ?>

                    </button>

                    
                    <?php if($evento->url_externa): ?>
                        <a href="<?php echo e($evento->url_externa); ?>"
                           target="_blank"
                           class="btn-secundario w-full mt-3 block text-center">
                            Ver en web oficial
                        </a>
                    <?php endif; ?>

                    
                    <p class="text-center text-navy/40 text-xs mt-4">
                        🔒 Compra segura · Entrada con código QR
                    </p>

                </div>

                
                <?php if($evento->latitud && $evento->longitud): ?>
                    <div class="ficha-mapa">
                        <h3 class="font-bold text-navy mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-morado-vibez" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            Ubicación
                        </h3>

                        
                        <?php if($evento->ubicacion_direccion): ?>
                            <p class="text-navy/60 text-sm mb-3"><?php echo e($evento->ubicacion_direccion); ?></p>
                        <?php endif; ?>

                        
                        <div id="mapa-evento"></div>

                        
                        <a href="https://www.google.com/maps?q=<?php echo e($evento->latitud); ?>,<?php echo e($evento->longitud); ?>"
                           target="_blank"
                           class="texto-enlace text-sm mt-3 inline-block">
                            Abrir en Google Maps →
                        </a>
                    </div>
                <?php else: ?>
                    
                    <div class="ficha-mapa text-center py-6">
                        <p class="text-navy/40 text-sm">📍 Ubicación no disponible</p>
                    </div>
                <?php endif; ?>

            </div>
        </div>

    </div>
</div>


<?php if(auth()->guard()->check()): ?>
<div id="modal-compra"
     style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(15,23,42,0.65);backdrop-filter:blur(4px);"
     onclick="if(event.target===this)cerrarModalCompra()">
    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
                background:#fff;border-radius:24px;padding:2rem;width:calc(100% - 2rem);max-width:440px;
                box-shadow:0 25px 60px rgba(124,58,237,0.25);">

        
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
            <h2 style="font-weight:900;font-size:1.25rem;color:var(--navy,#0f172a);margin:0">
                Comprar entradas
            </h2>
            <button onclick="cerrarModalCompra()"
                    style="background:none;border:none;cursor:pointer;font-size:1.75rem;color:#94a3b8;line-height:1">×</button>
        </div>

        
        <div style="background:#f0ecff;border-radius:12px;padding:1rem;margin-bottom:1.5rem">
            <p style="font-weight:700;color:var(--navy,#0f172a);margin:0;font-size:0.95rem"><?php echo e($evento->titulo); ?></p>
            <p style="color:#7c3aed;font-size:0.85rem;margin:4px 0 0">
                <?php echo e(\Carbon\Carbon::parse($evento->fecha_inicio)->locale('es')->isoFormat('D [de] MMMM [de] YYYY')); ?>

            </p>
        </div>

        
        <div style="margin-bottom:1.5rem">
            <label style="font-weight:600;font-size:0.875rem;color:var(--navy,#0f172a);display:block;margin-bottom:10px">
                Cantidad de entradas
            </label>
            <div style="display:flex;align-items:center;gap:16px">
                <button type="button" onclick="cambiarCantidad(-1)"
                        style="width:40px;height:40px;border-radius:50%;border:2px solid #7c3aed;
                               background:#fff;color:#7c3aed;font-size:1.25rem;cursor:pointer;
                               font-weight:700;display:flex;align-items:center;justify-content:center;
                               flex-shrink:0">−</button>
                <span id="modal-cantidad"
                      style="font-size:1.5rem;font-weight:900;color:var(--navy,#0f172a);
                             min-width:40px;text-align:center">1</span>
                <button type="button" onclick="cambiarCantidad(1)"
                        style="width:40px;height:40px;border-radius:50%;border:2px solid #7c3aed;
                               background:#fff;color:#7c3aed;font-size:1.25rem;cursor:pointer;
                               font-weight:700;display:flex;align-items:center;justify-content:center;
                               flex-shrink:0">+</button>
            </div>
        </div>

        
        <div style="display:flex;justify-content:space-between;align-items:center;
                    border-top:1px solid #ede9fe;padding-top:1rem;margin-bottom:1.5rem">
            <span style="font-weight:600;color:#64748b;font-size:0.9rem">Total</span>
            <span id="modal-total" class="text-gradient"
                  style="font-size:1.75rem;font-weight:900">
                <?php if($evento->es_gratuito): ?> Gratis
                <?php else: ?> <?php echo e(number_format($evento->precio_base, 2)); ?> €
                <?php endif; ?>
            </span>
        </div>

        
        <div id="modal-error"
             style="display:none;background:#fef2f2;border:1px solid #fca5a5;color:#dc2626;
                    border-radius:8px;padding:10px 14px;font-size:0.875rem;margin-bottom:1rem"></div>

        
        <button id="modal-btn-comprar"
                onclick="confirmarCompra()"
                class="btn-comprar w-full">
            <?php echo e($evento->es_gratuito ? 'Reservar gratis' : 'Confirmar compra'); ?>

        </button>

        <p style="text-align:center;font-size:0.75rem;color:#94a3b8;margin-top:12px;margin-bottom:0">
            🔒 Transacción segura · Recibirás tu QR al instante
        </p>
    </div>
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
/**
 * Inicializar el mapa de Leaflet con la ubicación del evento.
 * Se ejecuta directamente (sin event listeners) cuando el script carga.
 *
 * @param {number} latitud         - Latitud del evento en BD
 * @param {number} longitud        - Longitud del evento en BD
 * @param {string} nombreUbicacion - Nombre del lugar para el popup
 */
function inicializarMapa(latitud, longitud, nombreUbicacion) {
    // Crear el mapa centrado en las coordenadas del evento, zoom 15 = nivel calle
    var mapa = L.map('mapa-evento').setView([latitud, longitud], 15);

    // Añadir capa de tiles de OpenStreetMap (gratuita, sin necesidad de API key)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© <a href="https://openstreetmap.org">OpenStreetMap</a>'
    }).addTo(mapa);

    // Icono personalizado con los colores de VIBEZ
    var iconoVibez = L.divIcon({
        html: '<div style="background:linear-gradient(135deg,#7c3aed,#a855f7);width:32px;height:32px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.3)"></div>',
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -35],
        className: ''
    });

    // Añadir marcador en la ubicación y abrir el popup con el nombre del lugar
    L.marker([latitud, longitud], { icon: iconoVibez })
        .addTo(mapa)
        .bindPopup('<strong>' + nombreUbicacion + '</strong>')
        .openPopup();
}

// Llamar a la función directamente — los datos vienen del blade (PHP → JS)
<?php if($evento->latitud && $evento->longitud): ?>
    inicializarMapa(
        <?php echo e($evento->latitud); ?>,
        <?php echo e($evento->longitud); ?>,
        '<?php echo e(addslashes($evento->ubicacion_nombre ?? 'Ubicación del evento')); ?>'
    );
<?php endif; ?>

// Datos del evento pasados desde PHP
const EVENTO_ID   = <?php echo e($evento->id); ?>;
const PRECIO_BASE = <?php echo e($evento->precio_base ?? 0); ?>;
const ES_GRATUITO = <?php echo e($evento->es_gratuito ? 'true' : 'false'); ?>;
const AFORO_LIBRE = <?php echo e($evento->aforo_maximo ? $evento->aforo_maximo - $evento->aforo_actual : 9999); ?>;

let modalCantidad = 1;

function abrirCompra() {
    <?php if(auth()->guard()->guest()): ?>
    window.location.href = '<?php echo e(route('login')); ?>';
    return;
    <?php endif; ?>

    modalCantidad = 1;
    actualizarModalTotal();
    document.getElementById('modal-error').style.display = 'none';
    document.getElementById('modal-btn-comprar').disabled = false;
    document.getElementById('modal-btn-comprar').textContent = ES_GRATUITO ? 'Reservar gratis' : 'Confirmar compra';
    document.getElementById('modal-compra').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function cerrarModalCompra() {
    document.getElementById('modal-compra').style.display = 'none';
    document.body.style.overflow = '';
}

function cambiarCantidad(delta) {
    const nuevo = modalCantidad + delta;
    if (nuevo < 1 || nuevo > 10 || nuevo > AFORO_LIBRE) return;
    modalCantidad = nuevo;
    actualizarModalTotal();
}

function actualizarModalTotal() {
    document.getElementById('modal-cantidad').textContent = modalCantidad;
    if (!ES_GRATUITO) {
        const total = (PRECIO_BASE * modalCantidad).toFixed(2).replace('.', ',');
        document.getElementById('modal-total').textContent = total + ' €';
    }
}

async function confirmarCompra() {
    const btn   = document.getElementById('modal-btn-comprar');
    const error = document.getElementById('modal-error');
    const csrf  = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    btn.disabled    = true;
    btn.textContent = 'Procesando...';
    error.style.display = 'none';

    try {
        const res = await fetch('/api/entradas/comprar', {
            method:  'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept':       'application/json',
                'X-CSRF-TOKEN': csrf,
            },
            body: JSON.stringify({ evento_id: EVENTO_ID, cantidad: modalCantidad }),
        });

        const data = await res.json();

        if (data.success) {
            btn.textContent = '¡Redirigiendo...';
            document.body.style.transition = 'opacity 0.3s';
            document.body.style.opacity    = '0';
            setTimeout(() => { window.location.href = data.redirect; }, 320);
        } else {
            error.textContent   = data.message || 'Error al procesar la compra.';
            error.style.display = 'block';
            btn.disabled        = false;
            btn.textContent     = ES_GRATUITO ? 'Reservar gratis' : 'Confirmar compra';
        }
    } catch (e) {
        error.textContent   = 'Error de conexión. Inténtalo de nuevo.';
        error.style.display = 'block';
        btn.disabled        = false;
        btn.textContent     = ES_GRATUITO ? 'Reservar gratis' : 'Confirmar compra';
    }
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/eventos/detalle.blade.php ENDPATH**/ ?>
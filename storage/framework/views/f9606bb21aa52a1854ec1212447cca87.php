<?php $__env->startSection('titulo', 'Mis entradas — VIBEZ'); ?>


<?php $__env->startSection('content'); ?>


<link rel="stylesheet" href="<?php echo e(asset('css/vibez-home.css')); ?>">
<style>
  /* ── Filtros ── */
  .me-filtro-btn {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(124,58,237,0.22);
    color: rgba(245,241,234,0.45);
    border-radius: 999px;
    padding: 7px 20px;
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    cursor: pointer;
    transition: all 0.15s;
  }
  .me-filtro-btn.activo {
    background: linear-gradient(135deg, #7c3aed, #a855f7);
    border-color: transparent;
    color: #fff;
    font-weight: 700;
  }
  .me-filtro-btn:hover:not(.activo) {
    background: rgba(124,58,237,0.1);
    color: #c084fc;
    border-color: rgba(124,58,237,0.4);
  }

  /* ── Tarjeta de ticket ── */
  .me-card {
    border-radius: 18px;
    overflow: hidden;
    border: 1px solid rgba(124,58,237,0.28);
    background: rgba(255,255,255,0.03);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    cursor: pointer;
    transition: border-color 0.2s, transform 0.15s, box-shadow 0.2s;
    user-select: none;
    margin-bottom: 1.25rem;
  }
  .me-card:hover {
    border-color: rgba(168,85,247,0.55);
    transform: translateY(-2px);
    box-shadow: 0 8px 32px rgba(124,58,237,0.18);
  }
  .me-card.usada { opacity: 0.65; border-color: rgba(100,116,139,0.18); }

  /* ── Chevron ── */
  .me-chevron { transition: transform 0.25s ease; display: flex; align-items: center; }
  .me-chevron.abierto { transform: rotate(180deg); }

  /* ── Separador talonario ── */
  .me-sep { position: relative; border-top: 2px dashed rgba(124,58,237,0.28); }
  .me-circ {
    position: absolute; top: -11px;
    width: 22px; height: 22px;
    background: #07060c;
    border-radius: 50%;
    border: 2px solid rgba(124,58,237,0.28);
  }

  /* ── Panel QR ── */
  .me-qr-panel { background: rgba(0,0,0,0.22); padding: 22px 24px 26px; }
  .me-qr-marco {
    background: #fff; border-radius: 12px; padding: 10px;
    box-shadow: 0 4px 28px rgba(124,58,237,0.22); display: inline-block;
  }

  /* ── Contador ── */
  .me-cnt-unit {
    display: flex; flex-direction: column; align-items: center;
    background: rgba(0,0,0,0.38);
    border: 1px solid rgba(124,58,237,0.28);
    border-radius: 10px;
    padding: 8px 12px; min-width: 52px;
  }

  /* ── Badge ── */
  .me-badge-activa {
    background: rgba(52,211,153,0.1); border: 1px solid rgba(52,211,153,0.28);
    color: #34d399; display: inline-block;
    font-family: 'Archivo Narrow', sans-serif; font-size: 0.58rem;
    text-transform: uppercase; letter-spacing: 0.14em;
    padding: 3px 10px; border-radius: 999px; margin-bottom: 8px;
  }
  .me-badge-usada {
    background: rgba(148,163,184,0.08); border: 1px solid rgba(148,163,184,0.18);
    color: #94a3b8; display: inline-block;
    font-family: 'Archivo Narrow', sans-serif; font-size: 0.58rem;
    text-transform: uppercase; letter-spacing: 0.14em;
    padding: 3px 10px; border-radius: 999px; margin-bottom: 8px;
  }

  /* ── Responsive ── */
  @media (max-width: 600px) {
    .me-cnt-row { flex-direction: column; align-items: flex-start; gap: 14px; }
    .me-cnt-timer { gap: 5px; }
    .me-cnt-unit  { min-width: 42px; padding: 6px 8px; }
    .me-cnt-num   { font-size: 1.1rem !important; }
    .me-filtros   { flex-wrap: wrap; }
    .me-card-header { padding: 16px !important; }
    .me-qr-panel  { padding: 16px !important; }
  }
</style>


<?php echo $__env->make('partials.home.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<?php
  /* Encontramos el próximo evento futuro con entrada activa */
  $proximoEvento = null;
  foreach($pedidos as $pedido) {
    foreach($pedido->entradas as $entrada) {
      if ($entrada->estado_entrada == 1 && $entrada->evento &&
          \Carbon\Carbon::parse($entrada->evento->fecha_inicio)->isFuture()) {
        if (!$proximoEvento ||
            \Carbon\Carbon::parse($entrada->evento->fecha_inicio)
              ->lt(\Carbon\Carbon::parse($proximoEvento->fecha_inicio))) {
          $proximoEvento = $entrada->evento;
        }
      }
    }
  }
?>

<section style="background:linear-gradient(160deg,#07060c 0%,#130228 55%,#0d0820 100%);
                padding:2.5rem 0 2rem;border-bottom:1px solid rgba(124,58,237,0.15);">
  <div style="max-width:720px;margin:0 auto;padding:0 1.5rem;">

    
    <a href="<?php echo e(route('home')); ?>"
       style="display:inline-flex;align-items:center;gap:8px;text-decoration:none;
              color:rgba(245,241,234,0.4);font-family:'Archivo Narrow',sans-serif;
              font-size:0.72rem;text-transform:uppercase;letter-spacing:0.1em;
              margin-bottom:1.5rem;transition:color 0.15s;"
       onmouseover="this.style.color='#c084fc'"
       onmouseout="this.style.color='rgba(245,241,234,0.4)'">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
      </svg>
      Volver
    </a>

    <h1 style="font-family:'Anton',sans-serif;font-size:2.4rem;color:#fff;
               margin:0 0 0.3rem;line-height:1;text-transform:uppercase;letter-spacing:0.02em;">
      MIS ENTRADAS
    </h1>
    <p style="font-family:'Archivo Narrow',sans-serif;color:rgba(245,241,234,0.38);
              font-size:0.8rem;text-transform:uppercase;letter-spacing:0.1em;margin:0;">
      <?php echo e($pedidos->sum(fn($p) => $p->entradas->count())); ?>

      <?php echo e($pedidos->sum(fn($p) => $p->entradas->count()) === 1 ? 'entrada' : 'entradas'); ?> en total
    </p>

    
    <?php if($proximoEvento): ?>
    <div class="me-cnt-row"
         data-fecha="<?php echo e(\Carbon\Carbon::parse($proximoEvento->fecha_inicio)->toISOString()); ?>"
         style="margin-top:2rem;background:rgba(124,58,237,0.1);
                border:1px solid rgba(124,58,237,0.32);border-radius:18px;
                padding:20px 24px;display:flex;align-items:center;
                justify-content:space-between;gap:20px;backdrop-filter:blur(12px);">

      <div>
        <p style="font-family:'Archivo Narrow',sans-serif;font-size:0.6rem;
                  text-transform:uppercase;letter-spacing:0.14em;
                  color:#a855f7;margin:0 0 5px;">
          Próximo evento
        </p>
        <p style="font-family:'Anton',sans-serif;font-size:1rem;color:#fff;
                  margin:0 0 4px;text-transform:uppercase;letter-spacing:0.02em;">
          <?php echo e($proximoEvento->titulo); ?>

        </p>
        <p style="font-family:'Archivo Narrow',sans-serif;font-size:0.7rem;
                  color:rgba(245,241,234,0.35);margin:0;letter-spacing:0.06em;">
          <?php echo e(\Carbon\Carbon::parse($proximoEvento->fecha_inicio)->locale('es')->isoFormat('ddd D MMM YYYY · HH:mm')); ?>

        </p>
      </div>

      <div class="me-cnt-timer" style="display:flex;align-items:center;gap:6px;flex-shrink:0;">
        <div class="me-cnt-unit">
          <span class="me-cnt-num" id="cnt-dias"
                style="font-family:'Anton',sans-serif;font-size:1.4rem;color:#fff;line-height:1;">--</span>
          <span style="font-family:'Archivo Narrow',sans-serif;font-size:0.5rem;
                       text-transform:uppercase;letter-spacing:0.1em;
                       color:rgba(245,241,234,0.35);margin-top:3px;">días</span>
        </div>
        <span style="font-family:'Anton',sans-serif;font-size:1.2rem;
                     color:rgba(168,85,247,0.5);padding-bottom:14px;line-height:1;">:</span>
        <div class="me-cnt-unit">
          <span class="me-cnt-num" id="cnt-horas"
                style="font-family:'Anton',sans-serif;font-size:1.4rem;color:#fff;line-height:1;">--</span>
          <span style="font-family:'Archivo Narrow',sans-serif;font-size:0.5rem;
                       text-transform:uppercase;letter-spacing:0.1em;
                       color:rgba(245,241,234,0.35);margin-top:3px;">horas</span>
        </div>
        <span style="font-family:'Anton',sans-serif;font-size:1.2rem;
                     color:rgba(168,85,247,0.5);padding-bottom:14px;line-height:1;">:</span>
        <div class="me-cnt-unit">
          <span class="me-cnt-num" id="cnt-min"
                style="font-family:'Anton',sans-serif;font-size:1.4rem;color:#fff;line-height:1;">--</span>
          <span style="font-family:'Archivo Narrow',sans-serif;font-size:0.5rem;
                       text-transform:uppercase;letter-spacing:0.1em;
                       color:rgba(245,241,234,0.35);margin-top:3px;">min</span>
        </div>
        <span style="font-family:'Anton',sans-serif;font-size:1.2rem;
                     color:rgba(168,85,247,0.5);padding-bottom:14px;line-height:1;">:</span>
        <div class="me-cnt-unit">
          <span class="me-cnt-num" id="cnt-seg"
                style="font-family:'Anton',sans-serif;font-size:1.4rem;color:#fff;line-height:1;">--</span>
          <span style="font-family:'Archivo Narrow',sans-serif;font-size:0.5rem;
                       text-transform:uppercase;letter-spacing:0.1em;
                       color:rgba(245,241,234,0.35);margin-top:3px;">seg</span>
        </div>
      </div>
    </div>
    <?php endif; ?>

  </div>
</section>


<div style="background:radial-gradient(circle,rgba(124,58,237,0.09) 1.5px,transparent 1.5px),
                        linear-gradient(160deg,#07060c 0%,#0d0820 45%,#0e0722 75%,#07060c 100%);
            background-size:28px 28px,100% 100%;min-height:60vh;padding:2.5rem 0 5rem;">
<div style="max-width:720px;margin:0 auto;padding:0 1.5rem;">

  <?php if($pedidos->isEmpty()): ?>

    
    <div style="text-align:center;padding:4rem 1.5rem;">
      <div style="width:64px;height:64px;background:rgba(124,58,237,0.12);
                  border:2px solid rgba(124,58,237,0.28);border-radius:50%;
                  display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
        <svg width="32" height="32" fill="none" viewBox="0 0 24 24"
             stroke="#7c3aed" stroke-width="1.5">
          <path stroke-linecap="round" stroke-linejoin="round"
                d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
        </svg>
      </div>
      <p style="font-family:'Anton',sans-serif;font-size:1.4rem;color:#f5f1ea;
                margin:0 0 0.5rem;text-transform:uppercase;letter-spacing:0.02em;">
        Aún no tienes entradas
      </p>
      <p style="font-family:'Archivo',sans-serif;color:rgba(245,241,234,0.35);
                font-size:0.9rem;margin:0 0 2rem;">
        Explora los eventos disponibles y compra tu primera entrada.
      </p>
      <a href="<?php echo e(route('home')); ?>"
         style="display:inline-block;background:linear-gradient(135deg,#7c3aed,#a855f7);
                color:#fff;padding:12px 28px;border-radius:999px;
                font-family:'Archivo Narrow',sans-serif;font-size:0.85rem;
                font-weight:700;text-transform:uppercase;letter-spacing:0.1em;
                text-decoration:none;box-shadow:0 4px 20px rgba(124,58,237,0.4);">
        Explorar eventos
      </a>
    </div>

  <?php else: ?>

    
    <div class="me-filtros" style="display:flex;gap:8px;margin-bottom:2rem;">
      <button class="me-filtro-btn activo" onclick="filtrarEntradas('todas', this)">Todas</button>
      <button class="me-filtro-btn" onclick="filtrarEntradas('activas', this)">Activas</button>
      <button class="me-filtro-btn" onclick="filtrarEntradas('usadas', this)">Usadas</button>
    </div>

    
    <?php $__currentLoopData = $pedidos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pedido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php
        $evento       = $pedido->entradas->first()?->evento;
        $tieneActivas = $pedido->entradas->contains('estado_entrada', 1);
        $estadoCard   = $tieneActivas ? 'activas' : 'usadas';
      ?>

      <div class="me-card <?php echo e($estadoCard === 'usadas' ? 'usada' : ''); ?>"
           data-estado="<?php echo e($estadoCard); ?>"
           onclick="toggleTicketQr(<?php echo e($pedido->id); ?>)">

        
        <div class="me-card-header"
             style="padding:20px 24px;background:linear-gradient(135deg,#060011,#1a0f35);
                    display:flex;justify-content:space-between;align-items:flex-start;gap:16px;">

          <div style="flex:1;min-width:0;">
            <?php if($tieneActivas): ?>
              <span class="me-badge-activa">Activa</span>
            <?php else: ?>
              <span class="me-badge-usada">Usada</span>
            <?php endif; ?>

            <h3 style="font-family:'Anton',sans-serif;font-size:1.1rem;color:#fff;
                       margin:0 0 8px;line-height:1.15;text-transform:uppercase;
                       letter-spacing:0.02em;white-space:nowrap;overflow:hidden;
                       text-overflow:ellipsis;">
              <?php echo e($evento?->titulo ?? 'Evento eliminado'); ?>

            </h3>

            <?php if($evento): ?>
              <p style="display:flex;align-items:center;gap:6px;font-family:'Archivo Narrow',sans-serif;
                        font-size:0.72rem;color:rgba(245,241,234,0.4);margin:0 0 3px;
                        text-transform:uppercase;letter-spacing:0.06em;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" style="flex-shrink:0">
                  <rect x="3" y="4" width="18" height="18" rx="2"/>
                  <line x1="16" y1="2" x2="16" y2="6"/>
                  <line x1="8" y1="2" x2="8" y2="6"/>
                  <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <?php echo e(\Carbon\Carbon::parse($evento->fecha_inicio)->locale('es')->isoFormat('ddd D MMM YYYY · HH:mm')); ?>

              </p>
              <?php if($evento->ubicacion_nombre): ?>
              <p style="display:flex;align-items:center;gap:6px;font-family:'Archivo Narrow',sans-serif;
                        font-size:0.72rem;color:rgba(245,241,234,0.4);margin:0;
                        text-transform:uppercase;letter-spacing:0.06em;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" style="flex-shrink:0">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                  <circle cx="12" cy="10" r="3"/>
                </svg>
                <?php echo e($evento->ubicacion_nombre); ?>

              </p>
              <?php endif; ?>
            <?php endif; ?>
          </div>

          <div style="display:flex;flex-direction:column;align-items:flex-end;
                      gap:4px;flex-shrink:0;">
            <span style="font-family:'Anton',sans-serif;font-size:1.8rem;
                         color:rgba(168,85,247,0.6);line-height:1;">
              <?php echo e($pedido->entradas->count()); ?>×
            </span>
            <p style="margin:0;font-family:'Archivo',sans-serif;font-weight:800;font-size:0.9rem;">
              <?php if($pedido->total_final == 0): ?>
                <span style="color:#34d399;">Gratis</span>
              <?php else: ?>
                <span style="background:linear-gradient(135deg,#7c3aed,#a855f7);
                             -webkit-background-clip:text;-webkit-text-fill-color:transparent;
                             background-clip:text;">
                  <?php echo e(number_format($pedido->total_final, 2)); ?>€
                </span>
              <?php endif; ?>
            </p>
            <span class="me-chevron" id="chevron-<?php echo e($pedido->id); ?>"
                  style="color:rgba(245,241,234,0.28);margin-top:4px;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                   stroke="currentColor" stroke-width="2.5">
                <path d="M6 9l6 6 6-6"/>
              </svg>
            </span>
          </div>
        </div>

        
        <div class="me-sep">
          <div class="me-circ" style="left:-11px;"></div>
          <div class="me-circ" style="right:-11px;"></div>
        </div>

        
        <div id="qr-panel-<?php echo e($pedido->id); ?>" class="me-qr-panel" style="display:none">

          <?php if($pedido->entradas->count() > 1): ?>
            <p style="font-family:'Archivo Narrow',sans-serif;font-size:0.68rem;
                      text-transform:uppercase;letter-spacing:0.1em;
                      color:rgba(245,241,234,0.3);margin:0 0 18px;text-align:center;">
              <?php echo e($pedido->entradas->count()); ?> entradas · un QR por persona
            </p>
          <?php endif; ?>

          <div style="display:flex;gap:18px;justify-content:center;flex-wrap:wrap;">
            <?php $__currentLoopData = $pedido->entradas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $entrada): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div style="display:flex;flex-direction:column;align-items:center;gap:8px;">
                <div class="me-qr-marco">
                  
                  <div id="qr-canvas-<?php echo e($entrada->id); ?>"
                       data-codigo="<?php echo e($entrada->codigo_qr); ?>"
                       style="width:180px;height:180px;display:block;"></div>
                </div>
                <p style="font-family:'Archivo Narrow',sans-serif;font-size:0.75rem;
                          font-weight:700;color:rgba(245,241,234,0.65);margin:0;
                          text-transform:uppercase;letter-spacing:0.08em;">
                  Entrada #<?php echo e($i + 1); ?>

                </p>
                <p style="font-family:monospace;font-size:0.55rem;
                          color:rgba(245,241,234,0.22);margin:0;">
                  <?php echo e(substr($entrada->codigo_qr, 0, 20)); ?>…
                </p>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>

          <p style="text-align:center;font-family:'Archivo Narrow',sans-serif;font-size:0.7rem;
                    color:rgba(245,241,234,0.28);margin:18px 0 0;
                    text-transform:uppercase;letter-spacing:0.08em;">
            Presenta este QR en la entrada del evento
          </p>
        </div>

      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    <div id="me-no-resultados" style="display:none;text-align:center;padding:3rem 1.5rem;">
      <p style="font-family:'Anton',sans-serif;font-size:1.2rem;color:rgba(245,241,234,0.4);
                text-transform:uppercase;letter-spacing:0.02em;margin:0;">
        Sin entradas en esta categoría
      </p>
    </div>

  <?php endif; ?>

</div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="<?php echo e(asset('js/entradas-mis-entradas.js')); ?>"></script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/entradas/mis-entradas.blade.php ENDPATH**/ ?>
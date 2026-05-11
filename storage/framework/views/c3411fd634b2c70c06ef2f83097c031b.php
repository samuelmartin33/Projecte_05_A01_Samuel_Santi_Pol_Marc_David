
<?php
  $primerEvento = $eventos->first();
  $ticketsCount = $entradas ? count($entradas) : 0;
  $favCount     = $user->favoritos()->count();
  $nombre       = explode(' ', $user->nombre ?? $user->name ?? '')[0];
?>

<section style="padding:60px 48px 40px;max-width:1480px;margin:0 auto;position:relative;">
  <div style="display:grid;grid-template-columns:1.4fr 1fr;gap:32px;" class="logged-hero-grid">

    
    <div>
      <div class="mono" style="font-size:11px;color:var(--magenta);margin-bottom:14px;display:flex;align-items:center;gap:10px;">
        <span style="width:28px;height:1px;background:var(--magenta);display:inline-block;"></span>
        Hola de nuevo, <?php echo e($nombre); ?>

      </div>
      <h1 class="display glow-magenta" style="font-size:clamp(56px,9vw,132px);margin:0;line-height:0.9;">
        Tu próxima<br>
        <em style="font-style:italic;color:var(--magenta);font-family:'Bebas Neue',sans-serif;">fiesta</em> empieza ya.
      </h1>
      <p style="font-family:'Archivo Narrow',sans-serif;font-size:18px;color:var(--ink-dim);max-width:540px;margin:24px 0 28px;text-transform:uppercase;letter-spacing:0.08em;line-height:1.5;">
        Tienes <strong style="color:var(--ink);"><?php echo e($ticketsCount); ?> tickets activos</strong>
        · seguiste a <strong style="color:var(--ink);"><?php echo e($favCount); ?> promotores</strong>
      </p>
      <div style="display:flex;gap:12px;flex-wrap:wrap;">
        <a href="<?php echo e(route('entradas.mis-entradas')); ?>" class="btn-primary" style="padding:16px 28px;border-radius:999px;font-size:15px;text-decoration:none;">
          Mis tickets →
        </a>
        <?php if(\Route::has('cupones.index')): ?>
        <a href="<?php echo e(route('cupones.index')); ?>" class="btn-ghost" style="padding:16px 24px;border-radius:999px;font-size:13px;text-decoration:none;">
          Cupones
        </a>
        <?php else: ?>
        <a href="#" class="btn-ghost" style="padding:16px 24px;border-radius:999px;font-size:13px;text-decoration:none;">
          Cupones
        </a>
        <?php endif; ?>
      </div>
    </div>

    
    <?php if($primerEvento): ?>
    <div onclick="vibezOpenModal(<?php echo e($primerEvento->id); ?>)"
         style="position:relative;border-radius:18px;overflow:hidden;cursor:pointer;border:1px solid rgba(168,85,247,0.3);min-height:320px;box-shadow:0 20px 50px rgba(0,0,0,0.4),0 0 30px rgba(168,85,247,0.15);transition:transform 0.3s ease,box-shadow 0.3s ease;"
         onmouseenter="this.style.transform='translateY(-4px)';this.style.boxShadow='0 28px 60px rgba(0,0,0,0.5),0 0 40px rgba(168,85,247,0.25)'"
         onmouseleave="this.style.transform='';this.style.boxShadow='0 20px 50px rgba(0,0,0,0.4),0 0 30px rgba(168,85,247,0.15)'">
      <img src="<?php echo e($primerEvento->url_portada); ?>" alt=""
           style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;filter:contrast(1.05) brightness(0.6);">
      <div style="position:absolute;inset:0;background:linear-gradient(180deg,rgba(7,6,12,0.2) 0%,rgba(7,6,12,0.95) 100%);"></div>
      <div style="position:relative;padding:24px;height:100%;display:flex;flex-direction:column;justify-content:space-between;min-height:320px;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;">
          <span class="mono" style="font-size:10px;color:var(--magenta-2);background:rgba(168,85,247,0.18);border:1px solid rgba(168,85,247,0.4);padding:4px 10px;border-radius:999px;">
            ★ TU PRÓXIMO EVENTO
          </span>
          <span class="mono" style="font-size:10px;color:var(--ink-dim);"><?php echo e($primerEvento->fecha_fmt); ?></span>
        </div>
        <div>
          <h3 class="display" style="font-size:32px;margin:0 0 6px;line-height:0.95;"><?php echo e($primerEvento->titulo); ?></h3>
          <p class="mono" style="font-size:11px;color:var(--ink-dim);margin:0;">
            <?php echo e($primerEvento->ubicacion_nombre); ?> · <?php echo e($primerEvento->hora); ?>

          </p>
        </div>
      </div>
    </div>
    <?php endif; ?>

  </div>
</section>
<?php /**PATH C:\wamp64\www\DAW2\proyectos\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/partials/home/logged-hero.blade.php ENDPATH**/ ?>
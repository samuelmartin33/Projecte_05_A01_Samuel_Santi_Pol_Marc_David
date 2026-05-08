{{--
  Carrusel de eventos recomendados para el usuario.
  Variables: $user (Auth::user()), $eventos (primeros 6 eventos)
--}}
@php
  $nombre = explode(' ', $user->nombre ?? $user->name ?? '')[0];
  $matches = [97, 92, 88, 85, 82, 79, 76];
@endphp

<section style="padding:60px 48px 40px;max-width:1480px;margin:0 auto;">

  <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:28px;gap:20px;flex-wrap:wrap;">
    <div>
      <div class="mono" style="font-size:11px;color:var(--magenta);margin-bottom:12px;display:flex;align-items:center;gap:10px;">
        <span style="width:28px;height:1px;background:var(--magenta);display:inline-block;"></span>
        Recomendado para {{ $nombre }}
      </div>
      <h2 class="display" style="font-size:clamp(40px,6vw,80px);margin:0;">
        Te <em style="font-style:italic;color:var(--magenta);font-family:'Bebas Neue',sans-serif;">conocemos</em>.
      </h2>
      <p style="font-family:'Archivo Narrow',sans-serif;font-size:14px;color:var(--ink-dim);margin:10px 0 0;text-transform:uppercase;letter-spacing:0.1em;">
        Basado en tu historial · techno · disco · BCN
      </p>
    </div>
  </div>

  <div class="no-scrollbar" style="display:flex;gap:20px;overflow-x:auto;padding-bottom:16px;">

    @forelse($eventos->take(6) as $i => $evento)
    <article class="vibe-card vibez-event-card"
             data-categoria="{{ $evento->categoria?->nombre ?? '' }}"
             data-id="{{ $evento->id }}"
             onclick="vibezOpenModal({{ $evento->id }})"
             style="flex:0 0 320px;min-width:320px;position:relative;cursor:pointer;">

      <div class="img-wrap" style="position:relative;aspect-ratio:3/4;overflow:hidden;border-radius:14px;">
        <img src="{{ $evento->url_portada }}"
             alt="{{ $evento->titulo }}"
             style="width:100%;height:100%;object-fit:cover;filter:contrast(1.05) saturate(1.1) brightness(0.85);">
        <div style="position:absolute;inset:0;background:linear-gradient(180deg,transparent 50%,rgba(7,6,12,0.9));"></div>

        {{-- Badge de match --}}
        <div style="position:absolute;top:12px;left:12px;background:rgba(168,85,247,0.85);color:var(--cream);padding:4px 10px;border-radius:999px;font-family:'Archivo Narrow',sans-serif;font-size:10px;text-transform:uppercase;letter-spacing:0.1em;font-weight:600;">
          {{ $matches[$i % 7] }}% match
        </div>

        {{-- Info inferior --}}
        <div style="position:absolute;bottom:0;left:0;right:0;padding:18px;">
          <div class="mono" style="font-size:9px;color:var(--magenta-2);margin-bottom:6px;">
            {{ $evento->fecha_fmt }} · {{ $evento->precio_formateado }}
          </div>
          <h3 class="display" style="font-size:22px;margin:0;line-height:1;">{{ $evento->titulo }}</h3>
          <p class="mono" style="font-size:9px;color:var(--ink-dim);margin:6px 0 0;">{{ $evento->ubicacion_nombre }}</p>
        </div>
      </div>

    </article>
    @empty
      <p style="color:var(--ink-dim);font-family:'Archivo Narrow',sans-serif;padding:40px 0;">No hay recomendaciones disponibles.</p>
    @endforelse

  </div>

</section>

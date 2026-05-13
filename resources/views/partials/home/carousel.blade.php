{{--
  Carousel de eventos.
  Variables esperadas: $eventos (collection), $carouselId, $kicker, $titulo, $subtitulo
  Opcionales: $big (bool, tarjetas más grandes)
--}}
@php
  $carouselId = $carouselId ?? 'carousel-main';
  $kicker     = $kicker     ?? 'Top picks · curados por VIBEZ';
  $titulo     = $titulo     ?? 'Lo que rompe';
  $subtitulo  = $subtitulo  ?? 'Esta semana — selección editorial';
  $big        = $big        ?? false;
@endphp

<section style="padding:90px 48px 0;max-width:1480px;margin:0 auto;">

  {{-- Cabecera con controles --}}
  <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:36px;gap:20px;flex-wrap:wrap;">
    <div>
      <div class="mono" style="font-size:11px;color:var(--magenta);margin-bottom:12px;display:flex;align-items:center;gap:10px;">
        <span style="width:28px;height:1px;background:var(--magenta);display:inline-block;"></span>
        {{ $kicker }}
      </div>
      <h2 id="{{ $carouselId }}-titulo" class="display" style="font-size:clamp(48px,6vw,96px);margin:0;color:var(--ink);">{{ $titulo }}</h2>
      @if($subtitulo)
        <p style="font-family:'Archivo Narrow',sans-serif;font-size:16px;color:var(--ink-dim);margin:12px 0 0;text-transform:uppercase;letter-spacing:0.1em;">{{ $subtitulo }}</p>
      @endif
    </div>
    <div style="display:flex;gap:8px;">
      <button onclick="vibezScrollCarousel('{{ $carouselId }}', -1)"
              style="width:48px;height:48px;border-radius:50%;border:1px solid var(--ink-faint);background:transparent;color:var(--ink);cursor:pointer;font-size:18px;">←</button>
      <button onclick="vibezScrollCarousel('{{ $carouselId }}', 1)"
              style="width:48px;height:48px;border-radius:50%;border:1px solid var(--ink-faint);background:var(--magenta);color:var(--cream);cursor:pointer;font-size:18px;">→</button>
    </div>
  </div>

  {{-- Scroll horizontal --}}
  <div id="{{ $carouselId }}" class="scroll-x no-scrollbar cards-row" style="display:flex;gap:20px;overflow-x:auto;padding-bottom:16px;">

    @forelse($eventos as $idx => $evento)
      <article class="vibe-card vibez-event-card"
               data-categoria="{{ $evento->categoria?->nombre ?? '' }}"
               data-id="{{ $evento->id }}"
               onclick="vibezOpenModal({{ $evento->id }})"
               style="flex:0 0 {{ $big ? '540px' : '360px' }};min-width:{{ $big ? '540px' : '360px' }};cursor:pointer;">

        <div class="img-wrap" style="position:relative;aspect-ratio:{{ $big ? '4/5' : '3/4' }};overflow:hidden;">
          <img src="{{ $evento->url_portada }}"
               alt="{{ $evento->titulo }}"
               style="width:100%;height:100%;object-fit:cover;filter:contrast(1.05) saturate(1.1) brightness(0.85);">
          <div style="position:absolute;inset:0;background:linear-gradient(180deg,transparent 50%,rgba(7,6,12,0.85) 100%);"></div>

          {{-- Número de índice --}}
          <div class="num-big" style="position:absolute;top:12px;left:16px;font-size:{{ $big ? '96px' : '72px' }};">
            {{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}
          </div>

          {{-- Sold out --}}
          @if($evento->sold_out)
            <div style="position:absolute;top:18px;right:18px;background:var(--cream);color:var(--bg);padding:4px 12px;font-family:'Anton',sans-serif;font-size:11px;letter-spacing:0.05em;transform:rotate(4deg);">
              SOLD OUT
            </div>
          @endif

          {{-- Botón favorito --}}
          <button onclick="event.stopPropagation(); vibezToggleFav({{ $evento->id }}, this)"
                  style="position:absolute;top:18px;right:{{ $evento->sell_out ? '90px' : '18px' }};width:38px;height:38px;border-radius:50%;background:rgba(7,6,12,0.55);border:1px solid var(--ink-faint);color:var(--ink);backdrop-filter:blur(10px);cursor:pointer;display:flex;align-items:center;justify-content:center;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
          </button>

          {{-- Info inferior --}}
          <div style="position:absolute;bottom:0;left:0;right:0;padding:20px;">
            <div class="mono" style="font-size:10px;color:var(--magenta-2);margin-bottom:8px;display:flex;justify-content:space-between;">
              <span>{{ $evento->fecha_fmt }} · {{ $evento->categoria?->nombre ?? '' }}</span>
              <span>{{ $evento->precio_formateado }}</span>
            </div>
            <h3 class="display" style="font-size:{{ $big ? '44px' : '30px' }};margin:0;line-height:0.95;color:var(--ink);">
              {{ $evento->titulo }}
            </h3>
            <p style="font-family:'Archivo Narrow',sans-serif;font-size:13px;color:var(--ink-dim);margin:10px 0 0;text-transform:uppercase;letter-spacing:0.08em;">
              {{ $evento->ubicacion_nombre }}
            </p>
          </div>
        </div>

      </article>
    @empty
      <p style="color:var(--ink-dim);font-family:'Archivo Narrow',sans-serif;padding:40px 0;">No hay eventos disponibles.</p>
    @endforelse

  </div>

</section>

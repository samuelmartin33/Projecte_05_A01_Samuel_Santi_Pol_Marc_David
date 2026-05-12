@php
  $e = $eventoFeatured;
@endphp

<section class="hero-poster" style="position:relative;min-height:92vh;overflow:hidden;display:flex;flex-direction:column;">

  {{-- Imagen full-bleed con parallax --}}
  <div style="position:absolute;inset:0;overflow:hidden;">
    <img id="hero-parallax-img"
         class="parallax-img"
         src="{{ $e ? $e->url_portada : 'https://picsum.photos/seed/vibez-hero/1600/900' }}"
         alt="{{ $e ? $e->titulo : 'VIBEZ' }}"
         style="width:100%;height:115%;object-fit:cover;transform:translateY(0) scale(1.05);filter:contrast(1.05) saturate(1.15) brightness(0.78);">
    {{-- Gradiente oscuro --}}
    <div style="position:absolute;inset:0;background:linear-gradient(180deg,rgba(7,6,12,0.4) 0%,rgba(7,6,12,0.1) 30%,rgba(7,6,12,0.6) 75%,rgba(7,6,12,0.95) 100%);"></div>
    {{-- Wash morado --}}
    <div style="position:absolute;inset:0;background:radial-gradient(ellipse at 80% 20%,rgba(168,85,247,0.35) 0%,transparent 50%);mix-blend-mode:screen;"></div>
  </div>

  {{-- Badge fila superior --}}
  <div style="position:relative;z-index:5;padding:32px 48px 0;display:flex;justify-content:space-between;align-items:center;">
    <div class="mono" style="font-size:11px;color:var(--ink-dim);display:flex;align-items:center;gap:10px;">
      <span class="pulse-dot" style="width:8px;height:8px;border-radius:50%;background:var(--magenta);display:inline-block;"></span>
      En vivo · {{ $e?->ubicacion_nombre ?? 'Barcelona' }}
    </div>
    <div class="mono" style="font-size:11px;color:var(--ink-dim);">
      Edición #428 · {{ now()->isoFormat('MMMM YYYY') }}
    </div>
  </div>

  {{-- Contenido principal --}}
  <div style="position:relative;z-index:5;flex:1;display:flex;flex-direction:column;justify-content:flex-end;padding:0 48px 56px;max-width:1480px;width:100%;margin:0 auto;">
    <div style="display:flex;align-items:flex-end;justify-content:space-between;gap:48px;flex-wrap:wrap;">

      {{-- Izquierda: título + CTA --}}
      <div style="flex:1 1 600px;max-width:900px;">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:28px;">
          <span class="sticker" style="font-size:14px;padding:6px 14px;">★ FEATURED · Esta noche</span>
          <span class="mono" style="font-size:11px;color:var(--ink-dim);">{{ $e?->categoria?->nombre ?? 'Evento' }}</span>
        </div>

        <h1 class="display glow-magenta" style="font-size:clamp(64px,11vw,188px);margin:0;color:var(--ink);">
          @if($e)
            @php $partes = explode(' × ', $e->titulo); @endphp
            @foreach($partes as $i => $parte)
              <span style="display:block;">
                @if($i === 1)
                  <em style="font-style:italic;color:var(--magenta);font-family:'Bebas Neue',sans-serif;">{{ $parte }}</em>
                @else
                  {{ $parte }}
                @endif
              </span>
            @endforeach
          @else
            <span style="display:block;">VIBEZ</span>
            <em style="display:block;font-style:italic;color:var(--magenta);font-family:'Bebas Neue',sans-serif;">Esta Noche</em>
          @endif
        </h1>

        @if($e?->tagline)
          <p style="font-family:'Archivo Narrow',sans-serif;font-size:22px;color:var(--cream);margin:20px 0 6px;font-style:italic;max-width:540px;">
            "{{ $e->tagline }}"
          </p>
        @endif
        @if($e)
          <p class="mono" style="font-size:12px;color:var(--ink-dim);margin:0;">
            feat. {{ $e->organizador?->empresa?->nombre ?? $e->organizador?->nombre_empresa ?? '' }}
          </p>
        @endif

        <div style="display:flex;gap:14px;margin-top:36px;flex-wrap:wrap;">
          @if($e)
            <button class="btn-primary" onclick="vibezOpenModal({{ $e->id }})"
                    style="padding:18px 36px;border-radius:999px;font-size:18px;">
              Comprar entrada · {{ $e->precio_formateado }}
            </button>
          @endif
          <button class="btn-ghost" style="padding:18px 28px;border-radius:999px;font-size:14px;">
            ♡ Guardar
          </button>
        </div>
      </div>

      {{-- Derecha: countdown + meta --}}
      @if($e)
      <div style="flex:0 0 320px;display:flex;flex-direction:column;gap:24px;">

        {{-- Countdown --}}
        <div>
          <div class="mono" style="font-size:10px;color:var(--ink-dim);margin-bottom:10px;">Empieza en</div>
          <div style="display:flex;gap:8px;">
            @foreach([['cd-dias','días'],['cd-horas','h'],['cd-minutos','min'],['cd-segundos','seg']] as $i => [$cdId, $cdLabel])
              <div style="flex:1;padding:14px 6px;text-align:center;background:rgba(7,6,12,0.5);border:1px solid var(--line);backdrop-filter:blur(8px);">
                <div id="{{ $cdId }}" class="display" style="font-size:32px;color:{{ $i === 3 ? 'var(--magenta)' : 'var(--ink)' }};">00</div>
                <div class="mono" style="font-size:9px;color:var(--ink-dim);margin-top:4px;">{{ $cdLabel }}</div>
              </div>
            @endforeach
          </div>
        </div>

        {{-- Meta del evento --}}
        <div style="border-top:1px solid var(--line);padding-top:16px;display:flex;flex-direction:column;gap:10px;">
          @foreach([['Fecha', $e->fecha_fmt], ['Horario', $e->hora], ['Sala', $e->ubicacion_nombre], ['Aforo', $e->cupos_disponibles ? ($e->cupos_disponibles < 50 ? 'Quedan ' . $e->cupos_disponibles : '+ ' . $e->cupos_disponibles) : 'Libre']] as [$meta, $valor])
            <div style="display:flex;justify-content:space-between;align-items:center;">
              <span class="mono" style="font-size:10px;color:var(--ink-dim);">{{ $meta }}</span>
              <span style="font-size:14px;font-weight:600;">{{ $valor }}</span>
            </div>
          @endforeach
        </div>

      </div>
      @endif

    </div>
  </div>

  {{-- Texto vertical decorativo --}}
  <div class="mono vertical-text" style="position:absolute;right:16px;top:50%;font-size:10px;color:var(--ink-dim);letter-spacing:0.3em;">
    VIBEZ · NIGHT EDITION {{ date('y') }} · BCN
  </div>

</section>

@if($e)
<script>vibezStartCountdown('{{ $e->fecha_inicio->toIso8601String() }}');</script>
@endif

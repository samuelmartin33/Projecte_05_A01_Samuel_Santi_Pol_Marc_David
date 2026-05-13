{{--
  Grid de tickets activos del usuario.
  Variables: $entradas (array/collection de entradas con ->evento)
--}}
<section style="padding:60px 48px 40px;max-width:1480px;margin:0 auto;">

  <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:28px;gap:20px;flex-wrap:wrap;">
    <div>
      <div class="mono" style="font-size:11px;color:var(--magenta);margin-bottom:12px;display:flex;align-items:center;gap:10px;">
        <span style="width:28px;height:1px;background:var(--magenta);display:inline-block;"></span>
        Mis tickets · {{ count($entradas ?? []) }} activos
      </div>
      <h2 class="display" style="font-size:clamp(40px,6vw,80px);margin:0;">
        Lista <em style="font-style:italic;color:var(--magenta);font-family:'Bebas Neue',sans-serif;">VIP</em>
      </h2>
    </div>
    <a href="#" class="mono" style="font-size:11px;color:var(--magenta-2);text-decoration:none;border-bottom:1px solid currentColor;">
      Ver historial →
    </a>
  </div>

  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:16px;" class="tickets-grid">

    @forelse($entradas ?? [] as $i => $entrada)
    @php $ev = $entrada->evento ?? null; @endphp
    @if($ev)
    <div onclick="vibezOpenModal({{ $ev->id }})"
         style="display:grid;grid-template-columns:1fr 1px 90px;background:linear-gradient(135deg,rgba(168,85,247,0.08),rgba(13,10,24,0.7));border:1px solid rgba(168,85,247,0.3);border-radius:14px;overflow:hidden;cursor:pointer;position:relative;transition:transform 0.25s ease,box-shadow 0.25s ease;"
         onmouseenter="this.style.transform='translateY(-3px)';this.style.boxShadow='0 14px 30px rgba(168,85,247,0.3)'"
         onmouseleave="this.style.transform='';this.style.boxShadow=''">

      {{-- Info del evento --}}
      <div style="padding:16px 18px;">
        <div class="mono" style="font-size:9px;color:var(--magenta-2);margin-bottom:6px;">
          {{ $ev->categoria?->nombre ?? 'Evento' }} · {{ $entrada->cantidad ?? 1 }}× ENTRADA
        </div>
        <div class="display" style="font-size:18px;line-height:1;margin-bottom:8px;">{{ $ev->titulo }}</div>
        <div style="font-size:11px;color:var(--ink-dim);font-family:'Archivo Narrow',sans-serif;text-transform:uppercase;letter-spacing:0.1em;">
          {{ $ev->fecha_fmt }} · {{ $ev->hora }}
        </div>
        <div style="font-size:11px;color:var(--ink-dim);font-family:'Archivo Narrow',sans-serif;text-transform:uppercase;letter-spacing:0.1em;margin-top:2px;">
          {{ $ev->ubicacion_nombre }}
        </div>
      </div>

      {{-- Separador perforado --}}
      <div style="background:repeating-linear-gradient(0deg,var(--magenta) 0 4px,transparent 4px 8px);"></div>

      {{-- Stub QR --}}
      <div style="padding:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px;">
        <div style="width:56px;height:56px;background:var(--cream);border-radius:6px;padding:4px;display:flex;align-items:center;justify-content:center;">
          {{-- QR simulado con patrón de bloques --}}
          <svg width="48" height="48" viewBox="0 0 48 48">
            <rect width="48" height="48" fill="white"/>
            <g fill="black">
              @for($k = 0; $k < 64; $k++)
                @if((($i * 31 + $k * 7) % 100) < 50)
                  <rect x="{{ ($k % 8) * 6 }}" y="{{ floor($k / 8) * 6 }}" width="6" height="6"/>
                @endif
              @endfor
            </g>
          </svg>
        </div>
        <div class="mono" style="font-size:8px;color:var(--ink-dim);">#{{ $entrada->id ?? ('VBZ-' . ($i + 1)) }}</div>
      </div>

    </div>
    @endif
    @empty
      <p style="color:var(--ink-dim);font-family:'Archivo Narrow',sans-serif;padding:24px 0;grid-column:1/-1;">
        No tienes tickets activos.
      </p>
    @endforelse

  </div>

</section>

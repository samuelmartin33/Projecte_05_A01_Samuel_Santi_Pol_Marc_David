{{--
  Sección "De tus promotoras" en el home.
  Variables:
    $eventosPromotor  — colección de Eventos de promotoras seguidas
    $seguimientosIds  — array de empresa_id seguidos (para el botón)
    $favoritosIds     — array de evento_id favoritos
--}}

<section style="padding:60px 48px 40px;max-width:1480px;margin:0 auto;">

  <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:28px;gap:20px;flex-wrap:wrap;">
    <div>
      <div class="mono" style="font-size:11px;color:var(--magenta);margin-bottom:12px;display:flex;align-items:center;gap:10px;">
        <span style="width:28px;height:1px;background:var(--magenta);display:inline-block;"></span>
        Promotoras que sigues
      </div>
      <h2 class="display" style="font-size:clamp(36px,5vw,72px);margin:0;">
        Tu <em style="font-style:italic;color:var(--magenta);font-family:'Bebas Neue',sans-serif;">escena</em>.
      </h2>
      <p style="font-family:'Archivo Narrow',sans-serif;font-size:14px;color:var(--ink-dim);margin:10px 0 0;text-transform:uppercase;letter-spacing:0.1em;">
        Eventos de las promotoras que sigues
      </p>
    </div>
  </div>

  <div class="no-scrollbar" style="display:flex;gap:20px;overflow-x:auto;padding-bottom:16px;">

    @foreach($eventosPromotor as $evento)
    @php $empresa = $evento->organizador?->empresa; @endphp
    <article class="vibe-card vibez-event-card"
             data-categoria="{{ $evento->categoria?->nombre ?? '' }}"
             data-id="{{ $evento->id }}"
             onclick="vibezOpenModal({{ $evento->id }})"
             style="flex:0 0 300px;min-width:300px;position:relative;cursor:pointer;">

      <div class="img-wrap" style="position:relative;aspect-ratio:3/4;overflow:hidden;border-radius:14px;">
        <img src="{{ $evento->url_portada }}"
             alt="{{ $evento->titulo }}"
             style="width:100%;height:100%;object-fit:cover;filter:contrast(1.05) saturate(1.1) brightness(0.85);">
        <div style="position:absolute;inset:0;background:linear-gradient(180deg,transparent 45%,rgba(7,6,12,0.92));"></div>

        {{-- Badge promotora --}}
        @if($empresa)
        <div style="position:absolute;top:12px;left:12px;display:flex;align-items:center;gap:6px;
                    background:rgba(7,6,12,0.75);backdrop-filter:blur(8px);
                    padding:5px 10px;border:1px solid rgba(168,85,247,0.35);">
          @if($empresa->logo_url)
            <img src="{{ $empresa->logo_url }}" alt="{{ $empresa->nombre_empresa }}"
                 style="width:18px;height:18px;object-fit:cover;border-radius:50%;">
          @else
            <span style="width:18px;height:18px;background:#a855f7;display:flex;align-items:center;justify-content:center;
                         font-size:10px;font-weight:900;color:#fff;border-radius:50%;">
              {{ strtoupper(substr($empresa->nombre_empresa, 0, 1)) }}
            </span>
          @endif
          <span class="mono" style="font-size:9px;color:var(--cream);">{{ $empresa->nombre_empresa }}</span>
        </div>
        @endif

        {{-- Botón seguir encima de la card --}}
        @if($empresa)
        <button class="btn-seguir-home {{ in_array($empresa->id, $seguimientosIds) ? 'siguiendo' : '' }}"
                data-empresa-id="{{ $empresa->id }}"
                style="position:absolute;top:12px;right:12px;"
                onclick="event.stopPropagation(); toggleSeguirHome(this)"
                title="{{ in_array($empresa->id, $seguimientosIds) ? 'Dejar de seguir' : 'Seguir' }}">
          {{ in_array($empresa->id, $seguimientosIds) ? '✓' : '+' }}
        </button>
        @endif

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
    @endforeach

  </div>

</section>

<style>
.btn-seguir-home {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  border: 1.5px solid rgba(168,85,247,0.7);
  background: rgba(7,6,12,0.75);
  backdrop-filter: blur(8px);
  color: #a855f7;
  font-size: 18px;
  font-weight: 900;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.18s, color 0.18s;
  line-height: 1;
}
.btn-seguir-home:hover { background: rgba(168,85,247,0.25); }
.btn-seguir-home.siguiendo { background: rgba(168,85,247,0.3); color: #e9d5ff; border-color: #a855f7; }
.btn-seguir-home.cargando  { opacity: 0.5; pointer-events: none; }
</style>

<script>
async function toggleSeguirHome(btn) {
    const empresaId = btn.dataset.empresaId;
    btn.classList.add('cargando');
    try {
        const res = await fetch(`/api/seguimientos/${empresaId}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        });
        const data = await res.json();
        if (data.success) {
            if (data.siguiendo) {
                btn.classList.add('siguiendo');
                btn.textContent = '✓';
                btn.title = 'Dejar de seguir';
            } else {
                btn.classList.remove('siguiendo');
                btn.textContent = '+';
                btn.title = 'Seguir';
            }
        }
    } catch (e) {
        console.error('Error al seguir promotora', e);
    } finally {
        btn.classList.remove('cargando');
    }
}
</script>

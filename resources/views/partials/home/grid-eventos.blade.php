{{--
  Grid completo de todos los eventos con filtros AJAX.
  Variables: $eventos (collection), $categorias (collection), $favoritosIds (array)
--}}
<section style="padding:90px 48px 0;max-width:1480px;margin:0 auto;" id="seccion-grid-eventos">

  {{-- Cabecera --}}
  <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:36px;gap:20px;flex-wrap:wrap;">
    <div>
      <div class="mono" style="font-size:11px;color:var(--magenta);margin-bottom:12px;display:flex;align-items:center;gap:10px;">
        <span style="width:28px;height:1px;background:var(--magenta);display:inline-block;"></span>
        Todos los eventos · curados por VIBEZ
      </div>
      <h2 class="display" style="font-size:clamp(48px,6vw,96px);margin:0;color:var(--ink);">
        Top <em style="font-style:italic;color:var(--magenta);font-family:'Bebas Neue',sans-serif;">Picks</em>
      </h2>
      <p style="font-family:'Archivo Narrow',sans-serif;font-size:14px;color:var(--ink-dim);margin:10px 0 0;text-transform:uppercase;letter-spacing:0.1em;">
        <span id="vibez-grid-count">{{ $eventos->count() }}</span> eventos disponibles
      </p>
    </div>

    {{-- Filtros --}}
    <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
      {{-- Hidden input leído por vibezGridFiltrar / vibezGridLimpiar --}}
      <input type="hidden" id="grid-filtro-ubicacion" value="">
      <div class="ev-csel" id="ev-filtro-ub" style="min-width:180px;">
        <div class="ev-csel-trigger" onclick="cselToggle('ev-filtro-ub')"
             style="padding:10px 16px;font-family:'Archivo Narrow',sans-serif;font-size:12px;text-transform:uppercase;letter-spacing:0.06em;border-color:var(--line);">
          <span id="ev-filtro-ub-label" class="ev-csel-placeholder">Todas las ciudades</span>
          <span class="ev-csel-arrow">▾</span>
        </div>
        <ul class="ev-csel-menu">
          <li class="ev-csel-opt selected"
              onclick="cselPick('ev-filtro-ub','grid-filtro-ubicacion','','Todas las ciudades',this);vibezFiltrarCiudad()">
            Todas las ciudades
          </li>
          @foreach($ubicaciones ?? [] as $ub)
            <li class="ev-csel-opt"
                onclick="cselPick('ev-filtro-ub','grid-filtro-ubicacion','{{ $ub }}','{{ $ub }}',this);vibezFiltrarCiudad()">
              {{ $ub }}
            </li>
          @endforeach
        </ul>
      </div>

      <button onclick="vibezGridLimpiar()"
              style="background:transparent;border:1px solid var(--ink-faint);color:var(--ink-dim);padding:10px 16px;font-family:'Archivo Narrow',sans-serif;font-size:11px;text-transform:uppercase;letter-spacing:0.08em;cursor:pointer;">
        Limpiar
      </button>
    </div>
  </div>

  {{-- Spinner --}}
  <div id="vibez-grid-spinner" style="display:none;justify-content:center;padding:48px 0;">
    <div style="width:32px;height:32px;border-radius:50%;border:2px solid var(--line);border-top-color:var(--magenta);animation:vibez-spin 0.8s linear infinite;"></div>
  </div>

  {{-- Grid de tarjetas --}}
  <div id="vibez-grid-todos"
       style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px;">

    @forelse($eventos as $evento)
      @php
        $esFav     = in_array($evento->id, $favoritosIds ?? [], true);
        $empresa   = $evento->organizador?->empresa;
        $esSiguiendo = $empresa && in_array($empresa->id, $seguimientosIds ?? [], true);
      @endphp
      <article class="vibe-card vibez-grid-card vibez-event-card"
               data-id="{{ $evento->id }}"
               data-categoria="{{ $evento->categoria?->nombre ?? '' }}"
               onclick="vibezOpenModal({{ $evento->id }})"
               style="cursor:pointer;">

        <div class="img-wrap" style="position:relative;aspect-ratio:3/4;overflow:hidden;">
          <img src="{{ $evento->url_portada }}"
               alt="{{ $evento->titulo }}"
               style="width:100%;height:100%;object-fit:cover;filter:contrast(1.05) saturate(1.1) brightness(0.85);">
          <div style="position:absolute;inset:0;background:linear-gradient(180deg,transparent 50%,rgba(7,6,12,0.88));"></div>

          {{-- Badge promotora + botón seguir --}}
          @if($empresa)
          @auth
          @if(!Auth::user()->isAdmin() && !Auth::user()->isEmpresa())
          <div style="position:absolute;top:12px;left:12px;display:flex;align-items:center;gap:6px;max-width:calc(100% - 60px);">
            <div style="display:flex;align-items:center;gap:5px;background:rgba(7,6,12,0.75);backdrop-filter:blur(8px);padding:4px 8px 4px 5px;border:1px solid rgba(168,85,247,0.35);">
              @if($empresa->logo_url)
                <img src="{{ $empresa->logo_url }}" alt="{{ $empresa->nombre_empresa }}"
                     style="width:16px;height:16px;object-fit:cover;border-radius:50%;flex-shrink:0;">
              @else
                <span style="width:16px;height:16px;background:#a855f7;display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:900;color:#fff;border-radius:50%;flex-shrink:0;">
                  {{ strtoupper(substr($empresa->nombre_empresa, 0, 1)) }}
                </span>
              @endif
              <span class="mono" style="font-size:9px;color:var(--cream);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:100px;">{{ $empresa->nombre_empresa }}</span>
            </div>
            <button class="btn-seguir-home {{ $esSiguiendo ? 'siguiendo' : '' }}"
                    data-empresa-id="{{ $empresa->id }}"
                    onclick="event.stopPropagation(); toggleSeguirHome(this)"
                    title="{{ $esSiguiendo ? 'Dejar de seguir' : 'Seguir promotora' }}">
              {{ $esSiguiendo ? '✓' : '+' }}
            </button>
          </div>
          @endif
          @endauth
          @endif

          {{-- Badge en curso --}}
          @if($evento->fecha_inicio <= now() && ($evento->fecha_fin === null || $evento->fecha_fin >= now()))
            <div style="position:absolute;{{ $empresa ? 'top:44px' : 'top:12px' }};left:12px;background:var(--magenta);color:var(--cream);padding:4px 10px;border-radius:999px;font-family:'Archivo Narrow',sans-serif;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;display:flex;align-items:center;gap:5px;">
              <span class="pulse-dot" style="width:6px;height:6px;border-radius:50%;background:var(--cream);"></span>
              En curso
            </div>
          @endif

          {{-- Sold out --}}
          @if($evento->sold_out)
            <div style="position:absolute;top:18px;right:18px;background:var(--cream);color:var(--bg);padding:4px 12px;font-family:'Anton',sans-serif;font-size:11px;transform:rotate(4deg);">
              SOLD OUT
            </div>
          @endif

          {{-- Favorito --}}
          <button onclick="event.stopPropagation();vibezToggleFav({{ $evento->id }},this)"
                  data-fav-id="{{ $evento->id }}"
                  style="position:absolute;top:18px;right:{{ $evento->sold_out ? '90px' : '18px' }};width:38px;height:38px;border-radius:50%;background:rgba(7,6,12,0.55);border:1px solid var(--ink-faint);color:{{ $esFav ? 'var(--magenta)' : 'var(--ink)' }};backdrop-filter:blur(10px);cursor:pointer;display:flex;align-items:center;justify-content:center;"
                  class="{{ $esFav ? 'activo' : '' }}">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="{{ $esFav ? 'var(--magenta)' : 'currentColor' }}">
              <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
          </button>

          {{-- Info inferior --}}
          <div style="position:absolute;bottom:0;left:0;right:0;padding:18px;">
            <div class="mono" style="font-size:10px;color:var(--magenta-2);margin-bottom:6px;display:flex;justify-content:space-between;">
              <span>{{ $evento->fecha_fmt }} · {{ $evento->categoria?->nombre ?? '' }}</span>
              <span>{{ $evento->precio_formateado }}</span>
            </div>
            <h3 class="display" style="font-size:24px;margin:0;line-height:0.95;color:var(--ink);">{{ $evento->titulo }}</h3>
            <p style="font-family:'Archivo Narrow',sans-serif;font-size:11px;color:var(--ink-dim);margin:6px 0 0;text-transform:uppercase;letter-spacing:0.08em;">
              {{ $evento->ubicacion_nombre }}
            </p>
          </div>
        </div>

      </article>
    @empty
      <p style="color:var(--ink-dim);font-family:'Archivo Narrow',sans-serif;padding:60px 0;grid-column:1/-1;text-align:center;">
        No hay eventos disponibles.
      </p>
    @endforelse

  </div>

</section>



<script>
window.SEGUIMIENTOS_IDS = @json($seguimientosIds ?? []);
window.PUEDE_SEGUIR     = {{ (Auth::check() && !Auth::user()->isAdmin() && !Auth::user()->isEmpresa()) ? 'true' : 'false' }};
  window.toggleSeguirHome = async function(btn) {
          window.SEGUIMIENTOS_IDS = window.SEGUIMIENTOS_IDS.filter(function(id) { return id !== parseInt(empresaId); });
</script>
<script src="{{ asset('js/grid-eventos.js') }}"></script>
{{-- JS en public/js/grid-eventos.js --}}

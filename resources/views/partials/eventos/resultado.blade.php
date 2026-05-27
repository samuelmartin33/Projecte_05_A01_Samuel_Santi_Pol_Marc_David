{{-- Partial: grid de tarjetas + paginación. Se renderiza en la carga inicial
     y también vía AJAX (EventoController devuelve su HTML en JSON). --}}

<div class="ev-grid">

  @forelse($eventos as $evento)
    @php
      $imgUrl    = $evento->url_portada;
      $empresa   = $evento->organizador?->empresa ?? null;
      $empNombre = $empresa?->nombre_empresa ?? '';
      $empLogo   = $empresa?->logo_url ?? null;
      $empId     = $empresa?->id ?? null;

      $catNombre = $evento->categorias->first()?->nombre
                ?? $evento->categoria?->nombre
                ?? '';

      $precioFmt = $evento->es_gratuito
        ? 'Gratis'
        : ($evento->precio_base > 0 ? number_format($evento->precio_base, 2, ',', '.') . ' €' : 'Gratis');

      $fechaFmt = '';
      if ($evento->fecha_inicio) {
          try {
              $fechaFmt = \Carbon\Carbon::parse($evento->fecha_inicio)
                  ->locale('es')->isoFormat('D MMM');
          } catch (\Exception) {}
      }

      $ahora = now();
      $estaOcurriendo = $evento->fecha_inicio && $evento->fecha_fin
          && \Carbon\Carbon::parse($evento->fecha_inicio)->lte($ahora)
          && \Carbon\Carbon::parse($evento->fecha_fin)->gte($ahora);

      $esFav      = in_array($evento->id, $favoritosIds ?? []);
      $topEncurso = $empNombre ? 'top:44px' : 'top:12px';
    @endphp

    <a class="ev-card" href="{{ route('eventos.detalle', $evento->id) }}">

      <img src="{{ $imgUrl }}" alt="{{ $evento->titulo }}" class="ev-card-img">
      <div class="ev-card-overlay"></div>

      @if($empNombre)
        <div class="ev-card-empresa">
          @if($empLogo)
            <img src="{{ $empLogo }}" class="ev-card-empresa-logo" alt="{{ $empNombre }}">
          @else
            <div class="ev-card-empresa-ini">{{ strtoupper(substr($empNombre, 0, 1)) }}</div>
          @endif
          <span class="ev-card-empresa-nombre">{{ $empNombre }}</span>
        </div>
      @endif

      @if($estaOcurriendo)
        <div class="ev-card-encurso" style="{{ $topEncurso }};">
          <div class="ev-card-encurso-dot"></div>
          En curso
        </div>
      @endif

      <button
        onclick="event.preventDefault(); event.stopPropagation(); vibezToggleFav({{ $evento->id }}, this)"
        data-fav-id="{{ $evento->id }}"
        class="ev-card-fav{{ $esFav ? ' activo' : '' }}"
        title="{{ $esFav ? 'Quitar de favoritos' : 'Añadir a favoritos' }}"
      >
        <svg width="14" height="14" viewBox="0 0 24 24" fill="{{ $esFav ? 'var(--magenta)' : 'currentColor' }}" style="color:{{ $esFav ? 'var(--magenta)' : 'var(--ink)' }};">
          <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
        </svg>
      </button>

      <div class="ev-card-body">
        <div class="ev-card-meta">
          <span>{{ $fechaFmt }}{{ $catNombre ? ' · ' . $catNombre : '' }}</span>
          <span>{{ $precioFmt }}</span>
        </div>
        <h3 class="ev-card-titulo">{{ $evento->titulo }}</h3>
        @if($evento->ubicacion_nombre)
          <p class="ev-card-lugar">{{ $evento->ubicacion_nombre }}</p>
        @endif
      </div>

    </a>
  @empty
    <div class="ev-empty">
      <p style="font-size:48px;margin:0 0 16px;">◎</p>
      <p>No hay eventos para estos filtros.</p>
    </div>
  @endforelse

</div>

{{-- Paginación --}}
@if($eventos->hasPages())
  @php
    $cp  = $eventos->currentPage();
    $lp  = $eventos->lastPage();
    $win = 2;
  @endphp
  <div class="ev-paginacion">

    @if($eventos->onFirstPage())
      <span class="ev-pag-btn deshabilitado"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></span>
    @else
      <a href="{{ $eventos->previousPageUrl() }}" class="ev-pag-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></a>
    @endif

    @if($cp > $win + 2)
      <a href="{{ $eventos->url(1) }}" class="ev-pag-btn">1</a>
      @if($cp > $win + 3)<span class="ev-pag-puntos">…</span>@endif
    @endif

    @for($p = max(1, $cp - $win); $p <= min($lp, $cp + $win); $p++)
      @if($p === $cp)
        <span class="ev-pag-btn activo">{{ $p }}</span>
      @else
        <a href="{{ $eventos->url($p) }}" class="ev-pag-btn">{{ $p }}</a>
      @endif
    @endfor

    @if($cp < $lp - $win - 1)
      @if($cp < $lp - $win - 2)<span class="ev-pag-puntos">…</span>@endif
      <a href="{{ $eventos->url($lp) }}" class="ev-pag-btn">{{ $lp }}</a>
    @endif

    @if($eventos->hasMorePages())
      <a href="{{ $eventos->nextPageUrl() }}" class="ev-pag-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a>
    @else
      <span class="ev-pag-btn deshabilitado"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></span>
    @endif

  </div>
@endif

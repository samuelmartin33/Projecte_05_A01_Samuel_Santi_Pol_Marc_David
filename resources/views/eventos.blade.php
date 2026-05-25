@extends('layouts.app')

@section('titulo', 'VIBEZ — Todos los Eventos')

{{-- @section('content') suprime el nav/footer del layout, igual que home.blade.php --}}
@section('content')

{{-- ── Estilos ── --}}
<link rel="stylesheet" href="{{ asset('css/vibez-home.css') }}">
<style>
  :root {
    --bg: #07060c; --bg-2: #0d0820; --ink: #f5f1ea;
    --ink-dim: rgba(245,241,234,0.55); --ink-faint: rgba(245,241,234,0.18);
    --morado: #7c3aed; --magenta: #a855f7; --magenta-2: #c084fc;
    --cream: #f5f1ea; --line: rgba(245,241,234,0.12);
  }
  body { background: var(--bg); color: var(--ink); }

  /* Grid de tarjetas */
  .vibez-grid-card { border-radius: 0; }
  .vibez-grid-card .img-wrap { border-radius: 0; }
</style>

{{-- ── Datos globales para JS ── --}}
<script>
  window.EVENTOS_DATA   = @json($eventosParaJs ?? []);
  window.FAVORITOS_IDS  = @json($favoritosIds ?? []);
  window.CATEGORIAS     = @json($categorias->pluck('nombre')->prepend('Todo')->values());
  window.USER_AUTH      = @json(Auth::check());
  window.LOGIN_URL      = @json(route('login'));
</script>

{{-- ════════════════════════════════════════════════════
     NAV
════════════════════════════════════════════════════ --}}
@include('partials.home.nav')

{{-- ════════════════════════════════════════════════════
     CABECERA DE LA PÁGINA
════════════════════════════════════════════════════ --}}
<section style="padding:80px 48px 40px;max-width:1480px;margin:0 auto;">
  <div class="mono" style="font-size:11px;color:var(--magenta);margin-bottom:16px;display:flex;align-items:center;gap:10px;">
    <span style="width:28px;height:1px;background:var(--magenta);display:inline-block;"></span>
    Todos los eventos · curados por VIBEZ
  </div>
  <h1 style="font-family:'Anton',sans-serif;font-size:clamp(56px,8vw,120px);margin:0;line-height:0.9;color:var(--ink);text-transform:uppercase;">
    Eventos
  </h1>
  <p style="font-family:'Archivo Narrow',sans-serif;font-size:14px;color:var(--ink-dim);margin:16px 0 0;text-transform:uppercase;letter-spacing:0.1em;">
    Descubre lo que está pasando cerca de ti
  </p>
</section>

{{-- ════════════════════════════════════════════════════
     BARRA DE FILTROS POR CATEGORÍA (chips sticky)
════════════════════════════════════════════════════ --}}
<section style="position:sticky;top:0;z-index:30;background:rgba(7,6,12,0.92);backdrop-filter:blur(18px);border-bottom:1px solid var(--line);padding:16px 48px;">
  <div style="max-width:1480px;margin:0 auto;display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
    <span class="mono" style="font-size:11px;color:var(--ink-dim);white-space:nowrap;">
      <span id="vibez-count-label">{{ $eventos->count() }}</span> eventos
    </span>
    <div class="no-scrollbar" style="overflow-x:auto;white-space:nowrap;padding:4px 0;flex:1;">
      <div style="display:inline-flex;gap:10px;">
        <button class="chip active vibez-cat-chip" data-cat="Todo"
                onclick="vibezFilterCategoria('Todo')">Todo</button>
        @foreach($categorias as $cat)
          <button class="chip vibez-cat-chip" data-cat="{{ $cat->nombre }}"
                  onclick="vibezFilterCategoria('{{ $cat->nombre }}')">{{ $cat->nombre }}</button>
        @endforeach
      </div>
    </div>
  </div>
</section>

{{-- ════════════════════════════════════════════════════
     GRID COMPLETO DE EVENTOS CON AJAX
════════════════════════════════════════════════════ --}}
@include('partials.home.grid-eventos', [
  'eventos'      => $eventos,
  'categorias'   => $categorias,
  'favoritosIds' => $favoritosIds ?? [],
  'ubicaciones'  => $ubicaciones ?? [],
])

{{-- ════════════════════════════════════════════════════
     MODAL DETALLE DE EVENTO
════════════════════════════════════════════════════ --}}
@include('partials.home.detail-modal')

{{-- ════════════════════════════════════════════════════
     FOOTER
════════════════════════════════════════════════════ --}}
@include('partials.home.footer')

{{-- Toast de confirmación (favoritos, etc.) --}}
<div id="vibez-toast" class="toast" style="display:none;"></div>

{{-- ════════════════════════════════════════════════════
     SCRIPTS
════════════════════════════════════════════════════ --}}
<script src="{{ asset('js/vibez-home.js') }}"></script>
<script>
  /*
   * Función unificada de filtrado para la página Eventos.
   *
   * Problema original:
   *  - vibezGridFiltrar() (grid-eventos.blade.php) lee #grid-filtro-cat que
   *    no existe aquí → siempre enviaba categoría vacía.
   *  - _vibezFiltrarGrid() (vibez-home.js) no incluía la ubicación.
   *  - Los dos sistemas no compartían estado.
   *
   * Solución: una sola función que lee vibezActiveCategoria + select ciudad,
   * hace un único fetch y usa el renderizador completo (con badges de empresa,
   * botón seguir, favoritos, etc.).
   */
  function _eventosRenderGrid(grid, eventos) {
    if (!eventos.length) {
      grid.innerHTML = '<p style="color:var(--ink-dim);font-family:\'Archivo Narrow\',sans-serif;padding:60px 0;text-align:center;grid-column:1/-1;">No hay eventos para estos filtros.</p>';
      return;
    }
    grid.innerHTML = eventos.map(function(e) {
      var esFav      = (window.FAVORITOS_IDS || []).includes(e.id);
      var esSig      = e.empresa_id && (window.SEGUIMIENTOS_IDS || []).includes(e.empresa_id);
      var puedeSeguir = window.PUEDE_SEGUIR && e.empresa_id;
      var badgePromo = '';
      if (puedeSeguir) {
        var inicial = e.empresa_nombre ? e.empresa_nombre.charAt(0).toUpperCase() : '?';
        var logoHtml = e.empresa_logo
          ? '<img src="' + e.empresa_logo + '" style="width:16px;height:16px;object-fit:cover;border-radius:50%;flex-shrink:0;">'
          : '<span style="width:16px;height:16px;background:#a855f7;display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:900;color:#fff;border-radius:50%;flex-shrink:0;">' + inicial + '</span>';
        badgePromo = '<div style="position:absolute;top:12px;left:12px;display:flex;align-items:center;gap:6px;max-width:calc(100% - 60px);">'
          + '<div style="display:flex;align-items:center;gap:5px;background:rgba(7,6,12,0.75);backdrop-filter:blur(8px);padding:4px 8px 4px 5px;border:1px solid rgba(168,85,247,0.35);">'
          + logoHtml
          + '<span class="mono" style="font-size:9px;color:var(--cream);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:100px;">' + (e.empresa_nombre || '') + '</span>'
          + '</div>'
          + '<button class="btn-seguir-home ' + (esSig ? 'siguiendo' : '') + '" data-empresa-id="' + e.empresa_id + '" onclick="event.stopPropagation();toggleSeguirHome(this)" title="' + (esSig ? 'Dejar de seguir' : 'Seguir promotora') + '">'
          + (esSig ? '✓' : '+') + '</button></div>';
      }
      var enCursoTop = puedeSeguir ? 'top:44px' : 'top:12px';
      return '<article class="vibe-card vibez-grid-card" data-id="' + e.id + '" onclick="vibezOpenModal(' + e.id + ')" style="cursor:pointer;">'
        + '<div class="img-wrap" style="position:relative;aspect-ratio:3/4;overflow:hidden;">'
        + '<img src="' + (e.img || e.url_portada || '') + '" alt="' + e.titulo + '" style="width:100%;height:100%;object-fit:cover;filter:contrast(1.05) saturate(1.1) brightness(0.85);">'
        + '<div style="position:absolute;inset:0;background:linear-gradient(180deg,transparent 50%,rgba(7,6,12,0.88));"></div>'
        + badgePromo
        + (e.estaOcurriendo ? '<div style="position:absolute;' + enCursoTop + ';left:12px;background:var(--magenta);color:var(--cream);padding:4px 10px;border-radius:999px;font-family:\'Archivo Narrow\',sans-serif;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;display:flex;align-items:center;gap:5px;"><span style="width:6px;height:6px;border-radius:50%;background:var(--cream);"></span>En curso</div>' : '')
        + '<button onclick="event.stopPropagation();vibezToggleFav(' + e.id + ',this)" data-fav-id="' + e.id + '" class="' + (esFav ? 'activo' : '') + '" style="position:absolute;top:18px;right:18px;width:38px;height:38px;border-radius:50%;background:rgba(7,6,12,0.55);border:1px solid var(--ink-faint);color:' + (esFav ? 'var(--magenta)' : 'var(--ink)') + ';backdrop-filter:blur(10px);cursor:pointer;display:flex;align-items:center;justify-content:center;">'
        + '<svg width="14" height="14" viewBox="0 0 24 24" fill="' + (esFav ? 'var(--magenta)' : 'currentColor') + '"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>'
        + '</button>'
        + '<div style="position:absolute;bottom:0;left:0;right:0;padding:18px;">'
        + '<div class="mono" style="font-size:10px;color:var(--magenta-2);margin-bottom:6px;display:flex;justify-content:space-between;">'
        + '<span>' + (e.fechaFmt || e.fecha_fmt || '') + ' · ' + (e.categoria || '') + '</span>'
        + '<span>' + (e.precio_formateado || e.precio || '') + '</span></div>'
        + '<h3 class="display" style="font-size:24px;margin:0;line-height:0.95;">' + e.titulo + '</h3>'
        + '<p style="font-family:\'Archivo Narrow\',sans-serif;font-size:11px;color:var(--ink-dim);margin:6px 0 0;text-transform:uppercase;letter-spacing:0.08em;">' + (e.ubicacion_nombre || e.lugar || '') + '</p>'
        + '</div></div></article>';
    }).join('');
  }

  function _eventosFiltrar() {
    var cat      = vibezActiveCategoria || 'Todo';
    var ub       = document.getElementById('grid-filtro-ubicacion');
    var ubicacion = ub ? ub.value : '';
    var grid     = document.getElementById('vibez-grid-todos');
    var spinner  = document.getElementById('vibez-grid-spinner');
    var gridCount = document.getElementById('vibez-grid-count');
    var mainCount = document.getElementById('vibez-count-label');

    if (!grid) return;
    if (spinner) spinner.style.display = 'flex';

    var url = '/api/filtrar?categoria=' + encodeURIComponent(cat === 'Todo' ? '' : cat)
            + '&ubicacion=' + encodeURIComponent(ubicacion);

    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then(function(r) { return r.json(); })
      .then(function(data) {
        if (spinner) spinner.style.display = 'none';
        var eventos = data.eventos || [];
        if (gridCount) gridCount.textContent = eventos.length;
        if (mainCount) mainCount.textContent = eventos.length;
        _eventosRenderGrid(grid, eventos);
      })
      .catch(function() { if (spinner) spinner.style.display = 'none'; });
  }

  /* Los chips llaman vibezFilterCategoria → _vibezFiltrarGrid.
     Redirigimos al sistema unificado. */
  _vibezFiltrarGrid = function(cat) {
    vibezActiveCategoria = cat;
    _eventosFiltrar();
  };

  /* El select de ciudad llama vibezGridFiltrar.
     Redirigimos al sistema unificado (conserva la categoría activa). */
  vibezGridFiltrar = function() {
    _eventosFiltrar();
  };
</script>

@endsection

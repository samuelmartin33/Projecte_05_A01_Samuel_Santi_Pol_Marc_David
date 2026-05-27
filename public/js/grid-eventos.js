/**
 * grid-eventos.js — logica del grid de eventos
 */
/* Seguir promotora — definida aquí para estar siempre disponible */
if (typeof toggleSeguirHome === 'undefined') {
    var empresaId = btn.dataset.empresaId;
    btn.classList.add('cargando');
    try {
      var res = await fetch('/api/seguimientos/' + empresaId + '/toggle', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json',
        },
      });
      var data = await res.json();
      if (data.success) {
        if (data.siguiendo) {
          btn.classList.add('siguiendo');
          btn.textContent = '✓';
          btn.title = 'Dejar de seguir';
          if (!window.SEGUIMIENTOS_IDS.includes(parseInt(empresaId))) {
            window.SEGUIMIENTOS_IDS.push(parseInt(empresaId));
          }
        } else {
          btn.classList.remove('siguiendo');
          btn.textContent = '+';
          btn.title = 'Seguir promotora';
        }
        /* Sincronizar todos los botones con el mismo empresa_id en la página */
        document.querySelectorAll('.btn-seguir-home[data-empresa-id="' + empresaId + '"]').forEach(function(b) {
          if (b !== btn) {
            b.classList.toggle('siguiendo', data.siguiendo);
            b.textContent = data.siguiendo ? '✓' : '+';
          }
        });
      }
    } catch (e) {
      console.error('Error al seguir promotora', e);
    } finally {
      btn.classList.remove('cargando');
    }
  };
}

/* Filtra por ciudad: usa _eventosFiltrar si está disponible (página Eventos),
   si no cae al vibezGridFiltrar clásico. */
function vibezFiltrarCiudad() {
  if (typeof _eventosFiltrar !== 'undefined') { _eventosFiltrar(); return; }
  vibezGridFiltrar();
}

/* Filtrado AJAX del grid */
function vibezGridFiltrar() {
  var cat      = document.getElementById('grid-filtro-cat')?.value || '';
  var ubicacion = document.getElementById('grid-filtro-ubicacion')?.value || '';
  var grid     = document.getElementById('vibez-grid-todos');
  var spinner  = document.getElementById('vibez-grid-spinner');
  var countEl  = document.getElementById('vibez-grid-count');
  var mainCount = document.getElementById('vibez-count-label');

  if (spinner) spinner.style.display = 'flex';

  var url = '/api/filtrar?categoria=' + encodeURIComponent(cat) + '&ubicacion=' + encodeURIComponent(ubicacion);

  fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if (spinner) spinner.style.display = 'none';
      var eventos = data.eventos || [];
      if (countEl) countEl.textContent = eventos.length;
      if (mainCount) mainCount.textContent = eventos.length;
      if (!grid) return;
      if (!eventos.length) {
        grid.innerHTML = '<p style="color:var(--ink-dim);font-family:\'Archivo Narrow\',sans-serif;padding:60px 0;text-align:center;grid-column:1/-1;">No hay eventos para estos filtros.</p>';
        return;
      }
      grid.innerHTML = eventos.map(function(e) {
        var esFav       = (window.FAVORITOS_IDS || []).includes(e.id);
        var esSig       = e.empresa_id && (window.SEGUIMIENTOS_IDS || []).includes(e.empresa_id);
        var puedeSegir  = window.PUEDE_SEGUIR && e.empresa_id;
        var badgePromo  = '';
        if (puedeSegir) {
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
            + (esSig ? '✓' : '+')
            + '</button>'
            + '</div>';
        }
        var enCursoTop = puedeSegir ? 'top:44px' : 'top:12px';
        return '<article class="vibe-card vibez-grid-card" data-id="' + e.id + '" onclick="vibezOpenModal(' + e.id + ')" style="cursor:pointer;">'
          + '<div class="img-wrap" style="position:relative;aspect-ratio:3/4;overflow:hidden;">'
          + '<img src="' + (e.img || e.url_portada || '') + '" alt="' + e.titulo + '" style="width:100%;height:100%;object-fit:cover;filter:contrast(1.05) saturate(1.1) brightness(0.85);">'
          + '<div style="position:absolute;inset:0;background:linear-gradient(180deg,transparent 50%,rgba(7,6,12,0.88));"></div>'
          + badgePromo
          + (e.estaOcurriendo ? '<div style="position:absolute;' + enCursoTop + ';left:12px;background:var(--magenta);color:var(--cream);padding:4px 10px;border-radius:999px;font-family:\'Archivo Narrow\',sans-serif;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;display:flex;align-items:center;gap:5px;"><span style="width:6px;height:6px;border-radius:50%;background:var(--cream);"></span>En curso</div>' : '')
          + '<button onclick="event.stopPropagation();vibezToggleFav(' + e.id + ',this)" data-fav-id="' + e.id + '" class="' + (esFav ? 'activo' : '') + '" style="position:absolute;top:18px;right:18px;width:38px;height:38px;border-radius:50%;background:rgba(7,6,12,0.55);border:1px solid var(--ink-faint);color:var(--ink);backdrop-filter:blur(10px);cursor:pointer;display:flex;align-items:center;justify-content:center;">'
          + '<svg width="14" height="14" viewBox="0 0 24 24" fill="' + (esFav ? 'var(--magenta)' : 'currentColor') + '"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>'
          + '</button>'
          + '<div style="position:absolute;bottom:0;left:0;right:0;padding:18px;">'
          + '<div class="mono" style="font-size:10px;color:var(--magenta-2);margin-bottom:6px;display:flex;justify-content:space-between;"><span>' + (e.fecha_fmt || e.fechaFmt || '') + ' · ' + (e.categoria || '') + '</span><span>' + (e.precio_formateado || e.precio || '') + '</span></div>'
          + '<h3 class="display" style="font-size:24px;margin:0;line-height:0.95;">' + e.titulo + '</h3>'
          + '<p style="font-family:\'Archivo Narrow\',sans-serif;font-size:11px;color:var(--ink-dim);margin:6px 0 0;text-transform:uppercase;letter-spacing:0.08em;">' + (e.ubicacion_nombre || e.lugar || '') + '</p>'
          + '</div></div></article>';
      }).join('');
    })
    .catch(function() {
      if (spinner) spinner.style.display = 'none';
    });
}

function vibezGridLimpiar() {
  var ub = document.getElementById('grid-filtro-ubicacion');
  if (ub) {
    ub.value = '';
    var label = document.getElementById('ev-filtro-ub-label');
    if (label) { label.textContent = 'Todas las ciudades'; label.classList.add('ev-csel-placeholder'); }
    var cont  = document.getElementById('ev-filtro-ub');
    if (cont) {
      cont.querySelectorAll('.ev-csel-opt').forEach(function(o) { o.classList.remove('selected'); });
      var first = cont.querySelector('.ev-csel-opt');
      if (first) first.classList.add('selected');
    }
  }
  /* Resetear chip activo a "Todo" */
  document.querySelectorAll('.vibez-cat-chip').forEach(function(c) {
    c.classList.toggle('active', c.dataset.cat === 'Todo');
  });
  vibezActiveCategoria = 'Todo';
  vibezGridFiltrar();
}

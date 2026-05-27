/**
 * perfil-favoritos.js — logica de favoritos
 */
/* Filtra los favoritos por categoría usando el endpoint AJAX con ?favoritos=1 */
  function filtrarFavoritos(cat) {
    /* Actualizar chips activos */
    document.querySelectorAll('.vibez-cat-chip').forEach(function(c) {
      c.classList.toggle('active', c.dataset.cat === cat);
    });

    var grid    = document.getElementById('vibez-grid-todos');
    var spinner = document.getElementById('vibez-grid-spinner');
    var countEl = document.getElementById('vibez-grid-count');
    if (!grid) return;

    if (spinner) spinner.style.display = 'flex';

    var ciudad = document.getElementById('grid-filtro-ubicacion') ? document.getElementById('grid-filtro-ubicacion').value : '';
    var url = '/api/filtrar?favoritos=1&categoria=' + encodeURIComponent(cat === 'Todo' ? '' : cat) + '&ubicacion=' + encodeURIComponent(ciudad);

    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then(function(r) { return r.json(); })
      .then(function(data) {
        if (spinner) spinner.style.display = 'none';
        var eventos = data.eventos || [];
        if (countEl) countEl.textContent = eventos.length;

        if (!eventos.length) {
          grid.innerHTML = '<p style="color:rgba(245,241,234,0.45);font-family:\'Archivo Narrow\',sans-serif;padding:60px 0;grid-column:1/-1;text-align:center;">No tienes favoritos en esta categoría.</p>';
          return;
        }

        grid.innerHTML = eventos.map(function(e) {
          var esFav = true; /* siempre son favoritos en esta página */
          return '<article class="vibe-card vibez-grid-card" data-id="' + e.id + '" onclick="vibezOpenModal(' + e.id + ')" style="cursor:pointer;">'
            + '<div class="img-wrap" style="position:relative;aspect-ratio:3/4;overflow:hidden;">'
            + '<img src="' + (e.img || e.url_portada || '') + '" alt="' + e.titulo + '" style="width:100%;height:100%;object-fit:cover;filter:contrast(1.05) saturate(1.1) brightness(0.85);">'
            + '<div style="position:absolute;inset:0;background:linear-gradient(180deg,transparent 50%,rgba(7,6,12,0.88));"></div>'
            + '<button onclick="event.stopPropagation();vibezToggleFav(' + e.id + ',this)" data-fav-id="' + e.id + '" class="activo" style="position:absolute;top:18px;right:18px;width:38px;height:38px;border-radius:50%;background:rgba(7,6,12,0.55);border:1px solid rgba(245,241,234,0.18);color:var(--magenta, #a855f7);backdrop-filter:blur(10px);cursor:pointer;display:flex;align-items:center;justify-content:center;">'
            + '<svg width="14" height="14" viewBox="0 0 24 24" fill="var(--magenta, #a855f7)"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>'
            + '</button>'
            + '<div style="position:absolute;bottom:0;left:0;right:0;padding:18px;">'
            + '<div class="mono" style="font-size:10px;color:#c084fc;margin-bottom:6px;display:flex;justify-content:space-between;">'
            + '<span>' + (e.fechaFmt || '') + ' · ' + (e.categoria || '') + '</span>'
            + '<span>' + (e.precio || e.precio_formateado || '') + '</span></div>'
            + '<h3 class="display" style="font-size:24px;margin:0;line-height:0.95;">' + e.titulo + '</h3>'
            + '<p style="font-family:\'Archivo Narrow\',sans-serif;font-size:11px;color:rgba(245,241,234,0.55);margin:6px 0 0;text-transform:uppercase;letter-spacing:0.08em;">' + (e.lugar || e.ubicacion_nombre || '') + '</p>'
            + '</div></div></article>';
        }).join('');
      })
      .catch(function() { if (spinner) spinner.style.display = 'none'; });
  }

  /* Sobreescribir funciones del grid para que respeten el filtro de favoritos */
    var ub = document.getElementById('grid-filtro-ubicacion');
    if (ub) {
      ub.value = '';
      var label = document.getElementById('ev-filtro-ub-label');
      if (label) { label.textContent = 'Todas las ciudades'; label.classList.add('ev-csel-placeholder'); }
      var cont = document.getElementById('ev-filtro-ub');
      if (cont) {
        cont.querySelectorAll('.ev-csel-opt').forEach(function(o) { o.classList.remove('selected'); });
        var first = cont.querySelector('.ev-csel-opt');
        if (first) first.classList.add('selected');
      }
    }
    filtrarFavoritos('Todo');
  };

    var chip = document.querySelector('.vibez-cat-chip.active');
    filtrarFavoritos(chip ? chip.dataset.cat : 'Todo');
  };

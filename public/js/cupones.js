// VIBEZ Cupones — Funcionalidad interactiva

// Copia código del cupón al portapapeles (con fallback para navegadores antiguos)
function copiarCodigo(codigo, cuponId) {
    var fallback = function() {
        var el = document.createElement('textarea');
        el.value = codigo;
        el.style.cssText = 'position:fixed;opacity:0;pointer-events:none';
        document.body.appendChild(el);
        el.select();
        try { document.execCommand('copy'); } catch(e) {}
        document.body.removeChild(el);
        mostrarToastCupon('✓ Código copiado: ' + codigo);
        marcarCopiado(cuponId);
    };
    if (!navigator.clipboard) { fallback(); return; }
    navigator.clipboard.writeText(codigo)
        .then(function() { mostrarToastCupon('✓ Código copiado: ' + codigo); marcarCopiado(cuponId); })
        .catch(fallback);
}

// Actualiza visualmente el botón de copiar durante 2.6s
function marcarCopiado(cuponId) {
    var btn = document.getElementById('btn-copy-' + cuponId);
    if (!btn) return;
    btn.innerHTML = '✓ Copiado';
    btn.classList.add('copied');
    setTimeout(function() {
        btn.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg> Copiar';
        btn.classList.remove('copied');
    }, 2600);
}

// Muestra notificación temporal en la esquina inferior
function mostrarToastCupon(msg) {
    var t = document.getElementById('cup-toast');
    if (!t) return;
    t.textContent = msg;
    t.classList.add('show');
    clearTimeout(t._tid);
    t._tid = setTimeout(function() { t.classList.remove('show'); }, 2800);
}

// Filtra cupones por tipo: 'all', 'active', 'free'
function cupSetFilter(btn, filtro) {
    document.querySelectorAll('.cup-filter-chip').forEach(function(c) { c.classList.remove('active'); });
    btn.classList.add('active');
    var expired = document.getElementById('cup-sec-expired');
    document.querySelectorAll('.cup-card').forEach(function(card) {
        var tipo = card.dataset.type || 'active';
        if (filtro === 'all')         { card.style.display = ''; }
        else if (filtro === 'active') { card.style.display = (tipo === 'active' || tipo === 'free') ? '' : 'none'; }
        else if (filtro === 'free')   { card.style.display = tipo === 'free' ? '' : 'none'; }
    });
    if (expired) expired.style.display = (filtro === 'all') ? '' : 'none';
}

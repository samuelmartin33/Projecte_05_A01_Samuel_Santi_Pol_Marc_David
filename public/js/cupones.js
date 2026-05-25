// VIBEZ Cupones — Funcionalidad interactiva

// Copia código del cupón al portapapeles (con fallback para navegadores antiguos)
function copiarCodigo(codigo, cuponId) {
    var fallback = function() {
        var el = document.createElement('textarea');
        el.value = codigo;
        el.style.cssText = 'position:fixed;opacity:0;pointer-events:none';
        document.body.appendChild(el);
        el.select();
        try { document.execCommand('copy'); } catch(errorPortapapeles) {}
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
    var toastCupon = document.getElementById('cup-toast');
    if (!toastCupon) return;
    toastCupon.textContent = msg;
    toastCupon.classList.add('show');
    clearTimeout(toastCupon._tid);
    toastCupon._tid = setTimeout(function() { toastCupon.classList.remove('show'); }, 2800);
}

// Filtra cupones por tipo: 'all', 'active', 'free'
function cupSetFilter(btn, filtro) {
    Array.from(document.getElementsByClassName('cup-filter-chip')).forEach(function(chipCupon) { chipCupon.classList.remove('active'); });
    btn.classList.add('active');
    var bloqueCaducados = document.getElementById('cup-sec-expired');
    Array.from(document.getElementsByClassName('cup-card')).forEach(function(tarjetaCupon) {
        var tipo = tarjetaCupon.dataset.type || 'active';
        if (filtro === 'all')         { tarjetaCupon.style.display = ''; }
        else if (filtro === 'active') { tarjetaCupon.style.display = (tipo === 'active' || tipo === 'free') ? '' : 'none'; }
        else if (filtro === 'free')   { tarjetaCupon.style.display = tipo === 'free' ? '' : 'none'; }
    });
    if (bloqueCaducados) bloqueCaducados.style.display = (filtro === 'all') ? '' : 'none';
}

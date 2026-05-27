/**
 * nav-hamburger.js — menu hamburguesa movil en la navegacion
 */
/* ─── Menú móvil: hamburguesa (accesible para todos los usuarios) ── */
function toggleNavMobile() {
    var menu = document.getElementById('vibez-mobile-menu');
    var btn  = document.getElementById('vibez-nav-hamburger');
    var abierto = menu.style.display === 'flex';
    if (abierto) {
        menu.style.display = 'none';
        btn.classList.remove('abierto');
        btn.setAttribute('aria-expanded', 'false');
    } else {
        menu.style.display = 'flex';
        menu.style.flexDirection = 'column';
        btn.classList.add('abierto');
        btn.setAttribute('aria-expanded', 'true');
    }
}

/* Cierra el menú móvil al hacer clic fuera */
document.onclick = function(e) {
    var menu = document.getElementById('vibez-mobile-menu');
    var btn  = document.getElementById('vibez-nav-hamburger');
    if (!menu || menu.style.display !== 'flex') return;
    if (!btn.contains(e.target) && !menu.contains(e.target)) {
        menu.style.display = 'none';
        btn.classList.remove('abierto');
        btn.setAttribute('aria-expanded', 'false');
    }
});

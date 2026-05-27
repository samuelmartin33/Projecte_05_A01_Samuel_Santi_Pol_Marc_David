/**
 * moderador-dashboard.js — funciones del dashboard moderador
 */
/* Abre/cierra el dropdown del sidebar */
    function toggleAdmDropdown() {
        var d = document.getElementById('admDropdown');
        d.style.display = d.style.display === 'none' ? 'block' : 'none';
    }

    /* Abre/cierra la navegación en móvil (hamburguesa) */
    function toggleAdmNav() {
        var nav = document.querySelector('.adm-nav-group');
        var btn = document.getElementById('adm-hamburger');
        var side = document.querySelector('.adm-side');
        if (!nav || !btn) return;

        var abierto = nav.classList.toggle('adm-nav-abierto');
        btn.classList.toggle('abierto', abierto);
        btn.setAttribute('aria-expanded', abierto);

        if (abierto && side) {
            nav.style.top = side.offsetHeight + 'px';
        }
    }

    /* Cierra al hacer clic fuera */
    document.addEventListener('click', function (e) {
        var foot = document.getElementById('admSideFoot');
        var drop = document.getElementById('admDropdown');
        if (drop && foot && !foot.contains(e.target)) {
            drop.style.display = 'none';
        }

        /* Cierra el menú móvil si se hace clic fuera */
        var nav = document.querySelector('.adm-nav-group');
        var hamburger = document.getElementById('adm-hamburger');
        if (nav && nav.classList.contains('adm-nav-abierto')) {
            if (!nav.contains(e.target) && e.target !== hamburger && !hamburger.contains(e.target)) {
                nav.classList.remove('adm-nav-abierto');
                if (hamburger) { hamburger.classList.remove('abierto'); hamburger.setAttribute('aria-expanded', 'false'); }
            }
        }
    });

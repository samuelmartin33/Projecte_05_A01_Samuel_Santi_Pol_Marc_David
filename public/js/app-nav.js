function toggleMenuMovil() {
    var panel   = document.getElementById('navMovilPanel');
    var overlay = document.getElementById('navMovilOverlay');
    var btn     = document.getElementById('navHamburger');
    if (!panel) return;
    var abierto = panel.classList.contains('activo');
    if (abierto) {
        cerrarMenuMovil();
    } else {
        panel.classList.add('activo');
        overlay.classList.add('activo');
        btn.setAttribute('aria-expanded', 'true');
        btn.querySelector('.icono-ham').style.display = 'none';
        btn.querySelector('.icono-x').style.display   = 'block';
        document.body.style.overflow = 'hidden';
    }
}

function cerrarMenuMovil() {
    var panel   = document.getElementById('navMovilPanel');
    var overlay = document.getElementById('navMovilOverlay');
    var btn     = document.getElementById('navHamburger');
    if (!panel) return;
    panel.classList.remove('activo');
    overlay.classList.remove('activo');
    if (btn) {
        btn.setAttribute('aria-expanded', 'false');
        btn.querySelector('.icono-ham').style.display = 'block';
        btn.querySelector('.icono-x').style.display   = 'none';
    }
    document.body.style.overflow = '';
}

document.onkeydown = function (e) {
    if (e.key === 'Escape') cerrarMenuMovil();
};

(function () {
    function refrescarBadgeSocial() {
        fetch('/api/social/contador', { headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (resp) {
                if (!resp.exito) return;
                var badge = document.getElementById('nav-badge-social');
                if (!badge) return;
                var total = resp.datos.total;
                if (total > 0) {
                    badge.textContent   = total > 99 ? '99+' : total;
                    badge.style.display = 'inline-flex';
                } else {
                    badge.style.display = 'none';
                }
            })
            .catch(function () {});
    }

    var badge = document.getElementById('nav-badge-social');
    if (badge) {
        refrescarBadgeSocial();
        setInterval(refrescarBadgeSocial, 30000);
    }
})();

function toggleNavDropdown() {
    var dropdown = document.getElementById('navDropdown');
    var btn      = document.getElementById('navAvatarBtn');
    if (!dropdown) return;
    var abierto  = dropdown.style.display === 'block';

    dropdown.style.display = abierto ? 'none' : 'block';
    btn.setAttribute('aria-expanded', String(!abierto));

    if (!abierto) {
        dropdown.style.animation = 'none';
        dropdown.offsetHeight;
        dropdown.style.animation = 'dropdownEntrar 0.18s ease';
    }
}

var anteriorClickDocumento = document.onclick;
document.onclick = function(e) {
    if (typeof anteriorClickDocumento === 'function') {
        anteriorClickDocumento(e);
    }

    var wrapper = document.getElementById('navAvatarWrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        var dropdown = document.getElementById('navDropdown');
        var btn      = document.getElementById('navAvatarBtn');
        if (dropdown) dropdown.style.display = 'none';
        if (btn)      btn.setAttribute('aria-expanded', 'false');
    }
};

function cerrarSesion() {
    var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/api/logout', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
    })
    .then(function() {
        document.body.style.transition = 'opacity 0.3s';
        document.body.style.opacity    = '0';
        setTimeout(function() { window.location.href = '/'; }, 320);
    })
    .catch(function() { window.location.href = '/'; });
}

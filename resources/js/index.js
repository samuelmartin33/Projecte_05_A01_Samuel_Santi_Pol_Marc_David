/**
 * VIBEZ — index.js
 * Maneja el logout por AJAX desde el dashboard
 * + Panel admin: tabs para gestión de usuarios
 */

/* ============================================================
   LOGOUT
   ============================================================ */
document.getElementById('logoutBtn').addEventListener('click', async function () {
    this.classList.add('loading');
    this.textContent = 'Cerrando sesión...';

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')
                                  .getAttribute('content');

        const response = await fetch('/api/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept':       'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        });

        const data = await response.json();

        if (data.success) {
            // Fade-out y redirect al login
            document.body.style.transition = 'opacity 0.35s ease';
            document.body.style.opacity    = '0';
            setTimeout(() => { window.location.href = '/login'; }, 360);
        } else {
            this.classList.remove('loading');
            this.textContent = 'Cerrar sesión';
            console.error('[VIBEZ] Error en logout:', data.message);
        }

    } catch (err) {
        this.classList.remove('loading');
        this.textContent = 'Cerrar sesión';
        console.error('[VIBEZ] Error de conexión en logout:', err);
    }
});


/* ============================================================
   ADMIN PANEL — Tabs (solo si el panel existe en el DOM)
   ============================================================ */
(function () {
    const adminPanel = document.getElementById('adminPanel');
    if (!adminPanel) return;

    const tabBtns  = adminPanel.querySelectorAll('.admin-tab');
    const tabPanels = adminPanel.querySelectorAll('.admin-tab-panel');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const target = btn.getAttribute('data-tab');

            tabBtns.forEach(b => b.classList.remove('active'));
            tabPanels.forEach(p => p.classList.remove('active'));

            btn.classList.add('active');
            document.getElementById('panel-' + target).classList.add('active');
        });
    });

    // Auto-scroll al panel admin si hay un flash message
    const flash = adminPanel.querySelector('.admin-flash');
    if (flash) {
        setTimeout(() => {
            adminPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 300);
    }
})();

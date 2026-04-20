/**
 * VIBEZ — index.js
 * Maneja el logout por AJAX desde el dashboard
 */

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

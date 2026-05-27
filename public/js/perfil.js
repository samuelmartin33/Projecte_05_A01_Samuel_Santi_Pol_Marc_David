/**
 * perfil.js — logica de la pagina de perfil de usuario
 */
async function toggleSeguirPerfil(btn) {
        const empresaId = btn.dataset.empresaId;
        btn.classList.add('cargando');
        try {
            const res = await fetch(`/api/seguimientos/${empresaId}/toggle`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            });
            const data = await res.json();
            if (data.success && !data.siguiendo) {
                // Animar y quitar la fila
                const fila = btn.closest('div[style*="display:flex"]');
                if (fila) {
                    fila.style.transition = 'opacity 0.3s';
                    fila.style.opacity = '0';
                    setTimeout(() => fila.remove(), 300);
                }
            }
        } catch (e) {
            console.error('Error al dejar de seguir', e);
        } finally {
            btn.classList.remove('cargando');
        }
    }

/**
 * promotoras-seguidas.js — logica seguir promotoras en home
 */
async function toggleSeguirHome(btn) {
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
        if (data.success) {
            if (data.siguiendo) {
                btn.classList.add('siguiendo');
                btn.textContent = '✓';
                btn.title = 'Dejar de seguir';
            } else {
                btn.classList.remove('siguiendo');
                btn.textContent = '+';
                btn.title = 'Seguir';
            }
        }
    } catch (e) {
        console.error('Error al seguir promotora', e);
    } finally {
        btn.classList.remove('cargando');
    }
}

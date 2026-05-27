/**
 * social.js — logica pagina social
 */
(async function cargarPromotorasSocial() {
    const lista = document.getElementById('soc-promotoras-lista');
    if (!lista) return;
    try {
        const res  = await fetch('/api/seguimientos/promotoras', {
            headers: { 'Accept': 'application/json' }
        });
        const data = await res.json();
        const promotoras = data.promotoras ?? [];

        if (promotoras.length === 0) {
            lista.innerHTML = '<p class="soc-vacio" style="font-size:0.8rem;padding:4px 0;">Aún no sigues ninguna promotora.</p>';
            return;
        }

        lista.innerHTML = promotoras.map(p => {
            const inicial = p.nombre ? p.nombre.charAt(0).toUpperCase() : '?';
            const logoHtml = p.logo_url
                ? `<img src="${p.logo_url}" alt="${p.nombre}" class="soc-promotora-logo">`
                : `<div class="soc-promotora-logo-ini">${inicial}</div>`;

            const proximoEvento = (p.proximos_eventos && p.proximos_eventos.length > 0)
                ? `<span class="soc-promotora-evento">${p.proximos_eventos[0].titulo}</span>`
                : '';

            return `<div class="soc-promotora-item">
                ${logoHtml}
                <div class="soc-promotora-info">
                    <div class="soc-promotora-nombre">${p.nombre}</div>
                    ${proximoEvento}
                </div>
                <button class="soc-promotora-btn"
                        data-empresa-id="${p.id}"
                        onclick="toggleSeguirSocial(this)">✓</button>
            </div>`;
        }).join('');
    } catch (e) {
        lista.innerHTML = '<p class="soc-vacio" style="font-size:0.8rem;">Error al cargar promotoras.</p>';
    }
})();

async function toggleSeguirSocial(btn) {
    const empresaId = btn.dataset.empresaId;
    btn.disabled = true;
    try {
        const res  = await fetch(`/api/seguimientos/${empresaId}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        });
        const data = await res.json();
        if (data.success && !data.siguiendo) {
            const fila = btn.closest('.soc-promotora-item');
            if (fila) {
                fila.style.transition = 'opacity 0.25s';
                fila.style.opacity   = '0';
                setTimeout(() => fila.remove(), 250);
            }
        }
    } catch (e) {
        console.error('Error', e);
    } finally {
        btn.disabled = false;
    }
}

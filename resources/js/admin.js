/* VIBEZ — admin.js — Tab switching for standalone admin panel */

document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
        btn.classList.add('active');
    });
});

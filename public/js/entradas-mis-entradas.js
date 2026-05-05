document.querySelectorAll('[data-codigo]').forEach(function(el) {
    new QRCode(el, {
        text:       el.dataset.codigo,
        width:      200,
        height:     200,
        colorDark:  '#000000',
        colorLight: '#ffffff',
    });
});

function toggleQr(qrId, btnId) {
    var panel   = document.getElementById(qrId);
    var btn     = document.getElementById(btnId);
    var visible = panel.style.display !== 'none';
    panel.style.display = visible ? 'none' : 'block';
    btn.textContent     = visible ? 'Ver QR' : 'Ocultar QR';
}

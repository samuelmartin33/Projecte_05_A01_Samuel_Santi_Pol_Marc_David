document.querySelectorAll('[data-codigo]').forEach(function(el) {
    new QRCode(el, {
        text:       el.dataset.codigo,
        width:      parseInt(el.style.width)  || 220,
        height:     parseInt(el.style.height) || 220,
        colorDark:  '#000000',
        colorLight: '#ffffff',
    });
});

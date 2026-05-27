/**
 * empresa-perfil.js
 * Previsualización del logo antes de guardar en el perfil de empresa.
 */

/**
 * Previsualiza el logo seleccionado antes de guardarlo.
 * Llamado desde el atributo onchange del input file.
 * @param {HTMLInputElement} input
 */
function previsualizarLogo(input) {
    if (!input.files || !input.files[0]) return;

    var reader = new FileReader();
    reader.onload = function(e) {
        var wrap        = document.getElementById('logo-preview-wrap');
        var placeholder = document.getElementById('logo-placeholder');
        var preview     = document.getElementById('logo-preview');

        if (placeholder) placeholder.style.display = 'none';

        if (!preview) {
            preview           = document.createElement('img');
            preview.id        = 'logo-preview';
            preview.style.cssText = 'width:100%;height:100%;object-fit:cover;';
            wrap.appendChild(preview);
        }

        preview.src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
}

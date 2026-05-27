/**
 * empresa-facturacion.js — logica de facturacion empresa
 */
(function () {
    // ── Custom select ──
    function toggleSelect(id) {
        var el = document.getElementById(id);
        var isOpen = el.classList.contains('open');
        // Cerrar todos
        document.querySelectorAll('.cselect.open').forEach(function(s) { s.classList.remove('open'); });
        if (!isOpen) el.classList.add('open');
    }

    function getSelectVal(id) {
        var sel = document.querySelector('#' + id + ' .cselect-option.selected');
        return sel ? sel.dataset.val : '';
    }

    function setSelectVal(id, val) {
        var el = document.getElementById(id);
        el.querySelectorAll('.cselect-option').forEach(function(opt) {
            var active = opt.dataset.val === val;
            opt.classList.toggle('selected', active);
            if (active) el.querySelector('.cselect-val').textContent = opt.textContent.trim();
        });
    }

    // Delegar click en opciones
    document.addEventListener('click', function(e) {
        var opt = e.target.closest('.cselect-option');
        if (opt) {
            var cs = opt.closest('.cselect');
            setSelectVal(cs.id, opt.dataset.val);
            cs.classList.remove('open');
            aplicarFiltros();
            return;
        }
        // Cerrar al clicar fuera
        if (!e.target.closest('.cselect')) {
            document.querySelectorAll('.cselect.open').forEach(function(s) { s.classList.remove('open'); });
        }
    });

    // ── Filtrar y ordenar ──
    function aplicarFiltros() {
        var nombre = document.getElementById('f-nombre').value.trim().toLowerCase();
        var desde  = document.getElementById('f-desde').value;
        var hasta  = document.getElementById('f-hasta').value;
        var estado = getSelectVal('cs-estado');
        var ventas = getSelectVal('cs-ventas');
        var orden  = getSelectVal('cs-orden') || 'fecha-desc';

        var tbody  = document.getElementById('adm-tbody');
        var rows   = Array.from(tbody.querySelectorAll('tr'));

        var visible = rows.filter(function(r) {
            if (nombre && r.dataset.nombre.indexOf(nombre) === -1) return false;
            if (desde  && r.dataset.fecha < desde) return false;
            if (hasta  && r.dataset.fecha > hasta) return false;
            if (estado && r.dataset.estado !== estado) return false;
            if (ventas === 'con' && parseInt(r.dataset.vendidas) === 0) return false;
            if (ventas === 'sin' && parseInt(r.dataset.vendidas) > 0) return false;
            return true;
        });
        var hidden = rows.filter(function(r) { return visible.indexOf(r) === -1; });

        visible.sort(function(a, b) {
            switch (orden) {
                case 'fecha-asc':    return a.dataset.fecha.localeCompare(b.dataset.fecha);
                case 'ventas-desc':  return parseInt(b.dataset.vendidas) - parseInt(a.dataset.vendidas);
                case 'ventas-asc':   return parseInt(a.dataset.vendidas) - parseInt(b.dataset.vendidas);
                case 'importe-desc': return parseFloat(b.dataset.bruto)  - parseFloat(a.dataset.bruto);
                case 'importe-asc':  return parseFloat(a.dataset.bruto)  - parseFloat(b.dataset.bruto);
                case 'nombre-asc':   return a.dataset.nombre.localeCompare(b.dataset.nombre, 'es');
                case 'nombre-desc':  return b.dataset.nombre.localeCompare(a.dataset.nombre, 'es');
                default:             return b.dataset.fecha.localeCompare(a.dataset.fecha);
            }
        });

        visible.forEach(function(r) { r.style.display = ''; tbody.appendChild(r); });
        hidden.forEach(function(r)  { r.style.display = 'none'; });

        document.getElementById('adm-count-num').textContent = visible.length;
        document.getElementById('adm-empty-filter').style.display = visible.length === 0 ? 'block' : 'none';
    }

    function sortByCol(th) {
        var map = { nombre:'nombre', estado:'nombre', vendidas:'ventas', bruto:'importe' };
        var col = map[th.dataset.col];
        if (!col) return;

        var curOrd = getSelectVal('cs-orden') || 'fecha-desc';
        var newOrd = curOrd === col + '-desc' ? col + '-asc' : col + '-desc';
        setSelectVal('cs-orden', newOrd);

        document.querySelectorAll('#adm-table thead th').forEach(function(h) { h.classList.remove('sorted-asc','sorted-desc'); });
        th.classList.add(newOrd.endsWith('-asc') ? 'sorted-asc' : 'sorted-desc');
        aplicarFiltros();
    }

    function resetFiltros() {
        document.getElementById('f-nombre').value = '';
        document.getElementById('f-desde').value  = '';
        document.getElementById('f-hasta').value  = '';
        setSelectVal('cs-estado', '');
        setSelectVal('cs-ventas', '');
        setSelectVal('cs-orden', 'fecha-desc');
        document.querySelectorAll('#adm-table thead th').forEach(function(h) { h.classList.remove('sorted-asc','sorted-desc'); });
        aplicarFiltros();
    }

    window.aplicarFiltros = aplicarFiltros;
    window.sortByCol      = sortByCol;
    window.resetFiltros   = resetFiltros;
    window.toggleSelect   = toggleSelect;
})();

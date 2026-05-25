<?php $__env->startSection('titulo', 'Administración — ' . $empresa->nombre_empresa); ?>

<?php $__env->startPush('estilos'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/empresa-home.css')); ?>">
<style>
/* ── Tabla ── */
.adm-table-wrap { background:#0d0a18; border:1px solid rgba(245,241,234,0.10); overflow-x:auto; }
.adm-table { width:100%; border-collapse:collapse; }
.adm-table thead tr { border-bottom:1px solid rgba(245,241,234,0.10); }
.adm-table thead th {
    padding:13px 20px; font-family:'Archivo Narrow',sans-serif;
    font-size:0.5625rem; font-weight:600; text-transform:uppercase; letter-spacing:0.18em;
    color:rgba(245,241,234,0.35); white-space:nowrap; text-align:left;
    cursor:pointer; user-select:none; transition:color 0.15s;
}
.adm-table thead th.r { text-align:right; }
.adm-table thead th:hover { color:rgba(245,241,234,0.65); }
.adm-table thead th.sorted-asc  .sort-icon::after { content:' ↑'; color:#c084fc; }
.adm-table thead th.sorted-desc .sort-icon::after { content:' ↓'; color:#c084fc; }
.adm-table tbody tr { border-bottom:1px solid rgba(245,241,234,0.06); transition:background 0.12s; }
.adm-table tbody tr:last-child { border-bottom:none; }
.adm-table tbody tr:hover { background:rgba(245,241,234,0.03); }
.adm-table td { padding:16px 20px; color:#f5f1ea; vertical-align:middle; }
.adm-table td.r { text-align:right; }
.adm-evento-nombre { font-family:'Anton',sans-serif; font-size:1rem; text-transform:uppercase; letter-spacing:-0.01em; color:#f5f1ea; line-height:1.2; }
.adm-evento-fecha  { font-family:'Archivo Narrow',sans-serif; font-size:0.5625rem; font-weight:600; text-transform:uppercase; letter-spacing:0.14em; color:rgba(245,241,234,0.35); margin-top:4px; }
.adm-badge-activo  { display:inline-block; font-family:'Archivo Narrow',sans-serif; font-size:0.5rem; font-weight:700; text-transform:uppercase; letter-spacing:0.14em; padding:3px 8px; background:rgba(52,211,153,0.12); color:#34d399; border:1px solid rgba(52,211,153,0.25); }
.adm-badge-inactivo{ display:inline-block; font-family:'Archivo Narrow',sans-serif; font-size:0.5rem; font-weight:700; text-transform:uppercase; letter-spacing:0.14em; padding:3px 8px; background:rgba(245,241,234,0.06); color:rgba(245,241,234,0.35); border:1px solid rgba(245,241,234,0.10); }
.btn-pdf     { display:inline-flex; align-items:center; gap:6px; font-family:'Archivo Narrow',sans-serif; font-size:0.625rem; font-weight:700; text-transform:uppercase; letter-spacing:0.12em; padding:7px 14px; background:rgba(168,85,247,0.12); color:#c084fc; border:1px solid rgba(168,85,247,0.30); text-decoration:none; transition:background 0.15s,border-color 0.15s; white-space:nowrap; }
.btn-pdf:hover{ background:rgba(168,85,247,0.22); border-color:rgba(168,85,247,0.50); color:#d8b4fe; }
.btn-pdf-dis { display:inline-flex; align-items:center; gap:6px; font-family:'Archivo Narrow',sans-serif; font-size:0.625rem; font-weight:700; text-transform:uppercase; letter-spacing:0.12em; padding:7px 14px; background:transparent; color:rgba(245,241,234,0.18); border:1px solid rgba(245,241,234,0.08); cursor:default; white-space:nowrap; }

/* ── Barra de filtros ── */
.filtros-bar { display:flex; flex-wrap:wrap; gap:10px; align-items:flex-end; margin-bottom:16px; }
.filtro-grupo { display:flex; flex-direction:column; gap:5px; }
.filtro-label { font-family:'Archivo Narrow',sans-serif; font-size:0.5rem; font-weight:700; text-transform:uppercase; letter-spacing:0.16em; color:rgba(245,241,234,0.35); }
.filtro-input {
    background:#0d0a18; border:1px solid rgba(245,241,234,0.12); color:#f5f1ea;
    padding:8px 12px; font-size:0.8rem; font-family:'Archivo Narrow',sans-serif;
    outline:none; transition:border-color 0.15s;
}
.filtro-input::placeholder { color:rgba(245,241,234,0.25); }
.filtro-input:focus { border-color:rgba(168,85,247,0.50); }
.filtro-input-search { min-width:220px; }
.filtro-input-date   { min-width:130px; color-scheme:dark; }
.filtro-reset {
    display:inline-flex; align-items:center; gap:5px;
    font-family:'Archivo Narrow',sans-serif; font-size:0.625rem; font-weight:700;
    text-transform:uppercase; letter-spacing:0.12em; padding:8px 14px;
    background:transparent; color:rgba(245,241,234,0.30); border:1px solid rgba(245,241,234,0.10);
    cursor:pointer; transition:color 0.15s,border-color 0.15s;
}
.filtro-reset:hover { color:rgba(245,241,234,0.65); border-color:rgba(245,241,234,0.25); }

/* ── Custom dropdown ── */
.cselect { position:relative; min-width:160px; }
.cselect-trigger {
    display:flex; align-items:center; justify-content:space-between; gap:10px;
    background:#0d0a18; border:1px solid rgba(245,241,234,0.12);
    color:#f5f1ea; padding:8px 12px; font-size:0.8rem;
    font-family:'Archivo Narrow',sans-serif; cursor:pointer;
    transition:border-color 0.15s; user-select:none; white-space:nowrap;
}
.cselect-trigger:hover  { border-color:rgba(245,241,234,0.25); }
.cselect.open .cselect-trigger { border-color:rgba(168,85,247,0.55); }
.cselect-arrow { width:10px; height:10px; flex-shrink:0; opacity:0.4; transition:transform 0.15s,opacity 0.15s; }
.cselect.open .cselect-arrow  { transform:rotate(180deg); opacity:0.8; }
.cselect-menu {
    display:none; position:absolute; top:calc(100% + 4px); left:0; right:0;
    background:#0f0c1e; border:1px solid rgba(168,85,247,0.30);
    z-index:200; overflow:hidden;
    box-shadow:0 8px 32px rgba(0,0,0,0.55);
}
.cselect.open .cselect-menu { display:block; }
.cselect-option {
    padding:9px 14px; font-family:'Archivo Narrow',sans-serif; font-size:0.8rem;
    color:rgba(245,241,234,0.65); cursor:pointer; transition:background 0.1s,color 0.1s;
    white-space:nowrap;
}
.cselect-option:hover   { background:rgba(168,85,247,0.12); color:#f5f1ea; }
.cselect-option.selected{ background:rgba(168,85,247,0.18); color:#c084fc; font-weight:700; }

/* ── Contador / empty ── */
.adm-count { font-family:'Archivo Narrow',sans-serif; font-size:0.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:0.12em; color:rgba(245,241,234,0.30); margin-bottom:10px; }
.adm-count span { color:#c084fc; }
#adm-empty-filter { display:none; padding:32px; text-align:center; font-family:'Archivo Narrow',sans-serif; font-size:0.8rem; font-weight:600; text-transform:uppercase; letter-spacing:0.12em; color:rgba(245,241,234,0.20); }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<?php echo $__env->make('partials.home.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<section class="hero-home">
    <div class="hero-particula hero-particula-1"></div>
    <div class="hero-particula hero-particula-2"></div>
    <div class="hero-particula hero-particula-3"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 text-center relative z-10">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold mb-5"
             style="background:rgba(168,85,247,0.12);border:1px solid rgba(168,85,247,0.28);color:#c084fc;letter-spacing:0.06em;text-transform:uppercase;">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
            </svg>
            Ventas y facturación
        </div>
        <h1 class="text-4xl sm:text-5xl font-black text-white tracking-tight leading-tight mb-3">Administración</h1>
        <p class="text-slate-400 text-lg max-w-xl mx-auto">Consulta las ventas de cada evento y genera la factura en PDF.</p>
    </div>
</section>


<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="empresa-stats-grid" style="grid-template-columns:repeat(4,1fr);">
        <div class="empresa-stat-card">
            <div class="empresa-stat-numero" style="color:#34d399;"><?php echo e($totalIngresos > 0 ? number_format($totalIngresos,0,',','.') . '€' : '—'); ?></div>
            <div class="empresa-stat-label">Ingresos totales</div>
        </div>
        <div class="empresa-stat-card">
            <div class="empresa-stat-numero" style="color:#c084fc;"><?php echo e(number_format($totalEntradas,0,',','.')); ?></div>
            <div class="empresa-stat-label">Entradas vendidas</div>
        </div>
        <div class="empresa-stat-card">
            <div class="empresa-stat-numero"><?php echo e($eventos->count()); ?></div>
            <div class="empresa-stat-label">Eventos totales</div>
        </div>
        <div class="empresa-stat-card" style="border-right:none;">
            <div class="empresa-stat-numero"><?php echo e($avgTicket > 0 ? number_format($avgTicket,2,',','.') . '€' : '—'); ?></div>
            <div class="empresa-stat-label">Precio medio</div>
        </div>
    </div>
</section>


<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">

    <div class="seccion-empresa-titulo">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        Tus eventos
    </div>
    <p class="seccion-empresa-sub">Filtra, ordena y genera la factura de ventas en PDF</p>

    <?php if($eventos->isEmpty()): ?>
        <div class="empty-state">
            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="empty-titulo">Aún no tienes eventos</p>
            <p class="empty-desc">Crea tu primer evento para ver las ventas aquí.</p>
        </div>
    <?php else: ?>

    
    <div class="filtros-bar">

        <div class="filtro-grupo" style="flex:1;min-width:200px;">
            <label class="filtro-label">Buscar evento</label>
            <input id="f-nombre" class="filtro-input filtro-input-search" type="text"
                   placeholder="Nombre del evento…" oninput="aplicarFiltros()">
        </div>

        <div class="filtro-grupo">
            <label class="filtro-label">Fecha desde</label>
            <input id="f-desde" class="filtro-input filtro-input-date" type="date" oninput="aplicarFiltros()">
        </div>

        <div class="filtro-grupo">
            <label class="filtro-label">Fecha hasta</label>
            <input id="f-hasta" class="filtro-input filtro-input-date" type="date" oninput="aplicarFiltros()">
        </div>

        
        <div class="filtro-grupo">
            <label class="filtro-label">Estado</label>
            <div class="cselect" id="cs-estado">
                <div class="cselect-trigger" onclick="toggleSelect('cs-estado')">
                    <span class="cselect-val">Todos</span>
                    <svg class="cselect-arrow" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
                <div class="cselect-menu">
                    <div class="cselect-option selected" data-val="">Todos</div>
                    <div class="cselect-option" data-val="activo">Activo</div>
                    <div class="cselect-option" data-val="inactivo">Inactivo</div>
                </div>
            </div>
        </div>

        
        <div class="filtro-grupo">
            <label class="filtro-label">Ventas</label>
            <div class="cselect" id="cs-ventas">
                <div class="cselect-trigger" onclick="toggleSelect('cs-ventas')">
                    <span class="cselect-val">Todas</span>
                    <svg class="cselect-arrow" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
                <div class="cselect-menu">
                    <div class="cselect-option selected" data-val="">Todas</div>
                    <div class="cselect-option" data-val="con">Con ventas</div>
                    <div class="cselect-option" data-val="sin">Sin ventas</div>
                </div>
            </div>
        </div>

        
        <div class="filtro-grupo">
            <label class="filtro-label">Ordenar por</label>
            <div class="cselect" id="cs-orden" style="min-width:175px;">
                <div class="cselect-trigger" onclick="toggleSelect('cs-orden')">
                    <span class="cselect-val">Más reciente</span>
                    <svg class="cselect-arrow" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
                <div class="cselect-menu">
                    <div class="cselect-option selected" data-val="fecha-desc">Más reciente</div>
                    <div class="cselect-option" data-val="fecha-asc">Más antiguo</div>
                    <div class="cselect-option" data-val="ventas-desc">Más ventas</div>
                    <div class="cselect-option" data-val="ventas-asc">Menos ventas</div>
                    <div class="cselect-option" data-val="importe-desc">Mayor importe</div>
                    <div class="cselect-option" data-val="importe-asc">Menor importe</div>
                    <div class="cselect-option" data-val="nombre-asc">Nombre A → Z</div>
                    <div class="cselect-option" data-val="nombre-desc">Nombre Z → A</div>
                </div>
            </div>
        </div>

        <div class="filtro-grupo" style="justify-content:flex-end;">
            <label class="filtro-label" style="opacity:0;">·</label>
            <button class="filtro-reset" onclick="resetFiltros()">
                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Limpiar
            </button>
        </div>

    </div>

    <div class="adm-count" id="adm-count">
        Mostrando <span id="adm-count-num"><?php echo e($eventos->count()); ?></span> de <?php echo e($eventos->count()); ?> eventos
    </div>

    <div class="adm-table-wrap">
        <table class="adm-table" id="adm-table">
            <thead>
                <tr>
                    <th data-col="nombre" onclick="sortByCol(this)"><span class="sort-icon">Evento</span></th>
                    <th data-col="estado" onclick="sortByCol(this)"><span class="sort-icon">Estado</span></th>
                    <th class="r" data-col="vendidas" onclick="sortByCol(this)"><span class="sort-icon">Entradas vendidas</span></th>
                    <th class="r" data-col="bruto" onclick="sortByCol(this)"><span class="sort-icon">Importe bruto</span></th>
                    <th class="r">Factura PDF</th>
                </tr>
            </thead>
            <tbody id="adm-tbody">
            <?php $__currentLoopData = $eventos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $vendidas = (int)   ($ev->entradas_vendidas ?? 0);
                $bruto    = (float) ($ev->ingresos_brutos   ?? 0);
                $estado   = $ev->estado == 1 ? 'activo' : 'inactivo';
            ?>
            <tr data-nombre="<?php echo e(strtolower($ev->titulo)); ?>"
                data-fecha="<?php echo e(\Carbon\Carbon::parse($ev->fecha_inicio)->format('Y-m-d')); ?>"
                data-estado="<?php echo e($estado); ?>"
                data-vendidas="<?php echo e($vendidas); ?>"
                data-bruto="<?php echo e($bruto); ?>">
                <td>
                    <div class="adm-evento-nombre"><?php echo e($ev->titulo); ?></div>
                    <div class="adm-evento-fecha">
                        <?php echo e(\Carbon\Carbon::parse($ev->fecha_inicio)->locale('es')->isoFormat('D MMM YYYY · H:mm')); ?>

                        <?php if($ev->ubicacion_nombre): ?> &nbsp;·&nbsp;<?php echo e($ev->ubicacion_nombre); ?> <?php endif; ?>
                    </div>
                </td>
                <td>
                    <?php if($estado === 'activo'): ?>
                        <span class="adm-badge-activo">Activo</span>
                    <?php else: ?>
                        <span class="adm-badge-inactivo">Inactivo</span>
                    <?php endif; ?>
                </td>
                <td class="r">
                    <span style="font-family:'Anton',sans-serif;font-size:1.1rem;color:#c084fc;"><?php echo e(number_format($vendidas)); ?></span>
                    <?php if($ev->aforo_maximo): ?>
                        <span style="font-family:'Archivo Narrow',sans-serif;font-size:0.7rem;color:rgba(245,241,234,0.25);"> / <?php echo e($ev->aforo_maximo); ?></span>
                    <?php endif; ?>
                </td>
                <td class="r">
                    <?php if($ev->es_gratuito && $bruto == 0): ?>
                        <span style="font-family:'Archivo Narrow',sans-serif;font-size:0.65rem;font-weight:600;text-transform:uppercase;letter-spacing:.1em;color:rgba(245,241,234,0.25);">Gratuito</span>
                    <?php else: ?>
                        <span style="font-family:'Anton',sans-serif;font-size:1.1rem;color:#34d399;"><?php echo e(number_format($bruto,2,',','.')); ?> €</span>
                    <?php endif; ?>
                </td>
                <td class="r">
                    <?php if($vendidas > 0): ?>
                        <a href="<?php echo e(route('empresa.facturacion.generar-pdf', $ev)); ?>" class="btn-pdf" target="_blank">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Generar factura
                        </a>
                    <?php else: ?>
                        <span class="btn-pdf-dis">Sin ventas</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div id="adm-empty-filter">No hay eventos que coincidan con los filtros aplicados.</div>
    </div>

    <?php endif; ?>
</section>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
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
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\DAW2\proyectos\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/empresa/facturacion/index.blade.php ENDPATH**/ ?>
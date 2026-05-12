<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:Arial,Helvetica,sans-serif; font-size:12px; color:#1a1a2e; background:#fff; }

.page { padding:40px 45px; }

/* Cabecera */
.header-table { width:100%; border-collapse:collapse; margin-bottom:28px; }
.logo-text    { font-size:26px; font-weight:900; color:#7c3aed; letter-spacing:2px; }
.logo-sub     { font-size:9px; color:#999; text-transform:uppercase; letter-spacing:1.2px; margin-top:3px; }
.logo-datos   { font-size:9px; color:#666; margin-top:10px; line-height:1.6; }
.ref-cell     { text-align:right; vertical-align:top; }
.titulo-doc   { font-size:18px; font-weight:900; color:#1a1a2e; }
.fecha-gen    { font-size:10px; color:#666; margin-top:5px; }
.doc-badge    { display:inline-block; background:#7c3aed; color:#fff;
               padding:3px 10px; font-size:9px; font-weight:700;
               text-transform:uppercase; letter-spacing:1px; margin-top:8px; }

/* Separador */
.divider { border:none; border-top:2px solid #7c3aed; margin:0 0 24px; }

/* Empresa emisora */
.partes-table { width:100%; border-collapse:collapse; margin-bottom:22px; }
.parte-cell   { width:50%; vertical-align:top; padding-right:20px; }
.parte-label  { font-size:8px; font-weight:700; text-transform:uppercase;
               letter-spacing:1.5px; color:#7c3aed; margin-bottom:6px; }
.parte-nombre { font-size:13px; font-weight:700; color:#1a1a2e; margin-bottom:3px; }
.parte-dato   { font-size:10px; color:#555; margin-bottom:2px; }

/* Caja evento */
.evento-box   { background:#f3f0ff; border-left:4px solid #7c3aed;
               padding:12px 16px; margin-bottom:24px; }
.evento-label { font-size:8px; font-weight:700; text-transform:uppercase;
               letter-spacing:1.5px; color:#7c3aed; margin-bottom:5px; }
.evento-titulo{ font-size:15px; font-weight:900; color:#1a1a2e; }
.evento-meta  { font-size:10px; color:#666; margin-top:4px; }

/* Tabla de entradas */
.lineas-table       { width:100%; border-collapse:collapse; margin-bottom:16px; }
.lineas-table thead tr { background:#1a1a2e; color:#fff; }
.lineas-table th    { padding:9px 12px; font-size:9px; text-transform:uppercase;
                     letter-spacing:.8px; text-align:left; font-weight:700; }
.lineas-table th.r  { text-align:right; }
.lineas-table td    { padding:8px 12px; font-size:10px; color:#333;
                     border-bottom:1px solid #eee; vertical-align:top; }
.lineas-table td.r  { text-align:right; }
.lineas-table tbody tr:nth-child(even) td { background:#fafafa; }
.td-comprador { font-weight:600; color:#1a1a2e; }
.td-email     { font-size:9px; color:#888; margin-top:1px; }
.td-gratis    { color:#059669; font-weight:700; }
.td-precio    { color:#1a1a2e; font-weight:700; }

/* Totales */
.totales-wrap  { text-align:right; margin-bottom:28px; }
.totales-table { display:inline-table; min-width:280px; border-collapse:collapse; }
.totales-table td { padding:7px 14px; font-size:11px; border-bottom:1px solid #eee; }
.totales-table td.lbl { text-align:left; color:#555; }
.totales-table td.val { text-align:right; font-weight:700; }
.totales-table tr.total-final td { background:#7c3aed; color:#fff;
                                   font-size:14px; font-weight:900; border:none; }

/* Pie */
.footer { margin-top:36px; border-top:1px solid #eee; padding-top:12px;
         font-size:9px; color:#aaa; text-align:center; line-height:1.7; }
.footer strong { color:#7c3aed; }
</style>
</head>
<body>
<div class="page">

    
    <table class="header-table">
        <tr>
            <td style="vertical-align:top;width:55%;">
                <div class="logo-text">VIBEZ</div>
                <div class="logo-sub">Plataforma de eventos para jóvenes</div>
                <div class="logo-datos">
                    <?php echo e($empresa->nombre_empresa); ?><br>
                    <?php if($empresa->nif_cif): ?> NIF/CIF: <?php echo e($empresa->nif_cif); ?><br><?php endif; ?>
                    <?php if($empresa->direccion): ?> <?php echo e($empresa->direccion); ?><br><?php endif; ?>
                    <?php if($empresa->email_contacto): ?> <?php echo e($empresa->email_contacto); ?><?php endif; ?>
                </div>
            </td>
            <td class="ref-cell">
                <div class="titulo-doc">Informe de Ventas</div>
                <div class="fecha-gen">
                    Generado: <?php echo e(now()->format('d/m/Y \a \l\a\s H:i')); ?>

                </div>
                <div>
                    <span class="doc-badge"><?php echo e($totalEntradas); ?> entrada<?php echo e($totalEntradas !== 1 ? 's' : ''); ?> vendida<?php echo e($totalEntradas !== 1 ? 's' : ''); ?></span>
                </div>
            </td>
        </tr>
    </table>

    <hr class="divider">

    
    <table class="partes-table">
        <tr>
            <td class="parte-cell">
                <div class="parte-label">Empresa organizadora</div>
                <div class="parte-nombre"><?php echo e($empresa->nombre_empresa); ?></div>
                <?php if($empresa->razon_social): ?>
                    <div class="parte-dato"><?php echo e($empresa->razon_social); ?></div>
                <?php endif; ?>
                <?php if($empresa->nif_cif): ?>
                    <div class="parte-dato">NIF/CIF: <?php echo e($empresa->nif_cif); ?></div>
                <?php endif; ?>
                <?php if($empresa->direccion): ?>
                    <div class="parte-dato"><?php echo e($empresa->direccion); ?></div>
                <?php endif; ?>
            </td>
            <td class="parte-cell">
                <div class="parte-label">Resumen económico</div>
                <div class="parte-dato" style="margin-bottom:4px;">
                    <strong>Entradas pagadas:</strong> <?php echo e($entradasPagadas); ?>

                </div>
                <div class="parte-dato" style="margin-bottom:4px;">
                    <strong>Entradas gratuitas:</strong> <?php echo e($entradasGratis); ?>

                </div>
                <div class="parte-dato">
                    <strong>Total entradas:</strong> <?php echo e($totalEntradas); ?>

                </div>
            </td>
        </tr>
    </table>

    
    <div class="evento-box">
        <div class="evento-label">Evento</div>
        <div class="evento-titulo"><?php echo e($evento->titulo); ?></div>
        <div class="evento-meta">
            Fecha: <?php echo e(\Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y H:i')); ?>

            <?php if($evento->ubicacion_nombre): ?>
                &nbsp;&bull;&nbsp;<?php echo e($evento->ubicacion_nombre); ?>

            <?php endif; ?>
            <?php if($evento->aforo_maximo): ?>
                &nbsp;&bull;&nbsp;Aforo: <?php echo e($evento->aforo_maximo); ?>

            <?php endif; ?>
        </div>
    </div>

    
    <table class="lineas-table">
        <thead>
            <tr>
                <th style="width:5%;">#</th>
                <th style="width:35%;">Comprador</th>
                <th style="width:30%;">Email</th>
                <th style="width:15%;">Fecha compra</th>
                <th class="r" style="width:15%;">Importe</th>
            </tr>
        </thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $entradas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $entrada): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td style="color:#aaa;font-size:9px;"><?php echo e($i + 1); ?></td>
                <td>
                    <div class="td-comprador">
                        <?php echo e($entrada->pedido?->usuario?->nombre ?? '—'); ?>

                        <?php echo e($entrada->pedido?->usuario?->apellido1 ?? ''); ?>

                    </div>
                </td>
                <td style="font-size:9px;color:#888;">
                    <?php echo e($entrada->pedido?->usuario?->email ?? '—'); ?>

                </td>
                <td style="font-size:9px;color:#666;">
                    <?php echo e(\Carbon\Carbon::parse($entrada->fecha_creacion)->format('d/m/Y H:i')); ?>

                </td>
                <td class="r">
                    <?php if($entrada->precio_pagado == 0): ?>
                        <span class="td-gratis">Gratis</span>
                    <?php else: ?>
                        <span class="td-precio"><?php echo e(number_format($entrada->precio_pagado, 2, ',', '.')); ?> €</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="5" style="text-align:center;color:#aaa;padding:20px;">
                    No hay entradas vendidas para este evento.
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    
    <div class="totales-wrap">
        <table class="totales-table">
            <tr>
                <td class="lbl">Entradas pagadas (<?php echo e($entradasPagadas); ?>)</td>
                <td class="val"><?php echo e(number_format($importeBruto, 2, ',', '.')); ?> €</td>
            </tr>
            <?php if($entradasGratis > 0): ?>
            <tr>
                <td class="lbl">Entradas gratuitas (<?php echo e($entradasGratis); ?>)</td>
                <td class="val" style="color:#059669;">0,00 €</td>
            </tr>
            <?php endif; ?>
            <tr class="total-final">
                <td class="lbl">TOTAL RECAUDADO</td>
                <td class="val"><?php echo e(number_format($importeBruto, 2, ',', '.')); ?> €</td>
            </tr>
        </table>
    </div>

    
    <div class="footer">
        Documento generado automáticamente por <strong>VIBEZ Platform</strong>
        el <?php echo e(now()->format('d/m/Y \a \l\a\s H:i')); ?>.<br>
        Este documento es un informe de ventas interno. <strong><?php echo e($empresa->nombre_empresa); ?></strong> · vibez.es
    </div>

</div>
</body>
</html>
<?php /**PATH C:\wamp64\www\LARAVEL\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/empresa/facturacion/pdf.blade.php ENDPATH**/ ?>
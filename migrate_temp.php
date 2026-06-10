<?php
/**
 * migrate_temp.php — Ejecutar migraciones pendientes en el servidor FTP.
 *
 * IMPORTANTE: Subir este archivo a la carpeta PUBLIC del servidor.
 * Acceder desde el navegador: https://tudominio.com/migrate_temp.php
 * BORRAR del servidor después de ejecutar.
 *
 * Migraciones nuevas de este despliegue (feature/stock-barra):
 *   - 2026_06_10_000001_create_tipos_bebida_table   → crea tabla tipos_bebida + seeds
 *   - 2026_06_10_000002_modify_tipo_producto_to_string → convierte enum → varchar en
 *       productos_barra.tipo_producto y bono_consumos.tipo_producto
 */

define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';
$app    = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$html  = '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8">';
$html .= '<title>Migración VIBEZ</title>';
$html .= '<style>body{font-family:monospace;background:#0f0d1e;color:#f5f1ea;padding:24px;}';
$html .= 'h2{color:#a855f7;} .ok{color:#4ade80;} .err{color:#f87171;} .info{color:#fbbf24;}';
$html .= 'pre{background:#13102a;border:1px solid rgba(168,85,247,0.2);padding:16px;border-radius:8px;white-space:pre-wrap;}</style></head><body>';
$html .= '<h2>🚀 VIBEZ — Migración de base de datos</h2>';
$html .= '<p class="info">Ejecutando migraciones pendientes...</p>';

/* ── Ejecutar artisan migrate --force ─────────────────────────────── */
$kernel->call('migrate', ['--force' => true]);
$output = $kernel->output();

/* ── Verificar si las tablas nuevas existen ───────────────────────── */
try {
    $pdo = $app->make('db')->connection()->getPdo();

    $tiposBebida    = $pdo->query("SHOW TABLES LIKE 'tipos_bebida'")->rowCount()   > 0;
    $productosBarra = $pdo->query("SHOW TABLES LIKE 'productos_barra'")->rowCount() > 0;
    $bonoConsumos   = $pdo->query("SHOW TABLES LIKE 'bono_consumos'")->rowCount()  > 0;

    /* Verificar que tipo_producto ya es VARCHAR (no ENUM) */
    $colProd = $pdo->query("SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'productos_barra'
        AND COLUMN_NAME = 'tipo_producto'")->fetchColumn();
    $colBono = $pdo->query("SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'bono_consumos'
        AND COLUMN_NAME = 'tipo_producto'")->fetchColumn();

    $countTipos = $tiposBebida
        ? $pdo->query("SELECT COUNT(*) FROM tipos_bebida")->fetchColumn()
        : 0;

    $html .= '<h3>Estado de las tablas</h3><pre>';
    $html .= ($tiposBebida    ? '✅' : '❌') . ' tipos_bebida          ' . ($tiposBebida    ? "EXISTS ($countTipos tipos cargados)" : 'MISSING') . "\n";
    $html .= ($productosBarra ? '✅' : '❌') . ' productos_barra       ' . ($productosBarra ? 'EXISTS' : 'MISSING') . "\n";
    $html .= ($bonoConsumos   ? '✅' : '❌') . ' bono_consumos         ' . ($bonoConsumos   ? 'EXISTS' : 'MISSING') . "\n";
    $html .= "\n";
    $html .= ($colProd === 'varchar' ? '✅' : '⚠️ ') . ' productos_barra.tipo_producto → ' . strtoupper($colProd ?: '?') . "\n";
    $html .= ($colBono === 'varchar' ? '✅' : '⚠️ ') . ' bono_consumos.tipo_producto   → ' . strtoupper($colBono ?: '?') . "\n";
    $html .= '</pre>';

    /* ── Fallback SQL por si las migraciones no aplicaron ─────────── */
    $errores = [];

    if (!$tiposBebida) {
        try {
            $pdo->exec("CREATE TABLE tipos_bebida (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                nombre VARCHAR(100) NOT NULL,
                icono VARCHAR(20) NOT NULL DEFAULT '🍹',
                activo TINYINT(1) NOT NULL DEFAULT 1,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )");
            $pdo->exec("INSERT INTO tipos_bebida (nombre, icono, activo, created_at, updated_at) VALUES
                ('Cocktail',    '🍸', 1, NOW(), NOW()),
                ('Destilado',   '🥃', 1, NOW(), NOW()),
                ('Sin alcohol', '🧃', 1, NOW(), NOW())");
            $html .= '<p class="ok">✅ tipos_bebida creada e inicializada con SQL de fallback.</p>';
        } catch (Exception $e) {
            $errores[] = 'tipos_bebida: ' . $e->getMessage();
        }
    }

    if ($productosBarra && $colProd === 'enum') {
        try {
            $pdo->exec("ALTER TABLE productos_barra MODIFY tipo_producto VARCHAR(100) NOT NULL");
            $pdo->exec("UPDATE productos_barra SET tipo_producto='Cocktail'    WHERE tipo_producto='cocktail'");
            $pdo->exec("UPDATE productos_barra SET tipo_producto='Destilado'   WHERE tipo_producto='destilado'");
            $pdo->exec("UPDATE productos_barra SET tipo_producto='Sin alcohol' WHERE tipo_producto='sin_alcohol'");
            $html .= '<p class="ok">✅ productos_barra.tipo_producto convertido a VARCHAR con SQL de fallback.</p>';
        } catch (Exception $e) {
            $errores[] = 'productos_barra modify: ' . $e->getMessage();
        }
    }

    if ($bonoConsumos && $colBono === 'enum') {
        try {
            $pdo->exec("ALTER TABLE bono_consumos MODIFY tipo_producto VARCHAR(100) NOT NULL");
            $pdo->exec("UPDATE bono_consumos SET tipo_producto='Cocktail'    WHERE tipo_producto='cocktail'");
            $pdo->exec("UPDATE bono_consumos SET tipo_producto='Destilado'   WHERE tipo_producto='destilado'");
            $pdo->exec("UPDATE bono_consumos SET tipo_producto='Sin alcohol' WHERE tipo_producto='sin_alcohol'");
            $html .= '<p class="ok">✅ bono_consumos.tipo_producto convertido a VARCHAR con SQL de fallback.</p>';
        } catch (Exception $e) {
            $errores[] = 'bono_consumos modify: ' . $e->getMessage();
        }
    }

    if (!empty($errores)) {
        $html .= '<p class="err">❌ Errores en fallback SQL:<br>' . implode('<br>', array_map('htmlspecialchars', $errores)) . '</p>';
    }

} catch (Exception $e) {
    $html .= '<p class="err">❌ Error al verificar tablas: ' . htmlspecialchars($e->getMessage()) . '</p>';
}

/* ── Output de artisan migrate ────────────────────────────────────── */
$html .= '<h3>Output de artisan migrate</h3>';
$html .= '<pre>' . nl2br(htmlspecialchars($output)) . '</pre>';
$html .= '<p class="info">⚠️ Borra este archivo del servidor cuando termines.</p>';
$html .= '</body></html>';

echo $html;

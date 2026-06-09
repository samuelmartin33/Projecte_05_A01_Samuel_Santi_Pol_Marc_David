<?php

/**
 * migrar.php — Migraciones + limpieza de caché desde el navegador.
 *
 * Uso: https://tu-dominio.com/migrar.php?secret=vibez_migrate_2026
 *
 * IMPORTANTE: elimina este archivo del servidor después de usarlo.
 */

if (($_GET['secret'] ?? '') !== 'vibez_migrate_2026') {
    http_response_code(403);
    die('Acceso no autorizado.');
}

define('LARAVEL_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

$app    = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$salida = '';

// 1. Migraciones pendientes (no borra datos)
$kernel->call('migrate', ['--force' => true]);
$salida .= "=== migrate ===\n" . $kernel->output();

// 2. Caché de vistas Blade compiladas (storage/framework/views/)
$kernel->call('view:clear');
$salida .= "=== view:clear ===\n" . $kernel->output();

// 3. Caché de configuración
$kernel->call('config:clear');
$salida .= "=== config:clear ===\n" . $kernel->output();

// 4. Caché de rutas
$kernel->call('route:clear');
$salida .= "=== route:clear ===\n" . $kernel->output();

// 5. Caché de aplicación general
$kernel->call('cache:clear');
$salida .= "=== cache:clear ===\n" . $kernel->output();

echo '<pre style="font-family:monospace;background:#0f0d1e;color:#c084fc;padding:24px;font-size:14px;line-height:1.6;">';
echo htmlspecialchars($salida);
echo '</pre>';

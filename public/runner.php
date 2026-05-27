<?php
/**
 * SCRIPT TEMPORAL DE MIGRACIÓN - VIBEZ
 * ⚠️ ELIMINAR DEL SERVIDOR INMEDIATAMENTE DESPUÉS DE USAR ⚠️
 *
 * Permite ejecutar migrate:fresh --seed desde el navegador
 * cuando solo se dispone de acceso FTP al servidor.
 */

// --- Contraseña de acceso (cámbiala si quieres) ---
define('CLAVE_ACCESO', 'vibez2024');

// --- Estilos básicos inline ---
$css = '
    body { font-family: monospace; background: #0f172a; color: #e2e8f0; padding: 2rem; margin: 0; }
    h2   { color: #a855f7; }
    pre  { background: #1e293b; padding: 1.5rem; border-radius: 8px; overflow-x: auto; line-height: 1.6; }
    .ok  { color: #4ade80; }
    .err { color: #f87171; }
    .warn{ color: #facc15; }
    form { display: flex; gap: 1rem; align-items: center; margin-top: 1rem; }
    input[type=password] { padding: .5rem 1rem; border-radius: 6px; border: none; font-size: 1rem; }
    button { background: #7c3aed; color: white; border: none; padding: .5rem 1.5rem;
             border-radius: 6px; cursor: pointer; font-size: 1rem; }
    button:hover { background: #a855f7; }
';

// ─── Sin envío de formulario: mostrar login ───────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<!DOCTYPE html><html><head><meta charset='utf-8'>
          <title>VIBEZ Runner</title><style>{$css}</style></head><body>
          <h2>⚡ VIBEZ — Artisan Runner</h2>
          <p class='warn'>⚠️ Script temporal. Elimínalo del servidor después de usarlo.</p>
          <form method='POST'>
              <input type='password' name='clave' placeholder='Contraseña' autofocus>
              <button type='submit'>Ejecutar migrate:fresh --seed</button>
          </form>
          </body></html>";
    exit;
}

// ─── Contraseña incorrecta ────────────────────────────────────────────────────
if (!isset($_POST['clave']) || $_POST['clave'] !== CLAVE_ACCESO) {
    echo "<!DOCTYPE html><html><head><meta charset='utf-8'>
          <title>VIBEZ Runner</title><style>{$css}</style></head><body>
          <h2>⚡ VIBEZ — Artisan Runner</h2>
          <p class='err'>❌ Contraseña incorrecta.</p>
          <a href='' style='color:#a855f7'>← Volver</a>
          </body></html>";
    exit;
}

// ─── Contraseña correcta: ejecutar artisan ────────────────────────────────────
set_time_limit(300);
ini_set('memory_limit', '256M');

define('LARAVEL_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';
$app    = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<!DOCTYPE html><html><head><meta charset='utf-8'>
      <title>VIBEZ Runner</title><style>{$css}</style></head><body>
      <h2>⚡ VIBEZ — Artisan Runner</h2><pre>";

echo "▶ Ejecutando: php artisan migrate:fresh --seed --force\n";
echo str_repeat('─', 55) . "\n\n";

// Ejecutar el comando
$estado = $kernel->call('migrate:fresh', [
    '--seed'  => true,
    '--force' => true, // obligatorio en APP_ENV=production
]);

// Mostrar salida del comando
echo htmlspecialchars($kernel->output());

echo "\n" . str_repeat('─', 55) . "\n";

if ($estado === 0) {
    echo "<span class='ok'>✅ Migración completada correctamente.</span>\n";
} else {
    echo "<span class='err'>❌ El comando terminó con errores (código: {$estado}).</span>\n";
}

// ─── Autodestrucción del script ───────────────────────────────────────────────
$eliminado = @unlink(__FILE__);
if ($eliminado) {
    echo "\n<span class='ok'>🗑️  El script se ha eliminado automáticamente del servidor.</span>\n";
} else {
    echo "\n<span class='warn'>⚠️  No se pudo eliminar automáticamente. BORRA public/runner.php via FTP ahora.</span>\n";
}

echo "</pre></body></html>";

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Cambia productos_barra de per-evento a per-empresa:
 *  1. Añade columna empresa_id (INT UNSIGNED NOT NULL)
 *  2. Elimina la FK de evento_id y lo hace nullable
 *  3. Migra los datos existentes (empresa desde evento → organizador)
 *  4. Fija precio_unitario default = 2.50 (precio fijo del proveedor)
 */
return new class extends Migration
{
    public function up(): void
    {
        // Añadir empresa_id
        DB::statement("ALTER TABLE productos_barra ADD COLUMN empresa_id INT UNSIGNED NOT NULL DEFAULT 0 AFTER id");

        // Eliminar FK de evento_id para poder hacerlo nullable
        try {
            DB::statement("ALTER TABLE productos_barra DROP FOREIGN KEY productos_barra_evento_id_foreign");
        } catch (\Throwable $e) {
            // La FK puede tener otro nombre en distintos entornos; continuamos
        }

        // Hacer evento_id nullable (ya no es obligatorio)
        DB::statement("ALTER TABLE productos_barra MODIFY evento_id INT UNSIGNED NULL DEFAULT NULL");

        // Fijar precio_unitario = 2.50 por defecto (precio fijo al proveedor)
        DB::statement("ALTER TABLE productos_barra MODIFY precio_unitario DECIMAL(8,2) NOT NULL DEFAULT 2.50");

        // Migrar empresa_id desde los datos existentes (evento → organizador → empresa)
        DB::statement("
            UPDATE productos_barra pb
            INNER JOIN eventos e       ON pb.evento_id = e.id
            INNER JOIN organizadores o ON e.organizador_id = o.id
            SET pb.empresa_id = o.empresa_id
            WHERE pb.empresa_id = 0
        ");

        // Actualizar precio_unitario a 2.50 en registros que lo tenían en 0
        DB::statement("UPDATE productos_barra SET precio_unitario = 2.50 WHERE precio_unitario = 0");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE productos_barra DROP COLUMN empresa_id");
        DB::statement("ALTER TABLE productos_barra MODIFY evento_id INT UNSIGNED NOT NULL");
        DB::statement("ALTER TABLE productos_barra ADD CONSTRAINT productos_barra_evento_id_foreign FOREIGN KEY (evento_id) REFERENCES eventos(id)");
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Hace nullable la columna password_hash en la tabla usuarios.
 *
 * Por qué es necesario:
 *  Los usuarios que se registran con Google (o cualquier proveedor OAuth) no tienen
 *  contraseña propia en VIBEZ. Anteriormente se guardaba un UUID aleatorio como
 *  placeholder para cumplir con el NOT NULL del campo. Con este cambio, la columna
 *  acepta NULL directamente, lo que deja claro en el schema que el usuario no tiene
 *  contraseña y evita generar datos ficticios innecesarios.
 *
 *  Seguridad: si password_hash es NULL, Auth::attempt() con cualquier contraseña
 *  falla porque Hash::check($input, null) devuelve false. Los usuarios de Google
 *  solo pueden iniciar sesión via OAuth.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // nullable()->change() convierte la columna de NOT NULL a NULL.
            $table->string('password_hash')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Al revertir: vuelve a NOT NULL. Requiere que todas las filas
            // con NULL hayan sido actualizadas antes, o la BD rechazará el cambio.
            $table->string('password_hash')->nullable(false)->change();
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Añade campos fiscales y bancarios a la tabla empresas.
 *
 * Los nuevos campos permiten:
 *  - Identificar el tipo de promotor/empresa (tipo_promotor, tipo_empresa)
 *  - Completar la dirección fiscal (ciudad, codigo_postal, provincia, pais)
 *  - Gestionar pagos (email_facturacion, iban cifrado, titular_cuenta)
 *  - Controlar si el perfil fiscal está completo antes de publicar eventos
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            // Tipo de actividad del promotor
            $table->string('tipo_promotor', 50)->nullable()->after('descripcion');
            // Forma jurídica de la empresa
            $table->string('tipo_empresa', 50)->nullable()->after('tipo_promotor');

            // Dirección fiscal completa
            $table->string('ciudad', 100)->nullable()->after('direccion');
            $table->string('codigo_postal', 10)->nullable()->after('ciudad');
            $table->string('provincia', 100)->nullable()->after('codigo_postal');
            $table->string('pais', 100)->nullable()->default('España')->after('provincia');

            // Datos de facturación
            $table->string('email_facturacion', 200)->nullable()->after('pais');

            // Datos bancarios (IBAN cifrado con encrypt() de Laravel)
            $table->string('iban', 500)->nullable()->after('email_facturacion');
            $table->string('titular_cuenta', 200)->nullable()->after('iban');

            // Flag: true cuando el promotor ha completado todos los datos fiscales
            $table->boolean('perfil_fiscal_completo')->default(false)->after('titular_cuenta');
        });
    }

    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_promotor',
                'tipo_empresa',
                'ciudad',
                'codigo_postal',
                'provincia',
                'pais',
                'email_facturacion',
                'iban',
                'titular_cuenta',
                'perfil_fiscal_completo',
            ]);
        });
    }
};

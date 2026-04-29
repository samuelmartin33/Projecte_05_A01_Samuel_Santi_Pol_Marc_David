<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidaturas_trabajo', function (Blueprint $table) {
            // Allow anonymous applications (no trabajador account required)
            $table->unsignedInteger('trabajador_id')->nullable()->change();

            // Candidate personal data (stored directly for anonymous applicants)
            $table->string('nombre_candidato',    100)->nullable()->after('trabajador_id');
            $table->string('apellidos_candidato', 100)->nullable()->after('nombre_candidato');
            $table->string('email_candidato',     200)->nullable()->after('apellidos_candidato');
            $table->string('telefono_candidato',   30)->nullable()->after('email_candidato');
            $table->string('ciudad_candidato',    100)->nullable()->after('telefono_candidato');
            $table->string('linkedin_candidato',  500)->nullable()->after('ciudad_candidato');
            $table->text('perfil_profesional')        ->nullable()->after('linkedin_candidato');
            $table->string('habilidades',        1000)->nullable()->after('perfil_profesional');
            $table->string('idiomas',             500)->nullable()->after('habilidades');
        });
    }

    public function down(): void
    {
        Schema::table('candidaturas_trabajo', function (Blueprint $table) {
            $table->dropColumn([
                'nombre_candidato', 'apellidos_candidato', 'email_candidato',
                'telefono_candidato', 'ciudad_candidato', 'linkedin_candidato',
                'perfil_profesional', 'habilidades', 'idiomas',
            ]);
            $table->unsignedInteger('trabajador_id')->nullable(false)->change();
        });
    }
};
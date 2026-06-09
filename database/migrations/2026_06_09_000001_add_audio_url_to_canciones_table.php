<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('canciones', function (Blueprint $table) {
            // URL del archivo de audio subido por el admin (mp3, wav, etc.)
            $table->string('audio_url', 500)->nullable()->after('activa');
        });
    }

    public function down(): void
    {
        Schema::table('canciones', function (Blueprint $table) {
            $table->dropColumn('audio_url');
        });
    }
};

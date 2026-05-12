<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('evento_posts', function (Blueprint $table) {
            /* 1 = todos, 2 = solo amigos */
            $table->tinyInteger('visibilidad')->default(1)->after('estado');
        });
    }

    public function down(): void
    {
        Schema::table('evento_posts', function (Blueprint $table) {
            $table->dropColumn('visibilidad');
        });
    }
};

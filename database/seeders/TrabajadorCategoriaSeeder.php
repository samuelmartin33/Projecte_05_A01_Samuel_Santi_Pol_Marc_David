<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrabajadorCategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        // María (trabajador_id=1) tiene categorías: Fotógrafa (5) y Videógrafa (6)
        DB::table('trabajador_categoria')->insert([
            ['trabajador_id' => 1, 'categoria_trabajo_id' => 5, 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['trabajador_id' => 1, 'categoria_trabajo_id' => 6, 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
        ]);
    }
}

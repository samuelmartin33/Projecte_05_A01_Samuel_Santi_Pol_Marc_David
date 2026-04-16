<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventosFavoritosSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        // Pablo (usuario_id=5) marca como favorito el festival (evento_id=1)
        DB::table('eventos_favoritos')->insert([
            ['usuario_id' => 5, 'evento_id' => 1, 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['usuario_id' => 5, 'evento_id' => 2, 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
        ]);
    }
}

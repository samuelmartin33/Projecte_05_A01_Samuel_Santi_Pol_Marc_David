<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChatsSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        DB::table('chats')->insert([
            // Chat de evento (tipo_chat=1) vinculado al festival
            [
                'tipo_chat'           => 1,
                'evento_id'           => 1,
                'candidatura_id'      => null,
                'nombre'              => 'Chat Vibez Summer Festival 2026',
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],
            // Chat de candidatura (tipo_chat=2) sin FK a evento
            [
                'tipo_chat'           => 2,
                'evento_id'           => null,
                'candidatura_id'      => 1,
                'nombre'              => 'Chat candidatura fotógrafa',
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],
        ]);
    }
}

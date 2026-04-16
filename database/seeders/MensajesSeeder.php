<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MensajesSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        DB::table('mensajes')->insert([
            [
                'chat_id'             => 1,
                'usuario_id'          => 5,
                'contenido'           => '¡Estoy muy emocionado por el festival! ¿Habrá zona de descanso?',
                'leido'               => 1,
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],
            [
                'chat_id'             => 1,
                'usuario_id'          => 3,
                'contenido'           => '¡Hola Pablo! Sí, habrá zona chill-out con servicios completos. ¡Nos vemos allí!',
                'leido'               => 0,
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],
            [
                'chat_id'             => 2,
                'usuario_id'          => 3,
                'contenido'           => 'María, hemos revisado tu candidatura. ¿Podrías enviarnos algunos ejemplos de tu trabajo?',
                'leido'               => 0,
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],
        ]);
    }
}

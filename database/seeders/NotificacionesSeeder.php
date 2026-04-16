<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificacionesSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        DB::table('notificaciones')->insert([
            [
                'usuario_id'          => 5,
                'tipo_notificacion'   => 2,
                'titulo'              => 'Pago confirmado',
                'mensaje'             => 'Tu pago de 45,00 € para el Vibez Summer Festival 2026 ha sido procesado correctamente.',
                'url_accion'          => '/mis-entradas',
                'leida'               => 0,
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],
            [
                'usuario_id'          => 5,
                'tipo_notificacion'   => 1,
                'titulo'              => 'Evento próximo',
                'mensaje'             => 'El Vibez Summer Festival 2026 comienza en menos de 7 días. ¡Prepárate!',
                'url_accion'          => '/eventos/1',
                'leida'               => 0,
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],
            [
                'usuario_id'          => 4,
                'tipo_notificacion'   => 4,
                'titulo'              => 'Nueva candidatura recibida',
                'mensaje'             => 'Has recibido un mensaje del organizador sobre tu candidatura de fotógrafa.',
                'url_accion'          => '/mis-candidaturas/1',
                'leida'               => 0,
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],
        ]);
    }
}

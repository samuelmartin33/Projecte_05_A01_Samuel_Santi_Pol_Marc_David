<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Siembra mensajes para todos los chats: evento, candidatura y DMs entre amigos.
 */
class MensajesSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        DB::table('mensajes')->insert([

            /* ── Chat evento (chat_id=1): Pablo ↔ Carlos ──────────── */
            [
                'chat_id'             => 1,
                'usuario_id'          => 5,
                'contenido'           => '¡Estoy muy emocionado por el festival! ¿Habrá zona de descanso?',
                'leido'               => 1,
                'estado'              => 1,
                'fecha_creacion'      => $ahora->copy()->subMinutes(30),
                'fecha_actualizacion' => null,
            ],
            [
                'chat_id'             => 1,
                'usuario_id'          => 3,
                'contenido'           => '¡Hola Pablo! Sí, habrá zona chill-out con servicios completos. ¡Nos vemos allí!',
                'leido'               => 1,
                'estado'              => 1,
                'fecha_creacion'      => $ahora->copy()->subMinutes(25),
                'fecha_actualizacion' => null,
            ],
            [
                'chat_id'             => 1,
                'usuario_id'          => 6,
                'contenido'           => 'Yo también voy! ¿A qué hora empieza el escenario principal?',
                'leido'               => 0,
                'estado'              => 1,
                'fecha_creacion'      => $ahora->copy()->subMinutes(10),
                'fecha_actualizacion' => null,
            ],

            /* ── Chat candidatura (chat_id=2): Carlos ↔ María ────── */
            [
                'chat_id'             => 2,
                'usuario_id'          => 3,
                'contenido'           => 'María, hemos revisado tu candidatura. ¿Podrías enviarnos algunos ejemplos de tu trabajo?',
                'leido'               => 1,
                'estado'              => 1,
                'fecha_creacion'      => $ahora->copy()->subHours(2),
                'fecha_actualizacion' => null,
            ],
            [
                'chat_id'             => 2,
                'usuario_id'          => 4,
                'contenido'           => '¡Claro! Te mando el link a mi portfolio ahora mismo. Tengo fotos de los últimos tres festivales.',
                'leido'               => 0,
                'estado'              => 1,
                'fecha_creacion'      => $ahora->copy()->subHours(1),
                'fecha_actualizacion' => null,
            ],

            /* ── DM María(4) ↔ Pablo(5) (chat_id=3) ─────────────── */
            [
                'chat_id'             => 3,
                'usuario_id'          => 4,
                'contenido'           => 'Pablo, ¿ya tienes entrada para el Summer Festival?',
                'leido'               => 1,
                'estado'              => 1,
                'fecha_creacion'      => $ahora->copy()->subHours(5),
                'fecha_actualizacion' => null,
            ],
            [
                'chat_id'             => 3,
                'usuario_id'          => 5,
                'contenido'           => 'Sí! La compré la semana pasada. ¿Tú también vas?',
                'leido'               => 1,
                'estado'              => 1,
                'fecha_creacion'      => $ahora->copy()->subHours(4),
                'fecha_actualizacion' => null,
            ],
            [
                'chat_id'             => 3,
                'usuario_id'          => 4,
                'contenido'           => 'Claro, voy a fotografiar el evento. Si quieres te busco y te enseño mis fotos en directo 📸',
                'leido'               => 0,
                'estado'              => 1,
                'fecha_creacion'      => $ahora->copy()->subHours(3),
                'fecha_actualizacion' => null,
            ],

            /* ── DM Carlos(3) ↔ Pablo(5) (chat_id=4) ───────────── */
            [
                'chat_id'             => 4,
                'usuario_id'          => 3,
                'contenido'           => 'Hola Pablo, ¿pudiste ver el horario de actuaciones que publiqué?',
                'leido'               => 1,
                'estado'              => 1,
                'fecha_creacion'      => $ahora->copy()->subHours(8),
                'fecha_actualizacion' => null,
            ],
            [
                'chat_id'             => 4,
                'usuario_id'          => 5,
                'contenido'           => 'Sí! El set de las 2am tiene una pinta brutal. Ya estoy en modo festival 🔥',
                'leido'               => 1,
                'estado'              => 1,
                'fecha_creacion'      => $ahora->copy()->subHours(7),
                'fecha_actualizacion' => null,
            ],
            [
                'chat_id'             => 4,
                'usuario_id'          => 3,
                'contenido'           => 'Genial! Pásate por el backstage cuando llegues y te doy una pulsera VIP.',
                'leido'               => 0,
                'estado'              => 1,
                'fecha_creacion'      => $ahora->copy()->subHours(6),
                'fecha_actualizacion' => null,
            ],

            /* ── DM Ana(6) ↔ Pablo(5) (chat_id=5) ───────────────── */
            [
                'chat_id'             => 5,
                'usuario_id'          => 6,
                'contenido'           => 'Oye, ¿vas al Noche Latina de junio?',
                'leido'               => 1,
                'estado'              => 1,
                'fecha_creacion'      => $ahora->copy()->subMinutes(90),
                'fecha_actualizacion' => null,
            ],
            [
                'chat_id'             => 5,
                'usuario_id'          => 5,
                'contenido'           => 'Todavía no sé, depende de si queda entrada. ¿Ya tienes la tuya?',
                'leido'               => 1,
                'estado'              => 1,
                'fecha_creacion'      => $ahora->copy()->subMinutes(60),
                'fecha_actualizacion' => null,
            ],
            [
                'chat_id'             => 5,
                'usuario_id'          => 6,
                'contenido'           => 'Sí, pillé dos por si acaso 😄 Te aviso si sobra una.',
                'leido'               => 0,
                'estado'              => 1,
                'fecha_creacion'      => $ahora->copy()->subMinutes(45),
                'fecha_actualizacion' => null,
            ],

            /* ── DM María(4) ↔ Ana(6) (chat_id=6) ───────────────── */
            [
                'chat_id'             => 6,
                'usuario_id'          => 4,
                'contenido'           => 'Ana! Vi tus stories del festival. ¿Con qué cámara grabaste?',
                'leido'               => 1,
                'estado'              => 1,
                'fecha_creacion'      => $ahora->copy()->subMinutes(120),
                'fecha_actualizacion' => null,
            ],
            [
                'chat_id'             => 6,
                'usuario_id'          => 6,
                'contenido'           => 'Con la Sony A7IV + el objetivo 24-70. La calidad en baja luz es impresionante ✨',
                'leido'               => 0,
                'estado'              => 1,
                'fecha_creacion'      => $ahora->copy()->subMinutes(100),
                'fecha_actualizacion' => null,
            ],

        ]);
    }
}

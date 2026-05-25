<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Siembra chats existentes: evento (tipo=1), candidatura (tipo=2)
 * y chats directos entre amigos (tipo=1, nombre dm_{idMenor}_{idMayor}).
 */
class ChatsSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        DB::table('chats')->insert([

            /* ── Chat grupal de evento (id=1) ─────────────────────── */
            [
                'tipo_chat'           => 1,
                'evento_id'           => 1,
                'candidatura_id'      => null,
                'nombre'              => 'Chat Vibez Summer Festival 2026',
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],

            /* ── Chat de candidatura (id=2) ────────────────────────── */
            [
                'tipo_chat'           => 2,
                'evento_id'           => null,
                'candidatura_id'      => 1,
                'nombre'              => 'Chat candidatura fotógrafa',
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],

            /* ── Chat directo: María(4) ↔ Pablo(5) (id=3) ───────────
               Nombre sigue la convención: dm_{idMenor}_{idMayor}     */
            [
                'tipo_chat'           => 1,
                'evento_id'           => null,
                'candidatura_id'      => null,
                'nombre'              => 'dm_4_5',
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],

            /* ── Chat directo: Carlos(3) ↔ Pablo(5) (id=4) ──────── */
            [
                'tipo_chat'           => 1,
                'evento_id'           => null,
                'candidatura_id'      => null,
                'nombre'              => 'dm_3_5',
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],

            /* ── Chat directo: Ana(6) ↔ Pablo(5) (id=5) ──────────── */
            [
                'tipo_chat'           => 1,
                'evento_id'           => null,
                'candidatura_id'      => null,
                'nombre'              => 'dm_5_6',
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],

            /* ── Chat directo: María(4) ↔ Ana(6) (id=6) ──────────── */
            [
                'tipo_chat'           => 1,
                'evento_id'           => null,
                'candidatura_id'      => null,
                'nombre'              => 'dm_4_6',
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],

        ]);
    }
}

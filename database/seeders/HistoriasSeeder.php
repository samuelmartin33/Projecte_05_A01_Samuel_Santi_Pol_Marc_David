<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Siembra historias de 24h para varios usuarios.
 * Algunas están activas (expiran en el futuro) y otras ya caducadas.
 * También registra algunas vistas en historia_vistas.
 */
class HistoriasSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        $usuarios = DB::table('usuarios')->where('estado', 1)->orderBy('id')->pluck('id')->toArray();
        $eventos  = DB::table('eventos')->where('estado', 1)->orderBy('id')->limit(2)->pluck('id')->toArray();

        if (count($usuarios) < 3) {
            $this->command->warn('Faltan usuarios para las historias.');
            return;
        }

        // IDs de usuario por posición para referencias claras
        $u  = $usuarios;
        $e0 = isset($eventos[0]) ? $eventos[0] : null;
        $e1 = isset($eventos[1]) ? $eventos[1] : null;

        $historias = [

            /* ── Ana(6): historia activa con evento etiquetado ─── */
            [
                'usuario_id'          => isset($u[5]) ? $u[5] : $u[0],
                'media_url'           => 'https://picsum.photos/seed/hist1/600/1066',
                'texto'               => '¡Que noche más mágica! ✨',
                'evento_id'           => $e0,
                'expira_en'           => $ahora->copy()->addHours(20),
                'vistas'              => 3,
                'estado'              => 1,
                'fecha_creacion'      => $ahora->copy()->subHours(4),
                'fecha_actualizacion' => null,
            ],

            /* ── Pablo(5): historia activa sin evento ─────────── */
            [
                'usuario_id'          => isset($u[4]) ? $u[4] : $u[1],
                'media_url'           => 'https://picsum.photos/seed/hist2/600/1066',
                'texto'               => 'Preparando el look para esta noche 🎶',
                'evento_id'           => null,
                'expira_en'           => $ahora->copy()->addHours(18),
                'vistas'              => 1,
                'estado'              => 1,
                'fecha_creacion'      => $ahora->copy()->subHours(6),
                'fecha_actualizacion' => null,
            ],

            /* ── María(4): historia activa con evento ─────────── */
            [
                'usuario_id'          => isset($u[3]) ? $u[3] : $u[0],
                'media_url'           => 'https://picsum.photos/seed/hist3/600/1066',
                'texto'               => 'Capturando cada instante 📸',
                'evento_id'           => $e0,
                'expira_en'           => $ahora->copy()->addHours(15),
                'vistas'              => 5,
                'estado'              => 1,
                'fecha_creacion'      => $ahora->copy()->subHours(9),
                'fecha_actualizacion' => null,
            ],

            /* ── Diego(7): historia activa reciente ─────────── */
            [
                'usuario_id'          => isset($u[6]) ? $u[6] : $u[1],
                'media_url'           => 'https://picsum.photos/seed/hist4/600/1066',
                'texto'               => null,
                'evento_id'           => $e1,
                'expira_en'           => $ahora->copy()->addHours(22),
                'vistas'              => 0,
                'estado'              => 1,
                'fecha_creacion'      => $ahora->copy()->subHours(2),
                'fecha_actualizacion' => null,
            ],

            /* ── Sofía(8): historia activa con texto ─────────── */
            [
                'usuario_id'          => isset($u[7]) ? $u[7] : $u[2],
                'media_url'           => 'https://picsum.photos/seed/hist5/600/1066',
                'texto'               => 'El backstage también tiene su magia 🎬',
                'evento_id'           => $e0,
                'expira_en'           => $ahora->copy()->addHours(12),
                'vistas'              => 8,
                'estado'              => 1,
                'fecha_creacion'      => $ahora->copy()->subHours(12),
                'fecha_actualizacion' => null,
            ],

            /* ── Carlos(3): historia ya caducada (para pruebas) ─ */
            [
                'usuario_id'          => isset($u[2]) ? $u[2] : $u[0],
                'media_url'           => 'https://picsum.photos/seed/hist6/600/1066',
                'texto'               => 'El montaje del escenario empieza aquí 🏗️',
                'evento_id'           => $e0,
                'expira_en'           => $ahora->copy()->subHours(2),
                'vistas'              => 12,
                'estado'              => 1,
                'fecha_creacion'      => $ahora->copy()->subHours(26),
                'fecha_actualizacion' => null,
            ],

        ];

        $histIds = [];
        foreach ($historias as $historia) {
            $histIds[] = DB::table('historias')->insertGetId($historia);
        }

        /*
         * Registra algunas vistas: varios usuarios ya han visto las historias activas.
         * historia_vistas tiene unique(historia_id, usuario_id), así que no se repiten.
         */
        $vistas = [
            // Historia de Ana (id=0): Pablo y María la han visto
            ['historia_id' => $histIds[0], 'usuario_id' => isset($u[4]) ? $u[4] : $u[1], 'fecha_vista' => $ahora->copy()->subHours(3)],
            ['historia_id' => $histIds[0], 'usuario_id' => isset($u[3]) ? $u[3] : $u[2], 'fecha_vista' => $ahora->copy()->subHours(2)],
            ['historia_id' => $histIds[0], 'usuario_id' => isset($u[6]) ? $u[6] : $u[3], 'fecha_vista' => $ahora->copy()->subHours(1)],

            // Historia de Pablo (id=1): Ana la ha visto
            ['historia_id' => $histIds[1], 'usuario_id' => isset($u[5]) ? $u[5] : $u[0], 'fecha_vista' => $ahora->copy()->subHours(5)],

            // Historia de María (id=2): Pablo, Ana y Diego la han visto
            ['historia_id' => $histIds[2], 'usuario_id' => isset($u[4]) ? $u[4] : $u[1], 'fecha_vista' => $ahora->copy()->subHours(8)],
            ['historia_id' => $histIds[2], 'usuario_id' => isset($u[5]) ? $u[5] : $u[0], 'fecha_vista' => $ahora->copy()->subHours(7)],
            ['historia_id' => $histIds[2], 'usuario_id' => isset($u[6]) ? $u[6] : $u[3], 'fecha_vista' => $ahora->copy()->subHours(6)],
            ['historia_id' => $histIds[2], 'usuario_id' => isset($u[7]) ? $u[7] : $u[2], 'fecha_vista' => $ahora->copy()->subHours(5)],
            ['historia_id' => $histIds[2], 'usuario_id' => isset($u[3]) ? $u[3] : $u[4], 'fecha_vista' => $ahora->copy()->subHours(4)],

            // Historia de Sofía (id=4): múltiples vistas
            ['historia_id' => $histIds[4], 'usuario_id' => isset($u[4]) ? $u[4] : $u[1], 'fecha_vista' => $ahora->copy()->subHours(11)],
            ['historia_id' => $histIds[4], 'usuario_id' => isset($u[5]) ? $u[5] : $u[0], 'fecha_vista' => $ahora->copy()->subHours(10)],
            ['historia_id' => $histIds[4], 'usuario_id' => isset($u[3]) ? $u[3] : $u[2], 'fecha_vista' => $ahora->copy()->subHours(9)],
            ['historia_id' => $histIds[4], 'usuario_id' => isset($u[6]) ? $u[6] : $u[3], 'fecha_vista' => $ahora->copy()->subHours(8)],
            ['historia_id' => $histIds[4], 'usuario_id' => isset($u[2]) ? $u[2] : $u[4], 'fecha_vista' => $ahora->copy()->subHours(7)],
            ['historia_id' => $histIds[4], 'usuario_id' => $u[0],                         'fecha_vista' => $ahora->copy()->subHours(6)],
            ['historia_id' => $histIds[4], 'usuario_id' => isset($u[1]) ? $u[1] : $u[0],  'fecha_vista' => $ahora->copy()->subHours(5)],
            ['historia_id' => $histIds[4], 'usuario_id' => isset($u[7]) ? $u[7] : $u[2],  'fecha_vista' => $ahora->copy()->subHours(4)],
            ['historia_id' => $histIds[4], 'usuario_id' => isset($u[9]) ? $u[9] : $u[1],  'fecha_vista' => $ahora->copy()->subHours(3)],
        ];

        // Filtra vistas duplicadas antes de insertar (por si hay colisiones con pocos usuarios)
        $vistasUnicas = [];
        $vistasKey    = [];
        foreach ($vistas as $v) {
            $key = $v['historia_id'] . '_' . $v['usuario_id'];
            if (!isset($vistasKey[$key])) {
                $vistasKey[$key] = true;
                $vistasUnicas[]  = $v;
            }
        }

        DB::table('historia_vistas')->insert($vistasUnicas);

        $this->command->info('6 historias creadas con sus vistas registradas.');
    }
}

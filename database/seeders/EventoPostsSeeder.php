<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventoPostsSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        $usuarios = DB::table('usuarios')->where('estado', 1)->orderBy('id')->limit(5)->pluck('id')->toArray();
        $eventos  = DB::table('eventos')->where('estado', 1)->orderBy('id')->limit(3)->pluck('id')->toArray();

        if (count($usuarios) < 2 || count($eventos) < 1) {
            $this->command->warn('Faltan usuarios o eventos. Ejecuta primero UsuariosSeeder y EventosSeeder.');
            return;
        }

        // Ensure each of the first 5 users has an attended entrada (estado_entrada=2)
        $asistencias = [];
        foreach (array_slice($usuarios, 0, 5) as $i => $usuarioId) {
            $eventoId = $eventos[$i % count($eventos)];
            $asistencias[$usuarioId] = $eventoId;

            // Find or create a pedido for this user
            $pedidoId = DB::table('pedidos')->where('usuario_id', $usuarioId)->value('id');

            if (!$pedidoId) {
                $pedidoId = DB::table('pedidos')->insertGetId([
                    'usuario_id'          => $usuarioId,
                    'total'               => 0.00,
                    'total_descuento'     => 0.00,
                    'total_final'         => 0.00,
                    'estado'              => 1,
                    'fecha_creacion'      => $ahora,
                    'fecha_actualizacion' => null,
                ]);
            }

            // Add attended entrada if one doesn't already exist
            $existeAsistida = DB::table('entradas')
                ->where('pedido_id', $pedidoId)
                ->where('evento_id', $eventoId)
                ->where('estado_entrada', 2)
                ->exists();

            if (!$existeAsistida) {
                DB::table('entradas')->insert([
                    'pedido_id'           => $pedidoId,
                    'evento_id'           => $eventoId,
                    'estado_entrada'      => 2,
                    'codigo_qr'           => strtoupper(Str::random(20)),
                    'precio_unitario'     => 0.00,
                    'precio_pagado'       => 0.00,
                    'fecha_uso'           => $ahora,
                    'estado'              => 1,
                    'fecha_creacion'      => $ahora,
                    'fecha_actualizacion' => null,
                ]);
            }
        }

        $u = $usuarios;

        $posts = [
            [
                'usuario_id'  => $u[0],
                'evento_id'   => $asistencias[$u[0]],
                'descripcion' => '¡Qué noche tan increíble! El ambiente era indescriptible, la música te metía dentro del corazón. Ya estoy contando los días para el próximo 🔥',
                'imagenes'    => [
                    'https://picsum.photos/seed/vibez1a/800/600',
                    'https://picsum.photos/seed/vibez1b/800/600',
                ],
                'comentarios' => [
                    ['usuario_id' => $u[1], 'contenido' => '¡Yo también estuve! Una pasada total, el escenario principal estaba brutal.'],
                    ['usuario_id' => $u[2], 'contenido' => 'Qué envidia me dais… la próxima vez voy seguro 👏'],
                    ['usuario_id' => $u[3], 'contenido' => 'Las fotos no le hacen justicia, en directo era otro nivel.'],
                ],
            ],
            [
                'usuario_id'  => $u[1],
                'evento_id'   => $asistencias[$u[1]],
                'descripcion' => 'Tres horas bailando sin parar. Los DJs de la noche estuvieron a otro nivel, el drop del segundo set hizo enloquecer a todo el recinto.',
                'imagenes'    => [
                    'https://picsum.photos/seed/vibez2a/800/600',
                ],
                'comentarios' => [
                    ['usuario_id' => $u[0], 'contenido' => '¡Ese drop fue brutal! Me dejó sin palabras.'],
                    ['usuario_id' => $u[4], 'contenido' => 'Yo estaba justo al lado del escenario, la vibración del bajo se notaba en el pecho 😂'],
                ],
            ],
            [
                'usuario_id'  => $u[2],
                'evento_id'   => $asistencias[$u[2]],
                'descripcion' => 'Un festival donde el arte y la música se fusionan de manera perfecta. Me fui con la pila cargada hasta arriba, necesito más de esto.',
                'imagenes'    => [
                    'https://picsum.photos/seed/vibez3a/800/600',
                    'https://picsum.photos/seed/vibez3b/800/600',
                    'https://picsum.photos/seed/vibez3c/800/600',
                ],
                'comentarios' => [
                    ['usuario_id' => $u[1], 'contenido' => '¡Esa foto del atardecer con el escenario de fondo es preciosa!'],
                    ['usuario_id' => $u[3], 'contenido' => 'La instalación de luces era increíble, parecía que estabas dentro de otra dimensión.'],
                    ['usuario_id' => $u[0], 'contenido' => 'Vibez siempre cuida tanto los detalles visuales… 10/10.'],
                    ['usuario_id' => $u[4], 'contenido' => '¿Sabes si habrá otro evento así pronto? Lo necesito.'],
                ],
            ],
            [
                'usuario_id'  => $u[3],
                'evento_id'   => $asistencias[$u[3]],
                'descripcion' => 'Primera vez que vengo y me ha enamorado. Gente súper buena, ambiente increíble y una organización impecable. Volvería mil veces.',
                'imagenes'    => [
                    'https://picsum.photos/seed/vibez4a/800/600',
                    'https://picsum.photos/seed/vibez4b/800/600',
                ],
                'comentarios' => [
                    ['usuario_id' => $u[2], 'contenido' => '¡Bienvenida al club! Ya verás que siempre se supera.'],
                    ['usuario_id' => $u[0], 'contenido' => '¡Primera de muchas! 🎉'],
                ],
            ],
            [
                'usuario_id'  => $u[4],
                'evento_id'   => $asistencias[$u[4]],
                'descripcion' => 'La actuación acústica de medianoche fue lo más especial que he vivido. Silencio absoluto en el público mientras sonaban esas notas… piel de gallina.',
                'imagenes'    => [
                    'https://picsum.photos/seed/vibez5a/800/600',
                    'https://picsum.photos/seed/vibez5b/800/600',
                    'https://picsum.photos/seed/vibez5c/800/600',
                    'https://picsum.photos/seed/vibez5d/800/600',
                ],
                'comentarios' => [
                    ['usuario_id' => $u[1], 'contenido' => 'Me quedé sin palabras. Nunca había sentido algo así en un concierto.'],
                    ['usuario_id' => $u[2], 'contenido' => 'Ese momento con las luces apagadas y solo la guitarra… goosebumps 🙌'],
                    ['usuario_id' => $u[3], 'contenido' => '100% de acuerdo, fue el punto más alto de la noche.'],
                ],
            ],
        ];

        foreach ($posts as $postData) {
            $postId = DB::table('evento_posts')->insertGetId([
                'usuario_id'          => $postData['usuario_id'],
                'evento_id'           => $postData['evento_id'],
                'descripcion'         => $postData['descripcion'],
                'estado'              => 1,
                'fecha_creacion'      => $ahora->subMinutes(rand(5, 1440)),
                'fecha_actualizacion' => $ahora,
            ]);

            foreach ($postData['imagenes'] as $orden => $url) {
                DB::table('evento_post_imagenes')->insert([
                    'evento_post_id'      => $postId,
                    'imagen_url'          => $url,
                    'orden'               => $orden,
                    'estado'              => 1,
                    'fecha_creacion'      => $ahora,
                    'fecha_actualizacion' => $ahora,
                ]);
            }

            foreach ($postData['comentarios'] as $comentario) {
                DB::table('evento_post_comentarios')->insert([
                    'evento_post_id'      => $postId,
                    'usuario_id'          => $comentario['usuario_id'],
                    'contenido'           => $comentario['contenido'],
                    'estado'              => 1,
                    'fecha_creacion'      => $ahora,
                    'fecha_actualizacion' => $ahora,
                ]);
            }
        }

        $this->command->info('5 publicaciones de eventos creadas correctamente.');
    }
}

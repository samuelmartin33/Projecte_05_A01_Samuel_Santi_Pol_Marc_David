<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Siembra publicaciones sociales asociadas a eventos, con imágenes, comentarios
 * y respuestas anidadas (padre_id). También crea las entradas necesarias para
 * que los usuarios cumplan el requisito de asistencia al publicar.
 */
class EventoPostsSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        // Carga todos los usuarios activos y los primeros 3 eventos
        $usuarios = DB::table('usuarios')->where('estado', 1)->orderBy('id')->pluck('id')->toArray();
        $eventos  = DB::table('eventos')->where('estado', 1)->orderBy('id')->limit(3)->pluck('id')->toArray();

        if (count($usuarios) < 2 || count($eventos) < 1) {
            $this->command->warn('Faltan usuarios o eventos. Ejecuta primero UsuariosSeeder y EventosSeeder.');
            return;
        }

        // Asigna un evento asistido a cada usuario (rotando si hay menos eventos que usuarios)
        $asistencias = [];
        foreach ($usuarios as $i => $usuarioId) {
            $eventoId               = $eventos[$i % count($eventos)];
            $asistencias[$usuarioId] = $eventoId;

            // Busca o crea un pedido para el usuario
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

            // Añade entrada de asistencia si aún no existe
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

        /*
         * Definición de publicaciones.
         * Cada post puede tener comentarios raíz y, dentro de cada comentario,
         * un array 'respuestas' que se insertará con padre_id apuntando al comentario padre.
         */
        $posts = [

            /* ─── Post 1: Admin/Pablo sobre el festival ──────────── */
            [
                'usuario_id'  => $u[0],
                'evento_id'   => $asistencias[$u[0]],
                'descripcion' => '¡Qué noche tan increíble! El ambiente era indescriptible, la música te metía dentro del corazón. Ya estoy contando los días para el próximo 🔥',
                'imagenes'    => [
                    'https://picsum.photos/seed/vibez1a/800/600',
                    'https://picsum.photos/seed/vibez1b/800/600',
                ],
                'comentarios' => [
                    [
                        'usuario_id' => $u[1],
                        'contenido'  => '¡Yo también estuve! Una pasada total, el escenario principal estaba brutal.',
                        'respuestas' => [
                            ['usuario_id' => $u[0], 'contenido' => 'Verdad? Ese momento con las luces fue épico 🙌'],
                            ['usuario_id' => $u[4], 'contenido' => 'Qué envidia, ojalá hubiese podido ir!'],
                        ],
                    ],
                    [
                        'usuario_id' => $u[2],
                        'contenido'  => 'Qué envidia me dais… la próxima vez voy seguro 👏',
                        'respuestas' => [],
                    ],
                    [
                        'usuario_id' => $u[3],
                        'contenido'  => 'Las fotos no le hacen justicia, en directo era otro nivel.',
                        'respuestas' => [
                            ['usuario_id' => $u[1], 'contenido' => 'Totalmente de acuerdo, falta el sonido!'],
                        ],
                    ],
                ],
            ],

            /* ─── Post 2: Laura sobre los DJs ───────────────────── */
            [
                'usuario_id'  => $u[1],
                'evento_id'   => $asistencias[$u[1]],
                'descripcion' => 'Tres horas bailando sin parar. Los DJs de la noche estuvieron a otro nivel, el drop del segundo set hizo enloquecer a todo el recinto.',
                'imagenes'    => [
                    'https://picsum.photos/seed/vibez2a/800/600',
                ],
                'comentarios' => [
                    [
                        'usuario_id' => $u[0],
                        'contenido'  => '¡Ese drop fue brutal! Me dejó sin palabras.',
                        'respuestas' => [
                            ['usuario_id' => $u[1], 'contenido' => 'Jajaja sí, el público explotó! 😂'],
                        ],
                    ],
                    [
                        'usuario_id' => $u[4],
                        'contenido'  => 'Yo estaba justo al lado del escenario, la vibración del bajo se notaba en el pecho 😂',
                        'respuestas' => [],
                    ],
                ],
            ],

            /* ─── Post 3: Carlos sobre arte y música ─────────────── */
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
                    [
                        'usuario_id' => $u[1],
                        'contenido'  => '¡Esa foto del atardecer con el escenario de fondo es preciosa!',
                        'respuestas' => [
                            ['usuario_id' => $u[2], 'contenido' => 'Gracias! Fue justo en el momento mágico ✨'],
                            ['usuario_id' => $u[5], 'contenido' => 'Qué encuadre tan bonito, la usaré de inspo!'],
                        ],
                    ],
                    [
                        'usuario_id' => $u[3],
                        'contenido'  => 'La instalación de luces era increíble, parecía que estabas dentro de otra dimensión.',
                        'respuestas' => [],
                    ],
                    [
                        'usuario_id' => $u[0],
                        'contenido'  => 'Vibez siempre cuida tanto los detalles visuales… 10/10.',
                        'respuestas' => [],
                    ],
                    [
                        'usuario_id' => $u[4],
                        'contenido'  => '¿Sabes si habrá otro evento así pronto? Lo necesito.',
                        'respuestas' => [
                            ['usuario_id' => $u[2], 'contenido' => 'Sí! En otoño anunciamos algo parecido. Estate atento 👀'],
                        ],
                    ],
                ],
            ],

            /* ─── Post 4: María sobre su primera vez ─────────────── */
            [
                'usuario_id'  => $u[3],
                'evento_id'   => $asistencias[$u[3]],
                'descripcion' => 'Primera vez que vengo y me ha enamorado. Gente súper buena, ambiente increíble y una organización impecable. Volvería mil veces.',
                'imagenes'    => [
                    'https://picsum.photos/seed/vibez4a/800/600',
                    'https://picsum.photos/seed/vibez4b/800/600',
                ],
                'comentarios' => [
                    [
                        'usuario_id' => $u[2],
                        'contenido'  => '¡Bienvenida al club! Ya verás que siempre se supera.',
                        'respuestas' => [
                            ['usuario_id' => $u[3], 'contenido' => 'Gracias Carlos! La próxima ya vengo preparada 😄'],
                        ],
                    ],
                    [
                        'usuario_id' => $u[0],
                        'contenido'  => '¡Primera de muchas! 🎉',
                        'respuestas' => [],
                    ],
                ],
            ],

            /* ─── Post 5: Pablo sobre la actuación acústica ─────── */
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
                    [
                        'usuario_id' => $u[1],
                        'contenido'  => 'Me quedé sin palabras. Nunca había sentido algo así en un concierto.',
                        'respuestas' => [],
                    ],
                    [
                        'usuario_id' => $u[2],
                        'contenido'  => 'Ese momento con las luces apagadas y solo la guitarra… goosebumps 🙌',
                        'respuestas' => [
                            ['usuario_id' => $u[4], 'contenido' => 'Exacto! Fue de esos momentos que no se olvidan.'],
                            ['usuario_id' => $u[5], 'contenido' => 'Lo grabé en video, quedó increíble!'],
                        ],
                    ],
                    [
                        'usuario_id' => $u[3],
                        'contenido'  => '100% de acuerdo, fue el punto más alto de la noche.',
                        'respuestas' => [],
                    ],
                ],
            ],

            /* ─── Post 6: Ana sobre el evento (sin imagen) ────────── */
            [
                'usuario_id'  => isset($u[5]) ? $u[5] : $u[0],
                'evento_id'   => $asistencias[isset($u[5]) ? $u[5] : $u[0]],
                'descripcion' => 'Primer festival que vengo sola y ha sido la mejor decisión. La gente aquí es increíble, hice amigos en cinco minutos. Esto es Vibez 💜',
                'imagenes'    => [
                    'https://picsum.photos/seed/vibez6a/800/600',
                ],
                'comentarios' => [
                    [
                        'usuario_id' => $u[4],
                        'contenido'  => '¡Así es como hay que vivir los festivales! Me alegra que lo hayas pasado bien.',
                        'respuestas' => [
                            ['usuario_id' => isset($u[5]) ? $u[5] : $u[0], 'contenido' => 'Gracias Pablo! Ya sabes que la próxima vamos juntos 😄'],
                        ],
                    ],
                    [
                        'usuario_id' => $u[3],
                        'contenido'  => 'Los festivales en solitario son una experiencia única, te lo juro.',
                        'respuestas' => [],
                    ],
                ],
            ],

            /* ─── Post 7: Diego — publicación sin evento etiquetado ─ */
            [
                'usuario_id'  => isset($u[6]) ? $u[6] : $u[1],
                'evento_id'   => null,
                'descripcion' => 'Preparando la playlist para el verano. ¿Alguna recomendación de artista que no me pueda perder esta temporada? 🎶',
                'imagenes'    => [],
                'comentarios' => [
                    [
                        'usuario_id' => $u[4],
                        'contenido'  => 'Charlotte de Witte es imprescindible este año!',
                        'respuestas' => [
                            ['usuario_id' => isset($u[6]) ? $u[6] : $u[1], 'contenido' => 'La tengo en la lista! Su set del año pasado fue brutal.'],
                        ],
                    ],
                    [
                        'usuario_id' => $u[2],
                        'contenido'  => 'Pérate, que pronto anunciamos algo que te va a flipar... 👀',
                        'respuestas' => [
                            ['usuario_id' => $u[4], 'contenido' => 'Cuéntaaa!'],
                            ['usuario_id' => isset($u[6]) ? $u[6] : $u[1], 'contenido' => 'Dejando con la intriga... 😂'],
                        ],
                    ],
                ],
            ],

        ];

        foreach ($posts as $postData) {
            // Inserta la publicación
            $postId = DB::table('evento_posts')->insertGetId([
                'usuario_id'          => $postData['usuario_id'],
                'evento_id'           => $postData['evento_id'],
                'descripcion'         => $postData['descripcion'],
                'estado'              => 1,
                'fecha_creacion'      => $ahora->copy()->subMinutes(rand(5, 1440)),
                'fecha_actualizacion' => $ahora,
            ]);

            // Inserta las imágenes del post
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

            // Inserta comentarios raíz y sus respuestas
            foreach ($postData['comentarios'] as $comentario) {
                $comentarioId = DB::table('evento_post_comentarios')->insertGetId([
                    'evento_post_id'      => $postId,
                    'usuario_id'          => $comentario['usuario_id'],
                    'padre_id'            => null,
                    'contenido'           => $comentario['contenido'],
                    'estado'              => 1,
                    'fecha_creacion'      => $ahora->copy()->subMinutes(rand(1, 60)),
                    'fecha_actualizacion' => $ahora,
                ]);

                // Inserta respuestas anidadas con padre_id apuntando al comentario padre
                foreach ($comentario['respuestas'] as $respuesta) {
                    DB::table('evento_post_comentarios')->insert([
                        'evento_post_id'      => $postId,
                        'usuario_id'          => $respuesta['usuario_id'],
                        'padre_id'            => $comentarioId,
                        'contenido'           => $respuesta['contenido'],
                        'estado'              => 1,
                        'fecha_creacion'      => $ahora->copy()->subMinutes(rand(1, 30)),
                        'fecha_actualizacion' => $ahora,
                    ]);
                }
            }
        }

        $this->command->info('7 publicaciones de eventos creadas con comentarios y respuestas.');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BolsaOfertasTrabajoSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        DB::table('bolsa_ofertas_trabajo')->insert([

            /* ── Vinculadas al Festival (evento_id=1) ──────────────── */
            [
                'organizador_id'       => 1,
                'evento_id'            => 1,
                'categoria_trabajo_id' => 5, // Fotógrafo/a
                'titulo'               => 'Fotógrafo/a — Vibez Summer Festival 2026',
                'descripcion'          => 'Cobertura fotográfica completa del festival: escenarios, backstage y asistentes. Imágenes para redes sociales y prensa.',
                'requisitos'           => 'Mínimo 2 años de experiencia en eventos. Equipo propio. Portfolio demostrable.',
                'ubicacion'            => 'Madrid',
                'salario_min'          => 300.00,
                'salario_max'          => 500.00,
                'fecha_inicio_trabajo' => '2026-07-20 16:00:00',
                'fecha_fin_trabajo'    => '2026-07-21 08:00:00',
                'vacantes'             => 3,
                'estado'               => 1,
                'fecha_creacion'       => $ahora,
                'fecha_actualizacion'  => null,
            ],
            [
                'organizador_id'       => 1,
                'evento_id'            => 1,
                'categoria_trabajo_id' => 4, // Seguridad
                'titulo'               => 'Personal de Seguridad — Festival Vibez',
                'descripcion'          => 'Control de acceso, vigilancia de perímetro y gestión de incidencias durante el festival de música electrónica.',
                'requisitos'           => 'Habilitación de seguridad vigente. Buena presencia. Disponibilidad horaria completa.',
                'ubicacion'            => 'Madrid',
                'salario_min'          => 200.00,
                'salario_max'          => 350.00,
                'fecha_inicio_trabajo' => '2026-07-20 14:00:00',
                'fecha_fin_trabajo'    => '2026-07-21 10:00:00',
                'vacantes'             => 10,
                'estado'               => 1,
                'fecha_creacion'       => $ahora,
                'fecha_actualizacion'  => null,
            ],
            [
                'organizador_id'       => 1,
                'evento_id'            => 1,
                'categoria_trabajo_id' => 2, // Técnico de sonido
                'titulo'               => 'Técnico de Sonido — Escenario Principal',
                'descripcion'          => 'Operación y supervisión del sistema de sonido en el escenario principal durante todo el festival. Montaje previo incluido.',
                'requisitos'           => 'Experiencia demostrable en grandes eventos. Conocimientos de ProTools y sistemas DiGiCo.',
                'ubicacion'            => 'Madrid',
                'salario_min'          => 400.00,
                'salario_max'          => 650.00,
                'fecha_inicio_trabajo' => '2026-07-19 09:00:00',
                'fecha_fin_trabajo'    => '2026-07-21 10:00:00',
                'vacantes'             => 4,
                'estado'               => 1,
                'fecha_creacion'       => $ahora,
                'fecha_actualizacion'  => null,
            ],
            [
                'organizador_id'       => 1,
                'evento_id'            => 1,
                'categoria_trabajo_id' => 3, // Técnico de iluminación
                'titulo'               => 'Técnico de Iluminación — Vibez Summer Fest',
                'descripcion'          => 'Programación y operación del show de luces en los cuatro escenarios del festival. Trabajo en equipo con el director técnico.',
                'requisitos'           => 'Experiencia con grandMA2/3. Conocimientos de moving heads y LED. Trabajo en alturas.',
                'ubicacion'            => 'Madrid',
                'salario_min'          => 380.00,
                'salario_max'          => 600.00,
                'fecha_inicio_trabajo' => '2026-07-19 10:00:00',
                'fecha_fin_trabajo'    => '2026-07-21 08:00:00',
                'vacantes'             => 6,
                'estado'               => 1,
                'fecha_creacion'       => $ahora,
                'fecha_actualizacion'  => null,
            ],

            /* ── Ofertas generales (sin evento específico) ──────────── */
            [
                'organizador_id'       => 1,
                'evento_id'            => null,
                'categoria_trabajo_id' => 1, // Camarero/a
                'titulo'               => 'Camarero/a de Barra — Eventos Nocturnos',
                'descripcion'          => 'Atención en barra para eventos de música y fiestas privadas. Turnos nocturnos los fines de semana. Buen ambiente garantizado.',
                'requisitos'           => 'Experiencia en barra. Agilidad y buena presencia. Inglés básico valorado.',
                'ubicacion'            => 'Barcelona',
                'salario_min'          => 80.00,
                'salario_max'          => 130.00,
                'fecha_inicio_trabajo' => null,
                'fecha_fin_trabajo'    => null,
                'vacantes'             => 5,
                'estado'               => 1,
                'fecha_creacion'       => $ahora,
                'fecha_actualizacion'  => null,
            ],
            [
                'organizador_id'       => 1,
                'evento_id'            => null,
                'categoria_trabajo_id' => 6, // Videógrafo/a
                'titulo'               => 'Videógrafo/a — Aftermovies y Reels',
                'descripcion'          => 'Grabación y edición de aftermovies de eventos y contenido para Instagram Reels y TikTok. Trabajo por proyecto, remoto y presencial.',
                'requisitos'           => 'Portfolio de vídeos de eventos. Dominio de Premiere Pro o DaVinci. Equipo propio.',
                'ubicacion'            => 'Madrid',
                'salario_min'          => 250.00,
                'salario_max'          => 500.00,
                'fecha_inicio_trabajo' => null,
                'fecha_fin_trabajo'    => null,
                'vacantes'             => 2,
                'estado'               => 1,
                'fecha_creacion'       => $ahora,
                'fecha_actualizacion'  => null,
            ],
            [
                'organizador_id'       => 1,
                'evento_id'            => null,
                'categoria_trabajo_id' => 9, // Community manager
                'titulo'               => 'Community Manager — Cobertura en Directo',
                'descripcion'          => 'Gestión de redes sociales en tiempo real durante eventos. Creación de contenido, historias y retransmisiones en directo. Posibilidad de fijo.',
                'requisitos'           => 'Experiencia en redes sociales. Redacción ágil. Conocimientos de Canva y CapCut.',
                'ubicacion'            => 'Barcelona',
                'salario_min'          => 150.00,
                'salario_max'          => 280.00,
                'fecha_inicio_trabajo' => null,
                'fecha_fin_trabajo'    => null,
                'vacantes'             => 3,
                'estado'               => 1,
                'fecha_creacion'       => $ahora,
                'fecha_actualizacion'  => null,
            ],
            [
                'organizador_id'       => 1,
                'evento_id'            => null,
                'categoria_trabajo_id' => 7, // Auxiliar de producción
                'titulo'               => 'Auxiliar de Producción de Eventos',
                'descripcion'          => 'Apoyo integral en la organización, montaje y desmontaje de eventos. Coordinación con proveedores y equipos técnicos.',
                'requisitos'           => 'Proactividad, capacidad de trabajo bajo presión. Carnet de conducir valorado.',
                'ubicacion'            => 'Valencia',
                'salario_min'          => 100.00,
                'salario_max'          => 180.00,
                'fecha_inicio_trabajo' => null,
                'fecha_fin_trabajo'    => null,
                'vacantes'             => 4,
                'estado'               => 1,
                'fecha_creacion'       => $ahora,
                'fecha_actualizacion'  => null,
            ],
            [
                'organizador_id'       => 1,
                'evento_id'            => null,
                'categoria_trabajo_id' => 10, // Relaciones públicas
                'titulo'               => 'Relaciones Públicas — Club y Eventos VIP',
                'descripcion'          => 'Captación de clientes VIP, gestión de reservas y atención personalizada en eventos exclusivos. Excelentes comisiones por resultados.',
                'requisitos'           => 'Don de gentes y red de contactos. Experiencia en hostelería o eventos premium.',
                'ubicacion'            => 'Madrid',
                'salario_min'          => 200.00,
                'salario_max'          => 600.00,
                'fecha_inicio_trabajo' => null,
                'fecha_fin_trabajo'    => null,
                'vacantes'             => 2,
                'estado'               => 1,
                'fecha_creacion'       => $ahora,
                'fecha_actualizacion'  => null,
            ],
            [
                'organizador_id'       => 1,
                'evento_id'            => null,
                'categoria_trabajo_id' => 8, // Decorador/a
                'titulo'               => 'Decorador/a de Espacios para Eventos',
                'descripcion'          => 'Diseño y ambientación de espacios para bodas, fiestas privadas y eventos corporativos. Creatividad y trabajo en equipo.',
                'requisitos'           => 'Formación en diseño de interiores o experiencia equivalente. Portfolio de decoraciones.',
                'ubicacion'            => 'Barcelona',
                'salario_min'          => 180.00,
                'salario_max'          => 350.00,
                'fecha_inicio_trabajo' => null,
                'fecha_fin_trabajo'    => null,
                'vacantes'             => 2,
                'estado'               => 1,
                'fecha_creacion'       => $ahora,
                'fecha_actualizacion'  => null,
            ],
            [
                'organizador_id'       => 1,
                'evento_id'            => null,
                'categoria_trabajo_id' => 1, // Camarero/a
                'titulo'               => 'Camarero/a para Festivales de Verano',
                'descripcion'          => 'Servicio en barra y terraza para festivales de música al aire libre. Trabajo a jornada completa durante julio y agosto. Alojamiento incluido.',
                'requisitos'           => 'Experiencia mínima 1 año en hostelería. Disponibilidad verano completo.',
                'ubicacion'            => 'Madrid',
                'salario_min'          => 1200.00,
                'salario_max'          => 1500.00,
                'fecha_inicio_trabajo' => '2026-07-01 00:00:00',
                'fecha_fin_trabajo'    => '2026-08-31 00:00:00',
                'vacantes'             => 8,
                'estado'               => 1,
                'fecha_creacion'       => $ahora,
                'fecha_actualizacion'  => null,
            ],

        ]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Cancion — Representa una canción del catálogo de Vibez.
 *
 * El administrador gestiona este catálogo. Los clientes pueden comprar
 * canciones para añadirlas a la playlist de un evento de tipo Fiesta.
 */
class Cancion extends Model
{
    // Lista blanca de campos asignables masivamente (create() / fill())
    protected $fillable = [
        'titulo',
        'artista',
        'generos',
        'duracion_segundos',
        'precio',
        'activa',
    ];

    // Conversión automática de tipos al leer de la BD
    protected $casts = [
        'generos' => 'array',    // JSON en BD → array de PHP automáticamente (["Pop","Reggaeton"])
        'precio'  => 'float',    // llega como string de MySQL, lo convierte a número decimal
        'activa'  => 'boolean',  // TINYINT(1) en BD → true/false en PHP
    ];

    /**
     * Accessor: devuelve la duración en formato mm:ss para mostrar en vistas.
     * Acceso: $cancion->duracion_fmt  → "3:45"
     */
    public function getDuracionFmtAttribute(): string
    {
        $minutos = intdiv($this->duracion_segundos, 60);
        $segundos = $this->duracion_segundos % 60;
        return $minutos . ':' . str_pad($segundos, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Relación: una canción puede estar en muchas compras de playlist.
     * Se usará cuando implementemos playlist_evento_canciones.
     */
    public function comprasPlaylist()
    {
        return $this->hasMany(PlaylistEventoCancion::class, 'cancion_id');
    }
}

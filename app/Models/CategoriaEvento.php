<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la tabla categorias_evento.
 * Representa las categorías de eventos (Música, Cultura, Deporte, etc.)
 */
class CategoriaEvento extends Model
{
    // Nombre exacto de la tabla en BD
    protected $table = 'categorias_evento';

    // La tabla usa fecha_creacion / fecha_actualizacion, no timestamps de Laravel
    public $timestamps = false;

    protected $fillable = ['nombre', 'descripcion', 'icono_url', 'estado'];
}

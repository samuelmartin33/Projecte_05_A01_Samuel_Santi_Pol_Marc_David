<?php

namespace App\Models;

<<<<<<< HEAD
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
=======
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaEvento extends Model
{
    use HasFactory;

    protected $table = 'categorias_evento';

    public $timestamps = false;
>>>>>>> 842cf758743629209c59f3b6b6ec472ffcd429bf
}

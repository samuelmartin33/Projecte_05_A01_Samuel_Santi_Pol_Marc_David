<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la tabla `categorias_evento`.
 */
class CategoriaEvento extends Model
{
    use HasFactory;

    protected $table = 'categorias_evento';
    public $timestamps = false;

    protected $fillable = ['nombre', 'descripcion', 'icono_url', 'estado'];
}

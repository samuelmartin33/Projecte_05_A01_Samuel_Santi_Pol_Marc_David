<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la tabla categorias_trabajo.
 * Representa las categorías de puestos de trabajo en eventos
 * (camarero/a, técnico de sonido, fotógrafo/a, etc.).
 */
class CategoriaTrabajo extends Model
{
    protected $table = 'categorias_trabajo';
    public $timestamps = false;

    protected $fillable = ['nombre', 'descripcion', 'estado'];

    /**
     * Relación: una categoría tiene muchas ofertas de trabajo.
     */
    public function ofertas()
    {
        return $this->hasMany(BolsaOfertaTrabajo::class, 'categoria_trabajo_id');
    }
}

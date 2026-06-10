<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo TipoBebida — Categoría de bebida gestionable por el camarero.
 * Los tres tipos por defecto (Cocktail, Destilado, Sin alcohol) se crean en la migración.
 * El camarero puede añadir nuevos tipos desde el CRUD de stock.
 */
class TipoBebida extends Model
{
    protected $table = 'tipos_bebida';

    protected $fillable = ['nombre', 'icono', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    // Scope para filtrar solo los tipos activos
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}

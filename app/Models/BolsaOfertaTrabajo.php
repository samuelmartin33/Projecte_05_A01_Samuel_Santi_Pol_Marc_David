<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la tabla bolsa_ofertas_trabajo.
 * Representa una oferta laboral publicada por un organizador.
 * Las ofertas pueden estar vinculadas a un evento (opcional).
 */
class BolsaOfertaTrabajo extends Model
{
    protected $table = 'bolsa_ofertas_trabajo';
    public $timestamps = false;

    protected $fillable = [
        'organizador_id', 'evento_id', 'categoria_trabajo_id',
        'titulo', 'descripcion', 'requisitos', 'ubicacion',
        'salario_min', 'salario_max', 'vacantes',
        'fecha_inicio_trabajo', 'fecha_fin_trabajo', 'estado'
    ];

    protected $casts = [
        'salario_min' => 'float',
        'salario_max' => 'float',
    ];

    /**
     * Relación: la oferta pertenece a un organizador (empresa).
     */
    public function organizador()
    {
        return $this->belongsTo(Organizador::class, 'organizador_id');
    }

    /**
     * Relación: la oferta pertenece a una categoría de trabajo.
     */
    public function categoria()
    {
        return $this->belongsTo(CategoriaTrabajo::class, 'categoria_trabajo_id');
    }

    /**
     * Accessor: devuelve el rango salarial formateado.
     * Ejemplo: "€ 1.200 – € 1.800 / mes" o "A negociar"
     */
    public function getSalarioFormateadoAttribute(): string
    {
        if ($this->salario_min && $this->salario_max) {
            return '€ ' . number_format($this->salario_min, 0, ',', '.')
                 . ' – € ' . number_format($this->salario_max, 0, ',', '.')
                 . ' / mes';
        }
        if ($this->salario_min) {
            return 'Desde € ' . number_format($this->salario_min, 0, ',', '.') . ' / mes';
        }
        return 'A negociar';
    }
}

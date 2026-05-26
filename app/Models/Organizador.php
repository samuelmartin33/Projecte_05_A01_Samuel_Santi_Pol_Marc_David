<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\CategoriaTrabajo;

/**
 * Modelo para la tabla `organizadores`.
 * - rol: nivel de permiso ('organizador' | 'portero')
 * - categoria_trabajo_id: puesto visible del miembro (Camarero, Barman…)
 *   referencia a categorias_trabajo, la única tabla de tipos de trabajo del proyecto.
 */
class Organizador extends Model
{
    use HasFactory;

    protected $table = 'organizadores';

    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'empresa_id',
        'rol',
        'categoria_trabajo_id',
        'estado',
        'fecha_creacion',
        'fecha_actualizacion',
    ];

    public function isPortero(): bool
    {
        return $this->rol === 'portero';
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    /** Puesto de trabajo del miembro (Camarero, Barman, Portero…) */
    public function categoriaTrabajo(): BelongsTo
    {
        return $this->belongsTo(CategoriaTrabajo::class, 'categoria_trabajo_id');
    }
}

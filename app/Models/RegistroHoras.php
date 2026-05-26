<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Registro diario de horas trabajadas por un organizador o portero.
 */
class RegistroHoras extends Model
{
    protected $table = 'registro_horas';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'fecha',
        'horas',
        'descripcion',
        'fecha_creacion',
        'fecha_actualizacion',
    ];

    protected $casts = [
        'horas' => 'float',
        'fecha' => 'date',
    ];

    /**
     * Relación: el registro pertenece a un usuario.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}

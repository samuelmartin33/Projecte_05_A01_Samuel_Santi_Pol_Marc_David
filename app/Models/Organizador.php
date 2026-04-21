<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo Organizador
 *
 * Representa a un usuario que organiza eventos para una empresa.
 * Tabla: organizadores
 */
class Organizador extends Model
{
    protected $table = 'organizadores';

    /** La tabla no usa timestamps estándar de Laravel */
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'empresa_id',
        'estado',
        'fecha_creacion',
        'fecha_actualizacion',
    ];

    /** Relación: el organizador pertenece a un usuario */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /** Relación: el organizador pertenece a una empresa */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}

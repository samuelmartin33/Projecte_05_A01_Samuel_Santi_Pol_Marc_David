<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo Organizador
 *
 * Representa a un usuario que organiza eventos para una empresa.
 * Tabla: organizadores
=======

/**
 * Modelo para la tabla organizadores.
 * Un organizador es un usuario de tipo empresa que crea eventos y publica ofertas.
>>>>>>> 94a132a4f2ad280543f6421d5139cf3709db3dfc
 */
class Organizador extends Model
{
    protected $table = 'organizadores';
<<<<<<< HEAD

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
=======
    public $timestamps = false;

    protected $fillable = ['usuario_id', 'empresa_id', 'estado'];

    /**
     * Relación: un organizador pertenece a una empresa.
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    /**
     * Relación: un organizador es un usuario de la tabla usuarios.
     */
    public function usuario()
    {
        return $this->belongsTo(UsuarioVibez::class, 'usuario_id');
    }
>>>>>>> 94a132a4f2ad280543f6421d5139cf3709db3dfc
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la tabla organizadores.
 * Un organizador es un usuario de tipo empresa que crea eventos y publica ofertas.
 */
class Organizador extends Model
{
    protected $table = 'organizadores';
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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amigo extends Model
{
    protected $table      = 'amigos';
    public    $timestamps = false;

    protected $fillable = [
        'solicitante_id',
        'receptor_id',
        'estado',
        'fecha_creacion',
        'fecha_actualizacion',
    ];

    /* ——— Relaciones ——— */

    /** Usuario que envió la solicitud */
    public function solicitante()
    {
        return $this->belongsTo(Usuario::class, 'solicitante_id');
    }

    /** Usuario que recibió la solicitud */
    public function receptor()
    {
        return $this->belongsTo(Usuario::class, 'receptor_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    protected $table      = 'mensajes';
    public    $timestamps = false;

    protected $fillable = [
        'chat_id',
        'usuario_id',
        'contenido',
        'leido',
        'estado',
        'fecha_creacion',
        'fecha_actualizacion',
    ];

    /* ——— Relaciones ——— */

    /** Usuario que envió este mensaje */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /** Chat al que pertenece este mensaje */
    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }
}

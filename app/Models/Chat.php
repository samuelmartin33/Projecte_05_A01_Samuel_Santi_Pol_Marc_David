<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table      = 'chats';
    public    $timestamps = false;

    protected $fillable = [
        'tipo_chat',
        'evento_id',
        'candidatura_id',
        'nombre',
        'estado',
        'fecha_creacion',
        'fecha_actualizacion',
    ];

    /* ——— Relaciones ——— */

    /** Todos los mensajes de este chat, ordenados por fecha */
    public function mensajes()
    {
        return $this->hasMany(Mensaje::class, 'chat_id')->orderBy('fecha_creacion');
    }

    /**
     * Último mensaje del chat.
     * Se usa para mostrar la vista previa en la lista de conversaciones.
     */
    public function ultimoMensaje()
    {
        return $this->hasOne(Mensaje::class, 'chat_id')->orderByDesc('id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la tabla `chats`.
 */
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

    /** Miembros del chat (solo para tipo_chat = 2 / grupos) */
    public function miembros()
    {
        return $this->hasMany(ChatMiembro::class, 'chat_id')->with('usuario:id,nombre,apellido1,foto_url');
    }

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

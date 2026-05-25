<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Miembros de un chat de grupo (tipo_chat = 2).
 */
class ChatMiembro extends Model
{
    protected $table   = 'chat_miembros';
    public $timestamps = false;

    protected $fillable = [
        'chat_id',
        'usuario_id',
        'es_admin',
        'fecha_union',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventoPostComentario extends Model
{
    protected $table   = 'evento_post_comentarios';
    public $timestamps = false;

    protected $fillable = [
        'evento_post_id',
        'usuario_id',
        'contenido',
        'estado',
        'fecha_creacion',
        'fecha_actualizacion',
    ];

    protected $casts = [
        'fecha_creacion'      => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];

    public function post()
    {
        return $this->belongsTo(EventoPost::class, 'evento_post_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}

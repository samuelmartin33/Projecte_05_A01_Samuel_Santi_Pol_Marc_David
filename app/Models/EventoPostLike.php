<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventoPostLike extends Model
{
    public $timestamps = false;

    protected $table = 'evento_post_likes';

    protected $fillable = [
        'evento_post_id',
        'usuario_id',
        'estado',
        'fecha_creacion',
        'fecha_actualizacion',
    ];

    public function post()
    {
        return $this->belongsTo(EventoPost::class, 'evento_post_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventoPost extends Model
{
    protected $table   = 'evento_posts';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'evento_id',
        'descripcion',
        'estado',
        'visibilidad',
        'fecha_creacion',
        'fecha_actualizacion',
    ];

    protected $casts = [
        'fecha_creacion'      => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    public function imagenes()
    {
        return $this->hasMany(EventoPostImagen::class, 'evento_post_id')->orderBy('orden');
    }

    public function comentarios()
    {
        return $this->hasMany(EventoPostComentario::class, 'evento_post_id')->orderBy('fecha_creacion');
    }

    public function likes()
    {
        return $this->hasMany(EventoPostLike::class, 'evento_post_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventoPostImagen extends Model
{
    protected $table   = 'evento_post_imagenes';
    public $timestamps = false;

    protected $fillable = [
        'evento_post_id',
        'imagen_url',
        'orden',
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
}

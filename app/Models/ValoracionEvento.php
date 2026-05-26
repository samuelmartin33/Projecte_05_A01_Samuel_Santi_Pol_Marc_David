<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/* Valoración que un usuario hace sobre un evento (solo si compró entrada) */
class ValoracionEvento extends Model
{
    protected $table      = 'valoraciones_eventos';
    public    $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'evento_id',
        'puntuacion',
        'comentario',
        'estado',
        'fecha_creacion',
        'fecha_actualizacion',
    ];

    protected $casts = [
        'puntuacion'          => 'integer',
        'estado'              => 'integer',
        'fecha_creacion'      => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];

    /* Usuario que escribió la reseña */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /* Evento valorado */
    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }
}

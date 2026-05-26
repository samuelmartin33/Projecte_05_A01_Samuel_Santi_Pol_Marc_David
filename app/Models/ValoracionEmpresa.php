<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/* Valoración que un usuario hace sobre una empresa/promotora */
class ValoracionEmpresa extends Model
{
    protected $table      = 'valoraciones_empresas';
    public    $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'empresa_id',
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

    /* Empresa valorada */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}

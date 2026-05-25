<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriaVista extends Model
{
    protected $table   = 'historia_vistas';
    public $timestamps = false;

    protected $fillable = [
        'historia_id',
        'usuario_id',
        'fecha_vista',
    ];

    protected $casts = [
        'fecha_vista' => 'datetime',
    ];
}

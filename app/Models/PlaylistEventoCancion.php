<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo PlaylistEventoCancion — Registro de una canción comprada por un cliente para un evento Fiesta.
 *
 * Actúa como tabla pivot entre eventos, usuarios y canciones.
 * El campo 'orden' indica el orden de reproducción (= orden de compra).
 * La restricción unique(evento_id, usuario_id, cancion_id) impide que el mismo
 * cliente añada la misma canción dos veces al mismo evento.
 */
class PlaylistEventoCancion extends Model
{
    protected $table = 'playlist_evento_canciones';

    protected $fillable = [
        'evento_id',
        'usuario_id',
        'cancion_id',
        'orden',
        'precio_pagado',
    ];

    protected $casts = [
        'precio_pagado' => 'float',
    ];

    // Un registro pertenece a un evento
    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    // Un registro pertenece a un usuario (cliente)
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Un registro pertenece a una canción
    public function cancion()
    {
        return $this->belongsTo(Cancion::class, 'cancion_id');
    }
}

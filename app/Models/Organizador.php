<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Organizador extends Model
{
    use HasFactory;

    protected $table = 'organizadores';

    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'empresa_id',
        'estado',
        'fecha_creacion',
        'fecha_actualizacion',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}

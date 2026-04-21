<?php

namespace App\Models;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Organizador extends Model
{
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
=======
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizador extends Model
{
    use HasFactory;

    protected $table = 'organizadores';

    public $timestamps = false;
>>>>>>> 842cf758743629209c59f3b6b6ec472ffcd429bf
}

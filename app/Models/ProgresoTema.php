<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgresoTema extends Model
{
    protected $table = 'progresotema';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'usuarioId',
        'temaId',
        'completado',
        'tiempoVisto',
        'ultimoAcceso',
    ];

    protected $casts = [
        'completado' => 'boolean',
        'tiempoVisto' => 'int',
        'ultimoAcceso' => 'datetime',
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuarioId');
    }

    public function tema(): BelongsTo
    {
        return $this->belongsTo(Tema::class, 'temaId');
    }
}
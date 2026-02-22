<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tema extends Model
{
    protected $table = 'tema';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'nombre',
        'descripcion',
        'moduloId',
        'duracion',
        'orden',
        'videoUrl',
    ];

    protected $casts = [
        'duracion' => 'int',
        'orden' => 'int',
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime',
    ];

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(Modulo::class, 'moduloId');
    }

    public function progresoTemas(): HasMany
    {
        return $this->hasMany(ProgresoTema::class, 'temaId');
    }
}
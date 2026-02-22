<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Modulo extends Model
{
    protected $table = 'modulo';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'nombre',
        'descripcion',
        'cursoId',
    ];

    protected $casts = [
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime',
    ];

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class, 'cursoId');
    }

    public function temas(): HasMany
    {
        return $this->hasMany(Tema::class, 'moduloId');
    }
}
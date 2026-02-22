<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matricula extends Model
{
    protected $table = 'matricula';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'usuarioId',
        'cursoId',
        'estado',
        'progreso',
    ];

    protected $casts = [
        'progreso' => 'float',
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuarioId');
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class, 'cursoId');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'matriculaId');
    }
}
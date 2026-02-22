<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nota extends Model
{
    protected $table = 'nota';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'valor',
        'comentario',
        'usuarioId',
        'cursoId',
    ];

    protected $casts = [
        'valor' => 'int',
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
}
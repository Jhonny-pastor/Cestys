<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificado extends Model
{
    protected $table = 'certificado';

    // Ojo: tú tienes fechaEmision separado de createdAt
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'codigo',
        'link',
        'fechaEmision',
        'usuarioId',
        'cursoId',
        'estado',
        'notasAdicionales',
    ];

    protected $casts = [
        'fechaEmision' => 'datetime',
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
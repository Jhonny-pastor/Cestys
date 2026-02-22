<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    protected $table = 'pago';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'monto',
        'metodo',
        'referencia',
        'estado',
        'matriculaId',
    ];

    protected $casts = [
        'monto' => 'float',
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime',
    ];

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class, 'matriculaId');
    }
}
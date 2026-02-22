<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Administrador extends Model
{
    protected $table = 'administrador';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'usuarioId',
        'permisos',
        'puedeAsignarRol',
        'asignadoPor',
    ];

    protected $casts = [
        'puedeAsignarRol' => 'boolean',
        'permisos' => 'array', // si en BD lo guardas como JSON/text
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuarioId');
    }
}
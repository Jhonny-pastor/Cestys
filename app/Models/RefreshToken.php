<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefreshToken extends Model
{
    protected $table = 'refreshtoken';

    // Solo tiene createdAt, no updatedAt
    const CREATED_AT = 'createdAt';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'token',
        'usuarioId',
        'expiresAt',
    ];

    protected $casts = [
        'expiresAt' => 'datetime',
        'createdAt' => 'datetime',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuarioId');
    }
}
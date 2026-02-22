<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Usuario extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'usuario'; // si tu migración creó exactamente "Usuario" con mayúscula
    // Si tu tabla real es "usuarios" o "usuario" cambia esto.

    protected $fillable = [
        'email','password','googleId','rol','estado',
        'emailVerified','tokenExpiresAt','verificationToken'
    ];

    protected $hidden = [
        'password','verificationToken'
    ];

    protected $casts = [
        'emailVerified' => 'boolean',
        'tokenExpiresAt' => 'datetime',
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime',
    ];

    // Si tus columnas son createdAt/updatedAt (no created_at/updated_at):
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'rol' => $this->rol,
            'email' => $this->email,
        ];
    }
}
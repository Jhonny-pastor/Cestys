<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $payload = [
            'email' => 'jhonny.pastor@cestys.com',
            'password' => '12345678',
            'nombre' => 'Jhonny',
            'apellido' => 'Pastor',
        ];

        $response = $this->postJson('/api/v1/auth/register', $payload);

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'ok',
                'access_token',
                'token_type',
                'expires_in',
                'user' => ['id', 'email', 'rol'],
            ])
            ->assertJson([
                'ok' => true,
                'token_type' => 'bearer',
                'user' => [
                    'email' => 'jhonny.pastor@cestys.com',
                    'rol' => 'ESTUDIANTE',
                ],
            ]);

        $this->assertDatabaseHas((new Usuario())->getTable(), [
            'email' => 'jhonny.pastor@cestys.com',
            'rol' => 'ESTUDIANTE',
        ]);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        Usuario::create([
            'email' => 'jhonny.pastor@cestys.com',
            'password' => bcrypt('12345678'),
            'rol' => 'ESTUDIANTE',
            'estado' => true,
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'jhonny.pastor@cestys.com',
            'password' => '12345678',
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'ok',
                'access_token',
                'token_type',
                'expires_in',
                'user' => ['id', 'email', 'rol'],
            ])
            ->assertJson([
                'ok' => true,
                'token_type' => 'bearer',
                'user' => [
                    'email' => 'jhonny.pastor@cestys.com',
                    'rol' => 'ESTUDIANTE',
                ],
            ]);
    }
}


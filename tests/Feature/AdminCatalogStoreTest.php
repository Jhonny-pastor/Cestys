<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Curso;
use App\Models\Modulo;
use App\Models\Tema;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminCatalogStoreTest extends TestCase
{
    use RefreshDatabase;

    private function adminAuthHeaders(): array
    {
        $admin = Usuario::create([
            'email' => '42078151@continental.edu.pe',
            'password' => Hash::make('12345678'),
            'rol' => 'ADMIN',
            'estado' => true,
        ]);

        $token = JWTAuth::fromUser($admin);

        return [
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ];
    }

    public function test_admin_can_register_a_category(): void
    {
        $response = $this
            ->withHeaders($this->adminAuthHeaders())
            ->postJson('/api/v1/admin/categories', [
                'nombre' => 'Desarrollo Web',
                'descripcion' => 'Categoria para cursos web',
                'estado' => true,
            ]);

        $response
            ->assertStatus(201)
            ->assertJsonPath('ok', true)
            ->assertJsonPath('data.nombre', 'Desarrollo Web');

        $this->assertDatabaseHas((new Categoria())->getTable(), [
            'nombre' => 'Desarrollo Web',
        ]);
    }

    public function test_admin_can_register_a_course(): void
    {
        $headers = $this->adminAuthHeaders();
        $category = Categoria::create([
            'nombre' => 'Backend',
            'descripcion' => 'Categoria backend',
            'estado' => true,
        ]);

        $response = $this
            ->withHeaders($headers)
            ->postJson('/api/v1/admin/courses', [
                'codigo' => 'CURS-BE-001',
                'imagenPortada' => 'covers/backend.png',
                'nombre' => 'Laravel API Profesional',
                'descripcion' => 'Curso de APIs con Laravel',
                'precio' => 59.90,
                'horas' => 20,
                'valoracion' => 4.7,
                'estado' => 'PUBLISHED',
                'categoriaId' => $category->id,
            ]);

        $response
            ->assertStatus(201)
            ->assertJsonPath('ok', true)
            ->assertJsonPath('data.codigo', 'CURS-BE-001');

        $this->assertDatabaseHas((new Curso())->getTable(), [
            'codigo' => 'CURS-BE-001',
            'nombre' => 'Laravel API Profesional',
        ]);
    }

    public function test_admin_can_register_a_topic(): void
    {
        $headers = $this->adminAuthHeaders();

        $category = Categoria::create([
            'nombre' => 'Programacion',
            'descripcion' => 'Categoria base',
            'estado' => true,
        ]);

        $course = Curso::create([
            'codigo' => 'CURS-TOPIC-001',
            'nombre' => 'Curso para tema',
            'descripcion' => 'Base para crear tema',
            'precio' => 10,
            'horas' => 4,
            'valoracion' => 4.0,
            'estado' => 'PUBLISHED',
            'categoriaId' => $category->id,
        ]);

        $module = Modulo::create([
            'nombre' => 'Modulo Inicial',
            'descripcion' => 'Modulo prerequisito',
            'cursoId' => $course->id,
        ]);

        $response = $this
            ->withHeaders($headers)
            ->postJson('/api/v1/admin/topics', [
                'nombre' => 'Instalacion del entorno',
                'descripcion' => 'Preparar entorno local',
                'moduloId' => $module->id,
                'duracion' => 25,
                'orden' => 1,
                'videoUrl' => 'https://cdn.example.com/videos/instalacion.mp4',
            ]);

        $response
            ->assertStatus(201)
            ->assertJsonPath('ok', true)
            ->assertJsonPath('data.nombre', 'Instalacion del entorno');

        $this->assertDatabaseHas((new Tema())->getTable(), [
            'nombre' => 'Instalacion del entorno',
            'moduloId' => $module->id,
        ]);
    }
}


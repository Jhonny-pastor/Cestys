<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Pago;
use App\Models\Transaccion;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckoutFlowTest extends TestCase
{
    use RefreshDatabase;

    private function studentHeaders(): array
    {
        $student = Usuario::create([
            'email' => 'student.checkout@example.com',
            'password' => Hash::make('secret123'),
            'rol' => 'ESTUDIANTE',
            'estado' => true,
        ]);

        $token = JWTAuth::fromUser($student);

        return [
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ];
    }

    public function test_checkout_flow_creates_paid_order_and_enrollment(): void
    {
        $headers = $this->studentHeaders();

        $category = Categoria::create([
            'nombre' => 'Categoria Checkout',
            'descripcion' => 'Testing flow',
            'estado' => true,
        ]);

        $course = Curso::create([
            'codigo' => 'CHK-001',
            'nombre' => 'Curso Checkout',
            'descripcion' => 'Curso para flujo de checkout',
            'precio' => 49.90,
            'horas' => 8,
            'valoracion' => 4.5,
            'estado' => 'PUBLISHED',
            'categoriaId' => $category->id,
        ]);

        $this->withHeaders($headers)
            ->postJson('/api/v1/me/cart/items', ['cursoId' => $course->id])
            ->assertStatus(201)
            ->assertJsonPath('ok', true);

        $orderResponse = $this->withHeaders($headers)
            ->postJson('/api/v1/orders')
            ->assertStatus(201)
            ->assertJsonPath('ok', true)
            ->assertJsonPath('data.estado', 'PENDING');

        $orderId = $orderResponse->json('data.id');

        $this->postJson('/api/v1/payments/webhook', [
            'orderId' => $orderId,
            'status' => 'PAID',
            'paymentId' => 'SIM-PAY-CHECKOUT',
        ])->assertOk()->assertJsonPath('ok', true);

        $this->assertDatabaseHas((new Transaccion())->getTable(), [
            'id' => $orderId,
            'estado' => 'PAID',
            'paymentId' => 'SIM-PAY-CHECKOUT',
        ]);

        $this->assertDatabaseHas((new Matricula())->getTable(), [
            'cursoId' => $course->id,
            'estado' => 'ACTIVE',
        ]);

        $this->assertDatabaseHas((new Pago())->getTable(), [
            'referencia' => 'SIM-PAY-CHECKOUT',
            'estado' => 'PAID',
        ]);
    }
}


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

class CartAndPaymentTest extends TestCase
{
    use RefreshDatabase;

    private function studentHeaders(): array
    {
        $student = Usuario::create([
            'email' => 'jhonny.pastor@cestys.com',
            'password' => Hash::make('12345678'),
            'rol' => 'ESTUDIANTE',
            'estado' => true,
        ]);

        $token = JWTAuth::fromUser($student);

        return [
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ];
    }

    private function createCourse(string $code = 'CART-001'): Curso
    {
        $category = Categoria::create([
            'nombre' => 'Categoria Carrito',
            'descripcion' => 'Categoria para pruebas',
            'estado' => true,
        ]);

        return Curso::create([
            'codigo' => $code,
            'nombre' => 'Curso de prueba carrito',
            'descripcion' => 'Curso para pruebas de carrito',
            'precio' => 49.90,
            'horas' => 6,
            'valoracion' => 4.2,
            'estado' => 'PUBLISHED',
            'categoriaId' => $category->id,
        ]);
    }

    public function test_student_can_register_course_in_cart(): void
    {
        $headers = $this->studentHeaders();
        $course = $this->createCourse('CART-ADD-001');

        $this->withHeaders($headers)
            ->postJson('/api/v1/me/cart/items', [
                'cursoId' => $course->id,
            ])
            ->assertStatus(201)
            ->assertJsonPath('ok', true)
            ->assertJsonPath('data.items.0.cursoId', $course->id);

        $this->withHeaders($headers)
            ->getJson('/api/v1/me/cart')
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('data.items.0.cursoId', $course->id)
            ->assertJsonPath('data.total', 49.9);
    }

    public function test_payment_webhook_marks_order_as_paid_and_generates_enrollment(): void
    {
        $headers = $this->studentHeaders();
        $course = $this->createCourse('PAY-001');

        $this->withHeaders($headers)
            ->postJson('/api/v1/me/cart/items', ['cursoId' => $course->id])
            ->assertStatus(201);

        $orderResponse = $this->withHeaders($headers)
            ->postJson('/api/v1/orders')
            ->assertStatus(201)
            ->assertJsonPath('data.estado', 'PENDING');

        $orderId = (int) $orderResponse->json('data.id');

        $this->postJson('/api/v1/payments/webhook', [
            'orderId' => $orderId,
            'status' => 'PAID',
            'paymentId' => 'SIM-PAY-CART-001',
        ])->assertOk()->assertJsonPath('ok', true);

        $this->assertDatabaseHas((new Transaccion())->getTable(), [
            'id' => $orderId,
            'estado' => 'PAID',
            'paymentId' => 'SIM-PAY-CART-001',
        ]);

        $this->assertDatabaseHas((new Matricula())->getTable(), [
            'cursoId' => $course->id,
            'estado' => 'ACTIVE',
        ]);

        $this->assertDatabaseHas((new Pago())->getTable(), [
            'referencia' => 'SIM-PAY-CART-001',
            'estado' => 'PAID',
        ]);
    }
}


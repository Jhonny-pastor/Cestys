<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Transaccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    private function currentUser(Request $request)
    {
        return auth('api')->user() ?? $request->user();
    }

    private function cartKey(int $userId): string
    {
        return "cart:user:{$userId}";
    }

    private function getCart(int $userId): array
    {
        return Cache::get($this->cartKey($userId), []);
    }

    private function putCart(int $userId, array $cart): void
    {
        Cache::put($this->cartKey($userId), $cart, now()->addHours(12));
    }

    public function cart(Request $request)
    {
        $user = $this->currentUser($request);
        if (!$user) {
            return response()->json(['ok' => false, 'message' => 'Unauthorized'], 401);
        }

        $items = $this->getCart($user->id);
        $total = collect($items)->sum('precio');

        return response()->json([
            'ok' => true,
            'data' => [
                'items' => $items,
                'total' => $total,
            ],
        ]);
    }

    public function addCartItem(Request $request)
    {
        $user = $this->currentUser($request);
        if (!$user) {
            return response()->json(['ok' => false, 'message' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'cursoId' => ['required', 'integer'],
        ]);

        $course = Curso::findOrFail($data['cursoId']);
        $cart = collect($this->getCart($user->id))
            ->keyBy('cursoId')
            ->all();

        $cart[$course->id] = [
            'cursoId' => $course->id,
            'nombre' => $course->nombre,
            'precio' => (float) ($course->precio ?? 0),
        ];

        $cartItems = array_values($cart);
        $this->putCart($user->id, $cartItems);

        return response()->json([
            'ok' => true,
            'data' => [
                'items' => $cartItems,
                'total' => collect($cartItems)->sum('precio'),
            ],
        ], 201);
    }

    public function removeCartItem(Request $request, int $cursoId)
    {
        $user = $this->currentUser($request);
        if (!$user) {
            return response()->json(['ok' => false, 'message' => 'Unauthorized'], 401);
        }

        $items = collect($this->getCart($user->id))
            ->reject(fn ($item) => (int) $item['cursoId'] === $cursoId)
            ->values()
            ->all();

        $this->putCart($user->id, $items);

        return response()->json([
            'ok' => true,
            'data' => [
                'items' => $items,
                'total' => collect($items)->sum('precio'),
            ],
        ]);
    }

    public function clearCart(Request $request)
    {
        $user = $this->currentUser($request);
        if (!$user) {
            return response()->json(['ok' => false, 'message' => 'Unauthorized'], 401);
        }

        Cache::forget($this->cartKey($user->id));

        return response()->json([
            'ok' => true,
            'message' => 'Carrito vaciado',
        ]);
    }

    public function store(Request $request)
    {
        $user = $this->currentUser($request);
        if (!$user) {
            return response()->json(['ok' => false, 'message' => 'Unauthorized'], 401);
        }

        $items = $this->getCart($user->id);
        if (empty($items)) {
            return response()->json([
                'ok' => false,
                'message' => 'El carrito esta vacio',
            ], 422);
        }

        $total = collect($items)->sum('precio');
        $productLabel = collect($items)->pluck('nombre')->join(', ');

        $order = Transaccion::create([
            'producto' => Str::limit($productLabel, 255, ''),
            'monto' => $total,
            'estado' => 'PENDING',
            'paymentId' => null,
            'preferenceId' => 'SIM-' . strtoupper(Str::random(10)),
            'usuarioId' => $user->id,
            'items' => $items,
        ]);

        Cache::forget($this->cartKey($user->id));

        return response()->json([
            'ok' => true,
            'data' => $order,
            'payment' => [
                'mode' => 'SIMULATED',
                'webhook_url' => '/api/v1/payments/webhook',
                'payload_example' => [
                    'orderId' => $order->id,
                    'status' => 'PAID',
                    'paymentId' => 'SIM-PAY-001',
                ],
            ],
        ], 201);
    }

    public function myOrders(Request $request)
    {
        $user = $this->currentUser($request);
        if (!$user) {
            return response()->json(['ok' => false, 'message' => 'Unauthorized'], 401);
        }

        $orders = Transaccion::query()
            ->where('usuarioId', $user->id)
            ->orderByDesc('id')
            ->paginate(15);

        return response()->json([
            'ok' => true,
            'data' => $orders,
        ]);
    }

    public function orderStatus(Request $request, $id)
    {
        $user = $this->currentUser($request);
        if (!$user) {
            return response()->json(['ok' => false, 'message' => 'Unauthorized'], 401);
        }

        $order = Transaccion::where('usuarioId', $user->id)->findOrFail($id);

        return response()->json([
            'ok' => true,
            'data' => [
                'id' => $order->id,
                'estado' => $order->estado,
                'monto' => $order->monto,
                'items' => $order->items,
                'paymentId' => $order->paymentId,
            ],
        ]);
    }

    public function retryOrder(Request $request, $id)
    {
        $user = $this->currentUser($request);
        if (!$user) {
            return response()->json(['ok' => false, 'message' => 'Unauthorized'], 401);
        }

        $order = Transaccion::where('usuarioId', $user->id)->findOrFail($id);

        if ($order->estado === 'PAID') {
            return response()->json([
                'ok' => false,
                'message' => 'La orden ya fue pagada',
            ], 422);
        }

        $order->update([
            'estado' => 'PENDING',
            'preferenceId' => 'SIM-' . strtoupper(Str::random(10)),
        ]);

        return response()->json([
            'ok' => true,
            'data' => $order,
            'message' => 'Orden reintentada en modo simulado',
        ]);
    }
}

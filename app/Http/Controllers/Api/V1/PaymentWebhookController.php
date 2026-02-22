<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Matricula;
use App\Models\Pago;
use App\Models\Transaccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentWebhookController extends Controller
{
    public function webhook(Request $request)
    {
        $data = $request->validate([
            'orderId' => ['required', 'integer'],
            'status' => ['required', 'string'],
            'paymentId' => ['nullable', 'string', 'max:100'],
        ]);

        $normalizedStatus = strtoupper($data['status']);
        $order = Transaccion::findOrFail($data['orderId']);

        if ($normalizedStatus !== 'PAID') {
            $order->update([
                'estado' => $normalizedStatus,
                'paymentId' => $data['paymentId'] ?? $order->paymentId,
            ]);

            return response()->json([
                'ok' => true,
                'message' => 'Estado actualizado sin matricula automatica',
                'data' => $order,
            ]);
        }

        DB::transaction(function () use ($order, $data) {
            $order->refresh();
            $order->update([
                'estado' => 'PAID',
                'paymentId' => $data['paymentId'] ?? ('SIM-PAY-' . strtoupper(Str::random(8))),
            ]);

            $items = is_array($order->items) ? $order->items : [];
            $itemCount = max(count($items), 1);
            $paymentPerEnrollment = (float) $order->monto / $itemCount;

            foreach ($items as $item) {
                $cursoId = (int) ($item['cursoId'] ?? 0);
                if ($cursoId <= 0) {
                    continue;
                }

                $enrollment = Matricula::firstOrCreate(
                    [
                        'usuarioId' => $order->usuarioId,
                        'cursoId' => $cursoId,
                    ],
                    [
                        'estado' => 'ACTIVE',
                        'progreso' => 0,
                    ]
                );

                $existingPayment = Pago::query()
                    ->where('matriculaId', $enrollment->id)
                    ->where('referencia', $order->paymentId)
                    ->first();

                if (!$existingPayment) {
                    Pago::create([
                        'monto' => $paymentPerEnrollment,
                        'metodo' => 'SIMULATED_GATEWAY',
                        'referencia' => $order->paymentId,
                        'estado' => 'PAID',
                        'matriculaId' => $enrollment->id,
                    ]);
                }
            }
        });

        return response()->json([
            'ok' => true,
            'message' => 'Pago confirmado y matriculas generadas',
            'data' => [
                'orderId' => $order->id,
                'estado' => 'PAID',
                'paymentId' => $order->paymentId,
            ],
        ]);
    }
}

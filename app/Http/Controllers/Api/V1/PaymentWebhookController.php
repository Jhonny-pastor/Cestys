<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

class PaymentWebhookController extends Controller
{
    public function webhook() {
        return response()->json(['ok' => true, 'message' => 'TODO']);
    }
}

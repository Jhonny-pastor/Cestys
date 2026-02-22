<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function store() {
        return response()->json(['ok' => true, 'message' => 'TODO']);
    }
    public function myOrders() {
        return response()->json(['ok' => true, 'message' => 'TODO']);
    }
    public function orderStatus($id) {
        return response()->json(['ok' => true, 'message' => 'TODO']);
    }
    public function retryOrder($id) {
        return response()->json(['ok' => true, 'message' => 'TODO']);
    }
}

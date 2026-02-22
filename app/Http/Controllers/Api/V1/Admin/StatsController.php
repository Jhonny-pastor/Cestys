<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;

class StatsController extends Controller
{
    public function summary() {
        return response()->json(['ok' => true, 'message' => 'TODO']);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

class ProgressController extends Controller
{
    public function updateTopic($temaId) {
        return response()->json(['ok' => true, 'message' => 'TODO']);
    }
}

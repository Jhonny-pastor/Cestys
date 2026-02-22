<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

class EnrollmentController extends Controller
{
    public function myEnrollments() {
        return response()->json(['ok' => true, 'message' => 'TODO']);
    }
}

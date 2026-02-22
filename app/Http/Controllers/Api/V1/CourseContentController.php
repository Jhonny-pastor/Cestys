<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

class CourseContentController extends Controller
{
    public function show($id) {
        return response()->json(['ok' => true, 'message' => 'TODO']);
    }
}

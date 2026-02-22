<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

class GradeController extends Controller
{
    public function autoGrade($courseId) {
        return response()->json(['ok' => true, 'message' => 'TODO']);
    }
    public function adminIndex() {
        return response()->json(['ok' => true, 'message' => 'TODO']);
    }
    public function adminUpdate($id) {
        return response()->json(['ok' => true, 'message' => 'TODO']);
    }
}

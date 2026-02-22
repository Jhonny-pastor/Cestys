<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;

class GradeAdminController extends Controller
{
    public function index() {
        return response()->json(['ok' => true, 'message' => 'TODO']);
    }
    public function update($id) {
        return response()->json(['ok' => true, 'message' => 'TODO']);
    }
}

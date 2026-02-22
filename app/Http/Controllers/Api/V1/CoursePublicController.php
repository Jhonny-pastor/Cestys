<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CoursePublicController extends Controller
{
    public function index() {
        return response()->json(['ok' => true, 'message' => 'TODO']);
    }
    public function show($id) {
        return response()->json(['ok' => true, 'message' => 'TODO']);
    }
}

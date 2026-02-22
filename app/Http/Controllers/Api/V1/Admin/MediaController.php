<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;

class MediaController extends Controller
{
    public function upload() {
        return response()->json(['ok' => true, 'message' => 'TODO']);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

class StudentCertificateController extends Controller
{
    public function myCertificates() {
        return response()->json(['ok' => true, 'message' => 'TODO']);
    }
    public function download($id) {
        return response()->json(['ok' => true, 'message' => 'TODO']);
    }
    public function email($id) {
        return response()->json(['ok' => true, 'message' => 'TODO']);
    }
}

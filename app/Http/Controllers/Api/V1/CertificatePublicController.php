<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

class CertificatePublicController extends Controller
{
    public function validate($code) {
        return response()->json(['ok' => true, 'message' => 'TODO']);
    }
}

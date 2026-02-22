<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;

class CertificateAdminController extends Controller
{
    public function index() {
        return response()->json(['ok' => true, 'message' => 'TODO']);
    }
}

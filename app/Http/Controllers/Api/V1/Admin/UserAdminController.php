<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;

class UserAdminController extends Controller
{
    public function updateRole($id) {
        return response()->json(['ok' => true, 'message' => 'TODO']);
    }
}

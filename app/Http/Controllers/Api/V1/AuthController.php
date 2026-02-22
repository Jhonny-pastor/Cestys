<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Usuario;
use App\Models\Perfil; // si ya lo tienes
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        return response()->json(['ok' => true, 'message' => 'TODO']);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = Usuario::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'ok' => false,
                'message' => 'Credenciales inválidas',
            ], 401);
        }

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'ok' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'rol' => $user->rol,
            ],
        ]);
    }

    public function me(Request $request)
    {
        return response()->json(['ok' => true, 'message' => 'TODO']);
    }

    public function logout(Request $request)
    {
        return response()->json(['ok' => true, 'message' => 'TODO']);
    }

    public function forgotPassword(Request $request)
    {
        return response()->json(['ok' => true, 'message' => 'TODO']);
    }
    public function resetPassword(Request $request)
    {
        return response()->json(['ok' => true, 'message' => 'TODO']);
    }
}

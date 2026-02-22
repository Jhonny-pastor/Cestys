<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Usuario;
use App\Models\Perfil;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'unique:' . (new Usuario())->getTable() . ',email'],
            'password' => ['required', 'string', 'min:6'],
            'rol' => ['sometimes', 'string', 'in:ADMIN,ESTUDIANTE'],
            'estado' => ['sometimes', 'boolean'],
            'nombre' => ['nullable', 'string', 'max:255'],
            'apellido' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'fotografia' => ['nullable', 'string', 'max:255'],
        ]);

        $user = Usuario::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'rol' => $data['rol'] ?? 'ESTUDIANTE',
            'estado' => $data['estado'] ?? true,
        ]);

        Perfil::create([
            'usuarioId' => $user->id,
            'nombre' => $data['nombre'] ?? null,
            'apellido' => $data['apellido'] ?? null,
            'telefono' => $data['telefono'] ?? null,
            'direccion' => $data['direccion'] ?? null,
            'fotografia' => $data['fotografia'] ?? null,
        ]);

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
        ], 201);
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

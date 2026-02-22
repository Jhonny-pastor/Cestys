<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if (!$user || $user->rol !== 'ADMIN') {
            return response()->json(['error' => 'No autorizado'], 403);
        }
        return $next($request);
    }
}

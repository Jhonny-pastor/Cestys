<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminRole
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user(); // viene del guard JWT

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if (($user->rol ?? null) !== 'ADMIN') {
            return response()->json(['message' => 'Forbidden (ADMIN only)'], 403);
        }

        return $next($request);
    }
}
<?php

namespace App\Http\Middleware;

use App\Http\Resources\AuthResource;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');

        if ($authHeader && strpos($authHeader, 'Bearer ') === 0) {
            $token = str_replace('Bearer ', '', $authHeader);
            $tokenInstance = \Laravel\Sanctum\PersonalAccessToken::findToken($token);

            if ($tokenInstance && $tokenInstance->can('access-scope')) {
                return $next($request);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Unauthorized. No valid token provided.',
            'data' => null
        ], 401);
    }
}

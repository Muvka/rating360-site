<?php

namespace App\Http\Middleware;

use Closure;

class TokenAuth
{
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();

        if (! config('app.api_token') || $token !== config('app.api_token')) {
            return response()->json(['message' => 'Неверный токен'], 401);
        }

        return $next($request);
    }
}

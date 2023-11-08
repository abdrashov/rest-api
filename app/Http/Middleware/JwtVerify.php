<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtVerify extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // Attempt to parse and authenticate the JWT token.
            JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                // If the token is invalid, throw a validation exception with an error message.
                return throw ValidationException::withMessages([
                    'message' => __('jwt.invalid')
                ]);
            }

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                // If the token is expired, throw a validation exception with an error message.
                return throw ValidationException::withMessages([
                    'message' => __('jwt.expired')
                ]);
            }

            // If the token is not found or has other issues, throw a validation exception.
            return throw ValidationException::withMessages([
                'message' => __('jwt.not_found')
            ]);
        }

        // If the token is valid, continue with the request.
        return $next($request);
    }
}

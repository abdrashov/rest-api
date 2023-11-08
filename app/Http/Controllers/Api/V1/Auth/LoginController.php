<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\JwtController;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends JwtController
{
    /**
     * Attempt user login and generate an access token on success.
     *
     * @param \App\Http\Requests\Api\V1\Auth\LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(LoginRequest $request)
    {
        // Attempt to authenticate the user with the provided credentials.
        if (!$token = Auth::attempt($request->only('name', 'password'))) {
            // If authentication fails, throw a validation exception with an error message.
            throw ValidationException::withMessages(['name' => __('auth.password')]);
        }

        // Return a JSON response with the generated access token and its properties.
        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => config('jwt.ttl') * 60 // seconds converted to minutes
        ]);
    }
}

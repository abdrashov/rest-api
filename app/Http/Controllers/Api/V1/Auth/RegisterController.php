<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\JwtController;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends JwtController
{
    /**
     * Store a new user record in the database.
     *
     * @param \App\Http\Requests\Api\V1\Auth\RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RegisterRequest $request)
    {
        // Create a new user record with the provided name, email, and a hashed password.
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => __('success.message', ['attribute' => 'User'])
        ], 201);
    }
}

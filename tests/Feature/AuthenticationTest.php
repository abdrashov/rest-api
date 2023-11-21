<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\User;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test to verify logging in a user with valid credentials
     * @test
     */
    public function it_can_login_a_user_with_valid_credentials()
    {
        $name = Str::random(10);

        // Create a user with a random name and password
        User::factory()->create([
            'name' => $name,
            'password' => Hash::make('password'),
        ]);

        // Login with valid credentials and check the response structure
        $loginData = [
            'name' => $name,
            'password' => 'password',
        ];

        $response = $this->postJson('/api/v1/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'token_type',
                'expires_in',
            ]);
    }

    /**
     * Test to ensure login fails with invalid credentials
     * @test
     */
    public function it_cannot_login_with_invalid_credentials()
    {
        $name = Str::random(10);

        // Create a user with a random name and password
        User::factory()->create([
            'name' => $name,
            'password' => Hash::make('password'),
        ]);

        // Try to login with invalid credentials and check for error response
        $invalidLoginData = [
            'name' => $name,
            'password' => 'wrong_password',
        ];

        $response = $this->postJson('/api/v1/login', $invalidLoginData);

        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'name' => [
                        __('auth.password')
                    ]
                ]
            ]);
    }

    /**
     * Test to ensure successful registration of a new user
     * @test
     */
    public function it_can_register_a_new_user()
    {
        $name = Str::random(10);

        // Register a new user and check if it exists in the database
        $userData = [
            'name' => $name,
            'email' => $name . '@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson('/api/v1/register', $userData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => __('success.message', ['attribute' => 'User'])
            ]);

        $this->assertDatabaseHas('users', [
            'name' => $name,
            'email' => $name . '@example.com',
        ]);
    }
}


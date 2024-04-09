<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test a successful user login response
     * 
     * @return void
     */
    public function test_successful_user_login_response(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'message',
            'token',
            'user' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
            ],
        ]);
    }

    /**
     * Test a failed user login response
     * 
     * @return void
     */
    public function test_failed_user_login_response(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'invalidpassword',
            'password_confirmation' => 'invalidpassword',
        ]);

        $response->assertStatus(401);

        $response->assertJsonStructure([
            'message',
        ]);
    }
}

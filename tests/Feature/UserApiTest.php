<?php

namespace Tests\Feature;

use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_successfully(): void
    {
        Mail::fake();

        $payload = [
            'name' => 'Budi Developer',
            'email' => 'budi.dev@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/auth/register', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Register Successful');

        $this->assertDatabaseHas('users', [
            'email' => 'budi.dev@example.com',
            'name' => 'Budi Developer',
        ]);

        Mail::assertQueued(WelcomeEmail::class, function ($mail) use ($payload) {
            return $mail->hasTo($payload['email']);
        });
    }

    public function test_user_can_login_and_receive_token(): void
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Login successful')
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['access_token'],
                'errors',
            ]);
    }

    public function test_invalid_login_returns_unauthorized(): void
    {
        $user = User::factory()->create([
            'email' => 'invalid@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401)
            ->assertJsonPath('message', 'User Invalid');
    }

    public function test_authenticated_user_can_view_their_profile(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/auth/user/' . $user->id);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'email' => $user->email,
            ])
            ->assertJsonStructure([
                'data' => [
                    'user_id',
                    'full_name',
                    'email',
                    'register_at',
                ],
            ]);
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Logout successful');
    }
}

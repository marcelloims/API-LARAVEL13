<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    protected function authHeaders(User $user): array
    {
        return [
            'Authorization' => 'Bearer ' . $user->createToken('test')->plainTextToken,
        ];
    }

    public function test_authenticated_user_can_fetch_posts(): void
    {
        $user = User::factory()->create();
        Post::create([
            'user_id' => $user->id,
            'title' => 'Belajar Laravel',
            'slug' => 'belajar-laravel',
        ]);

        $response = $this->withHeaders($this->authHeaders($user))
            ->getJson('/api/post/fetch?keyword=laravel&perPage=10');

        $response->assertStatus(200)
            ->assertJsonPath('data.0.title', 'Belajar Laravel');
    }

    public function test_authenticated_user_can_list_posts(): void
    {
        $user = User::factory()->create();
        Post::create([
            'user_id' => $user->id,
            'title' => 'Daftar Post',
            'slug' => 'daftar-post',
        ]);

        $response = $this->withHeaders($this->authHeaders($user))
            ->getJson('/api/post/get');

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Daftar Post']);
    }

    public function test_authenticated_user_can_view_post_detail(): void
    {
        $user = User::factory()->create();
        $post = Post::create([
            'user_id' => $user->id,
            'title' => 'Detail Post',
            'slug' => 'detail-post',
        ]);

        $response = $this->withHeaders($this->authHeaders($user))
            ->getJson('/api/post/detail/' . $post->id);

        $response->assertStatus(200)
            ->assertJsonPath('data.title', 'Detail Post');
    }

    public function test_authenticated_user_can_create_post(): void
    {
        $user = User::factory()->create();

        $response = $this->withHeaders($this->authHeaders($user))
            ->postJson('/api/post/save', [
                'user_id' => $user->id,
                'title' => 'Belajar Laravel 13',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('message', 'Data has been created');

        $this->assertDatabaseHas('posts', [
            'title' => 'Belajar Laravel 13',
            'user_id' => $user->id,
        ]);
    }

    public function test_authenticated_user_can_update_post(): void
    {
        $user = User::factory()->create();
        $post = Post::create([
            'user_id' => $user->id,
            'title' => 'Old Title',
            'slug' => 'old-title',
        ]);

        $response = $this->withHeaders($this->authHeaders($user))
            ->patchJson('/api/post/update/' . $post->id, [
                'user_id' => $user->id,
                'title' => 'Updated Title',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Data has been updated');

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Title',
        ]);
    }

    public function test_authenticated_user_can_delete_post(): void
    {
        $user = User::factory()->create();
        $post = Post::create([
            'user_id' => $user->id,
            'title' => 'Delete Me',
            'slug' => 'delete-me',
        ]);

        $response = $this->withHeaders($this->authHeaders($user))
            ->deleteJson('/api/post/delete/' . $post->id);

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Data has been deleted');

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }
}

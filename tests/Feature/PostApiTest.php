<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_a_list_of_posts(): void
    {
        Post::factory()->count(3)->create();

        $this->getJson('/api/posts')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'content', 'created_at', 'updated_at'],
                ],
            ]);
    }

    public function test_it_returns_404_when_post_is_missing(): void
    {
        $this->getJson('/api/posts/999')
            ->assertNotFound()
            ->assertJson(['error' => '投稿が見つかりません']);
    }

    public function test_authenticated_user_can_create_post_via_api(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->postJson('/api/posts', [
            'title' => 'API Post',
            'content' => 'Created via API',
        ])
            ->assertCreated()
            ->assertJsonPath('data.title', 'API Post');
    }
}

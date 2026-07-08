<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostWebTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_posts_index(): void
    {
        Post::factory()->create(['title' => 'Sample Post']);

        $this->get(route('posts.index'))
            ->assertOk()
            ->assertSee('Sample Post');
    }

    public function test_authenticated_user_can_create_a_post(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('posts.store'), [
                'title' => 'Hello MicroBlog',
                'content' => 'First post body',
            ])
            ->assertRedirect(route('posts.index'));

        $this->assertDatabaseHas('posts', [
            'title' => 'Hello MicroBlog',
            'user_id' => $user->id,
        ]);
    }

    public function test_post_validation_requires_title_and_content(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('posts.create'))
            ->post(route('posts.store'), [])
            ->assertRedirect(route('posts.create'))
            ->assertSessionHasErrors(['title', 'content']);
    }
}

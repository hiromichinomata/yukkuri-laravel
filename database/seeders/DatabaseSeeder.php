<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $tags = collect(['laravel', 'php', 'web'])
            ->map(fn (string $name) => Tag::query()->create(['name' => $name]));

        Post::factory(10)
            ->for($user)
            ->create()
            ->each(function (Post $post) use ($tags): void {
                $post->tags()->attach($tags->random(2));
            });

        User::factory(5)->create();
    }
}

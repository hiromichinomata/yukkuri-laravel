<?php

namespace App\Providers;

use App\Events\PostCreated;
use App\Listeners\SendPostNotification;
use App\Models\Post;
use App\Policies\PostPolicy;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Post::class, PostPolicy::class);

        Event::listen(PostCreated::class, SendPostNotification::class);
    }
}

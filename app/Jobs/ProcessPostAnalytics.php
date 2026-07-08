<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessPostAnalytics implements ShouldQueue
{
    use Queueable;

    public function __construct(public Post $post)
    {
    }

    public function handle(): void
    {
        Log::info('投稿アナリティクス処理中: '.$this->post->id);
    }
}

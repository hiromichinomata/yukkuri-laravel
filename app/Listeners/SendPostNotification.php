<?php

namespace App\Listeners;

use App\Events\PostCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendPostNotification implements ShouldQueue
{
    public function handle(PostCreated $event): void
    {
        Log::info('新規投稿通知: 投稿ID '.$event->post->id);
    }
}

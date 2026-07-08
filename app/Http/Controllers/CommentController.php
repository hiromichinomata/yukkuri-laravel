<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:1000'],
        ]);

        $comment = new Comment($validated);
        $comment->post_id = $post->id;
        $comment->user_id = Auth::id();
        $comment->save();

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'コメントを追加しました！');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        abort_unless($comment->user_id === Auth::id(), 403);

        $post = $comment->post;
        $comment->delete();

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'コメントを削除しました！');
    }
}

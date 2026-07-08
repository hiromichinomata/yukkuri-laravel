<?php

namespace App\Http\Controllers;

use App\Events\PostCreated;
use App\Jobs\ProcessPostAnalytics;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        $posts = Post::with('user')->latest()->paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function create(): View
    {
        return view('posts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        $post = new Post($validated);
        $post->user_id = Auth::id();
        $post->save();

        PostCreated::dispatch($post);
        ProcessPostAnalytics::dispatch($post);

        return redirect()->route('posts.index')->with('success', '投稿が作成されました！');
    }

    public function show(Post $post): View
    {
        $post->load(['user', 'comments.user', 'tags']);

        return view('posts.show', compact('post'));
    }

    public function edit(Post $post): View
    {
        Gate::authorize('update', $post);

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        Gate::authorize('update', $post);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        $post->update($validated);

        return redirect()->route('posts.show', $post)->with('success', '投稿を更新しました！');
    }

    public function destroy(Post $post): RedirectResponse
    {
        Gate::authorize('delete', $post);

        $post->delete();

        return redirect()->route('posts.index')->with('success', '投稿を削除しました！');
    }
}

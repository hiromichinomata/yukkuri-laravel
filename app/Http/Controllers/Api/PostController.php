<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function index(): JsonResponse
    {
        $posts = Post::with('user:id,name')->latest()->paginate(20);

        return response()->json([
            'data' => $posts->items(),
            'meta' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'total' => $posts->total(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        $post = new Post($validated);
        $post->user_id = Auth::id();
        $post->save();

        return response()->json(['data' => $post->load('user:id,name')], 201);
    }

    public function show(int $id): JsonResponse
    {
        $post = Post::with(['user:id,name', 'comments.user:id,name', 'tags'])->find($id);

        if (! $post) {
            return response()->json(['error' => '投稿が見つかりません'], 404);
        }

        return response()->json(['data' => $post]);
    }

    public function update(Request $request, Post $post): JsonResponse
    {
        Gate::authorize('update', $post);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        $post->update($validated);

        return response()->json(['data' => $post->fresh('user:id,name')]);
    }

    public function destroy(Post $post): JsonResponse
    {
        Gate::authorize('delete', $post);

        $post->delete();

        return response()->json(null, 204);
    }
}

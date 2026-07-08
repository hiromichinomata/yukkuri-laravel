@extends('layouts.microblog')

@section('title', $post->title)

@section('content')
    <article class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <h1 class="text-2xl font-semibold text-gray-900">{{ $post->title }}</h1>
        <p class="mt-2 text-sm text-gray-500">
            {{ $post->user->name }} ・ {{ $post->created_at->format('Y-m-d H:i') }}
        </p>

        @if ($post->tags->isNotEmpty())
            <div class="mt-3 flex flex-wrap gap-2">
                @foreach ($post->tags as $tag)
                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-700">#{{ $tag->name }}</span>
                @endforeach
            </div>
        @endif

        <div class="mt-6 whitespace-pre-wrap text-gray-800">{{ $post->content }}</div>

        @can('update', $post)
            <div class="mt-6 flex gap-3">
                <a href="{{ route('posts.edit', $post) }}" class="rounded-md bg-gray-800 px-3 py-2 text-sm text-white hover:bg-gray-700">編集</a>
                <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('削除しますか？')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-md border border-red-300 px-3 py-2 text-sm text-red-700 hover:bg-red-50">削除</button>
                </form>
            </div>
        @endcan
    </article>

    @auth
        @if (Auth::id() !== $post->user_id)
            <div class="mt-4">
                @if (Auth::user()->isFollowing($post->user))
                    <form action="{{ route('users.unfollow', $post->user) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-md border border-gray-300 px-3 py-2 text-sm hover:bg-gray-50">
                            {{ $post->user->name }}さんのフォローを解除
                        </button>
                    </form>
                @else
                    <form action="{{ route('users.follow', $post->user) }}" method="POST">
                        @csrf
                        <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm text-white hover:bg-indigo-500">
                            {{ $post->user->name }}さんをフォロー
                        </button>
                    </form>
                @endif
            </div>
        @endif
    @endauth

    <section class="mt-8">
        <h2 class="mb-4 text-xl font-semibold text-gray-900">コメント</h2>

        <div class="space-y-3">
            @forelse ($post->comments as $comment)
                <div class="rounded-lg border border-gray-200 bg-white p-4">
                    <p class="text-sm text-gray-500">{{ $comment->user->name }} ・ {{ $comment->created_at->diffForHumans() }}</p>
                    <p class="mt-2 text-gray-800">{{ $comment->content }}</p>
                    @if (Auth::id() === $comment->user_id)
                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-600 hover:underline">削除</button>
                        </form>
                    @endif
                </div>
            @empty
                <p class="text-gray-600">まだコメントはありません。</p>
            @endforelse
        </div>

        @auth
            <form action="{{ route('posts.comments.store', $post) }}" method="POST" class="mt-6 space-y-3 rounded-lg border border-gray-200 bg-white p-4">
                @csrf
                <label for="content" class="block text-sm font-medium text-gray-700">コメントを追加</label>
                <textarea
                    id="content"
                    name="content"
                    rows="3"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >{{ old('content') }}</textarea>
                @error('content')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
                <button type="submit" class="rounded-md bg-gray-800 px-3 py-2 text-sm text-white hover:bg-gray-700">送信</button>
            </form>
        @else
            <p class="mt-4 text-sm text-gray-600">
                コメントするには <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">ログイン</a> してください。
            </p>
        @endauth
    </section>
@endsection

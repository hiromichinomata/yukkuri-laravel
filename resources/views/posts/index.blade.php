@extends('layouts.microblog')
@section('title', '投稿一覧')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-900">投稿一覧</h1>
        @auth
            <a href="{{ route('posts.create') }}" class="rounded-md bg-gray-800 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                新規投稿
            </a>
        @endauth
    </div>

    <div class="space-y-4">
        @forelse ($posts as $post)
            <article class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold">
                    <a href="{{ route('posts.show', $post) }}" class="text-gray-900 hover:underline">
                        {{ $post->title }}
                    </a>
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    {{ $post->user->name }} ・ {{ $post->created_at->diffForHumans() }}
                </p>
                <p class="mt-3 text-gray-700">{{ \Illuminate\Support\Str::limit($post->content, 160) }}</p>
            </article>
        @empty
            <p class="text-gray-600">投稿はまだありません。</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $posts->links() }}
    </div>
@endsection

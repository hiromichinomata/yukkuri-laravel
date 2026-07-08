@extends('layouts.microblog')

@section('title', '投稿編集')

@section('content')
    <h1 class="mb-6 text-2xl font-semibold text-gray-900">投稿を編集</h1>

    @if ($errors->any())
        <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-red-800">
            <ul class="list-disc ps-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('posts.update', $post) }}" method="POST" class="space-y-4 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        @csrf
        @method('PUT')
        <div>
            <label for="title" class="mb-1 block text-sm font-medium text-gray-700">タイトル：</label>
            <input
                type="text"
                id="title"
                name="title"
                value="{{ old('title', $post->title) }}"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
        </div>
        <div>
            <label for="content" class="mb-1 block text-sm font-medium text-gray-700">内容：</label>
            <textarea
                id="content"
                name="content"
                rows="8"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >{{ old('content', $post->content) }}</textarea>
        </div>
        <button type="submit" class="rounded-md bg-gray-800 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
            更新する
        </button>
    </form>
@endsection

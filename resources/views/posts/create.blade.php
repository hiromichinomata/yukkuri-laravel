@extends('layouts.microblog')

@section('title', '新規投稿')

@section('content')
    <h1 class="mb-6 text-2xl font-semibold text-gray-900">新規投稿作成</h1>

    @if ($errors->any())
        <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-red-800">
            <ul class="list-disc ps-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('posts.store') }}" method="POST" class="space-y-4 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        @csrf
        <div>
            <label for="title" class="mb-1 block text-sm font-medium text-gray-700">タイトル：</label>
            <input
                type="text"
                id="title"
                name="title"
                value="{{ old('title') }}"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
            @if ($errors->has('title'))
                <span class="mt-1 block text-sm text-red-600">{{ $errors->first('title') }}</span>
            @endif
        </div>
        <div>
            <label for="content" class="mb-1 block text-sm font-medium text-gray-700">内容：</label>
            <textarea
                id="content"
                name="content"
                rows="8"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >{{ old('content') }}</textarea>
            @if ($errors->has('content'))
                <span class="mt-1 block text-sm text-red-600">{{ $errors->first('content') }}</span>
            @endif
        </div>
        <button type="submit" class="rounded-md bg-gray-800 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
            投稿する
        </button>
    </form>
@endsection

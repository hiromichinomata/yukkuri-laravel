@extends('layouts.microblog')

@section('title', $user->name.'のフォロワー')

@section('content')
    <h1 class="mb-4 text-2xl font-semibold">{{ $user->name }}のフォロワー</h1>
    <ul class="space-y-2">
        @forelse ($followers as $follower)
            <li class="rounded-md border border-gray-200 bg-white px-4 py-3">{{ $follower->name }}</li>
        @empty
            <li class="text-gray-600">フォロワーはまだいません。</li>
        @endforelse
    </ul>
    <div class="mt-4">{{ $followers->links() }}</div>
@endsection

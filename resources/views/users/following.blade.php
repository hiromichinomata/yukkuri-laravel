@extends('layouts.microblog')

@section('title', $user->name.'のフォロー中')

@section('content')
    <h1 class="mb-4 text-2xl font-semibold">{{ $user->name }}のフォロー中</h1>
    <ul class="space-y-2">
        @forelse ($following as $followed)
            <li class="rounded-md border border-gray-200 bg-white px-4 py-3">{{ $followed->name }}</li>
        @empty
            <li class="text-gray-600">フォロー中のユーザーはいません。</li>
        @endforelse
    </ul>
    <div class="mt-4">{{ $following->links() }}</div>
@endsection

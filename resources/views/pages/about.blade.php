@extends('layouts.microblog')

@section('title', 'このサイトについて')

@section('content')
    <h1 class="mb-4 text-2xl font-semibold">このサイトについて</h1>
    <p class="leading-relaxed text-gray-700">
        MicroBlog は Laravel 学習用のサンプルアプリケーションです。
        短い投稿の作成・編集、コメント、フォローなど SNS 風の機能を通して、
        MVC・Eloquent・認証・API といった Laravel の基本を学べます。
    </p>
@endsection

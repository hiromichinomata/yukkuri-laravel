@extends('layouts.microblog')

@section('title', 'リアルタイムチャット')

@section('content')
    <h1 class="mb-4 text-2xl font-semibold">リアルタイムチャット</h1>
    <p class="mb-4 text-sm text-gray-600">
        Laravel Echo / Reverb（またはログブロードキャスト）でメッセージを配信します。
        ブロードキャスト設定が `log` の場合は、ブラウザ間の配信は行われずサーバーログに出力されます。
    </p>

    <div id="chat-messages" class="mb-4 h-80 space-y-2 overflow-y-auto rounded-lg border border-gray-200 bg-white p-4"></div>

    <form id="chat-form" class="flex gap-2">
        <input
            id="chat-input"
            type="text"
            class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            placeholder="メッセージを入力..."
            required
        >
        <button type="submit" class="rounded-md bg-gray-800 px-4 py-2 text-sm text-white hover:bg-gray-700">送信</button>
    </form>
@endsection

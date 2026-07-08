<header class="border-b border-gray-200 bg-white">
    <div class="mx-auto flex max-w-3xl items-center justify-between px-4 py-4">
        <a href="{{ route('home') }}" class="text-lg font-semibold text-gray-900">MicroBlog</a>
        <nav class="flex flex-wrap items-center gap-4 text-sm">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900">ホーム</a>
            <a href="{{ route('about') }}" class="text-gray-600 hover:text-gray-900">このサイトについて</a>
            <a href="{{ route('posts.index') }}" class="text-gray-600 hover:text-gray-900">投稿一覧</a>
            @auth
                <a href="{{ route('posts.create') }}" class="text-gray-600 hover:text-gray-900">新規投稿</a>
                <a href="{{ route('chat.index') }}" class="text-gray-600 hover:text-gray-900">チャット</a>
                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">ダッシュボード</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-gray-600 hover:text-gray-900">ログアウト</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">ログイン</a>
                <a href="{{ route('register') }}" class="rounded-md bg-gray-900 px-3 py-1.5 text-white hover:bg-gray-700">登録</a>
            @endauth
        </nav>
    </div>
</header>

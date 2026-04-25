---
title: "第8章: 入力フォームとバリデーション"
free: false
---

## 8.1 フォーム作成とCSRF対策の基礎

**ゆっくり霊夢：**  
「みんな、まずはフォーム作成の基本から始めるわ。  
Laravelでは、Bladeテンプレートを使って簡単にフォームを作成できるのよ。」

**ゆっくり魔理沙：**  
「その通りだぜ！ただし、フォーム作成で忘れてはいけないのがCSRF対策だな。  
CSRF（クロスサイトリクエストフォージェリ）攻撃からアプリケーションを守るため、Laravelは自動的にCSRFトークンを生成してくれるんだ。」

**ゆっくり霊夢：**  
「実際のフォームでは、`@csrf`ディレクティブを使ってトークンを埋め込むの。  
例えば、投稿作成フォームはこんな感じになるわ。」

*【サンプルコード：フォーム作成とCSRF対策】*

```blade
<!-- resources/views/posts/create.blade.php -->
@extends('layouts.app')

@section('title', '新規投稿')

@section('content')
    <h1>新規投稿作成</h1>

    <form action="{{ route('posts.store') }}" method="POST">
        @csrf
        <div>
            <label for="title">タイトル：</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}">
        </div>
        <div>
            <label for="content">内容：</label>
            <textarea id="content" name="content">{{ old('content') }}</textarea>
        </div>
        <button type="submit">投稿する</button>
    </form>
@endsection
```

**ゆっくり魔理沙：**  
「この例では、`@csrf`を記述することで、フォーム送信時にCSRFトークンが自動的に含まれるようになっているぜ。  
これにより、不正なリクエストからアプリケーションを保護できるんだな！」

---

## 8.2 リクエストバリデーションの仕組みとカスタマイズ

**ゆっくり霊夢：**  
「次は、リクエストバリデーションについて学ぶわ。  
フォームから送信されたデータが正しいかどうかをチェックするのは、とても大事なポイントよ。」

**ゆっくり魔理沙：**  
「Laravelでは、コントローラ内で簡単にバリデーションを実装できるんだ。例えば、投稿作成時の処理では、`validate()`メソッドを使って入力内容を検証するぜ！」

*【サンプルコード：バリデーションの実装】*

```php
// app/Http/Controllers/PostController.php

public function store(Request $request)
{
    // バリデーションルールを定義
    $validated = $request->validate([
        'title'   => 'required|string|max:255',
        'content' => 'required|string',
    ]);

    // バリデーションを通過したら投稿を保存
    $post = new Post($validated);
    $post->user_id = auth()->id();
    $post->save();

    return redirect()->route('posts.index')->with('success', '投稿が作成されました！');
}
```

**ゆっくり霊夢：**  
「また、必要に応じてカスタムバリデーションルールを作成したり、エラーメッセージを変更することもできるわ。  
これにより、ユーザーにとってわかりやすいフィードバックを提供できるのよ。」

**ゆっくり魔理沙：**  
「例えば、`resources/lang/ja/validation.php`を編集することで、日本語のエラーメッセージを細かく設定できるぜ！  
こうしたカスタマイズは、ユーザーエクスペリエンスの向上に大きく寄与するんだな。」

---

## 8.3 エラーメッセージの表示とユーザーへのフィードバック

**ゆっくり霊夢：**  
「最後に、バリデーションエラーが発生したときに、ユーザーへ適切なフィードバックを表示する方法を見ていくわ。  
Bladeテンプレート内でエラーメッセージを表示するのはとても簡単よ。」

**ゆっくり魔理沙：**  
「例えば、先ほどの投稿作成フォームにエラー表示を追加すると、こんな感じになるぜ！」

*【サンプルコード：エラーメッセージの表示】*

```blade
<!-- resources/views/posts/create.blade.php -->
@extends('layouts.app')

@section('title', '新規投稿')

@section('content')
    <h1>新規投稿作成</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('posts.store') }}" method="POST">
        @csrf
        <div>
            <label for="title">タイトル：</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}">
        </div>
        <div>
            <label for="content">内容：</label>
            <textarea id="content" name="content">{{ old('content') }}</textarea>
        </div>
        <button type="submit">投稿する</button>
    </form>
@endsection
```

**ゆっくり霊夢：**  
「この例では、`$errors->any()`でエラーが存在するかをチェックし、エラーメッセージのリストを表示しているの。  
こうすることで、ユーザーはどこに入力ミスがあったのかすぐに確認できるわ。」

**ゆっくり魔理沙：**  
「さらに、各フォームフィールドの近くにエラーを個別に表示することも可能だぜ。  
例えば、`$errors->first('title')`を使って、タイトルに関する最初のエラーメッセージを表示することができるんだな！」

```blade
<div>
    <label for="title">タイトル：</label>
    <input type="text" id="title" name="title" value="{{ old('title') }}">
    @if ($errors->has('title'))
        <span class="error">{{ $errors->first('title') }}</span>
    @endif
</div>
```

**ゆっくり霊夢：**  
「このように、ユーザーに分かりやすいフィードバックを提供することで、より使いやすいフォームが実現できるわね。  
エラーメッセージの見た目や位置も工夫して、ユーザー体験を向上させましょう！」

---

以上で、第8章「入力フォームとバリデーション」の解説は終了！  
ゆっくり霊夢と魔理沙と一緒に、フォーム作成、CSRF対策、リクエストバリデーション、そしてエラーメッセージの表示方法について学んだわ。  
これらの基本をしっかり押さえて、ユーザーに優しいインターフェースを実現していこうね！
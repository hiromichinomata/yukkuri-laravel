---
title: "第7章: サンプルアプリケーション「MicroBlog」の設計と実装"
free: false
---

## 7.1 アプリケーション概要と機能要件の定義

**ゆっくり霊夢：**  
「みんな、これからMicroBlogというサンプルアプリケーションの設計に入るわ。  
MicroBlogは、短い投稿（記事）を作成・編集できる機能に加え、他のユーザーの投稿にコメントしたり、ユーザー同士でフォローし合うSNS風のサービスよ。」

**ゆっくり魔理沙：**  
「その通りだぜ！  
具体的な機能要件はこんな感じになるんだ：  
- **ユーザー認証**：登録、ログイン、ログアウト  
- **投稿機能**：記事の作成、編集、削除、一覧表示  
- **コメント機能**：各投稿に対してコメントを追加  
- **フォロー／フォロワー機能**：ユーザー同士でのフォロー、フォロワー一覧の表示

これらを実装して、実際に動くMicroBlogを作っていくぜ！」

**ゆっくり霊夢：**  
「まずは、全体の設計を頭に入れて、どのテーブルやモデル、コントローラが必要になるかを洗い出すことが大事ね。  
この設計を元に、以降の実装を進めていきましょう！」

---

## 7.2 投稿（記事）の作成・編集機能の実装

**ゆっくり霊夢：**  
「次は、投稿機能の実装よ。  
ユーザーは、自分の投稿を作成・編集できるようにしたいわね。」

**ゆっくり魔理沙：**  
「まずは、投稿用のテーブルやモデルの準備だぜ。  
既にマイグレーションで`posts`テーブルを定義してあると仮定して、次にコントローラを作成するぜ。」

```bash
php artisan make:controller PostController --resource
```

**ゆっくり霊夢：**  
「このリソースコントローラには、`index`、`create`、`store`、`edit`、`update`、`destroy`などのメソッドが用意されるわ。  
たとえば、投稿の作成（store）の処理は以下のように実装できるの。」

*【サンプルコード：投稿作成処理】*

```php
// app/Http/Controllers/PostController.php

public function store(Request $request)
{
    $validated = $request->validate([
        'title'   => 'required|string|max:255',
        'content' => 'required|string',
    ]);

    $post = new Post($validated);
    $post->user_id = auth()->id(); // ログイン中のユーザーIDを設定
    $post->save();

    return redirect()->route('posts.index')->with('success', '投稿が作成されました！');
}
```

**ゆっくり魔理沙：**  
「編集（update）処理も同様だな。ユーザーが自分の投稿を編集できるように、編集フォームから送信されたデータで更新するんだぜ！」  

---

## 7.3 コメント機能の追加とリレーションの構築

**ゆっくり霊夢：**  
「次は、コメント機能よ。  
各投稿に対して、ユーザーがコメントを追加できるようにするため、`comments`テーブルとモデルを用意するの。」

**ゆっくり魔理沙：**  
「まずは、マイグレーションでコメントテーブルを作成しよう。例えば、こんな感じだぜ！」

*【サンプルコード：コメントテーブルのマイグレーション】*

```php
// database/migrations/xxxx_xx_xx_create_comments_table.php

Schema::create('comments', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('post_id');
    $table->unsignedBigInteger('user_id');
    $table->text('content');
    $table->timestamps();

    $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
});
```

**ゆっくり霊夢：**  
「そして、モデル間のリレーションシップも定義するわ。  
例えば、`Post`モデルには以下のように記述するの。」

*【サンプルコード：Postモデルのコメントリレーション】*

```php
// app/Models/Post.php

public function comments()
{
    return $this->hasMany(Comment::class);
}
```

**ゆっくり魔理沙：**  
「逆に、`Comment`モデルでは、どの投稿に属しているかを定義するんだぜ！」

```php
// app/Models/Comment.php

public function post()
{
    return $this->belongsTo(Post::class);
}

public function user()
{
    return $this->belongsTo(User::class);
}
```

**ゆっくり霊夢：**  
「あとは、コメントを保存するためのコントローラやルートを設定して、ユーザーが各投稿に対してコメントを追加できるようにするのよ！」

---

## 7.4 ユーザー間のフォロー／フォロワー機能の実装

**ゆっくり霊夢：**  
「最後は、ユーザー間のフォロー／フォロワー機能の実装について解説するわ。  
これは、ユーザー同士が相互に繋がるSNS機能の一種ね。」

**ゆっくり魔理沙：**  
「フォロー機能は、多対多の自己参照リレーションシップで実現できるんだぜ。  
まず、`follows`という中間テーブルのマイグレーションを作成しよう！」

*【サンプルコード：フォロー用テーブルのマイグレーション】*

```php
// database/migrations/xxxx_xx_xx_create_follows_table.php

Schema::create('follows', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('follower_id');
    $table->unsignedBigInteger('followed_id');
    $table->timestamps();

    $table->unique(['follower_id', 'followed_id']);

    $table->foreign('follower_id')->references('id')->on('users')->onDelete('cascade');
    $table->foreign('followed_id')->references('id')->on('users')->onDelete('cascade');
});
```

**ゆっくり霊夢：**  
「そして、`User`モデルに以下のリレーションシップを定義するのよ。」

*【サンプルコード：Userモデルのフォロー／フォロワー関係】*

```php
// app/Models/User.php

// 自分をフォローしているユーザー（フォロワー）
public function followers()
{
    return $this->belongsToMany(User::class, 'follows', 'followed_id', 'follower_id');
}

// 自分がフォローしているユーザー（フォロー中）
public function following()
{
    return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followed_id');
}
```

**ゆっくり魔理沙：**  
「これで、例えばログイン中のユーザーが特定のユーザーをフォローする、あるいはフォローを解除するといった操作ができるようになるんだ。  
専用のコントローラやルートを作成して、ボタン一つでフォロー／フォロー解除ができるUIを実装すると、ユーザー体験がグッと向上するぜ！」

---

以上で、第7章「サンプルアプリケーション『MicroBlog』の設計と実装」の解説は終了！  
ゆっくり霊夢と魔理沙と一緒に、アプリケーションの概要と機能要件の定義、投稿の作成・編集、コメント機能、そしてユーザー間のフォロー／フォロワー機能について学んだわ。  
この章で学んだ知識をもとに、実際にMicroBlogを作成し、さらに発展させていこうね！
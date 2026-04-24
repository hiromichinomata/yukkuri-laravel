---
title: "第3章: Laravelの基本概念"
free: false
---

## 3.1 MVCアーキテクチャとLaravelの実装

**ゆっくり霊夢：**  
「まずは、MVCアーキテクチャについて説明するわね。MVCとは、Model（モデル）、View（ビュー）、Controller（コントローラ）の3つの主要な部分に分けてアプリケーションを構築する設計思想よ。」

**ゆっくり魔理沙：**  
「その通りだぜ！  
- **Model**：データの取得や保存、ビジネスロジックを担当する。  
- **View**：ユーザーに表示する画面やレイアウト。  
- **Controller**：リクエストを受け取り、適切なModelやViewに振り分ける役割を持つんだ。」

**ゆっくり霊夢：**  
「Laravelでは、このMVCの考え方がしっかり実装されているの。  
たとえば、`app/Models`ディレクトリにはデータベースとのやり取りを担うモデルが置かれ、`resources/views`ディレクトリにはBladeテンプレートを用いたビューがあるのよ。」

**ゆっくり魔理沙：**  
「さらに、コントローラは`app/Http/Controllers`に配置され、ルーティングと連携してリクエストの処理を担うんだ。  
これにより、コードが役割ごとに整理され、保守性も高まるぜ！」

*【サンプルコード：シンプルなモデル例】*

```php
<?php
// app/Models/Post.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'content'];
}
```

---

## 3.2 ルーティングとコントローラの基礎

**ゆっくり霊夢：**  
「次は、ルーティングとコントローラの基本について解説するわ。  
Laravelでは、`routes/web.php`でルート（URLとコントローラの対応関係）を定義するの。」

**ゆっくり魔理沙：**  
「例えば、`/posts`というURLにアクセスすると、PostControllerの`index`メソッドが呼ばれるように設定できるんだ。  
こんな感じのコードになるぜ！」

*【サンプルコード：ルーティング例】*

```php
// routes/web.php
use App\Http\Controllers\PostController;

Route::get('/posts', [PostController::class, 'index']);
```

**ゆっくり霊夢：**  
「そして、実際に処理を記述するのがコントローラよ。  
以下は、`PostController`の例。ユーザーからのリクエストに対して、データを取得してビューに渡す処理が書かれているわ。」

*【サンプルコード：コントローラ例】*

```php
<?php
// app/Http/Controllers/PostController.php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // 一覧表示
    public function index()
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }
}
```

**ゆっくり魔理沙：**  
「このように、ルーティングがURLとコントローラのメソッドを結び付け、コントローラがModelからデータを取得し、ビューへデータを渡す流れになっているんだな。」

---

## 3.3 Bladeテンプレートによるビューの作成

**ゆっくり霊夢：**  
「さて、最後にビューの作成について解説するわ。  
Laravelでは、Bladeというテンプレートエンジンを使って、効率的にビューを作成できるの。」

**ゆっくり魔理沙：**  
「Bladeは、シンプルな構文でPHPのコードをビュー内に埋め込むことができるんだ。  
例えば、変数の出力は`{{ $変数 }}`という形で記述するぜ！」

*【サンプルコード：Bladeテンプレート例】*

```blade
<!-- resources/views/posts/index.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>投稿一覧</title>
</head>
<body>
    <h1>投稿一覧</h1>
    <ul>
        @foreach ($posts as $post)
            <li>{{ $post->title }}</li>
        @endforeach
    </ul>
</body>
</html>
```

**ゆっくり霊夢：**  
「この例では、`@foreach`ディレクティブを使って、`$posts`のデータをループ処理して表示しているの。  
Bladeには、条件分岐やループ、レイアウト継承など、便利な機能がたくさん用意されているわよ。」

**ゆっくり魔理沙：**  
「つまり、Bladeを使うことで、見た目のデザインとロジックが綺麗に分離され、メンテナンスもしやすくなるってわけだな。  
これで、基本的なMVCの仕組みと、ルーティング・コントローラ・ビューの連携が理解できたはずだぜ！」

---

以上で、第3章「Laravelの基本概念」の解説は終了！  
ゆっくり霊夢と魔理沙と一緒に、MVCアーキテクチャ、ルーティングとコントローラ、そしてBladeテンプレートについて学んだわ。  
次の章では、より実践的な機能や具体的なアプリケーション作成に入っていくから、しっかりこの基本を押さえておいてね！
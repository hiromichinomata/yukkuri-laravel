---
title: "第10章: RESTful APIの設計と実装"
free: false
---

## 10.1 APIルーティングとリソースコントローラ

**ゆっくり霊夢：**  
「みんな、今回はRESTful APIの設計について学ぶわ。  
Laravelでは、API専用のルートを`routes/api.php`に定義するの。ここでは、リソースコントローラを使って、効率よくAPIのエンドポイントを作成する方法を説明するわ。」

**ゆっくり魔理沙：**  
「そうだぜ！例えば、投稿（Post）のAPIエンドポイントを作成する場合、リソースコントローラを使うと、標準的なCRUD操作のルートが自動で設定されるんだ。  
まず、以下のコマンドでAPI用のコントローラを作成するぜ！」

```bash
php artisan make:controller Api/PostController --api
```

**ゆっくり霊夢：**  
「この`--api`オプションをつけることで、不要な`create`や`edit`メソッドが除外され、API向けに最適化されたコントローラが生成されるの。  
次に、`routes/api.php`にリソースルートを定義するわ。」

*【サンプルコード：APIルーティング】*

```php
// routes/api.php
use App\Http\Controllers\Api\PostController;

Route::apiResource('posts', PostController::class);
```

**ゆっくり魔理沙：**  
「これで、`GET /api/posts`や`POST /api/posts`など、RESTfulなエンドポイントが自動的に利用できるようになるんだぜ。  
後は、PostController内で各メソッドに対応した処理を記述して、APIとしての機能を実装するだけだな！」

---

## 10.2 API認証（Laravel Sanctum／Passport）の導入

**ゆっくり霊夢：**  
「次は、API認証の導入について説明するわ。  
Laravelでは、シンプルなAPI認証を実現するために、Laravel SanctumやPassportが利用できるの。今回は、軽量なLaravel Sanctumを例にとって解説するわね。」

**ゆっくり魔理沙：**  
「Sanctumは、SPAやモバイルアプリケーション向けにトークンベースの認証を簡単に実装できるんだぜ。  
まずは、以下のコマンドでパッケージをインストールするぜ！」

```bash
composer require laravel/sanctum
```

**ゆっくり霊夢：**  
「インストール後、マイグレーションを実行して必要なテーブルを作成するの。」

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

**ゆっくり魔理沙：**  
「そして、`app/Http/Kernel.php`にある`api`ミドルウェアグループに、`EnsureFrontendRequestsAreStateful`（SPA向け）や`auth:sanctum`を追加して、認証を適用できるようにするんだぜ。  
これで、ユーザーがログインすると、発行されたトークンを使ってAPIにアクセスできるようになるんだな！」

**ゆっくり霊夢：**  
「また、ログイン時にトークンを発行するエンドポイントを用意し、ユーザーが正しい資格情報を入力した場合にトークンを返す処理を実装するの。  
これで、API認証の基本が整うわね！」

---

## 10.3 JSONレスポンスとエラーハンドリング

**ゆっくり霊夢：**  
「最後は、APIのレスポンスとしてJSONを返す方法と、エラーハンドリングについて解説するわ。  
APIは基本的にJSON形式でデータをやり取りするので、きちんとしたレスポンスを返すことが重要なの。」

**ゆっくり魔理沙：**  
「たとえば、投稿の一覧を返す`index`メソッドでは、モデルから取得したデータをそのままJSONに変換して返すんだぜ。  
こんな感じに記述できるな！」

*【サンプルコード：JSONレスポンス】*

```php
// app/Http/Controllers/Api/PostController.php

public function index()
{
    $posts = Post::all();
    return response()->json([
        'data' => $posts
    ], 200);
}
```

**ゆっくり霊夢：**  
「また、エラーハンドリングでは、たとえばリクエストの検証に失敗した場合や、リソースが見つからなかった場合に、適切なHTTPステータスコードとエラーメッセージを返すことが大切よ。」

**ゆっくり魔理沙：**  
「例えば、特定の投稿が存在しない場合、404エラーを返す処理はこんな風になるぜ！」

*【サンプルコード：エラーハンドリング例】*

```php
public function show($id)
{
    $post = Post::find($id);

    if (!$post) {
        return response()->json([
            'error' => '投稿が見つかりません'
        ], 404);
    }

    return response()->json([
        'data' => $post
    ], 200);
}
```

**ゆっくり霊夢：**  
「このように、適切なHTTPステータスコードを返すことで、クライアント側がエラーの内容を正しく把握できるわ。  
エラーハンドリングの工夫は、APIの信頼性向上につながる重要なポイントね！」

**ゆっくり魔理沙：**  
「これで、RESTful APIの基本設計、認証、そしてレスポンスとエラーハンドリングの実装方法が理解できたな。  
Laravelを使えば、シンプルでパワフルなAPIが簡単に構築できるんだぜ！」

---

以上で、第10章「RESTful APIの設計と実装」の解説は終了！  
ゆっくり霊夢と魔理沙と一緒に、APIルーティングとリソースコントローラ、API認証の導入、そしてJSONレスポンスとエラーハンドリングについて学んだわ。  
これらの知識を活用して、次は実際のアプリケーションにAPIを実装していこうね！
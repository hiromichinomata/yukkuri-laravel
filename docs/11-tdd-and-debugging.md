---
title: "第11章: テスト駆動開発（TDD）とデバッグ"
free: false
---

## 11.1 PHPUnitによるユニットテストの基礎

**ゆっくり霊夢：**  
「みんな、テストはコードの品質を保つためにとても重要よ。  
まずは、PHPUnitを使ったユニットテストの基本について学ぶわね！」

**ゆっくり魔理沙：**  
「そうだぜ！Laravelでは、`php artisan make:test`コマンドを使って簡単にテストファイルを作成できるんだ。  
例えば、単体テストを作成する場合、以下のコマンドを実行してみようぜ！」

```bash
php artisan make:test ExampleTest --unit
```

**ゆっくり霊夢：**  
「生成されたテストファイルは`tests/Unit/ExampleTest.php`に配置されるの。  
中身はこんな感じよ。例えば、シンプルなメソッドのテストをしてみるわね。」

*【サンプルコード：ExampleTest.php】*

```php
<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /** @test */
    public function it_adds_two_numbers_correctly()
    {
        $result = 2 + 3;
        $this->assertEquals(5, $result);
    }
}
```

**ゆっくり魔理沙：**  
「このテストは、2+3が5になることを確認しているだけだが、こうした基本的なテストから始めるんだな。  
テストを実行するには、以下のコマンドを使うぜ！」

```bash
vendor/bin/phpunit
```

---

## 11.2 Featureテストでアプリケーション全体を検証

**ゆっくり霊夢：**  
「次は、Featureテストについて学ぶわ。  
Featureテストは、実際のHTTPリクエストをシミュレートして、アプリケーション全体の動作を検証するのに使われるのよ。」

**ゆっくり魔理沙：**  
「例えば、あるルートが正しくレスポンスを返すかどうかをテストする場合、以下のように記述できるんだぜ！」

*【サンプルコード：Featureテスト】*

```php
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_a_list_of_posts()
    {
        // ここで、必要に応じてファクトリを使いテストデータを作成
        \App\Models\Post::factory()->count(3)->create();

        $response = $this->getJson('/api/posts');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'title', 'content', 'created_at', 'updated_at']
                     ]
                 ]);
    }
}
```

**ゆっくり霊夢：**  
「このテストでは、`/api/posts`エンドポイントが正常に動作し、期待したJSON構造を返すかどうかをチェックしているわ。  
Featureテストは、実際のユーザーの動作に近い形でアプリケーション全体を検証できるのが魅力ね！」

---

## 11.3 Tinkerやログ機能を用いたデバッグ技法

**ゆっくり霊夢：**  
「最後に、デバッグ技法について解説するわ。  
開発中は、Tinkerやログ機能を使って、リアルタイムにコードの動作を確認するのが大切よ。」

**ゆっくり魔理沙：**  
「まず、TinkerはLaravelのREPL環境で、以下のコマンドで起動できるんだぜ！」

```bash
php artisan tinker
```

**ゆっくり霊夢：**  
「Tinkerを使えば、モデルのメソッドを実行したり、データベースの状態を確認したりできるの。  
例えば、`User::all()`と入力するだけで、全ユーザーの一覧を確認できるわね！」

**ゆっくり魔理沙：**  
「そして、ログ機能も重要だぜ。Laravelでは、`Log`ファサードを使ってログを記録できる。  
例えば、以下のように記述すると、エラー情報などをログに残すことができるんだ！」

*【サンプルコード：ログ記録例】*

```php
use Illuminate\Support\Facades\Log;

public function store(Request $request)
{
    try {
        // 処理
    } catch (\Exception $e) {
        Log::error('投稿保存時のエラー: '.$e->getMessage());
        return response()->json(['error' => '保存に失敗しました'], 500);
    }
}
```

**ゆっくり霊夢：**  
「このように、適切なログ出力を実装することで、問題発生時の原因追及がスムーズになるわ。  
Tinkerとログ機能を併用して、効率よくデバッグしていきましょう！」

**ゆっくり魔理沙：**  
「これで、PHPUnitによるユニットテスト、Featureテスト、そしてTinkerやログを用いたデバッグ技法が理解できたな。  
テスト駆動開発（TDD）を実践すれば、安心してアプリケーションを拡張できるから、ぜひ取り入れてくれよな！」

---

以上で、第11章「テスト駆動開発（TDD）とデバッグ」の解説は終了！  
ゆっくり霊夢と魔理沙と一緒に、ユニットテスト、Featureテスト、そしてデバッグ技法について学んだわ。  
これらの知識を活用して、堅牢でバグの少ないアプリケーション開発を目指していきましょう！
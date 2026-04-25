---
title: "第6章: データベースとEloquent ORM"
free: false
---

## 6.1 マイグレーションでテーブルを定義する

**ゆっくり霊夢：**  
「まずは、マイグレーションについて説明するわ。  
Laravelでは、マイグレーションを使ってデータベースのテーブル構造をバージョン管理できるの。  
新しいテーブルを作成する場合は、以下のコマンドを実行してマイグレーションファイルを生成するのよ。」

```bash
php artisan make:migration create_posts_table --create=posts
```

**ゆっくり魔理沙：**  
「その通りだぜ！  
生成されたファイルには、`up()`メソッドと`down()`メソッドがあって、テーブル作成と削除の処理が記述されるんだ。  
例えば、`posts`テーブルの場合はこんな感じになるな！」

*【サンプルコード：マイグレーション例】*

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // 外部キー制約（usersテーブルとの関係）
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
```

**ゆっくり霊夢：**  
「このように、マイグレーションファイルにテーブルの構造を定義して、`php artisan migrate`で実行するだけでデータベースが更新されるのよ！」

---

## 6.2 シーディングとファクトリによるテストデータ作成

**ゆっくり霊夢：**  
「次は、シーディングとファクトリを使ってテストデータを作成する方法について解説するわ。  
これらを使うと、開発中に大量のダミーデータを自動で作成できるの。」

**ゆっくり魔理沙：**  
「まずは、ファクトリの設定だな。  
Laravelでは、各モデルに対応するファクトリを定義して、テストデータを生成する。  
例えば、`Post`モデルのファクトリは以下のように作成するぜ！」

*【サンプルコード：PostFactory】*

```php
<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'title'   => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'user_id' => User::factory(), // ユーザーも同時に生成
        ];
    }
}
```

**ゆっくり霊夢：**  
「ファクトリが用意できたら、シーダーを使って実際にデータベースにデータを投入するの。  
例えば、`database/seeders/DatabaseSeeder.php`に以下のように記述するわ。」

*【サンプルコード：DatabaseSeeder】*

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 10件の投稿データを生成する
        Post::factory(10)->create();
    }
}
```

**ゆっくり魔理沙：**  
「シーダーを実行するには、下記のコマンドを使うんだぜ！」

```bash
php artisan db:seed
```

**ゆっくり霊夢：**  
「これで、開発環境に手軽にテストデータが投入できるようになるの。  
ファクトリとシーダーをうまく組み合わせることで、複雑なデータの関係も再現できるわね！」

---

## 6.3 モデル間のリレーションシップ（1対多、N対Nなど）

**ゆっくり霊夢：**  
「最後は、Eloquent ORMの強みでもある、モデル間のリレーションシップについて解説するわ。  
これにより、複数のテーブル間の関連性を簡単に扱えるの。」

**ゆっくり魔理沙：**  
「例えば、1対多のリレーションシップとして、`User`モデルと`Post`モデルの関係が挙げられるな。  
ユーザーは複数の投稿を持つので、`User`モデルには`hasMany`、`Post`モデルには`belongsTo`を定義するぜ！」

*【サンプルコード：1対多のリレーションシップ】*

```php
// app/Models/User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    // ユーザーは複数の投稿を持つ
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
```

```php
// app/Models/Post.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // 投稿は1人のユーザーに属する
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

**ゆっくり霊夢：**  
「また、N対Nのリレーションシップもとても便利よ。  
例えば、投稿に複数のタグを付ける場合、`Post`と`Tag`は多対多の関係になるの。  
この場合、中間テーブルを用意して、両モデルに`belongsToMany`を定義するのよ。」

*【サンプルコード：N対Nのリレーションシップ】*

```php
// app/Models/Post.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
```

```php
// app/Models/Tag.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}
```

**ゆっくり魔理沙：**  
「このように、Eloquent ORMを使うと、リレーションシップが簡単に定義でき、直感的にデータの操作ができるようになるんだぜ！  
例えば、あるユーザーの全投稿を取得する場合は、以下のように記述できるぜ！」

```php
$user = User::find(1);
$posts = $user->posts; // ユーザー1の全投稿を取得
```

**ゆっくり霊夢：**  
「これで、マイグレーションでのテーブル定義、シーディングとファクトリによるテストデータの作成、そしてモデル間のリレーションシップについての基本が理解できたわね。  
Eloquent ORMの機能を活用して、効率的にデータ操作を行い、より複雑なアプリケーションを作っていきましょう！」

---

以上で、第6章「データベースとEloquent ORM」の解説は終了！  
ゆっくり霊夢と魔理沙と一緒に、Laravelのマイグレーション、シーディング、ファクトリ、そしてリレーションシップについて学んだわ。  
次章では、さらに実践的なアプリケーションの機能追加に進むので、しっかりと基礎を押さえておいてね！
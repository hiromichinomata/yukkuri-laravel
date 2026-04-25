---
title: "第2章: Laravel開発環境の構築"
free: false
---

## 2.1 Composerコンテナを使ったLaravelプロジェクトの作成

**ゆっくり霊夢：**  
「みんな、まずはLaravelプロジェクトの作成から始めるわよ。  
今回は**ローカルにPHPを入れず**、Docker Compose上のComposerを使って進めるの。」

**ゆっくり魔理沙：**  
「その通りだぜ！  
まずはDocker Desktopを起動して、プロジェクト直下に以下の`compose.yaml`を用意しよう。」

```yaml
services:
  php:
    image: php:8.5-cli
    working_dir: /workspace
    volumes:
      - ./:/workspace
    command: sh -lc "tail -f /dev/null"

  composer:
    image: composer:2
    working_dir: /workspace
    volumes:
      - ./:/workspace
    entrypoint: ["composer"]
```

**ゆっくり霊夢：**  
「準備できたら、まずDockerとComposerコンテナが使えるか確認してね。」

```bash
# ここは yukkuri-laravel/ （リポジトリルート）で実行
docker compose version
docker compose run --rm composer --version
```

**ゆっくり魔理沙：**  
「この本は**Laravel 13（安定版）**前提だから、  
`composer create-project` で13系を明示して作るのが安全だぜ！」

**ゆっくり魔理沙：**  
「次に、Laravelプロジェクトの作成だ。  
以下のコマンドを使えば、**Laravel 13系**のプロジェクトが作成されるぜ！」

```bash
# ここは yukkuri-laravel/ （リポジトリルート）で実行
docker compose run --rm composer create-project laravel/laravel:^13.0 my_laravel_app
```

**ゆっくり霊夢：**  
「`my_laravel_app`の部分は、自分のプロジェクト名に変更してね。  
これで、必要なパッケージが全てインストールされ、プロジェクトの基盤が整うの。」

---

## 2.2 Laravel Sail（Docker）によるローカル開発環境構築

**ゆっくり霊夢：**  
「Laravelの開発を快適に進めるためには、ローカル開発環境の構築が大切よ。  
ここでは、現在の標準的な方法として**Laravel Sail（Docker）**を紹介するわ。」

**ゆっくり魔理沙：**  
「Sailを使う前に、`docker --version` と `docker info` を実行して、  
**Dockerデーモンが起動していること**を確認しておくんだぜ。  
`Cannot connect to the Docker daemon` と表示される場合は、Docker Desktopを起動してから再実行しよう！」

**ゆっくり霊夢：**  
「準備ができたら、プロジェクト直下で以下を実行するわ。」

```bash
# ここは yukkuri-laravel/ （リポジトリルート）で実行
docker compose run --rm -w /workspace/my_laravel_app composer require laravel/sail --dev
docker compose run --rm -w /workspace/my_laravel_app php php artisan sail:install
cp my_laravel_app/.env.example my_laravel_app/.env

# ここからは my_laravel_app/ で実行
cd my_laravel_app
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
```

**ゆっくり魔理沙：**  
「`WWWUSER` と `WWWGROUP` は、最初から `my_laravel_app/.env.example` に入れておくと毎回の追記が不要だぜ。  
例えば macOS なら `WWWUSER=502`、`WWWGROUP=20` を設定しておけば、Sail起動時の警告を防げるんだ。  
Linux環境では `id -u` と `id -g` の結果を設定すればOKだぜ。」

**ゆっくり霊夢：**  
「これで、Dockerコンテナ内でLaravelアプリケーションが動き出すの。  
ブラウザで `http://localhost` にアクセスしてトップページが表示されれば成功よ。」

**ゆっくり魔理沙：**  
「フロントエンド資産をビルドする場合は、Node.js / npm も必要になるぜ。  
Sail経由なら次のコマンドで実行できるんだ！」

```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

**ゆっくり霊夢：**  
「最後に、次のコマンドで起動確認しておくと安心よ。」

```bash
# ここは yukkuri-laravel/ （リポジトリルート）で実行
docker compose run --rm php php -v

# ここは my_laravel_app/ で実行
./vendor/bin/sail up -d
./vendor/bin/sail artisan about
```

---

## 2.3 初期設定とディレクトリ構成の理解

**ゆっくり霊夢：**  
「Laravelプロジェクトが作成できたら、次は初期設定とディレクトリ構成を理解するわ。  
プロジェクトのルートディレクトリには、たくさんのファイルやフォルダがあるけど、それぞれ役割が決まっているの。」

**ゆっくり魔理沙：**  
「例えば、`app`ディレクトリには、アプリケーションのコアとなるクラスやビジネスロジックが入っている。  
`config`ディレクトリには設定ファイルが集まっていて、`routes`ディレクトリにはWebルーティングやAPIルーティングの定義があるぜ！」

**ゆっくり霊夢：**  
「`resources/views`ディレクトリには、Bladeテンプレートファイルが配置されているし、  
`public`ディレクトリは、公開用のファイル（CSS、JavaScript、画像など）が置かれる場所よ。」

**ゆっくり魔理沙：**  
「その他にも、環境変数を管理する`.env`ファイルや、アプリケーションのエントリーポイントとなる`index.php`が`public`にあるなど、各ディレクトリの役割を把握しておくことが、後々の開発で大いに役立つぜ！」

**ゆっくり霊夢：**  
「実際にプロジェクトの構造を確認しながら、各ファイルやディレクトリの意味を理解していこうね。  
この基礎をしっかり固めることで、次のステップに進むときもスムーズになるわ。」

---

以上で、第2章の内容は完了！  
ゆっくり霊夢と魔理沙と一緒に、Composerの導入からローカル環境の整備、そしてLaravelプロジェクトの初期設定まで進めたわ。  
次章では、Laravelの基本概念や実際のコード作成に入っていくので、引き続き楽しく学んでいこうね！
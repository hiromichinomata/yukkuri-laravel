# yukkuri-laravel

ゆっくり解説形式の Laravel 13 学習用リポジトリです。`docs/` の各章に対応するサンプル実装（MicroBlog）を含みます。

## セットアップ

```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail npm run dev
```

ブラウザで http://localhost を開きます。

シード済みユーザー例:

- email: `test@example.com`
- password: `password`

## 主なルート

| パス | 内容 |
| --- | --- |
| `/about` | 静的ページ |
| `/posts` | 投稿一覧 |
| `/register` / `/login` | Breeze 認証 |
| `/api/posts` | 投稿 API |
| `/chat` | リアルタイムチャット画面（要ログイン） |

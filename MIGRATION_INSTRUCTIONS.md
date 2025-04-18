# マイグレーション手順

## 概要
このドキュメントでは、LiveAI.jpのデータベースマイグレーションを実行する手順について説明します。

## マイグレーションの実行

サーバー上で以下のコマンドを実行してください：

```bash
cd /path/to/liveaijp
php artisan migrate
```

## 特定のマイグレーションの確認

特定のマイグレーションの状態を確認するには：

```bash
php artisan migrate:status
```

## トラブルシューティング

マイグレーションに失敗した場合は、以下のコマンドでキャッシュをクリアしてから再試行してください：

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan migrate
```

## 最近追加されたマイグレーション

- `2025_04_18_000000_create_chat_request_counts_table.php` - チャットリクエスト数を追跡するためのテーブル

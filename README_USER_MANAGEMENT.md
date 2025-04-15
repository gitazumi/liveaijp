# LiveAI.jp ユーザーアカウント管理手順

このドキュメントでは、LiveAI.jpのユーザーアカウント管理に関する手順を説明します。

## 実装済みの変更

以下の変更は既に実装され、本番環境に適用されています：

1. ログインページからGoogleログイン機能の削除
2. 問い合わせフォームの改善：
   - 送信ボタンのテキスト色を黒に変更
   - 送信機能の修復（reCAPTCHA関連の問題を修正）
   - フォームの横幅を広げる
3. トップページの「無料体験する」ボタンに正しいリンク（https://liveai.jp/register）を追加

## ユーザーアカウント管理

ユーザーアカウント管理のために、以下の2つの方法を用意しました：

### 方法1: Artisanコマンドを使用する（推奨）

1. 本番サーバーにSSH接続します
2. プロジェクトディレクトリに移動します
3. 以下のコマンドを実行します：

```bash
php artisan adjust:users
```

このコマンドは以下の処理を行います：
- 3つの特定アカウント（admin@gmail.com、app@yotsuyalotus.com、app@akihabarazest.com）以外のすべてのユーザーを削除
- app@yotsuyalotus.comとapp@akihabarazest.comのメール認証を完了させる

確認なしで実行する場合は、`--force`オプションを使用します：

```bash
php artisan adjust:users --force
```

### 方法2: PHPスクリプトを使用する

1. 本番サーバーにSSH接続します
2. プロジェクトディレクトリに移動します
3. 以下のコマンドを実行します：

```bash
cd /path/to/project
php database/scripts/adjust_users.php
```

## 注意事項

- このスクリプトを実行する前に、データベースのバックアップを取ることをお勧めします
- スクリプト実行時に表示される削除対象ユーザーリストを慎重に確認してください
- 3つの特定アカウント（admin@gmail.com、app@yotsuyalotus.com、app@akihabarazest.com）は削除されません

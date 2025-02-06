#!/bin/bash
cd /var/www  # Laravelのプロジェクトフォルダへ移動

# 変更があるか確認
if ! git diff --quiet || ! git diff --cached --quiet; then
    git add .
    git commit -m "Auto commit: $(date '+%Y-%m-%d %H:%M:%S')"
    git push origin main
else
    echo "No changes to commit."
fi

<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;

$keepEmails = [
    'admin@gmail.com',
    'app@yotsuyalotus.com',
    'app@akihabarazest.com'
];

$usersToDelete = User::whereNotIn('email', $keepEmails)->get();

echo "削除するユーザー数: " . $usersToDelete->count() . "\n";
echo "削除するユーザー: " . $usersToDelete->pluck('email')->join(', ') . "\n";

echo "これらのユーザーを削除してもよろしいですか？（yes/no）: ";
$handle = fopen("php://stdin", "r");
$line = trim(fgets($handle));
if ($line !== 'yes') {
    echo "中止しました\n";
    exit;
}

DB::beginTransaction();
try {
    foreach ($usersToDelete as $user) {
        echo "削除中: " . $user->email . "\n";
        $user->delete();
    }
    
    foreach (['app@yotsuyalotus.com', 'app@akihabarazest.com'] as $email) {
        $user = User::where('email', $email)->first();
        if ($user) {
            if (!$user->hasVerifiedEmail()) {
                $user->email_verified_at = now();
                $user->save();
                echo "{$email} のメール認証を完了しました\n";
            } else {
                echo "{$email} は既にメール認証済みです\n";
            }
        } else {
            echo "{$email} が見つかりません\n";
        }
    }
    
    DB::commit();
    echo "正常に完了しました\n";
} catch (\Exception $e) {
    DB::rollback();
    echo "エラーが発生しました: " . $e->getMessage() . "\n";
}

<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

$force = in_array('--force', $argv);

$keepEmails = [
    'admin@gmail.com',
    'app@yotsuyalotus.com',
    'app@akihabarazest.com'
];

try {
    $usersToDelete = User::whereNotIn('email', $keepEmails)->get();
    
    echo "削除するユーザー数: " . $usersToDelete->count() . "\n";
    
    if ($usersToDelete->count() > 0) {
        echo "削除するユーザー: " . $usersToDelete->pluck('email')->join(', ') . "\n";
        
        if (!$force) {
            echo "これらのユーザーを削除してもよろしいですか？（yes/no）: ";
            $handle = fopen("php://stdin", "r");
            $line = trim(fgets($handle));
            if ($line !== 'yes') {
                echo "中止しました\n";
                exit;
            }
        }
        
        DB::beginTransaction();
        
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
    } else {
        echo "削除対象のユーザーはありません\n";
    }
} catch (\Exception $e) {
    if (isset($DB) && DB::transactionLevel() > 0) {
        DB::rollback();
    }
    echo "エラーが発生しました: " . $e->getMessage() . "\n";
}

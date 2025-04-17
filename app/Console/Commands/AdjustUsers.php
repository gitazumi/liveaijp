<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AdjustUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adjust:users {--force : Force deletion without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '指定されたアカウントのみを保持し、他のアカウントを削除します';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $keepEmails = [
            'admin@gmail.com',
            'app@yotsuyalotus.com',
            'app@akihabarazest.com'
        ];

        try {
            $usersToDelete = User::whereNotIn('email', $keepEmails)->get();
            
            $this->info('削除するユーザー数: ' . $usersToDelete->count());
            
            if ($usersToDelete->count() > 0) {
                $this->info('削除するユーザー: ' . $usersToDelete->pluck('email')->join(', '));
                
                if (!$this->option('force') && !$this->confirm('これらのユーザーを削除してもよろしいですか？')) {
                    $this->info('中止しました');
                    return;
                }
                
                DB::beginTransaction();
                
                foreach ($usersToDelete as $user) {
                    $this->info('削除中: ' . $user->email);
                    $user->delete();
                }
                
                foreach (['app@yotsuyalotus.com', 'app@akihabarazest.com'] as $email) {
                    $user = User::where('email', $email)->first();
                    if ($user) {
                        if (!$user->hasVerifiedEmail()) {
                            $user->email_verified_at = now();
                            $user->save();
                            $this->info("{$email} のメール認証を完了しました");
                        } else {
                            $this->info("{$email} は既にメール認証済みです");
                        }
                    } else {
                        $this->error("{$email} が見つかりません");
                    }
                }
                
                DB::commit();
                $this->info('正常に完了しました');
            } else {
                $this->info('削除対象のユーザーはありません');
            }
        } catch (\Exception $e) {
            DB::rollback();
            $this->error('エラーが発生しました: ' . $e->getMessage());
        }
    }
}

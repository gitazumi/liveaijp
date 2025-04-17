<?php


require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Starting command registration fix...\n";

$commandPath = __DIR__.'/app/Console/Commands/AdjustUsers.php';
if (!file_exists($commandPath)) {
    echo "ERROR: Command file does not exist at: $commandPath\n";
    echo "Creating the command file...\n";
    
    if (!is_dir(dirname($commandPath))) {
        mkdir(dirname($commandPath), 0755, true);
    }
    
    $commandContent = <<<'EOT'
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
            if (DB::transactionLevel() > 0) {
                DB::rollback();
            }
            $this->error('エラーが発生しました: ' . $e->getMessage());
        }
    }
}
EOT;
    
    file_put_contents($commandPath, $commandContent);
    echo "Command file created successfully.\n";
} else {
    echo "Command file exists at: $commandPath\n";
    
    $content = file_get_contents($commandPath);
    if (strpos($content, "protected \$signature = 'adjust:users") === false) {
        echo "WARNING: Command signature is not correctly defined in the file.\n";
        echo "Updating the command file...\n";
        
        $commandContent = <<<'EOT'
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
            if (DB::transactionLevel() > 0) {
                DB::rollback();
            }
            $this->error('エラーが発生しました: ' . $e->getMessage());
        }
    }
}
EOT;
        
        file_put_contents($commandPath, $commandContent);
        echo "Command file updated successfully.\n";
    } else {
        echo "Command signature is correctly defined in the file.\n";
    }
}

$kernelPath = __DIR__.'/app/Console/Kernel.php';
$kernelContent = file_get_contents($kernelPath);

if (strpos($kernelContent, 'protected $commands = [') === false) {
    echo "Adding commands array to Kernel.php...\n";
    
    $kernelContent = str_replace(
        'protected function schedule(Schedule $schedule): void',
        "/**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected \$commands = [
        \\App\\Console\\Commands\\AdjustUsers::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule \$schedule): void",
        $kernelContent
    );
    
    file_put_contents($kernelPath, $kernelContent);
    echo "Commands array added to Kernel.php successfully.\n";
} elseif (strpos($kernelContent, 'App\Console\Commands\AdjustUsers') === false) {
    echo "Adding AdjustUsers to commands array in Kernel.php...\n";
    
    $kernelContent = preg_replace(
        '/protected \$commands = \[(.*?)\]/s',
        "protected \$commands = [$1\n        \\App\\Console\\Commands\\AdjustUsers::class,\n    ]",
        $kernelContent
    );
    
    file_put_contents($kernelPath, $kernelContent);
    echo "AdjustUsers added to commands array in Kernel.php successfully.\n";
} else {
    echo "AdjustUsers is already registered in Kernel.php.\n";
}

$providerPath = __DIR__.'/app/Providers/AppServiceProvider.php';
$providerContent = file_get_contents($providerPath);

if (strpos($providerContent, 'App\Console\Commands\AdjustUsers') === false) {
    echo "Adding AdjustUsers to AppServiceProvider.php...\n";
    
    $providerContent = str_replace(
        'public function register()',
        'public function register()',
        $providerContent
    );
    
    $providerContent = str_replace(
        'public function register()
    {',
        'public function register()
    {
        $this->app->singleton(\'command.adjust.users\', function ($app) {
            return new \App\Console\Commands\AdjustUsers;
        });

        $this->commands([
            \'command.adjust.users\',
        ]);',
        $providerContent
    );
    
    file_put_contents($providerPath, $providerContent);
    echo "AdjustUsers added to AppServiceProvider.php successfully.\n";
} else {
    echo "AdjustUsers is already registered in AppServiceProvider.php.\n";
}

echo "\nCommand registration fix complete. Now run the following commands to clear cache:\n";
echo "composer dump-autoload\n";
echo "php artisan clear-compiled\n";
echo "php artisan config:clear\n";
echo "php artisan cache:clear\n";
echo "php artisan view:clear\n";
echo "php artisan route:clear\n";
echo "php artisan optimize\n";
echo "php artisan list | grep adjust\n";
echo "\nThen run the command with:\n";
echo "php artisan adjust:users --force\n";

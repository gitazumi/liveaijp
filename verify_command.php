<?php


require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$commandPath = __DIR__.'/app/Console/Commands/AdjustUsers.php';
echo "1. Command file exists: " . (file_exists($commandPath) ? "YES" : "NO") . "\n";

echo "2. Command class is loaded: " . (class_exists('App\Console\Commands\AdjustUsers') ? "YES" : "NO") . "\n";

$kernelPath = __DIR__.'/app/Console/Kernel.php';
$kernelContent = file_get_contents($kernelPath);
echo "3. Command registered in Kernel: " . (strpos($kernelContent, 'App\Console\Commands\AdjustUsers') !== false ? "YES" : "NO") . "\n";

$providerPath = __DIR__.'/app/Providers/AppServiceProvider.php';
$providerContent = file_get_contents($providerPath);
echo "4. Command registered in AppServiceProvider: " . (strpos($providerContent, 'App\Console\Commands\AdjustUsers') !== false ? "YES" : "NO") . "\n";

echo "5. All available commands:\n";
$commands = $kernel->all();
foreach ($commands as $name => $command) {
    if (strpos($name, 'adjust') !== false) {
        echo "   - $name: " . get_class($command) . "\n";
    }
}

echo "6. Looking for command with 'adjust:users' signature:\n";
$found = false;
foreach ($commands as $name => $command) {
    $reflection = new ReflectionClass($command);
    if ($reflection->hasProperty('signature')) {
        $property = $reflection->getProperty('signature');
        $property->setAccessible(true);
        $signature = $property->getValue($command);
        if ($signature === 'adjust:users' || $signature === 'adjust:users {--force : Force deletion without confirmation}') {
            echo "   Found command '$name' with matching signature: $signature\n";
            $found = true;
        }
    }
}
if (!$found) {
    echo "   No command found with 'adjust:users' signature\n";
}

echo "\n7. Artisan commands in 'adjust' namespace:\n";
passthru('php artisan list | grep adjust');

echo "\nDiagnostic complete. If any checks failed, follow the instructions in the PR to fix the issues.\n";

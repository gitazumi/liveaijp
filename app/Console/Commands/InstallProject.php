<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstallProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:project';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call("migrate:fresh");
        $this->info('Database refreshed successfully.');
        $this->call("db:seed");
        $this->info('New data seeded successfully');
    }
}

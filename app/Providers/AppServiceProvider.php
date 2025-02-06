<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        DB::listen(function ($query) {
            Log::info('SQL Query Executed: ' . $query->sql . ' [' . implode(', ', $query->bindings) . '] (' . $query->time . 'ms)');
        });
    }

    public function register()
    {
        //
    }
}
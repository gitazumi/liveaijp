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
        
        \Illuminate\Auth\Notifications\VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('LiveAI ご登録の確認')
                ->markdown('emails.verification', ['verificationUrl' => $url]);
        });
        
        \App\Models\User::observe(\App\Observers\UserObserver::class);
    }

    public function register()
    {
        $this->app->singleton('command.adjust.users', function ($app) {
            return new \App\Console\Commands\AdjustUsers;
        });

        $this->commands([
            'command.adjust.users',
        ]);
    }
}

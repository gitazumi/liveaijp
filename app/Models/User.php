<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'status',
        'chatbot_token',
        'google_access_token',
        'google_refresh_token',
        'expires_in',        
        'google_id',
        'faq_limit',
        'api_request_limit'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $user->chatbot_token = Str::uuid()->toString();
        });
    }
    
    /**
     * 既存アカウント（2025年4月16日以前に作成）かどうかを判定
     */
    public function isExistingAccount()
    {
        return $this->created_at <= '2025-04-16 23:59:59';
    }
    
    public function chatRequestCounts()
    {
        return $this->hasMany(ChatRequestCount::class);
    }

    /**
     * パスワードリセット通知の送信
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordJapanese($token));
    }
}

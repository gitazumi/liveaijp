<?php

namespace App\Helpers;

use App\Models\Faq;
use App\Models\ChatRequestCount;
use Illuminate\Support\Facades\Auth;

class UsageHelper
{
    /**
     * 利用状況情報を取得する
     */
    public static function getUsageInfo()
    {
        $user = Auth::user();
        $isExistingAccount = $user->isExistingAccount();
        
        $faqCount = Faq::where('user_id', $user->id)->count();
        $faqLimit = $isExistingAccount ? '無制限' : 20;
        
        $chatCount = 0;
        $chatLimit = $isExistingAccount ? '無制限' : 100;
        
        if (!$isExistingAccount) {
            $today = date('Y-m-d');
            $requestCount = ChatRequestCount::where('user_id', $user->id)
                ->where('date', $today)
                ->first();
            
            if ($requestCount) {
                $chatCount = $requestCount->count;
            }
        }
        
        return [
            'faqCount' => $faqCount,
            'faqLimit' => $faqLimit,
            'chatCount' => $chatCount,
            'chatLimit' => $chatLimit,
            'isExistingAccount' => $isExistingAccount
        ];
    }
}

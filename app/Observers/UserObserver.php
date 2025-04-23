<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * ユーザーステータスの変更を監視
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updating(User $user)
    {
        if ($user->isDirty('status') && $user->status === 'inactive') {
        }
    }
}

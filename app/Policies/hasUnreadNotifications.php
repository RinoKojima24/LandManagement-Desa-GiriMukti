<?php

namespace App\Policies;

use Illuminate\Support\Facades\DB;
use App\Models\User;

class hasUnreadNotifications
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        return DB::table('notifications')
            ->where('user_id', auth()->id())
            ->where('is_read', false)
            ->exists();
    }
}

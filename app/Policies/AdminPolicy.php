<?php

namespace App\Policies;

use App\Models\User;

class AdminPolicy
{
    public function __construct()
    {
        //
    }
    /**
     * Create a new policy instance.
     */
   public function isAdmin(User $user): bool
    {
        return $user->role === 'admin';
    }
}

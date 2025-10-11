<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\DB;

class hasUnreadNotifications extends Component
{
    public bool $hasUnread;

    public function __construct()
    {
        // Cek apakah user punya notifikasi belum dibaca
        $this->hasUnread = DB::table('notifications')
            ->where('user_id', auth()->id())
            ->where('is_read', false)
            ->exists();
    }

    public function render(): View|Closure|string
    {
        return view('components.has-unread-notifications');
    }
}

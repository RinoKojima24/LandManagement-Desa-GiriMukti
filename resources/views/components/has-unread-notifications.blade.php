{{-- components/has-unread-notifications.blade.php --}}
<div class="notification-icon">
    <a href="{{ route('notifications.index') }}">
        <i class="fas fa-bell"></i>
        @if($hasUnread)
            <span class="notification-badge"></span>
        @endif
    </a>
</div>

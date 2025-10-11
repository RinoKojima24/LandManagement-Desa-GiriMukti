
<div class="notification-wrapper">
    <button class="btn btn-link notification-bell" id="notificationBell" onclick="toggleNotifications()">
        <i class="fas fa-bell fa-lg"></i>
        <span class="badge badge-danger" id="notifBadge" style="display: none;">0</span>
    </button>

    <div class="notification-dropdown" id="notifDropdown" style="display: none;">
        <div class="dropdown-header">
            <h5>Notifikasi</h5>
            <button class="btn btn-sm btn-link" onclick="markAllRead()">
                Tandai Semua Dibaca
            </button>
        </div>

        <div id="notifList" class="notification-list">
            <div class="text-center py-3">
                <i class="fas fa-spinner fa-spin"></i> Memuat...
            </div>
        </div>

        <div class="dropdown-footer">
            <a href="{{ route('notifications.index') }}">Lihat Semua Notifikasi</a>
        </div>
    </div>
</div>

@push('styles')
<style>
.notification-wrapper {
    position: relative;
    display: inline-block;
}

.notification-bell {
    position: relative;
    color: #333;
}

.notification-bell .badge {
    position: absolute;
    top: -5px;
    right: -5px;
    padding: 3px 6px;
    border-radius: 10px;
    font-size: 10px;
}

.notification-dropdown {
    position: absolute;
    top: 45px;
    right: 0;
    width: 380px;
    max-height: 500px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 1000;
}

.dropdown-header {
    padding: 15px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.dropdown-header h5 {
    margin: 0;
}

.notification-list {
    max-height: 400px;
    overflow-y: auto;
}

.notif-item {
    padding: 15px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: background 0.2s;
}

.notif-item:hover {
    background: #f8f9fa;
}

.notif-item.unread {
    background: #e3f2fd;
}

.notif-title {
    font-weight: bold;
    margin-bottom: 5px;
}

.notif-message {
    font-size: 14px;
    color: #666;
    margin-bottom: 5px;
}

.notif-time {
    font-size: 12px;
    color: #999;
}

.dropdown-footer {
    padding: 10px;
    text-align: center;
    border-top: 1px solid #eee;
}

.dropdown-footer a {
    color: #007bff;
    text-decoration: none;
}
</style>
@endpush

@push('scripts')
<script>
// Load notifications on page load
document.addEventListener('DOMContentLoaded', function() {
    loadNotifications();
    setInterval(loadNotifications, 30000); // Refresh every 30 seconds
});

function loadNotifications() {
    // Update badge count
    fetch('/notifications/count')
        .then(res => res.json())
        .then(data => {
            const badge = document.getElementById('notifBadge');
            if (data.unread_count > 0) {
                badge.textContent = data.unread_count;
                badge.style.display = 'inline';
            } else {
                badge.style.display = 'none';
            }
        });
}

function toggleNotifications() {
    const dropdown = document.getElementById('notifDropdown');

    if (dropdown.style.display === 'none') {
        dropdown.style.display = 'block';
        loadUnreadNotifications();
    } else {
        dropdown.style.display = 'none';
    }
}

function loadUnreadNotifications() {
    fetch('/notifications/unread')
        .then(res => res.json())
        .then(data => {
            displayNotifications(data.data);
        });
}

function displayNotifications(notifications) {
    const list = document.getElementById('notifList');

    if (notifications.length === 0) {
        list.innerHTML = '<div class="text-center py-4 text-muted">Tidak ada notifikasi baru</div>';
        return;
    }

    let html = '';
    notifications.forEach(notif => {
        html += `
            <div class="notif-item ${notif.is_read ? '' : 'unread'}"
                 onclick="openNotification(${notif.id}, '${notif.link_url || ''}')">
                <div class="notif-title">${notif.title}</div>
                <div class="notif-message">${notif.message}</div>
                <div class="notif-time">${timeAgo(notif.created_at)}</div>
            </div>
        `;
    });

    list.innerHTML = html;
}

function openNotification(notifId, linkUrl) {
    fetch(`/notifications/${notifId}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(() => {
        loadNotifications();
        if (linkUrl) {
            window.location.href = linkUrl;
        } else {
            window.location.href = `/notifications/${notifId}`;
        }
    });
}

function markAllRead() {
    fetch('/notifications/read-all', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(() => {
        loadNotifications();
        loadUnreadNotifications();
    });
}

function timeAgo(datetime) {
    const now = new Date();
    const date = new Date(datetime);
    const seconds = Math.floor((now - date) / 1000);

    if (seconds < 60) return 'Baru saja';
    if (seconds < 3600) return Math.floor(seconds / 60) + ' menit lalu';
    if (seconds < 86400) return Math.floor(seconds / 3600) + ' jam lalu';
    return Math.floor(seconds / 86400) + ' hari lalu';
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const wrapper = document.querySelector('.notification-wrapper');
    const dropdown = document.getElementById('notifDropdown');

    if (!wrapper.contains(event.target)) {
        dropdown.style.display = 'none';
    }
});
</script>
@endpush

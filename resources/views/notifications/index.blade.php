@extends('layouts.mobile')

@section('title', 'Notifikasi Saya')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-12">
            {{-- Header Card --}}
            <div class="card shadow-lg border-0 mb-4 animate-slide-down">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="mb-3 mb-md-0">
                            <h2 class="mb-1">
                                <i class="fas fa-bell text-primary"></i> Notifikasi
                            </h2>
                            <p class="text-muted mb-0 small">Kelola semua notifikasi Anda</p>
                        </div>
                        <div class="btn-group-actions">
                            <button class="btn btn-primary btn-sm shadow-sm animate-scale" onclick="markAllRead()">
                                <i class="fas fa-check-double"></i> Tandai Semua Dibaca
                            </button>
                            <button class="btn btn-danger btn-sm shadow-sm animate-scale ml-2" onclick="deleteReadNotifications()">
                                <i class="fas fa-trash"></i> Hapus Yang Sudah Dibaca
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm animate-fade-in">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm animate-fade-in">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            {{-- Notifikasi List --}}
            @if($notifications->count() > 0)
                <div class="row">
                    @foreach($notifications as $index => $notif)
                        <div class="col-md-12 mb-3 animate-slide-up" style="animation-delay: {{ $index * 0.1 }}s">
                            <div class="card notification-card shadow-sm border-0 {{ $notif->is_read ? '' : 'unread-notification' }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="notification-header">
                                            @if(!$notif->is_read)
                                                <span class="badge badge-primary badge-pulse mb-2">
                                                    <i class="fas fa-circle"></i> Baru
                                                </span>
                                            @endif
                                            <h5 class="card-title mb-1">
                                                <i class="fas fa-envelope-open-text text-primary"></i>
                                                {{ $notif->title }}
                                            </h5>
                                        </div>
                                        <div class="notification-time text-right">
                                            <small class="text-muted">
                                                <i class="far fa-clock"></i>
                                                {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>

                                    <p class="card-text text-muted mb-3">{{ $notif->message }}</p>

                                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                                        <div class="notification-meta mb-2 mb-md-0">
                                            <span class="badge badge-secondary badge-pill">
                                                <i class="fas fa-tag"></i> {{ $notif->type }}
                                            </span>
                                        </div>

                                        <div class="notification-actions btn-group-sm">
                                            @if($notif->id)
                                                <a href="{{ route('notifications.show',$notif->id) }}" class="btn btn-sm btn-outline-primary animate-scale">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                            @endif

                                            @if(!$notif->is_read)
                                                <button onclick="markAsRead({{ $notif->id }})" class="btn btn-sm btn-outline-success animate-scale ml-1">
                                                    <i class="fas fa-check"></i> Baca
                                                </button>
                                            @endif

                                            <button onclick="deleteNotification({{ $notif->id }})" class="btn btn-sm btn-outline-danger animate-scale ml-1">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Progress indicator untuk notifikasi belum dibaca --}}
                                @if(!$notif->is_read)
                                    <div class="notification-indicator"></div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-4 animate-fade-in">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="card shadow-lg border-0 animate-fade-in">
                    <div class="card-body">
                        <div class="text-center py-5">
                            <div class="empty-state-icon mb-4">
                                <i class="fas fa-bell-slash fa-4x text-muted"></i>
                            </div>
                            <h4 class="text-muted mb-2">Tidak Ada Notifikasi</h4>
                            <p class="text-muted">Anda tidak memiliki notifikasi saat ini</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Animations */
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    @keyframes scaleHover {
        from {
            transform: scale(1);
        }
        to {
            transform: scale(1.05);
        }
    }

    .animate-slide-down {
        animation: slideDown 0.5s ease-out;
    }

    .animate-slide-up {
        animation: slideUp 0.5s ease-out;
        animation-fill-mode: both;
    }

    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }

    .animate-scale {
        transition: all 0.3s ease;
    }

    .animate-scale:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    /* Card Styles */
    .notification-card {
        border-radius: 12px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .notification-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }

    .unread-notification {
        background: linear-gradient(135deg, #E3F2FD 0%, #ffffff 100%);
        border-left: 4px solid #2196F3;
    }

    .notification-indicator {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #2196F3, #21CBF3);
        animation: pulse 2s ease-in-out infinite;
    }

    /* Badge Styles */
    .badge-pulse {
        animation: pulse 2s ease-in-out infinite;
        box-shadow: 0 0 0 0 rgba(33, 150, 243, 0.7);
    }

    .badge-pill {
        padding: 5px 12px;
        font-weight: 500;
    }

    /* Button Styles */
    .btn-group-actions button {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .notification-actions .btn {
        border-radius: 6px;
        font-weight: 500;
    }

    /* Empty State */
    .empty-state-icon {
        animation: pulse 3s ease-in-out infinite;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .btn-group-actions {
            display: flex;
            flex-direction: column;
            width: 100%;
            margin-top: 10px;
        }

        .btn-group-actions button {
            margin: 5px 0 !important;
            width: 100%;
        }

        .notification-actions {
            width: 100%;
            display: flex;
            justify-content: space-between;
        }

        .notification-actions .btn {
            flex: 1;
            margin: 0 2px !important;
        }
    }

    /* Card Shadow Effects */
    .shadow-sm {
        box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
    }

    .shadow-lg {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
    }

    /* Alert Styles */
    .alert {
        border-radius: 10px;
        border: none;
    }

    /* Typography */
    .card-title {
        font-weight: 600;
        color: #333;
    }

    .card-text {
        line-height: 1.6;
    }

    /* Icon Colors */
    .text-primary {
        color: #2196F3 !important;
    }

    /* Smooth Scrolling */
    html {
        scroll-behavior: smooth;
    }
</style>
@endpush

@push('scripts')
<script>
// Mark single notification as read
function markAsRead(notifId) {
    if (confirm('Tandai notifikasi ini sebagai sudah dibaca?')) {
        fetch(`/notifications/${notifId}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showSuccessToast('Notifikasi ditandai sebagai sudah dibaca');
                setTimeout(() => location.reload(), 1000);
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Terjadi kesalahan. Silakan coba lagi.');
        });
    }
}

// Mark all as read
function markAllRead() {
    if (confirm('Tandai semua notifikasi sebagai sudah dibaca?')) {
        fetch('/notifications/read-all', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showSuccessToast(data.message);
                setTimeout(() => location.reload(), 1000);
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Terjadi kesalahan. Silakan coba lagi.');
        });
    }
}

// Delete notification
function deleteNotification(notifId) {
    if (confirm('Hapus notifikasi ini?')) {
        fetch(`/notifications/${notifId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showSuccessToast('Notifikasi berhasil dihapus');
                setTimeout(() => location.reload(), 1000);
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Terjadi kesalahan. Silakan coba lagi.');
        });
    }
}

// Delete all read notifications
function deleteReadNotifications() {
    if (confirm('Hapus semua notifikasi yang sudah dibaca?')) {
        fetch('/notifications/read/clear', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showSuccessToast(data.message);
                setTimeout(() => location.reload(), 1000);
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Terjadi kesalahan. Silakan coba lagi.');
        });
    }
}

// Success Toast (optional - requires toast library or custom implementation)
function showSuccessToast(message) {
    // If you have a toast library like toastr, use it here
    // Otherwise, you can use a simple alert or create a custom toast
    alert(message);
}
</script>
@endpush
@endsection

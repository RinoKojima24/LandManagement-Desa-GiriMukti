@extends('layouts.mobile')

@section('title', 'Detail Notifikasi')

@push('styles')
<style>
    .notification-wrapper {
        background: #f5f5f5;
        min-height: 100vh;
        padding: 20px 0;
    }

    .notification-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .card-header-custom {
        background: white;
        padding: 20px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .back-btn {
        background: #f3f4f6;
        border: none;
        color: #374151;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
        text-decoration: none;
    }

    .back-btn:hover {
        background: #e5e7eb;
        color: #111827;
    }

    .card-header-custom h1 {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
        color: #111827;
    }

    .card-body-custom {
        padding: 24px;
    }

    .badge-group {
        display: flex;
        gap: 8px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .badge-custom {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
    }

    .badge-unread {
        background: #dbeafe;
        color: #1e40af;
    }

    .badge-read {
        background: #f3f4f6;
        color: #6b7280;
    }

    .badge-type {
        background: #fef3c7;
        color: #92400e;
    }

    .notification-title {
        font-size: 22px;
        font-weight: 600;
        color: #111827;
        margin-bottom: 12px;
        line-height: 1.4;
    }

    .notification-time {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #6b7280;
        font-size: 14px;
        margin-bottom: 20px;
    }

    .notification-message {
        background: #f9fafb;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        margin: 20px 0;
    }

    .notification-message p {
        color: #374151;
        font-size: 15px;
        line-height: 1.6;
        margin: 0;
    }

    .btn-custom {
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        text-decoration: none;
        justify-content: center;
    }

    .btn-primary-custom {
        background: #3b82f6;
        color: white;
        width: 100%;
        margin-top: 16px;
    }

    .btn-primary-custom:hover {
        background: #2563eb;
    }

    .btn-danger-custom {
        background: #ef4444;
        color: white;
        width: 100%;
    }

    .btn-danger-custom:hover {
        background: #dc2626;
    }

    .card-footer-custom {
        padding: 20px 24px;
        background: #f9fafb;
        border-top: 1px solid #e5e7eb;
    }

    @media (max-width: 768px) {
        .notification-wrapper {
            padding: 10px;
        }

        .card-header-custom {
            padding: 16px;
        }

        .card-body-custom {
            padding: 20px;
        }

        .notification-title {
            font-size: 20px;
        }

        .card-footer-custom {
            padding: 16px 20px;
        }
    }

    @media (max-width: 576px) {
        .notification-wrapper {
            padding: 0;
        }

        .notification-card {
            border-radius: 0;
        }
    }

    .btn-loading {
        opacity: 0.6;
        pointer-events: none;
    }
</style>
@endpush

@section('content')
<div class="notification-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                <div class="notification-card">
                    <div class="card-header-custom">
                        <a href="{{ route('notifications.index') }}" class="back-btn">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h1>Detail Notifikasi</h1>
                    </div>

                    <div class="card-body-custom">
                        <div class="badge-group">
                            <span class="badge-custom {{ $notification->is_read ? 'badge-read' : 'badge-unread' }}">
                                {{ $notification->is_read ? 'Sudah Dibaca' : 'Belum Dibaca' }}
                            </span>
                            <span class="badge-custom badge-type">
                                {{ $notification->type }}
                            </span>
                        </div>

                        <h2 class="notification-title">{{ $notification->title }}</h2>

                        <div class="notification-time">
                            <i class="far fa-clock"></i>
                            <span>
                                {{ \Carbon\Carbon::parse($notification->created_at)->format('d M Y, H:i') }}
                                · {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                            </span>
                        </div>

                        <div class="notification-message">
                            <p>{{ $notification->message }}</p>
                        </div>

                        @if($notification->link_url)
                            <a href="{{ $notification->link_url }}" class="btn-custom btn-primary-custom">
                                <i class="fas fa-external-link-alt"></i>
                                Buka Link Terkait
                            </a>
                        @endif
                    </div>

                    <div class="card-footer-custom">
                        <button onclick="deleteNotification({{ $notification->id }})"
                                class="btn-custom btn-danger-custom"
                                id="deleteBtn">
                            <i class="fas fa-trash-alt"></i>
                            Hapus Notifikasi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteNotification(notifId) {
    if (confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')) {
        const deleteBtn = document.getElementById('deleteBtn');
        deleteBtn.classList.add('btn-loading');
        deleteBtn.disabled = true;

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
                window.location.href = '{{ route("notifications.index") }}';
            } else {
                alert('Gagal menghapus notifikasi');
                deleteBtn.classList.remove('btn-loading');
                deleteBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
            deleteBtn.classList.remove('btn-loading');
            deleteBtn.disabled = false;
        });
    }
}
</script>
@endpush

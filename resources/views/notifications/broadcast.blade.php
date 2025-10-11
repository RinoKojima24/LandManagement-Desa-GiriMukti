
@extends('layouts.mobile')

@section('title', 'Broadcast Notifikasi')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4>Kirim Notifikasi Broadcast</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('notifications.broadcast.role') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label>Target Role</label>
                            <select name="role" class="form-control" required>
                                <option value="">-- Pilih Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->role }}">{{ ucfirst($role->role) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Tipe Notifikasi</label>
                            <select name="type" class="form-control" required>
                                <option value="ANNOUNCEMENT">Pengumuman</option>
                                <option value="SYSTEM">Sistem</option>
                                <option value="INFO">Informasi</option>
                                <option value="WARNING">Peringatan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Judul</label>
                            <input type="text" name="title" class="form-control" required maxlength="255">
                        </div>

                        <div class="form-group">
                            <label>Pesan</label>
                            <textarea name="message" class="form-control" rows="5" required></textarea>
                        </div>

                        <div class="form-group">
                            <label>Link URL (Opsional)</label>
                            <input type="url" name="link_url" class="form-control" placeholder="https://example.com">
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-paper-plane"></i> Kirim Broadcast
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

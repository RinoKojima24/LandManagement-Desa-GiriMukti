@extends('layouts.app')

@section('title', 'Detail User - ' . $user->nama_petugas)

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-user-circle"></i> Detail User
                        </h4>
                        <div class="btn-group">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('users.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Alert Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- User Profile Header -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="flex-shrink-0">
                                    <div class="avatar-placeholder bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 80px; height: 80px; font-size: 2rem;">
                                        {{ strtoupper(substr($user->nama_petugas, 0, 1)) }}
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h3 class="mb-1">{{ $user->nama_petugas }}</h3>
                                    <p class="text-muted mb-1">{{ $user->jabatan }}</p>
                                    <div class="d-flex gap-2">
                                        <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'supervisor' ? 'warning' : 'info') }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                        <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="dropdown">
                                        <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-cogs"></i> Aksi
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">
                                                    <i class="fas fa-edit text-warning"></i> Edit User
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('users.toggle-status', $user->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="dropdown-item"
                                                            onclick="return confirm('Apakah Anda yakin ingin {{ $user->is_active ? 'menonaktifkan' : 'mengaktifkan' }} user ini?')">
                                                        <i class="fas fa-{{ $user->is_active ? 'toggle-off text-secondary' : 'toggle-on text-success' }}"></i>
                                                        {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                    </button>
                                                </form>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"
                                                            onclick="return confirm('Apakah Anda yakin ingin menghapus user ini? Aksi ini tidak dapat dibatalkan!')">
                                                        <i class="fas fa-trash"></i> Hapus User
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Information -->
                    <div class="row">
                        <!-- Informasi Dasar -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-info-circle"></i> Informasi Dasar
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold" width="40%">
                                                <i class="fas fa-key text-primary"></i> ID Petugas:
                                            </td>
                                            <td>{{ $user->id }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">
                                                <i class="fas fa-user text-primary"></i> Username:
                                            </td>
                                            <td>{{ $user->username }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">
                                                <i class="fas fa-id-card text-primary"></i> Nama Lengkap:
                                            </td>
                                            <td>{{ $user->nama_petugas }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">
                                                <i class="fas fa-briefcase text-primary"></i> Jabatan:
                                            </td>
                                            <td>{{ $user->jabatan }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">
                                                <i class="fas fa-user-tag text-primary"></i> Role:
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'supervisor' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">
                                                <i class="fas fa-toggle-on text-primary"></i> Status:
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Kontak -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-success text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-address-book"></i> Informasi Kontak
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold" width="40%">
                                                <i class="fas fa-envelope text-success"></i> Email:
                                            </td>
                                            <td>
                                                <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                                    {{ $user->email }}
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">
                                                <i class="fas fa-phone text-success"></i> No. Telepon:
                                            </td>
                                            <td>
                                                @if($user->no_telepon)
                                                    <a href="tel:{{ $user->no_telepon }}" class="text-decoration-none">
                                                        {{ $user->no_telepon }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">Tidak ada</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>

                                    <!-- Quick Contact Actions -->
                                    <div class="mt-3">
                                        <div class="d-grid gap-2">
                                            <a href="mailto:{{ $user->email }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-envelope"></i> Kirim Email
                                            </a>
                                            @if($user->no_telepon)
                                                <a href="tel:{{ $user->no_telepon }}" class="btn btn-outline-success btn-sm">
                                                    <i class="fas fa-phone"></i> Hubungi
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Aktivitas & Timestamp -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-clock"></i> Riwayat Aktivitas
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 text-center">
                                            <div class="border rounded p-3">
                                                <i class="fas fa-calendar-plus text-primary mb-2" style="font-size: 2rem;"></i>
                                                <h6 class="fw-bold">Tanggal Dibuat</h6>
                                                <p class="mb-0">
                                                    {{ $user->created_at ? $user->created_at->format('d F Y') : '-' }}
                                                </p>
                                                <small class="text-muted">
                                                    {{ $user->created_at ? $user->created_at->format('H:i') . ' WIB' : '' }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <div class="border rounded p-3">
                                                <i class="fas fa-edit text-warning mb-2" style="font-size: 2rem;"></i>
                                                <h6 class="fw-bold">Terakhir Diupdate</h6>
                                                <p class="mb-0">
                                                    {{ $user->updated_at ? $user->updated_at->format('d F Y') : '-' }}
                                                </p>
                                                <small class="text-muted">
                                                    {{ $user->updated_at ? $user->updated_at->format('H:i') . ' WIB' : '' }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <div class="border rounded p-3">
                                                <i class="fas fa-sign-in-alt text-success mb-2" style="font-size: 2rem;"></i>
                                                <h6 class="fw-bold">Login Terakhir</h6>
                                                <p class="mb-0">
                                                    {{ $user->last_login ? $user->last_login->format('d F Y') : 'Belum pernah' }}
                                                </p>
                                                <small class="text-muted">
                                                    {{ $user->last_login ? $user->last_login->format('H:i') . ' WIB' : 'login' }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Status Summary -->
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <div class="alert alert-{{ $user->is_active ? 'success' : 'warning' }}" role="alert">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-{{ $user->is_active ? 'check-circle' : 'exclamation-triangle' }} me-2"></i>
                                                    <div>
                                                        <strong>Status Akun:</strong>
                                                        @if($user->is_active)
                                                            Akun ini aktif dan dapat mengakses sistem.
                                                        @else
                                                            Akun ini nonaktif dan tidak dapat mengakses sistem.
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                <a href="{{ route('users.index') }}" class="btn btn-secondary me-md-2">
                                    <i class="fas fa-list"></i> Kembali ke Daftar
                                </a>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning me-md-2">
                                    <i class="fas fa-edit"></i> Edit User
                                </a>
                                <form action="{{ route('users.toggle-status', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-{{ $user->is_active ? 'outline-secondary' : 'outline-success' }} me-md-2"
                                            onclick="return confirm('Apakah Anda yakin ingin {{ $user->is_active ? 'menonaktifkan' : 'mengaktifkan' }} user ini?')">
                                        <i class="fas fa-{{ $user->is_active ? 'toggle-off' : 'toggle-on' }}"></i>
                                        {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

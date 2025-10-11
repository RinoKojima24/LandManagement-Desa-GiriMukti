@extends('layouts.app')

@section('title', 'Daftar User')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Daftar User</h2>
                    <p class="text-muted mb-0">Kelola data pengguna sistem</p>
                </div>
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-lg shadow-sm">
                    <i class="fas fa-plus me-2"></i>Tambah User
                </a>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-0">
                    <!-- Filter Section -->
                    <div class="p-4 bg-light rounded-top-4">
                        <div class="row g-3">
                            <!-- Search Box -->
                            <div class="col-lg-6 col-md-6">
                                <label class="form-label fw-semibold text-dark mb-2">
                                    <i class="fas fa-search me-2"></i>Pencarian
                                </label>
                                <form action="{{ route('users.search') }}" method="GET" class="d-flex gap-2">
                                    <input type="text" name="search" class="form-control form-control-lg"
                                           placeholder="Cari username, nama, email, atau jabatan..."
                                           value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-outline-primary btn-lg px-4">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </form>
                            </div>

                            <!-- Role Filter -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <label class="form-label fw-semibold text-dark mb-2">
                                    <i class="fas fa-filter me-2"></i>Filter Role
                                </label>
                                <form action="{{ route('users.filter') }}" method="GET">
                                    <select name="role" class="form-select form-select-lg" onchange="this.form.submit()">
                                        <option value="">Semua Role</option>
                                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="petugas" {{ request('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                                        <option value="supervisor" {{ request('role') == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                                    </select>
                                </form>
                            </div>

                            <!-- Reset Filter -->
                            <div class="col-lg-3 col-md-2 col-sm-6 d-flex align-items-end">
                                @if(request('search') || request('role'))
                                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-lg w-100">
                                        <i class="fas fa-refresh me-2"></i>Reset
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Alert Messages -->
                    @if(session('success'))
                        <div class="mx-4 mt-4">
                            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mx-4 mt-4">
                            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        </div>
                    @endif

                    <!-- Desktop Table View -->
                    <div class="d-none d-lg-block p-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="fw-semibold">No</th>
                                        <th class="fw-semibold">User Info</th>
                                        <th class="fw-semibold">Contact</th>
                                        <th class="fw-semibold">Role & Status</th>
                                        <th class="fw-semibold">Last Login</th>
                                        <th class="fw-semibold text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $index => $user)
                                        <tr class="border-bottom">
                                            <td class="fw-semibold">{{ $users->firstItem() + $index }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                                         style="width: 45px; height: 45px; font-size: 18px; font-weight: bold;">
                                                        {{ strtoupper(substr($user->nama_petugas, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1 fw-bold">{{ $user->nama_petugas }}</h6>
                                                        <small class="text-muted">{{ $user->username }}</small>
                                                        <br><small class="text-muted">{{ $user->jabatan }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="mb-1">
                                                        <i class="fas fa-envelope text-muted me-2"></i>
                                                        <span class="small">{{ $user->email }}</span>
                                                    </div>
                                                    <div>
                                                        <i class="fas fa-phone text-muted me-2"></i>
                                                        <span class="small">{{ $user->no_telepon ?? '-' }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="mb-2">
                                                    <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'supervisor' ? 'warning' : 'info') }} px-3 py-2">
                                                        <i class="fas fa-user-shield me-1"></i>{{ ucfirst($user->role) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }} px-3 py-2">
                                                        <i class="fas fa-{{ $user->is_active ? 'check-circle' : 'times-circle' }} me-1"></i>
                                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="small {{ $user->last_login ? 'text-dark' : 'text-muted' }}">
                                                    {{ $user->last_login ? $user->last_login->format('d/m/Y H:i') : 'Belum Login' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1 justify-content-center">
                                                    <a href="{{ route('users.show', $user->id) }}"
                                                       class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('users.edit', $user->id) }}"
                                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('users.toggle-status', $user->id) }}"
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                                class="btn btn-sm btn-outline-{{ $user->is_active ? 'secondary' : 'success' }}"
                                                                title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                                onclick="return confirm('Apakah Anda yakin ingin {{ $user->is_active ? 'menonaktifkan' : 'mengaktifkan' }} user ini?')">
                                                            <i class="fas fa-{{ $user->is_active ? 'toggle-off' : 'toggle-on' }}"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('users.destroy', $user->id) }}"
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"
                                                                onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="fas fa-users fa-3x mb-3"></i>
                                                    <h5>Tidak ada data user</h5>
                                                    <p class="mb-0">Silakan tambah user baru atau ubah filter pencarian</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="d-lg-none p-3">
                        @forelse($users as $index => $user)
                            <div class="card mb-3 border shadow-sm">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 d-flex align-items-start mb-3">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                                 style="width: 50px; height: 50px; font-size: 20px; font-weight: bold;">
                                                {{ strtoupper(substr($user->nama_petugas, 0, 1)) }}
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-bold">{{ $user->nama_petugas }}</h6>
                                                <p class="text-muted mb-1 small">@{{ $user->username }}</p>
                                                <p class="text-muted mb-0 small">{{ $user->jabatan }}</p>
                                            </div>
                                            <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Email</small>
                                            <span class="small">{{ $user->email }}</span>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Telepon</small>
                                            <span class="small">{{ $user->no_telepon ?? '-' }}</span>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Role</small>
                                            <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'supervisor' ? 'warning' : 'info') }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Last Login</small>
                                            <span class="small">{{ $user->last_login ? $user->last_login->format('d/m/Y H:i') : 'Belum Login' }}</span>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2 flex-wrap">
                                        <a href="{{ route('users.show', $user->id) }}"
                                           class="btn btn-sm btn-outline-info flex-fill">
                                            <i class="fas fa-eye me-1"></i>Detail
                                        </a>
                                        <a href="{{ route('users.edit', $user->id) }}"
                                           class="btn btn-sm btn-outline-warning flex-fill">
                                            <i class="fas fa-edit me-1"></i>Edit
                                        </a>
                                        <form action="{{ route('users.toggle-status', $user->id) }}"
                                              method="POST" class="flex-fill">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-{{ $user->is_active ? 'secondary' : 'success' }} w-100"
                                                    onclick="return confirm('Apakah Anda yakin ingin {{ $user->is_active ? 'menonaktifkan' : 'mengaktifkan' }} user ini?')">
                                                <i class="fas fa-{{ $user->is_active ? 'toggle-off' : 'toggle-on' }} me-1"></i>
                                                {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('users.destroy', $user->id) }}"
                                              method="POST" class="flex-fill">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger w-100"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                                <i class="fas fa-trash me-1"></i>Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-users fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">Tidak ada data user</h5>
                                <p class="text-muted">Silakan tambah user baru atau ubah filter pencarian</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($users->hasPages())
                        <div class="p-4 pt-0">
                            <div class="d-flex justify-content-center">
                                {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
        transition: background-color 0.15s ease-in-out;
    }

    .btn-group .btn {
        border-radius: 0.375rem !important;
        margin: 0 1px;
    }

    .alert {
        border-radius: 0.75rem;
    }

    .card {
        transition: box-shadow 0.15s ease-in-out;
    }

    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }

        .btn-lg {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .form-control-lg, .form-select-lg {
            padding: 0.5rem 0.75rem;
            font-size: 1rem;
        }
    }
</style>
@endsection

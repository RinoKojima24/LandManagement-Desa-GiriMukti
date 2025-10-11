@extends('layouts.app')

@section('title', 'Edit User - ' . $user->nama_petugas)

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-user-edit"></i> Edit User: {{ $user->nama_petugas }}
                        </h4>
                        <div class="btn-group">
                            <a href="{{ route('users.show', $user->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Lihat Detail
                            </a>
                            <a href="{{ route('users.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Alert Error -->
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Info Update -->
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle"></i>
                        <strong>Catatan:</strong> Kosongkan field password jika tidak ingin mengubah password.
                    </div>

                    <!-- Form -->
                    <form action="{{ route('users.update', $user->id) }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Username -->
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">
                                    Username <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text"
                                           class="form-control @error('username') is-invalid @enderror"
                                           id="username"
                                           name="username"
                                           value="{{ old('username', $user->username) }}"
                                           placeholder="Masukkan username"
                                           required>
                                </div>
                                @error('username')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Password -->
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    Password Baru
                                    <small class="text-muted">(Kosongkan jika tidak ingin mengubah)</small>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           id="password"
                                           name="password"
                                           placeholder="Minimal 8 karakter">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                        <i class="fas fa-eye" id="password-icon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Konfirmasi Password -->
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">
                                    Konfirmasi Password Baru
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password"
                                           class="form-control @error('password_confirmation') is-invalid @enderror"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           placeholder="Ulangi password baru">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                        <i class="fas fa-eye" id="password_confirmation-icon"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Nama Petugas -->
                            <div class="col-md-6 mb-3">
                                <label for="nama_petugas" class="form-label">
                                    Nama Lengkap <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-id-card"></i>
                                    </span>
                                    <input type="text"
                                           class="form-control @error('nama_petugas') is-invalid @enderror"
                                           id="nama_petugas"
                                           name="nama_petugas"
                                           value="{{ old('nama_petugas', $user->nama_petugas) }}"
                                           placeholder="Masukkan nama lengkap"
                                           required>
                                </div>
                                @error('nama_petugas')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                             <!-- No Telepon -->
                            <div class="col-md-6 mb-3">
                                <label for="no_telepon" class="form-label">No. Telepon</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <input type="number"
                                           class="form-control @error('no_telepon') is-invalid @enderror"
                                           id="no_telepon"
                                           name="no_telepon"
                                           value="{{ old('no_telepon', $user->no_telepon) }}"
                                           placeholder="08xxxxxxxxxx">
                                </div>
                                @error('no_telepon')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>


                        </div>

                        <div class="row">
                            <!-- Role -->
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">
                                    Role <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user-tag"></i>
                                    </span>
                                    <select class="form-select @error('role') is-invalid @enderror"
                                            id="role"
                                            name="role"
                                            required>
                                        <option value="">Pilih Role</option>
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="super_admin" {{ old('role', $user->role) == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                        <option value="operator" {{ old('role', $user->role) == 'operator' ? 'selected' : '' }}>Operator</option>
                                        <option value="viewer" {{ old('role', $user->role) == 'viewer' ? 'selected' : '' }}>Viewer</option>
                                    </select>
                                </div>
                                @error('role')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Status Aktif -->
                        <div class="row">
                            <div class="col-12 mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="is_active"
                                           name="is_active"
                                           value="1"
                                           {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <i class="fas fa-toggle-on text-success"></i>
                                        User Aktif
                                    </label>
                                    <small class="form-text text-muted d-block">
                                        User aktif dapat login ke sistem
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Info User -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body py-3">
                                        <h6 class="card-title mb-2">
                                            <i class="fas fa-info-circle text-info"></i> Informasi User
                                        </h6>
                                        <div class="row text-sm">
                                            <div class="col-md-3">
                                                <strong>ID User:</strong><br>
                                                <span class="badge bg-primary">{{ $user->id }}</span>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Dibuat:</strong><br>
                                                <small class="text-muted">
                                                    {{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-' }}
                                                </small>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Terakhir Update:</strong><br>
                                                <small class="text-muted">
                                                    {{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : '-' }}
                                                </small>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Last Login:</strong><br>
                                                <small class="text-muted">
                                                    {{ $user->last_login ? $user->last_login->format('d/m/Y H:i') : 'Belum pernah login' }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary me-md-2">
                                        <i class="fas fa-times"></i> Batal
                                    </a>
                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-info me-md-2">
                                        <i class="fas fa-eye"></i> Lihat Detail
                                    </a>
                                    <button type="reset" class="btn btn-warning me-md-2" onclick="return confirm('Reset semua perubahan?')">
                                        <i class="fas fa-undo"></i> Reset
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Update User
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');

    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Bootstrap form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
</script>
@endsection

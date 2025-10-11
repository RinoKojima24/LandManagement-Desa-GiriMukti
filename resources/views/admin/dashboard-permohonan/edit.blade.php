@extends('layouts.app')

@section('title', 'Edit Permohonan Surat')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Edit Permohonan Surat</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard-permohonan.update', [$tipe, $permohonan->id_permohonan]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Info Tipe Surat (readonly) -->
                        <div class="alert alert-info">
                            <strong>Tipe Surat:</strong>
                            @if($tipe == 'surat_keterangan')
                                Surat Keterangan
                            @else
                                Surat Permohonan
                            @endif
                        </div>

                        <!-- Jenis Surat -->
                        <div class="mb-3">
                            <label for="id_jenis_surat" class="form-label">Jenis Surat <span class="text-danger">*</span></label>
                            <select class="form-select @error('id_jenis_surat') is-invalid @enderror"
                                    id="id_jenis_surat" name="id_jenis_surat" required>
                                <option value="">-- Pilih Jenis Surat --</option>
                                @foreach($jenisSurat as $js)
                                    <option value="{{ $js->id_jenis_surat }}"
                                            {{ (old('id_jenis_surat', $permohonan->id_jenis_surat) == $js->id_jenis_surat) ? 'selected' : '' }}>
                                        {{ $js->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_jenis_surat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- NIK -->
                        <div class="mb-3">
                            <label for="nik" class="form-label">NIK <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nik') is-invalid @enderror"
                                   id="nik" name="nik" value="{{ old('nik', $permohonan->nik) }}"
                                   placeholder="Masukkan 16 digit NIK" maxlength="16" required>
                            @error('nik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nama Lengkap -->
                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror"
                                   id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $permohonan->nama_lengkap) }}"
                                   placeholder="Masukkan nama lengkap" required>
                            @error('nama_lengkap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jenis Kelamin -->
                        <div class="mb-3">
                            <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input @error('jenis_kelamin') is-invalid @enderror"
                                           type="radio" name="jenis_kelamin" id="laki_laki" value="L"
                                           {{ old('jenis_kelamin', $permohonan->jenis_kelamin) == 'L' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="laki_laki">Laki-laki</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input @error('jenis_kelamin') is-invalid @enderror"
                                           type="radio" name="jenis_kelamin" id="perempuan" value="P"
                                           {{ old('jenis_kelamin', $permohonan->jenis_kelamin) == 'P' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="perempuan">Perempuan</label>
                                </div>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror"
                                      id="alamat" name="alamat" rows="3"
                                      placeholder="Masukkan alamat lengkap" required>{{ old('alamat', $permohonan->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Keperluan (hanya untuk surat keterangan) -->
                        @if($tipe == 'surat_keterangan')
                            <div class="mb-3">
                                <label for="keperluan" class="form-label">Keperluan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('keperluan') is-invalid @enderror"
                                       id="keperluan" name="keperluan" value="{{ old('keperluan', $permohonan->keperluan ?? '') }}"
                                       placeholder="Masukkan keperluan" required>
                                @error('keperluan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror"
                                    id="status" name="status">
                                <option value="pending" {{ old('status', $permohonan->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="verifikasi" {{ old('status', $permohonan->status) == 'verifikasi' ? 'selected' : '' }}>Verifikasi</option>
                                <option value="reject" {{ old('status', $permohonan->status) == 'reject' ? 'selected' : '' }}>Reject</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>
                        <h6 class="fw-bold mb-3">Dokumen Pendukung</h6>

                        <!-- Upload KTP -->
                        <div class="mb-3">
                            <label for="ktp" class="form-label">Upload KTP</label>
                            @if($permohonan->ktp)
                                <div class="mb-2">
                                    <small class="text-muted">File saat ini: </small>
                                    @if(Str::endsWith($permohonan->ktp, '.pdf'))
                                        <a href="{{ Storage::url($permohonan->ktp) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-file-pdf"></i> Lihat PDF
                                        </a>
                                    @else
                                        <a href="{{ Storage::url($permohonan->ktp) }}" target="_blank">
                                            <img src="{{ Storage::url($permohonan->ktp) }}" alt="KTP" class="img-thumbnail" style="max-width: 200px;">
                                        </a>
                                    @endif
                                </div>
                            @endif
                            <input type="file" class="form-control @error('ktp') is-invalid @enderror"
                                   id="ktp" name="ktp" accept=".jpg,.jpeg,.png,.pdf">
                            <small class="text-muted">Format: JPG, JPEG, PNG, PDF (Max: 2MB). Kosongkan jika tidak ingin mengubah.</small>
                            @error('ktp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Upload Dokumen Pendukung -->
                        <div class="mb-3">
                            <label for="dokumen_pendukung" class="form-label">Upload Dokumen Pendukung</label>
                            @if($permohonan->dokumen_pendukung)
                                <div class="mb-2">
                                    <small class="text-muted">File saat ini: </small>
                                    @if(Str::endsWith($permohonan->dokumen_pendukung, '.pdf'))
                                        <a href="{{ Storage::url($permohonan->dokumen_pendukung) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-file-pdf"></i> Lihat PDF
                                        </a>
                                    @else
                                        <a href="{{ Storage::url($permohonan->dokumen_pendukung) }}" target="_blank">
                                            <img src="{{ Storage::url($permohonan->dokumen_pendukung) }}" alt="Dokumen" class="img-thumbnail" style="max-width: 200px;">
                                        </a>
                                    @endif
                                </div>
                            @endif
                            <input type="file" class="form-control @error('dokumen_pendukung') is-invalid @enderror"
                                   id="dokumen_pendukung" name="dokumen_pendukung" accept=".jpg,.jpeg,.png,.pdf">
                            <small class="text-muted">Format: JPG, JPEG, PNG, PDF (Max: 2MB). Kosongkan jika tidak ingin mengubah.</small>
                            @error('dokumen_pendukung')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('dashboard-permohonan.show', [$tipe, $permohonan->id_permohonan]) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Validasi NIK hanya angka
        document.getElementById('nik').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });
</script>
@endpush

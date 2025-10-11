@extends('layouts.app')

@section('title', 'Detail Permohonan Surat')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Permohonan Surat</h5>
                    <div>
                        @if($tipe == 'surat_keterangan')
                            <span class="badge bg-info">Surat Keterangan</span>
                        @else
                            <span class="badge bg-secondary">Surat Permohonan</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">ID Permohonan:</div>
                        <div class="col-md-8">{{ $permohonan->id_permohonan }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">NIK:</div>
                        <div class="col-md-8">{{ $permohonan->nik }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Nama Lengkap:</div>
                        <div class="col-md-8">{{ $permohonan->nama_lengkap }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Jenis Kelamin:</div>
                        <div class="col-md-8">
                            {{ $permohonan->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Alamat:</div>
                        <div class="col-md-8">{{ $permohonan->alamat }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Jenis Surat:</div>
                        <div class="col-md-8">{{ $permohonan->nama_jenis_surat }}</div>
                    </div>

                    @if($tipe == 'surat_keterangan' && $permohonan->keperluan)
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Keperluan:</div>
                            <div class="col-md-8">{{ $permohonan->keperluan }}</div>
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Status:</div>
                        <div class="col-md-8">
                            @if($permohonan->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($permohonan->status == 'verifikasi')
                                <span class="badge bg-success">Verifikasi</span>
                            @else
                                <span class="badge bg-danger">Reject</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Tanggal Pengajuan:</div>
                        <div class="col-md-8">
                            {{ \Carbon\Carbon::parse($permohonan->created_at)->format('d F Y, H:i') }}
                        </div>
                    </div>

                    @if($permohonan->created_by_name)
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Diajukan Oleh:</div>
                            <div class="col-md-8">{{ $permohonan->created_by_name }}</div>
                        </div>
                    @endif

                    <!-- Dokumen -->
                    <hr>
                    <h6 class="fw-bold mb-3">Dokumen Pendukung</h6>

                    @if($permohonan->ktp)
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">KTP:</div>
                            <div class="col-md-8">
                                @if(Str::endsWith($permohonan->ktp, '.pdf'))
                                    <a href="{{ Storage::url($permohonan->ktp) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-file-pdf"></i> Lihat PDF
                                    </a>
                                @else
                                    <a href="{{ Storage::url($permohonan->ktp) }}" target="_blank">
                                        <img src="{{ Storage::url($permohonan->ktp) }}" alt="KTP" class="img-thumbnail" style="max-width: 300px;">
                                    </a>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">KTP:</div>
                            <div class="col-md-8 text-muted">Tidak ada dokumen</div>
                        </div>
                    @endif

                    @if($permohonan->dokumen_pendukung)
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Dokumen Pendukung:</div>
                            <div class="col-md-8">
                                @if(Str::endsWith($permohonan->dokumen_pendukung, '.pdf'))
                                    <a href="{{ Storage::url($permohonan->dokumen_pendukung) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-file-pdf"></i> Lihat PDF
                                    </a>
                                @else
                                    <a href="{{ Storage::url($permohonan->dokumen_pendukung) }}" target="_blank">
                                        <img src="{{ Storage::url($permohonan->dokumen_pendukung) }}" alt="Dokumen" class="img-thumbnail" style="max-width: 300px;">
                                    </a>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Dokumen Pendukung:</div>
                            <div class="col-md-8 text-muted">Tidak ada dokumen</div>
                        </div>
                    @endif

                    <!-- Update Status -->
                    <hr>
                    <h6 class="fw-bold mb-3">Update Status</h6
                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('dashboard-permohonan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form Delete Hidden -->
<form id="deleteForm" action="{{ route('dashboard-permohonan.destroy', [$tipe, $permohonan->id_permohonan]) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete() {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm').submit();
            }
        });
    }
</script>
@endpush

@extends('layouts.mobile')

@section('title', 'Detail Surat')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('surat.unverified') }}">Validasi Surat</a></li>
                    <li class="breadcrumb-item active">Detail Surat</li>
                </ol>
            </nav>

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>Detail Surat {{ ucfirst($data->tipe_surat) }}
                    </h5>
                    <a href="{{ route('surat.unverified') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- Informasi Pemohon --}}
                        <div class="col-md-6 mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-user me-2"></i>Informasi Pemohon
                            </h6>
                            <table class="table table-sm">
                                <tr>
                                    <td width="40%"><strong>NIK</strong></td>
                                    <td>: {{ $data->nik }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Lengkap</strong></td>
                                    <td>: {{ $data->nama_lengkap }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jenis Kelamin</strong></td>
                                    <td>: {{ $data->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Alamat</strong></td>
                                    <td>: {{ $data->alamat }}</td>
                                </tr>
                            </table>
                        </div>

                        {{-- Informasi Surat --}}
                        <div class="col-md-6 mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-info-circle me-2"></i>Informasi Surat
                            </h6>
                            <table class="table table-sm">
                                <tr>
                                    <td width="40%"><strong>Jenis Surat</strong></td>
                                    <td>: {{ $data->jenis_surat ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tipe</strong></td>
                                    <td>:
                                        <span class="badge bg-{{ $data->tipe_surat === 'permohonan' ? 'info' : 'warning' }}">
                                            {{ ucfirst($data->tipe_surat) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td>:
                                        <span class="badge bg-{{ $data->status === 'pending' ? 'secondary' : ($data->status === 'verified' ? 'success' : 'danger') }}">
                                            {{ ucfirst($data->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @if($data->tipe_surat === 'keterangan' && isset($data->keperluan))
                                <tr>
                                    <td><strong>Keperluan</strong></td>
                                    <td>: {{ $data->keperluan }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td><strong>Tanggal Pengajuan</strong></td>
                                    <td>: {{ \Carbon\Carbon::parse($data->created_at)->format('d F Y, H:i') }} WIB</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{-- Dokumen Pendukung --}}
                    <div class="row">
                        <div class="col-12">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-paperclip me-2"></i>Dokumen Pendukung
                            </h6>
                        </div>

                        {{-- KTP --}}
                        @if($data->ktp)
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <strong>KTP</strong>
                                </div>
                                <div class="card-body text-center">
                                    @if(Str::endsWith($data->ktp, '.pdf'))
                                        <i class="fas fa-file-pdf fa-4x text-danger mb-2"></i>
                                        <p class="mb-2">File PDF</p>
                                    @else
                                        <img src="{{ Storage::url($data->ktp) }}"
                                             class="img-fluid rounded mb-2"
                                             alt="KTP"
                                             style="max-height: 200px; object-fit: cover;">
                                    @endif
                                    <a href="{{ Storage::url($data->ktp) }}"
                                       class="btn btn-sm btn-primary"
                                       target="_blank">
                                        <i class="fas fa-download me-1"></i>Download
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Dokumen Pendukung --}}
                        @if($data->dokumen_pendukung)
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <strong>Dokumen Pendukung</strong>
                                </div>
                                <div class="card-body text-center">
                                    @if(Str::endsWith($data->dokumen_pendukung, '.pdf'))
                                        <i class="fas fa-file-pdf fa-4x text-danger mb-2"></i>
                                        <p class="mb-2">File PDF</p>
                                    @else
                                        <img src="{{ Storage::url($data->dokumen_pendukung) }}"
                                             class="img-fluid rounded mb-2"
                                             alt="Dokumen Pendukung"
                                             style="max-height: 200px; object-fit: cover;">
                                    @endif
                                    <a href="{{ Storage::url($data->dokumen_pendukung) }}"
                                       class="btn btn-sm btn-primary"
                                       target="_blank">
                                        <i class="fas fa-download me-1"></i>Download
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                      
                    </div>

                    {{-- Action Buttons --}}
                    @if($data->status === 'pending')
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button"
                                        class="btn btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#rejectModal">
                                    <i class="fas fa-times me-1"></i>Tolak
                                </button>
                                <button type="button"
                                        class="btn btn-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#verifyModal">
                                    <i class="fas fa-check me-1"></i>Verifikasi
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Verify --}}
                    <div class="modal fade" id="verifyModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title">Verifikasi Surat</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('surat.verified', $data->id_permohonan) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="tipe_surat" value="{{ $data->tipe_surat }}">
                                    <div class="modal-body">
                                        <p>Apakah Anda yakin ingin memverifikasi surat ini?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check me-1"></i>Verifikasi
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Reject --}}
                    <div class="modal fade" id="rejectModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">Tolak Surat</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('surat.reject', $data->id_permohonan) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="tipe_surat" value="{{ $data->tipe_surat }}">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Alasan Penolakan:</label>
                                            <textarea name="alasan_penolakan"
                                                      class="form-control"
                                                      rows="4"
                                                      placeholder="Masukkan alasan penolakan"
                                                      required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-times me-1"></i>Tolak
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

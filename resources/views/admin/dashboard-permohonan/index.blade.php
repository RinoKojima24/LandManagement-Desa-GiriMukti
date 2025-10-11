@extends('layouts.app')

@section('title', 'Data Permohonan Surat')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Data Permohonan Surat</h5>
                    <a href="{{ route('dashboard-permohonan.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Tambah Permohonan
                    </a>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="permohonanTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>NIK</th>
                                    <th>Nama Lengkap</th>
                                    <th>Jenis Surat</th>
                                    <th>Tipe Surat</th>
                                    <th>Status</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permohonan as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->nik }}</td>
                                        <td>{{ $item->nama_lengkap }}</td>
                                        <td>{{ $item->nama_jenis_surat }}</td>
                                        <td>
                                            @if($item->tipe_surat == 'surat_keterangan')
                                                <span class="badge bg-info">Surat Keterangan</span>
                                            @else
                                                <span class="badge bg-secondary">Surat Permohonan</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->status == 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif($item->status == 'verifikasi')
                                                <span class="badge bg-success">Verifikasi</span>
                                            @else
                                                <span class="badge bg-danger">Reject</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('dashboard-permohonan.show', [$item->tipe_surat, $item->id_permohonan]) }}"
                                                   class="btn btn-sm btn-info" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('dashboard-permohonan.edit', [$item->tipe_surat, $item->id_permohonan]) }}"
                                                   class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="confirmDelete('{{ $item->tipe_surat }}', '{{ $item->id_permohonan }}')"
                                                        title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Tidak ada data permohonan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form Delete Hidden -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#permohonanTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
            },
            order: [[6, 'desc']]
        });
    });

    function confirmDelete(tipe, id) {
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
                const form = document.getElementById('deleteForm');
                form.action = `/permohonan/${tipe}/${id}`;
                form.submit();
            }
        });
    }
</script>
@endpush

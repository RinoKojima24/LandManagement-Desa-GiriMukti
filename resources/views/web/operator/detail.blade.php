@extends('layouts.mobile')

@section('content')
<style>
.desktop-only {
    display: none;
}

@media (max-width: 768px) {
    .desktop-only {
        display: block;
    }
}
</style>
@php
    $jenis_surat = [
        'skt' => 'Surat Keterangan Tanah (SKT)',
        'sporadik' => 'Surat Pernyataan Penguasaan Fisik (Sporadik)',
        'waris' => 'Surat Keterangan Waris Tanah',
        'hibah' => 'Surat Hibah Tanah',
        'jual_beli' => 'Surat Jual Beli Tanah',
        'tidak_sengketa' => 'Surat Keterangan Tidak Sengketa',
        'permohonan' => 'Surat Permohonan Penggarapan / Pemanfaatan Tanah Desa',
        'lokasi' => 'Surat Keterangan Lokasi Tanah',
        'domisili' => 'Surat Keterangan Domisili',
        'usaha' => 'Surat Keterangan Usaha',
        'tidak-mampu' => 'Surat Keterangan Tidak Mampu',
        'kelahiran' => 'Surat Keterangan Kelahiran',
        'kematian' => 'Surat Keterangan Kematian',
    ];
@endphp
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex items-center space-x-3">
                <a href="{{ url('warga') }}" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-xl font-semibold text-gray-900">Cari Berkas</h1>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Filter Section -->

        <!-- Results Section Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Detail Warga</h2>
                </div>
            </div>
        </div>

        <!-- Loading Placeholder (Show when filtering) -->
        <div id="loadingPlaceholder" class="hidden">
            <div class="space-y-4">
                @for($i = 0; $i < 3; $i++)
                <div class="bg-white rounded-lg p-4 shadow-sm animate-pulse">
                    <div class="h-4 bg-gray-200 rounded w-3/4 mb-3"></div>
                    <div class="h-3 bg-gray-200 rounded w-1/2 mb-2"></div>
                    <div class="h-3 bg-gray-200 rounded w-2/3"></div>
                </div>
                @endfor
            </div>
        </div>

        <!-- Results List -->
        <div id="resultsList">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <table class="table table-borderless">
                        <tr><td><span style="font-size: 20px;"><b>NIK : </b>{{ $warga->nik }}</span></td></tr>
                        <tr><td><span style="font-size: 20px;"><b>Nama : </b>{{ $warga->nama_petugas }}</span></td></tr>
                        <tr><td><span style="font-size: 20px;"><b>No.Whatsapp : </b>{{ $warga->no_telepon }}</span></td></tr>
                        <tr><td><span style="font-size: 20px;"><b>Alamat Email : </b>{{ $warga->email }}</span></td></tr>
                        {{-- <tr><td><span style="font-size: 20px;"><b>Jenis Kelamin : </b>{{ $query->jenis_kelamin == "L" ? "Laki - Laki" : "Perempuan" }}</span></td></tr>
                        <tr><td><span style="font-size: 20px;"><b>Alamat : </b>{{ $query->alamat }}</span></td></tr>
                        <tr><td><span style="font-size: 20px;"><b>Jenis Surat : </b>{{ $jenis_surat[$query->JenisSurat->name] }}</span></td></tr>
                        --}}
                        <tr>
                            <td>
                                <span style="font-size: 20px;"><b>Status : </b></span>
                                <span class="px-3 py-1 text-xs font-medium rounded-full
                                    @if($warga->is_active == '1') bg-yellow-100 text-yellow-700
                                    @elseif($warga->is_active == '0') bg-green-100 text-green-700
                                    @else bg-red-100 text-red-700
                                    @endif">
                                    {{ $warga->is_active == "0" ? "Active" : "Menunggu Konfirmasi"  }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <form action="{{ url('warga/'.$warga->id) }}" method="post">
                                    @csrf
                                    @method('PUT')
                                    <h1>Status Pengajuan Warga : </h1>
                                    <input type="hidden" name="no_telepon" value="{{ $warga->no_telepon }}">
                                    <div class="form-group">
                                        <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                            <option value="1" {{ old('status', $warga->is_active) == '1' ? 'selected' : '' }}>Pending</option>
                                            <option value="0" {{ old('status', $warga->is_active) == '0' ? 'selected' : '' }}>Verifikasi</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success">Kirim / Simpan</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-12 col-md-6">
                    <table class="table table-borderless">
                        <tr><td><span style="font-size: 20px;"><b>KTP : </b></span></td></tr>
                        <tr><td><img src="{{ url('/lihat-ktp/'.basename($warga->foto_ktp)) }}" alt=""></td></tr>
                    </table>
                </div>

            </div>
            <table>
                <tr>
                    <td></td>
                </tr>
            </table>
            <!-- Empty State -->
            {{-- <div class="bg-white rounded-lg p-12 text-center shadow-sm">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Data Ditemukan</h3>
                <p class="text-gray-500">Coba ubah filter atau kata kunci pencarian Anda</p>
            </div> --}}
        </div>

    </div>
</div>

<script>
function toggleFilter(filterId) {
    const filterElement = document.getElementById('filter-' + filterId);
    const allFilters = document.querySelectorAll('[id^="filter-"]');

    allFilters.forEach(filter => {
        if (filter.id !== 'filter-' + filterId) {
            filter.classList.add('hidden');
        }
    });

    filterElement.classList.toggle('hidden');
}

// Show loading on form submit
document.getElementById('filterForm').addEventListener('submit', function() {
    document.getElementById('loadingPlaceholder').classList.remove('hidden');
    document.getElementById('resultsList').classList.add('hidden');
});

@section('jquery')
    $('#tipe_surat').on('change', function() {
        $('#ganti_tipe_surat').submit();
    });
@endsection
</script>

<style>
@media print {
    .no-print {
        display: none;
    }
}
</style>
@endsection

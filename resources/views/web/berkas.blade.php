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
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex items-center space-x-3">
                <a href="{{ url('home') }}" class="text-gray-600 hover:text-gray-900">
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
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <form method="GET" action="{{ url()->current() }}" id="filterForm">
                <!-- Search Bar -->
                <div class="mb-4">
                    <div class="relative">
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Pencarian"
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Filter Buttons Row -->
                {{-- <div class="grid grid-cols-3 gap-2 mb-4">
                    <button type="button"
                            onclick="toggleFilter('newor')"
                            class="flex items-center justify-center space-x-2 px-3 py-2 bg-amber-50 text-amber-700 rounded-lg border border-amber-200 hover:bg-amber-100 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <span class="text-sm font-medium">Newor Bidang</span>
                    </button>

                    <button type="button"
                            onclick="toggleFilter('name')"
                            class="flex items-center justify-center space-x-2 px-3 py-2 bg-gray-50 text-gray-700 rounded-lg border border-gray-200 hover:bg-gray-100 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="text-sm font-medium">Name</span>
                    </button>

                    <button type="button"
                            onclick="toggleFilter('jenis')"
                            class="flex items-center justify-center space-x-2 px-3 py-2 bg-gray-50 text-gray-700 rounded-lg border border-gray-200 hover:bg-gray-100 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-sm font-medium">Jenis Surat</span>
                    </button>
                </div>

                <!-- Collapsible Filter Sections -->
                <div id="filter-newor" class="hidden mb-3">
                    <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="verifikasi" {{ request('status') == 'verifikasi' ? 'selected' : '' }}>Verifikasi</option>
                        <option value="reject" {{ request('status') == 'reject' ? 'selected' : '' }}>Reject</option>
                    </select>
                </div>

                <div id="filter-name" class="hidden mb-3">
                    <input type="text"
                           name="nama"
                           value="{{ request('nama') }}"
                           placeholder="Masukkan nama lengkap"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>

                <div id="filter-jenis" class="hidden mb-3">
                    <select name="jenis_surat" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <option value="">Semua Jenis Surat</option>
                        @foreach($jenis_surat_list as $js)
                            <option value="{{ $js->id_jenis_surat }}" {{ request('jenis_surat') == $js->id_jenis_surat ? 'selected' : '' }}>
                                {{ $js->name }} {{ $js->jenis_surat }}
                            </option>
                        @endforeach
                    </select>
                </div> --}}

                <!-- Date Range -->
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div>
                        <input type="hidden" name="tipe_surat" value="{{ @$_GET['tipe_surat'] ?? 0 }}">
                        <label class="block text-xs text-gray-600 mb-1">Dari Tanggal</label>
                        <input type="date"
                               name="tanggal_dari"
                               value="{{ request('tanggal_dari') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Sampai Tanggal</label>
                        <input type="date"
                               name="tanggal_sampai"
                               value="{{ request('tanggal_sampai') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-3">
                    <button type="submit"
                            class="flex-1 bg-teal-600 text-white py-2.5 rounded-lg font-medium hover:bg-teal-700 transition">
                        Cari
                    </button>
                    <a href="{{ url()->current() }}"
                       class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Results Section Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Berkas Terdaftar</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Menampilkan {{  count($surat_keterangan) + count($surat_permohonan) }} berkas
                    </p>
                </div>
                <div>
                    <form action="" method="get" id="ganti_tipe_surat">
                        <select name="tipe_surat" id="tipe_surat" class="form-control">
                            <option value="0" {{ @$_GET['tipe_surat'] == "0" ? 'selected' : '' }}>Pengajuan Surat Tanah</option>
                            <option value="1" {{ @$_GET['tipe_surat'] == "1" ? 'selected' : '' }}>Pengajuan Surat Keterangan</option>
                        </select>
                    </form>
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
            @if(  count($surat_keterangan) == 0 &&  count($surat_permohonan)  == 0)
                <!-- Empty State -->
                <div class="bg-white rounded-lg p-12 text-center shadow-sm">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Data Ditemukan</h3>
                    <p class="text-gray-500">Coba ubah filter atau kata kunci pencarian Anda</p>
                </div>
            @else
                <div class="desktop-only">
                    <!-- Surat Keterangan Cards -->
                    @foreach($surat_keterangan as $surat)
                    <div class="bg-white rounded-lg shadow-sm mb-3 overflow-hidden hover:shadow-md transition">
                        <div class="p-4">
                            <!-- Header -->
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 mb-1">{{ $surat->nama_lengkap }}</h3>
                                    <p class="text-sm text-gray-600">{{ $surat->jenis_surat_lengkap }}</p>
                                </div>
                                <span class="px-3 py-1 text-xs font-medium rounded-full
                                    @if($surat->status == 'pending') bg-yellow-100 text-yellow-700
                                    @elseif($surat->status == 'verifikasi') bg-green-100 text-green-700
                                    @else bg-red-100 text-red-700
                                    @endif">
                                    {{ ucfirst($surat->status) }}
                                </span>
                            </div>

                            <!-- Details -->
                            <div class="space-y-2 mb-3">
                                <div class="flex items-center text-sm">
                                    <span class="text-gray-500 w-28">Jenis Berkas:</span>
                                    <span class="text-gray-900 font-medium">Surat {{ ucwords(str_replace('_', ' ', 'keterangan')) }}</span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <span class="text-gray-500 w-28">Tanggal Input:</span>
                                    <span class="text-gray-900">{{ \Carbon\Carbon::parse($surat->created_at)->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <span class="text-gray-500 w-28">No Surat:</span>
                                    <span class="text-gray-900">{{ $surat->no_surat ?? $surat->id_permohonan }}</span>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <a href="{{ url('berkas/'.$surat->id_permohonan.'?tipe_surat='.$_GET['tipe_surat']) }}"
                            class="block w-full text-center bg-teal-600 text-white py-2 rounded-lg font-medium hover:bg-teal-700 transition">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                    @endforeach

                    <!-- Surat Permohonan Cards -->
                    @foreach($surat_permohonan as $surat)
                    <div class="bg-white rounded-lg shadow-sm mb-3 overflow-hidden hover:shadow-md transition">
                        <div class="p-4">
                            <!-- Header -->
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 mb-1">{{ $surat->nama_lengkap }}</h3>
                                    <p class="text-sm text-gray-600">{{ $surat->jenis_surat_lengkap }}</p>
                                </div>
                                <span class="px-3 py-1 text-xs font-medium rounded-full
                                    @if($surat->status == 'pending') bg-yellow-100 text-yellow-700
                                    @elseif($surat->status == 'verifikasi') bg-green-100 text-green-700
                                    @else bg-red-100 text-red-700
                                    @endif">
                                    {{ ucfirst($surat->status) }}
                                </span>
                            </div>

                            <!-- Details -->
                            <div class="space-y-2 mb-3">
                                <div class="flex items-center text-sm">
                                    <span class="text-gray-500 w-28">Jenis Berkas:</span>
                                    <span class="text-gray-900 font-medium">Surat {{ ucwords(str_replace('_', ' ', 'permohonan')) }}</span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <span class="text-gray-500 w-28">Tanggal Input:</span>
                                    <span class="text-gray-900">{{ \Carbon\Carbon::parse($surat->created_at)->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <span class="text-gray-500 w-28">No Surat:</span>
                                    <span class="text-gray-900">{{ $surat->no_surat ?? $surat->id_permohonan }}</span>
                                </div>
                            </div>

                            <!-- Action Button -->

                            <a href="{{ url('berkas/'.$surat->id_permohonan.'?tipe_surat='.$_GET['tipe_surat']) }}"
                            class="block w-full text-center bg-teal-600 text-white py-2 rounded-lg font-medium hover:bg-teal-700 transition">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Desktop Table View (Hidden on Mobile) -->
        <div class="hidden lg:block bg-white rounded-lg shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Surat</th> --}}
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse([...$surat_keterangan, ...$surat_permohonan] as $surat)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $surat->nama_lengkap ?? $surat->nama }}</div>
                            <div class="text-sm text-gray-500">{{ $surat->nik }}</div>
                        </td>
                        {{-- <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $surat->jenis_surat_lengkap }}</div>
                        </td> --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded">
                                {{ property_exists($surat, 'keperluan') ? 'Keterangan' : 'Permohonan' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($surat->created_at)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs font-medium rounded-full
                                @if($surat->status == 'pending') bg-yellow-100 text-yellow-700
                                @elseif($surat->status == 'verifikasi') bg-green-100 text-green-700
                                @else bg-red-100 text-red-700
                                @endif">
                                {{ ucfirst($surat->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ url('berkas/'.($surat->id_permohonan ?? $surat->id).'?tipe_surat='.$_GET['tipe_surat']) }}"
                               class="text-teal-600 hover:text-teal-900 font-medium">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            Tidak ada data ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
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

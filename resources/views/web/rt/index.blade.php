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
                <h1 class="text-xl font-semibold text-gray-900">Data RT</h1>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <form method="GET" action="{{ url()->current() }}" id="filterForm">
                <!-- Search Bar -->
                <div class="mb-4">
                    <div class="relative row">
                        <div class="col-sm-12 col-md-12">
                            <input type="text"
                                name="cari_nama"
                                value="{{ request('cari_nama') }}"
                                placeholder="Cari nama RT"
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        </div>
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
                    <a href="{{ url('rt/create') }}"
                       class="px-6 py-2.5 bg-green-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition">
                        Tambah Data
                    </a>
                </div>
            </form>
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

                <div class="desktop-only">
                    <!-- Surat Keterangan Cards -->


                    <!-- Surat Permohonan Cards -->

                </div>

        </div>

        <!-- Desktop Table View (Hidden on Mobile) -->
        <div class="hidden lg:block bg-white rounded-lg shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor RT</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama RT</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">RT</th>


                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($rt as $item)
                        <tr>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $loop->iteration }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $item->nomor_rt }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $item->nama_rt }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $item->nama }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <form action="{{ url('rt/'.$item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <a href="{{ url('rt/'.$item->id.'/edit') }}"
                                    class=" font-medium btn btn-success">
                                    Edit
                                </a>
                                <button type="submit" onclick="return confirm('Yakin ingin menghapus data ini?')" class="btn btn-danger">Hapus</button>
                            </form>

                        </td>
                        </tr>
                        <!-- tampilan ketika data ADA -->
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

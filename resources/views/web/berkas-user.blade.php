@extends('layouts.mobile')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2">
                Berkas Surat Saya
            </h1>
            <p class="text-gray-600">Kelola dan pantau status pengajuan surat Anda</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Berkas</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $berkas->total() }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Menunggu</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $berkas->where('status', 'pending')->count() }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Disetujui</p>
                        <p class="text-2xl font-bold text-green-600">{{ $berkas->where('status', 'approved')->count() }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Ditolak</p>
                        <p class="text-2xl font-bold text-red-600">{{ $berkas->where('status', 'rejected')->count() }}</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            {{-- <!-- Table Header with Search -->
            <div class="p-6 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h2 class="text-xl font-semibold text-gray-900">Daftar Berkas</h2>
                    <div class="relative">
                        <input type="text" placeholder="Cari berkas..."
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full sm:w-64">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div> --}}

            <!-- Desktop Table -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">NIK</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jenis Surat</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                            {{-- <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th> --}}
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($berkas as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                {{ $item->id_permohonan }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $item->nik }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">{{ strtoupper(substr($item->nama_lengkap, 0, 1)) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->nama_lengkap }}</div>
                                        <div class="text-sm text-gray-500">{{ $item->jenis_kelamin }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    {{ $item->jenis_surat == 'surat_keterangan' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $item->jenis_surat == 'surat_keterangan' ? 'Surat Keterangan' : 'Surat Permohonan' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusConfig = [
                                        'pending' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Menunggu'],
                                        'approved' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Disetujui'],
                                        'rejected' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Ditolak'],
                                    ];
                                    $status = $statusConfig[$item->status] ?? ['class' => 'bg-gray-100 text-gray-800', 'text' => ucfirst($item->status)];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $status['class'] }}">
                                    {{ $status['text'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                            </td>
                            {{-- <td class="px-6 py-4 text-sm">
                                <button class="text-blue-600 hover:text-blue-800 font-medium transition-colors">
                                    Detail
                                </button>
                            </td> --}}
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="mt-4 text-gray-500">Belum ada berkas yang diajukan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="lg:hidden divide-y divide-gray-100">
                @forelse($berkas as $item)
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold">{{ strtoupper(substr($item->nama_lengkap, 0, 1)) }}</span>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-base font-semibold text-gray-900">{{ $item->nama_lengkap }}</h3>
                                <p class="text-sm text-gray-500">NIK: {{ $item->nik }}</p>
                            </div>
                        </div>
                        @php
                            $statusConfig = [
                                'pending' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Menunggu'],
                                'approved' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Disetujui'],
                                'rejected' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Ditolak'],
                            ];
                            $status = $statusConfig[$item->status] ?? ['class' => 'bg-gray-100 text-gray-800', 'text' => ucfirst($item->status)];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $status['class'] }}">
                            {{ $status['text'] }}
                        </span>
                    </div>
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">ID Permohonan:</span>
                            <span class="font-medium text-gray-900">{{ $item->id_permohonan }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Jenis Surat:</span>
                            <span class="font-medium text-gray-900">
                                {{ $item->jenis_surat == 'surat_keterangan' ? 'Surat Keterangan' : 'Surat Permohonan' }}
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Tanggal:</span>
                            <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</span>
                        </div>
                    </div>
                    <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        Lihat Detail
                    </button>
                </div>
                @empty
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="mt-4 text-gray-500">Belum ada berkas yang diajukan</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($berkas->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $berkas->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

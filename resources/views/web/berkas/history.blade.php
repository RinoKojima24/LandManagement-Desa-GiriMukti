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
                <a href="{{ url('berkas/'.$surat_id.'?tipe_surat='.$tipe_surat) }}" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                Kembali
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Filter Section -->

        <!-- Results List -->
            @foreach ($chat as $a)
                <div class="card">
                    <div class="card-body">
                        <p>{{ $a->pesan }}</p>
                        <p>{{ date('d/m/Y H:i:s') }}</p>
                    </div>
                </div>
            @endforeach
            {{ $chat->appends(request()->query())->links() }}
    </div>
</div>

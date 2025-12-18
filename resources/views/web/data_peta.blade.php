@extends('layouts.mobile')

@section('content')
<div class="container-fluid px-3 py-4" style="background-color: #f5f5f5; min-height: 100vh;">
    <!-- Header -->
    <div class="mb-4">
        <div class="d-flex justify-content-between">
            <div class="d-flex align-items-center mb-3">
                @if (isset($_GET['pemilik']))
                    <a href="{{ url('tanah') }}" class="text-decoration-none text-dark me-3">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                @else
                    <a href="{{ route('home') }}" class="text-decoration-none text-dark me-3">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                @endif
                <h5 class="mb-0 fw-bold">Data Peta Tanah</h5>
            </div>
            <div class="d-flex align-items-center mb-3">
                @if (isset($_GET['pemilik']))
                    @if (Auth::user()->role != "warga")
                        <a href="{{ url('tanah/create?pemilik='.$_GET['pemilik']) }}" class="btn btn-success">Tambah Data</a>
                    @endif
                @endif
            </div>
        </div>
        <p class="text-muted small mb-3">Gunakan pencarian untuk mempermudah melihat data</p>

        <!-- Search Box -->
        <form action="{{ route('data.peta') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text"
                       name="search"
                       class="form-control"
                       placeholder="Pencarian..."
                       value="{{ request('search') }}">
                <button class="btn btn-success" type="submit">
                    <i class="fas fa-search me-1"></i> Cari
                </button>
            </div>
        </form>
    </div>

    <!-- Cards Container -->
    @if(isset($_GET['pemilik']))
    <div class="row g-3">
        @forelse($data as $item)
        {{-- @dd($item->foto_peta) --}}
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card">
                <img src="{{ url('storage/'.$item->foto_peta) }}" alt="...">
                <div class="card-body">
                    {{-- <div class="d-flex justify-content-between">
                        <div class="btn btn-warning h-8" style="font-size: 12px;">Nomor Bidang</div>
                        <div class="mb-3">
                            <span class="float-end" style="font-size: 14px;">{{ $item->nomor_bidang }}</span><br>
                            <span class="float-end" style="font-size: 14px;"><b>{{ $item->SuratPermohonan->nama_lengkap ?? "-" }}</b></span>

                        </div>
                    </div> --}}
                    {{-- <div class="d-flex justify-content-between">
                        <div>
                            <label for="" style="font-size: 14px;">Titik Kordinat</label><br>
                            <span style="font-size: 12px;">{{ $item->titik_kordinat }}</span>
                        </div>
                    </div> --}}
                    @php
                        $kordinat = explode(',', ($item->titik_kordinat == "-" ? '0,0' : $item->titik_kordinat));
                    @endphp

                    <h1 style="font-size: 20px;"><b>{{ $item->SuratUkur->kecamatan ?? "" }}, {{ $item->SuratUkur->provinsi ?? "" }}, Indonesia</b></h1>
                    <p><br> Lat {{ $kordinat[0] }} <br> Long {{ $kordinat[1] }} <br></p>
                    <hr>
                    <p>{{ date('d/m/Y, H:i:s', strtotime($item->tanggal_pengukuran)) }}</p>
                    <br>
                    <a href="{{ url('tanah/'.$item->id.'/show') }}" class="btn btn-success w-full">Lihat Data Peta</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>
                Tidak ada data yang ditemukan.
            </div>
        </div>
        @endforelse
    </div>
    @else
        {{-- @dd("ASD") --}}
        <div class="lg:block bg-white rounded-lg shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Whatsapp</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($warga as $item)
                        <tr>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $loop->iteration }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $item->nama_petugas }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $item->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $item->no_telepon }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-medium rounded-full
                                @if($item->is_active == '1') bg-yellow-100 text-yellow-700
                                @elseif($item->is_active == '0') bg-green-100 text-green-700
                                @else bg-red-100 text-red-700
                                @endif">
                                {{ $item->is_active == "0" ? "Active" : "Menunggu Konfirmasi"  }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ url('tanah?pemilik='.$item->id) }}"
                                class="text-teal-600 hover:text-teal-900 font-medium">
                                Lihat Detail
                            </a>
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
    @endif

</div>

<style>
    /* Custom Styles */
    .object-fit-cover {
        object-fit: cover;
    }

    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
    }

    .btn-success {
        background-color: #059669;
        border-color: #059669;
    }

    .btn-success:hover {
        background-color: #047857;
        border-color: #047857;
    }

    /* Responsive adjustments */
    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .card-img-top {
            height: 100px;
        }
    }
</style>
@endsection

@extends('layouts.mobile')

@section('content')
<div class="container-fluid px-3 py-4" style="background-color: #f5f5f5; min-height: 100vh;">
    <!-- Header -->
    <div class="mb-4">
        <div class="d-flex justify-content-between">
            <div class="d-flex align-items-center mb-3">
                <a href="{{ route('home') }}" class="text-decoration-none text-dark me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h5 class="mb-0 fw-bold">Data Peta Tanah</h5>
            </div>
            <div class="d-flex align-items-center mb-3">
                <a href="{{ url('tanah/create') }}" class="btn btn-success">Tambah Data</a>
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
    <div class="row g-3">
        @forelse($data as $item)
        {{-- @dd($item->foto_peta) --}}
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card">
                <img src="{{ url('storage/'.$item->foto_peta) }}" alt="...">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="btn btn-warning h-8" style="font-size: 12px;">Nomor Bidang</div>
                        <div class="mb-3">
                            <span class="float-end" style="font-size: 14px;">{{ $item->nomor_bidang }}</span><br>
                            <span class="float-end" style="font-size: 14px;"><b>{{ $item->SuratPermohonan->nama_lengkap ?? "-" }}</b></span>

                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>
                            <label for="" style="font-size: 14px;">Titik Kordinat</label><br>
                            <span style="font-size: 12px;">{{ $item->titik_kordinat }}</span>
                        </div>
                    </div><br>
                    <hr>
                    <br>
                    <div class="d-flex justify-content-between">
                        <div>
                            <label for="" style="font-size: 14px;">Peruntukan</label><br>
                            <span style="font-size: 13px;">{{ $item->peruntukan }}</span>
                        </div>
                        <div>
                            <label for="" style="font-size: 14px;">Status</label><br>
                            <span style="font-size: 13px;">{{ $item->status }}</span>
                        </div>
                    </div>
                    <br>
                    <div class="d-flex justify-content-between">
                        <div>
                            <label for="" style="font-size: 14px;">Panjang</label><br>
                            <span style="font-size: 13px;">{{ $item->panjang }} m</span>
                        </div>
                        <div>
                            <label for="" style="font-size: 14px;">Lebar</label><br>
                            <span style="font-size: 13px;">{{ $item->lebar }} m</span>
                        </div>
                        <div>
                            <label for="" style="font-size: 14px;">Luas</label><br>
                            <span style="font-size: 13px;">{{ $item->luas }} m<sup>2</sup></span>
                        </div>
                    </div>
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

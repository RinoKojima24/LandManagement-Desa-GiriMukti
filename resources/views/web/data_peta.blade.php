@extends('layouts.mobile')

@section('content')
<div class="container-fluid px-3 py-4" style="background-color: #f5f5f5; min-height: 100vh;">
    <!-- Header -->
    <div class="mb-4">
        <div class="d-flex align-items-center mb-3">
            <a href="{{ route('home') }}" class="text-decoration-none text-dark me-3">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h5 class="mb-0 fw-bold">Data Peta Tanah</h5>
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
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <!-- Image Placeholder -->
              <div class="card-img-top bg-secondary" style="height: 120px; position: relative;">
                    <img src="{{ $tanah->foto ?? asset('images/tanah-placeholder.jpg') }}"
                        class="w-100 h-100 object-fit-cover"
                        alt="Foto Tanah"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                    <div class="fallback-overlay position-absolute top-0 start-0 w-100 h-100 bg-secondary d-flex align-items-center justify-content-center"
                        style="display: none;">
                        <i class="fas fa-image text-white fa-2x"></i>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Title -->
                    <h6 class="card-title fw-bold mb-2">{{ $item->nama_jenis ?? 'Tidak Ada Nama' }}</h6>

                    <!-- Date -->
                    <p class="text-muted small mb-3">
                        <i class="far fa-calendar me-1"></i>
                        {{ date('d/m/Y') }}
                    </p>

                    <!-- Details Grid -->
                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <small class="text-muted d-block">Panjang</small>
                            <small class="fw-semibold">{{ $item->panjang ?? '-' }} m</small>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">Lebar</small>
                            <small class="fw-semibold">{{ $item->luas_tanah ?? '-' }} m²</small>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">Penerbit</small>
                            <small class="fw-semibold">{{ $item->penerbit ?? 'Kementan' }}</small>
                        </div>
                    </div>

                    <!-- NIB Badge -->
                    <div class="mb-3">
                        <span class="badge bg-light text-dark border">
                            NIB: {{ $item->nib ?? '-' }}
                        </span>
                    </div>

                    <!-- Action Button -->
                    <a href="{{ route('bidang-tanah.show', $item->id_bidang_tanah) }}"
                       class="btn btn-success btn-sm w-100">
                        Lihat Detail
                    </a>
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

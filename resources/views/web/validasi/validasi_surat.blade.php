@extends('layouts.mobile')

@section('title', 'Validasi Surat')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    {{-- Header Section --}}
    <div class="mb-4">
        <h4 class="fw-bold text-dark mb-1">
            <i class="fas fa-clipboard-check me-2 text-primary"></i>Validasi Surat
        </h4>
        <p class="text-muted mb-0">Kelola surat yang menunggu verifikasi</p>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filter & Search Bar --}}
    <div class="card shadow-sm mb-4" style="border: 1px solid #e0e0e0;">
        <div class="card-body p-3">
            <div class="row g-2">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control border-start-0 ps-0" placeholder="Cari NIK, nama, atau jenis surat...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select id="filterTipe" class="form-select">
                        <option value="">Semua Tipe</option>
                        <option value="permohonan">Permohonan</option>
                        <option value="keterangan">Keterangan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="sortBy" class="form-select">
                        <option value="newest">Terbaru</option>
                        <option value="oldest">Terlama</option>
                        <option value="name">Nama A-Z</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Cards Grid --}}
    <div class="row g-3" id="cardsContainer">
        @forelse($data as $item)
            <div class="col-12 col-md-6 col-xl-4 card-item"
                 data-tipe="{{ $item->tipe_surat }}"
                 data-nama="{{ strtolower($item->nama_lengkap) }}"
                 data-nik="{{ $item->nik }}"
                 data-jenis="{{ strtolower($item->jenis_surat ?? '') }}"
                 data-date="{{ $item->created_at }}">
                <div class="card h-100 shadow-sm hover-card" style="border: 1px solid #e0e0e0;">
                    {{-- Card Header --}}
                    <div class="card-header bg-gradient border-0 p-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="text-white">
                                <h6 class="mb-1 fw-bold">{{ $item->nama_lengkap }}</h6>
                                <small class="opacity-75">
                                    <i class="fas fa-id-card me-1"></i>{{ $item->nik }}
                                </small>
                            </div>
                            <span class="badge bg-white text-primary px-2 py-1">
                                <i class="fas fa-{{ $item->tipe_surat === 'permohonan' ? 'file-alt' : 'clipboard-list' }} me-1"></i>
                                {{ ucfirst($item->tipe_surat) }}
                            </span>
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="card-body p-3">
                        <div class="mb-3">
                            <label class="text-muted small mb-1">Jenis Surat</label>
                            <p class="mb-0 fw-semibold text-dark">{{ $item->jenis_surat ?? '-' }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small mb-1">Status</label>
                            <div>
                                <span class="badge bg-secondary px-3 py-2">
                                    <i class="fas fa-clock me-1"></i>{{ ucfirst($item->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small mb-1">Tanggal Pengajuan</label>
                            <p class="mb-0">
                                <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                <span class="text-dark">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</span>
                            </p>
                        </div>
                    </div>

                    {{-- Card Footer --}}
                    <div class="card-footer bg-light border-0 p-3">
                        <div class="d-grid gap-2">
                            <a href="{{ route('surat.detail', ['id' => $item->id, 'tipe' => $item->tipe_surat]) }}"
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye me-2"></i>Lihat Detail
                            </a>
                            <div class="row g-2">
                                <div class="col-6">
                                    <button type="button"
                                            class="btn btn-success btn-sm w-100"
                                            data-bs-toggle="modal"
                                            data-bs-target="#verifyModal{{ $item->id }}">
                                        <i class="fas fa-check me-1"></i>Verifikasi
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button"
                                            class="btn btn-danger btn-sm w-100"
                                            data-bs-toggle="modal"
                                            data-bs-target="#rejectModal{{ $item->id }}">
                                        <i class="fas fa-times me-1"></i>Tolak
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Verify --}}
            <div class="modal fade" id="verifyModal{{ $item->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header border-0 bg-success text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-check-circle me-2"></i>Verifikasi Surat
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('surat.verified', $item->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipe_surat" value="{{ $item->tipe_surat }}">
                            <div class="modal-body p-4">
                                <p class="mb-3">Apakah Anda yakin ingin memverifikasi surat ini?</p>
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <div class="mb-2">
                                            <small class="text-muted">NIK</small>
                                            <p class="mb-0 fw-semibold">{{ $item->nik }}</p>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">Nama Lengkap</small>
                                            <p class="mb-0 fw-semibold">{{ $item->nama_lengkap }}</p>
                                        </div>
                                        <div class="mb-0">
                                            <small class="text-muted">Jenis Surat</small>
                                            <p class="mb-0 fw-semibold">{{ $item->jenis_surat }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check me-1"></i>Ya, Verifikasi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Modal Reject --}}
            <div class="modal fade" id="rejectModal{{ $item->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header border-0 bg-danger text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-times-circle me-2"></i>Tolak Surat
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('surat.reject', $item->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipe_surat" value="{{ $item->tipe_surat }}">
                            <div class="modal-body p-4">
                                <p class="mb-3">Apakah Anda yakin ingin menolak surat ini?</p>
                                <div class="card bg-light border-0 mb-3">
                                    <div class="card-body">
                                        <div class="mb-2">
                                            <small class="text-muted">NIK</small>
                                            <p class="mb-0 fw-semibold">{{ $item->nik }}</p>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">Nama Lengkap</small>
                                            <p class="mb-0 fw-semibold">{{ $item->nama_lengkap }}</p>
                                        </div>
                                        <div class="mb-0">
                                            <small class="text-muted">Jenis Surat</small>
                                            <p class="mb-0 fw-semibold">{{ $item->jenis_surat }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Alasan Penolakan</label>
                                    <textarea name="alasan_penolakan"
                                              class="form-control"
                                              rows="3"
                                              placeholder="Masukkan alasan penolakan (opsional)"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-times me-1"></i>Ya, Tolak
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        @empty
            <div class="col-12">
                <div class="card shadow-sm" style="border: 1px solid #e0e0e0;">
                    <div class="card-body text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-inbox fa-4x text-muted opacity-50"></i>
                        </div>
                        <h5 class="text-muted mb-2">Tidak Ada Surat</h5>
                        <p class="text-muted mb-0">Tidak ada surat yang menunggu verifikasi saat ini</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Empty State for Filter --}}
    <div id="emptyState" class="d-none">
        <div class="card shadow-sm" style="border: 1px solid #e0e0e0;">
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-search fa-4x text-muted opacity-50"></i>
                </div>
                <h5 class="text-muted mb-2">Tidak Ada Hasil</h5>
                <p class="text-muted mb-0">Coba gunakan kata kunci lain</p>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Card Hover Effect */
    .hover-card {
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
        border-color: #667eea !important;
    }

    /* Gradient Animation */
    .bg-gradient {
        background-size: 200% 200%;
        animation: gradientShift 3s ease infinite;
    }

    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    /* Button Hover Effects */
    .btn {
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
    }

    /* Modal Animation */
    .modal.fade .modal-dialog {
        transition: transform 0.3s ease-out;
    }

    /* Badge Styling */
    .badge {
        font-weight: 500;
        letter-spacing: 0.3px;
    }

    /* Card Footer Buttons */
    .card-footer .btn {
        font-size: 0.875rem;
        font-weight: 500;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }

        .card-header h6 {
            font-size: 0.95rem;
        }

        .card-header small {
            font-size: 0.75rem;
        }
    }

    /* Input Focus Style */
    .form-control:focus,
    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    /* Loading Animation */
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .loading {
        animation: pulse 1.5s ease-in-out infinite;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Search functionality
    $('#searchInput').on('keyup', function() {
        filterCards();
    });

    // Filter by type
    $('#filterTipe').on('change', function() {
        filterCards();
    });

    // Sort functionality
    $('#sortBy').on('change', function() {
        sortCards($(this).val());
    });

    function filterCards() {
        const searchTerm = $('#searchInput').val().toLowerCase();
        const filterTipe = $('#filterTipe').val();
        let visibleCount = 0;

        $('.card-item').each(function() {
            const nama = $(this).data('nama');
            const nik = $(this).data('nik').toString();
            const jenis = $(this).data('jenis');
            const tipe = $(this).data('tipe');

            const matchSearch = nama.includes(searchTerm) ||
                              nik.includes(searchTerm) ||
                              jenis.includes(searchTerm);
            const matchTipe = !filterTipe || tipe === filterTipe;

            if (matchSearch && matchTipe) {
                $(this).removeClass('d-none').addClass('animate__animated animate__fadeIn');
                visibleCount++;
            } else {
                $(this).addClass('d-none').removeClass('animate__animated animate__fadeIn');
            }
        });

        // Show/hide empty state
        if (visibleCount === 0 && $('.card-item').length > 0) {
            $('#emptyState').removeClass('d-none');
        } else {
            $('#emptyState').addClass('d-none');
        }
    }

    function sortCards(sortType) {
        const container = $('#cardsContainer');
        const cards = $('.card-item').get();

        cards.sort(function(a, b) {
            switch(sortType) {
                case 'newest':
                    return new Date($(b).data('date')) - new Date($(a).data('date'));
                case 'oldest':
                    return new Date($(a).data('date')) - new Date($(b).data('date'));
                case 'name':
                    return $(a).data('nama').localeCompare($(b).data('nama'));
                default:
                    return 0;
            }
        });

        $.each(cards, function(idx, card) {
            container.append(card);
        });
    }

    // Add animation on page load
    $('.card-item').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
        $(this).addClass('animate__animated animate__fadeInUp');
    });

    // Auto-dismiss alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
@endpush
@endsection

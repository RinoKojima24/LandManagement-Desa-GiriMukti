@extends(Auth::user() && Auth::user()->role === 'admin' ? 'layouts.app' : 'layouts.mobile')

@section('content')
<div class="container-fluid px-0">
    <div class="antrean-wrapper">
        <!-- Header -->
        <div class="antrean-header">
            <a href="{{ route('antrean.index') }}" class="back-button">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </a>
            <h1 class="header-title">Antrean Online</h1>
        </div>

        <!-- Info Badge -->
        <div class="info-badge">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            <span>Antrean Hari Ini: Senin, 8 Juli 2025</span>
        </div>

        <form action="{{ route('antrean.store') }}" method="POST" class="antrean-form">
            @csrf

            <!-- Nomor Saat Ini -->
            <div class="form-section">
                <label class="form-label">Nomor Saat Ini:</label>
                <div class="nomor-antrean">A-021</div>
            </div>

            <!-- Total Hari Ini -->
            <div class="form-section">
                <label class="form-label">Total Hari Ini:</label>
                <div class="total-info">{{ $jumlahMenunggu }} Orang</div>
            </div>

            <!-- Jenis Layanan -->
            <div class="form-section">
                <label for="layanan_id" class="form-label">Jenis Layanan</label>
                <div class="select-wrapper">
                    <select name="layanan_id" id="layanan_id" class="form-select @error('layanan_id') is-invalid @enderror" required>
                        <option value="">Pilih jenis layanan</option>
                        @foreach($layanan as $item)
                            <option value="{{ $item->id }}" {{ old('layanan_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_layanan }}
                            </option>
                        @endforeach
                    </select>
                    <svg class="select-arrow" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>
                @error('layanan_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Total Hari Ini (Bottom) -->
            <div class="form-section">
                <label class="form-label">Total Hari Ini:</label>
                <div class="total-info">{{ $jumlahMenunggu }} Orang</div>
            </div>

            <!-- Hidden Fields -->
            <input type="hidden" name="tanggal" value="{{ date('Y-m-d') }}">

            @if(isset($keterangan))
                <input type="hidden" name="keterangan" value="{{ $keterangan }}">
            @endif

            <!-- Submit Button -->
            <button type="submit" class="submit-button">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="12" y1="18" x2="12" y2="12"></line>
                    <line x1="9" y1="15" x2="15" y2="15"></line>
                </svg>
                Ambil Nomer Antrean
            </button>
        </form>
    </div>
</div>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.antrean-wrapper {
    max-width: 480px;
    margin: 0 auto;
    background: #ffffff;
    min-height: 100vh;
    padding: 0;
}

.antrean-header {
    display: flex;
    align-items: center;
    padding: 16px 20px;
    background: #ffffff;
    border-bottom: 1px solid #e5e7eb;
    position: sticky;
    top: 0;
    z-index: 10;
}

.back-button {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    margin-right: 12px;
    color: #374151;
    text-decoration: none;
    transition: opacity 0.2s;
}

.back-button:hover {
    opacity: 0.7;
}

.header-title {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin: 0;
}

.info-badge {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #fef3c7;
    padding: 10px 16px;
    margin: 16px 20px;
    border-radius: 8px;
    font-size: 13px;
    color: #92400e;
}

.info-badge svg {
    flex-shrink: 0;
    color: #d97706;
}

.antrean-form {
    padding: 0 20px 24px;
}

.form-section {
    margin-bottom: 24px;
}

.form-label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 8px;
}

.nomor-antrean {
    font-size: 32px;
    font-weight: 700;
    color: #111827;
    padding: 12px 0;
}

.total-info {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    padding: 4px 0;
}

.select-wrapper {
    position: relative;
}

.form-select {
    width: 100%;
    padding: 12px 40px 12px 16px;
    font-size: 14px;
    color: #6b7280;
    background: #ffffff;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    appearance: none;
    cursor: pointer;
    transition: all 0.2s;
}

.form-select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-select.is-invalid {
    border-color: #ef4444;
}

.select-arrow {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    color: #6b7280;
}

.error-message {
    font-size: 12px;
    color: #ef4444;
    margin-top: 6px;
}

.submit-button {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 14px 24px;
    font-size: 15px;
    font-weight: 600;
    color: #ffffff;
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
    margin-top: 32px;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.25);
}

.submit-button:hover {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.35);
    transform: translateY(-1px);
}

.submit-button:active {
    transform: translateY(0);
}

/* Responsive Design */
@media (min-width: 768px) {
    .antrean-wrapper {
        margin-top: 40px;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        min-height: auto;
    }

    .antrean-header {
        border-radius: 12px 12px 0 0;
    }

    .info-badge {
        margin: 20px 24px;
    }

    .antrean-form {
        padding: 0 24px 32px;
    }

    .form-section {
        margin-bottom: 28px;
    }

    .submit-button {
        margin-top: 40px;
    }
}

@media (min-width: 1024px) {
    .antrean-wrapper {
        max-width: 520px;
    }

    .header-title {
        font-size: 20px;
    }

    .nomor-antrean {
        font-size: 36px;
    }

    .total-info {
        font-size: 20px;
    }
}
</style>
@endsection

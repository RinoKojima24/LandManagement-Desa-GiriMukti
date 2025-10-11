@extends('layouts.form')

@push('styles')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        background-color: #f8f9fa;
        color: #333;
        line-height: 1.6;
    }

    .container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        min-height: 100vh;
    }

    .card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        padding: 24px;
        margin-bottom: 20px;
    }

    .header {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e0e0e0;
    }

    .back-btn {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        padding: 8px 12px;
        margin-right: 15px;
        color: #333;
        transition: all 0.3s ease;
        border-radius: 8px;
    }

    .back-btn:hover {
        background-color: #f5f5f5;
        transform: translateX(-2px);
    }

    h1 {
        font-size: 22px;
        font-weight: 600;
        color: #1a1a1a;
    }

    .info-box {
        background-color: #f0f8ff;
        border-left: 4px solid #333;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 24px;
        font-size: 14px;
        color: #555;
    }

    .form-group {
        margin-bottom: 24px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        font-weight: 500;
        color: #444;
    }

    label .required {
        color: #dc3545;
        margin-left: 2px;
    }

    label .optional {
        color: #999;
        font-weight: 400;
        font-size: 13px;
        margin-left: 4px;
    }

    input[type="text"],
    select,
    textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.3s ease;
        background-color: #fff;
    }

    input[type="text"]:focus,
    select:focus,
    textarea:focus {
        outline: none;
        border-color: #333;
        box-shadow: 0 0 0 3px rgba(51, 51, 51, 0.1);
    }

    input[type="text"]:disabled {
        background-color: #f5f5f5;
        color: #999;
        cursor: not-allowed;
    }

    input[type="text"]::placeholder,
    textarea::placeholder {
        color: #999;
    }

    select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
        padding-right: 45px;
        cursor: pointer;
    }

    select option:first-child {
        color: #999;
    }

    textarea {
        min-height: 150px;
        resize: vertical;
        line-height: 1.5;
    }

    .char-counter {
        text-align: right;
        font-size: 12px;
        color: #999;
        margin-top: 4px;
    }

    .char-counter.warning {
        color: #ff9800;
    }

    .char-counter.danger {
        color: #dc3545;
    }

    .submit-btn {
        width: 100%;
        padding: 16px;
        background-color: #333;
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 30px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .submit-btn:hover {
        background-color: #000;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    }

    .submit-btn:active {
        transform: translateY(0);
    }

    .submit-btn:disabled {
        background-color: #999;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-danger {
        background-color: #f8d7da;
        border: 1px solid #f5c2c7;
        color: #842029;
    }

    .alert-success {
        background-color: #d1e7dd;
        border: 1px solid #badbcc;
        color: #0f5132;
    }

    .error-message {
        color: #dc3545;
        font-size: 12px;
        margin-top: 4px;
        display: none;
    }

    .form-group.error input,
    .form-group.error select,
    .form-group.error textarea {
        border-color: #dc3545;
    }

    .form-group.error .error-message {
        display: block;
    }

    /* Loading Spinner */
    .spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #ffffff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 0.8s linear infinite;
        margin-right: 8px;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Responsive Tablet */
    @media (min-width: 768px) {
        .container {
            padding: 40px;
        }

        .card {
            padding: 32px;
        }

        h1 {
            font-size: 26px;
        }

        .form-group {
            margin-bottom: 28px;
        }

        label {
            font-size: 15px;
            margin-bottom: 10px;
        }

        input[type="text"],
        select,
        textarea {
            padding: 14px 18px;
            font-size: 15px;
        }
    }

    /* Responsive Desktop */
    @media (min-width: 1024px) {
        .container {
            max-width: 700px;
            padding: 60px 40px;
        }

        .card {
            padding: 40px;
        }

        .header {
            margin-bottom: 40px;
            padding-bottom: 20px;
        }

        h1 {
            font-size: 28px;
        }

        .form-group {
            margin-bottom: 32px;
        }

        label {
            font-size: 16px;
            margin-bottom: 10px;
        }

        input[type="text"],
        select,
        textarea {
            padding: 16px 20px;
            font-size: 16px;
            border-radius: 10px;
        }

        .submit-btn {
            padding: 18px;
            font-size: 18px;
            border-radius: 12px;
            margin-top: 40px;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="card">
        <div class="header">
            <a href="{{ route('home') }}">
                <button class="back-btn" type="button">←</button>
            </a>
            <h1>Kritik & Saran</h1>
        </div>

        <div class="info-box">
            💡 Masukan Anda sangat berarti untuk meningkatkan kualitas pelayanan kami. Sampaikan kritik atau saran dengan sopan dan konstruktif.
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form id="kritikSaranForm" action="{{ route('kritik_saran.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="nama">
                    Nama<span class="optional">(Opsional)</span>
                </label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    placeholder="Masukkan nama Anda"
                    value="{{ auth()->check() ? auth()->user()->nama_petugas : old('nama') }}"
                    {{ auth()->check() ? 'disabled' : '' }}
                >
                @if(auth()->check())
                    <input type="hidden" name="nama" value="{{ auth()->user()->name }}">
                @endif
                <div class="error-message">Nama tidak boleh lebih dari 100 karakter</div>
            </div>

            <div class="form-group">
                <label for="jenis">
                    Jenis<span class="required">*</span>
                </label>
                <select id="jenis" name="jenis" required>
                    <option value="" disabled selected>Pilih jenis</option>
                    <option value="kritik" {{ old('jenis') == 'kritik' ? 'selected' : '' }}>Kritik</option>
                    <option value="saran" {{ old('jenis') == 'saran' ? 'selected' : '' }}>Saran</option>
                </select>
                <div class="error-message">Jenis wajib dipilih</div>
            </div>

            <div class="form-group">
                <label for="pesan">
                    Pesan<span class="required">*</span>
                </label>
                <textarea
                    id="pesan"
                    name="pesan"
                    placeholder="Tuliskan kritik atau saran Anda di sini..."
                    maxlength="1000"
                    required
                >{{ old('pesan') }}</textarea>
                <div class="char-counter">
                    <span id="charCount">0</span> / 1000 karakter
                </div>
                <div class="error-message">Pesan wajib diisi</div>
            </div>

            <button type="submit" class="submit-btn" id="submitBtn">
                Kirim
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Character counter untuk textarea
    const pesanTextarea = document.getElementById('pesan');
    const charCount = document.getElementById('charCount');
    const charCounter = document.querySelector('.char-counter');

    function updateCharCount() {
        const length = pesanTextarea.value.length;
        charCount.textContent = length;

        // Update styling berdasarkan jumlah karakter
        if (length > 900) {
            charCounter.classList.add('danger');
            charCounter.classList.remove('warning');
        } else if (length > 800) {
            charCounter.classList.add('warning');
            charCounter.classList.remove('danger');
        } else {
            charCounter.classList.remove('warning', 'danger');
        }
    }

    // Update saat halaman dimuat (untuk old value)
    updateCharCount();

    // Update saat user mengetik
    pesanTextarea.addEventListener('input', updateCharCount);

    // Form validation sebelum submit
    document.getElementById('kritikSaranForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        const pesan = document.getElementById('pesan').value.trim();
        const jenis = document.getElementById('jenis').value;

        // Validasi pesan tidak boleh kosong
        if (pesan.length === 0) {
            e.preventDefault();
            alert('Pesan tidak boleh kosong');
            document.getElementById('pesan').focus();
            return false;
        }

        // Validasi pesan minimal 10 karakter
        if (pesan.length < 10) {
            e.preventDefault();
            alert('Pesan minimal 10 karakter');
            document.getElementById('pesan').focus();
            return false;
        }

        // Validasi jenis harus dipilih
        if (!jenis) {
            e.preventDefault();
            alert('Silakan pilih jenis kritik atau saran');
            document.getElementById('jenis').focus();
            return false;
        }

        // Disable button dan tampilkan loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner"></span>Mengirim...';

        // Form akan submit secara normal ke server
        return true;
    });

    // Reset error state saat user mulai mengetik
    const formInputs = document.querySelectorAll('input, select, textarea');
    formInputs.forEach(input => {
        input.addEventListener('input', function() {
            this.closest('.form-group')?.classList.remove('error');
        });
    });

    // Auto-resize textarea
    pesanTextarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
</script>
@endpush

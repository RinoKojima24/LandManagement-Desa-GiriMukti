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

    input[type="text"],
    input[type="number"],
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
    input[type="number"]:focus,
    select:focus,
    textarea:focus {
        outline: none;
        border-color: #333;
        box-shadow: 0 0 0 3px rgba(51, 51, 51, 0.1);
    }

    input[type="text"]::placeholder,
    input[type="number"]::placeholder,
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
        min-height: 120px;
        resize: vertical;
        line-height: 1.5;
    }

    .upload-section {
        margin-bottom: 24px;
    }

    .upload-label {
        display: block;
        margin-bottom: 16px;
        font-size: 14px;
        font-weight: 500;
        color: #444;
    }

    .upload-group {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
        padding: 12px;
        background-color: #f8f9fa;
        border-radius: 8px;
        border: 2px dashed #e0e0e0;
        transition: all 0.3s ease;
    }

    .upload-group:hover {
        border-color: #333;
        background-color: #f5f5f5;
    }

    .upload-group.has-file {
        border-style: solid;
        border-color: #28a745;
        background-color: #f0f8f4;
    }

    .upload-input {
        flex: 1;
        font-size: 14px;
        color: #999;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .upload-input.uploaded {
        color: #28a745;
        font-weight: 500;
    }

    .upload-btn {
        width: 44px;
        height: 44px;
        border: 2px solid #333;
        border-radius: 8px;
        background-color: #fff;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .upload-btn:hover {
        background-color: #333;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .upload-btn:hover svg {
        stroke: #fff;
    }

    .upload-btn svg {
        width: 20px;
        height: 20px;
        transition: stroke 0.3s ease;
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

    input[type="file"] {
        display: none;
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
        input[type="number"],
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
        input[type="number"],
        select,
        textarea {
            padding: 16px 20px;
            font-size: 16px;
            border-radius: 10px;
        }

        .upload-btn {
            width: 48px;
            height: 48px;
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
            <a href="{{ url('operator') }}">
                <button class="back-btn" type="button">←</button>
            </a>
            <h1>Tambah Operator</h1>
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

      <form
      action="{{ url('operator/'.$operator->id) }}"
      method="POST"
      enctype="multipart/form-data">
    @csrf
    @method('PUT')
            <input type="hidden" name="type" value="keterangan">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="namaLengkap">
                            Nama Lengkap<span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="namaLengkap"
                            name="namaLengkap"
                            class="form-control"
                            placeholder="Masukkan nama lengkap"
                            value="{{ old('namaLengkap', $operator->nama_petugas) }}"
                            required
                        >
                        <div class="error-message">Nama lengkap wajib diisi</div>
                    </div>

                    <div class="form-group">
                        <label for="nik">
                            NIK<span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="nik"
                            name="nik"
                            class="form-control"
                            placeholder="Masukkan 16 digit NIK"
                            maxlength="16"
                            pattern="[0-9]{16}"
                            value="{{ old('nik', $operator->nik) }}"
                            required
                        >
                        <div class="error-message">NIK harus 16 digit angka</div>
                    </div>
                    <div class="form-group">
                        <label for="nik">
                            NIP<span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="nip"
                            name="nip"
                            class="form-control"
                            placeholder="Masukkan 16 digit NIP"
                            maxlength="16"
                            pattern="[0-9]{16}"
                            value="{{ old('nip', $operator->nip) }}"
                            required
                        >
                        <div class="error-message">NIK harus 16 digit angka</div>
                    </div>
                    <div class="upload-section">
                        <div class="upload-label">Upload Dokumen<span class="required">*</span></div>

                        <div class="upload-group" id="ktpGroup">
                            <div class="upload-input" id="ktpFileName">KTP (JPG, PNG, PDF - Maks 2MB)</div>
                            <label for="ktpUpload" class="upload-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/>
                                </svg>
                            </label>
                            <input
                                type="file"
                                id="ktpUpload"
                                name="foto_ktp"
                                accept="image/jpeg,image/jpg,image/png,.pdf"

                            >
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="alamat">
                            No. Whatsapp<span class="required">*</span>
                        </label>
                        <input type="text" class="form-control" name="no_telepon" id="no_telepon" value="{{ old('no_telepon', $operator->no_telepon) }}" placeholder="Masukan nomor whatsapp!" required>
                        <div class="error-message">Alamat wajib diisi</div>
                    </div>

                    <div class="form-group">
                        <label for="alamat">
                            Email<span class="required">*</span>
                        </label>
                        <input type="text" required class="form-control" name="email" id="email" value="{{ old('email', $operator->email) }}" placeholder="Masukan Email" required>
                    </div>

                    <div class="form-group">
                        <label for="alamat">
                            Jabatan<span class="required">*</span>
                        </label>
                        <input type="text" class="form-control" name="jabatan" id="jabatan" value="{{ old('jabatan', $operator->jabatan) }}" placeholder="Masukan Jabatan" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="submit-btn" id="submitBtn">
                Simpan Data
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Validasi ukuran file (max 2MB)
    function validateFileSize(file, maxSizeMB = 2) {
        const maxSize = maxSizeMB * 1024 * 1024; // Convert to bytes
        if (file.size > maxSize) {
            alert(`Ukuran file terlalu besar. Maksimal ${maxSizeMB}MB`);
            return false;
        }
        return true;
    }

    // Handle KTP upload
    document.getElementById('ktpUpload').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const ktpGroup = document.getElementById('ktpGroup');
        const ktpFileName = document.getElementById('ktpFileName');

        if (file) {
            if (validateFileSize(file)) {
                ktpFileName.textContent = file.name;
                ktpFileName.classList.add('uploaded');
                ktpGroup.classList.add('has-file');
            } else {
                this.value = ''; // Reset input
            }
        }
    });

    // Handle dokumen pendukung upload
    // document.getElementById('pendukungUpload').addEventListener('change', function(e) {
    //     const file = e.target.files[0];
    //     const pendukungGroup = document.getElementById('pendukungGroup');
    //     const pendukungFileName = document.getElementById('pendukungFileName');

    //     if (file) {
    //         if (validateFileSize(file)) {
    //             pendukungFileName.textContent = file.name;
    //             pendukungFileName.classList.add('uploaded');
    //             pendukungGroup.classList.add('has-file');
    //         } else {
    //             this.value = ''; // Reset input
    //         }
    //     }
    // });

    // Validasi NIK (hanya angka)
    document.getElementById('nik').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Form validation sebelum submit
    // document.getElementById('suratForm').addEventListener('submit', function(e) {
    //     const submitBtn = document.getElementById('submitBtn');
    //     const nik = document.getElementById('nik').value;

    //     // Validasi NIK
    //     if (nik.length !== 16) {
    //         e.preventDefault();
    //         alert('NIK harus 16 digit');
    //         document.getElementById('nik').focus();
    //         return false;
    //     }

    //     // Disable button dan tampilkan loading
    //     submitBtn.disabled = true;
    //     submitBtn.innerHTML = '<span class="spinner"></span>Mengirim...';

    //     // Form akan submit secara normal ke server
    //     return true;
    // });

    // Reset error state saat user mulai mengetik
    // const formInputs = document.querySelectorAll('input, select, textarea');
    // formInputs.forEach(input => {
    //     input.addEventListener('input', function() {
    //         this.closest('.form-group')?.classList.remove('error');
    //     });
    // });
</script>
@endpush

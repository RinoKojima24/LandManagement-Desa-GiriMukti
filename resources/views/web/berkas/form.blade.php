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
            <a href="{{ url('berkas/'.$query->id.'?tipe_surat=0') }}">
                <button class="back-btn" type="button">←</button>
            </a>
            @if($_GET['list_surat'] == "1")
                <h1>Surat Pernyataan Pemasangan Tanda Batas dan Persetujuan Pihak Yang Berbatasan</h1>
            @elseif($_GET['list_surat'] == "2")
                <h1>Berita Acara Pengukuran Bidang Tanah</h1>
            @elseif($_GET['list_surat'] == "3")
                <h1>SURAT PERNYATAAN PENGUASAAN FISIK BIDANG TANAH</h1>
            @endif
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

        <form id="suratForm" action="{{ url('berkas/'.$query->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="list_surat" value="{{ $_GET['list_surat'] }}">
            <input type="hidden" name="surat_id" value="{{ $surat->id ?? "" }}">
            @if($_GET['list_surat'] == "1")
            <div id="list_surat_{{ $_GET['list_surat'] }}">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <h3>Menyetujui Pihak yang Berbatasan</h3>
                        <br>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <h3 style="font-size: 24px;"><b>Sebelah Utara</b></h3>
                        <div class="form-group">
                            <label for="namaLengkap">
                                Nama<span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="sebelah_utara_nama"
                                name="sebelah_utara_nama"
                                class="form-control"
                                placeholder="Masukkan nama lengkap"
                                value="{{ old('sebelah_utara_nama', @$surat->sebelah_utara_nama) }}"
                                required
                            >
                            <div class="error-message">Nama lengkap wajib diisi</div>
                        </div>
                        <div class="form-group">
                            <label for="namaLengkap">
                                NiK<span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="sebelah_utara_nik"
                                name="sebelah_utara_nik"
                                class="form-control"
                                placeholder="Masukkan NIK"
                                value="{{ old('sebelah_utara_nik',  @$surat->sebelah_utara_nik) }}"
                                required
                            >
                            <div class="error-message">Nama lengkap wajib diisi</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <h3 style="font-size: 24px;"><b>Sebelah Timur</b></h3>
                        <div class="form-group">
                            <label for="namaLengkap">
                                Nama<span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="sebelah_timur_nama"
                                name="sebelah_timur_nama"
                                class="form-control"
                                placeholder="Masukkan nama lengkap"
                                value="{{ old('sebelah_timur_nama',  @$surat->sebelah_timur_nama) }}"
                                required
                            >
                            <div class="error-message">Nama lengkap wajib diisi</div>
                        </div>
                        <div class="form-group">
                            <label for="namaLengkap">
                                NiK<span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="sebelah_timur_nik"
                                name="sebelah_timur_nik"
                                class="form-control"
                                placeholder="Masukkan NIK"
                                value="{{ old('sebelah_timur_nik',  @$surat->sebelah_timur_nik) }}"
                                required
                            >
                            <div class="error-message">Nama lengkap wajib diisi</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <h3 style="font-size: 24px;"><b>Sebelah Selatan</b></h3>
                        <div class="form-group">
                            <label for="namaLengkap">
                                Nama<span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="sebelah_selatan_nama"
                                name="sebelah_selatan_nama"
                                class="form-control"
                                placeholder="Masukkan Nama lengkap"
                                value="{{ old('sebelah_selatan_nama', @$surat->sebelah_selatan_nama) }}"
                                required
                            >
                            <div class="error-message">Nama lengkap wajib diisi</div>
                        </div>
                        <div class="form-group">
                            <label for="namaLengkap">
                                NiK<span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="sebelah_selatan_nik"
                                name="sebelah_selatan_nik"
                                class="form-control"
                                placeholder="Masukkan NIK"
                                value="{{ old('sebelah_selatan_nik', @$surat->sebelah_selatan_nik) }}"
                                required
                            >
                            <div class="error-message">Nama lengkap wajib diisi</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <h3 style="font-size: 24px;"><b>Sebelah Berat</b></h3>
                        <div class="form-group">
                            <label for="namaLengkap">
                                Nama<span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="sebelah_barat_nama"
                                name="sebelah_barat_nama"
                                class="form-control"
                                placeholder="Masukkan Nama lengkap"
                                value="{{ old('sebelah_barat_nama', @$surat->sebelah_barat_nama) }}"
                                required
                            >
                            <div class="error-message">Nama lengkap wajib diisi</div>
                        </div>
                        <div class="form-group">
                            <label for="namaLengkap">
                                NiK<span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="sebelah_barat_nik"
                                name="sebelah_barat_nik"
                                class="form-control"
                                placeholder="Masukkan NIK"
                                value="{{ old('sebelah_barat_nik', @$surat->sebelah_barat_nik) }}"
                                required
                            >
                            <div class="error-message">Nama lengkap wajib diisi</div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label for="namaLengkap">
                                Yang Membuat Pernyataan<span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="pembuat_pernyataan"
                                name="pembuat_pernyataan"
                                class="form-control"
                                placeholder="Masukkan Nama lengkap"
                                value="{{ old('pembuat_pernyataan', @$surat->pembuat_pernyataan) }}"
                                required
                            >
                            <div class="error-message">Nama lengkap wajib diisi</div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <h3 style="font-size: 24px;"><b>Ketua RT</b></h3>
                        <div class="form-group">
                            <label for="namaLengkap">
                                Nama Ketua RT<span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="nama_ketua_rt"
                                name="nama_ketua_rt"
                                class="form-control"
                                placeholder="Masukkan Nama lengkap"
                                value="{{ old('nama_ketua_rt', @$surat->nama_ketua_rt) }}"
                                required
                            >
                            <div class="error-message">Nama lengkap wajib diisi</div>
                        </div>
                        <div class="form-group">
                            <label for="namaLengkap">
                                RT Berapa<span class="required">*</span>
                            </label>
                            <input
                                type="number"
                                id="rt"
                                name="rt"
                                class="form-control"
                                placeholder="Masukkan NIK"
                                value="{{ old('rt', @$surat->rt) }}"
                                required
                            >
                            <div class="error-message">Nama lengkap wajib diisi</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @if($_GET['list_surat'] == "2")
                <div id="list_surat_{{ $_GET['list_surat'] }}">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            {{-- <h3 style="font-size: 24px;"><b>Tanggal dilaksanakan</b></h3> --}}
                            <div class="form-group">
                                <label for="namaLengkap">
                                    Tanggal dilaksanakan<span class="required">*</span>
                                </label>
                                <input
                                    type="date"
                                    id="tanggal_dilaksanakan"
                                    name="tanggal_dilaksanakan"
                                    class="form-control"
                                    placeholder="Masukkan nama lengkap"
                                    value="{{ old('tanggal_dilaksanakan', @$surat->tanggal_dilaksanakan) }}"
                                    required
                                >
                                <div class="error-message">Nama lengkap wajib diisi</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <h3 style="font-size: 24px;"><b>Keterangan Pengukur 1</b></h3>
                            <div class="form-group">
                                <label for="namaLengkap">
                                    Nama<span class="required">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="nama_1"
                                    name="nama_1"
                                    class="form-control"
                                    placeholder="Masukkan nama lengkap"
                                    value="{{ old('nama_1', @$surat->nama_1) }}"
                                >
                                <div class="error-message">Nama lengkap wajib diisi</div>
                            </div>
                            <div class="form-group">
                                <label for="namaLengkap">
                                    NIP<span class="required">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="nip_1"
                                    name="nip_1"
                                    class="form-control"
                                    placeholder="Masukkan NIP"
                                    value="{{ old('nip_1', @$surat->nip_1) }}"
                                >
                                <div class="error-message">Nama lengkap wajib diisi</div>
                            </div>
                            <div class="form-group">
                                <label for="namaLengkap">
                                    Jabatan<span class="required">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="jabatan_1"
                                    name="jabatan_1"
                                    class="form-control"
                                    placeholder="Masukkan Jabatan"
                                    value="{{ old('jabatan_1', @$surat->jabatan_1) }}"
                                >
                                <div class="error-message">Nama lengkap wajib diisi</div>
                            </div>
                            <div class="form-group">
                                <label for="namaLengkap">
                                    Tugas<span class="required">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="tugas_1"
                                    name="tugas_1"
                                    class="form-control"
                                    placeholder="Masukkan Tugas"
                                    value="{{ old('tugas_1', @$surat->tugas_1) }}"
                                >
                                <div class="error-message">Nama lengkap wajib diisi</div>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <h3 style="font-size: 24px;"><b>Keterangan Pengukur 2</b></h3>
                            <div class="form-group">
                                <label for="namaLengkap">
                                    Nama<span class="required">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="nama_2"
                                    name="nama_2"
                                    class="form-control"
                                    placeholder="Masukkan nama lengkap"
                                    value="{{ old('nama_2', @$surat->nama_2) }}"
                                >
                                <div class="error-message">Nama lengkap wajib diisi</div>
                            </div>
                            <div class="form-group">
                                <label for="namaLengkap">
                                    NIP<span class="required">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="nip_2"
                                    name="nip_2"
                                    class="form-control"
                                    placeholder="Masukkan NIP"
                                    value="{{ old('nip_2', @$surat->nip_2) }}"
                                >
                                <div class="error-message">Nama lengkap wajib diisi</div>
                            </div>
                            <div class="form-group">
                                <label for="namaLengkap">
                                    Jabatan<span class="required">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="jabatan_2"
                                    name="jabatan_2"
                                    class="form-control"
                                    placeholder="Masukkan Jabatan"
                                    value="{{ old('jabatan_2', @$surat->jabatan_2) }}"
                                >
                                <div class="error-message">Nama lengkap wajib diisi</div>
                            </div>
                            <div class="form-group">
                                <label for="namaLengkap">
                                    Tugas<span class="required">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="tugas_2"
                                    name="tugas_2"
                                    class="form-control"
                                    placeholder="Masukkan Tugas"
                                    value="{{ old('tugas_2', @$surat->tugas_2) }}"
                                >
                                <div class="error-message">Nama lengkap wajib diisi</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if($_GET['list_surat'] == "3")
                <div id="list_surat_{{ $_GET['list_surat'] }}">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="namaLengkap">
                                    Pembuat Pernyataan
                                </label>
                                <input
                                    type="text"
                                    id="pembuat_pernyataan"
                                    name="pembuat_pernyataan"
                                    class="form-control"
                                    placeholder="Masukkan nama lengkap"
                                    value="{{ old('pembuat_pernyataan', @$surat->pembuat_pernyataan) }}"
                                >
                                <div class="error-message">Nama lengkap wajib diisi</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="namaLengkap">
                                    Tahun Kuasa
                                </label>
                                <input
                                    type="number"
                                    id="tahun_kuasa"
                                    name="tahun_kuasa"
                                    class="form-control"
                                    placeholder="Masukkan nama lengkap"
                                    value="{{ old('tahun_kuasa', @$surat->tahun_kuasa) }}"
                                >
                                <div class="error-message">Nama lengkap wajib diisi</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="namaLengkap">
                                    Nama Peroleh
                                </label>
                                <input
                                    type="text"
                                    id="nama_peroleh"
                                    name="nama_peroleh"
                                    class="form-control"
                                    placeholder="Masukkan nama lengkap"
                                    value="{{ old('nama_peroleh', @$surat->nama_peroleh) }}"
                                >
                                <div class="error-message">Nama lengkap wajib diisi</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <h3 style="font-size: 21px;"><b>Saksi 1</b></h3>
                            <div class="form-group">
                                <label for="namaLengkap">
                                    Nama Saksi
                                </label>
                                <input
                                    type="text"
                                    id="nama_saksi_1"
                                    name="nama_saksi_1"
                                    class="form-control"
                                    placeholder="Masukkan nama lengkap"
                                    value="{{ old('nama_saksi_1', @$surat->nama_saksi_1) }}"
                                >
                                <div class="error-message">Nama lengkap wajib diisi</div>
                            </div>
                            <div class="form-group">
                                <label for="namaLengkap">
                                    NIK Saksi
                                </label>
                                <input
                                    type="text"
                                    id="nik_saksi_1"
                                    name="nik_saksi_1"
                                    class="form-control"
                                    placeholder="Masukkan NIK"
                                    value="{{ old('nik_saksi_1', @$surat->nik_saksi_1) }}"
                                >
                                <div class="error-message">Nama lengkap wajib diisi</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <h3 style="font-size: 21px;"><b>Saksi 2</b></h3>
                            <div class="form-group">
                                <label for="namaLengkap">
                                    Nama Saksi
                                </label>
                                <input
                                    type="text"
                                    id="nama_saksi_2"
                                    name="nama_saksi_2"
                                    class="form-control"
                                    placeholder="Masukkan nama lengkap"
                                    value="{{ old('nama_saksi_2', @$surat->nama_saksi_2) }}"
                                >
                                <div class="error-message">Nama lengkap wajib diisi</div>
                            </div>
                            <div class="form-group">
                                <label for="namaLengkap">
                                    NIK Saksi
                                </label>
                                <input
                                    type="text"
                                    id="nik_saksi_2"
                                    name="nik_saksi_2"
                                    class="form-control"
                                    placeholder="Masukkan NIK"
                                    value="{{ old('nik_saksi_2', @$surat->nik_saksi_2) }}"
                                >
                                <div class="error-message">Nama lengkap wajib diisi</div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <h3 style="font-size: 21px;"><b>Data RT</b></h3>
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="namaLengkap">
                                            Nomor RT
                                        </label>
                                        <input
                                            type="text"
                                            id="nomor_rt"
                                            name="nomor_rt"
                                            class="form-control"
                                            placeholder="592.2/0001"
                                            value="{{ old('nomor_rt', @$surat->nomor_rt) }}"
                                        >
                                        <div class="error-message">Nama lengkap wajib diisi</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="namaLengkap">
                                            Tanggal RT
                                        </label>
                                        <input
                                            type="date"
                                            id="tanggal_rt"
                                            name="tanggal_rt"
                                            class="form-control"
                                            value="{{ old('tanggal_rt', @$surat->tanggal_rt) }}"
                                        >
                                        <div class="error-message">Nama lengkap wajib diisi</div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="namaLengkap">
                                            RT Berapa
                                        </label>
                                        <input
                                            type="number"
                                            id="rt"
                                            name="rt"
                                            class="form-control"
                                            value="{{ old('rt', @$surat->rt) }}"
                                        >
                                        <div class="error-message">Nama lengkap wajib diisi</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="namaLengkap">
                                            Nama RT
                                        </label>
                                        <input
                                            type="text"
                                            id="nama_rt"
                                            name="nama_rt"
                                            class="form-control"
                                            placeholder="Nama RT"
                                            value="{{ old('nama_rt', @$surat->nama_rt) }}"
                                        >
                                        <div class="error-message">Nama lengkap wajib diisi</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <h3 style="font-size: 21px;"><b>Kepala Desa Girimukti</b></h3>
                            <div class="form-group">
                                <label for="namaLengkap">
                                    Nomor
                                </label>
                                <input
                                    type="text"
                                    id="nomor_kades"
                                    name="nomor_kades"
                                    class="form-control"
                                    value="{{ old('nomor_kades', @$surat->nomor_kades) }}"
                                >
                                <div class="error-message">Nama lengkap wajib diisi</div>
                            </div>
                            <div class="form-group">
                                <label for="namaLengkap">
                                    Tanggal
                                </label>
                                <input
                                    type="date"
                                    id="tanggal_kades"
                                    name="tanggal_kades"
                                    class="form-control"
                                    value="{{ old('tanggal_kades', @$surat->tanggal_kades) }}"
                                >
                                <div class="error-message">Nama lengkap wajib diisi</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <h3 style="font-size: 21px;"><b>Camat Penajam</b></h3>
                            <div class="form-group">
                                <label for="namaLengkap">
                                    Nomor
                                </label>
                                <input
                                    type="text"
                                    id="nomor_camat"
                                    name="nomor_camat"
                                    class="form-control"
                                    value="{{ old('nomor_camat', @$surat->nomor_camat) }}"
                                >
                                <div class="error-message">Nama lengkap wajib diisi</div>
                            </div>
                            <div class="form-group">
                                <label for="namaLengkap">
                                    Tanggal
                                </label>
                                <input
                                    type="date"
                                    id="tanggal_penajam"
                                    name="tanggal_penajam"
                                    class="form-control"
                                    value="{{ old('tanggal_penajam', @$surat->tanggal_penajam) }}"
                                >
                                <div class="error-message">Nama lengkap wajib diisi</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <button type="submit" class="submit-btn" id="submitBtn">
                Simpan
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
    document.getElementById('pendukungUpload').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const pendukungGroup = document.getElementById('pendukungGroup');
        const pendukungFileName = document.getElementById('pendukungFileName');

        if (file) {
            if (validateFileSize(file)) {
                pendukungFileName.textContent = file.name;
                pendukungFileName.classList.add('uploaded');
                pendukungGroup.classList.add('has-file');
            } else {
                this.value = ''; // Reset input
            }
        }
    });

    // Validasi NIK (hanya angka)
    document.getElementById('nik').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Form validation sebelum submit
    document.getElementById('suratForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        const nik = document.getElementById('nik').value;

        // Validasi NIK
        if (nik.length !== 16) {
            e.preventDefault();
            alert('NIK harus 16 digit');
            document.getElementById('nik').focus();
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
</script>
@endpush

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Tambah Data Peta Tanah</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">


    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        #map {
            height: 100vh;
            width: 100%;
        }

        .map-tools {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            z-index: 9999;
        }
        .tool-btn {
            background: white;
            border-radius: 8px;
            padding: 10px 12px;
            border: 1px solid #ccc;
            font-size: 14px;
            cursor: pointer;
            box-shadow: 0 3px 6px rgba(0,0,0,0.2);
            transition: 0.2s;
        }
        .tool-btn:hover {
            background: #f3f3f3;
        }

/* Panel fullscreen modal */
#inputPanel {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 70%;
    height: 70%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.4);
    z-index: 100000;
    display: none; /* default: sembunyi */
    overflow-y: auto;
}

#inputPanel h3 {
    margin-top: 0;
    font-size: 20px;
}

#inputPanel input,
#inputPanel textarea {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    margin-bottom: 15px;
    font-size: 15px;
    border-radius: 8px;
    border: 1px solid #ccc;
}

/* Close button dalam panel */
#closePanelBtn {
    position: absolute;
    top: 15px;
    right: 20px;
    background: #ff5555;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 8px 14px;
    cursor: pointer;
}

#closePanelBtn:hover {
    background: #ff0000;
}

    </style>


<style>
    .upload-section {
        margin-bottom: 20px;
        font-family: Arial, sans-serif;
    }

    .upload-label {
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
    }

    .required {
        color: red;
        margin-left: 3px;
    }

    .upload-group {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border: 2px dashed #5a5a5a;
        border-radius: 10px;
        padding: 18px;
        background: #f6f6f6;
        position: relative;
    }

    .upload-input {
        font-size: 14px;
        color: #666;
    }

    .upload-btn {
        cursor: pointer;
        padding: 6px 10px;
        border-radius: 8px;
        background: #ffffff;
        border: 1px solid #ccc;
        display: flex;
        align-items: center;
    }

    .upload-btn svg {
        width: 22px;
        height: 22px;
        color: #444;
    }

input[type="file"] {
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    top: 0;
    left: 0;
}

</style>
</head>

<body>

    <div id="map"></div>

    <div class="map-tools">
        {{-- <button class="tool-btn" onclick="zoomIn()">Zoom +</button>
        <button class="tool-btn" onclick="zoomOut()">Zoom -</button> --}}
        <select id="mapType" style="top:10px; right:10px;">
            <option value="street">Peta Biasa</option>
            <option value="satellite">Satelit</option>
        </select>
        <button class="tool-btn" id="togglePanelBtn">Data Tanah</button>
        <button class="tool-btn" onclick="goToMyLocation()">📍 Lokasi Saya</button>
        <button class="tool-btn" onclick="addMarkerMode()">📌 Tambah Marker</button>
        <hr>
        <button class="tool-btn" onclick="polygonMode()">⬛ Polygon Mode</button>
        <button class="tool-btn" onclick="finishPolygon()">✔ Selesai Polygon</button>
        <button class="tool-btn" onclick="deletePolygon()">🗑 Hapus Polygon</button>
        <hr>
        <button class="tool-btn" onclick="jalanMode()">🛣 Jalan Mode</button>
        <button class="tool-btn" onclick="finishJalan()">✔ Selesai Jalan</button>
        <button class="tool-btn" onclick="deleteJalan()">🗑 Hapus Jalan</button>

    </div>

        <!-- Panel Input -->
    <div id="inputPanel">
        <button id="closePanelBtn">Close</button>
        <h3>Tambah Data Peta Tanah</h3>
{{--
        <label>Marker Position:</label>

        <label>Polygon Data:</label> --}}

                <!-- Search Box -->
        <form action="{{ url('tanah') }}" method="POST" class="mb-3" enctype="multipart/form-data">
            @csrf
            <div id="content2" class="tab-content">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Terjadi kesalahan:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="row">
                    <div class="form-group col-sm-12">
                        <br>
                        <select name="opsi" class="form-control" id="opsi">
                            <option value="0">Pendaftaran Pertama</option>
                            <option value="1">Pendaftaran Peralihan Hak</option>
                            <option value="2">Surat Ukur</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="">Jalan (Polyline)</label>
                        <textarea
                            id="jalan"
                            name="titik_kordinat_jalan"
                            rows="6"
                            class="form-control"
                            readonly
                            placeholder="lat,lng per baris"
                        ></textarea>
                    </div>
                </div>
                <div id="pendaftaran_pertama_form" class="row">
                    <div class="form-group col-sm-12 col-md-12">
                        <center><h1>Pendaftaran Pertama</h1></center>
                    </div>
                    <div class="form-group col-sm-12 col-md-12">
                    <hr>
                    <center><h3>HAK MILIK</h3></center>
                    </div>
                    <div class="form-group col-sm-12 col-md-3">
                        <input type="text" name="hak" class="form-control" placeholder="Hak" id="hak">
                    </div>
                    <div class="form-group col-sm-12 col-md-3">
                        <input type="text" name="nomor" class="form-control" placeholder="Nomor" id="nomor">
                    </div>
                    <div class="form-group col-sm-12 col-md-3">
                        <input type="text" name="desa_kel" class="form-control" placeholder="Desa Kelurahan" id="desa_kel">
                    </div>
                    <div class="form-group col-sm-12 col-md-3">
                        <input type="date" name="tanggal_berakhirnya_hak" class="form-control" placeholder="Tanggal Berakhirnya Hak" id="tanggal_berakhirnya_hak">
                    </div>
                    <div class="form-group col-sm-12 col-md-12">
                    <hr>
                    <center><h3>NIB</h3></center>
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        <input type="text" name="nib" class="form-control" placeholder="NIB" id="nib">
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        <input type="text" name="letak_tanah" class="form-control" placeholder="Letak Tanah" id="letak_tanah">
                    </div>
                    <div class="form-group col-sm-12 col-md-12">
                    <hr>
                    <center><h3>ASAL HAK</h3></center>
                    </div>
                    <div class="form-group col-sm-12 col-md-4">
                        <input type="text" name="konversi" class="form-control" placeholder="Konversi" id="konversi">
                    </div>
                    <div class="form-group col-sm-12 col-md-4">
                        <input type="text" name="pemberian_hak" class="form-control" placeholder="Pemberian Hak" id="pemberian_hak">
                    </div>
                    <div class="form-group col-sm-12 col-md-4">
                        <select name="pemecahan" class="form-control" id="pemecahan">
                            <option value="0">Pemecahan</option>
                            <option value="1">Pemisahan</option>
                            <option value="2">Penggabungan Bidang</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-12 col-md-12">
                    <hr>
                    <center><h3>DASAR PENDAFTARAN</h3></center>
                    </div>
                    <div class="form-group col-sm-12 col-md-4">
                        <label for="">Daftar Isian 202</label>
                        <input type="date" name="tgl_konversi" class="form-control" placeholder="Tanggal Konversi" id="tgl_konversi">
                        <input type="text" name="no_konversi" class="form-control" placeholder="Nomor Konversi" id="no_konversi">
                    </div>
                    <div class="form-group col-sm-12 col-md-4">
                        <label for="">Surat Keputusan</label>
                        <input type="date" name="tgl_pemberian_hak" class="form-control" placeholder="Tanggal Keputusan" id="tgl_pemberian_hak">
                        <input type="text" name="no_pemberian_hak" class="form-control" placeholder="Nomor Keputusan" id="no_pemberian_hak">
                    </div>
                    <div class="form-group col-sm-12 col-md-4">
                        <label for="">Permohonan Pemecahan / Pemisahan / Penggabungan Bidang</label>
                        <input type="date" name="tgl_pemecahan" class="form-control" placeholder="Tanggal Permohonan Pemecahan / Pemisahan / Penggabungan Bidang" id="tgl_pemecahan">
                        <input type="text" name="no_pemecahan" class="form-control" placeholder="Nomor Permohonan Pemecahan / Pemisahan / Penggabungan Bidang" id="no_pemecahan">
                    </div>
                    <div class="form-group col-sm-12 col-md-12">
                        <hr>
                        <center><h3>SURAT UKUR</h3></center>
                    </div>
                    <div class="form-group col-sm-12 col-md-4">
                        <input type="date" name="tgl_surat_ukur" class="form-control" placeholder="Tanggal" id="tgl_surat_ukur">
                    </div>
                    <div class="form-group col-sm-12 col-md-4">
                        <input type="text" name="no_surat_ukur" class="form-control" placeholder="No." id="no_surat_ukur">
                    </div>
                    <div class="form-group col-sm-12 col-md-4">
                        <input type="text" name="luas_surat_ukur" class="form-control" placeholder="Luas" id="luas_surat_ukur">
                    </div>
                    <div class="form-group col-sm-12 col-md-12">
                        <hr>
                        <center><h3>NAMA PEMEGANG HAK</h3></center>
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        <input type="text" name="nama_pemegang_hak" class="form-control" placeholder="Nama Pemegang Hak" value="{{ $warga->nama_petugas }}" id="nama_pemegang_hak">
                    </div>
                    <div class="form-group col-sm-12 col-md-6">
                        <input type="date" name="tanggal_lahir_akta_pendirian" class="form-control" placeholder="Tanggal lahir / akta pendirian" id="tanggal_lahir_akta_pendirian">
                    </div>
                    <div class="form-group col-sm-12 col-md-12">
                        <hr>
                        <center><h3>PENUNJUK</h3></center>
                    </div>
                    <div class="form-group col-sm-12 col-md-12">
                        <textarea name="petunjuk" id="petunjuk" class="form-control" cols="30" rows="2"></textarea>
                    </div>
                </div>
                <br>
                <div id="pendaftaran_peralihan_hak_form" class="row container" style="display:none;">
                        <div class="form-group col-sm-12 col-md-12">
                            <center><h3>Pendaftaran Peralihan Hak. Pembebanan dan Pencatatan Lainnya</h3></center>
                        </div>
                    <div class="form-group col-sm-12 col-md-12">
                        <center>
                            <button type="button" id="addRow" class="btn btn-primary mb-3">+ Tambah Baris</button>
                        </center>
                    </div>

                    <div id="multipleRows"></div>
                </div>
                <div id="surat_ukur_form" class="row container" style="display:none;">
                    <div class="form-group col-sm-12 col-md-12">
                        <h2 style="text-align: center;">Surat Ukur</h2>
                    </div>
                    <div class="form-group col-sm-12 col-md-12">
                        <label for="">Nomor</label>
                        <input type="text" name="nomor_surat" placeholder="Nomor" id="nomor_surat" class="form-control">
                    </div>
                    <div class="form-group col-sm-12 col-md-12">
                        <hr>
                    </div>
                    <div class="row container">
                        <div class="form-group col-sm-12 col-md-3">
                            <input type="text" name="provinsi" id="provinsi" placeholder="Provinsi" class="form-control">
                        </div>
                        <div class="form-group col-sm-12 col-md-3">
                            <input type="text" name="kabupaten" id="kabupaten" placeholder="Kabupaten / Kota" class="form-control">
                        </div>
                        <div class="form-group col-sm-12 col-md-3">
                            <input type="text" name="kecamatan" id="kecamatan" placeholder="Kecamatan" class="form-control">
                        </div>
                        <div class="form-group col-sm-12 col-md-3">
                            <input type="text" name="desa" id="desa" placeholder="Desa / Kelurahan" class="form-control">
                        </div>
                    </div>

                    <div class="row container">
                        <div class="form-group col-sm-12 col-md-12">
                            <hr>
                        </div>
                        <div class="form-group col-sm-12 col-md-3">
                            <input type="text" name="peta" id="peta" placeholder="Peta" class="form-control">
                        </div>
                        <div class="form-group col-sm-12 col-md-3">
                            <input type="text" name="nomor_peta" id="nomor_peta" placeholder="Nomor Peta" class="form-control">
                        </div>
                        <div class="form-group col-sm-12 col-md-3">
                            <input type="text" name="lembar" id="lembar" placeholder="Lembar" class="form-control">
                        </div>
                        <div class="form-group col-sm-12 col-md-3">
                            <input type="text" name="kotak" id="kotak" placeholder="Kotak" class="form-control">
                        </div>
                    </div>
                    <div class="row container">
                        <div class="form-group col-sm-12 col-md-12">
                            <hr>
                        </div>
                        <div class="form-group col-sm-12 col-md-12">
                            <label for="">Keadaan Tanah</label>
                            <textarea name="keadaan_tanah" id="keadaan_tanah" class="form-control" cols="30" rows="2" ></textarea>
                        </div>
                        <div class="form-group col-sm-12 col-md-12">
                            <label for="">Tanda Tanda Batas</label>
                            <textarea name="tanda_tanda_batas" id="tanda_tanda_batas" class="form-control" cols="30" rows="2" ></textarea>
                        </div>
                        <div class="form-group col-sm-12 col-md-12">
                            <label for="">Penunjukan dan penetapan batas</label>
                            <textarea name="penunjukan_dan_penetapan_batas" id="penunjukan_dan_penetapan_batas" class="form-control" cols="30" rows="2" ></textarea>
                        </div>
                        <div class="form-group col-sm-12 col-md-12">
                            <label for="">Hal lain-lain</label>
                            <textarea name="hal_lain_lain" id="hal_lain_lain" class="form-control" cols="30" rows="2" ></textarea>
                        </div>
                    </div>
                    <div class="row container">
                        <div class="form-group col-sm-12 col-md-12">
                            <hr>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            <input type="date" name="tgl_daftar_isian_208" id="tgl_daftar_isian_208" placeholder="Tanggal Daftar Isian 208" class="form-control">
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            <input type="text" name="no_daftar_isian_208" id="no_daftar_isian_208" placeholder="Nomor Daftar Isian 208" class="form-control">
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            <input type="date" name="tgl_daftar_isian_302" id="tgl_daftar_isian_302" placeholder="Tanggal Daftar Isian 302" class="form-control">
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            <input type="text" name="no_daftar_isian_302" id="no_daftar_isian_302" placeholder="Nomor Daftar Isian 302" class="form-control">
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            <input type="date" name="tgl_daftar_isian_307" id="tgl_daftar_isian_307" placeholder="Tanggal Daftar Isian 307" class="form-control">
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            <input type="text" name="no_daftar_isian_307" id="no_daftar_isian_307" placeholder="Nomor Daftar Isian 307" class="form-control">
                        </div>
                        <div class="form-group col-sm-12 col-md-12">
                            <label for="">Tanggal Penomoran Surat Ukur</label>
                            <input type="date" name="tanggal_penomoran_surat_ukur" id="tanggal_penomoran_surat_ukur" placeholder="Tanggal Penomoran Surat Ukur" class="form-control">
                        </div>
                    </div>
                    <div class="row container">
                        <div class="form-group col-sm-12 col-md-12">
                            <hr>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            <input type="text" name="nomor_surat_ukur" id="nomor_surat_ukur" placeholder="Nomor Surat Ukur" class="form-control">
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            <input type="text" name="nomor_hak" id="nomor_hak" placeholder="Nomor Hak" class="form-control">
                        </div>
                    </div>
                    <div class="row container">
                        <div class="form-group col-sm-12 col-md-12">
                            <hr><h3>Dikeluarkan Surat Ukur</h3>
                        </div>

                        <button type="button" id="addSuratUkur" class="btn btn-primary mb-3">
                            + Tambah Surat Ukur
                        </button>

                        <div id="suratUkurContainer"></div>
                    </div>

                </div>






                <div class="row">

                    {{-- <div class="form-group col-sm-12">
                        <label for="">Pilih Warga<span class="required">*</span></label>
                        <select name="user_id" id="user_id" class="form-control">
                            @foreach ($warga as $a)
                                <option value="{{ $a->id }}" {{ old('user_id') == $a->id ? 'selected' : '' }}>{{ $a->nama_petugas }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                    <div class="form-group col-sm-6">
                        <label for="">Titik Kordinat<span class="required">*</span></label>
                        <input type="hidden" name="user_id" value="{{ $warga->id }}">
                        <input type="text" name="titik_kordinat" readonly id="marker" placeholder="lat,lng">
                        <textarea id="polygon" style="display: none;" id="titik_kordinat_polygon" name="titik_kordinat_polygon" rows="6" placeholder="Polygon coordinates">{{ old('tanggal_pengukuran') }}</textarea>
                    </div>
                    {{-- <div class="form-group col-sm-12">
                        <label for="">Tanggal Pengukuran<span class="required">*</span></label>
                        <input type="date" name="tanggal_pengukuran" placeholder="Tanggal Pengukuran" value="{{ old('tanggal_pengukuran') }}" class="form-control" id="tanggal_pengukuran">
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="">Peruntukan<span class="required">*</span></label>
                        <textarea name="peruntukan" id="peruntukan" class="form-control" cols="10" rows="5">{{ old('peruntukan') }}</textarea>
                    </div>
                    <div class="form-group col-sm-12 col-md-3">
                        <label for="">Status<span class="required">*</span></label>
                        <input type="text" name="status" placeholder="Status" class="form-control" value="{{ old('status') }}" id="status">
                    </div>
                    <div class="form-group col-sm-12 col-md-3">
                        <label for="">Panjang<span class="required">*</span></label>
                        <input type="number" name="panjang" placeholder="Panjang" class="form-control" value="{{ old('panjang') ?? 0 }}" id="panjang">
                    </div>
                    <div class="form-group col-sm-12 col-md-3">
                        <label for="">Lebar<span class="required">*</span></label>
                        <input type="number" name="lebar" placeholder="Lebar" class="form-control" value="{{ old('lebar') ?? 0 }}" id="lebar">
                    </div>
                    <div class="form-group col-sm-12 col-md-3">
                        <label for="">Luas<span class="required">*</span></label>
                        <input type="number" name="luas" placeholder="Luas" readonly class="form-control" value="{{ old('luas') ?? 0 }}" id="luas">
                    </div>
                     --}}
                    <div class="form-group col-sm-12 col-md-6">

                        <div class="upload-section">
                            <div class="upload-label">Foto Peta<span class="required">*</span></div>

                            <div class="upload-group" id="fotoPetaGroup">
                                <div class="upload-input" id="FotoPetaFileName">Foto Peta (JPG, PNG Maks 2MB)</div>
                                <label for="ktpUpload" class="upload-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/>
                                    </svg>
                                </label>
                                <input
                                    type="file"
                                    id="foto_peta"
                                    name="foto_peta"
                                    accept="image/jpeg,image/jpg,image/png,.pdf"
                                    required
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <center>
                <hr>
                <button type="submit" class="btn btn-success p-3">Simpan Data</button>
            </center>

        </form>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.getElementById('opsi').addEventListener('change', function () {
            const value = this.value;

            // semua form disembunyikan dulu
            document.getElementById('pendaftaran_pertama_form').style.display = 'none';
            document.getElementById('pendaftaran_peralihan_hak_form').style.display = 'none';
            document.getElementById('surat_ukur_form').style.display = 'none';

            // tampilkan sesuai pilihan
            if (value == '0') {
                document.getElementById('pendaftaran_pertama_form').style.display = 'block';
            } else if (value == '1') {
                document.getElementById('pendaftaran_peralihan_hak_form').style.display = 'block';
            } else if (value == '2') {
                document.getElementById('surat_ukur_form').style.display = 'block';
            }
        });
    </script>
    <script>
        let index = 0;

        document.getElementById('addRow').addEventListener('click', function () {
            index++;

            const row = `
                <div class="row mt-3 singleRow" id="row_${index}">
                    <div class="form-group col-sm-12 col-md-4">
                        <label>Sebab perubahan Tanggal Pendaftaran No</label>
                        <input type="text" name="sebab[]" class="form-control" placeholder="Sebab perubahan">
                    </div>

                    <div class="form-group col-sm-12 col-md-4">
                        <label>Nama yang berhak dan Pemegang hak lainnya</label>
                        <input type="text" name="nama[]" class="form-control" placeholder="Nama">
                    </div>

                    <div class="form-group col-sm-12 col-md-3">
                        <label>Tanda Tangan Kepala Kantor dan Cap</label>
                        <input type="text" name="tanda_tangan[]" class="form-control" readonly value="Mohon diajukan!">
                    </div>

                    <div class="form-group col-sm-12 col-md-1">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-danger form-control" onclick="removeRow(${index})">X</button>
                    </div>
                </div>
            `;

            document.getElementById('multipleRows').insertAdjacentHTML('beforeend', row);
        });

        function removeRow(id) {
            document.getElementById('row_' + id).remove();
        }
    </script>

    <script>
        let suratIndex = 0;

        document.getElementById('addSuratUkur').addEventListener('click', function () {
            suratIndex++;

            const row = `
                <div class="row mt-3 singleRow" id="surat_row_${suratIndex}">

                    <div class="form-group col-sm-12 col-md-2">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal_surat_ukur[]" class="form-control">
                    </div>

                    <div class="form-group col-sm-12 col-md-2">
                        <label>Nomor</label>
                        <input type="text" name="nomor_surat_ukur_all[]" class="form-control">
                    </div>

                    <div class="form-group col-sm-12 col-md-2">
                        <label>Luas</label>
                        <input type="text" name="luas_surat_ukur_all[]" class="form-control">
                    </div>

                    <div class="form-group col-sm-12 col-md-2">
                        <label>Nomor Hak</label>
                        <input type="text" name="nomor_hak_all[]" class="form-control">
                    </div>

                    <div class="form-group col-sm-12 col-md-2">
                        <label>Sisa Luas</label>
                        <input type="text" name="sisa_luas[]" class="form-control">
                    </div>

                    <div class="form-group col-sm-12 col-md-1">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-danger form-control" onclick="removeSuratRow(${suratIndex})">X</button>
                    </div>

                </div>
            `;

            document.getElementById('suratUkurContainer').insertAdjacentHTML('beforeend', row);
        });


        function removeSuratRow(id) {
            document.getElementById('surat_row_' + id).remove();
        }
    </script>

    <script>
        const panel = document.getElementById("inputPanel");
        const toggleBtn = document.getElementById("togglePanelBtn");
        const closeBtn = document.getElementById("closePanelBtn");

        toggleBtn.onclick = () => {
            panel.style.display = "block";
        };

        closeBtn.onclick = () => {
            panel.style.display = "none";
        };

        function validateFileSize(file, maxSizeMB = 2) {
            const maxSize = maxSizeMB * 1024 * 1024; // Convert to bytes
            if (file.size > maxSize) {
                alert(`Ukuran file terlalu besar. Maksimal ${maxSizeMB}MB`);
                return false;
            }
            return true;
        }

        function perkalian(val1, val2) {
            return (parseFloat(val1) * parseFloat(val2));
        }

        //Maps
        const map = L.map('map').setView([-2.5489, 118.0149], 5);

        // Layer BIASA
        const streetLayer = L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            {
                maxZoom: 19,
                attribution: '© OpenStreetMap',
                crossOrigin: true
            }
        );

        // Layer SATELIT (Esri)
        const satelliteLayer = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/' +
            'World_Imagery/MapServer/tile/{z}/{y}/{x}',
            {
                maxZoom: 19,
                attribution: '© Esri',
                crossOrigin: true
            }
        );

        // Default layer
        streetLayer.addTo(map);

        // L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        //     maxZoom: 19,
        //     attribution: '© OpenStreetMap'
        // }).addTo(map);

        // Handle combobox
        document.getElementById('mapType').addEventListener('change', function () {
            if (this.value === 'satellite') {
                map.removeLayer(streetLayer);
                map.addLayer(satelliteLayer);
            } else {
                map.removeLayer(satelliteLayer);
                map.addLayer(streetLayer);
            }
        });

        const rtList = @json($rtList);
        const colors = [
            '#e6194b','#3cb44b','#ffe119','#4363d8','#f58231',
            '#911eb4','#46f0f0','#f032e6','#bcf60c','#fabebe',
            '#008080','#e6beff','#9a6324','#fffac8','#800000',
            '#aaffc3','#808000'
        ];

        rtList.forEach((rt, index) => {
            fetch(rt.file)
                .then(res => res.json())
                .then(geojson => {

                    const layer = L.geoJSON(geojson, {
                        interactive: false,
                        style: {
                            color: colors[index % colors.length],
                            weight: 2,
                            opacity: 1,
                            fillOpacity: 0.5
                        },
                        onEachFeature: (feature, layer) => {
                            layer.bindPopup(`<b>${rt.nama}</b>`);
                        }
                    }).addTo(map);



                });
        });

        let addMarkerActive = false;
        let polygonActive = false;

        let polygonPoints = [];

        let tempLine = null;
        let finalPolygon = null;

        let jalanActive = false;
        let jalanPoints = [];
        let tempJalanLine = null;
        let finalJalan = null;

        let firstofall = 0;


        // ===== AUTO GPS =====
        function autoLocateOnLoad() {
            if(firstofall == 1) {
                if (!navigator.geolocation) return;

                navigator.geolocation.getCurrentPosition(pos => {
                    let lat = pos.coords.latitude;
                    let lng = pos.coords.longitude;

                    map.setView([lat, lng], 17);
                    L.marker([lat, lng]).addTo(map).bindPopup("Lokasi Anda").openPopup();
                });
            }

            if(firstofall == 0) {
                fetch("{{ asset('storage/rt/tanah_69314fb496362.geojson') }}")
                    .then(res => res.json())
                    .then(geojson => {

                        const rtLayer = L.geoJSON(geojson, {
                            interactive: false,
                            style: {
                                color: '#e6194b',
                                weight: 2,
                                fillOpacity: 0.5
                            }
                        }).addTo(map);

                        // ✅ fokus ke RT
                        map.fitBounds(rtLayer.getBounds(), {
                            padding: [30, 30]
                        });

                        rtLayer.bindPopup("RT. 15").openPopup();
                });

                    firstofall = 1;
            }
        }
        window.onload = autoLocateOnLoad;

        // ===== TOOLS =====
        function zoomIn() { map.zoomIn(); }
        function zoomOut() { map.zoomOut(); }
        function goToMyLocation() { autoLocateOnLoad(); }

        function addMarkerMode() {
            polygonActive = false;
            addMarkerActive = true;
            alert("Klik pada peta untuk menambahkan marker.");
        }

        function polygonMode() {
            addMarkerActive = false;
            polygonActive = true;

            polygonPoints = [];

            if (tempLine) { map.removeLayer(tempLine); tempLine = null; }
            alert("Polygon Mode aktif: klik beberapa titik.");
        }

        function finishPolygon() {
            if (polygonPoints.length < 3) {
                alert("Minimal 3 titik untuk membuat polygon.");
                return;
            }

            if (tempLine) map.removeLayer(tempLine);
            if (finalPolygon) map.removeLayer(finalPolygon);

            finalPolygon = L.polygon(polygonPoints, {
                color: "red",
                fillColor: "orange",
                fillOpacity: 0.4
            }).addTo(map);

            polygonActive = false;
        }

        // ===== HAPUS POLYGON =====
        function deletePolygon(alert = true) {
            if (finalPolygon) {
                map.removeLayer(finalPolygon);
                finalPolygon = null;
            }

            if (tempLine) {
                map.removeLayer(tempLine);
                tempLine = null;
            }

            polygonPoints = [];
            polygonActive = false;
            document.getElementById("polygon").value = polygonPoints.map(p => p.join(",")).join("\n");



            if(this.alert == true) {
                alert("Polygon berhasil dihapus.");
            }
        }

        function jalanMode() {
            polygonActive = false;
            addMarkerActive = false;
            jalanActive = true;

            jalanPoints = [];

            if (tempJalanLine) {
                map.removeLayer(tempJalanLine);
                tempJalanLine = null;
            }

            alert("Jalan Mode aktif: klik titik-titik jalan.");
        }

        function finishJalan() {
            if (jalanPoints.length < 2) {
                alert("Minimal 2 titik untuk jalan.");
                return;
            }

            if (tempJalanLine) map.removeLayer(tempJalanLine);
            if (finalJalan) map.removeLayer(finalJalan);

            finalJalan = L.polyline(jalanPoints, {
                color: "black",
                weight: 4
            }).addTo(map);

            jalanActive = false;
        }

        function deleteJalan() {
            if (finalJalan) {
                map.removeLayer(finalJalan);
                finalJalan = null;
            }

            if (tempJalanLine) {
                map.removeLayer(tempJalanLine);
                tempJalanLine = null;
            }

            jalanPoints = [];
            jalanActive = false;

            document.getElementById("jalan").value = "";
            alert("Jalan berhasil dihapus.");
        }


        let currentMarker = null;

        function addSingleMarker(latlng) {

            // Hapus marker lama jika ada
            if (currentMarker !== null) {
                map.removeLayer(currentMarker);
            }

            // Tambah marker baru
            currentMarker = L.marker(latlng).addTo(map).bindPopup("Marker baru");


            document.getElementById("marker").value = latlng.lat + "," + latlng.lng;
        }

        @if (old('titik_kordinat'))
            const str = "{{old('titik_kordinat')}}";

            const [lat, lng] = str.split(",");

            const result = {
                lat: parseFloat(lat),
                lng: parseFloat(lng)
            };

            addSingleMarker(result);


        @endif

        @if (old('titik_kordinat_polygon'))
            deletePolygon(false);
            const input_titik_kordinat_polygon = `{{ old('titik_kordinat_polygon') }}`;

            const result_titik_kordinat_polygon = input_titik_kordinat_polygon
                .trim()
                .split("\n")
                .map(line => {
                    const [lat, lng] = line.split(",");
                    return [parseFloat(lng), parseFloat(lat)];
                });

                result_titik_kordinat_polygon.forEach(c => {
                    polygonPoints.push([c[1], c[0]]); // GeoJSON = lon,lat → Leaflet butuh lat,lon
                });

            if (polygonPoints.length > 1) {
                if (tempLine) map.removeLayer(tempLine);

                tempLine = L.polyline(polygonPoints, {
                    color: "blue",
                    dashArray: "5, 5"
                }).addTo(map);
            }
            document.getElementById("polygon").value = polygonPoints.map(p => p.join(",")).join("\n");
            console.log(result_titik_kordinat_polygon);
        @endif


        // ===== EVENT CLICK PETA =====
        map.on('click', function (e) {

            if (addMarkerActive) {
                // console.log(e.latlng);
                addSingleMarker(e.latlng);
                // L.marker([e.latlng.lat, e.latlng.lng])
                //     .addTo(map)
                //     .bindPopup("Marker baru");
                addMarkerActive = false;
            }

            if (polygonActive) {
                polygonPoints.push([e.latlng.lat, e.latlng.lng]);

                if (polygonPoints.length > 1) {
                    if (tempLine) map.removeLayer(tempLine);

                    tempLine = L.polyline(polygonPoints, {
                        color: "blue",
                        dashArray: "5, 5"
                    }).addTo(map);
                }


                document.getElementById("polygon").value = polygonPoints.map(p => p.join(",")).join("\n");
            }

            if (jalanActive) {
                jalanPoints.push([e.latlng.lat, e.latlng.lng]);

                if (jalanPoints.length > 1) {
                    if (tempJalanLine) map.removeLayer(tempJalanLine);

                    tempJalanLine = L.polyline(jalanPoints, {
                        color: "gray",
                        dashArray: "5,5"
                    }).addTo(map);
                }

                document.getElementById("jalan").value =
                    jalanPoints.map(p => p.join(",")).join("\n");
            }


            // console.log([currentMarker, polygonPoints]);
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            document.getElementById('foto_peta').addEventListener('change', function(e) {
                console.log("ASD");
                const file = e.target.files[0];
                const ktpGroup = document.getElementById('fotoPetaGroup');
                const ktpFileName = document.getElementById('FotoPetaFileName');

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

            $('#panjang').on('keyup',function() {
                $('#luas').val(perkalian($('#panjang').val(), $('#lebar').val()));
            });

            $('#lebar').on('keyup',function() {
                $('#luas').val(perkalian($('#panjang').val(), $('#lebar').val()));

            });
        });
    </script>

</body>
</html>

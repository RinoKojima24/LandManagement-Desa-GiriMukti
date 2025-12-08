@extends('layouts.mobile')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<style>
    #map { height: 500px; }

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

<style>
/* Wrapper */
.tabs {
    width: 100%;
    /* max-width: 600px; */
    margin: 40px auto;
    font-family: Arial, sans-serif;
}

/* Hidden radio */
.tabs input[type="radio"] {
    display: none;
}

/* Label tab */
.tabs .tab_label {
    padding: 12px 20px;
    display: inline-block;
    cursor: pointer;
    background: #e0e0e0;
    border-radius: 8px 8px 0 0;
    margin-right: 5px;
    transition: 0.3s;
}

/* Saat tab aktif */
.tabs input:checked + label {
    background: #4CAF50;
    color: white;
}

/* Content */
.tab-content {
    border: 1px solid #ccc;
    padding: 20px;
    display: none;
    border-radius: 0 8px 8px 8px;
    background: #fafafa;
}

/* Tampilkan konten sesuai tab */
#tab1:checked ~ #content1,
#tab2:checked ~ #content2 {
    display: block;
}
</style>
<div class="px-3 py-4" style="background-color: #f5f5f5; min-height: 100vh;">
    <!-- Header -->
    <div class="mb-4">
        <div class="d-flex justify-content-between">
            <div class="d-flex align-items-center mb-3">
                <a href="{{ url('tanah') }}" class="text-decoration-none text-dark me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h5 class="mb-0 fw-bold">Tambah Data Peta Tanah</h5>
            </div>
        </div>

        <!-- Search Box -->
        <form action="{{ url('tanah') }}" method="POST" class="mb-3" enctype="multipart/form-data">
            @csrf
            <div class="tabs">

                <!-- TAB 1 -->
                <input type="radio" id="tab1" name="tab" checked>
                <label for="tab1" class="tab_label">Input Kordinat Tanah</label>

                <!-- TAB 2 -->
                <input type="radio" id="tab2" name="tab">
                <label for="tab2" class="tab_label">Input Data Tanah</label>


                    <!-- CONTENT 1 -->
                    <div id="content1" class="tab-content">



                        <div class="capek">
                            <div class="form-group">
                                <div id="map"></div>
                            </div>
                            <div class="form-group col-sm-12 col-md-6" style="display: none;">
                                <label for="">Titik Kordinat Utama<span class="required">*</span></label>
                                <input type="text" name="titik_kordinat" value="-" readonly placeholder="Titik Kordinat (-0.4743788971644572, 117.15811604595541)" class="form-control" id="titik_kordinat">
                            </div>
                            <div class="form-group col-sm-12 col-md-6" style="display: none;">
                                <label for="">Titik Kordinat Polygon<span class="required">*</span></label>
                                <textarea name="titik_kordinat_polygon" class="form-control" id="titik_kordinat_polygon" cols="30" rows="5" readonly></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- CONTENT 2 -->
                    <div id="content2" class="tab-content">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="">Pilih Surat Permohonan<span class="required">*</span></label>
                                <select name="surat_permohonan_id" id="surat_permohonan_id" class="form-control">
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
                                        ]
                                    @endphp
                                    @foreach ($permohonans as $a)
                                        <option value="{{ $a->id_permohonan }}" {{ old('surat_permohonan_id') == $a->id_permohonan ? 'selected' : '' }}>{{ $a->id_permohonan }} | {{ $a->nama_lengkap }} | {{ $jenis_surat[$a->kode_jenis] ?? "Tidak ada Permohonan" }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12">
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



            </div>
            <center>
                <button type="submit" class="btn btn-success p-3">Simpan Data</button>
            </center>
        </form>



    </div>

    <!-- Cards Container -->
    <div class="row g-3">

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

<script>
    @section('jquery')
    let map = L.map('map').setView([-6.2, 106.8], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19
}).addTo(map);

let markersLayer = L.layerGroup().addTo(map);
let polygonLayer = null;
let polygonCoords = [];

function updatePolygonTextarea() {
    let text = polygonCoords.map(c => c.join(", ")).join("\n");
    $("#titik_kordinat_polygon").val(text);
}

function redrawPolygon() {
    if (polygonLayer) map.removeLayer(polygonLayer);

    if (polygonCoords.length >= 3) {
        polygonLayer = L.polygon(polygonCoords, {
            color: "blue",
            weight: 3,
            fillColor: "lightblue",
            fillOpacity: 0.3
        }).addTo(map);
    }
}

function createEditableMarker(latlng, index) {
    let marker = L.marker(latlng, { draggable: true }).addTo(markersLayer);

    marker.bindPopup("Titik " + (index + 1));

    // DRAG = update titik
    marker.on("drag", function (e) {
        polygonCoords[index] = [e.latlng.lat, e.latlng.lng];
        redrawPolygon();
        updatePolygonTextarea();
    });

    // LONG PRESS (0.8s) = hapus titik
    let pressTimer;
    marker.on("mousedown touchstart", function () {
        pressTimer = setTimeout(() => {
            removePoint(index);
        }, 800);
    });

    marker.on("mouseup mouseleave touchend", function () {
        clearTimeout(pressTimer);
    });
}

function removePoint(index) {
    polygonCoords.splice(index, 1);

    markersLayer.clearLayers();

    polygonCoords.forEach((coord, i) => createEditableMarker(coord, i));

    redrawPolygon();
    updatePolygonTextarea();
}

map.on("click", function(e) {
    let newPoint = [e.latlng.lat, e.latlng.lng];

    polygonCoords.push(newPoint);

    createEditableMarker(newPoint, polygonCoords.length - 1);

    redrawPolygon();
    updatePolygonTextarea();
});



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
    @endsection
</script>
@endsection

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Leaflet Polygon Tools</title>
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

    @php
        $geojsonPath = public_path($peta->titik_kordinat_polygon);
        $geojsonData = file_get_contents($geojsonPath);
    @endphp

    <div id="map"></div>

    <div class="map-tools">
        {{-- <button class="tool-btn" onclick="zoomIn()">Zoom +</button>
        <button class="tool-btn" onclick="zoomOut()">Zoom -</button> --}}
        <button class="tool-btn" id="togglePanelBtn">Data Tanah</button>
        <button class="tool-btn" onclick="goToMyLocation()">📍 Lokasi Saya</button>
        <button class="tool-btn" onclick="addMarkerMode()">📌 Tambah Marker</button>
        <button class="tool-btn" onclick="polygonMode()">⬛ Polygon Mode</button>
        <button class="tool-btn" onclick="finishPolygon()">✔ Selesai Polygon</button>
        <button class="tool-btn" onclick="deletePolygon()">🗑 Hapus Polygon</button>
    </div>


        <!-- Panel Input -->
    <div id="inputPanel">
        <button id="closePanelBtn">Close</button>
        <h3>Tambah Data Peta Tanah</h3>
{{--
        <label>Marker Position:</label>

        <label>Polygon Data:</label> --}}

                <!-- Search Box -->
        <form action="{{ url('tanah/'.$peta->id) }}" method="POST" class="mb-3" enctype="multipart/form-data">
            @csrf
            @method('PUT')
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
                        <label for="">Pilih Warga<span class="required">*</span></label>
                        <select name="user_id" id="user_id" class="form-control">
                            {{-- @php
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
                            @endphp --}}
                            @foreach ($warga as $a)
                                <option value="{{ $a->id }}" {{ old('user_id', $peta->user_id) == $a->id ? 'selected' : '' }}>{{ $a->nama_petugas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="">Titik Kordinat<span class="required">*</span></label>
                        <input type="text" name="titik_kordinat" readonly id="marker" value="{{ old('titik_kordinat', $peta->titik_kordinat) }}" placeholder="lat,lng">
                        <textarea id="polygon" style="display: none;" name="titik_kordinat_polygon" rows="6" placeholder="Polygon coordinates"></textarea>
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="">Tanggal Pengukuran<span class="required">*</span></label>
                        <input type="date" name="tanggal_pengukuran" placeholder="Tanggal Pengukuran" value="{{ old('tanggal_pengukuran', $peta->tanggal_pengukuran) }}" class="form-control" id="tanggal_pengukuran">
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="">Peruntukan<span class="required">*</span></label>
                        <textarea name="peruntukan" id="peruntukan" class="form-control" cols="10" rows="5">{{ old('peruntukan', $peta->peruntukan) }}</textarea>
                    </div>
                    <div class="form-group col-sm-12 col-md-3">
                        <label for="">Status<span class="required">*</span></label>
                        <input type="text" name="status" placeholder="Status" class="form-control" value="{{ old('status', $peta->status) }}" id="status">
                    </div>
                    <div class="form-group col-sm-12 col-md-3">
                        <label for="">Panjang<span class="required">*</span></label>
                        <input type="number" name="panjang" placeholder="Panjang" class="form-control" value="{{ old('panjang', $peta->panjang) ?? 0 }}" id="panjang">
                    </div>
                    <div class="form-group col-sm-12 col-md-3">
                        <label for="">Lebar<span class="required">*</span></label>
                        <input type="number" name="lebar" placeholder="Lebar" class="form-control" value="{{ old('lebar', $peta->lebar) ?? 0 }}" id="lebar">
                    </div>
                    <div class="form-group col-sm-12 col-md-3">
                        <label for="">Luas<span class="required">*</span></label>
                        <input type="number" name="luas" placeholder="Luas" readonly class="form-control" value="{{ old('luas', $peta->luas) ?? 0 }}" id="luas">
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
                                >
                            </div>
                        </div>
                    </div>


                    <div class="form-group col-sm-12 col-md-6">
                        <img src="{{ url('storage/'.$peta->foto_peta) }}" alt="">
                    </div>
                </div>
            </div>
            <center>
                <button type="submit" class="btn btn-success p-3">Simpan Data</button>
            </center>

        </form>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

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

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        let addMarkerActive = false;
        let polygonActive = false;

        let polygonPoints = [];

        let tempLine = null;
        let finalPolygon = null;

        // ===== AUTO GPS =====
        function autoLocateOnLoad() {
            if (!navigator.geolocation) return;

            navigator.geolocation.getCurrentPosition(pos => {
                let lat = pos.coords.latitude;
                let lng = pos.coords.longitude;

                map.setView([lat, lng], 17);
                L.marker([lat, lng]).addTo(map).bindPopup("Lokasi Anda").openPopup();
            });
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


        @if (old('titik_kordinat', $peta->titik_kordinat))
            const str = "{{old('titik_kordinat', $peta->titik_kordinat)}}";

            const [lat, lng] = str.split(",");

            const result = {
                lat: parseFloat(lat),
                lng: parseFloat(lng)
            };

            addSingleMarker(result);


        @endif

        let geojsonData = {!! $geojsonData !!};

        let geo = L.geoJSON(geojsonData);

        geo.eachLayer(function(layer) {
            if (layer.feature.geometry.type === "Polygon") {
                let coords = layer.feature.geometry.coordinates[0];
                coords.forEach(c => {
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

                // console.log(coords);
                // coords.forEach(c => {
                //     polygonCoords.push([c[1], c[0]]); // GeoJSON = lon,lat → Leaflet butuh lat,lon
                // });

                // // Buat marker editable
                // polygonCoords.forEach((coord, i) => {
                //     createEditableMarker(coord, i);
                // });

                // redrawPolygon();
                // updatePolygonTextarea();
                // map.fitBounds(polygonCoords);
            }
        });


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
                console.log(e.latlng);
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

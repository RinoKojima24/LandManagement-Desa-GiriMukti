@extends('layouts.app')

@section('title', 'Detail Bidang Tanah')

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    * {
        box-sizing: border-box;
    }

    .detail-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .header-section {
        background: #fff;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .back-btn {
        background: #f5f5f5;
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 18px;
        color: #333;
        transition: all 0.3s;
    }

    .back-btn:hover {
        background: #e0e0e0;
    }

    .header-title {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
        color: #333;
    }

    .map-section {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .map-container {
        position: relative;
        width: 100%;
        height: 400px;
        background: #f0f0f0;
    }

    #map {
        width: 100%;
        height: 100%;
        z-index: 1;
    }

    .info-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: white;
        padding: 8px 12px;
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        z-index: 1000;
    }

    .info-badge-icon {
        width: 20px;
        height: 20px;
        background: #FFA726;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
    }

    .map-info-bar {
        background: #f8f9fa;
        padding: 12px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid #e0e0e0;
        flex-wrap: wrap;
        gap: 10px;
    }

    .map-info-item {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .map-info-label {
        font-size: 11px;
        color: #666;
        text-transform: uppercase;
    }

    .map-info-value {
        font-size: 14px;
        font-weight: 600;
        color: #333;
    }

    .data-section {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 20px;
    }

    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }

    .data-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
    }

    .data-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .data-label {
        font-size: 12px;
        color: #666;
        font-weight: 500;
    }

    .data-value {
        font-size: 14px;
        color: #333;
        padding: 8px 12px;
        background: #f8f9fa;
        border-radius: 6px;
        border-left: 3px solid #2196F3;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-active {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .status-inactive {
        background: #ffebee;
        color: #c62828;
    }

    .action-button {
        background: #0d5c4a;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        width: 100%;
        margin-top: 10px;
        transition: all 0.3s;
    }

    .action-button:hover {
        background: #094438;
    }

    /* Custom Leaflet Marker */
    .custom-marker {
        background: #2196F3;
        color: white;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
        border: 3px solid white;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    }

    @media (max-width: 768px) {
        .detail-container {
            padding: 10px;
        }

        .header-section {
            padding: 12px 15px;
        }

        .header-title {
            font-size: 18px;
        }

        .map-container {
            height: 300px;
        }

        .data-grid {
            grid-template-columns: 1fr;
        }

        .map-info-bar {
            flex-direction: column;
            align-items: flex-start;
        }

        .section-title {
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        .map-container {
            height: 250px;
        }
    }
</style>
@endpush

@section('content')
<div class="detail-container">
    <!-- Header -->
    <div class="header-section">
        <button class="back-btn" onclick="window.history.back()">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
        </button>
        <h1 class="header-title">Swaploting</h1>
    </div>

    <!-- Map Section -->
    <div class="map-section">
        <div class="map-container">
            <div id="map"></div>

            <!-- Info Badge -->
            <div class="info-badge">
                <div class="info-badge-icon">🗺️</div>
                <span>Monitor Bidang</span>
            </div>
        </div>

        <!-- Map Info Bar -->
        <div class="map-info-bar">
            <div class="map-info-item">
                <span class="map-info-label">Tanggal</span>
                <span class="map-info-value" id="currentDate">01/06/2023</span>
            </div>
            <div class="map-info-item">
                <span class="map-info-label">Titik Barat</span>
                <span class="map-info-value">{{ $data['batas_barat'] }}</span>
            </div>
            <div class="map-info-item">
                <span class="map-info-label">Titik Utara</span>
                <span class="map-info-value">{{ $data['batas_utara'] }}</span>
            </div>
            <div class="map-info-item">
                <span class="map-info-label">Titik Selatan</span>
                <span class="map-info-value">{{ $data['batas_selatan'] }}</span>
            </div>
            <div class="map-info-item">
                <span class="map-info-label">Titik Timur</span>
                <span class="map-info-value">{{ $data['batas_timur'] }}</span>
            </div>
            <div class="map-info-item">
                <span class="map-info-label">Nomor Bidang</span>
                <span class="map-info-value">{{ $data['nomor_bidang'] }}</span>
            </div>
            <div class="map-info-item">
                <span class="map-info-label">Luas</span>
                <span class="map-info-value">{{ $data['luas_tanah'] }} m²</span>
            </div>
            <div class="map-info-item">
                <span class="map-info-label">Keterangan</span>
                <span class="map-info-value">{{ $data['keterangan'] }}</span>
            </div>
        </div>
    </div>

    <!-- Data Bidang Tanah -->
    <div class="data-section">
        <h2 class="section-title">Informasi Bidang Tanah</h2>
        <div class="data-grid">
            <div class="data-item">
                <span class="data-label">Nomor Bidang</span>
                <div class="data-value">{{ $data['nomor_bidang'] }}</div>
            </div>
            <div class="data-item">
                <span class="data-label">NIB</span>
                <div class="data-value">{{ $data['nib'] }}</div>
            </div>
            <div class="data-item">
                <span class="data-label">Luas Tanah</span>
                <div class="data-value">{{ $data['luas_tanah'] }} m²</div>
            </div>
            <div class="data-item">
                <span class="data-label">Status</span>
                <div class="data-value">
                    <span class="status-badge {{ $data['is_active'] == 1 ? 'status-active' : 'status-inactive' }}">
                        {{ $data['status_text'] }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Lokasi & Batas -->
    <div class="data-section">
        <h2 class="section-title">Lokasi & Batas Wilayah</h2>
        <div class="data-grid">
            <div class="data-item">
                <span class="data-label">Alamat</span>
                <div class="data-value">{{ $data['alamat_tanah'] }}</div>
            </div>
            <div class="data-item">
                <span class="data-label">Wilayah</span>
                <div class="data-value">{{ $data['nama_wilayah'] }} ({{ $data['kode_wilayah'] }})</div>
            </div>
            <div class="data-item">
                <span class="data-label">Koordinat Tengah</span>
                <div class="data-value">{{ $data['koordinat_tanah'] }}</div>
            </div>
            <div class="data-item">
                <span class="data-label">Batas Utara</span>
                <div class="data-value">{{ $data['batas_utara'] }}</div>
            </div>
            <div class="data-item">
                <span class="data-label">Batas Selatan</span>
                <div class="data-value">{{ $data['batas_selatan'] }}</div>
            </div>
            <div class="data-item">
                <span class="data-label">Batas Timur</span>
                <div class="data-value">{{ $data['batas_timur'] }}</div>
            </div>
            <div class="data-item">
                <span class="data-label">Batas Barat</span>
                <div class="data-value">{{ $data['batas_barat'] }}</div>
            </div>
        </div>
    </div>

    <!-- Detail Lainnya -->
    <div class="data-section">
        <h2 class="section-title">Detail Lainnya</h2>
        <div class="data-grid">
            <div class="data-item">
                <span class="data-label">Jenis Tanah</span>
                <div class="data-value">{{ $data['nama_jenis'] }}</div>
            </div>
            <div class="data-item">
                <span class="data-label">Status Kepemilikan</span>
                <div class="data-value">{{ $data['nama_status'] }}</div>
            </div>
            <div class="data-item">
                <span class="data-label">Keterangan</span>
                <div class="data-value">{{ $data['keterangan'] }}</div>
            </div>
            <div class="data-item">
                <span class="data-label">Dibuat</span>
                <div class="data-value">{{ $data['created_at'] }}</div>
            </div>
            <div class="data-item">
                <span class="data-label">Terakhir Diubah</span>
                <div class="data-value">{{ $data['updated_at'] }}</div>
            </div>
        </div>
    </div>

    <!-- Action Button -->
    <button class="action-button" onclick="showData()">
        Lihat Data Peta
    </button>
</div>

<!-- Hidden data for map -->
<div id="mapData" style="display:none;">
    <span id="koordinat_tengah">{{ $data['koordinat_tanah'] }}</span>
    <span id="batas_utara">{{ $data['batas_utara'] }}</span>
    <span id="batas_selatan">{{ $data['batas_selatan'] }}</span>
    <span id="batas_timur">{{ $data['batas_timur'] }}</span>
    <span id="batas_barat">{{ $data['batas_barat'] }}</span>
    <span id="nomor_bidang">{{ $data['nomor_bidang'] }}</span>
    <span id="luas_tanah">{{ $data['luas_tanah'] }}</span>
</div>
@endsection

@push('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let map;
    let polygon;
    let markers = [];

    // Set current date
    document.addEventListener('DOMContentLoaded', function() {
        const dateElement = document.getElementById('currentDate');
        const today = new Date();
        const formatted = today.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
        dateElement.textContent = formatted;

        // Initialize map
        initMap();
    });

    function parseCoordinate(coordStr) {
        // Parse coordinate string format "lat,lng" or handle other formats
        if (!coordStr || coordStr === '-') {
            return null;
        }

        const parts = coordStr.split(',');
        if (parts.length !== 2) {
            return null;
        }

        const lat = parseFloat(parts[0].trim());
        const lng = parseFloat(parts[1].trim());

        if (isNaN(lat) || isNaN(lng)) {
            return null;
        }

        return [lat, lng];
    }

    function initMap() {
        // Get coordinates from hidden div
        const koordinatTengah = document.getElementById('koordinat_tengah').textContent;
        const batasUtara = document.getElementById('batas_utara').textContent;
        const batasSelatan = document.getElementById('batas_selatan').textContent;
        const batasTimur = document.getElementById('batas_timur').textContent;
        const batasBarat = document.getElementById('batas_barat').textContent;

        // Parse center coordinate
        let centerCoord = parseCoordinate(koordinatTengah);

        // If no center coordinate, use default (Samarinda)
        if (!centerCoord) {
            centerCoord = [-0.5022, 117.1536]; // Samarinda coordinates
        }

        // Initialize map
        map = L.map('map', {
            center: centerCoord,
            zoom: 15,
            zoomControl: true
        });

        // Add OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        // Parse corner coordinates
        const cornerCoords = [
            parseCoordinate(batasUtara),
            parseCoordinate(batasTimur),
            parseCoordinate(batasSelatan),
            parseCoordinate(batasBarat)
        ];

        // Check if we have valid coordinates
        const validCoords = cornerCoords.filter(coord => coord !== null);

        if (validCoords.length >= 3) {
            // Draw polygon with actual coordinates
            polygon = L.polygon(validCoords, {
                color: '#FF8C00',
                fillColor: '#FFD700',
                fillOpacity: 0.3,
                weight: 3
            }).addTo(map);

            // Add numbered markers at each corner
            validCoords.forEach((coord, index) => {
                const customIcon = L.divIcon({
                    className: 'custom-marker',
                    html: `<div class="custom-marker">${index + 1}</div>`,
                    iconSize: [32, 32],
                    iconAnchor: [16, 16]
                });

                const marker = L.marker(coord, {
                    icon: customIcon,
                    draggable: true
                }).addTo(map);

                // Add popup with coordinate info
                marker.bindPopup(`<b>Titik ${index + 1}</b><br>${coord[0]}, ${coord[1]}`);

                // Update polygon when marker is dragged
                marker.on('dragend', function(e) {
                    updatePolygon();
                });

                markers.push(marker);
            });

            // Fit map to polygon bounds
            map.fitBounds(polygon.getBounds(), { padding: [50, 50] });
        } else {
            // Draw example polygon if no valid coordinates
            const exampleCoords = [
                [centerCoord[0] + 0.001, centerCoord[1] - 0.001],
                [centerCoord[0] + 0.001, centerCoord[1] + 0.001],
                [centerCoord[0] - 0.001, centerCoord[1] + 0.001],
                [centerCoord[0] - 0.001, centerCoord[1] - 0.001]
            ];

            polygon = L.polygon(exampleCoords, {
                color: '#FF8C00',
                fillColor: '#FFD700',
                fillOpacity: 0.3,
                weight: 3
            }).addTo(map);

            // Add markers
            exampleCoords.forEach((coord, index) => {
                const customIcon = L.divIcon({
                    className: 'custom-marker',
                    html: `<div class="custom-marker">${index + 1}</div>`,
                    iconSize: [32, 32],
                    iconAnchor: [16, 16]
                });

                const marker = L.marker(coord, {
                    icon: customIcon,
                    draggable: true
                }).addTo(map);

                marker.bindPopup(`<b>Titik ${index + 1}</b><br>${coord[0].toFixed(6)}, ${coord[1].toFixed(6)}`);

                marker.on('dragend', function(e) {
                    updatePolygon();
                });

                markers.push(marker);
            });

            map.fitBounds(polygon.getBounds(), { padding: [50, 50] });
        }

        // Add scale control
        L.control.scale({ imperial: false, metric: true }).addTo(map);
    }

    function updatePolygon() {
        const newCoords = markers.map(marker => marker.getLatLng());
        polygon.setLatLngs(newCoords);
    }

    function showData() {
        const coords = markers.map((marker, index) => {
            const pos = marker.getLatLng();
            return `Titik ${index + 1}: ${pos.lat.toFixed(6)}, ${pos.lng.toFixed(6)}`;
        });

        alert('Koordinat Peta:\n\n' + coords.join('\n'));
    }
</script>
@endpush

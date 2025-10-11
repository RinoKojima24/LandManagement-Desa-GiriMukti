@extends(Auth::user() && Auth::user()->role === 'admin' ? 'layouts.app' : 'layouts.mobile')
@section('title', 'Detail Bidang Tanah - ' . ($data['nomor_bidang'] ?? 'Data'))

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
<style>
:root {
    --primary: #0d5c4a;
    --primary-dark: #094438;
    --secondary: #2c3e50;
    --accent: #3498db;
    --success: #27ae60;
    --warning: #f39c12;
    --danger: #e74c3c;
    --light: #f8f9fa;
    --border: #e0e0e0;
    --text-primary: #333;
    --text-secondary: #666;
    --shadow: 0 2px 8px rgba(0,0,0,0.08);
    --shadow-hover: 0 4px 16px rgba(0,0,0,0.12);
}

* {
    box-sizing: border-box;
}

.detail-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

/* Header Section */
.header-section {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: var(--shadow);
    margin-bottom: 24px;
}

.header-top {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 12px;
}

.back-btn {
    background: var(--light);
    border: none;
    padding: 10px 16px;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    color: var(--text-primary);
    transition: all 0.3s;
}

.back-btn:hover {
    background: var(--border);
    transform: translateX(-2px);
}

.header-title {
    margin: 0;
    font-size: 24px;
    font-weight: 600;
    color: var(--text-primary);
    flex: 1;
}

.header-actions {
    display: flex;
    gap: 12px;
}

.btn-action {
    padding: 10px 20px;
    border-radius: 8px;
    border: none;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-delete {
    background: var(--danger);
    color: white;
}

.btn-delete:hover {
    background: #c0392b;
    transform: translateY(-2px);
    box-shadow: var(--shadow-hover);
}

.breadcrumb {
    display: flex;
    gap: 8px;
    font-size: 14px;
    color: var(--text-secondary);
}

.breadcrumb a {
    color: var(--accent);
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

/* Main Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
    margin-bottom: 24px;
}

/* Map Section */
.map-card {
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow);
    overflow: hidden;
}

.map-header {
    padding: 16px 20px;
    background: var(--light);
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.map-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 8px;
}

.map-controls {
    display: flex;
    gap: 8px;
}

.map-type-btn {
    padding: 6px 12px;
    border: 2px solid var(--border);
    background: white;
    border-radius: 6px;
    cursor: pointer;
    font-size: 12px;
    transition: all 0.3s;
}

.map-type-btn:hover,
.map-type-btn.active {
    background: var(--accent);
    border-color: var(--accent);
    color: white;
}

#map {
    width: 100%;
    height: 500px;
}

.map-footer {
    padding: 16px 20px;
    background: var(--light);
    border-top: 1px solid var(--border);
}

.coordinate-display {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.coord-label {
    font-size: 12px;
    color: var(--text-secondary);
    font-weight: 500;
}

.coord-value {
    font-family: 'Courier New', monospace;
    font-weight: 600;
    color: var(--text-primary);
}

.copy-btn {
    padding: 6px 12px;
    background: var(--primary);
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 12px;
    transition: all 0.3s;
}

.copy-btn:hover {
    background: var(--primary-dark);
}

.no-map-placeholder {
    height: 500px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
}

.no-map-placeholder i {
    font-size: 64px;
    margin-bottom: 16px;
    opacity: 0.3;
}

/* Info Panel */
.info-panel {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.info-card {
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow);
    padding: 20px;
}

.info-card-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 2px solid var(--light);
}

.info-item {
    margin-bottom: 16px;
}

.info-item:last-child {
    margin-bottom: 0;
}

.info-label {
    font-size: 12px;
    color: var(--text-secondary);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}

.info-value {
    font-size: 15px;
    color: var(--text-primary);
    font-weight: 500;
}

.info-value.large {
    font-size: 24px;
    color: var(--primary);
}

.status-badge {
    display: inline-block;
    padding: 6px 14px;
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

/* Boundary Compass */
.boundary-compass {
    position: relative;
    width: 100%;
    height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 20px 0;
}

.compass-center {
    position: absolute;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--accent), var(--primary));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 28px;
    box-shadow: var(--shadow);
    z-index: 10;
}

.boundary-point {
    position: absolute;
    background: white;
    border: 2px solid var(--border);
    border-radius: 8px;
    padding: 12px 16px;
    text-align: center;
    box-shadow: var(--shadow);
    transition: all 0.3s;
}

.boundary-point:hover {
    transform: scale(1.05);
    box-shadow: var(--shadow-hover);
    border-color: var(--accent);
}

.boundary-north { top: 10px; left: 50%; transform: translateX(-50%); }
.boundary-south { bottom: 10px; left: 50%; transform: translateX(-50%); }
.boundary-east { right: 10px; top: 50%; transform: translateY(-50%); }
.boundary-west { left: 10px; top: 50%; transform: translateY(-50%); }

.boundary-label {
    font-size: 11px;
    color: var(--text-secondary);
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 4px;
}

.boundary-value {
    font-size: 13px;
    color: var(--text-primary);
    font-weight: 500;
}

/* Quick Actions */
.quick-actions {
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow);
    padding: 20px;
    margin-bottom: 24px;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 12px;
}

.action-btn {
    padding: 16px;
    border: 2px solid var(--border);
    background: white;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    text-align: center;
}

.action-btn i {
    font-size: 24px;
    color: var(--accent);
}

.action-btn span {
    font-size: 13px;
    font-weight: 500;
    color: var(--text-primary);
}

.action-btn:hover {
    border-color: var(--accent);
    background: var(--accent);
    transform: translateY(-2px);
    box-shadow: var(--shadow-hover);
}

.action-btn:hover i,
.action-btn:hover span {
    color: white;
}

/* Details Grid */
.details-section {
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow);
    padding: 24px;
    margin-bottom: 24px;
}

.section-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid var(--light);
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}

.detail-item {
    padding: 16px;
    background: var(--light);
    border-radius: 8px;
    border-left: 4px solid var(--accent);
}

/* Metadata Footer */
.metadata-footer {
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow);
    padding: 16px 24px;
    display: flex;
    justify-content: space-around;
    align-items: center;
}

.metadata-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-secondary);
    font-size: 13px;
}

.metadata-item i {
    color: var(--accent);
}

/* Loading Overlay */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.95);
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
}

.spinner {
    width: 50px;
    height: 50px;
    border: 4px solid var(--light);
    border-top-color: var(--accent);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Custom Leaflet Marker */
.custom-marker {
    background: var(--accent);
    color: white;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
    border: 3px solid white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
}

/* Responsive */
@media (max-width: 1024px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .detail-container {
        padding: 12px;
    }

    .header-top {
        flex-wrap: wrap;
    }

    .header-actions {
        width: 100%;
        justify-content: stretch;
    }

    .btn-action {
        flex: 1;
        justify-content: center;
    }

    #map {
        height: 350px;
    }

    .boundary-compass {
        height: 400px;
    }

    .boundary-north { top: 10px; }
    .boundary-south { bottom: 10px; }
    .boundary-east { right: 10px; }
    .boundary-west { left: 10px; }

    .details-grid {
        grid-template-columns: 1fr;
    }

    .actions-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .metadata-footer {
        flex-direction: column;
        gap: 12px;
        align-items: flex-start;
    }
}
</style>
@endpush

@section('content')
<div class="detail-container">
    <!-- Header -->
    <div class="header-section">
        <div class="header-top">
            <button class="back-btn" onclick="window.history.back()">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </button>
            <h1 class="header-title">
                <i class="fas fa-map-marked-alt"></i>
                Detail Bidang Tanah
            </h1>
            <div class="header-actions">
                <button class="btn-action btn-delete" onclick="deleteData({{ $data['id_bidang_tanah'] }})">
                    <i class="fas fa-trash"></i>
                    <span>Hapus</span>
                </button>
            </div>
        </div>
        <div class="breadcrumb">
            <a href="{{ route('bidang-tanah.index') }}">
                <i class="fas fa-home"></i> Home
            </a>
            <span>/</span>
            <a href="{{ route('bidang-tanah.index') }}">Bidang Tanah</a>
            <span>/</span>
            <span>{{ $data['nomor_bidang'] }}</span>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
        <!-- Map Section -->
        <div class="map-card">
            <div class="map-header">
                <div class="map-title">
                    <i class="fas fa-map"></i>
                    Peta Lokasi & Batas Wilayah
                </div>
                @if($data['koordinat_tanah'] !== '-')
                <div class="map-controls">
                    <button class="map-type-btn active" onclick="toggleMapType('street')" title="Peta Jalan">
                        <i class="fas fa-road"></i>
                    </button>
                    <button class="map-type-btn" onclick="toggleMapType('satellite')" title="Satelit">
                        <i class="fas fa-satellite"></i>
                    </button>
                    <button class="map-type-btn" onclick="toggleMapType('terrain')" title="Terrain">
                        <i class="fas fa-mountain"></i>
                    </button>
                </div>
                @endif
            </div>

            @if($data['koordinat_tanah'] !== '-')
                <div id="map"></div>
                <div class="map-footer">
                    <div class="coordinate-display">
                        <div>
                            <div class="coord-label">Koordinat Tengah</div>
                            <div class="coord-value" id="centerCoord">{{ $data['koordinat_tanah'] }}</div>
                        </div>
                        <button class="copy-btn" onclick="copyCoordinates()">
                            <i class="fas fa-copy"></i> Salin
                        </button>
                    </div>
                </div>
            @else
                <div class="no-map-placeholder">
                    <i class="fas fa-map-marker-alt"></i>
                    <h5>Koordinat Tidak Tersedia</h5>
                    <p>Data koordinat untuk bidang tanah ini belum tersedia</p>
                </div>
            @endif
        </div>

        <!-- Info Panel -->
        <div class="info-panel">
            <!-- Primary Info -->
            <div class="info-card">
                <div class="info-card-title">
                    <i class="fas fa-info-circle"></i>
                    Informasi Utama
                </div>
                <div class="info-item">
                    <div class="info-label">Nomor Bidang</div>
                    <div class="info-value">{{ $data['nomor_bidang'] }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">NIB</div>
                    <div class="info-value">{{ $data['nib'] }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Luas Tanah</div>
                    <div class="info-value large">{{ $data['luas_tanah'] }} m²</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        <span class="status-badge {{ $data['is_active'] == 1 ? 'status-active' : 'status-inactive' }}">
                            {{ $data['status_text'] }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Classification -->
            <div class="info-card">
                <div class="info-card-title">
                    <i class="fas fa-tags"></i>
                    Klasifikasi
                </div>
                <div class="info-item">
                    <div class="info-label">Jenis Tanah</div>
                    <div class="info-value">{{ $data['nama_jenis'] }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status Kepemilikan</div>
                    <div class="info-value">{{ $data['nama_status'] }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Wilayah</div>
                    <div class="info-value">{{ $data['nama_wilayah'] }}</div>
                    @if($data['kode_wilayah'] !== '-')
                        <small style="color: var(--text-secondary)">Kode: {{ $data['kode_wilayah'] }}</small>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    @if($data['koordinat_tanah'] !== '-')
    <div class="quick-actions">
        <div class="section-title">
            <i class="fas fa-bolt"></i>
            Aksi Cepat
        </div>
        <div class="actions-grid">
            <button class="action-btn" onclick="focusOnMap()">
                <i class="fas fa-crosshairs"></i>
                <span>Fokus Lokasi</span>
            </button>
            <button class="action-btn" onclick="openInGoogleMaps()">
                <i class="fab fa-google"></i>
                <span>Google Maps</span>
            </button>
            <button class="action-btn" onclick="shareLocation()">
                <i class="fas fa-share-nodes"></i>
                <span>Bagikan</span>
            </button>
            <button class="action-btn" onclick="showMapData()">
                <i class="fas fa-database"></i>
                <span>Data Peta</span>
            </button>
        </div>
    </div>
    @endif

    <!-- Boundary Information -->
    <div class="details-section">
        <div class="section-title">
            <i class="fas fa-border-all"></i>
            Batas Wilayah
        </div>
        <div class="boundary-compass">
            <div class="compass-center">
                <i class="fas fa-compass"></i>
            </div>
            <div class="boundary-point boundary-north">
                <div class="boundary-label">Utara</div>
                <div class="boundary-value">{{ $data['batas_utara'] }}</div>
            </div>
            <div class="boundary-point boundary-south">
                <div class="boundary-label">Selatan</div>
                <div class="boundary-value">{{ $data['batas_selatan'] }}</div>
            </div>
            <div class="boundary-point boundary-east">
                <div class="boundary-label">Timur</div>
                <div class="boundary-value">{{ $data['batas_timur'] }}</div>
            </div>
            <div class="boundary-point boundary-west">
                <div class="boundary-label">Barat</div>
                <div class="boundary-value">{{ $data['batas_barat'] }}</div>
            </div>
        </div>
    </div>

    <!-- Location Details -->
    <div class="details-section">
        <div class="section-title">
            <i class="fas fa-location-dot"></i>
            Informasi Lokasi
        </div>
        <div class="details-grid">
            <div class="detail-item">
                <div class="info-label">Alamat Lengkap</div>
                <div class="info-value">{{ $data['alamat_tanah'] }}</div>
            </div>
            @if($data['keterangan'] !== '-')
            <div class="detail-item">
                <div class="info-label">Keterangan</div>
                <div class="info-value">{{ $data['keterangan'] }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Metadata Footer -->
    <div class="metadata-footer">
        <div class="metadata-item">
            <i class="fas fa-calendar-plus"></i>
            <span>Dibuat: <strong>{{ $data['created_at'] }}</strong></span>
        </div>
        <div class="metadata-item">
            <i class="fas fa-calendar-check"></i>
            <span>Diperbarui: <strong>{{ $data['updated_at'] }}</strong></span>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="loading-overlay">
    <div class="spinner"></div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let map;
let polygon;
let centerMarker;
let boundaryMarkers = [];
let currentCoordinates;

// Data dari controller
const bidangTanahData = @json($data);

$(document).ready(function() {
    if (bidangTanahData.koordinat_tanah !== '-') {
        initializeMap();
    }
});

function parseCoordinate(coordStr) {
    if (!coordStr || coordStr === '-') return null;

    const parts = coordStr.replace(/\s+/g, '').split(',');
    if (parts.length !== 2) return null;

    const lat = parseFloat(parts[0]);
    const lng = parseFloat(parts[1]);

    return (!isNaN(lat) && !isNaN(lng)) ? [lat, lng] : null;
}

function initializeMap() {
    const centerCoord = parseCoordinate(bidangTanahData.koordinat_tanah);
    if (!centerCoord) return;

    currentCoordinates = { lat: centerCoord[0], lng: centerCoord[1] };

    // Initialize map
    map = L.map('map', {
        center: centerCoord,
        zoom: 16,
        zoomControl: true
    });

    // Street layer (default)
    const streetLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap',
        maxZoom: 19,
        id: 'street'
    }).addTo(map);

    const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: '© Esri',
        maxZoom: 19,
        id: 'satellite'
    });

    const terrainLayer = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenTopoMap',
        maxZoom: 17,
        id: 'terrain'
    });

    window.mapLayers = { street: streetLayer, satellite: satelliteLayer, terrain: terrainLayer };

    // Parse boundary coordinatesassssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss
    const boundaries = {
        north: parseCoordinate(bidangTanahData.batas_utara),
        south: parseCoordinate(bidangTanahData.batas_selatan),
        east: parseCoordinate(bidangTanahData.batas_timur),
        west: parseCoordinate(bidangTanahData.batas_barat)
    };

    const validBoundaries = Object.values(boundaries).filter(coord => coord !== null);

    if (validBoundaries.length >= 3) {
        // Draw polygon
        polygon = L.polygon(validBoundaries, {
            color: '#FF8C00',
            fillColor: '#FFD700',
            fillOpacity: 0.3,
            weight: 3
        }).addTo(map);

        // Add draggable markers for boundaries
        ['north', 'south', 'east', 'west'].forEach((direction, index) => {
            if (boundaries[direction]) {
                const markerIcon = L.divIcon({
                    className: 'custom-marker',
                    html: `<div class="custom-marker">${index + 1}</div>`,
                    iconSize: [36, 36],
                    iconAnchor: [18, 18]
                });

                const marker = L.marker(boundaries[direction], {
                    icon: markerIcon,
                    draggable: true,
                    title: direction.toUpperCase()
                }).addTo(map);

                marker.bindPopup(`<b>Batas ${direction.toUpperCase()}</b><br>${boundaries[direction][0].toFixed(6)}, ${boundaries[direction][1].toFixed(6)}`);

                marker.on('dragend', function() {
                    updatePolygon();
                });

                boundaryMarkers.push(marker);
            }
        });

        // Fit bounds
        map.fitBounds(polygon.getBounds(), { padding: [50, 50] });
    }

    // Add center marker
    const centerIcon = L.divIcon({
        html: '<div style="background: #e74c3c; border: 3px solid white; border-radius: 50%; width: 24px; height: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"></div>',
        iconSize: [24, 24],
        iconAnchor: [12, 12],
        className: ''
    });

    centerMarker = L.marker(centerCoord, { icon: centerIcon }).addTo(map);
    centerMarker.bindPopup(`
        <div style="text-align: center; padding: 8px;">
            <h6><strong>${bidangTanahData.nomor_bidang}</strong></h6>
            <p style="margin: 4px 0;">Luas: ${bidangTanahData.luas_tanah} m²</p>
            <p style="margin: 4px 0; font-size: 12px;">${centerCoord[0].toFixed(6)}, ${centerCoord[1].toFixed(6)}</p>
        </div>
    `);

    // Scale control
    L.control.scale({ imperial: false, metric: true }).addTo(map);
}

function updatePolygon() {
    if (polygon && boundaryMarkers.length > 0) {
        const newCoords = boundaryMarkers.map(marker => marker.getLatLng());
        polygon.setLatLngs(newCoords);
    }
}

function toggleMapType(type) {
    if (!map || !window.mapLayers) return;

    Object.values(window.mapLayers).forEach(layer => {
        if (map.hasLayer(layer)) map.removeLayer(layer);
    });

    if (window.mapLayers[type]) {
        window.mapLayers[type].addTo(map);
    }

    $('.map-type-btn').removeClass('active');
    $(`.map-type-btn[onclick*="${type}"]`).addClass('active');
}

function focusOnMap() {
    if (map && currentCoordinates) {
        map.setView([currentCoordinates.lat, currentCoordinates.lng], 18, {
            animate: true,
            duration: 1
        });
        if (centerMarker) {
            centerMarker.openPopup();
        }
    }
}

function openInGoogleMaps() {
    if (currentCoordinates) {
        const url = `https://www.google.com/maps?q=${currentCoordinates.lat},${currentCoordinates.lng}&z=18`;
        window.open(url, '_blank');
    } else {
        showAlert('warning', 'Koordinat tidak tersedia');
    }
}

function shareLocation() {
    if (!currentCoordinates) {
        showAlert('warning', 'Koordinat tidak tersedia');
        return;
    }

    const shareData = {
        title: `Lokasi ${bidangTanahData.nomor_bidang}`,
        text: `${bidangTanahData.alamat_tanah}\nKoordinat: ${currentCoordinates.lat}, ${currentCoordinates.lng}`,
        url: window.location.href
    };

    if (navigator.share) {
        navigator.share(shareData)
            .then(() => showAlert('success', 'Lokasi berhasil dibagikan!'))
            .catch(() => fallbackShare(shareData));
    } else {
        fallbackShare(shareData);
    }
}

function fallbackShare(shareData) {
    const textToCopy = `${shareData.title}\n${shareData.text}\n${shareData.url}`;
    copyToClipboard(textToCopy);
    showAlert('success', 'Informasi lokasi telah disalin ke clipboard!');
}

function copyCoordinates() {
    if (currentCoordinates) {
        const coordText = `${currentCoordinates.lat}, ${currentCoordinates.lng}`;
        copyToClipboard(coordText);
        showAlert('success', 'Koordinat telah disalin!');
    } else {
        showAlert('warning', 'Koordinat tidak tersedia');
    }
}

function copyToClipboard(text) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).catch(() => fallbackCopy(text));
    } else {
        fallbackCopy(text);
    }
}

function fallbackCopy(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    document.body.appendChild(textArea);
    textArea.select();
    try {
        document.execCommand('copy');
    } catch (err) {
        console.error('Copy failed:', err);
    }
    document.body.removeChild(textArea);
}

function showMapData() {
    if (!boundaryMarkers || boundaryMarkers.length === 0) {
        showAlert('info', 'Tidak ada data batas wilayah yang dapat ditampilkan');
        return;
    }

    const coords = boundaryMarkers.map((marker, index) => {
        const pos = marker.getLatLng();
        const labels = ['Utara', 'Selatan', 'Timur', 'Barat'];
        return `${labels[index] || 'Titik ' + (index + 1)}: ${pos.lat.toFixed(6)}, ${pos.lng.toFixed(6)}`;
    });

    const message = `Koordinat Peta Bidang Tanah\n\n` +
                   `Nomor Bidang: ${bidangTanahData.nomor_bidang}\n` +
                   `Luas: ${bidangTanahData.luas_tanah} m²\n\n` +
                   `Batas Wilayah:\n${coords.join('\n')}\n\n` +
                   `Koordinat Tengah: ${currentCoordinates.lat}, ${currentCoordinates.lng}`;

    alert(message);
}

function deleteData(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data bidang tanah ini?\n\nTindakan ini tidak dapat dibatalkan.')) {
        showLoading();

        $.ajax({
            url: `/bidang-tanah/${id}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                hideLoading();
                showAlert('success', 'Data berhasil dihapus!');
                setTimeout(() => {
                    window.location.href = '{{ route("bidang-tanah.index") }}';
                }, 1500);
            },
            error: function(xhr) {
                hideLoading();
                const message = xhr.responseJSON?.message || 'Gagal menghapus data!';
                showAlert('error', message);
            }
        });
    }
}

function showLoading() {
    $('#loadingOverlay').css('display', 'flex');
}

function hideLoading() {
    $('#loadingOverlay').hide();
}

function showAlert(type, message) {
    const alertTypes = {
        success: { class: 'alert-success', icon: 'fa-check-circle', color: '#27ae60' },
        error: { class: 'alert-danger', icon: 'fa-exclamation-circle', color: '#e74c3c' },
        warning: { class: 'alert-warning', icon: 'fa-exclamation-triangle', color: '#f39c12' },
        info: { class: 'alert-info', icon: 'fa-info-circle', color: '#3498db' }
    };

    const alertConfig = alertTypes[type] || alertTypes.info;

    const alertHtml = `
        <div class="alert ${alertConfig.class} alert-dismissible fade show" role="alert"
             style="position: fixed; top: 20px; right: 20px; z-index: 10000; min-width: 300px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15); border-left: 4px solid ${alertConfig.color};
                    border-radius: 8px; background: white;">
            <i class="fas ${alertConfig.icon}"></i> <strong>${message}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `;

    $('body').append(alertHtml);

    setTimeout(() => {
        $('.alert').fadeOut(400, function() {
            $(this).remove();
        });
    }, 5000);
}

// Handle window resize
$(window).on('resize', function() {
    if (map) {
        setTimeout(() => {
            map.invalidateSize();
        }, 100);
    }
});
</script>
@endpush

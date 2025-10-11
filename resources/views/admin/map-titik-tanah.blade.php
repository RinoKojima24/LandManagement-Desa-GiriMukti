@extends(Auth::user() && Auth::user()->role === 'admin' ? 'layouts.app' : 'layouts.mobile')

@section('title', 'Peta Titik Tanah')

@section('content')
<div class="map-container">
              <a href="{{ route('home') }}">
                <button class="back-btn" type="button">←</button>
            </a>
    <!-- Hero Header dengan Gradient -->
    <div class="hero-header">

        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-8 col-md-7">
                    <div class="header-content">
                        <div class="icon-wrapper">
                            <i class="fas fa-globe-asia"></i>
                        </div>
                        <div>

                            <h1 class="page-title mb-2">Peta Interaktif Bidang Tanah</h1>
                            <p class="page-subtitle mb-0">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                <span id="totalPointsHeader">{{ count($data) }}</span> Titik Lokasi Terdaftar
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-5 text-md-right mt-3 mt-md-0">
                    <div class="header-stats">
                        <div class="stat-card">
                            <div class="stat-icon bg-gradient-primary">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-value">{{ count($data) }}</span>
                                <span class="stat-label">Total Bidang</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Control Panel -->
    <div class="control-panel">
        <div class="container-fluid">
            <div class="row g-3">
                <div class="col-lg-3 col-md-6">
                    <div class="control-card">
                        <label class="control-label">
                            <i class="fas fa-search"></i> Cari Lokasi
                        </label>
                        <input type="text" id="searchInput" class="form-control control-input" placeholder="Cari berdasarkan ID...">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="control-card">
                        <label class="control-label">
                            <i class="fas fa-map"></i> Tipe Peta
                        </label>
                        <div class="btn-group-toggle w-100" role="group">
                            <button class="btn-map-type active" data-type="street" onclick="toggleMapType('street')">
                                <i class="fas fa-road"></i> Jalan
                            </button>
                            <button class="btn-map-type" data-type="satellite" onclick="toggleMapType('satellite')">
                                <i class="fas fa-satellite"></i> Satelit
                            </button>
                            <button class="btn-map-type" data-type="terrain" onclick="toggleMapType('terrain')">
                                <i class="fas fa-mountain"></i> Terrain
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="control-card">
                        <label class="control-label">
                            <i class="fas fa-filter"></i> Filter Tampilan
                        </label>
                        <select class="form-control control-input" id="filterSelect">
                            <option value="all">Semua Marker</option>
                            <option value="cluster">Cluster Mode</option>
                            <option value="heatmap">Heatmap Mode</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="control-card">
                        <label class="control-label">
                            <i class="fas fa-cog"></i> Aksi
                        </label>
                        <div class="action-buttons">
                            <button class="btn-action btn-primary" onclick="fitAllMarkers()" title="Tampilkan Semua">
                                <i class="fas fa-expand"></i>
                            </button>
                            <button class="btn-action btn-success" onclick="refreshMap()" title="Refresh">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                            <button class="btn-action btn-info" onclick="exportData()" title="Export Data">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="btn-action btn-warning" onclick="toggleFullscreen()" title="Fullscreen">
                                <i class="fas fa-expand-arrows-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Container dengan Sidebar -->
    <div class="map-content">
        <div class="container-fluid">
            <div class="row g-3">
                <!-- Sidebar List -->
                <div class="col-lg-4 col-xl-3">
                    <div class="sidebar-card">
                        <div class="sidebar-header">
                            <h5 class="sidebar-title">
                                <i class="fas fa-list-ul"></i> Daftar Lokasi
                            </h5>
                            <span class="badge badge-primary badge-pill">{{ count($data) }}</span>
                        </div>
                        <div class="sidebar-body">
                            <div class="location-list" id="locationList">
                                @foreach($data as $index => $item)
                                <div class="location-item" data-id="{{ $item['id_bidang_tanah'] }}" data-lat="{{ $item['latitude'] }}" data-lng="{{ $item['longitude'] }}" onclick="focusMarker({{ $item['latitude'] }}, {{ $item['longitude'] }}, {{ $item['id_bidang_tanah'] }})">
                                    <div class="location-number">{{ $index + 1 }}</div>
                                    <div class="location-info">
                                        <div class="location-id">
                                            <i class="fas fa-map-pin"></i> Bidang #{{ $item['id_bidang_tanah'] }}
                                        </div>
                                        <div class="location-coords">
                                            <small>
                                                <i class="fas fa-compass"></i>
                                                {{ number_format($item['latitude'], 6) }}, {{ number_format($item['longitude'], 6) }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="location-actions">
                                        <button class="btn-icon-sm" onclick="event.stopPropagation(); copyCoords({{ $item['latitude'] }}, {{ $item['longitude'] }})" title="Copy">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                        <a href="{{ route('bidang-tanah.show', $item['id_bidang_tanah']) }}" class="btn-icon-sm" onclick="event.stopPropagation()" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map Display -->
                <div class="col-lg-8 col-xl-9">
                    <div class="map-card">
                        <div class="map-overlay-info">
                            <div class="overlay-stat">
                                <i class="fas fa-eye"></i>
                                <span>Zoom: <strong id="zoomLevel">5</strong></span>
                            </div>
                            <div class="overlay-stat">
                                <i class="fas fa-crosshairs"></i>
                                <span>Center: <strong id="centerCoords">-</strong></span>
                            </div>
                        </div>
                        <div id="map"></div>
                        <div class="map-legend">
                            <div class="legend-title">
                                <i class="fas fa-info-circle"></i> Legenda
                            </div>
                            <div class="legend-items">
                                <div class="legend-item">
                                    <span class="legend-marker marker-red"></span>
                                    <span>Bidang Tanah</span>
                                </div>
                                <div class="legend-item">
                                    <span class="legend-marker marker-blue"></span>
                                    <span>Lokasi Terpilih</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-section">
        <div class="container-fluid">
            <div class="row g-3">
                <div class="col-lg-3 col-md-6">
                    <div class="stat-box">
                        <div class="stat-box-icon bg-gradient-info">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <div class="stat-box-content">
                            <h3 class="stat-box-value" id="visibleMarkers">{{ count($data) }}</h3>
                            <p class="stat-box-label">Marker Terlihat</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-box">
                        <div class="stat-box-icon bg-gradient-success">
                            <i class="fas fa-crosshairs"></i>
                        </div>
                        <div class="stat-box-content">
                            <h3 class="stat-box-value" id="selectedMarker">0</h3>
                            <p class="stat-box-label">Lokasi Dipilih</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-box">
                        <div class="stat-box-icon bg-gradient-warning">
                            <i class="fas fa-ruler-combined"></i>
                        </div>
                        <div class="stat-box-content">
                            <h3 class="stat-box-value" id="mapArea">-</h3>
                            <p class="stat-box-label">Area Tampilan</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-box">
                        <div class="stat-box-icon bg-gradient-danger">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-box-content">
                            <h3 class="stat-box-value" id="lastUpdate">Baru saja</h3>
                            <p class="stat-box-label">Terakhir Update</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="mt-3">Memuat peta...</p>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
:root {
--primary-color:  rgba(20, 184, 166, 0.95) 100%;
 --secondary-color: #3f37c9;
    --success-color: #06d6a0;
    --warning-color: #ffd60a;
    --danger-color: #ef476f;
    --info-color: #00b4d8;
    --dark-color: #1a1a2e;
    --light-color: #f8f9fa;
    --border-radius: 12px;
    --box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background: whitesmoke;
    min-height: 100vh;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.map-container {
    padding: 0;
    position: relative;
}

/* Hero Header */
.hero-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: white;
    padding: 2rem 0;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    position: relative;
    overflow: hidden;
}

.hero-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
    background-size: cover;
    opacity: 0.5;
}

.header-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    position: relative;
    z-index: 1;
}

.icon-wrapper {
    width: 70px;
    height: 70px;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
}

.page-subtitle {
    font-size: 1rem;
    opacity: 0.95;
    font-weight: 400;
}

.header-stats {
    position: relative;
    z-index: 1;
}

.stat-card {
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    border-radius: var(--border-radius);
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stat-value {
    display: block;
    font-size: 1.75rem;
    font-weight: 700;
    color: white;
    line-height: 1;
}

.stat-label {
    display: block;
    font-size: 0.875rem;
    color: rgba(255,255,255,0.9);
    margin-top: 0.25rem;
}

/* Control Panel */
.control-panel {
    background: white;
    padding: 1.5rem 0;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    position: sticky;
    top: 0;
    z-index: 999;
}

.control-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 1rem;
    border: 2px solid #f0f0f0;
    transition: var(--transition);
}

.control-card:hover {
    border-color: var(--primary-color);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(67, 97, 238, 0.15);
}

.control-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--dark-color);
    margin-bottom: 0.5rem;
}

.control-label i {
    color: var(--primary-color);
    margin-right: 0.5rem;
}

.control-input {
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    padding: 0.625rem 1rem;
    font-size: 0.9rem;
    transition: var(--transition);
}

.control-input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    outline: none;
}

.btn-group-toggle {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.5rem;
}

.btn-map-type {
    padding: 0.625rem;
    border: 2px solid #e0e0e0;
    background: white;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    color: var(--dark-color);
}

.btn-map-type:hover {
    border-color: var(--primary-color);
    background: rgba(67, 97, 238, 0.05);
}

.btn-map-type.active {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
}

.action-buttons {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.5rem;
}

.btn-action {
    padding: 0.625rem;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    cursor: pointer;
    transition: var(--transition);
    color: white;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
}

.btn-action.btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
}

.btn-action.btn-success {
    background: linear-gradient(135deg, #06d6a0, #00b894);
}

.btn-action.btn-info {
    background: linear-gradient(135deg, #00b4d8, #0096c7);
}

.btn-action.btn-warning {
    background: linear-gradient(135deg, #ffd60a, #ffc300);
}

/* Map Content */
.map-content {
    padding: 1.5rem 0;
}

/* Sidebar */
.sidebar-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    overflow: hidden;
    height: 600px;
    display: flex;
    flex-direction: column;
}

.sidebar-header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.sidebar-title {
    font-size: 1.1rem;
    font-weight: 700;
    margin: 0;
}

.sidebar-body {
    flex: 1;
    overflow: hidden;
}

.location-list {
    height: 100%;
    overflow-y: auto;
    padding: 0.5rem;
}

.location-list::-webkit-scrollbar {
    width: 8px;
}

.location-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.location-list::-webkit-scrollbar-thumb {
    background: var(--primary-color);
    border-radius: 10px;
}

.location-item {
    background: white;
    border: 2px solid #f0f0f0;
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    cursor: pointer;
    transition: var(--transition);
}

.location-item:hover {
    border-color: var(--primary-color);
    transform: translateX(5px);
    box-shadow: 0 4px 15px rgba(67, 97, 238, 0.2);
}

.location-item.active {
    border-color: var(--primary-color);
    background: linear-gradient(135deg, rgba(67, 97, 238, 0.1), rgba(63, 55, 201, 0.1));
}

.location-number {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1rem;
    flex-shrink: 0;
}

.location-info {
    flex: 1;
    min-width: 0;
}

.location-id {
    font-weight: 600;
    color: var(--dark-color);
    margin-bottom: 0.25rem;
    font-size: 0.95rem;
}

.location-coords {
    color: #666;
    font-size: 0.8rem;
}

.location-actions {
    display: flex;
    gap: 0.5rem;
    flex-shrink: 0;
}

.btn-icon-sm {
    width: 32px;
    height: 32px;
    border: 2px solid #e0e0e0;
    background: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition);
    color: var(--dark-color);
}

.btn-icon-sm:hover {
    border-color: var(--primary-color);
    color: var(--primary-color);
    background: rgba(67, 97, 238, 0.05);
}

/* Map Card */
.map-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    overflow: hidden;
    position: relative;
    height: 600px;
}

#map {
    width: 100%;
    height: 100%;
    z-index: 1;
}

.map-overlay-info {
    position: absolute;
    top: 20px;
    right: 20px;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.overlay-stat {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(10px);
    padding: 0.75rem 1rem;
    border-radius: 10px;
    font-size: 0.875rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.overlay-stat i {
    color: var(--primary-color);
}

.map-legend {
    position: absolute;
    bottom: 20px;
    left: 20px;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(10px);
    padding: 1rem;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    z-index: 1000;
}

.legend-title {
    font-weight: 700;
    font-size: 0.9rem;
    margin-bottom: 0.75rem;
    color: var(--dark-color);
}

.legend-items {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.85rem;
}

.legend-marker {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.marker-red {
    background: var(--danger-color);
}

.marker-blue {
    background: var(--primary-color);
}

/* Statistics Section */
.stats-section {
    padding: 1.5rem 0 2rem;
}

.stat-box {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    box-shadow: var(--box-shadow);
    display: flex;
    align-items: center;
    gap: 1.25rem;
    transition: var(--transition);
}

.stat-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 35px rgba(0,0,0,0.15);
}

.stat-box-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    color: white;
    flex-shrink: 0;
}

.bg-gradient-info {
    background: linear-gradient(135deg, #00b4d8, #0096c7);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #06d6a0, #00b894);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #ffd60a, #ffc300);
}

.bg-gradient-danger {
    background: linear-gradient(135deg, #ef476f, #d62828);
}

.stat-box-content {
    flex: 1;
}

.stat-box-value {
    font-size: 2rem;
    font-weight: 700;
    color: var(--dark-color);
    margin: 0;
    line-height: 1;
}

.stat-box-label {
    font-size: 0.9rem;
    color: #666;
    margin: 0.5rem 0 0;
}

/* Loading Overlay */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    opacity: 0;
    visibility: hidden;
    transition: var(--transition);
}

.loading-overlay.show {
    opacity: 1;
    visibility: visible;
}

.loading-content {
    text-align: center;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
    border-width: 4px;
}

/* Custom Popup */
.leaflet-popup-content-wrapper {
    border-radius: 12px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.2);
    padding: 0;
    overflow: hidden;
}

.leaflet-popup-content {
    margin: 0;
    min-width: 250px;
}

.marker-popup {
    padding: 1.5rem;
}

.marker-popup h6 {
    color: var(--primary-color);
    font-weight: 700;
    margin-bottom: 1rem;
    font-size: 1.1rem;
}

.marker-popup .btn {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border: none;
    border-radius: 8px;
    padding: 0.75rem;
    color: white;
    font-weight: 600;
    transition: var(--transition);
}

.marker-popup .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(67, 97, 238, 0.3);
}

/* Responsive Design */
@media (max-width: 991.98px) {
    .page-title {
        font-size: 1.5rem;
    }

    .header-content {
        flex-direction: column;
        text-align: center;
    }

    .control-panel {
        position: relative;
    }

    .sidebar-card {
        height: 400px;
        margin-bottom: 1rem;
    }

    .map-card {
        height: 500px;
    }
}

@media (max-width: 767.98px) {
    .hero-header {
        padding: 1.5rem 0;
    }

    .icon-wrapper {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
    }

    .page-title {
        font-size: 1.25rem;
    }

    .stat-card {
        padding: 1rem;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        font-size: 1.25rem;
    }

    .stat-value {
        font-size: 1.5rem;
    }

    .control-panel {
        padding: 1rem 0;
    }

    .btn-group-toggle {
        grid-template-columns: 1fr;
    }

    .action-buttons {
        grid-template-columns: repeat(2, 1fr);
    }

    .map-overlay-info {
        top: 10px;
        right: 10px;
    }

    .overlay-stat {
        padding: 0.5rem 0.75rem;
        font-size: 0.75rem;
    }

    .map-legend {
        bottom: 10px;
        left: 10px;
        padding: 0.75rem;
    }
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.location-item {
    animation: fadeInUp 0.3s ease-out;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

.location-item.active {
    animation: pulse 1s infinite;
}

/* Badge Custom */
.badge-pill {
    padding: 0.5em 1em;
    font-size: 0.85rem;
    font-weight: 600;
}

.badge-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
}

/* Leaflet Custom Styles */
.leaflet-control-zoom {
    border: none !important;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15) !important;
    border-radius: 10px !important;
    overflow: hidden;
}

.leaflet-control-zoom a {
    width: 35px !important;
    height: 35px !important;
    line-height: 35px !important;
    font-size: 18px !important;
    border: none !important;
    background: white !important;
    color: var(--primary-color) !important;
    transition: var(--transition) !important;
}

.leaflet-control-zoom a:hover {
    background: var(--primary-color) !important;
    color: white !important;
}

/* Marker Cluster Custom */
.marker-cluster {
    background: linear-gradient(135deg, rgba(67, 97, 238, 0.8), rgba(63, 55, 201, 0.8)) !important;
    border: 4px solid white !important;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3) !important;
}

.marker-cluster div {
    background: rgba(255,255,255,0.3) !important;
    color: white !important;
    font-weight: 700 !important;
    font-size: 14px !important;
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let map;
let markers = [];
let markerClusterGroup;
let selectedMarker = null;
const bidangData = @json($data);

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

$(document).ready(function() {
    showLoading();
    setTimeout(() => {
        initializeMap();
        initializeSearch();
        initializeFilter();
        updateStats();
        hideLoading();
    }, 800);
});

function showLoading() {
    $('#loadingOverlay').addClass('show');
}

function hideLoading() {
    $('#loadingOverlay').removeClass('show');
}

function initializeMap() {
    // Initialize map with better view
    map = L.map('map', {
        zoomControl: true,
        scrollWheelZoom: true,
        doubleClickZoom: true,
        dragging: true
    }).setView([-2.5489, 118.0149], 5);

    // Define tile layers
    const streetLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19,
        id: 'street'
    }).addTo(map);

    const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: '&copy; Esri',
        maxZoom: 19,
        id: 'satellite'
    });

    const terrainLayer = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenTopoMap',
        maxZoom: 17,
        id: 'terrain'
    });

    // Store layers
    window.mapLayers = {
        street: streetLayer,
        satellite: satelliteLayer,
        terrain: terrainLayer
    };

    // Initialize marker cluster group
    markerClusterGroup = L.markerClusterGroup({
        chunkedLoading: true,
        spiderfyOnMaxZoom: true,
        showCoverageOnHover: false,
        zoomToBoundsOnClick: true,
        maxClusterRadius: 80,
        iconCreateFunction: function(cluster) {
            const count = cluster.getChildCount();
            let size = 'small';
            if (count > 10) size = 'medium';
            if (count > 50) size = 'large';

            return L.divIcon({
                html: '<div><span>' + count + '</span></div>',
                className: 'marker-cluster marker-cluster-' + size,
                iconSize: L.point(40, 40)
            });
        }
    });

    // Add markers
    addAllMarkers();

    // Map event listeners
    map.on('zoomend', updateMapInfo);
    map.on('moveend', updateMapInfo);

    // Add scale control
    L.control.scale({
        position: 'bottomright',
        imperial: false
    }).addTo(map);

    // Initial map info update
    updateMapInfo();
}

function addAllMarkers() {
    markers = [];
    markerClusterGroup.clearLayers();

    const redIcon = L.icon({
        iconUrl: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAiIGhlaWdodD0iMzAiIHZpZXdCb3g9IjAgMCAzMCAzMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMTUiIGN5PSIxNSIgcj0iMTQiIGZpbGw9IiNFRjQ3NkYiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS13aWR0aD0iMiIvPgo8Y2lyY2xlIGN4PSIxNSIgY3k9IjE1IiByPSI2IiBmaWxsPSJ3aGl0ZSIvPgo8L3N2Zz4=',
        iconSize: [30, 30],
        iconAnchor: [15, 15],
        popupAnchor: [0, -15]
    });

    const blueIcon = L.icon({
        iconUrl: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAiIGhlaWdodD0iMzAiIHZpZXdCb3g9IjAgMCAzMCAzMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMTUiIGN5PSIxNSIgcj0iMTQiIGZpbGw9IiM0MzYxRUUiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS13aWR0aD0iMiIvPgo8Y2lyY2xlIGN4PSIxNSIgY3k9IjE1IiByPSI2IiBmaWxsPSJ3aGl0ZSIvPgo8L3N2Zz4=',
        iconSize: [35, 35],
        iconAnchor: [17.5, 17.5],
        popupAnchor: [0, -17.5]
    });

    bidangData.forEach((item, index) => {
        if (item.latitude && item.longitude) {
            const marker = L.marker([item.latitude, item.longitude], {
                icon: redIcon,
                id: item.id_bidang_tanah
            });

            const popupContent = `
                <div class="marker-popup">
                    <h6><i class="fas fa-map-pin"></i> Bidang Tanah #${item.id_bidang_tanah}</h6>
                    <div class="mb-3">
                        <p class="mb-2"><strong><i class="fas fa-compass"></i> Koordinat:</strong><br>
                        ${parseFloat(item.latitude).toFixed(6)}, ${parseFloat(item.longitude).toFixed(6)}</p>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-sm" onclick="copyCoords(${item.latitude}, ${item.longitude})">
                            <i class="fas fa-copy"></i> Salin Koordinat
                        </button>
                        <a href="/bidang-tanah/${item.id_bidang_tanah}" class="btn btn-sm">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            `;

            marker.bindPopup(popupContent, {
                maxWidth: 300,
                className: 'custom-popup'
            });

            marker.on('click', function() {
                highlightLocation(item.id_bidang_tanah);
                updateSelectedMarker(item.id_bidang_tanah);
            });

            markerClusterGroup.addLayer(marker);
            markers.push({marker: marker, data: item, icon: {red: redIcon, blue: blueIcon}});
        }
    });

    map.addLayer(markerClusterGroup);

    // Fit bounds if markers exist
    if (markers.length > 0) {
        setTimeout(() => {
            const group = L.featureGroup(markers.map(m => m.marker));
            map.fitBounds(group.getBounds().pad(0.1));
        }, 300);
    }
}

function focusMarker(lat, lng, id) {
    map.setView([lat, lng], 16, {
        animate: true,
        duration: 1
    });

    // Find and open popup
    markers.forEach(item => {
        if (item.data.id_bidang_tanah === id) {
            setTimeout(() => {
                item.marker.openPopup();
            }, 500);
        }
    });

    highlightLocation(id);
    updateSelectedMarker(id);

    Toast.fire({
        icon: 'success',
        title: 'Fokus ke lokasi #' + id
    });

    // Scroll to map on mobile
    if (window.innerWidth < 992) {
        $('html, body').animate({
            scrollTop: $("#map").offset().top - 100
        }, 500);
    }
}

function highlightLocation(id) {
    $('.location-item').removeClass('active');
    $(`.location-item[data-id="${id}"]`).addClass('active');

    // Scroll to active item
    const activeItem = $(`.location-item[data-id="${id}"]`);
    if (activeItem.length) {
        const container = $('.location-list');
        const scrollTo = activeItem.position().top + container.scrollTop() - 100;
        container.animate({scrollTop: scrollTo}, 300);
    }
}

function updateSelectedMarker(id) {
    // Reset all markers to red
    markers.forEach(item => {
        item.marker.setIcon(item.icon.red);
    });

    // Set selected marker to blue
    const selectedItem = markers.find(item => item.data.id_bidang_tanah === id);
    if (selectedItem) {
        selectedItem.marker.setIcon(selectedItem.icon.blue);
        selectedMarker = id;
        $('#selectedMarker').text('1');
    }
}

function toggleMapType(type) {
    if (!map || !window.mapLayers) return;

    Object.values(window.mapLayers).forEach(layer => {
        map.removeLayer(layer);
    });

    if (window.mapLayers[type]) {
        window.mapLayers[type].addTo(map);
    }

    $('.btn-map-type').removeClass('active');
    $(`.btn-map-type[data-type="${type}"]`).addClass('active');

    const typeText = {street: 'Jalan', satellite: 'Satelit', terrain: 'Terrain'}[type];
    Toast.fire({
        icon: 'info',
        title: `Tampilan: ${typeText}`
    });
}

function fitAllMarkers() {
    if (markers.length > 0) {
        const group = L.featureGroup(markers.map(m => m.marker));
        map.fitBounds(group.getBounds().pad(0.1), {
            animate: true,
            duration: 1
        });

        Toast.fire({
            icon: 'success',
            title: 'Menampilkan semua marker'
        });
    }
}

function refreshMap() {
    showLoading();

    setTimeout(() => {
        map.invalidateSize();
        fitAllMarkers();
        updateStats();
        hideLoading();

        Toast.fire({
            icon: 'success',
            title: 'Peta berhasil di-refresh'
        });
    }, 500);
}

function exportData() {
    const csvContent = "data:text/csv;charset=utf-8,"
        + "ID,Latitude,Longitude\n"
        + bidangData.map(item => `${item.id_bidang_tanah},${item.latitude},${item.longitude}`).join("\n");

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `bidang_tanah_${new Date().getTime()}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    Toast.fire({
        icon: 'success',
        title: 'Data berhasil diexport'
    });
}

function toggleFullscreen() {
    const elem = document.documentElement;

    if (!document.fullscreenElement) {
        elem.requestFullscreen().then(() => {
            Toast.fire({
                icon: 'success',
                title: 'Mode fullscreen aktif'
            });
            setTimeout(() => map.invalidateSize(), 100);
        });
    } else {
        document.exitFullscreen().then(() => {
            Toast.fire({
                icon: 'info',
                title: 'Keluar dari fullscreen'
            });
            setTimeout(() => map.invalidateSize(), 100);
        });
    }
}

function copyCoords(lat, lng) {
    const text = `${lat}, ${lng}`;
    navigator.clipboard.writeText(text).then(() => {
        Toast.fire({
            icon: 'success',
            title: 'Koordinat berhasil disalin'
        });
    });
}

function initializeSearch() {
    $('#searchInput').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();

        $('.location-item').each(function() {
            const id = $(this).data('id').toString().toLowerCase();
            const lat = $(this).data('lat').toString().toLowerCase();
            const lng = $(this).data('lng').toString().toLowerCase();

            if (id.includes(searchTerm) || lat.includes(searchTerm) || lng.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });

        const visibleCount = $('.location-item:visible').length;
        $('#visibleMarkers').text(visibleCount);
    });
}

function initializeFilter() {
    $('#filterSelect').on('change', function() {
        const filter = $(this).val();

        map.removeLayer(markerClusterGroup);

        if (filter === 'cluster') {
            map.addLayer(markerClusterGroup);
            Toast.fire({icon: 'info', title: 'Mode: Cluster'});
        } else if (filter === 'all') {
            markers.forEach(item => item.marker.addTo(map));
            Toast.fire({icon: 'info', title: 'Mode: Semua Marker'});
        } else if (filter === 'heatmap') {
            map.addLayer(markerClusterGroup);
            Toast.fire({icon: 'info', title: 'Mode: Heatmap (Coming Soon)'});
        }
    });
}

function updateMapInfo() {
    const zoom = map.getZoom();
    const center = map.getCenter();

    $('#zoomLevel').text(zoom);
    $('#centerCoords').text(`${center.lat.toFixed(4)}, ${center.lng.toFixed(4)}`);

    const bounds = map.getBounds();
    const area = calculateArea(bounds);
    $('#mapArea').text(area);
}

function calculateArea(bounds) {
    const ne = bounds.getNorthEast();
    const sw = bounds.getSouthWest();
    const width = ne.distanceTo(L.latLng(sw.lat, ne.lng));
    const height = ne.distanceTo(L.latLng(ne.lat, sw.lng));
    const area = (width * height) / 1000000; // km²

    return area < 1 ? '<1 km²' : area.toFixed(0) + ' km²';
}

function updateStats() {
    $('#visibleMarkers').text(markers.length);
    $('#totalPointsHeader').text(markers.length);
    $('#lastUpdate').text('Baru saja');
}

// Resize handler
$(window).resize(function() {
    if (map) {
        clearTimeout(window.resizeTimer);
        window.resizeTimer = setTimeout(() => map.invalidateSize(), 250);
    }
});

// Connection status
window.addEventListener('online', () => {
    Toast.fire({icon: 'success', title: 'Koneksi tersambung'});
});

window.addEventListener('offline', () => {
    Toast.fire({icon: 'warning', title: 'Koneksi terputus'});
});

console.log('🗺️ Map initialized with', bidangData.length, 'points');
</script>
@endpush

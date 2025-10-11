@extends('layouts.app')

@section('title', 'Data Bidang Tanah')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Bidang Tanah</h3>
                    <div class="card-tools">
                        <a href="{{ route('bidang-tanah.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Bidang Tanah
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="bidangTanahTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Bidang</th>
                                    <th>NIB</th>
                                    <th>Luas Tanah (m²)</th>
                                    <th>Alamat</th>
                                    <th>Jenis Tanah</th>
                                    <th>Status Kepemilikan</th>
                                    <th>Wilayah</th>
                                    <th>Koordinat</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data akan dimuat via AJAX oleh DataTables -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Bidang Tanah</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detailContent">
                <!-- Detail content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Peta -->
<div class="modal fade" id="mapModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lokasi Bidang Tanah</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="alert alert-info" id="koordinatInfo">
                            <strong>Koordinat:</strong> <span id="currentCoordinates">-</span>
                        </div>
                    </div>
                </div>
                <div id="map" style="height: 500px; width: 100%;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="openInGoogleMaps()">
                    <i class="fas fa-external-link-alt"></i> Buka di Google Maps
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<style>
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    .btn-group .btn {
        margin-right: 2px;
    }
    .badge {
        font-size: 0.75em;
    }
    .leaflet-container {
        height: 500px;
        width: 100%;
    }
    #mapModal .modal-dialog {
        max-width: 90%;
    }
    .coordinate-display {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 15px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
let map;
let currentMarker;
let currentCoordinates;

$(document).ready(function() {
    $('#bidangTanahTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "{{ route('bidang-tanah.index') }}",
            "type": "GET"
        },
        "columns": [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'nomor_bidang', name: 'bt.nomor_bidang'},
            {data: 'nib', name: 'bt.nib'},
            {data: 'luas_formatted', name: 'bt.luas_tanah', orderable: true, searchable: false},
            {data: 'alamat_tanah', name: 'bt.alamat_tanah'},
            {data: 'nama_jenis', name: 'nama_jenis', defaultContent: '-'},
            {data: 'nama_status', name: 'nama_status', defaultContent: '-'},
            {data: 'nama_wilayah', name: 'nama_wilayah', defaultContent: '-'},
            {data: 'koordinat_button', name: 'koordinat_button', orderable: false, searchable: false},
            {data: 'status_badge', name: 'bt.is_active', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json"
        },
        "order": [[1, 'asc']],
        "pageLength": 25,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]]
    });

    // Initialize map when modal is shown
    $('#mapModal').on('shown.bs.modal', function () {
        if (!map) {
            initializeMap();
        }
        // Invalidate size to fix display issues
        setTimeout(function() {
            map.invalidateSize();
        }, 100);
    });
});

function initializeMap() {
    // Default coordinates (Samarinda, East Kalimantan)
    const defaultLat = -0.5017;
    const defaultLng = 117.1536;

    map = L.map('map').setView([defaultLat, defaultLng], 13);

    // Add tile layer (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
}

function viewDetail(id) {
    // Ajax request to get detail data
    $.ajax({
        url: '/bidang-tanah/' + id,
        type: 'GET',
        success: function(response) {
            let html = `
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr><td><strong>Nomor Bidang:</strong></td><td>${response.nomor_bidang}</td></tr>
                            <tr><td><strong>NIB:</strong></td><td>${response.nib}</td></tr>
                            <tr><td><strong>Luas Tanah:</strong></td><td>${response.luas_tanah} m²</td></tr>
                            <tr><td><strong>Alamat:</strong></td><td>${response.alamat_tanah}</td></tr>
                            <tr><td><strong>Jenis Tanah:</strong></td><td>${response.nama_jenis || '-'}</td></tr>
                            <tr><td><strong>Status Kepemilikan:</strong></td><td>${response.nama_status || '-'}</td></tr>
                            <tr><td><strong>Wilayah:</strong></td><td>${response.nama_wilayah || '-'}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr><td><strong>Koordinat:</strong></td><td>${response.koordinat_tanah || '-'}</td></tr>
                            <tr><td><strong>Batas Utara:</strong></td><td>${response.batas_utara || '-'}</td></tr>
                            <tr><td><strong>Batas Selatan:</strong></td><td>${response.batas_selatan || '-'}</td></tr>
                            <tr><td><strong>Batas Timur:</strong></td><td>${response.batas_timur || '-'}</td></tr>
                            <tr><td><strong>Batas Barat:</strong></td><td>${response.batas_barat || '-'}</td></tr>
                            <tr><td><strong>Keterangan:</strong></td><td>${response.keterangan || '-'}</td></tr>
                            <tr><td><strong>Status:</strong></td><td>${response.is_active == 1 ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>'}</td></tr>
                        </table>
                    </div>
                </div>
            `;
            $('#detailContent').html(html);
            $('#detailModal').modal('show');
        },
        error: function() {
            alert('Gagal memuat detail data');
        }
    });
}

function editData(id) {
    // Redirect to edit page or open edit modal
    window.location.href = '/bidang-tanah/' + id + '/edit';
}

function deleteData(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        $.ajax({
            url: '/bidang-tanah/' + id,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                alert('Data berhasil dihapus');
                $('#bidangTanahTable').DataTable().ajax.reload();
            },
            error: function() {
                alert('Gagal menghapus data');
            }
        });
    }
}

function showMap(koordinat, nomorBidang, alamat) {
    if (!koordinat || koordinat === '-') {
        alert('Koordinat tidak tersedia untuk bidang tanah ini');
        return;
    }

    // Parse coordinates (assuming format: "latitude,longitude" or "latitude, longitude")
    const coords = koordinat.toString().replace(/\s+/g, '').split(',');

    if (coords.length !== 2) {
        alert('Format koordinat tidak valid. Format yang benar: latitude,longitude');
        return;
    }

    const lat = parseFloat(coords[0]);
    const lng = parseFloat(coords[1]);

    if (isNaN(lat) || isNaN(lng)) {
        alert('Koordinat tidak valid');
        return;
    }

    // Store current coordinates for Google Maps link
    currentCoordinates = { lat: lat, lng: lng };

    // Update coordinate display
    $('#currentCoordinates').text(`${lat}, ${lng}`);

    // Show modal
    $('#mapModal').modal('show');

    // Wait for modal to be fully shown before manipulating map
    $('#mapModal').on('shown.bs.modal', function () {
        // Initialize map if not exists
        if (!map) {
            initializeMap();
        }

        // Set map view to the coordinates
        map.setView([lat, lng], 16);

        // Remove existing marker if any
        if (currentMarker) {
            map.removeLayer(currentMarker);
        }

        // Add new marker
        currentMarker = L.marker([lat, lng]).addTo(map);

        // Create popup content
        let popupContent = `
            <div class="text-center">
                <strong>${nomorBidang || 'Bidang Tanah'}</strong><br>
                ${alamat || 'Alamat tidak tersedia'}<br>
                <small class="text-muted">Koordinat: ${lat}, ${lng}</small>
            </div>
        `;

        currentMarker.bindPopup(popupContent).openPopup();

        // Invalidate size to fix display issues
        setTimeout(function() {
            map.invalidateSize();
        }, 100);
    });
}

function openInGoogleMaps() {
    if (currentCoordinates) {
        const url = `https://www.google.com/maps?q=${currentCoordinates.lat},${currentCoordinates.lng}`;
        window.open(url, '_blank');
    } else {
        alert('Koordinat tidak tersedia');
    }
}

// Alternative function to show coordinates in different formats
function parseCoordinates(koordinat) {
    // Handle different coordinate formats
    let coords;

    // Check if it's in DMS format (Degrees Minutes Seconds)
    if (koordinat.includes('°')) {
        // Convert DMS to decimal degrees
        coords = convertDMSToDecimal(koordinat);
    } else if (koordinat.includes(',')) {
        // Decimal degrees separated by comma
        coords = koordinat.replace(/\s+/g, '').split(',');
    } else if (koordinat.includes(' ')) {
        // Decimal degrees separated by space
        coords = koordinat.trim().split(/\s+/);
    } else {
        return null;
    }

    if (coords && coords.length === 2) {
        const lat = parseFloat(coords[0]);
        const lng = parseFloat(coords[1]);

        if (!isNaN(lat) && !isNaN(lng)) {
            return { lat: lat, lng: lng };
        }
    }

    return null;
}

function convertDMSToDecimal(dms) {
    // Basic DMS to decimal conversion
    // This is a simplified version - you might need to adjust based on your coordinate format
    const regex = /(\d+)°(\d+)'([\d.]+)"/g;
    const matches = dms.matchAll(regex);
    const coords = [];

    for (const match of matches) {
        const degrees = parseFloat(match[1]);
        const minutes = parseFloat(match[2]);
        const seconds = parseFloat(match[3]);

        const decimal = degrees + (minutes / 60) + (seconds / 3600);
        coords.push(decimal);
    }

    return coords;
}
</script>
@endpush

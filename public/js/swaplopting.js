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

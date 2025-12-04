@extends('layouts.mobile')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <style>
        #map {
            width: 100%;
            height: 400px;
        }
    </style>

{{-- @dd($peta); --}}


<div class="container-fluid px-3 py-4" style="background-color: #f5f5f5; min-height: 100vh;">
    <!-- Header -->
    <div class="mb-4">
        <div class="d-flex justify-content-between">
            <div class="d-flex align-items-center mb-3">
                <a href="{{ url('tanah') }}" class="text-decoration-none text-dark me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h5 class="mb-0 fw-bold">Detail Data Peta</h5>
            </div>
            <div>
                <a href="{{ url('tanah/'.$peta->id.'/edit') }}" class="btn btn-primary">Edit Data</a>
            </div>
        </div>
    </div>

    <!-- Cards Container -->
    <div class="card">
        <div class="card-body">
            <img src="{{ url('storage/'.$peta->foto_peta) }}" class="d-block mx-auto" alt="">
            <div class="d-flex justify-content-between">
                <div>
                    <span style="font-size: 14px;"><b>{{ $peta->SuratPermohonan->nama_lengkap ?? ""}}</b></span><br>
                    <span style="font-size: 13px;">{{ $peta->nomor_bidang }}</span>
                </div>
                <div>
                    <span>.</span>
                </div>
                <div>
                    <span class="" style="font-size: 14px;">Peruntukan : {{ $peta->peruntukan }}<br>Status : {{ $peta->status }}</span>
                </div>
            </div>
            <br>
            <hr>
            <br>
            <div class="d-flex justify-content-between">
                <div>
                    <label for="" style="font-size: 14px;">Panjang</label><br>
                    <span style="font-size: 13px;">{{ $peta->panjang }} m</span>
                </div>
                <div>
                    <label for="" style="font-size: 14px;">Lebar</label><br>
                    <span style="font-size: 13px;">{{ $peta->lebar }} m</span>
                </div>
                <div>
                    <label for="" style="font-size: 14px;">Luas</label><br>
                    <span style="font-size: 13px;">{{ $peta->luas }} m<sup>2</sup></span>
                </div>
            </div>
            <br>
            @php
                $kordinat = explode(',', ($peta->titik_kordinat == "-" ? '0,0' : $peta->titik_kordinat));
                // $kordinat_1 = explode(',', $peta->titik_kordinat_1);
                // $kordinat_2 = explode(',', $peta->titik_kordinat_2);
                // $kordinat_3 = explode(',', $peta->titik_kordinat_3);
                // $kordinat_4 = explode(',', $peta->titik_kordinat_4);

            @endphp
            <div id="map"></div>

            <br>
            {{-- <div class="d-flex justify-content-between">
                <div>
                    <label for="" style="font-size: 14px;">Titik Kordinat</label><br>
                    <span style="font-size: 13px;">{{ $peta->titik_kordinat }}</span>
                </div>
            </div> --}}
            {{-- <br> --}}
            <hr>
                @php
                    $poly_text = $peta->titik_kordinat_polygon;
                    // dd($poly_text);
                    $poly =   explode("\r\n", $peta->titik_kordinat_polygon);
                    // dd($poly);

                @endphp
                {{-- @foreach ($poly as $index => $value)
                    <div>
                        <label for="" style="font-size: 14px;">Titik Kordinat {{ $index }}</label><br>
                        <span style="font-size: 13px;">{{ $value }}</span>
                    </div>
                @endforeach --}}
            <br>
            {{-- <hr><br> --}}
            <div>
                <label for="" style="font-size: 13px;">Peta Tanah</label><br>
                <span style="font-size: 14px;">Tanggal Pengukuran : {{ $peta->tanggal_pengukuran }}</span>
            </div>
        </div>
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
    // buat map
    const map = L.map('map').setView([0, 0], 2);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OSM'
    }).addTo(map);

    // load GeoJSON
    fetch('{{ asset("$peta->titik_kordinat_polygon") }}')
      .then(res => res.json())
      .then(geojson => {
        const layer = L.geoJSON(geojson, {
          style: feature => ({
            color: '#3388ff',
            weight: 2,
            fillOpacity: 0.4
          }),
          onEachFeature: (feature, layer) => {
            // popup menampilkan atribut (dbf fields)
            if (feature.properties) {
              const props = feature.properties;
              let html = '<table>';
              for (const key in props) {
                html += `<tr><th>${key}</th><td>${props[key]}</td></tr>`;
              }
              html += '</table>';
              layer.bindPopup(html);
            }
          }
        }).addTo(map);

        // zoom ke bounds layer
        map.fitBounds(layer.getBounds());
      })
      .catch(err => console.error('Gagal load GeoJSON:', err));
  </script>

@endsection
